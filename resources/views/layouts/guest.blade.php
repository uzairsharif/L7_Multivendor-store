
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- <link rel="icon" href="https://getbootstrap.com/docs/4.0/assets/img/favicons/favicon.ico"> -->
    <link rel="icon" href ="{{asset('favicon.ico')}}">

    <title>@yield('title')</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/carousel/">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href ="{{asset('css/guestHome.css')}}">
    <!-- <link href="../../dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- Custom styles for this template -->
    <!-- <link href="carousel.css" rel="stylesheet"> -->
  </head>
  <body>
    <!-- <div class="container"> -->
      
      <!-- <header> -->
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
          <div class="container">
            <a class="navbar-brand" href="{{url('/')}}">Zaira Crockery</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
              <ul class="navbar-nav mr-auto">
                <!-- <li class="nav-item active">
                  <a class="nav-link" href="#">Home<span class="sr-only">(current)</span></a>
                </li> -->
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('products_page') }}">Products</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('product.cart') }}">Cart</a>
                </li>
                <!-- Contact page pr abhi kam krna hai..  -->
                <li class="nav-item">
                  <a class="nav-link" href="#">Contact</a>
                </li>
                <!-- Blog page pr abhi kam krna hai. -->
                <li class="nav-item">
                  <a class="nav-link" href="#">Blog</a>
                </li>


                @guest
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('register') }}">Sign up</a>
                </li>
                @endguest
                @auth
                <li class="nav-item">
                  <form id="guest-logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <a class="nav-link" href="{{ route('logout')}}"
                    onclick="event.preventDefault();
                    document.getElementById('guest-logout-form').submit();">Sign out
                    </a>
                  
                  </form> 
                </li>
                @endauth
              </ul>
              <!-- <form class="form-inline mt-2 mt-md-0">
                <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
              </form> -->
            </div>
          </div>
        </nav>
      <!-- </header> -->
    <!-- </div> -->
      @yield('content')
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    <!-- <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script> -->
    <!-- <script src="../../assets/js/vendor/popper.min.js"></script> -->
    <!-- <script src="../../dist/js/bootstrap.min.js"></script> -->
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <!-- <script src="../../assets/js/vendor/holder.min.js"></script> -->
    
    <script src="{{asset('js/app.js')}}"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script defer>
  </body>
</html>
