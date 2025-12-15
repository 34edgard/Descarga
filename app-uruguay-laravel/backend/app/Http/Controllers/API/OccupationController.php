<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OccupationRequest;
use App\Http\Resources\OccupationResource;
use App\Services\OccupationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OccupationController extends Controller
{
    /**
     * @var OccupationService
     */
    protected OccupationService $occupationService;

    /**
     * DummyModel Constructor
     *
     * @param OccupationService $occupationService
     *
     */
    public function __construct(OccupationService $occupationService)
    {
        $this->occupationService = $occupationService;
    }


    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return OccupationResource::collection($this->occupationService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->occupationService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(OccupationRequest $request): OccupationResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new OccupationResource($this->occupationService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): OccupationResource
    {
        return OccupationResource::make($this->occupationService->getById($id));
    }


    public function update(OccupationRequest $request, int $id): OccupationResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new OccupationResource($this->occupationService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->occupationService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->occupationService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
