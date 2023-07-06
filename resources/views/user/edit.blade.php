@php use Carbon\Carbon; @endphp
@include('layout.header')
@php
    echo '<script>var old_email = "'.$person->email.'"</script>';
    echo '<script>var id = "'.$person->id.'"</script>';
@endphp
<header>
    <title>Chỉnh sửa người dùng</title>
    <style>
        .needsclick {
            text-align: center;
            margin: 2rem 0;
        }

        #file-upload-preview {
            cursor: pointer;
        }
    </style>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
</header>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-8 ms-auto me-auto shadow-lg p-3">
                <h2 class="text-center text-uppercase">Cập nhật thông tin cho người dùng {{$person->id}}</h2>
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
                <form class="row g-3 needs-validation" id="updatePerson" action="{{route('person.update', $person)}}"
                      method="post" novalidate enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12 d-flex justify-content-center">
                        @if(strpos($person->image_link, 'avatars') === false)
                            <img class="avatars rounded" data-bs-toggle="modal" data-bs-target="#exampleModal" style="cursor:pointer; border: 5px solid green" src="{{$person->image_link}}" width="20%" height="auto">
                        @else
                            <img class="avatars rounded" data-bs-toggle="modal" data-bs-target="#exampleModal" style="cursor:pointer; border: 5px solid green" src="{{asset('storage/' . $person->image_link)}}" width="20%" height="auto">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="form-label">Họ và tên<span class="mark-note">*</span></label>
                        <input type="text" class="form-control" value="{{$person->name}}" name="name" id="name"
                               required>
                        <div class="invalid-feedback" id="name_error">
                            Hãy nhập họ tên của người dùng.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email<span class="mark-note">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="iconMail">@</span>
                            <input type="email" class="form-control" value="{{$person->email}}" name="email" id="email"
                                   aria-describedby="inputGroupPrepend" required>
                            <div class="invalid-feedback" id="email_error">
                                Hãy nhập email của người dùng.
                            </div>
                        </div>
                    </div>
                    @php
                        $birthday = Carbon::createFromFormat('d-m-Y', $person->birthday)->format('Y-m-d');
                    @endphp
                    <div class="col-md-6">
                        <label for="birthday" class="form-label">Ngày sinh<span class="mark-note">*</span></label>
                        <input type="date" class="form-control" id="birthday" value="{{$birthday}}"
                               name="birthday" required>
                        <div class="invalid-feedback" id="birthday_error">
                            Hãy nhập ngày sinh của người dùng.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="phone_number" class="form-label">Số điện thoại<span
                                class="mark-note">*</span></label>
                        <input type="text" class="form-control" id="phone_number" value="{{$person->phone_number}}"
                               name="phone_number" required>
                        <div class="invalid-feedback" id="phone_error">
                            Hãy nhập số điện thoại của người dùng.
                        </div>
                    </div>
                    <div id="tab-content">
                        <div class="tab-pane show active" id="file-upload-preview">
                            <div class="fallback">
                                <input name="image_link" type="file" id="image"
                                       accept="image/png,image/jpeg,image/gif,image/jpg,image/bmp,image/svg,image/webp"
                                       style="display: none;" required>
                            </div>
                            <div class="dz-message needsclick">
                                <span class="material-icons fs-1">
                                    cloud_upload
                                </span>
                                <h3 class="text-uppercase">Chỉnh sửa ảnh đại diện</h3>
                                <span class="text-muted font-13">(Chọn đúng định dạng file ảnh <strong>(png, jpg, jpeg, gif, bmp, svg, webp)</strong>,
                                     với kích thước <strong>tối đa</strong> 10MB.)</span>
                                <p id="error-image" class="hide" style="text-align:center; color: red;">Định dạng ảnh
                                    phải đúng, không được để trống và không được vượt quá 10MB.</p>
                                <p id="success-image" class="hide" style="text-align:center; color: green;">Hoàn
                                    hảo.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button class="btn btn-success text-uppercase me-2" id="btn-updateCourse" value="{{$person->id}}" type="button" data-bs-toggle="modal" data-bs-target="#courseList">Chỉnh sửa khóa học</button>
                        <button class="btn btn-primary text-uppercase" type="submit">Cập nhật</button>
                    </div>
                </form>
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
                    <div class="container mb-2">
                        <form class="row g-3 needs-validation" action="{{route('course_person.store')}}" novalidate>
                            <label for="course" class="form-label mt-1 mb-1">Khóa học</label>
                            <div class="col-md-6 mt-0">
                                <select class="form-select rounded-0" id="course" required>
                                    <option selected disabled value="">Choose...</option>
                                    <option>...</option>
                                </select>
                                <div class="invalid-feedback">
                                    Vui lòng chọn 1 khóa học để thêm.
                                </div>
                            </div>
                            <div class="col-md-6 mt-0"><button id="insertCoursePerson" class="btn btn-primary rounded-0" type="button">Thêm khóa học vào danh sách</button></div>
                        </form>
                    </div>
                    <div class="container">
                        <div id="pagination-container"></div>
                        <div id="myList" class="container">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</main>
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
<script src={{asset('js/index.js')}}></script>
<script src={{asset('js/person/person.js')}}></script>
<script src={{asset('js/person/person.update.js')}}></script>
<script>
    $(".avatars").click(function(){
        $('#avatar').attr('src', $(this).attr('src'));
    })
</script>
@include('layout.footer')
