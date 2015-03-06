@extends('static.template_simple_photobackground')
@section('content')
<div class="whitePage">
  <div class="ourcontent">
    <h1>Welcome To Lebanese Blogs</h1>
    <p>Lebanon's best way of discovering and reading awesome blogs</p>
    <div class="featureTable">
      <div class="third">
        <h2>Follow Blogs</h2>
        <img src="{{asset('img/follow-em.png')}}" width="120px" height="auto">
      </div>
      <div class="third">
        <h2>Like Posts</h2>
        <img src="{{asset('img/bucket-of-hearts.png')}}" width="107px" height="auto">
      </div>
      <div class="third last">
        <h2>Search</h2>
        <img src="{{asset('img/nothing-found.png')}}" width="126px" height="auto">
      </div>
    </div>

    <a href="{{URL::to('/')}}" class="bigAssRedButton">Get Started</a>
  </div>
</div>
@stop
