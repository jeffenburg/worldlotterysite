@extends('admin.layout')

@section('title', 'Sign in')

@section('content')
    <div class="card login-card">
        <h1>WorldLotterySite Admin</h1>
        <p class="muted">Sign in to manage content.</p>

        <form method="POST" action="{{ route('admin.login.attempt') }}">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" @class(['is-invalid' => $errors->has('email')])>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <div class="field">
                <label class="checkbox"><input type="checkbox" name="remember" value="1"> Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Sign in</button>
        </form>
    </div>
@endsection
