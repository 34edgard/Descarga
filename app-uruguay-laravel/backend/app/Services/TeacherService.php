<?php

namespace App\Services;

use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use App\Repositories\TeacherRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TeacherService
{
    /**
     * @var TeacherRepository $teacherRepository
     */
    protected $teacherRepository;

    /**
     * DummyClass constructor.
     *
     * @param TeacherRepository $teacherRepository
     */
    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    /**
     * Get all teacherRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->teacherRepository->all();
    }


    /**
     * Get paginated teacherRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->teacherRepository->allPaginated($filters);

        return [
            'data' => TeacherResource::collection($paginatedData->items()),
            'meta' => [
                'total'         => $paginatedData->total(),
                'current_page'  => $paginatedData->currentPage(),
                'last_page'     => $paginatedData->lastPage(),
                'per_page'      => $paginatedData->perPage(),
            ],
        ];
    }

    /**
     * Get teacherRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->teacherRepository->find($id);
    }

    /**
     * Validate teacherRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->teacherRepository->create($data);
    }

    /**
     * Update teacherRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $teacherRepository = $this->teacherRepository->update($data, $id);
            DB::commit();
            return $teacherRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete teacherRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $teacherRepository = $this->teacherRepository->delete($id);
            DB::commit();
            return $teacherRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete teacherRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->teacherRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
