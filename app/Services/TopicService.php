<?php

namespace App\Services;

use App\Models\Topic;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

class TopicService extends AbstractService
{
	/** @var string $model */
	protected $model = Topic::class;

	public function index(\Illuminate\Http\Request $properties)
	{
		$payload = QueryBuilder::for($this->model)
			->with([
				'user',
				'articles' => fn (Builder $query) => $query->orderByDesc('updated_at')->limit(5),
			])
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
			->paginate($properties['per_page'])
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

	public function show(int|Model $resource): Model|null
	{
		return $resource->loadMissing([
			'articles' => fn (Builder $query) => $query->orderByDesc('updated_at')->limit(5),
			'user',
		]);
	}

	public function update(array $properties, int|Model $resource): Model|null
	{
		$resource->update($properties);
		$resource->articles()->sync($properties['articles']);
		$resource = $this->show($resource);
		
        return $resource;
	}
}