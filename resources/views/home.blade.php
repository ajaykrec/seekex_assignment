@extends('layout.main')

@section('page-content')
<div class="container">
    @include('bucket.list')
    @include('ball.list')

    @include('suggestion_form')
</div>
@endsection