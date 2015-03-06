@extends('static.template_simple')
@section('content')
<?php
/**
 * First, check if we have errors from a previously submitted form
 */
  if($errors->count() > 0):
    echo '<ul class="message warning">';
    foreach ($errors->all() as $key => $message) {
      echo '<li>' . $message . '</li>';
    };
    echo '</ul>';
  endif;
?>
  <?php
/**
 *  Check if there's input from previous forms
 *  If not, use values from database
 */
  $oldValues = Input::old();
  if (empty($oldValues['name'])) {
    $defaultName = $blog->blog_name;
  }else{
    $defaultName = $oldValues['name'];
  }
  if (empty($oldValues['description'])) {
    $defaultDescription = $blog->blog_description;
  }else{
    $defaultDescription = $oldValues['description'];
  }

 /**
 *  Now the actual form
 */
 ?>
  <h1>You are authorized to edit {{$blog->blog_name}}</h1>
  {{ Form::open(array(
    'url' => '/edit/blog/'.$blog->blog_id,
    'files' =>  'true'
    )) }}

  <!-- Blog Name -->
  {{ Form::label('name', 'Name of Blog')}}
  {{ Form::text('name', $defaultName ) }}

  <!-- Blog Description -->
  {{ Form::label('description', 'Description of Blog (the shorter the better)')}}
  {{ Form::textarea('description', $defaultDescription) }}

  <!-- Blog image -->
  <img src="{{asset('/img/thumbs/'.$blog->blog_id.'.jpg')}}" alt="">
  {{ Form::label('image', 'Avatar')}}
  {{ Form::file('image') }}

  <!-- Submit Button -->
  <br>
  {{ Form::submit('Submit changes') }}
  {{ Form::close() }}
@stop
