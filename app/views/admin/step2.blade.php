<?php
  $url = Input::get('url');
  $twitter = Input::get('twitter');

  $avatar = (new LebaneseBlogs\Crawling\TwitterImageExtractor($twitter))->image();
  $metadata = new LebaneseBlogs\Crawling\MetaDataExtractor($url);
  $id = (new LebaneseBlogs\Utilities\Strings($url))->IdFromUrl();
  $title = $metadata->title();
  $feed = $metadata->feed();

  // Initiate Simple Pie instance for feed
  $maxitems = 0; // no limit on extent of crawl
  $sp_feed = new SimplePie(); // We'll process this feed with all of the default options.
  $sp_feed->set_feed_url($feed); // Set which feed to process.
  $sp_feed->strip_htmltags(false);
  $sp_feed->enable_cache(false);
  $sp_feed->init(); // Run SimplePie.
  $sp_feed->handle_content_type(); // This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
  $feedItems = $sp_feed->get_items(0, 8);
?>
@extends('admin.layout')
@section('content')

@if($errors->any())
<div class="row">
  <div class="col-log-12">
    <ul>
      {{implode('',$errors->all('<li>:message</li>'))}}
    </ul>
  </div>
</div>
@endif

{{ Form::open(array('url' => '/admin/addBlog/step2')) }}
<div class="row">
  <div class="col-lg-6">
      <div class="form-group">
        {{ Form::label('url', 'Blog URL')}}
        {{ Form::text('url', $url ,['class'=>'form-control']) }}
      </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
      <div class="form-group">
        {{ Form::label('id', 'Blog ID (example: beirutspring)')}}
        {{ Form::text('id', $id ,['class'=>'form-control']) }}
      </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
      <div class="form-group">
        {{ Form::label('title', 'Blog Title')}}
        {{ Form::text('title', $title ,['class'=>'form-control']) }}
      </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
      <div class="form-group">
        {{ Form::label('twitter', 'Blog Twitter')}}
        {{ Form::text('twitter', $twitter ,['class'=>'form-control']) }}
      </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
      <div class="form-group">
        {{ Form::label('avatar', 'Blog Avatar (preview below)')}}
        {{ Form::text('avatar', $avatar ,['class'=>'form-control']) }}
      </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
      <div class="form-group">
        {{ Form::label('feed', 'Blog Feed')}}
        {{ Form::text('feed', $feed ,['class'=>'form-control']) }}
      </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
      <div class="form-group">
        {{ Form::label('description', 'Blog Description')}}
        {{ Form::text('description', null ,['class'=>'form-control']) }}
      </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
      <div class="form-group">
        {{ Form::label('tags', 'Blog Tags (separate with comas)')}}
        {{ Form::text('tags', null ,['class'=>'form-control']) }}
      </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-6">
    <button type="submit" class="btn btn-default">Submit</button>
  </div>
</div>

{{ Form::close()}}

<hr>

<div class="row">
  <div class="col-lg-12">
    <img src="{{$avatar}}">

    <ul>
      @foreach($feedItems as $feedItem)
        <li>
          <h3>{{$feedItem->get_title()}}</h3>
          <p>{{str_limit($feedItem->get_content(), 200)}}</p>
        </li>
      @endforeach
    </ul>
  </div>
</div>

@stop
