<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentOrder extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'post_id'];
    protected $guarded = ['status'];
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class)->select('id', 'name');
    }
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class)->select('id', 'content');
    }
}
