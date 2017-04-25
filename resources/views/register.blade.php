@extends('layout')

@section('header')
	<link rel="stylesheet" href="css/style.css" >
@stop

@section('container')
	<div class="container">
    	<h3> SIGN UP here to use URL SHORTNER </h3>
    	{{ Form::open(['url'=>'/register']) }}
		<p><input type="text" name="uname" placeholder="User Name" required /></p>

	    <p><input type="email" name="email" placeholder="Your Email" required /></p>
    
    	<p><input type="password" name="pass" placeholder="Your Password" required /></p>
    
    	<p><button type="submit" name="btn-signup">Sign Me Up</button></td></p>
    	{{ Form::close() }}
    </div>
@stop