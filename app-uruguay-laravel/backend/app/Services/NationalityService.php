<?php

namespace App\Services;

use App\Repositories\NationalityRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class NationalityService
{
    /**
     * @var NationalityRepository $nationalityRepository
     */
    protected $nationalityRepository;

    /**
     * DummyClass constructor.
     *
     * @param NationalityRepository $nationalityRepository
     */
    public function __construct(NationalityRepository $nationalityRepository)
    {
        $this->nationalityRepository = $nationalityRepository;
    }

    /**
     * Get all nationalityRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->nationalityRepository->all();
    }

    /**
     * Get nationalityRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->nationalityRepository->find($id);
    }

    /**
     * Validate nationalityRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->nationalityRepository->create($data);
    }

    /**
     * Update nationalityRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $nationalityRepository = $this->nationalityRepository->update($data, $id);
            DB::commit();
            return $nationalityRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete nationalityRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $nationalityRepository = $this->nationalityRepository->delete($id);
            DB::commit();
            return $nationalityRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
