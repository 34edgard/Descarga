<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequest;
use App\Http\Resources\ClassroomResource;
use App\Services\ClassroomService;
use Illuminate\Http\Request;


class ClassroomController extends Controller
{
    /**
     * @var ClassroomService
     */
    protected ClassroomService $classroomService;

    /**
     * DummyModel Constructor
     *
     * @param ClassroomService $classroomService
     *
     */
    public function __construct(ClassroomService $classroomService)
    {
        $this->classroomService = $classroomService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ClassroomResource::collection($this->classroomService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->classroomService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(ClassroomRequest $request): ClassroomResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new ClassroomResource($this->classroomService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): ClassroomResource
    {
        return ClassroomResource::make($this->classroomService->getById($id));
    }


    public function update(ClassroomRequest $request, int $id): ClassroomResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new ClassroomResource($this->classroomService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->classroomService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->classroomService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
