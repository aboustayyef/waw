<?php namespace waw\Utilities;

class Strings
{
  private $string;
  function __construct($string)
  {
    $this->string = $string;
  }

  public function IdFromUrl(){
    $url = $this->string;
    https://brainsforless.wordpress.com
    $url = preg_replace('#https?://|www#', '', $url);
    $urlParts = explode('.', $url);
    foreach ($urlParts as $key => $part) {
      if (!empty($part) && $part != 'blog' && $part != 'weblog') {
        return $part;
      }
    }
  }
}

?>
