<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use App\Http\Responses\ActionResponse;
use App\Repositories\Activities\IActivityRepository;
use App\Repositories\Properties\IPropertyRepository;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        private readonly IPropertyRepository $propertyRepository,
        private readonly IActivityRepository $activityRepository
    ){}

    public function properties(): JsonResponse
    {
        $count = $this->propertyRepository->getAll()->count();
        return ActionResponse::ok(number_format($count));
    }

    public function balances(): JsonResponse
    {
        $balances = $this->propertyRepository->getCurrentMonthBalanceTotal();
        return ActionResponse::ok(money_currency($balances));
    }

    public function activities(): JsonResponse
    {
        $activities = $this->activityRepository->getAll(['limit' => 4]);
        $activities->getCollection()->transform(fn ($activity) =>  ActivityResource::toArray($activity));
        return ActionResponse::ok(paginated_response($activities));
    }
}
