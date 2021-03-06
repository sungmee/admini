<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <a class="navbar-brand mr-auto mr-lg-0" href="#">{Admini}</a>&nbsp;&nbsp;&nbsp;&nbsp;
    <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            @include('admini::layouts.menu')
        </ul>
        <form class="form-inline my-2 my-lg-0" action="{{ route('admini.auth.logout') }}" method="POST">
            @csrf
            {{-- <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search"> --}}
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">{{ trans('admini::auth.logout') }}</button>
        </form>
    </div>
</nav>

<!-- <div class="nav-scroller bg-white shadow-sm">
    <nav class="nav nav-underline">
        <a class="nav-link active" href="#">Dashboard</a>
        <a class="nav-link" href="#">
            Friends
            <span class="badge badge-pill bg-light align-text-bottom">27</span>
        </a>
        <a class="nav-link" href="#">Explore</a>
        <a class="nav-link" href="#">Suggestions</a>
        <a class="nav-link" href="#">Link</a>
        <a class="nav-link" href="#">Link</a>
        <a class="nav-link" href="#">Link</a>
        <a class="nav-link" href="#">Link</a>
        <a class="nav-link" href="#">Link</a>
    </nav>
</div> -->