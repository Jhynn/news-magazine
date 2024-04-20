<?php

namespace App\Http\Filers;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class UserTypeFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('roles', function(Builder $query) use ($value) {
            $query->where('name', $value);
        });
    }
}
