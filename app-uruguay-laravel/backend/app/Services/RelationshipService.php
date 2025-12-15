<?php

namespace App\Services;

use App\Repositories\RelationshipRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class RelationshipService
{
    /**
     * @var RelationshipRepository $relationshipRepository
     */
    protected $relationshipRepository;

    /**
     * DummyClass constructor.
     *
     * @param RelationshipRepository $relationshipRepository
     */
    public function __construct(RelationshipRepository $relationshipRepository)
    {
        $this->relationshipRepository = $relationshipRepository;
    }

    /**
     * Get all relationshipRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->relationshipRepository->all();
    }


    /**
     * Get paginated relationshipRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->relationshipRepository->allPaginated($filters);

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
     * Get relationshipRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->relationshipRepository->find($id);
    }

    /**
     * Validate relationshipRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->relationshipRepository->create($data);
    }

    /**
     * Update relationshipRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $relationshipRepository = $this->relationshipRepository->update($data, $id);
            DB::commit();
            return $relationshipRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete relationshipRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $relationshipRepository = $this->relationshipRepository->delete($id);
            DB::commit();
            return $relationshipRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete relationshipRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->relationshipRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
