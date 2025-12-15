<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Services\StudentService;
use Illuminate\Http\Request;


class StudentController extends Controller
{
    /**
     * @var StudentService
     */
    protected StudentService $studentService;

    /**
     * DummyModel Constructor
     *
     * @param StudentService $studentService
     *
     */
    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return StudentResource::collection($this->studentService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->studentService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(StudentRequest $request): StudentResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new StudentResource($this->studentService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function show(int $id): StudentResource
    {
        return StudentResource::make($this->studentService->getById($id));
    }

    public function update(StudentRequest $request, int $id): StudentResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new StudentResource($this->studentService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->studentService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->studentService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
