@extends('layouts.app')

@section('title', 'Products')

@section('content')
	<form action="/profit" method="POST">
		@method('GET')
		<div class="container">	
			<div class="row">
				<h2>Select start and end date</h2>
			</div>
			<div class="row">
				<div class="col-md-4">
						<label for="date_from">Start Date</label>
						<input class="form-control" required id="date_from" type="date" name="date_from">
				</div>
				<div class="col-md-4">
					<label for="date_to">End Date</label>	
					<input type="date" required class="form-control" id="date_to" name="date_to">
					
				</div>
				<!-- <div class="col-md-4">
			    	<select class="form-control" id="profitMonth" name="profit_month">
			      		<option>select month</option>
				      	<option value="1">January</option>
				      	<option value="2">February</option>
				      	<option value="3">March</option>
				      	<option value="4">April</option>
				      	<option value="5">May</option>
				      	<option value="6">June</option>
				      	<option value="7">July</option>
				      	<option value="8">August</option>
				      	<option value="9">Septempber</option>
				      	<option value="10">Octuber</option>
				      	<option value="11">November</option>
			      		<option value="12">December</option>
			    	</select>
				</div> -->
				<div class="col-md-4 d-flex flex-column">
					<button id="profit_btn" class="mt-auto btn btn-primary btn-block" type="submit">Find Profit</button>
				</div>
			</div>
		</div>	
	</form>	
	@if(isset($profit))
		<div class="container">
			<div class="row">
				<div class="col-lg-4">

				    <label for="idInput">Profit</label>	
					<input type="text" disabled class="form-control" name="profit" value="{{$profit}}">
				
				</div>
			</div>
		</div>
		<!-- <div class="container">	
			<table class="table table-responsive">
			  	<thead>
			    	<tr>
				      	<th scope="col">Profit</th>
			    	</tr>
			  	</thead>
			  	<tbody>
			    		<tr>
				      		<td scope="row">{{$profit ?? ''}}</td> 	
			    		</tr>
			 	</tbody>
			</table>
		</div> -->
	@endif	

@endsection