<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use Illuminate\Support\Str;


class RoleService extends AbstractService
{
	protected $model = Role::class;

	private function preProcessing(array $properties): array
	{
		if (isset($properties['title']))
			$name = Str::slug($properties['title']);

		if (isset($name)) {
			$properties['name'] = $name;
	
			$aux = Role::whereName($name)->where('id', '<>', $properties['id'] ?? 0)->firstOr(function() {
				return false;
			});
			
		if ($aux)
            throw new \Exception(__('this :resource already exists', ['resource' => __('role')]), 400);
		}

		$properties['guard_name'] = 'api';

		return $properties;
	}

	public function store(array $properties): Role|null
	{
		$tmp = Role::create($this->preProcessing($properties));
		$tmp->permissions()->sync($properties['permissions']);

        return $tmp;
	}

	public function show(int|Model $resource): Role|null
	{
		if (gettype($resource) == 'integer')
			$resource = Role::firstOrFail($resource);

		return $resource->loadMissing([
			'permissions',
		]);
	}

	public function update(array $properties, int|Model $resource): Role|null
	{
		$properties['id'] = $resource->id;
		$tmp = $this->show($resource);
        $tmp->update($this->preProcessing($properties));
		$tmp->permissions()->sync($properties['permissions']);

        return $tmp;
	}
}