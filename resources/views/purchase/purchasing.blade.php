@extends('layouts.app')

@section('title', 'Purchasing')

@section('content')
	<div class="container">
		<div class="row">
			<div class="mx-auto col-sm-10">
				@if(Session::has('alert-success'))
					<div class="flash-message">	
	     				<p class="alert alert-success">{{ Session::get('alert-success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
	     			</div>
      			@endif
      			@if(Session::has('alert-danger'))
      				<div class="flash-message">
      					<p class="alert alert-danger">
      						{{Session::get('alert-danger') }}
      						<a href="#" class="close" data-dismiss="alert"aria-label="close">&times;
      						</a>
      					</p>
      				</div>
      			@endif
      			<div class="mt-5">	  
					@livewire('purchasing')
      			</div>				      								
			</div>
		</div>	
	</div>
@endsection