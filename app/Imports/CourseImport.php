<?php

namespace App\Imports;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CourseImport implements ToModel, WithStartRow, WithValidation, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        return new Course([
            'name' => $row['name'],
            'description' => $row['description'],
            'start_time' => $row['start_time'],
            'end_time' => $row['end_time'],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
    public function rules(): array
    {
        return [
            '*.name' => ['required'],
            '*.description' => ['required'],
            '*.start_time' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            '*.end_time' => [
                'required',
                'date',
                'after:today',
                'after_or_equal:today',
            ],
        ];
    }
}
