<!DOCTYPE html>
<html>
	<head>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<title>URL Shortner</title>
		@yield('header')
	</head>
	<body>
		@yield('container')
		@yield('footer')
	</body>
</html>