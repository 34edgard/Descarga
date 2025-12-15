<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProvenanceRequest;
use App\Http\Resources\ProvenanceResource;
use App\Services\ProvenanceService;
use Illuminate\Http\Request;

class ProvenanceController extends Controller
{
    /**
     * @var ProvenanceService
     */
    protected ProvenanceService $provenanceService;

    /**
     * DummyModel Constructor
     *
     * @param ProvenanceService $provenanceService
     *
     */
    public function __construct(ProvenanceService $provenanceService)
    {
        $this->provenanceService = $provenanceService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ProvenanceResource::collection($this->provenanceService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->provenanceService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(ProvenanceRequest $request): ProvenanceResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new ProvenanceResource($this->provenanceService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function show(int $id): ProvenanceResource
    {
        return ProvenanceResource::make($this->provenanceService->getById($id));
    }

    public function update(ProvenanceRequest $request, int $id): ProvenanceResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new ProvenanceResource($this->provenanceService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->provenanceService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->provenanceService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
