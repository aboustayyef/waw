<?php
/**
*
*/
class imageAnalyzer extends BaseController
{

  function __construct($image)
  {
    if ($this->image = new imagick($image)) {
      //nothing, proceed
    } else {
      return false;
    }
  }

  public function getDominantHue(){
    $this->image->resizeImage(1,1, imagick::FILTER_GAUSSIAN, 1.5);
    return $this->getMostRepeatedColorByIteration();
  }

  public function getMostRepeatedColorByIteration(){
    $this->image->posterizeImage(20,1);
    try {
      $highestCount=0;
      $highestCountColor='';
      $colors = $this->image->getImageHistogram();
      foreach ($colors as $key => $color) {
        $count = $color->getColorCount() ;
        $value = $color->getColor();
        if ($count > $highestCount) {
          $highestCount = $count;
          $highestCountColor = $value;
        }
      }
      return self::getHue($value);

    } catch (Exception $e) {
      return 'unable to get color';
    }
  }

  public static function getHue($array){
  /*
  |--------------------------------------------------------------------------
  | Source of the math to convert RGB to HSL
  |--------------------------------------------------------------------------
  | http://www.niwa.nu/2013/05/math-behind-colorspace-conversions-rgb-hsl/
  */

    $RGB['R'] = $array['r'] / 255;
    $RGB['G'] = $array['g']  / 255;
    $RGB['B'] = $array['b']  /255;
    // calculate maximum and minimum values;
    $maxValue = 0;
    $maxKey ='';
    $minValue = 1;
    $minKey ='';
    foreach ($RGB as $key => $value) {
      if ($value > $maxValue) {
        $maxValue = $value;
        $maxKey = $key;
      }
      if ($value < $minValue) {
        $minValue = $value;
        $minKey = $key;
      }
    }
    $delta = $maxValue - $minValue;
    if ($maxKey == 'R') {
      $hue = ($RGB['G'] - $RGB['B']) / $delta;
    }elseif ($maxKey == 'G'){
      $hue = 2.0 + (($RGB['B']-$RGB['R']) / $delta );
    }elseif ($maxKey == 'B'){
      $hue = 4.0 + (($RGB['R']-$RGB['G']) / $delta );
    }
    return round($hue*60);
  }
}

?>
