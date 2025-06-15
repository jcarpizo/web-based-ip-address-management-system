@extends('layout')
@section('content')
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <main class="flex-shrink-0">
        <div id="root"></div>
    </main>
@endsection
