<?php

if (!Cache::has($source)) {
  return;
}
$articles = Cache::get($source);
?>
<div class="post_wrapper">
  <div class="card news
    <?php if ($articles['meta']['language'] == 'Arabic') {
      echo 'arabic';
    } ?>
  ">
    <div class="newsheader">
      <h3>{{$articles['meta']['feedTitle']}} <span class="beta">(beta)</span><small>{{$articles['meta']['attribution']}}</small></h3>
    </div>
    <?php
      $feedTitle = $articles['meta']['feedTitle'];
      $articles = array_chunk($articles['content'], 5); // get first 5 articles only
      $articles = $articles[0];
    ?>
    <ul>
      @foreach($articles as $article)
      <?php
        $virality = $article['virality'];
        $timeAgo = (new Carbon\Carbon($article['gmtDateTime']))->diffForHumans();
        if (!empty($article['img'])) {
          $img = '/img/cache/' . $source . '/'.md5($article['img']).'.jpg';
        }else{
          $img='NO_IMAGE';
        }

        //$timeAgo = str_replace(' ', '&nbsp;', $timeAgo);
      ?>
        <li>
          <div class="newsitem">

            @if($img != 'NO_IMAGE')
            <div class="newsItemImage">
              <a href="{{$article['url']}}" target="blank"><img src="{{$img}}" alt=""></a>
            </div>
            @endif

            <div class="newsContent
              @if($img == "NO_IMAGE")
                  noimage
              @endif
            ">
              <a href="{{$article['url']}}" target="blank">{{$article['headline']}}</a><br> <span class="timeAgo"> {{$timeAgo}}</span>
              {{View::make('posts.partials.virality')->with('score',$virality)}}
            </div>

          </div>
        </li>
      @endforeach
    </ul>
  </div>
</div>
