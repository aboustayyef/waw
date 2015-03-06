<div class="bubblewrapper">
  <ul class="sharing">
    <li class="header">This Blogger</li>
    <li><a href ="/blogger/{{$post->blog_id}}"><i class="fa fa-info-circle"></i>Learn more about this blog</a></li>

    @if(User::signedIn())
      @if($ourUser->follows($post->blog_id))
      <li class="removeFromFavorites" data-userId="{{$ourUser->id}}" data-blogId="{{$post->blog_id}}"><i class="fa fa-star"></i>Remove it from your favorites</li>
      @else
      <li class="addToFavorites" data-userId="{{$ourUser->id}}" data-blogId="{{$post->blog_id}}"><i class="fa fa-star"></i>Add it to your favorites</li>
      @endif
    @else
      <li class ="dim"><i class="fa fa-star"></i>Add it to your favorites</li>
    @endif

    <li class="header">This Post</li>
    <li><i class="fa fa-facebook"></i>Share On Facebook</li>
    <li><i class="fa fa-twitter"></i>Share On Twitter</li>
    @if(User::signedIn())
      @if($ourUser->hasSaved($post->post_id))
      <li class="removeFromSaved" data-userId="{{$ourUser->id}}" data-postId="{{$post->post_id}}"><i class="fa fa-clock-o"></i>Remove from reading list</li>
      @else
      <li class="addToSaved" data-userId="{{$ourUser->id}}" data-postId="{{$post->post_id}}"><i class="fa fa-clock-o"></i>Read it later</li>
      @endif
    @else
      <li class ="dim"><i class="fa fa-clock-o"></i>Read it later</li>
    @endif
  </ul>
</div>
