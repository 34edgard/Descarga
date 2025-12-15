<?php

namespace App\Services;

use App\Repositories\DisabilityRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class DisabilityService
{
    /**
     * @var DisabilityRepository $disabilityRepository
     */
    protected $disabilityRepository;

    /**
     * DummyClass constructor.
     *
     * @param DisabilityRepository $disabilityRepository
     */
    public function __construct(DisabilityRepository $disabilityRepository)
    {
        $this->disabilityRepository = $disabilityRepository;
    }

    /**
     * Get all disabilityRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->disabilityRepository->all();
    }


    /**
     * Get paginated addressRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->disabilityRepository->allPaginated($filters);

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
     * Get disabilityRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->disabilityRepository->find($id);
    }

    /**
     * Validate disabilityRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->disabilityRepository->create($data);
    }

    /**
     * Update disabilityRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $disabilityRepository = $this->disabilityRepository->update($data, $id);
            DB::commit();
            return $disabilityRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete disabilityRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $disabilityRepository = $this->disabilityRepository->delete($id);
            DB::commit();
            return $disabilityRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

    /**
     * Delete disabilityRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->disabilityRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
