<div class="navbar navbar-expand-lg navbar-container" style="background-color: #2f4f4f">
    <div class="navbar-align">
        <div style="display: flex; gap: 1em; align-items: center; width: 57em; justify-content: flex-start; padding-right: 80vw">

            <div style="display: flex; align-items: center; margin-right: 2vw">
                <a href="{{ url('/') }}" style="background-color: #ffffff; padding: 0.7em; border-radius: 50%; align-self:center">
                    <img src="{{asset('/images/logo.png')}}" height="40">
                </a>
                <a href="{{ url('/') }}" style="color: white; font-size: 200%; font-weight: bold; margin-left: 0.4em">
                    PlanWiser
                </a>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-bars" style="color: white"></i>
            </button>

            <div class="collapse navbar-collapse navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="navbar-nav mr-auto"><a class="nav-item" href="/admin"> Dashboard </a></li>
                    <li class="navbar-nav mr-auto"><a class="nav-item" href="{{ url('admin/manageUsers') }}">Users</a></li>
                    <li class="navbar-nav mr-auto"><a class="nav-item" href="{{ url('admin/reports') }}">Reports</a></li>
                    <li class="navbar-nav mr-auto"><a class="nav-item" href="{{ url('admin/projects') }}"> Projects </a></li>
                </ul>
            </div>
        </div>
        @if (Auth::check())
            <div class="collapse navbar-collapse navbarSupportedContent" style="justify-content: flex-end;">
                <ul class="navbar-nav">
                    <li class="navbar-nav"><a class="nav-item" href="{{ url('/profile/'.Auth::id()) }} " style="width: max-content"> {{ Auth::user()->username }} </a></li>
                    <li class="navbar-nav"><a class="nav-item" href="{{ url('/logout') }}" style="width: max-content"> Log Out </a></li>
                </ul>
            </div>
        @else
            <div class="collapse navbar-collapse navbarSupportedContent" style="justify-content: flex-end;">
                <ul class="navbar-nav">
                    <a id="profile-btn" class=" nav-item" href="{{ url('/register') }}" style="width: max-content"> Sign Up </a>
                    <a id="logout-btn" class="nav-item" href="{{ url('/login') }}" style="width: max-content"> Log In </a>
                </ul>
            </div>
        @endif
    </div>
</div>

