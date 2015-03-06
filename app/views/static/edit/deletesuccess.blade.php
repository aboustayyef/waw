@extends('static.template_simple')
@section('content')
  <div class="message allok">
    <h1>Your Post has been succesfully deleted</h1>
    <p>If you deleted it by mistake, don't worry. Our crawler will put it back in the system</p>
    <p>{{link_to('/','&larr; Back to your home page')}}</p>
  </div>
@stop
