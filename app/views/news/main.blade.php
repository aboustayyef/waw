@extends('posts.template')

@section('content')

{{-- disable ajax loading of more posts --}}
<script>lbApp.reachedEndOfPosts = true</script>

  {{-- Show a message if one exists --}}

  @if (Session::has('lbMessage'))
    @include('posts.partials.helloWindow')
  @endif



  {{-- Render the first batch of posts --}}

    <div class="posts cards"> <!-- cards is default -->
      <?php
        $availableNewsSources = ['naharnet','lbci', 'nna_english', 'elnashra'];
        foreach ($availableNewsSources as $key => $source) {
          echo View::make('posts.extras.news')->with('source', $source);
        }
      ?>
    </div>
@stop
