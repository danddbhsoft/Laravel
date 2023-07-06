<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePerson extends Model
{
    use HasFactory;
    protected $table = 'course_person';
    public $timestamps = false;
    protected $fillable = [
        'person_id',
        'course_id',
    ];
}
