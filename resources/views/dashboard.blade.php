@extends('layout')

@section('header')
	<link rel="stylesheet" href="css/style.css" >
@stop

@section('container')
	@if (Session::has('id'))
	<p style="text-align:right;"><a href="{{ asset('home/logout') }}">Logout</a></p>
	<div class="container">
			<h3> WELCOME {{ session('name') }}</h3>
			<h1 class="title"> Shorten a URL </h1>
			{{ Form::open(['url'=>'/make'])}}
			<p><input type="url" name="url" placeholder="Enter a Url" autocomplete="off" />
				<input type="number" name="expiry" min="30" value="30" />Days Valid</p>
			   <input type="submit" value="SHORTEN" />			
			{{ Form::close()}}
		<p><a href="home/page=1">NORMAL URL DASHBOARD</a>
		   <a href="api/register">API URL DASHBOARD</a></p>
	</div>
	@else
		@include('login');
	@endif	
@stop