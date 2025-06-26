<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'student';

    protected $fillable = [
        'name',
        'surname',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->surname}";
    }
}
