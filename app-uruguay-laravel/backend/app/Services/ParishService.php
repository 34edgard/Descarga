<?php

namespace App\Services;

use App\Repositories\ParishRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ParishService
{
    /**
     * @var ParishRepository $parishRepository
     */
    protected $parishRepository;

    /**
     * DummyClass constructor.
     *
     * @param ParishRepository $parishRepository
     */
    public function __construct(ParishRepository $parishRepository)
    {
        $this->parishRepository = $parishRepository;
    }

    /**
     * Get all parishRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->parishRepository->all();
    }


    /**
     * Get paginated parishRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->parishRepository->allPaginated($filters);

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
     * Get parishRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->parishRepository->find($id);
    }

    /**
     * Validate parishRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->parishRepository->create($data);
    }

    /**
     * Update parishRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $parishRepository = $this->parishRepository->update($data, $id);
            DB::commit();
            return $parishRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete parishRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $parishRepository = $this->parishRepository->delete($id);
            DB::commit();
            return $parishRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete parishRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->parishRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
