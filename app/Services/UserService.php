<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

class UserService extends AbstractService
{
	protected $model = User::class;

	public function index(\Illuminate\Http\Request $properties)
	{
		$payload = QueryBuilder::for($this->model)
			->allowedFilters([
				'id',
				'name',
				'surname',
				'email',
				'username',
			])
			->allowedSorts([
				'updated_at',
				'name',
			])
			->defaultSort('-updated_at')
			->paginate($properties['per_page'])
			->appends($properties->query());

		return $payload;
	}

	public function show(int|Model $resource): Model|null
	{
		return $resource->loadMissing([
			'articles' => fn (Builder $query) => $query->orderByDesc('updated_at')->limit(5),
			'topics' => fn (Builder $query) => $query->orderByDesc('updated_at')->limit(5),
		]);
	}
}