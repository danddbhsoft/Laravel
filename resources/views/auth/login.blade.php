@include('layout.header')
<h2 class="text-center text-uppercase">Đăng Nhập Hệ Thống</h2>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-5 shadow-lg ms-auto me-auto rounded">
            @if ($errors->any())
                <div class="alert alert-danger p-0 mt-2">
                    <ul class="mb-1 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form class="row g-3 p-3 needs-validation" action="{{route('processLogin')}}" method="post" novalidate>
                @csrf
                <div class="col-md-12">
                    <label for="email" class="form-label">Email <span class="mark-note">*</span></label>
                    <input type="text" class="form-control" name="email" id="email" required>
                    <div class="invalid-feedback">
                        Hãy nhập email đăng nhập của bạn!
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="password" class="form-label">Mật khẩu <span class="mark-note">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="invalid-feedback">
                        Hãy nhập mật khẩu đăng nhập của bạn!
                    </div>
                </div>
                <div class="row mt-2 mb-2">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember"
                                   id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex flex-column">
                    <div class="d-flex justify-content-center mb-2">
                        <button class="btn btn-primary text-uppercase rounded-1" type="submit">Đăng nhập</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layout.footer')
