@extends('layouts.app')

@section('title', '404 Not Found')

@section('content')
<div class="text-center py-5">
    <h1 class="display-3">404</h1>
    <h2 class="mb-4">Page Not Found</h2>
    <p class="text-muted mb-4">Sorry, the page you requested could not be found or may no longer exist.</p>

    @if(Auth::check())
        @php
            $role = Auth::user()->role;
            $route = $role === 'admin'
                ? route('admin.dashboard')
                : ($role === 'editor'
                    ? route('editor.dashboard')
                    : ($role === 'cameraman' ? route('cameraman.dashboard') : route('dashboard')));
        @endphp
        <a href="{{ $route }}" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    @else
        <a href="{{ route('dashboard') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Back to Home
        </a>
    @endif
</div>
@endsection
