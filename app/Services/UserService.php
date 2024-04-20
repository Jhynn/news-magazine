<?php

namespace App\Services;

use App\Http\Filers\UserTypeFilter;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\{
    AllowedFilter,
    QueryBuilder
};

class UserService extends AbstractService
{
	protected $model = User::class;

	public function index(\Illuminate\Http\Request $properties): LengthAwarePaginator
	{
		$payload = QueryBuilder::for($this->model)
			->allowedFilters([
				'id',
				'name',
				'surname',
				'email',
				'username',
				AllowedFilter::custom('type', new UserTypeFilter()),
			])
			->allowedSorts([
				'updated_at',
				'name',
			])
			->defaultSort('-updated_at')
			->paginate($properties->query('per_page'))
			->appends($properties->query());

		return $payload;
	}

	public function show(int|Model $resource): Model|null
	{
		if (gettype($resource) == 'integer')
			$resource = User::firstOrFail($resource);

		return $resource->loadMissing([
			'articles' => fn (Builder $query) => $query->orderByDesc('updated_at')->limit(5),
			'topics' => fn (Builder $query) => $query->orderByDesc('updated_at')->limit(5),
		]);
	}
}