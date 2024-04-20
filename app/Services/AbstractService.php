<?php

namespace App\Services;

use App\Contracts\ServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AbstractService implements ServiceInterface
{
	protected $model;

    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    private function resolveModel()
    {
        return app($this->model);
    }

    /**
	 * @inheritDoc 
	 */
	public function index(Request $properties)
    {
        return $this->model->paginate($properties->query('per_page') ?? 15);
    }

	/**
	 * @inheritDoc
	 */
	public function store(array $properties): Model|null
    {
        $tmp = $this->model->create($properties);

        return $tmp;
    }

    /**
	 * @inheritDoc
	 */
	public function show(int|Model $resource): Model|null
    {
        if (gettype($resource) == 'integer')
            return $this->model->findOrFail($resource);

        return $resource;
    }

	/**
	 * @inheritDoc
	 */
	public function update(array $properties, int|Model $resource): Model|null
    {
        $tmp = $this->show($resource);
        $tmp->update($properties);

        return $tmp;
    }

	/**
	 * @inheritDoc
	 */
	public function destroy(int|Model $resource, array $aux=null): Model|null
    {
        $tmp = $this->show($resource);
        $tmp->delete();

        return $tmp;
    }
}