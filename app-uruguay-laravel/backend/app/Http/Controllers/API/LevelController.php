<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LevelRequest;
use App\Http\Resources\LevelResource;
use App\Services\LevelService;
use Illuminate\Http\Request;


class LevelController extends Controller
{
    /**
     * @var LevelService
     */
    protected LevelService $levelService;

    /**
     * DummyModel Constructor
     *
     * @param LevelService $levelService
     *
     */
    public function __construct(LevelService $levelService)
    {
        $this->levelService = $levelService;
    }


    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return LevelResource::collection($this->levelService->getAll());
    }

    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->levelService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(LevelRequest $request): LevelResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new LevelResource($this->levelService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): LevelResource
    {
        return LevelResource::make($this->levelService->getById($id));
    }

    public function update(LevelRequest $request, int $id): LevelResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new LevelResource($this->levelService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->levelService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->levelService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
