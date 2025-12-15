<?php

namespace App\Services;

use App\Repositories\NutritionalStatusRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class NutritionalStatusService
{
    /**
     * @var NutritionalStatusRepository $nutritionalStatusRepository
     */
    protected $nutritionalStatusRepository;

    /**
     * DummyClass constructor.
     *
     * @param NutritionalStatusRepository $nutritionalStatusRepository
     */
    public function __construct(NutritionalStatusRepository $nutritionalStatusRepository)
    {
        $this->nutritionalStatusRepository = $nutritionalStatusRepository;
    }

    /**
     * Get all nutritionalStatusRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->nutritionalStatusRepository->all();
    }


    /**
     * Get paginated addressRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->nutritionalStatusRepository->allPaginated($filters);

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
     * Get nutritionalStatusRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->nutritionalStatusRepository->find($id);
    }

    /**
     * Validate nutritionalStatusRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->nutritionalStatusRepository->create($data);
    }

    /**
     * Update nutritionalStatusRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $nutritionalStatusRepository = $this->nutritionalStatusRepository->update($data, $id);
            DB::commit();
            return $nutritionalStatusRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete nutritionalStatusRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $nutritionalStatusRepository = $this->nutritionalStatusRepository->delete($id);
            DB::commit();
            return $nutritionalStatusRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete nutritionalStatusRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->nutritionalStatusRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
