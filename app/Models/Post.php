<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'teacher_id',
        'content',
        'price',
        'rejected_reason',
        'status'
    ];


    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(PostPhoto::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(PostReview::class);
    }

    public function studentorder(): HasMany
    {
        return $this->hasMany(StudentOrder::class);
    }
}
