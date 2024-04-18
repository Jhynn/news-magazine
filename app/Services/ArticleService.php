<?php

namespace App\Services;

use App\Models\Article;

class ArticleService extends AbstractService
{
	protected $model = Article::class;

	public function store(array $properties): \Illuminate\Database\Eloquent\Model|null
	{
		/** @var $user \App\Models\User */
		$user = auth()->user();

		return $user->articles()->create($properties);
	}
}