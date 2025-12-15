<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MunicipalityRequest;
use App\Http\Resources\MunicipalityResource;
use App\Services\MunicipalityService;


class MunicipalityController extends Controller
{
    /**
     * @var MunicipalityService
     */
    protected MunicipalityService $municipalityService;

    /**
     * DummyModel Constructor
     *
     * @param MunicipalityService $municipalityService
     *
     */
    public function __construct(MunicipalityService $municipalityService)
    {
        $this->municipalityService = $municipalityService;
    }


    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return MunicipalityResource::collection($this->municipalityService->getAll());
    }


    public function store(MunicipalityRequest $request): MunicipalityResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new MunicipalityResource($this->municipalityService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): MunicipalityResource
    {
        return MunicipalityResource::make($this->municipalityService->getById($id));
    }


    public function update(MunicipalityRequest $request, int $id): MunicipalityResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new MunicipalityResource($this->municipalityService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->municipalityService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
