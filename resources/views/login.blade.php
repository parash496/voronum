@extends('layout')

@section('header')
	<link rel="stylesheet" href="css/style.css" >
@stop

@section('container')
	<div class="container">
    	<h3> SIGN IN to use URL SHORTNER </h3>
		{{ Form::open(['url'=>'/login/home']) }}
		<p><input type="email" name="email" placeholder="Your Email" required /></p>

    	<p><input type="password" name="pass" placeholder="Your Password" required /></p>
    
    	<p><button type="submit" name="btn-login">Sign In</button>
    
    	<a href="signup">Sign Up Here</a></p>

		{{ Form::close()}}
	</div>	
@stop