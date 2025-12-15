<?php

namespace App\Services;

use App\Repositories\OccupationRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OccupationService
{
    /**
     * @var OccupationRepository $occupationRepository
     */
    protected $occupationRepository;

    /**
     * DummyClass constructor.
     *
     * @param OccupationRepository $occupationRepository
     */
    public function __construct(OccupationRepository $occupationRepository)
    {
        $this->occupationRepository = $occupationRepository;
    }

    /**
     * Get all occupationRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->occupationRepository->all();
    }


    /**
     * Get paginated addressRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->occupationRepository->allPaginated($filters);

        return [
            'data' => $paginatedData->items(),
            'meta' => [
                'total'         => $paginatedData->total(),
                'current_page'  => $paginatedData->currentPage(),
                'last_page'     => $paginatedData->lastPage(),
                'per_page'      => $paginatedData->perPage(),
            ],
        ];
    }

    /**
     * Get occupationRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->occupationRepository->find($id);
    }

    /**
     * Validate occupationRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->occupationRepository->create($data);
    }

    /**
     * Update occupationRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $occupationRepository = $this->occupationRepository->update($data, $id);
            DB::commit();
            return $occupationRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete occupationRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $occupationRepository = $this->occupationRepository->delete($id);
            DB::commit();
            return $occupationRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete occupationRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->occupationRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
