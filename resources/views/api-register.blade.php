@extends('layout')

@section('header')
	<link rel="stylesheet" href="css/style.css" >
@stop

@section('container')
    <a href="home/logout">Logout</a>
	
    	<h3>  REGISTER HERE to use API URL SHORTNER </h3>
    	{{ Form::open(['url'=>'api/registeration']) }}
		<p>ROLE</p>
        <input type="text" name="role" required />

	    <p>COMPANY</p>
        <input type="text" name="company"  required />
    
        <p>APPLICATION NAME</p>
        <input type="text" name="app_name" required />

        <p>APPLICATION URL</p>
        <input type="text" name="app_url" required />

        <p>APPLICATION DESCRIPTION</p>
        <input type="text" name="app_description" required />

        <p><input type="submit" name="register" value="Register"/></p>
    	{{ Form::close() }}    
@stop