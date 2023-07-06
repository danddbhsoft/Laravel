@include('layout.header')
<header>
    <title>Thêm người dùng</title>
    <style>
        .needsclick{
            text-align: center;
            margin: 2rem 0;
        }
        #file-upload-preview{
            cursor: pointer;
        }
    </style>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</header>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-8 ms-auto me-auto shadow-lg p-3">
                <h2 class="text-center text-uppercase">Thêm người dùng</h2>
                @if ($errors->any())
                    <div class="alert alert-danger p-0 mt-2">
                        <ul class="mb-1 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="row g-3 needs-validation" id="insertPerson" action="{{route('person.store')}}" method="post" novalidate enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6">
                        <label for="name" class="form-label">Họ và tên<span class="mark-note">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" required>
                        <div class="invalid-feedback" id="name_error">
                            Hãy nhập họ tên của người dùng.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email<span class="mark-note">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="iconMail">@</span>
                            <input type="email" class="form-control" name="email" id="email"
                                   aria-describedby="inputGroupPrepend" required>
                            <div class="invalid-feedback" id="email_error">
                                Hãy nhập email của người dùng.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="birthday" class="form-label">Ngày sinh<span class="mark-note">*</span></label>
                        <input type="date" class="form-control" id="birthday" name="birthday" required>
                        <div class="invalid-feedback" id="birthday_error">
                            Hãy nhập ngày sinh của người dùng.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="phone_number" class="form-label">Số điện thoại<span class="mark-note">*</span></label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                        <div class="invalid-feedback" id="phone_error">
                            Hãy nhập số điện thoại của người dùng.
                        </div>
                    </div>
                    <div id="tab-content">
                        <div class="tab-pane show active" id="file-upload-preview">
                            <div class="fallback">
                                <input name="image_link" type="file" id="image"
                                       accept="image/png,image/jpeg,image/gif,image/jpg,image/bmp,image/svg,image/webp" style="display: none;" required>
                            </div>
                            <div class="dz-message needsclick">
                                <span class="material-icons">
                                    cloud_upload
                                </span>
                                <h3>Thêm ảnh đại diện.</h3>
                                <span class="text-muted font-13">(Chọn đúng định dạng file ảnh <strong>(png, jpg, jpeg, gif, bmp, svg, webp)</strong>,
                                     với kích thước <strong>tối đa</strong> 10MB.)</span>
                                <p id="error-image" class="hide" style="text-align:center; color: red;">Định dạng ảnh phải đúng, không được để trống và không được vượt quá 10MB.</p>
                                <p id="success-image" class="hide" style="text-align:center; color: green;">Hoàn hảo.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button class="btn btn-primary text-uppercase" type="submit">Thêm người dùng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script src={{asset('js/isValidated.js')}}></script>
<script src={{asset('js/index.js')}}></script>
<script src={{asset('js/person/person.index.js')}}></script>
<script src={{asset('js/person/person.create.js')}}></script>
@include('layout.footer')
