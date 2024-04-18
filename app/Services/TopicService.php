<?php

namespace App\Services;

use App\Models\Topic;

class TopicService extends AbstractService
{
	protected $model = Topic::class;

	public function store(array $properties): \Illuminate\Database\Eloquent\Model|null
	{
		/** @var $user \App\Models\User */
		$user = auth()->user();

		return $user->topics()->create($properties);
	}
}