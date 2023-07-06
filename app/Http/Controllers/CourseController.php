<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportCourseExcelRequest;
use App\Imports\CourseImport;
use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\CourseExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $model;
    private $paginate;
    public function __construct()
    {
        $this->model = (new Course())->query();
        $this->paginate = 10;
    }
    public function index(Request $request)
    {
        $name = $request->get('name');
        $description = $request->get('description');
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        $courses = Course::query()
            ->where('name', 'like', '%'.$name.'%')
            ->where('description', 'like', '%'.$description.'%');
        if($request->get('start_time') != '' && $request->get('end_time') != ''){
            $courses->whereDate('start_time', '>=', $start_time)
                ->whereDate('end_time', '<=', $end_time);
        }else{
            if($request->get('start_time') != ''){
                $courses->where('start_time', $start_time);
            }
            if($request->get('end_time') != ''){
                $courses->where('end_time', $end_time);
            }
        }
        $courses = $courses->paginate($this->paginate);
        $courses->appends(request()->query());
        $course_person = array();
        foreach ($courses as $course){
            $course_person[$course->id] = $course->person->map(function ($person) {
                return [
                    'id' => $person->id,
                    'image_link' => strpos($person->image_link, 'avatars') === false ? $person->image_link : asset('storage/' . $person->image_link),
                    'name' => $person->name,
                    'email' => $person->email,
                    'birthday' => $person->birthday,
                    'phone_number' => $person->phone_number,
                    'pivot' => $person->pivot,
                ];
            });
        }
        $course_person = json_encode($course_person);
        return view('course.index', [
            'courses' => $courses,
            'name' => $name,
            'description' => $description,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'course_person' => $course_person,
        ]);
    }

    public function get(){
        $courses = Course::query()->select(['id', 'name', 'start_time', 'end_time'])->whereDate('start_time', '>=', DB::raw('CURDATE()'))->orderBy('start_time', 'asc')->get();
        echo $courses;
    }

    public function apiPerson(Request $request){
        $request->validate([
            'id' => 'exists:App\Models\Course,id',
        ]);
        $course = Course::find($request->id);
        $people = $course->person->map(function ($person) {
            return [
                'id' => $person->id,
                'image_link' => strpos($person->image_link, 'avatars') === false ? $person->image_link : asset('storage/' . $person->image_link),
                'name' => $person->name,
                'email' => $person->email,
                'birthday' => $person->birthday,
                'phone_number' => $person->phone_number,
                'pivot' => $person->pivot,
            ];
        });
        echo json_encode($people);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('course.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        try {
            $arr = $request->validated();
            $course = $this->model->create($arr);
            return redirect()->route('course.edit', $course)
                ->with('success', 'Thêm khóa học thành công!');
        }catch (\Exception $exception) {
            return back()->withErrors('Đã xảy ra lỗi trong quá trình thêm. Vui lòng thử lại.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        dd(Str::of('Nuno Maduro')->toHtmlString());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return view('course.edit', [
            'course' => $course,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        try {
            $arr = $request->validated();
            $course->update($arr);
            return redirect()->route('course.update', $course)
                ->with("success", "Cập nhật thông tin thành công!");
        }catch (\Exception $exception) {
            return back()
                ->withErrors('Đã xảy ra lỗi trong quá trình cập nhật. Vui lòng thử lại.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        try {
            DB::table('course_person')->where('course_id', $course->id)->delete();
            $course->delete();
            return back()->with('success', 'Xóa khóa học thành công!');
        }catch (ModelNotFoundException $exception) {
            return back();
        }catch (\Exception $exception) {
            return back()->withErrors('Đã xảy ra lỗi trong quá trình xóa. Vui lòng thử lại.');
        }
    }
    public function export_excel(Request $request){
        return Excel::download(new CourseExport($request), 'course.xlsx');
    }
    public function import_excel(ImportCourseExcelRequest $request){
        $file = $request->file('file')->getRealPath();
        $array = Excel::toArray(new CourseImport, $file);
        for($i = 0; $i < count($array[0]); $i++){
            if(gettype($array[0][$i]['start_time']) !== 'string') {
                $array[0][$i]['start_time'] = Carbon::instance(Date::excelToDateTimeObject($array[0][$i]['start_time']))
                    ->format('Y-m-d');
            }else{
                $array[0][$i]['start_time'] = Carbon::parse($array[0][$i]['start_time'])
                    ->format('Y-m-d');
            }
            if(gettype($array[0][$i]['end_time']) !== 'string'){
                $array[0][$i]['end_time'] = Carbon::instance(Date::excelToDateTimeObject($array[0][$i]['end_time']))
                    ->format('Y-m-d');
            }else{
                $array[0][$i]['end_time'] = Carbon::parse($array[0][$i]['end_time'])
                    ->format('Y-m-d');
            }
        }
        $validator = Validator::make($array[0], [
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
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            foreach ($array[0] as $item) {
                $this->model->create($item);
            }
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            $maxId = DB::table('courses')->max('id');
            DB::statement("ALTER TABLE courses AUTO_INCREMENT = " . ($maxId + 1));
            return redirect()->back()->with('error', 'Thêm các khóa học từ file excel thất bại. Vui lòng thử lại!');
        }
        $sum = $this->model->count();
        if ($sum % $this->paginate == 0) {
            $count = intval($this->model->count() / $this->paginate);
        } else {
            $count = intval($this->model->count() / $this->paginate) + 1;
        }
        return redirect()->route('course.list', ['page' => $count])
            ->with('success', 'Thêm các khóa học từ file excel thành công!');
    }
}
