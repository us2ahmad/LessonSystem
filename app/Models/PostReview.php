<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostReview extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_id',
        'student_id',
        'comment',
        'rate'
    ];
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
