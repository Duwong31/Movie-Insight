@extends('layouts.app')

@section('title', 'Register - Movie Insight')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8"> {{-- Có thể rộng hơn login --}}
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Sử dụng 'fullname' nếu bạn đã cấu hình trong Model/Controller --}}
                        <div class="row mb-3">
                            <label for="fullname" class="col-md-4 col-form-label text-md-end">{{ __('Full Name') }}</label>
                            <div class="col-md-6">
                                <input id="fullname" type="text" class="form-control @error('fullname') is-invalid @enderror" name="fullname" value="{{ old('fullname') }}" required autocomplete="name" autofocus>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            </div>
                        </div>

                        {{-- Phone (Thêm nếu bạn có trong RegisterController và migration) --}}
                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone Number') }} <small>(Optional)</small></label>
                            <div class="col-md-6">
                                <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="tel">
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                <small class="form-text text-muted">Minimum 8 characters.</small>
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                            <div class="col-md-8 offset-md-4 mt-3">
                                Already have an account? <a href="{{ route('login') }}">Login</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Sử dụng chung style với login hoặc thêm style riêng nếu cần --}}
@push('styles')
<style>
    /* Kế thừa style từ login hoặc thêm style riêng */
    .card { background-color: #333; color: #fff; border: 1px solid #444; }
    .card-header { background-color: #444; border-bottom: 1px solid #555; text-align: center}
    .form-control { background-color: #555; color: #fff; border: 1px solid #666; }
    .form-control:focus { background-color: #666; color: #fff; border-color: #e70634; box-shadow: none; }
    .btn-primary { background-color: #e70634; border-color: #e70634; }
    .btn-primary:hover { background-color: #c4042a; border-color: #c4042a; }
    .btn-link { color: #e70634; text-decoration: none; }
    .btn-link:hover { color: #fff; }
    .alert-danger { background-color: #dc3545; color: white; border: none; }
    label { color: #ccc; }
    .text-muted { color: #aaa !important; }
</style>
@endpush