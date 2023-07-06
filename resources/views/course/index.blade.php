@include('layout.header')
<header>
    <title>Danh sách khóa học</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
</header>
<main>
    <div class="container">
        <div class="row">
            <h2 class="text-center text-uppercase">
                danh sách khóa học trong hệ thống
            </h2>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between">
                        <div class="d-flex justify-content-between">
                            @role('writer')
                            <a class="btn btn-primary mt-2 mb-2" href="{{route('course.insert')}}">Thêm khóa học</a>
                            @endrole
                        </div>
                        <div class="d-flex flex-row">
                            <button class="btn btn-info mt-2 mb-2 me-2" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" id="search" style="height: 37.6px; padding: 6px 12px"><span class="material-icons text-white">search</span></button>
                            @role('writer')
                            <form action="{{route('course.import')}}" method="post" class="d-flex align-items-center" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="file" id="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" hidden>
                                <button class="btn btn-success mt-2 mb-2 me-2" id="btn-import" style="height: 37.6px; padding: 6px 12px" type="button"><span class="material-icons">publish</span></button>
                                <button class="btn btn-light mt-2 mb-2 me-2" type="submit">Import</button>
                            </form>
                            @endrole
                            <a class="btn btn-light mt-2 mb-2" href="{{route('course.export', ['query' => request()->query()])}}">Export</a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body rounded-0 border-bottom-0">
                                <form id="searchForm" class="row" action="{{route('course.list')}}" method="get">
                                    <div class="col-md-3">
                                        <label for="name" class="form-label">Tên khóa học</label>
                                        <input type="search" class="form-control" value="{{($name !== '') ? $name : '' }}" placeholder="Tên khóa học" name="name" id="name">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="description" class="form-label">Mô tả</label>
                                        <input type="search" class="form-control" value="{{($description !== '') ? $description : '' }}" placeholder="Mô tả" name="description" id="description">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="start_time" class="form-label">Ngày bắt đầu</label>
                                        <input type="date" class="form-control" value="{{($start_time !== '') ? $start_time : '' }}" placeholder="Ngày bắt đầu" name="start_time" id="start_time">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="end_time" class="form-label">Ngày kết thúc</label>
                                        <input type="date" class="form-control" value="{{($end_time !== '') ? $end_time : '' }}" placeholder="Ngày kết thúc" name="end_time" id="end_time">
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
                @if(session('error'))
                    <div class="alert alert-success p-2">
                        <p style="color: black; margin-bottom: 0px">{{ session('error') }}</p>
                    </div>
                @endif
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tên</th>
                        <th scope="col">Mô tả</th>
                        <th scope="col">Ngày bắt đầu</th>
                        <th scope="col">Ngày kết thúc</th>
                        <th scope="col">Học viên</th>
                        @role('writer')
                        <th scope="col">Action</th>
                        @endrole
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($courses as $course)
                        <tr>
                            <th scope="row">{{$course->id}}</th>
                            <td>{{$course->name}}</td>
                            <td style="width: 45%">{{$course->description}}</td>
                            <td>{{$course->start_time}}</td>
                            <td>{{$course->end_time}}</td>
                            <td style="position: relative"><button type="button" value="{{route('person.export', ['query' => ['course' => $course->id]]).'!'.$course->id}}" style="border:0; width:24px; height:24px; position: absolute; left: 8px; top: 8px;" data-bs-toggle="modal" data-bs-target="#personList" data-toggle="tooltip" title="Xem danh sách khóa học" class="btn-ViewPeople"><span class="material-icons bg-warning text-white btn-tmp-update" style="position: absolute; left: 0px; top: 0px">visibility</span></button></td>
                            @role('writer')
                            <td class="d-flex flex-row justify-content-left" height="100%"><button style="border:0; width:24px; height:24px; position: relative;" data-toggle="tooltip" title="Chỉnh sửa thông tin khóa học" class="btn-update me-2"><a href="{{route('course.edit', $course)}}"><span class="material-icons bg-success text-white btn-tmp-update" style="position: absolute; left:0; top: 0">drive_file_rename_outline</span></a></button>
                                @role('admin')<form method="post" action="{{route("course.destroy", $course)}}" style="width:24px; height:24px; position: relative;">@csrf @method('DELETE')<button value='1' style="border:0; width:24px; height:24px; position: absolute;" onclick="return confirm('Bạn có chắc chắn muốn xóa khóa học cùng toàn bộ danh sách học viên của khóa học này?')" data-toggle="tooltip" title="Xóa khóa học" class="btn-delete"><span class="material-icons bg-danger text-white btn-tmp-delete" style="position: absolute; left:0; top: 0">delete_outline</span></button></form></td>@endrole
                            @endrole
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $courses->links() }}
            </div>
        </div>
    </div>
    <div class="modal fade" id="personList" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Danh sách học viên của khóa học</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-end">
                        <a class="btn btn-light mt-2 mb-2" id="link-export-person" href="">Export</a>
                    </div>
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
<script src="{{asset('js/pagination.js')}}"></script>
<script src="{{asset('js/course/course.js')}}"></script>
<script src="{{asset('js/course/course.index.js')}}"></script>
<script>
    $('#btn-import').click(function (){
        $('#file').click();
    })
    $('#btnReset').click(function(){
        $("#searchForm input").each(function() {
            $(this).val('');
        })
        $("#searchForm").off("submit").submit();
    })
</script>
@php
    echo '<script> var course_person = '.$course_person.' </script>';
@endphp
@include('layout.footer')
