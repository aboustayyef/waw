{{--

Requires the original numerator and denominator:
Numerator: $n (float)
Denominator: $d (int)
--}}

<?php
  // convert score to a 5 star rating system
  $ratio = $n / $d ;

  $score_over_five = $ratio * 5;

  // round it to the nearest 0.5 ;
  $score_over_five = (round($score_over_five * 2))/2;

  $N = $score_over_five;
  $D = 5;

  $fullStars = floor($N);
  $halfStars = ceil($N) == floor($N) ? 0:1;
  $emptyStars = $halfStars? $D - 1 - $fullStars : $D - $fullStars;
  $score = $fullStars;
  if ($halfStars) {
    $score = $score + 0.5;
  }
?>

<div class="stars" title="Blogger rating: {{$score}} / {{$D}} (original: {{$n}} / {{$d}})">
  <span class="rating">Rating:</span>
  <?php
    for ($i=0; $i < $fullStars ; $i++) {
      echo '<span class="fullstar"></span>';
    }
    if ($halfStars) {
      echo '<span class="halfstar"></span>';
    }
    for ($i=0; $i < $emptyStars ; $i++) {
      echo '<span class="emptystar"></span>';
    }
  ?>

</div>
