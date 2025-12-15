<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Services\AddressService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddressController extends Controller
{
    /**
     * @var AddressService
     */
    protected AddressService $addressService;

    /**
     * DummyModel Constructor
     *
     * @param AddressService $addressService
     *
     */
    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }


    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return AddressResource::collection($this->addressService->getAll());
    }


    public function paginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->addressService->getPaginated($request->all());
        return $this->success($response);
    }


    public function store(AddressRequest $request): AddressResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new AddressResource($this->addressService->save($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function show(int $id): AddressResource
    {
        return AddressResource::make($this->addressService->getById($id));
    }


    public function update(AddressRequest $request, int $id): AddressResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new AddressResource($this->addressService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->addressService->deleteById($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }

    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->addressService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
