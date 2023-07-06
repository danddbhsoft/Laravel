@include('layout.header')
<header>
    <title>Thêm khóa học</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</header>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-8 ms-auto me-auto shadow-lg p-3">
                <h2 class="text-center text-uppercase">Thêm khóa học</h2>
                @if ($errors->any())
                    <div class="alert alert-danger p-0 mt-2">
                        <ul class="mb-1 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="row g-3 needs-validation" id="insertCourse" action="{{route('course.store')}}" method="post" novalidate enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12">
                        <label for="name" class="form-label">Tên khóa học<span class="mark-note">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" required>
                        <div class="invalid-feedback" id="name_error">
                            Hãy nhập tên khóa học.
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="description" class="form-label">Mô tả<span class="mark-note">*</span></label>
                        <textarea class="form-control" name="description" id="description" style="height: 200px" required></textarea>
                        <div class="invalid-feedback">
                            Hãy nhập mô tả cho khóa học này.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="start_time" class="form-label">Ngày bắt đầu<span class="mark-note">*</span></label>
                        <input type="date" class="form-control" id="start_time" name="start_time" required>
                        <div class="invalid-feedback" id="start_time_error">
                            Hãy nhập ngày bắt đầu của khóa học.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="end_time" class="form-label">Ngày kết thúc<span class="mark-note">*</span></label>
                        <input type="date" class="form-control" id="end_time" name="end_time" required>
                        <div class="invalid-feedback" id="end_time_error">
                            Hãy nhập ngày kết thúc của khóa học.
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button class="btn btn-primary text-uppercase" type="submit">Thêm khóa học</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script src={{asset('js/isValidated.js')}}></script>
<script src="{{asset('js/course/course.create.js')}}"></script>
@include('layout.footer')
