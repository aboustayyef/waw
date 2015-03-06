<?php
$height = $post->post_image_height;
$width = $post->post_image_width;

$currentPost = new Post;
$currentPost = $currentPost->find($post->post_id);

if ($currentPost->hasImage()) {
  if ($currentPost->cacheImage()) {
    $image = $currentPost->cacheImage();
  } else {
    $image = $post->post_image;
  }
} else {
    $image = asset('/img/no_image.png');
}?>
@if ($height > $width)
<img class="lazy" data-original="{{$image}}" src="{{asset('/img/grey.gif')}}" width="100" height="auto" alt="">
@else
<img class="lazy" data-original="{{$image}}" src="{{asset('/img/grey.gif')}}" height="100" width="auto" alt="">
@endif
