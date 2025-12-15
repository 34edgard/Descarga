<?php

namespace App\Services;

use App\Repositories\PhoneRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PhoneService
{
    /**
     * @var PhoneRepository $phoneRepository
     */
    protected $phoneRepository;

    /**
     * DummyClass constructor.
     *
     * @param PhoneRepository $phoneRepository
     */
    public function __construct(PhoneRepository $phoneRepository)
    {
        $this->phoneRepository = $phoneRepository;
    }

    /**
     * Get all phoneRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->phoneRepository->all();
    }

    /**
     * Get phoneRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->phoneRepository->find($id);
    }

    /**
     * Validate phoneRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->phoneRepository->create($data);
    }

    /**
     * Update phoneRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $phoneRepository = $this->phoneRepository->update($data, $id);
            DB::commit();
            return $phoneRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete phoneRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $phoneRepository = $this->phoneRepository->delete($id);
            DB::commit();
            return $phoneRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
