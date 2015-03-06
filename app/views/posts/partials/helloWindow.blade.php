<?php
  // $windowDetails = array(
  //   'left-message'    =>    ['Tip','15'], // second figure is width percentage
  //   'right-message'   =>    ['Seeing many posts you\'re not interested in? Add blogs to your favorites and only see the posts you care about', '85' ],
  //   'color'           =>    '#ffcc66'
  // );
?>

<?php
if (isset($windowDetails)): ?>

<div class="helloWindowArea">
  <div class="helloWindow" style="background:{{$windowDetails['color']}}">
    <div class="leftMessage" style="width:{{$windowDetails['left-message'][1]}}%">
      <div class="inner">
        {{$windowDetails['left-message'][0]}}
      </div>
    </div>
    <div class="rightMessage" style="width:{{$windowDetails['right-message'][1]}}%">
      <div class="inner">
        {{$windowDetails['right-message'][0]}}
      </div>
    </div>
  </div>
</div>

<?php endif; ?>
