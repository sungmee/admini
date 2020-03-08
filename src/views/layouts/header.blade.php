@php
  $path = request()->getPathInfo();
@endphp

<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
  <a class="navbar-brand mr-auto mr-lg-0" href="#">{Admini}</a>&nbsp;&nbsp;&nbsp;&nbsp;
  <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link{{ '/admini/news' == $path ? ' active' : '' }}" href="{{ route('admini.posts.index', ['type' => 'news']) }}">{{ trans('admini::post.editor.news_index') }}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link{{ '/admini/news/create' == $path ? ' active' : '' }}" href="{{ route('admini.posts.create', ['type' => 'news']) }}">{{ trans('admini::post.editor.add_new') }}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link{{ '/admini/pages' == $path ? ' active' : '' }}" href="{{ route('admini.posts.index', ['type' => 'pages']) }}">{{ trans('admini::post.editor.pages_index') }}</a>
      </li>
      <!-- <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Settings</a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li> -->
    </ul>
    <form class="form-inline my-2 my-lg-0" action="{{ route('admini.logout') }}" method="POST">
      <!-- <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search"> -->
      @csrf
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