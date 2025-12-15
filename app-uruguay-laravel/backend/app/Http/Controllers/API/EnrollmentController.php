<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnrollmentRequest;
use App\Http\Resources\EnrollmentResource;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;


class EnrollmentController extends Controller
{
    /**
     * @var EnrollmentService
     */
    protected EnrollmentService $enrollmentService;

    /**
     * DummyModel Constructor
     *
     * @param EnrollmentService $enrollmentService
     *
     */
    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }


    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return EnrollmentResource::collection($this->enrollmentService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->enrollmentService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(EnrollmentRequest $request): EnrollmentResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new EnrollmentResource($this->enrollmentService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): EnrollmentResource
    {
        return EnrollmentResource::make($this->enrollmentService->getById($id));
    }


    public function update(EnrollmentRequest $request, int $id): EnrollmentResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new EnrollmentResource($this->enrollmentService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->enrollmentService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->enrollmentService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
