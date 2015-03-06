<div class="post_wrapper post-{{Session::get('postsCounter')}} card-{{Session::get('cardsCounter')}}"> <!-- /For grouping items together -->

  <div class="card">

    <!-- Blog Header . don't show where we're at the blog's page -->
    @if (Session::get('pageKind') != 'blogger')
    <div class="blog_header">
      <!-- Thumbnail -->
      <a href="{{url('/blogger/'.$post->blog_id)}}">
        <img
          class="thumbnail"
          @if (app('env') == 'staging')
            src="http://static1.lebaneseblogs.com/{{$post->blog_id.'.jpg'}}"
          @else
            src="{{asset('/img/thumbs/'.$post->blog_id.'.jpg')}}"
          @endif
          alt="{{$blog->blog_name }} thumbnail"
          width ="50px" height="50px">
      </a>
      <!-- Blog's Name -->
      <div class="blogname">
        <a href="{{url('/blogger/'.$post->blog_id)}}">
          {{ $blog->blog_name }}
        </a>
      </div>

      <!-- Follow button -->
      @if(User::signedIn())
        @if($ourUser->follows($post->blog_id))
          <div data-blogid="{{$post->blog_id}}" class="followBlogger followed"></div>
        @else
          <div data-blogid="{{$post->blog_id}}" class="followBlogger"></div>
        @endif
      @else
        <div data-blogid="{{$post->blog_id}}" class="login followBlogger"></div>
      @endif
    </div> <!-- /Blog Header -->
      @endif

    <!-- Post Body -->

    <div class="post_body">
      <div class="metaInfo">
        <div class="postedSince">
          {{lbFunctions::time_elapsed_string($post->post_timestamp)}}
        </div>
        {{View::make('posts.partials.virality')->with('score',$post->post_virality)}}
      </div>
      <!-- Post Title -->
      <h2
        @if (lbFunctions::isArabic($post->post_title))
         class="rtl"
        @endif
        >
        <!-- outward url -->
        <a href="{{URL::to('/exit').'?url='.urlencode($post->post_url).'&token='.Session::get('_token')}}" target="_blank">{{ $post->post_title }} </a>

        <!-- rating -->
        <?php

          if (($post->rating_denominator > 0) && ($post->rating_numerator > 1)) {
            echo '<!-- Rating -->';
            echo View::make('posts.partials.rating')->with('n',$post->rating_numerator)->with('d',$post->rating_denominator);
          }
        ?>
      </h2>

      <!-- Post image (if any ) -->
        @if ($post->post_image_height > 0)
          <a href="{{URL::to('/exit').'?url='.urlencode($post->post_url).'&token='.Session::get('_token')}}" target="_blank">
            {{View::make('posts.partials.post_image')->with('post',$post)}}
          </a>
        @else
      <!-- Post Excerpt (If no image) -->
          <p class ="excerpt
          @if (lbFunctions::isArabic($post->post_excerpt))
           rtl">
          @else
            ">
          @endif
            {{$post->post_excerpt}}
          </p>
        @endif
    </div>

    <div class="tools_footer">
      &nbsp; {{-- This just creates a free space at the bottom of each post --}}
      <?php $blogOwner = $blog->blog_author_twitter_username ?>
    </div>
    @if (User::signedIn())
      @if ($ourUser->twitter_username == 'beirutspring' ||  $ourUser->twitter_username == $blogOwner)
        <div class="editpost">
          {{link_to('/edit/post/'.$post->post_id, 'edit this post', ['class'  =>  'button'])}}
        </div>
      @endif
      @if ($ourUser->twitter_username == 'beirutspring')
        <div class="postvisits">{{$post->post_visits}}</div>
      @endif
    @endif
    <div class="sharingButton tweetit">
      <?php
        "%title% %url% [by %@author%] via lebaneseblogs.com";
        $byline = $blog->blog_author_twitter_username ? " by @$blog->blog_author_twitter_username" : "";
        $byline .= " via lebaneseblogs.com";
        $allowedTitleSize = 140 - strlen($byline) - 28; // urls count for 22 chars on twitter and we add space
        $byline = ' ' . $post->post_url . $byline;
        $postTitle = $post->post_title;
        if (strlen($postTitle) >= $allowedTitleSize) {
          $postTitle = substr($postTitle, 0, ($allowedTitleSize - 4)) . '... ';
        }
        $tweetExpression = $postTitle.$byline;
        $twitterUrl = urlencode($tweetExpression);
      ?>
      <a href="https://twitter.com/intent/tweet?text={{$twitterUrl}}" title="Click to send this post to Twitter!" target="_blank">
        <?php fontAwesomeToSvg::convert('fa-twitter') ?> Tweet
      </a>

    </div>
    <div data-postid="{{$post->post_id}}" class="sharingButton likeit
    <?php if(User::signedIn()){
      if ($ourUser->likes($post->post_id)) {
        echo ' liked ';
      } else {
        echo ' unliked';
      }
    }?>">
        <?php fontAwesomeToSvg::convert('fa-heart') ?> like
    </div>
  </div> <!-- /card -->
</div> <!-- /post wrapper -->
