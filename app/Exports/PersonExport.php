<?php

namespace App\Exports;

use App\Models\Person;
use Illuminate\Http\Request as RequestAlias;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PersonExport implements FromCollection, WithHeadings
{
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
        $people = Person::query()->select(['id', 'name', 'email', 'birthday', 'phone_number']);
        if(array_key_exists('name', $this->query)) {
            $name = $this->query['name'];
            $people->where('name', 'like', '%' . $name . '%');
        }
        if(array_key_exists('email', $this->query)) {
            $email = $this->query['email'];
            $people->where('email', 'like', '%' . $email . '%');
        }
        if(array_key_exists('phone_number', $this->query)) {
            $phone_number = $this->query['phone_number'];
            $people->where('phone_number', 'like', '%' . $phone_number . '%');
        }
        if(array_key_exists('min_age', $this->query) && array_key_exists('max_age', $this->query)){
            $min_age = $this->query['min_age'];
            $max_age = $this->query['max_age'];
            $people->whereRaw("TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN ? AND ?", [$min_age, $max_age]);
        }
        if(array_key_exists('course', $this->query)){
            $course = $this->query['course'];
            $people->join('course_person', 'person.id', '=', 'course_person.person_id')
                ->where('course_id', $course);
        }
        return $people->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'email',
            'birthday',
            'phone_number',
        ];
    }
}
