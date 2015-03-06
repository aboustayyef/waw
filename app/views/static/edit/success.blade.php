@extends('static.template_simple')
@section('content')
  <div class="message allok">
    <h2>Success</h2>
    <p>You changes have been made. But because of caching, it may take a few minutes for the changes to show.</p>
    <p>{{link_to('/', '&larr; Back to Lebanese Blogs')}}</p>
  </div>
@stop
