<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use App\Traits\PaginateAndFilterTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @codeCoverageIgnore
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    use PaginateAndFilterTrait;

    protected $model;

    /** @var array Cache configuration for unique validations */
    protected array $uniqueValidationCache = [
        'enabled' => true,
        'ttl' => 300, // 5 minutes
        'prefix' => 'unique_validation'
    ];


    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public function insert(array $attributes): bool
    {
        return $this->model->insert($attributes);
    }

    /**
     * @param array $attributes
     * @return int
     */
    public function insertGetId(array $attributes): bool
    {
        return $this->model->insertGetId($attributes);
    }


    /**
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        $data = $this->model->find($id);
        $data->update($attributes);
        return $data;
    }


    /**
     * Update the status field of a model in a generic, configurable way.
     *
     * @param int $id
     * @param mixed $status
     * @param string $statusField
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function updateStatus(int $id, $status, string $statusField = 'status')
    {
        $model = $this->model->findOrFail($id);
        $model->{$statusField} = $status;
        $model->save();

        return $model;
    }


    /**
     * @param array $attributes
     * @param array $uniqueBy
     * @param array $update
     * @return bool
     */
    public function upsert(array $attributes, array $uniqueBy, array $update = []): bool
    {
        return $this->model->upsert($attributes, $uniqueBy, $update);
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function all($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc')
    {
        return $this->model->orderBy($orderBy, $sortBy)->get($columns);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOneOrFail(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function findBy(array $data)
    {
        return $this->model->where($data)->all();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function findOneBy(array $data)
    {
        return $this->model->where($data)->first();
    }

    /**
     * @param array $data
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOneByOrFail(array $data)
    {
        return $this->model->where($data)->firstOrFail();
    }


    /**
     * Get paginated with filters.
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function allPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->allWithPaginate($this->model, $filters, $perPage, ['name']);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Delete multiple by ids.
     *
     * @param $id
     * @return bool
     */
    public function deleteMultiple(array $ids): bool
    {
        if (empty($ids)) {
            throw new \InvalidArgumentException('No se proporcionaron IDs para eliminar.');
        }
        return $this->model->whereIn('id', $ids)->delete();
    }
}
