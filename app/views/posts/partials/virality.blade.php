<?php
$viralityColor = "#39CCCC"; // green

if ($score > 9) {
  $viralityColor = "#FFDC00"; // yello
}

if ($score > 19) {
  $viralityColor = "#FF851B"; // orange
}
if ($score > 29) {
  $viralityColor = "#FF4136"; // red
}

if ($score > 39) {
  $viralityColor = "#BE0000"; // deep red
}
$virality = $score * 2; // convert to a percentile score
?>
<div class="viralityWrapper">
Virality &nbsp;
 <div class="viralityBox" title="Virality Score: {{$score}}/50">
  <div class="viralityScore" style ="background: {{$viralityColor}}; width: {{$virality}}px"> <!-- This will be styled from the code -->
  </div>
</div>
</div>
