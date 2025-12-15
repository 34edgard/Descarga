<?php

namespace App\Services;

use App\Repositories\LevelRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class LevelService
{
    /**
     * @var LevelRepository $levelRepository
     */
    protected $levelRepository;

    /**
     * DummyClass constructor.
     *
     * @param LevelRepository $levelRepository
     */
    public function __construct(LevelRepository $levelRepository)
    {
        $this->levelRepository = $levelRepository;
    }

    /**
     * Get all levelRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->levelRepository->all();
    }

    /**
     * Get paginated addressRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->levelRepository->allPaginated($filters);

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
     * Get levelRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->levelRepository->find($id);
    }

    /**
     * Validate levelRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->levelRepository->create($data);
    }

    /**
     * Update levelRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $levelRepository = $this->levelRepository->update($data, $id);
            DB::commit();
            return $levelRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete levelRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $levelRepository = $this->levelRepository->delete($id);
            DB::commit();
            return $levelRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete levelRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->levelRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
