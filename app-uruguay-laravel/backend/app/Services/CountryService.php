<?php

namespace App\Services;

use App\Repositories\CountryRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CountryService
{
    /**
     * @var CountryRepository $countryRepository
     */
    protected $countryRepository;

    /**
     * DummyClass constructor.
     *
     * @param CountryRepository $countryRepository
     */
    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * Get all countryRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->countryRepository->all();
    }

    /**
     * Get paginated countryRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->countryRepository->allPaginated($filters);

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
     * Get countryRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->countryRepository->find($id);
    }

    /**
     * Validate countryRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->countryRepository->create($data);
    }

    /**
     * Update countryRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $countryRepository = $this->countryRepository->update($data, $id);
            DB::commit();
            return $countryRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete countryRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $countryRepository = $this->countryRepository->delete($id);
            DB::commit();
            return $countryRepository;
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
            $response = $this->countryRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
