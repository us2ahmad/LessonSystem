<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'post_id', 'total'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
