<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Puzzle extends Model
{
    protected $fillable = [
        'student_id',
        'puzzle_string',
        'total_score',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
