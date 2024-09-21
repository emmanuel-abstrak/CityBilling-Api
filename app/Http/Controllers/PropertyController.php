<?php

namespace App\Http\Controllers;

use App\Http\Requests\Properties\PropertyCreateRequest;
use App\Http\Requests\Properties\PropertyUpdateRequest;
use App\Http\Resources\PropertyResource;
use App\Http\Responses\ActionResponse;
use App\Library\Enums\UserRole;
use App\Mail\SendOwnerPropertyCreatedMail;
use App\Models\Property;
use App\Repositories\Properties\IPropertyRepository;
use App\Repositories\TariffGroups\ITariffGroupRepository;
use App\Repositories\Users\IUserRepository;
use App\ServiceProviders\Shared\IServiceProvider;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PropertyController extends Controller
{
    public function __construct(
        private readonly IPropertyRepository $propertyRepository,
        private readonly IUserRepository $userRepository,
        private readonly ITariffGroupRepository $tariffGroupRepository,
        private readonly IServiceProvider $serviceProvider,
    ){}

    public function index(): JsonResponse
    {
        $properties = $this->propertyRepository->getAll(request()->query());
        $properties->getCollection()->transform(fn ($property) =>  PropertyResource::toArray($property));

        return ActionResponse::ok(paginated_response($properties));
    }

    /**
     * @throws Exception
     */
    public function store(PropertyCreateRequest $request): JsonResponse
    {
        Gate::authorize('create', Property::class);
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $tariffGroup = $this->tariffGroupRepository->getBySizeAndSuburb($validated['size'], $validated['suburb_id']);
            if (!$tariffGroup) {
                return ActionResponse::badRequest('Tariff group not found');
            }

            $meterDetail = $this->serviceProvider->lookupNewMeter($validated['meter']);

            if (!$meterDetail) {
                return ActionResponse::badRequest('Meter provider not found');
            }

            $user = $this->userRepository->getByEmail($validated['email']);
            if (!$user) {
                $user = $this->userRepository->create([
                    'role' => UserRole::user->value,
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['phone_number']),
                    'phone_number' => $validated['phone_number'],
                    'id_number' => $validated['id_number'],
                ]);
            }

            $property = $this->propertyRepository->create([
                'suburb_id' => $validated['suburb_id'],
                'owner_id' => $user->getAttribute('id'),
                'tariff_group_id' => $tariffGroup->getAttribute('id'),
                'size' => $validated['size'],
                'meter' => $validated['meter'],
                'meter_provider' => $meterDetail->getProvider(),
                'type_id' => $validated['type_id'],
                'address' => $validated['address'],
            ]);

            DB::commit();
            if ($validated['send_notification']) {
                /** @var Property $property */
                Mail::to($user->getAttribute('email'))
                    ->send(new SendOwnerPropertyCreatedMail($user, $property));
            }
            return ActionResponse::ok(PropertyResource::toArray($property));
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function update(PropertyUpdateRequest $request, int $id): JsonResponse
    {
        Gate::authorize('update', Property::class);
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $tariffGroup = $this->tariffGroupRepository->getBySizeAndSuburb($validated['size'], $validated['suburb_id']);
            if (!$tariffGroup) {
                return ActionResponse::badRequest('Tariff group not found');
            }

            $property = $this->propertyRepository->getById($id);

            $updateData = [
                'suburb_id' => $validated['suburb_id'],
                'tariff_group_id' => $tariffGroup->getAttribute('id'),
                'size' => $validated['size'],
                'meter' => $validated['meter'],
                'type_id' => $validated['type_id'],
                'address' => $validated['address'],
            ];

            if ($property->getAttribute('meter') != $validated['meter']) {
                $meterDetail = $this->serviceProvider->lookupNewMeter($validated['meter']);

                if (!$meterDetail) {
                    return ActionResponse::badRequest('Meter provider not found');
                }

                $updateData['meter_provider'] = $meterDetail->getProvider();
            }

            $this->userRepository->update($property->getAttribute('owner_id'), [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'id_number' => $validated['id_number'],
            ]);

            $property = $this->propertyRepository->update($id, $updateData);

            DB::commit();
            return ActionResponse::ok(PropertyResource::toArray($property));
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
