<?php

namespace App\Services;

use App\Repositories\MunicipalityRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class MunicipalityService
{
    /**
     * @var MunicipalityRepository $municipalityRepository
     */
    protected $municipalityRepository;

    /**
     * DummyClass constructor.
     *
     * @param MunicipalityRepository $municipalityRepository
     */
    public function __construct(MunicipalityRepository $municipalityRepository)
    {
        $this->municipalityRepository = $municipalityRepository;
    }

    /**
     * Get all municipalityRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->municipalityRepository->all();
    }

    /**
     * Get municipalityRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->municipalityRepository->find($id);
    }

    /**
     * Validate municipalityRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->municipalityRepository->create($data);
    }

    /**
     * Update municipalityRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $municipalityRepository = $this->municipalityRepository->update($data, $id);
            DB::commit();
            return $municipalityRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete municipalityRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $municipalityRepository = $this->municipalityRepository->delete($id);
            DB::commit();
            return $municipalityRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
