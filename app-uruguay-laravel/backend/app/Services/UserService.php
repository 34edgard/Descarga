<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class UserService
{
    /**
     * @var UserRepository $userRepository
     */
    protected $userRepository;

    /**
     * DummyClass constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all userRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->userRepository->all();
    }

    /**
     * Get paginated userRepository with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $paginatedData = $this->userRepository->allPaginated($filters);

        return [
            'data' => UserResource::collection($paginatedData->items()),
            'meta' => [
                'total'         => $paginatedData->total(),
                'current_page'  => $paginatedData->currentPage(),
                'last_page'     => $paginatedData->lastPage(),
                'per_page'      => $paginatedData->perPage(),
            ],
        ];
    }

    /**
     * Get userRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * Validate userRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->create($data);

            if (!empty($data['role'])) {
                $user->syncRoles($data['role']);
            }

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to save post data');
        }
    }

    /**
     * Update userRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->update($data, $id);

            // Sync roles and permissions if provided
            if (array_key_exists('role', $data)) {
                $user->syncRoles($data['role']);
            }

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete userRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $userRepository = $this->userRepository->delete($id);
            DB::commit();
            return $userRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Delete userRepository by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->userRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }


    /**
     * Update the status field of a user using the repository.
     *
     * @param int $id
     * @param mixed $status
     * @param string $statusField
     * @return mixed
     */
    public function updateStatus(int $id, $status, string $statusField)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->updateStatus($id, $status, $statusField);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update user status');
        }
    }
}
