<nav class="navbar navbar-default navbar-static-top">
    <div class="">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <img class="hamburger" src="/images/hamburger.svg" data-toggle="modal"  data-target="#modal-menu" width="30">

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/index') }}">Tennis Centar
                {{ config('app.name', 'Laravel') }}
            </a>

            <!-- User/notification Image -->
            <div class="user">
                <img src="/images/user.svg" width="30" data-toggle="modal"  data-target="#modal-menu-user">
                <span class="tag bg-primary">14</span>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('login') }}">Login</a></li>
                    <li><a href="/register">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>