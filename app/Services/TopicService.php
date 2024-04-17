<?php

namespace App\Services;

use App\Models\Topic;

class TopicService extends AbstractService
{
	protected $model = Topic::class;

	public function store(array $properties): \Illuminate\Database\Eloquent\Model|null
	{
		return auth()->user()->topics()->create($properties);
	}
}