@extends('posts.template')

@section('content')

  <?php

    if (User::signedIn()) {
      $user =  User::find(User::signedIn());
    }
  ?>

  {{-- Show the Channel bar if we're in the 'channel pagekind' --}}

  @if (Session::get('pageKind') == 'channel')
      <div class="currentChannel" style="background: {{Channel::color(Session::get('channel'))}}">
        <span class="close dynamicLink" data-destination="{{URL::to('/posts/all')}}"><a href ="#">&times;</a></span>
        {{Channel::description(Session::get('channel'))}}
      </div>
  @endif


  {{-- Show a message if one exists --}}

  @if (Session::has('lbMessage'))
    @include('posts.partials.helloWindow')
  @endif


  {{-- Get The Initial Set of Posts --}}

    <?php
      $initialPosts = Page::getPosts();
      // if we have less than 20 initial posts,
      // we disable infinite scrolling
      if (count($initialPosts) < 20) {
        echo '<script>lbApp.reachedEndOfPosts = true</script>';
      }
      // if we don't have any initial posts
      // we return the relevant "no results" page
      if (!$initialPosts) {
        echo View::make('posts.extras.noresults');
      }
    ?>


  {{-- Render the first batch of posts --}}
    @if($initialPosts)
    <div class="posts cards"> <!-- cards is default -->
      @include('posts.render', array(
        'posts'=>$initialPosts ,
        'from'=>0,
        'to'=>20))
    </div>
    @endif
@stop
