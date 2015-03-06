{{-- This is the initial set of posts --}}

@include('partials.header')
@include('partials.topbar')

<div id="momentumScrollingViewport">

  <div id="content">
    @yield('content')
  </div>

</div>

@include('partials.sidebar')
@include('partials.footer')
