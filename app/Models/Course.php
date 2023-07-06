<?php

namespace App\Models;

use App\Models\Scopes\CourseScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $fillable = [
        'name',
        'description',
        'start_time',
        'end_time',
    ];
    public function getStartTimeAttribute($value): string{
        return Carbon::parse($value)->format('d-m-Y');
    }
    public function getEndTimeAttribute($value): string{
        return Carbon::parse($value)->format('d-m-Y');
    }
    public function person(){
        return $this->belongsToMany(Person::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CourseScope());
    }
}
