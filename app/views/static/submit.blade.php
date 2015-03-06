@extends('static.template')
@section('content')

<?php
  if($errors->count() > 0):
    echo '<ul class="message warning">';
    foreach ($errors->all() as $key => $message) {
      echo '<li>' . $message . '</li>';
    };
    echo '</ul>';
  elseif (Session::has('message')):
    echo '<div class="message allok"><p>' . Session::get('message'). '</p>';
    echo '<a href="' . URL::to('/posts/all') . '">&larr; back to lebanese blogs</a></div>';
  endif;

?>

<h1>Submit Your Blog</h1>

<h2>Requirements</h2>
<p>Before you submit a blog, make sure it fulfills the following criteria:</p>
<ul class="criteria">
  <li>The author has to be either Lebanese, living in Lebanon or writing about Lebanon</li>
  <li>The blog should be at least 6 months old</li>
  <li>The blog should be personal, not commercial or institutional</li>
  <li>The blog should not be a vehicle for ads or spam in the posts</li>
</ul>

<h2>Submit Here <small>(all fields are required)</small></h2>

<?php

  // set default values from previously entered values

  $oldValues = Input::old();

  if (empty($oldValues['email'])) {
    $defaultEmail = '';
  }else{
    $defaultEmail = $oldValues['email'];
  }

  if (empty($oldValues['twitter'])) {
    $defaultTwitter = '';
  }else{
    $defaultTwitter = $oldValues['twitter'];
  }

  if (empty($oldValues['url'])) {
    $defaultUrl = '';
  }else{
    $defaultUrl = $oldValues['url'];
  }
?>

{{ Form::open(array('url' => '/about/submit')) }}

{{ Form::label('url', 'Blog Url')}}
{{ Form::text('url', $defaultUrl, ['placeholder' => 'http://myAwesomeBlog.wordpress.com']) }}

{{ Form::label('email', 'Email Address')}}
{{ Form::text('email',$defaultEmail, ['placeholder' => 'test@test.com']) }}

{{ Form::label('twitter', 'Twitter Handle')}}
{{ Form::text('twitter', $defaultTwitter, ['placeholder' => '@myTwitter']) }}

{{ Form::submit('Submit it') }}

{{ Form::close() }}



@stop
