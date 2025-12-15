<?php

namespace App\Services;

use App\Http\Resources\SchoolYearResource;
use App\Repositories\SchoolYearRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SchoolYearService
{
    /**
     * @var SchoolYearRepository $schoolYearRepository
     */
    protected $schoolYearRepository;

    /**
     * DummyClass constructor.
     *
     * @param SchoolYearRepository $schoolYearRepository
     */
    public function __construct(SchoolYearRepository $schoolYearRepository)
    {
        $this->schoolYearRepository = $schoolYearRepository;
    }

    /**
     * Get all schoolYearRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->schoolYearRepository->all();
    }


    /**
     * Get paginated schoolYearRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->schoolYearRepository->allPaginated($filters);

        return [
            'data' => SchoolYearResource::collection($paginatedData->items()),
            'meta' => [
                'total'         => $paginatedData->total(),
                'current_page'  => $paginatedData->currentPage(),
                'last_page'     => $paginatedData->lastPage(),
                'per_page'      => $paginatedData->perPage(),
            ],
        ];
    }


    /**
     * Get schoolYearRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->schoolYearRepository->find($id);
    }

    /**
     * Validate schoolYearRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->schoolYearRepository->create($data);
    }

    /**
     * Update schoolYearRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $schoolYearRepository = $this->schoolYearRepository->update($data, $id);
            DB::commit();
            return $schoolYearRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete schoolYearRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $schoolYearRepository = $this->schoolYearRepository->delete($id);
            DB::commit();
            return $schoolYearRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete schoolYearRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->schoolYearRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
