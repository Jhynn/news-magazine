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
			->with('roles')
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

	public function store(array $properties): Model|null
    {
        $tmp = User::create($properties);
		$tmp->assignRole($properties['role']);

        return $tmp;
    }

	public function show(int|Model $resource): Model|null
	{
		if (gettype($resource) == 'integer')
			$resource = User::firstOrFail($resource);

		return $resource->loadMissing([
			'roles',
			'medias' => fn (Builder $query) => $query->orderByDesc('updated_at')->limit(5),
			'articles' => fn (Builder $query) => $query->orderByDesc('updated_at')->limit(5),
			'topics' => fn (Builder $query) => $query->orderByDesc('updated_at')->limit(5),
		]);
	}

	public function update(array $properties, int|Model $resource): Model|null
    {
        $tmp = $this->show($resource);
        $tmp->update($properties);

		if (isset($properties['role']))
			$tmp->assignRole($properties['role']);

        return $tmp;
    }

	public function destroy(int|Model $resource, array $aux=null): User|null
    {
        $tmp = $this->show($resource);
        ($tmp->status == 'active') ? ($tmp->status = 'inactive') : ($tmp->status = 'active');
		$tmp->save();

        return $tmp;
    }
}