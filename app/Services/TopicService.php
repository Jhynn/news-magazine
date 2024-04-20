<?php

namespace App\Services;

use App\Models\Topic;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

class TopicService extends AbstractService
{
	/** @var string $model */
	protected $model = Topic::class;

	public function index(\Illuminate\Http\Request $properties): LengthAwarePaginator
	{
		/** @var \App\Models\User $user */
		$user = auth()->user();

		$payload = QueryBuilder::for($this->model)
			->with([
				'user',
				'articles' => fn (Builder $query) => $query->orderByDesc('updated_at')->limit(5),
			])
			->when($user->hasRole('writer'), function(Builder $query) use ($user) {
				$query->where('user_id', $user->id);
			})
			->allowedFilters([
				'id',
				'name',
				'title',
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
		/** @var $user \App\Models\User */
		$user = auth()->user();
		$topic = $user->topics()->create($properties);
		$topic->articles()->sync($properties['articles']);

		return $topic;
	}

	public function show(int|Model $resource): Topic|null
	{
		if (gettype($resource) == 'integer')
			$resource = Topic::firstOrFail($resource);

		return $resource->loadMissing([
			'articles' => fn (Builder $query) => $query->orderByDesc('updated_at')->limit(5),
			'user',
		]);
	}

	public function update(array $properties, int|Model $resource): Model|null
	{
		/** @var \App\Models\User $user */
		$user = auth()->user();
		$id = $properties['id'];

		if (auth()->check() && $user->hasRole('writer') && ! $user->topics()->findOr($id, function() {
			return false;
		}))
			throw new \Exception(__('you are not allowed to update this :resource', ['resource' => __('topic')]), 403);

		$resource->update($properties);
		$resource->articles()->sync($properties['articles']);
		$resource = $this->show($resource);
		
        return $resource;
	}

	public function destroy(int|Model $resource, array $aux = null): Model|null
	{
		$tmp = $this->show($resource);

		if ($tmp->articles()->count())
			throw new \Exception(__(
				'you cannot delete this :resource because it have :another_resource related', 
				[
					'resource' => __('topic'),
					'another_resource' => __('articles'),
				]
			), 403);

        $tmp->delete();

        return $tmp;
	}
}