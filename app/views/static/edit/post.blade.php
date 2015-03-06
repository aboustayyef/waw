@extends('static.template_simple')
@section('content')

<?php
  // prepare some data;
  $categoriesList = Channel::getValueDescriptionArray();
  $categoriesListWithNone = $categoriesList;
  $categoriesListWithNone[''] = 'None';
  $currentCategories = explode(',' , $post->post_tags);
  $originalCategory1 = trim($currentCategories[0]);
  if (count($currentCategories) > 1) {
    $originalCategory2 = trim($currentCategories[1]);
  } else {
    $originalCategory2 = '';
  }

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
  if (empty($oldValues['title'])) {
    $defaultTitle = $post->post_title;
  }else{
    $defaultTitle = $oldValues['title'];
  }
  if (empty($oldValues['excerpt'])) {
    $defaultExcerpt = $post->post_excerpt;
  }else{
    $defaultExcerpt = $oldValues['excerpt'];
  }

 /**
 *  Now the actual form
 */
?>
  <h1>Edit "{{$post->post_title}}"</h1>

  {{ Form::open(array(
    'url' => '/edit/post/'.$post->post_id,
    'files' =>  'true'
    ))
  }}


  {{ Form::label('title', 'Post Title')}}
  {{ Form::text('title', $post->post_title) }}

  {{Form::label('category1', 'Post categories. You can pick up to 2');}}

  {{Form::select('category1', $categoriesList, $originalCategory1);}}
  {{Form::select('category2', $categoriesListWithNone, $originalCategory2);}}

  {{ Form::label('excerpt', 'Post Excerpt')}}
  {{ Form::textarea('excerpt', $post->post_excerpt) }}

  {{ Form::label('rating', 'If This is a review, enter your rating (over 5)')}}
  <?php
    if ($post->rating_denominator > 0) {
      $ratingValue = ($post->rating_numerator / $post->rating_denominator) * 5;
    } else {
      $ratingValue = $post->rating_numerator;
    }
  ?>
  <input name="rating" type="text" value="{{$ratingValue}}" id="rating" style="width:60px"> <br>

@if ($post->post_image_height > 0 )
  <img src="{{$post->post_image}}" width="300" alt="your post's image">
@endif

  <p>
    If you want to add a photo or change your post's photo, make sure you edit your original post and put your desired photo before the others. Also make sure the photo is at least 300px wide. </p><p>After you do that, delete your post using the button below and allow our algorithm 10-20 minutes to scan it back in...
  </p>

  {{link_to('/delete/post/'.$post->post_id, 'Delete this post', ['class'=>'danger'])}}

  <br>
  {{ Form::submit('Submit changes') }}


  {{ Form::close() }}

@stop
