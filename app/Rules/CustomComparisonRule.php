<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CustomComparisonRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $old_time;

    public function __construct($old_time)
    {
        $this->old_time = $old_time;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $now = Carbon::now()->startOfDay();
        $old_time = Carbon::createFromFormat('d-m-Y', $this->old_time)->startOfDay();
        $start_time = Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        if($start_time->greaterThanOrEqualTo($old_time)  || $start_time->lessThanOrEqualTo($old_time) && $start_time->greaterThanOrEqualTo($now)){
        }else{
            $fail('Trường :attribute không được trước thời gian cũ hoặc trước thời gian hiện tại');
        }
    }
}
