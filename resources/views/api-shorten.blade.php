@extends('layout')

@section('header')
	<link rel="stylesheet" href="{{ asset('css/style.css') }}" >
@stop


@section('container')
    @if (Session::has('id'))
    	<div style="text-align:right;">
    		<p><a href="{{ asset('home/logout') }}">Logout</a></p>
    		<p><a href="{{ asset('dashboard') }}">Home Dashboard</a></p>
    		<p><a href="{{ asset('home/page=1') }}">Normal Url Dashboard</a></p>
    	</div>
		<div class="container">
			<h3> WELCOME {{ session('name') }}</h3>
			<h1 class="title">API DASHBOARD </h1>
			<p><h4> API KEY: {{ $getApiKey->api_key }} </h4>
			<a href="{{ asset('api/regenerate') }}"> REGENERATE KEY</a></p>
			@if (count($paginatedView) >= 1)
			<table style="width:100%">
				<tr>
					<th> URL </th>
					<th> SHORTENED URL </th>
					<th> STATUS </th>
					<th> No. OF HITS </th>
				</tr>
				@for($i=0;$i<$paginatedView['numOfLinks'];$i++)
                <tr>
                	<td>{{ $paginatedView['links'][$i]->url }}</td>
					<td>
					@if ($paginatedView['links'][$i]->action == 1)
						<a href="{{ URL::route('web', [$paginatedView['links'][$i]->hash, $currentPage])}}" target="_blank">{{env('URL_PATH')}}/{{ $paginatedView['links'][$i]->hash }}</a>
					@else
						{{env('URL_PATH')}}/{{ $paginatedView['links'][$i]->hash }}
					@endif
					</td>
					<td><form action="{{ URL::route('apistate', [$paginatedView['links'][$i]->hash, $currentPage])}}" method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						@if ($paginatedView['links'][$i]->action == 1)
  							<input type="submit" name="action" value="disable">
  						@else
  							<input type="submit" name="action" value="enable">
						@endif
						</form>
					</td>
					<td>{{ $paginatedView['links'][$i]->redirect }}</td>
				</tr>
				@endfor
			</table>
			@else
				<h3>No URL to Display</h3>
			@endif
			<p>
				@for($i=1; $i<=$paginatedView['numOfPages']; $i++)
					@if($i==$currentPage)
						{{ $i }}
					@else
						<a href="{{ URL::route('paginate', [$i])}}">{{ $i }}</a>
					@endif	
				@endfor
			</p>			
		</div>
	@else
		@include('login');
	@endif
@stop