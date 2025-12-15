<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RelationshipRequest;
use App\Http\Resources\RelationshipResource;
use App\Services\RelationshipService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RelationshipController extends Controller
{
    /**
     * @var RelationshipService
     */
    protected RelationshipService $relationshipService;

    /**
     * DummyModel Constructor
     *
     * @param RelationshipService $relationshipService
     *
     */
    public function __construct(RelationshipService $relationshipService)
    {
        $this->relationshipService = $relationshipService;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return RelationshipResource::collection($this->relationshipService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->relationshipService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(RelationshipRequest $request): RelationshipResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new RelationshipResource($this->relationshipService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): RelationshipResource
    {
        return RelationshipResource::make($this->relationshipService->getById($id));
    }


    public function update(RelationshipRequest $request, int $id): RelationshipResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new RelationshipResource($this->relationshipService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->relationshipService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->relationshipService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
