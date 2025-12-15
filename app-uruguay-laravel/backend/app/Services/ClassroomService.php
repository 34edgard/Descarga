<?php

namespace App\Services;

use App\Repositories\ClassroomRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ClassroomService
{
    /**
     * @var ClassroomRepository $classroomRepository
     */
    protected $classroomRepository;

    /**
     * DummyClass constructor.
     *
     * @param ClassroomRepository $classroomRepository
     */
    public function __construct(ClassroomRepository $classroomRepository)
    {
        $this->classroomRepository = $classroomRepository;
    }

    /**
     * Get all classroomRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->classroomRepository->all();
    }


    /**
     * Get paginated addressRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->classroomRepository->allPaginated($filters);

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
     * Get classroomRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->classroomRepository->find($id);
    }

    /**
     * Validate classroomRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->classroomRepository->create($data);
    }

    /**
     * Update classroomRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $classroomRepository = $this->classroomRepository->update($data, $id);
            DB::commit();
            return $classroomRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete classroomRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $classroomRepository = $this->classroomRepository->delete($id);
            DB::commit();
            return $classroomRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete classroomRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->classroomRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
