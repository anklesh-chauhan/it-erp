<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreVisitPhotoRequest;
use App\Http\Requests\Api\V1\VisitCheckInRequest;
use App\Http\Requests\Api\V1\VisitCheckOutRequest;
use App\Http\Resources\Api\V1\MediaResource;
use App\Http\Resources\Api\V1\VisitResource;
use App\Services\Field\FieldVisitService;
use App\Services\Visit\VisitEnforcementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function __construct(
        private FieldVisitService $visitService,
        private VisitEnforcementService $enforcement,
    ) {}

    public function show(Request $request, int $visit): VisitResource
    {
        $record = $this->visitService->findOwned($request->user(), $visit);
        $record->needs_check_in_image = $this->enforcement->needsCheckInImage($record);
        $record->needs_check_out_image = $this->enforcement->needsCheckOutImage($record);

        return new VisitResource($record);
    }

    public function checkIn(VisitCheckInRequest $request, int $visit): VisitResource
    {
        $record = $this->visitService->findOwned($request->user(), $visit);
        $record = $this->visitService->checkIn($request->user(), $record, $request->validated());
        $record->needs_check_in_image = $this->enforcement->needsCheckInImage($record);
        $record->needs_check_out_image = $this->enforcement->needsCheckOutImage($record);

        return new VisitResource($record);
    }

    public function checkOut(VisitCheckOutRequest $request, int $visit): JsonResponse
    {
        $record = $this->visitService->findOwned($request->user(), $visit);
        $result = $this->visitService->checkOut($request->user(), $record, $request->validated());

        $visitModel = $result['visit'];
        $visitModel->needs_check_in_image = $this->enforcement->needsCheckInImage($visitModel);
        $visitModel->needs_check_out_image = $result['needs_checkout_image']
            || $this->enforcement->needsCheckOutImage($visitModel);

        return response()->json([
            'data' => new VisitResource($visitModel),
            'meta' => [
                'needs_checkout_image' => $result['needs_checkout_image'],
            ],
        ]);
    }

    public function storePhoto(StoreVisitPhotoRequest $request, int $visit): MediaResource
    {
        $record = $this->visitService->findOwned($request->user(), $visit);

        $media = $this->visitService->storePhoto(
            $request->user(),
            $record,
            $request->file('photo'),
            $request->validated(),
        );

        return new MediaResource($media);
    }
}
