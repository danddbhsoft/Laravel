<?php

namespace App\Http\Controllers;

use App\Exports\CourseExport;
use App\Exports\PersonExport;
use App\Models\Course;
use App\Models\Person;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Psy\Util\Json;

class PersonController extends Controller
{
    private $model;
    private $paginate;
    public function __construct()
    {
        $this->model = (new Person())->query();
        $this->paginate = 10;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $min_age = $request->get('min_age');
        $max_age = $request->get('max_age');
        $phone_number = $request->get('phone_number');
        $course = $request->get('course');
        $people = Person::query()
            ->where('name', 'like', '%'.$name.'%')
            ->where('email', 'like', '%'.$email.'%')
            ->where('phone_number', 'like', '%'.$phone_number.'%');
        if($min_age != '' && $max_age != ''){
            $people->whereRaw("TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN ? AND ?", [$min_age, $max_age]);
        }
        if($course != ''){
            $people->join('course_person', 'person.id', '=', 'course_person.person_id')
                ->where('course_id', $course);
        }
        $people = $people->paginate($this->paginate);
        $courses = Course::query()->get();
        $course_person = array();
        foreach ($people as $person){
            $course_person[$person->id] = $person->courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'description' => $course->description,
                    'start_time' => $course->start_time,
                    'end_time' => $course->end_time,
                    'pivot' => $course->pivot,
                ];
            });
        }
        $course_person = json_encode($course_person);
        $people->appends(request()->query());
        return view("user.index", [
            'people'=>$people,
            'course_person' => $course_person,
            'name' => $name,
            'email' => $email,
            'min_age' => $min_age,
            'max_age' => $max_age,
            'phone_number' => $phone_number,
            'course' => $course,
            'courses' => $courses,
        ]);
    }

    public function apiCourse(Request $request){
        $request->validate([
            'id' => 'exists:App\Models\Person,id',
        ]);
        $person = Person::find($request->id);
        $courses = $person->courses->map(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->name,
                'description' => $course->description,
                'start_time' => $course->start_time,
                'end_time' => $course->end_time,
                'pivot' => $course->pivot,
            ];
        });
        echo json_encode($courses);
    }

    public function get(){
        $people = Person::query()->select(['id', 'image_link', 'name', 'email', 'phone_number'])->get();
        echo $people;
    }

    public function checkMail(Request $request){
        $request->validate([
            'email' => 'email',
        ]);
        $email = $request->input('email');
        if(Person::where('email', $email)->exists()){
            echo false;
        }else{
            echo true;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("user.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonRequest $request)
    {
        try {
            $arr = $request->validated();
            $image_link = $arr['image_link'];
            $path = Storage::disk('public')->putFile('avatars', $image_link);
            $arr['image_link'] = $path;
            $person = $this->model->create($arr);
            $newPath = 'avatars/' . $person->id . '/' . basename($path);
            $record = Person::findOrFail($person->id);
            $data = $request->only(['image_link']);
            $data['image_link'] = $newPath;
            $record->update($data);
            Storage::disk('public')->move($path, $newPath);
            return redirect()->route('person.update', $person)->with('success', 'Thêm thành công!');
        }catch (\Exception $exception) {
            return back()->withErrors('Đã xảy ra lỗi trong quá trình thêm. Vui lòng thử lại.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person)
    {
        try {
            return view('user.edit', ['person' => $person]);
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('person.list');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonRequest $request, Person $person)
    {
        try {
            $arr = $request->validated();
            if($request->has('image_link')){
                $old_link = $person->image_link;
                if (Storage::disk('public')->exists($old_link)) {
                    Storage::disk('public')->delete($old_link);
                    $path = Storage::disk('public')->putFile('avatars/'.$person->id, $request->image_link);
                    $arr['image_link'] = $path;
                    $person->update($arr);
                    return redirect()->route('person.update', $person)->with("success", "Cập nhật thông tin thành công!");
                }else{
                        $path = Storage::disk('public')->putFile('avatars/' . $person->id, $request->image_link);
                        $arr['image_link'] = $path;
                        $person->update($arr);
                        return redirect()->route('person.update', $person)->with("success", "Cập nhật thông tin thành công!");
                }
            }else{
                $person->update($arr);
                return redirect()->route('person.update', $person)->with("success", "Cập nhật thông tin thành công!");
            }
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('person.list');
        }catch (\Exception $exception) {
            return back()->withErrors('Đã xảy ra lỗi trong quá trình cập nhật. Vui lòng thử lại.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        try {
            DB::table('course_person')->where('person_id', $person->id)->delete();
            $person->delete();
            return back()->with('success', 'Xóa người dùng thành công!');
        }catch (ModelNotFoundException $exception) {
            return back();
        }catch (\Exception $exception) {
            return back()->withErrors('Đã xảy ra lỗi trong quá trình xóa. Vui lòng thử lại.');
        }

    }
    public function export_excel(Request $request){
        return Excel::download(new PersonExport($request), 'person.xlsx');
    }
}
