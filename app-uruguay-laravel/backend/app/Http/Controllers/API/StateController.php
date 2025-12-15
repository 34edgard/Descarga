<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StateRequest;
use App\Http\Resources\StateResource;
use App\Services\StateService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StateController extends Controller
{
    /**
     * @var StateService
     */
    protected StateService $stateService;

    /**
     * DummyModel Constructor
     *
     * @param StateService $stateService
     *
     */
    public function __construct(StateService $stateService)
    {
        $this->stateService = $stateService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return StateResource::collection($this->stateService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->stateService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(StateRequest $request): StateResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new StateResource($this->stateService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function show(int $id): StateResource
    {
        return StateResource::make($this->stateService->getById($id));
    }

    public function update(StateRequest $request, int $id): StateResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new StateResource($this->stateService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->stateService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->stateService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
