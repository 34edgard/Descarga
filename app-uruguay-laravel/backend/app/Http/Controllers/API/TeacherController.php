<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Http\Resources\TeacherResource;
use App\Services\TeacherService;
use Illuminate\Http\Request;


class TeacherController extends Controller
{
    /**
     * @var TeacherService
     */
    protected TeacherService $teacherService;

    /**
     * DummyModel Constructor
     *
     * @param TeacherService $teacherService
     *
     */
    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return TeacherResource::collection($this->teacherService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->teacherService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(TeacherRequest $request): TeacherResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new TeacherResource($this->teacherService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function show(int $id): TeacherResource
    {
        return TeacherResource::make($this->teacherService->getById($id));
    }


    public function update(TeacherRequest $request, int $id): TeacherResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new TeacherResource($this->teacherService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->teacherService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->teacherService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
