<?php

namespace App\Services;

use App\Repositories\DocumentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class DocumentService
{
    /**
     * @var DocumentRepository $documentRepository
     */
    protected $documentRepository;

    /**
     * DummyClass constructor.
     *
     * @param DocumentRepository $documentRepository
     */
    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    /**
     * Get all documentRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->documentRepository->all();
    }

    /**
     * Get documentRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->documentRepository->find($id);
    }

    /**
     * Validate documentRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->documentRepository->create($data);
    }

    /**
     * Update documentRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $documentRepository = $this->documentRepository->update($data, $id);
            DB::commit();
            return $documentRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete documentRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $documentRepository = $this->documentRepository->delete($id);
            DB::commit();
            return $documentRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
