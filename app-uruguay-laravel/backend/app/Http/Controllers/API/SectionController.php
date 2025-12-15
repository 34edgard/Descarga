<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SectionRequest;
use App\Http\Resources\SectionResource;
use App\Services\SectionService;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * @var SectionService
     */
    protected SectionService $sectionService;

    /**
     * DummyModel Constructor
     *
     * @param SectionService $sectionService
     *
     */
    public function __construct(SectionService $sectionService)
    {
        $this->sectionService = $sectionService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return SectionResource::collection($this->sectionService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->sectionService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(SectionRequest $request): SectionResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new SectionResource($this->sectionService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function show(int $id): SectionResource
    {
        return SectionResource::make($this->sectionService->getById($id));
    }

    public function update(SectionRequest $request, int $id): SectionResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new SectionResource($this->sectionService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->sectionService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->sectionService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
