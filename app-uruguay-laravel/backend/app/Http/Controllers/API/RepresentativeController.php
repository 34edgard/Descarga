<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RepresentativeRequest;
use App\Http\Resources\RepresentativeResource;
use App\Services\RepresentativeService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RepresentativeController extends Controller
{
    /**
     * @var RepresentativeService
     */
    protected RepresentativeService $representativeService;

    /**
     * DummyModel Constructor
     *
     * @param RepresentativeService $representativeService
     *
     */
    public function __construct(RepresentativeService $representativeService)
    {
        $this->representativeService = $representativeService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return RepresentativeResource::collection($this->representativeService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->representativeService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(RepresentativeRequest $request): RepresentativeResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new RepresentativeResource($this->representativeService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function show(int $id): RepresentativeResource
    {
        return RepresentativeResource::make($this->representativeService->getById($id));
    }

    public function update(RepresentativeRequest $request, int $id): RepresentativeResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new RepresentativeResource($this->representativeService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->representativeService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->representativeService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
