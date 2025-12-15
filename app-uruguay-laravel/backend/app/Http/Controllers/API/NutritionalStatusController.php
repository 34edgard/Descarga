<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NutritionalStatusRequest;
use App\Http\Resources\NutritionalStatusResource;
use App\Services\NutritionalStatusService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NutritionalStatusController extends Controller
{
    /**
     * @var NutritionalStatusService
     */
    protected NutritionalStatusService $nutritionalStatusService;

    /**
     * DummyModel Constructor
     *
     * @param NutritionalStatusService $nutritionalStatusService
     *
     */
    public function __construct(NutritionalStatusService $nutritionalStatusService)
    {
        $this->nutritionalStatusService = $nutritionalStatusService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return NutritionalStatusResource::collection($this->nutritionalStatusService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->nutritionalStatusService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(NutritionalStatusRequest $request): NutritionalStatusResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new NutritionalStatusResource($this->nutritionalStatusService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): NutritionalStatusResource
    {
        return NutritionalStatusResource::make($this->nutritionalStatusService->getById($id));
    }


    public function update(NutritionalStatusRequest $request, int $id): NutritionalStatusResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new NutritionalStatusResource($this->nutritionalStatusService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->nutritionalStatusService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->nutritionalStatusService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
