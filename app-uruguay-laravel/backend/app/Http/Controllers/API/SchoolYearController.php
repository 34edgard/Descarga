<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolYearRequest;
use App\Http\Resources\SchoolYearResource;
use App\Services\SchoolYearService;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    /**
     * @var SchoolYearService
     */
    protected SchoolYearService $schoolYearService;

    /**
     * DummyModel Constructor
     *
     * @param SchoolYearService $schoolYearService
     *
     */
    public function __construct(SchoolYearService $schoolYearService)
    {
        $this->schoolYearService = $schoolYearService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return SchoolYearResource::collection($this->schoolYearService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->schoolYearService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(SchoolYearRequest $request): SchoolYearResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new SchoolYearResource($this->schoolYearService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function show(int $id): SchoolYearResource
    {
        return SchoolYearResource::make($this->schoolYearService->getById($id));
    }

    public function update(SchoolYearRequest $request, int $id): SchoolYearResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new SchoolYearResource($this->schoolYearService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->schoolYearService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->schoolYearService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
