<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreGpsPointsRequest;
use App\Http\Resources\Api\V1\GpsPointResource;
use App\Services\Field\FieldGpsService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GpsPointController extends Controller
{
    public function store(StoreGpsPointsRequest $request, FieldGpsService $gpsService): AnonymousResourceCollection
    {
        $points = $gpsService->storeMany(
            $request->user(),
            $request->validated(),
        );

        return GpsPointResource::collection($points);
    }
}
