<?php

namespace App\Services;

use App\Repositories\EnrollmentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class EnrollmentService
{
    /**
     * @var EnrollmentRepository $enrollmentRepository
     */
    protected $enrollmentRepository;

    /**
     * DummyClass constructor.
     *
     * @param EnrollmentRepository $enrollmentRepository
     */
    public function __construct(EnrollmentRepository $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }

    /**
     * Get all enrollmentRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->enrollmentRepository->all();
    }


    /**
     * Get paginated addressRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->enrollmentRepository->allPaginated($filters);

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
     * Get enrollmentRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->enrollmentRepository->find($id);
    }

    /**
     * Validate enrollmentRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->enrollmentRepository->create($data);
    }

    /**
     * Update enrollmentRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $enrollmentRepository = $this->enrollmentRepository->update($data, $id);
            DB::commit();
            return $enrollmentRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete enrollmentRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $enrollmentRepository = $this->enrollmentRepository->delete($id);
            DB::commit();
            return $enrollmentRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete enrollmentRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->enrollmentRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
