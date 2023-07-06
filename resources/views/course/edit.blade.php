@php use Carbon\Carbon; @endphp
@include('layout.header')
<header>
    <title>Chỉnh sửa khóa học</title>
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
    <link rel="stylesheet" href="{{asset('css/course.edit.css')}}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</header>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-8 ms-auto me-auto shadow-lg p-3">
                <h2 class="text-center text-uppercase">Chỉnh sửa khóa học</h2>
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
                <form class="row g-3 needs-validation" id="updateCourse" action="{{route('course.update', $course)}}" method="post" novalidate enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">
                        <label for="name" class="form-label">Tên khóa học<span class="mark-note">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{$course->name}}" id="name" required>
                        <div class="invalid-feedback" id="name_error">
                            Hãy nhập tên khóa học.
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="description" class="form-label">Mô tả<span class="mark-note">*</span></label>
                        <textarea class="form-control" name="description" id="description" style="height: 200px" required>{{$course->description}}</textarea>
                        <div class="invalid-feedback">
                            Hãy nhập mô tả cho khóa học này.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="start_time" class="form-label">Ngày bắt đầu<span class="mark-note">*</span></label>
                        <input type="date" class="form-control" id="start_time" value="{{Carbon::createFromFormat('d-m-Y', $course->start_time)->format('Y-m-d')}}" name="start_time" required>
                        <div class="invalid-feedback" id="start_time_error">
                            Hãy nhập ngày bắt đầu của khóa học.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="end_time" class="form-label">Ngày kết thúc<span class="mark-note">*</span></label>
                        <input type="date" class="form-control" id="end_time" value="{{Carbon::createFromFormat('d-m-Y', $course->end_time)->format('Y-m-d')}}" name="end_time" required>
                        <div class="invalid-feedback" id="end_time_error">
                            Hãy nhập ngày kết thúc của khóa học.
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button class="btn btn-success text-uppercase me-2" id="btn-updateStudent" type="button" value="{{$course->id}}" type="button" data-bs-toggle="modal" data-bs-target="#personList">Chỉnh sửa học viên</button>
                        <button class="btn btn-primary text-uppercase" type="submit">Sửa khóa học</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- person list -->
    <div class="modal fade" id="personList" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Danh sách học viên của khóa học</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <nav style="margin-left: 12px; margin-right: 12px">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active rounded-0" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Danh sách học viên</button>
                            <button class="nav-link rounded-0" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Thêm học viên</button>
                        </div>
                    </nav>
                    <div class="tab-content mt-2" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                            <div class="container">
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-light mt-2 mb-2" href="{{route('person.export', ['query' => ['course' => $course->id]])}}">Export</a>
                                </div>
                                <div id="pagination-container"></div>
                                <div id="myList" class="container">

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade mt-2" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                            <div class="container mb-2">
                                <form class="row mb-4" id="searchForm">
                                    <div class="col-md-3">
                                        <label for="name" class="form-label">Họ và tên</label>
                                        <input type="search" class="form-control rounded-0" placeholder="Tên" name="name" id="name_search">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="search" class="form-control rounded-0" placeholder="Email" name="email" id="email_search">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="email" class="form-label">Số điện thoại</label>
                                        <input type="search" class="form-control rounded-0" placeholder="Số điện thoại" name="phone_number" id="phone_number_search">
                                    </div>
                                    <div class="col-md-1">
                                        <label for="btn-resetSearch" class="form-label" style="visibility: hidden">Reset</label>
                                        <br>
                                        <button class="btn btn-danger rounded-0 text-white" id="btn-resetSearch" type="button">Reset</button>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="btn-searchPerson" class="form-label" style="visibility: hidden">Button</label>
                                        <br>
                                        <button class="btn btn-info rounded-0 text-white" id="btn-searchPerson" type="button">Tìm kiếm</button>
                                    </div>
                                </form>
                                <div class="row g-3 justify-content-between">
                                    <label for="people" class="form-label mt-1 mb-1">Danh sách học viên</label>
                                    <div class="col-md-12 mt-0 trademark ms-2 p-2 border border-bottom-0"  style="width: calc(100% - 16px);" id="people">

                                    </div>
                                    <form class="row g-3 needs-validation mt-0 p-2 pt-0" id="insertPersonCourse" method="post" action="{{route('course_person.store_list', $course)}}" novalidate>
                                        @csrf
                                        <div class="col-md-12 mt-0 trademark ms-2 p-2 border">
                                            <label for="people" class="form-label mt-1 mb-2">Đã chọn</label>
                                            <label for="people" id="removeList" style="cursor: pointer" class="form-label mt-1 mb-2 text-danger text-decoration-underline">Bỏ chọn</label>
                                            <div id="choosePeople">

                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2 d-flex justify-content-end"><button id="insertCoursePerson" class="btn btn-primary rounded-0" type="submit">Thêm người dùng vào khóa học</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
                     aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <rect width="100%" height="100%" fill="#007aff"></rect>
                </svg>
                <strong class="me-auto toast-title">Hệ thống</strong>
                <small>Vừa xong</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div>
</main>
@if(auth()->check() && auth()->user()->hasRole('admin'))
    @php
        echo "<script>var role = 'admin';</script>";
    @endphp
@else
    @php
        echo "<script>var role = '';</script>";
    @endphp
@endif
<script src={{asset('js/isValidated.js')}}></script>
<script src="{{asset('js/pagination.js')}}"></script>
<script src="{{asset('js/index.js')}}"></script>
<script src="{{asset('js/course/course.js')}}"></script>
<script src="{{asset('js/course/course.update.js')}}"></script>
@php
echo '<script>var course = '.json_encode($course).'</script>';
@endphp
@include('layout.footer')
