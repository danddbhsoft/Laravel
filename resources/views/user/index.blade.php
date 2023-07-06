@include('layout.header')
<header>
    <title>Danh sách người dùng</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
</header>
<main>
    <div class="container">
        <div class="row">
            <h2 class="text-center text-uppercase">
                danh sách người dùng trong hệ thống
            </h2>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between">
                        <div>
                            @role('writer')
                            <a class="btn btn-primary mt-2 mb-2" href="{{route("person.insert")}}">Thêm người dùng</a>
                            @endrole
                        </div>
                        <div>
                            <a class="btn btn-light mt-2 mb-2" href="{{route('person.export', ['query' => request()->query()])}}">Export</a>
                            <button class="btn btn-info mt-2 mb-2 me-2" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" id="search" style="height: 37.6px; padding: 6px 12px"><span class="material-icons text-white">search</span></button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body rounded-0 border-bottom-0">
                                <form id="searchForm" class="row" action="{{route('person.list')}}" method="get">
                                    <div class="col-md-3">
                                        <label for="name" class="form-label">Họ và tên</label>
                                        <input type="search" class="form-control rounded-0" value="{{$name != '' ? $name : ''}}" placeholder="Họ và tên" name="name" id="name">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="search" class="form-control rounded-0" value="{{$email != '' ? $email : ''}}" placeholder="Email" name="email" id="email">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="start_time" class="form-label">Độ tuổi</label>
                                        <div class="d-flex flex-row">
                                            <div class="col-md-6 me-2">
                                                <input type="search" class="form-control rounded-0" value="{{$min_age != '' ? $min_age : ''}}" placeholder="Min" name="min_age" id="min_age">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="search" class="form-control rounded-0" value="{{$max_age != '' ? $max_age : ''}}" placeholder="Max" name="max_age" id="max_age">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="phone_number" class="form-label">Số điện thoại</label>
                                        <input type="search" class="form-control rounded-0" value="{{$phone_number != '' ? $phone_number : ''}}" placeholder="Số điện thoại" name="phone_number" id="phone_number">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="course" class="form-label">Khóa học</label>
                                        <select class="form-select rounded-0" id="course" name="course">
                                            <option selected disabled value="">Choose...</option>
                                            @foreach($courses as $item)
                                                @if($course != '' && $item->id == $course)
                                                    echo "<option selected value="{{$item->id}}">{{$item->name}}</option>";
                                                @else
                                                    echo "<option value="{{$item->id}}">{{$item->name}}</option>";
                                                @endif
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end mt-2">
                                        <button class="btn btn-danger me-2" id="btnReset" type="button">Reset</button>
                                        <button class="btn btn-primary">Tìm kiếm</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger p-0 mt-2">
                        <ul class="mb-1 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success p-2">
                        <p style="color: black; margin-bottom: 0px">{{ session('success') }}</p>
                    </div>
                @endif
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Ảnh</th>
                        <th scope="col">Họ tên</th>
                        <th scope="col">Email</th>
                        <th scope="col">Ngày sinh</th>
                        <th scope="col">Điện thoại</th>
                        <th scope="col">Khóa học</th>
                        @role('writer')
                        <th scope="col">Action</th>
                        @endrole
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($people as $person)
                        <tr>
                            <th scope="row">{{$person->id}}</th>
                            @if(strpos($person->image_link, 'avatars') === false)
                                <td><img class="avatars" style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#exampleModal" src="{{$person->image_link}}" width="32" height="24"></td>
                            @else
                                <td><img class="avatars" style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#exampleModal" src="{{asset('storage/' . $person->image_link)}}" width="32" height="24"></td>
                            @endif
                            <td>{{$person->name}}</td>
                            <td>{{$person->email}}</td>
                            <td>{{$person->birthday}}</td>
                            <td>{{substr($person->phone_number, 0, 4).' '.substr($person->phone_number, 4, 3).' '.substr($person->phone_number, 7, 3)}}</td>
                            <td><form action="{{route('person.api')}}">@csrf<button type="button" value="{{$person->id}}" style="border:0; width:24px; height:24px; position: relative;" data-bs-toggle="modal" data-bs-target="#courseList" data-toggle="tooltip" title="Xem danh sách khóa học" class="btn-ViewCourse"><span class="material-icons bg-warning text-white btn-tmp-update" style="position: absolute; left:0; top: 0">visibility</span></button></form></td>
                            @role('writer')
                            <td class="d-flex flex-row justify-content-left"><button style="border:0; width:24px; height:24px; position: relative;" data-toggle="tooltip" title="Chỉnh sửa thông tin khách hàng" class="btn-update me-2"><a href="{{route('person.edit', $person)}}"><span class="material-icons bg-success text-white btn-tmp-update" style="position: absolute; left:0; top: 0">drive_file_rename_outline</span></a></button>
                                @role('admin')
                                <form method="post" action="{{route("person.destroy", $person)}}" style="width:24px; height:24px; position: relative;">@csrf @method('DELETE')<button value='1' style="border:0; width:24px; height:24px; position: absolute;" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')" data-toggle="tooltip" title="Xóa khách hàng" class="btn-delete"><span class="material-icons bg-danger text-white btn-tmp-delete" style="position: absolute; left:0; top: 0">delete_outline</span></button></form></td>
                                @endrole
                            @endrole
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $people->links() }}
            </div>
        </div>
    </div>

    <!-- img -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ảnh đại diện</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                    <img src="" id="avatar" width="100%" height="auto">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- course list -->
    <div class="modal fade" id="courseList" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Danh sách khóa học của người dùng</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="pagination-container"></div>
                    <div id="myList" class="container">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    $(".avatars").click(function(){
        $('#avatar').attr('src', $(this).attr('src'));
    })
</script>
@php
    echo '<script> var course_person = '.$course_person.' </script>';
@endphp
<script>
    $('#btnReset').click(function(){
        $("#searchForm input").each(function() {
            $(this).val('');
        })
        $("#course").val('');
        $("#searchForm").off("submit").submit();
    })
</script>
<script src="{{asset('js/pagination.js')}}"></script>
<script src={{asset('js/index.js')}}></script>
<script src={{asset('js/person/person.js')}}></script>
<script src={{asset('js/person/person.index.js')}}></script>
@include('layout.footer')
