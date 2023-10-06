<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href ="{{asset('css/app.css')}}">
	 
	 <!-- <script src="https://cdn.tailwindcss.com"></script> -->
	@livewireStyles
	<title>@yield('title')</title>
	
</head>
<body>
	@auth
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
		<div style="padding: 0px;" class="container">
		 	<a class="navbar-brand" href="{{ url('') }}">ZAIRA CROCKERY</a>
		  	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		    	<span class="navbar-toggler-icon"></span>
		  	</button>

		  	<div class="collapse navbar-collapse" id="navbarSupportedContent">

		    	<ul class="navbar-nav mr-auto">

		      		<li class="nav-item active">
		        		<a class="nav-link" href="{{ url('/calculations') }}">Billing<span class="sr-only">(current)</span></a>
		      		</li>
		      		<!-- <li class="nav-item active">
		        		<a class="nav-link" href="product">Products<span class="sr-only">(current)</span></a>
		      		</li> -->
		      		<li class="nav-item active">
		        		<a class="nav-link" href="/order">Orders</a>
		      		</li>
		      		
		      		<li class="nav-item dropdown">
		        		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		          		Products
		        		</a>
		        		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
		          			<a class="dropdown-item" href="/product">Show Products</a>
		          			<div class="dropdown-divider"></div>
		          			<!-- <a class="dropdown-item" href="{{ url('/product/create') }}">Create Product</a> -->
		          			<!-- <div class="dropdown-divider"></div> -->
		          			<a class="dropdown-item" href="{{ url('/product_return') }}">Return Products</a>
		          			<div class="dropdown-divider"></div>
		          			<a class="dropdown-item" href="/Low_Product">Low Stock Products</a>
		        		</div>
		      		</li>

	      			<li class="nav-item dropdown">
	      		  		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	      		    		Purchase
	      		  		</a>
	      		  		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
      		    			<a class="dropdown-item" href="/purchase">Insert Purchase Record</a>
      		    			<div class="dropdown-divider"></div>
      		    			<a class="dropdown-item" href="{{ url('/purchases') }}">All Purchases</a>
      		    			<div class="dropdown-divider"></div>
      		    			<a class="dropdown-item" href="{{ url('/product_return') }}">Purchase Return</a>
      		    			
      		    		</div>
	      			</li>
	      			<li class="nav-item dropdown">
	      		  		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	      		    		sale
	      		  		</a>
	      		  		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
      		    			<a class="dropdown-item" href="/sale">Insert Sales Record</a>
      		    			<div class="dropdown-divider"></div>
      		    			<a class="dropdown-item" href="/sales">Sale records</a>
      		    			
      		    			
      		    		</div>
	      			</li>
		      		<!-- <li class="nav-item dropdown">
		        		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		          		vendors
		        		</a>
		        		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
		          			<a class="dropdown-item" href="/vendor">Show Vendors</a>
		          			 
		          			<a class="dropdown-item" href="{{ url('/vendor/create') }}">Create Vendors</a>
		          			
		   
		        		</div>
		      		</li> -->
		     	 	<!-- <li class="nav-item">
		        		<a class="nav-link disabled" href="#">Disabled</a>
		      		</li> -->
		      		<li class="nav-item active">
		        		<a class="nav-link" href="/profit">Profit</a>
		      		</li>
		      		
		    	</ul>
		    	
		    	<!-- <form action="{{ url('logout') }}" method="POST">
	      		    @CSRF
	      		    
	      			<a href="#">
	      		    	<button class="btn btn-danger close-modal">Sign Out</button>
	      			</a>
		      	</form> -->
		    	<ul class="navbar-nav">
		    		<li class="nav-item">
		    			<a class="nav-link" href="#">
		    				Welcome:<span>{{Auth::user()->name??''}}</span>
		    			</a>
		    		</li>
		    		<li class="nav-item text-nowrap">
		    			<form id="logout-form" action="{{ route('logout') }}" method="POST">
		    			@csrf
		    			<a class="nav-link" href="{{ route('logout')}}"
		    			
		    			onclick="event.preventDefault();
		    			document.getElementById('logout-form').submit();">Sign out
		    			</a>
		    		   	
		    		    </form>	
		    		</li>
		    	</ul>
		  	</div>
		</div>
	</nav>
	@endauth
    @yield('content')

	<script src="{{asset('js/app.js')}}"></script>
	<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script defer>
	<!-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
	@livewireScripts
	
</body>
</html>