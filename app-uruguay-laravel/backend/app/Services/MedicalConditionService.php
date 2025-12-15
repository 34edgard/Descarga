<?php

namespace App\Services;

use App\Repositories\MedicalConditionRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class MedicalConditionService
{
    /**
     * @var MedicalConditionRepository $medicalConditionRepository
     */
    protected $medicalConditionRepository;

    /**
     * DummyClass constructor.
     *
     * @param MedicalConditionRepository $medicalConditionRepository
     */
    public function __construct(MedicalConditionRepository $medicalConditionRepository)
    {
        $this->medicalConditionRepository = $medicalConditionRepository;
    }

    /**
     * Get all medicalConditionRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->medicalConditionRepository->all();
    }


    /**
     * Get paginated medicalConditionRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->medicalConditionRepository->allPaginated($filters);

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
     * Get medicalConditionRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->medicalConditionRepository->find($id);
    }

    /**
     * Validate medicalConditionRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->medicalConditionRepository->create($data);
    }

    /**
     * Update medicalConditionRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $medicalConditionRepository = $this->medicalConditionRepository->update($data, $id);
            DB::commit();
            return $medicalConditionRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete medicalConditionRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $medicalConditionRepository = $this->medicalConditionRepository->delete($id);
            DB::commit();
            return $medicalConditionRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete medicalConditionRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->medicalConditionRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
