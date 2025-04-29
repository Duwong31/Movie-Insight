@extends('layouts.app') {{-- Kế thừa layout chính của bạn --}}

@section('title', 'Login - Movie Insight') {{-- Đặt tiêu đề trang --}}

@section('content')
<div class="container mt-5 mb-5"> {{-- Thêm class của Bootstrap hoặc CSS của bạn --}}
    <div class="row justify-content-center">
        <div class="col-md-6"> {{-- Giới hạn chiều rộng form --}}
            <div class="card"> {{-- Sử dụng card style nếu muốn --}}
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    {{-- Hiển thị lỗi validation nếu có --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                     {{-- Hiển thị thông báo flash (ví dụ: sai mật khẩu) --}}
                     @if (session('status')) {{-- Thường dùng cho password reset --}}
                         <div class="alert alert-success" role="alert">
                             {{ session('status') }}
                         </div>
                     @endif
                      @if (session('error')) {{-- Bạn có thể flash lỗi từ controller --}}
                         <div class="alert alert-danger" role="alert">
                             {{ session('error') }}
                         </div>
                     @endif


                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                {{-- @error('email') Hiển thị lỗi ngay dưới input nếu muốn
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror --}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                             <div class="col-md-8 offset-md-4 mt-3">
                                Don't have an account? <a href="{{ route('register') }}">Sign up</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Thêm CSS để style form cho giống trang web của bạn */
    .card { background-color: #333; color: #fff; border: 1px solid #444; }
    .card-header { background-color: #444; border-bottom: 1px solid #555; }
    .form-control { background-color: #555; color: #fff; border: 1px solid #666; }
    .form-control:focus { background-color: #666; color: #fff; border-color: #e70634; box-shadow: none; }
    .form-check-label { color: #ccc; }
    .btn-primary { background-color: #e70634; border-color: #e70634; }
    .btn-primary:hover { background-color: #c4042a; border-color: #c4042a; }
    .btn-link { color: #e70634; text-decoration: none; }
    .btn-link:hover { color: #fff; }
    .alert-danger { background-color: #dc3545; color: white; border: none; }
    .alert-success { background-color: #198754; color: white; border: none; }
    label { color: #ccc; } /* Màu cho label */
</style>
@endpush