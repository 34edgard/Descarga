<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParishRequest;
use App\Http\Resources\ParishResource;
use App\Services\ParishService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParishController extends Controller
{
    /**
     * @var ParishService
     */
    protected ParishService $parishService;

    /**
     * DummyModel Constructor
     *
     * @param ParishService $parishService
     *
     */
    public function __construct(ParishService $parishService)
    {
        $this->parishService = $parishService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ParishResource::collection($this->parishService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->parishService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(ParishRequest $request): ParishResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new ParishResource($this->parishService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): ParishResource
    {
        return ParishResource::make($this->parishService->getById($id));
    }


    public function update(ParishRequest $request, int $id): ParishResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new ParishResource($this->parishService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->parishService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->parishService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
