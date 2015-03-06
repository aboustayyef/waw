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

<h2>Give Us Feedback.</h2>
<p>Don't hold anything back. We want to get better</p>

<?php

  // set default values from previously entered values

  $oldValues = Input::old();

  if (empty($oldValues['email'])) {
    $defaultEmail = '';
  }else{
    $defaultEmail = $oldValues['email'];
  }

  if (empty($oldValues['feedback'])) {
    $defaultFeedback = '';
  }else{
    $defaultFeedback = $oldValues['feedback'];
  }

?>

{{ Form::open(array('url' => '/about/feedback')) }}

{{ Form::label('email', 'Email Address: (If you want a response)')}}
{{ Form::text('email',$defaultEmail, ['placeholder' => 'Optional']) }}
<br>
{{ Form::label('feedback', 'Your Feedback')}}
{{ Form::textarea('feedback', $defaultFeedback, ['placeholder' => 'Complaints, feature requests, compliments.. All fit here']) }}
<br>
{{ Form::submit('Submit it') }}

{{ Form::close() }}

@stop
