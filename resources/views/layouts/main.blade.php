<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <title>DemoCart | @yield('title')</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/mdb.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/master.css')}}">
</head>
<body>
    <div class="container-fuild">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">DemoCart</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav">
                <li class="nav-item active">
                  <a class="nav-link" href="{{(request()->route('api_key')) ? route('products', request()->route('api_key')) : route('home')}}">Products</a>
                </li>
                @if (!request()->route('api_key'))
                  <li class="nav-item">
                    <a class="nav-link" href="{{route('access-cart')}}">Access Cart</a>
                  </li>
                  <li class="nav-item">
                    <a class="btn  nav-link btn-success" href="{{route('request-api-key')}}">Request API Key</a>
                  </li>
                @else
                  <li class="nav-item">
                    <a class="nav-link" href="{{route('cart', request()->route('api_key'))}}">My Cart</a>
                  </li>
                @endif
              </ul>
              @if (request()->route('api_key'))
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item user-avatar-container">
                      <a class="nav-link user-avatar" aria-expanded="false" href="{{route('profile', request()->route('api_key'))}}">
                        <strong>{{getenv('DEMO_API_USER')}}</strong>
                      </a>
                    </li>
                    <li class="nav-item logout">
                      <a class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                      </a>
                    </li>
                </ul>
              @endif
            </div>
        </nav>
    </div>
    <main>
        <div class="container">
            {{-- @include('includes.flash-message') --}}
            @yield('main')
        </div>
    </main>
    <footer class="container">

    </footer>
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('js/mdb.min.js')}}"></script>
    <script src="{{asset('js/all.js')}}"></script>
    <script src="{{asset('js/master.js')}}"></script>
</body>
</html>