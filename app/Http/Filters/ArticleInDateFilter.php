<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ArticleInDateFilter implements Filter
{

    public function __invoke(Builder $query, $value, string $property)
    {
        if (gettype($value) == 'string')
            $value = array($value, now()->format('Y-m-d'));

        $query->whereBetween('updated_at', $value);
    }
}
