<?php

namespace App\Services;

use App\Http\Filters\{
	ArticleByTopicsFilter,
    ArticleInDateFilter
};
use App\Models\Article;
use Spatie\QueryBuilder\{
    AllowedFilter,
    QueryBuilder
};

class ArticleService extends AbstractService
{
	protected $model = Article::class;

	public function index(\Illuminate\Http\Request $properties)
	{
		$payload = QueryBuilder::for($this->model)
			->with([
				'author',
				'topics',
			])
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

	public function store(array $properties): \Illuminate\Database\Eloquent\Model|null
	{
		/** @var $user \App\Models\User */
		$user = auth()->user();

		return $user->articles()->create($properties);
	}
}