<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalConditionRequest;
use App\Http\Resources\MedicalConditionResource;
use App\Services\MedicalConditionService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MedicalConditionController extends Controller
{
    /**
     * @var MedicalConditionService
     */
    protected MedicalConditionService $medicalConditionService;

    /**
     * DummyModel Constructor
     *
     * @param MedicalConditionService $medicalConditionService
     *
     */
    public function __construct(MedicalConditionService $medicalConditionService)
    {
        $this->medicalConditionService = $medicalConditionService;
    }


    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return MedicalConditionResource::collection($this->medicalConditionService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->medicalConditionService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(MedicalConditionRequest $request): MedicalConditionResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new MedicalConditionResource($this->medicalConditionService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): MedicalConditionResource
    {
        return MedicalConditionResource::make($this->medicalConditionService->getById($id));
    }

    public function update(MedicalConditionRequest $request, int $id): MedicalConditionResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new MedicalConditionResource($this->medicalConditionService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->medicalConditionService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->medicalConditionService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
