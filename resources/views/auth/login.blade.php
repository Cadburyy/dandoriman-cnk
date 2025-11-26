@extends('layouts.guest')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="row g-0">
                    <div class="col-12 d-flex align-items-center justify-content-center bg-light p-4">
                        <img src="{{ asset('images/cnk.png') }}" alt="CNK" class="img-fluid" style="max-height: 250px;">
                    </div>

                    <div class="col-12 p-4">
                        <h4 class="mb-4 text-center">{{ __('Dandori Ticket') }}</h4>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Username') }}</label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autocomplete="off" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password" required autocomplete="off">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                {{ __('Login') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection