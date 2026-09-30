@if (session('success'))
    <div class="flash flash-success" role="status">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="flash flash-error" role="alert">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="flash flash-error" role="alert">
        Please fix the following:
        <ul>
            @foreach ($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif
