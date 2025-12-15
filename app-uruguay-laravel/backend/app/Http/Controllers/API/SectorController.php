<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SectorRequest;
use App\Http\Resources\SectorResource;
use App\Services\SectorService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SectorController extends Controller
{
    /**
     * @var SectorService
     */
    protected SectorService $sectorService;

    /**
     * DummyModel Constructor
     *
     * @param SectorService $sectorService
     *
     */
    public function __construct(SectorService $sectorService)
    {
        $this->sectorService = $sectorService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return SectorResource::collection($this->sectorService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->sectorService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(SectorRequest $request): SectorResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new SectorResource($this->sectorService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function show(int $id): SectorResource
    {
        return SectorResource::make($this->sectorService->getById($id));
    }

    public function update(SectorRequest $request, int $id): SectorResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new SectorResource($this->sectorService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->sectorService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->sectorService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
