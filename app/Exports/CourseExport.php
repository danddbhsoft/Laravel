<?php

namespace App\Exports;

use App\Models\Course;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request as RequestAlias;

class CourseExport implements FromCollection, WithHeadings
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    private array $query;

    public function __construct(RequestAlias $request)
    {
        $this->query = array();
        if($request->has('query')){
            $this->query = $request->get('query');
        }
    }

    public function collection()
    {
        $courses = Course::query()->select(['id', 'name', 'description', 'start_time', 'end_time'])
            ->whereDate('end_time', '>=', DB::raw('CURDATE()'));
        if(array_key_exists('name', $this->query)){
            $name = $this->query['name'];
            $courses->where('name', 'like', '%'.$name.'%');
        }
        if(array_key_exists('description', $this->query)) {
            $description = $this->query['description'];
            $courses->where('description', 'like', '%' . $description . '%');
        }
        if(array_key_exists('start_time', $this->query) && array_key_exists('end_time', $this->query)){
            $start_time = $this->query['start_time'];
            $end_time = $this->query['end_time'];
            $courses->whereDate('start_time', '>=', $start_time)
                ->whereDate('end_time', '<=', $end_time);
        }else{
            if(array_key_exists('start_time', $this->query)){
                $start_time = $this->query['start_time'];
                $courses->where('start_time', $start_time);
            }
            if(array_key_exists('end_time', $this->query)){
                $end_time = $this->query['end_time'];
                $courses->where('end_time', $end_time);
            }
        }
        $courses->orderBy('start_time', 'asc');
        return $courses->get();
    }
    public function headings(): array
    {
        return [
            'id',
            'name',
            'description',
            'start_time',
            'end_time',
        ];
    }
}
