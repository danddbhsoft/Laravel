<?php

namespace App\Http\Controllers;

use App\Http\Requests\RemoveCoursePersonRequest;
use App\Http\Requests\StoreCoursePersonRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Models\Course;
use App\Models\CoursePerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursePersonController extends Controller
{
    public function __construct()
    {
        $this->model = (new CoursePerson())->query();
    }

    public function remove(RemoveCoursePersonRequest $request){
        $validatedData = $request->validated();
        $person_id = $validatedData['person_id'];
        $course_id = $validatedData['course_id'];
        try{
            $course_person = CoursePerson::query()
                ->where('person_id', $person_id)
                ->where('course_id', $course_id)->delete();
            echo true;
        }catch(\Exception $exception){
            echo false;
        }
    }
    public function store(StoreCoursePersonRequest $request)
    {
        $validatedData = $request->validated();
        try{
            $course_person = CoursePerson::create($validatedData);
            $course = Course::select('id', 'name', 'description', 'start_time', 'end_time')
                ->find($course_person->course_id);
            $pivot = ['course_id' => $validatedData['course_id'], 'person_id' => $validatedData['person_id']];
            $course['pivot'] = $pivot;
            echo $course;
        }catch(\Exception $exception){
            echo false;
        }
    }
    public function store_list(Request $request, Course $course)
    {
        $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'required|exists:App\Models\Person,id',
        ]);
        $array = $request->get('selected');
        try {
            DB::beginTransaction();
            foreach ($array as $item) {
                $this->model->create(['course_id' => $course->id, 'person_id' => $item]);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Thêm các học viên vào khóa học thành công!');
        }catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Thêm các học viên vào khóa học thất bại! Vui lòng thử lại!');
        }
    }
}
