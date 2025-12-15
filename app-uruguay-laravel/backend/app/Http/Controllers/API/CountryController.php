<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Http\Resources\CountryResource;
use App\Services\CountryService;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * @var CountryService
     */
    protected CountryService $countryService;

    /**
     * DummyModel Constructor
     *
     * @param CountryService $countryService
     *
     */
    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }


    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return CountryResource::collection($this->countryService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->countryService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(CountryRequest $request): CountryResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new CountryResource($this->countryService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): CountryResource
    {
        return CountryResource::make($this->countryService->getById($id));
    }


    public function update(CountryRequest $request, int $id): CountryResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new CountryResource($this->countryService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->countryService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->countryService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
