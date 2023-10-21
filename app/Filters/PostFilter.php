<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;

class PostFilter
{
    public function filter()
    {
        return [
            'price', 'content', 'teacher_name',
            AllowedFilter::callback('item', function (Builder $query, $value) {
                $query->where('status', 'approved');
                $query->where('price', 'like', "%{$value}%")
                    ->orWhere('content', 'like', "%{$value}%")
                    ->orWhereHas('teacher', function (Builder $query) use ($value) {
                        $query->where('name', 'like', "%{$value}%");
                    });
            }),
        ];
    }
}
