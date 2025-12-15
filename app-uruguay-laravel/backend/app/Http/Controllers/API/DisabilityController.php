<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DisabilityRequest;
use App\Http\Resources\DisabilityResource;
use App\Services\DisabilityService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisabilityController extends Controller
{
    /**
     * @var DisabilityService
     */
    protected DisabilityService $disabilityService;

    /**
     * DummyModel Constructor
     *
     * @param DisabilityService $disabilityService
     *
     */
    public function __construct(DisabilityService $disabilityService)
    {
        $this->disabilityService = $disabilityService;
    }


    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return DisabilityResource::collection($this->disabilityService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->disabilityService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(DisabilityRequest $request): DisabilityResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new DisabilityResource($this->disabilityService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): DisabilityResource
    {
        return DisabilityResource::make($this->disabilityService->getById($id));
    }


    public function update(DisabilityRequest $request, int $id): DisabilityResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new DisabilityResource($this->disabilityService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->disabilityService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->disabilityService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
