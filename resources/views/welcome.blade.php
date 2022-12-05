@extends('layout.index')

@section('content')
    <h1>Home</h1>
    <div style="margin-top: 10px;">
        <form action="{{ url('/donasi') }}" method="post" target="_blank">
            @csrf
            <input type="number" min="1000" max="10000" name="nominal">
            <button type="submit">OK</button>
        </form>
    </div>
@endsection
