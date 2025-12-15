<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationLevelRequest;
use App\Http\Resources\EducationLevelResource;
use App\Services\EducationLevelService;
use Illuminate\Http\Request;

class EducationLevelController extends Controller
{
    /**
     * @var EducationLevelService
     */
    protected EducationLevelService $educationLevelService;

    /**
     * DummyModel Constructor
     *
     * @param EducationLevelService $educationLevelService
     *
     */
    public function __construct(EducationLevelService $educationLevelService)
    {
        $this->educationLevelService = $educationLevelService;
    }


    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return EducationLevelResource::collection($this->educationLevelService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->educationLevelService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(EducationLevelRequest $request): EducationLevelResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new EducationLevelResource($this->educationLevelService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): EducationLevelResource
    {
        return EducationLevelResource::make($this->educationLevelService->getById($id));
    }


    public function update(EducationLevelRequest $request, int $id): EducationLevelResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new EducationLevelResource($this->educationLevelService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->educationLevelService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->educationLevelService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
