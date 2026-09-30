<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') · WorldLotterySite</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
@auth
<div class="admin">
    <aside class="sidebar">
        <div class="brand">WorldLotterySite <small>Admin</small></div>
        <nav>
            <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Dashboard</a>
            <a href="{{ route('admin.lotteries.index') }}" @class(['active' => request()->routeIs('admin.lotteries.*')])>Lotteries</a>
            <a href="{{ route('admin.pages.index') }}" @class(['active' => request()->routeIs('admin.pages.*')])>Pages</a>
            <a href="{{ route('admin.posts.index') }}" @class(['active' => request()->routeIs('admin.posts.*')])>Posts</a>
        </nav>
        <div class="sidebar-footer">
            <span class="user">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn-link" style="color: inherit;">Sign out</button>
            </form>
        </div>
    </aside>

    <main class="content">
        <div class="page-header">
            <h1>@yield('title')</h1>
            <div class="actions">@yield('actions')</div>
        </div>

        @include('admin.partials.flash')

        @yield('content')
    </main>
</div>
@else
<div class="login-wrap">
    @include('admin.partials.flash')
    @yield('content')
</div>
@endauth
</body>
</html>
