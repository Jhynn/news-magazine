<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ArticleByTopicFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('topics', function(Builder $query) use ($value) {
            $query->whereIn('name', $value);
        });
    }
}
