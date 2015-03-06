{{-- This view handles the routing for the extra cards --}}

<?php
  $pageKind = Session::get('pageKind');
?>

@if ($counter == 0)

  @if ($pageKind == 'blogger')
    @include('posts.extras.topBloggerList')
    <?php Session::set('cardsCounter', Session::get('cardsCounter') + 1); ?>

  @elseif ($pageKind == 'following')
    <div class="post_wrapper"> <!-- on its own, not part of top list -->
      @include('posts.extras.user')
    </div>
    <?php Session::set('cardsCounter', Session::get('cardsCounter') + 1); ?>

  @elseif ($pageKind == 'searchResults')
    @include('posts.extras.searchResults')
    <?php Session::set('cardsCounter', Session::get('cardsCounter') + 1); ?>

  @elseif ($pageKind == 'liked')
    <div class="post_wrapper"> <!-- on its own, not part of top list -->
      @include('posts.extras.user')
    </div>
    <?php Session::set('cardsCounter', Session::get('cardsCounter') + 1); ?>

  @elseif ($pageKind == 'search')
    {{-- Nothing yet --}}



  @else
    @include('posts.extras.topList')
    <?php Session::set('cardsCounter', Session::get('cardsCounter') + 1); ?>
  @endif
@endif

@if ($counter == 11)
  {{View::make('posts.extras.news')->with('source','naharnet')}}
  <?php
      Session::set('cardsCounter', Session::get('cardsCounter') + 1);
  ?>
@endif



@if (in_array($counter, [6,14,29,44, 59]))
  @include('posts.extras.adsense1')
  <?php
      Session::set('cardsCounter', Session::get('cardsCounter') + 1);
  ?>
@endif
