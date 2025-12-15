<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NationalityRequest;
use App\Http\Resources\NationalityResource;
use App\Services\NationalityService;


class NationalityController extends Controller
{
    /**
     * @var NationalityService
     */
    protected NationalityService $nationalityService;

    /**
     * DummyModel Constructor
     *
     * @param NationalityService $nationalityService
     *
     */
    public function __construct(NationalityService $nationalityService)
    {
        $this->nationalityService = $nationalityService;
    }


    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return NationalityResource::collection($this->nationalityService->getAll());
    }


    public function store(NationalityRequest $request): NationalityResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new NationalityResource($this->nationalityService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): NationalityResource
    {
        return NationalityResource::make($this->nationalityService->getById($id));
    }


    public function update(NationalityRequest $request, int $id): NationalityResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new NationalityResource($this->nationalityService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->nationalityService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
