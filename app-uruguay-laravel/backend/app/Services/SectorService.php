<?php

namespace App\Services;

use App\Repositories\SectorRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SectorService
{
    /**
     * @var SectorRepository $sectorRepository
     */
    protected $sectorRepository;

    /**
     * DummyClass constructor.
     *
     * @param SectorRepository $sectorRepository
     */
    public function __construct(SectorRepository $sectorRepository)
    {
        $this->sectorRepository = $sectorRepository;
    }

    /**
     * Get all sectorRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->sectorRepository->all();
    }


    /**
     * Get paginated sectorRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->sectorRepository->allPaginated($filters);

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
     * Get sectorRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->sectorRepository->find($id);
    }

    /**
     * Validate sectorRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->sectorRepository->create($data);
    }

    /**
     * Update sectorRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $sectorRepository = $this->sectorRepository->update($data, $id);
            DB::commit();
            return $sectorRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete sectorRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $sectorRepository = $this->sectorRepository->delete($id);
            DB::commit();
            return $sectorRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete sectorRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->sectorRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
