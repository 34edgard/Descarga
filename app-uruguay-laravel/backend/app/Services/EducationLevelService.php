<?php

namespace App\Services;

use App\Repositories\EducationLevelRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class EducationLevelService
{
    /**
     * @var EducationLevelRepository $educationLevelRepository
     */
    protected $educationLevelRepository;

    /**
     * DummyClass constructor.
     *
     * @param EducationLevelRepository $educationLevelRepository
     */
    public function __construct(EducationLevelRepository $educationLevelRepository)
    {
        $this->educationLevelRepository = $educationLevelRepository;
    }

    /**
     * Get all educationLevelRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->educationLevelRepository->all();
    }


    /**
     * Get paginated addressRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->educationLevelRepository->allPaginated($filters);

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
     * Get educationLevelRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->educationLevelRepository->find($id);
    }

    /**
     * Validate educationLevelRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->educationLevelRepository->create($data);
    }

    /**
     * Update educationLevelRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $educationLevelRepository = $this->educationLevelRepository->update($data, $id);
            DB::commit();
            return $educationLevelRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete educationLevelRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $educationLevelRepository = $this->educationLevelRepository->delete($id);
            DB::commit();
            return $educationLevelRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete educationLevelRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->educationLevelRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
