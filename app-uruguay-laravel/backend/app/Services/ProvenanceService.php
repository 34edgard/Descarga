<?php

namespace App\Services;

use App\Repositories\ProvenanceRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProvenanceService
{
    /**
     * @var ProvenanceRepository $provenanceRepository
     */
    protected $provenanceRepository;

    /**
     * DummyClass constructor.
     *
     * @param ProvenanceRepository $provenanceRepository
     */
    public function __construct(ProvenanceRepository $provenanceRepository)
    {
        $this->provenanceRepository = $provenanceRepository;
    }

    /**
     * Get all provenanceRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->provenanceRepository->all();
    }


    /**
     * Get paginated provenanceRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->provenanceRepository->allPaginated($filters);

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
     * Get provenanceRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->provenanceRepository->find($id);
    }

    /**
     * Validate provenanceRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->provenanceRepository->create($data);
    }

    /**
     * Update provenanceRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $provenanceRepository = $this->provenanceRepository->update($data, $id);
            DB::commit();
            return $provenanceRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete provenanceRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $provenanceRepository = $this->provenanceRepository->delete($id);
            DB::commit();
            return $provenanceRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

    /**
     * Delete provenanceRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->provenanceRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
