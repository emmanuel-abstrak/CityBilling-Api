<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use App\Http\Responses\ActionResponse;
use App\Models\Activity;
use App\Models\User;
use App\Repositories\Activities\IActivityRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ActivityLogController extends Controller
{
    public function __construct(private readonly IActivityRepository $activityRepository){}

    public function index(): JsonResponse
    {
        Gate::authorize('view', Activity::class);
        $activities = $this->activityRepository->getAll(request()->query());
        $activities->getCollection()->transform(fn ($activity) =>  ActivityResource::toArray($activity));

        return ActionResponse::ok(paginated_response($activities));
    }
}
