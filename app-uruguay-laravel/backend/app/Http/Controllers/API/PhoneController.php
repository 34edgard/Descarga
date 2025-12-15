<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PhoneRequest;
use App\Http\Resources\PhoneResource;
use App\Services\PhoneService;


class PhoneController extends Controller
{
    /**
     * @var PhoneService
     */
    protected PhoneService $phoneService;

    /**
     * DummyModel Constructor
     *
     * @param PhoneService $phoneService
     *
     */
    public function __construct(PhoneService $phoneService)
    {
        $this->phoneService = $phoneService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return PhoneResource::collection($this->phoneService->getAll());
    }

    public function store(PhoneRequest $request): PhoneResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new PhoneResource($this->phoneService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function show(int $id): PhoneResource
    {
        return PhoneResource::make($this->phoneService->getById($id));
    }

    public function update(PhoneRequest $request, int $id): PhoneResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new PhoneResource($this->phoneService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->phoneService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
