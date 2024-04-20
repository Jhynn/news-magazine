<?php

namespace App\Services;

use Spatie\Permission\Models\Role;

class RoleService extends AbstractService
{
	protected $model = Role::class;
}