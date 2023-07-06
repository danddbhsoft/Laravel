<?php

namespace App\Models;

use App\Models\Scopes\PersonScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Spatie\Permission\Traits\HasRoles;

class Person extends Model
{
    use HasFactory, HasRoles;
    protected $table = "person";
    protected $fillable = [
        'name',
        'email',
        'birthday',
        'phone_number',
        'image_link',
    ];
    public function getBirthdayAttribute($value): string{
        return Carbon::parse($value)->format('d-m-Y');
    }
    public function courses(){
        return $this->belongsToMany(Course::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PersonScope());
    }

}
