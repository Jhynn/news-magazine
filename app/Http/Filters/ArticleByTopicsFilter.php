<?php

namespace App\Http\Filters;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ArticleByTopicsFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $topicIDs = Topic::where('title', 'like', "%{$value}%")->select('id')->get()->toArray();

        $query->whereHas('topics', function(Builder $query) use ($topicIDs) {
            $query->whereIn('topics.id', $topicIDs);
        });
    }
}
