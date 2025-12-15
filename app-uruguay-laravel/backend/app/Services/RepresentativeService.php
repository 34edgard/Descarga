<?php

namespace App\Services;

use App\Http\Resources\RepresentativeResource;
use App\Repositories\RepresentativeRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class RepresentativeService
{
    /**
     * @var RepresentativeRepository $representativeRepository
     */
    protected $representativeRepository;

    /**
     * DummyClass constructor.
     *
     * @param RepresentativeRepository $representativeRepository
     */
    public function __construct(RepresentativeRepository $representativeRepository)
    {
        $this->representativeRepository = $representativeRepository;
    }

    /**
     * Get all representativeRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->representativeRepository->all();
    }


    /**
     * Get paginated representativeRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->representativeRepository->allPaginated($filters);

        return [
            'data' => RepresentativeResource::collection($paginatedData->items()),
            'meta' => [
                'total'         => $paginatedData->total(),
                'current_page'  => $paginatedData->currentPage(),
                'last_page'     => $paginatedData->lastPage(),
                'per_page'      => $paginatedData->perPage(),
            ],
        ];
    }


    /**
     * Get representativeRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->representativeRepository->find($id);
    }

    /**
     * Validate representativeRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->representativeRepository->create($data);
    }

    /**
     * Update representativeRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $representativeRepository = $this->representativeRepository->update($data, $id);
            DB::commit();
            return $representativeRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete representativeRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $representativeRepository = $this->representativeRepository->delete($id);
            DB::commit();
            return $representativeRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete representativeRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->representativeRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
