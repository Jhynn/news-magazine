<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;

class PermissionService extends AbstractService
{
	protected $model = Permission::class;
}