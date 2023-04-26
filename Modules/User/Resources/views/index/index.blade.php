@extends('layout')

@section('page_subheader')
	@include('components.subheader', ['title' => 'Thông báo'])
@stop

@section('content')


@stop

@section('after_script')
	<script src="{{asset('static/backend/js/user/list.js')}}" type="text/javascript"></script>
@stop