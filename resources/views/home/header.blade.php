<div class="header_main">
            <div class="mobile_menu">
               <nav class="navbar navbar-expand-lg navbar-light bg-light">
                  <div class="logo_mobile"><a href="index.html"><img src="images/logo.png"></a></div>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarNav">
                     <ul class="navbar-nav">
                        <li class="nav-item">
                           @guest
                              <a class="nav-link" href="{{ route('homepage') }}">Home</a>
                           @else
                              <a class="nav-link" href="{{ route('home') }}">Home</a>
                           @endguest
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="{{ route('about') }}">About</a>
                        </li>
                        <li class="nav-item">
                           @auth
                              <a class="nav-link" href="{{ route('services') }}">Services</a>
                           @endauth
                        </li>
                        <li class="nav-item">
                           @auth
                               <a class="nav-link" href="{{ route('addData') }}">Add Data</a>
                           @endauth
                        </li>
                        <li class="nav-item">
                           <a class="nav-link " href="#">Contact</a>
                        </li>
                     </ul>
                  </div>
               </nav>
            </div>
            <div class="container-fluid">
               <div class="logo"><a href="index.html"><img src="images/logo.png"></a></div>
               <div class="menu_main">
                  <ul>
                     <li>
                        @guest
                           <a href="{{ route('homepage') }}">Home</a>
                        @else
                           <a href="{{ route('home') }}">Home</a>
                        @endguest
                     </li>

                     <li> <a  href="{{ route('about') }}">About</a> </li>
                     <li>
                        @auth
                           <a href="{{ route('services') }}">Services</a>
                        @endauth
                     </li>

                     <li>
                        @auth
                         <a href="{{ route('addData') }}">Add Data</a>
                        @endauth
                     </li>

                     @if (Route::has('login'))
                     @auth

                     <li><x-app-layout></x-app-layout></li>
    
                     @else
                     <li><a href="{{route('login')}}">Login</a></li>
                     <li><a href="{{route('register')}}">Register</a></li>

                     @endauth
                     @endif
                  </ul>
               </div>
            </div>
         </div>