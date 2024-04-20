<?php

namespace App\Services;

use App\Http\Filters\{
	ArticleByTopicsFilter,
    ArticleInDateFilter
};
use App\Models\Article;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\{
    AllowedFilter,
    QueryBuilder
};

class ArticleService extends AbstractService
{
	protected $model = Article::class;

	public function index(\Illuminate\Http\Request $properties): LengthAwarePaginator
	{
		/** @var \App\Models\User $user */
		$user = auth()->user();

		$payload = QueryBuilder::for($this->model)
			->with([
				'author',
				'topics',
			])
			->when($user->hasRole(['admin', 'dev']), function(Builder $query) {
				$query->withTrashed();
			})
			->when($user->hasRole('writer'), function(Builder $query) use ($user) {
				$query->where('user_id', $user->id);
			})
			->allowedFilters([
				'id',
				'title',
				AllowedFilter::exact('author', 'user_id'),
				AllowedFilter::custom('topics', new ArticleByTopicsFilter()),
				AllowedFilter::custom('date', new ArticleInDateFilter()),
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

		return $user->articles()->create($properties);
	}

	public function show(int|Model $resource): Model|null
	{
		if (gettype($resource) == 'integer')
			$resource = Article::firstOrFail($resource);

		return $resource->loadMissing([
			'topics' => fn (Builder $query) => $query->orderByDesc('updated_at')->limit(5),
			'author',
		]);
	}

	public function update(array $properties, int|Model $resource): Model|null
	{
		/** @var \App\Models\User $user */
		$user = auth()->user();
		$id = $properties['id'];

		if (auth()->check() && $user->hasRole('writer') && ! $user->articles()->findOr($id, function() {
			return false;
		}))
			throw new \Exception(__('you are not allowed to update this :resource', ['resource' => __('article')]), 403);

		$resource->update($properties);
		$resource->topics()->sync($properties['topics']);
		$resource = $this->show($resource);
		
        return $resource;
	}
}