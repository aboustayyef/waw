{{-- **************************** --}}
{{-- Top 5 list of blogger posts  --}}
{{-- **************************** --}}

<?php
$blogid = Session::get('blogger');
$blog = Blog::with('posts')->find($blogid);
$followerCount = $blog->followers() + 7 ; // for morale add 7;
$blogName = $blog->blog_name;
$blogDescription = $blog->blog_description;
$blogAvatar = asset('img/thumbs/' . $blog->blog_id . '.jpg');
$numberOfPosts = $blog->posts->count();
$posts = Post::getTopPostsByBlogger($blog->blog_id);
$ourUser = User::signedIn();
if ($ourUser) {
  $ourUser = User::find($ourUser);
}
?>
<!-- Top List wrapper -->
<div class="post_wrapper toplist">

<!-- About Blog -->
  <div id="bloggerStats" class="card push_down no_min">
    <div>
      <img src="{{$blogAvatar}}" width="50" height="50" alt="">
      <h2>{{$blogName}}</h2>
        <div class="follow">
        @if($ourUser)
          @if($ourUser->follows($blogid))
            <div data-blogid="{{$blogid}}" class="followBlogger followed"></div>
          @else
            <div data-blogid="{{$blogid}}" class="followBlogger"></div>
          @endif
        @else
          <div data-blogid="{{$blogid}}" class="login followBlogger"></div>
        @endif
      </div>
    </div>
    <div class="blogDescription">
      <p>{{$blogDescription}}</p>
    </div>
    <ul>
      <li>Followers: <span class="followerCount">{{ $followerCount }}</span></li>
      <li>Posts indexed: {{$numberOfPosts}}</li>
      <li><a href="{{$blog->blog_url}}"><i class="fa fa-link"></i> Go to website</a></li>
      <li><a href="http://twitter.com/{{$blog->blog_author_twitter_username}}"><i class="fa fa-twitter"></i> Follow on Twitter</a></li>
    </ul>
    @if (User::signedIn())
      <?php $blogOwner = $blog->blog_author_twitter_username ?>
      @if ($ourUser->twitter_username == 'beirutspring' ||  $ourUser->twitter_username == $blogOwner)
        <div class="editBlog">
          {{link_to('/edit/blog/'.$blog->blog_id, 'edit this blog')}}
        </div>
        @endif
    @endif
  </div>

<!-- Top List -->

  <div class="card">
    <h3>Top Posts</h3>
    <h4 class="category">By {{$blogName}}</h4>
    <ul>
      @foreach ($posts as $post)
      <li>
        <div class="item">
          <div class="thumb">
            <a href ="{{$post->post_url}}">
              {{View::make('images.topListThumb')->with('post',$post)}}
            </a>
          </div>
          <div class="details">
            <a href="{{$post->post_url}}"><h4>{{$post->post_title}}</h4></a>
            <small>Published {{lbFunctions::time_elapsed_string($post->post_timestamp)}}</small>
          </div>
        </div>
      </li>
      @endforeach
    </ul>
  </div>
</div>
