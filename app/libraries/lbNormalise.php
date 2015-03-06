<?php
/**
*
*/
class lbNormalise
{

  function __construct()
  {
    # code...
  }

  static function cleanUpText($original_string, $length){
    /*****************************************
    *
    * Will clean up and truncate a string
    *
    *******************************************/
    $original_string = html_entity_decode($original_string, ENT_COMPAT, 'utf-8');
    $original_string = strip_tags($original_string);
    str_replace(array("\r", "\r\n", "\n"), '', $original_string);
    $original_string = trim($original_string);
    if (strlen($original_string) > $length ) {
      $new_string = mb_substr($original_string,0,$length)."...";
    } else {
      $new_string = $original_string;
    }
    return $new_string ;
  } // function cleanupheadline


  public static function unicode_decode ( $string ) // source: http://www.sitepoint.com/forums/showthread.php?602738-How-to-sanitize-UTF-8-input-efficiently

  {

  // step 1
  $string = mb_convert_encoding($string, 'utf-8', 'utf-8');

  // step 2
  if (!function_exists('decode_unicode'))
  {
    function decode_unicode ( $c )
    {
        return ( ( $c = ( isset ( $c[3] ) ? $c[3]
                :
                ( isset ( $c[2] ) ? hexdec ( $c[2] )
                :
                hexdec ( $c[1] ) ) ) ) < 0x80 ? chr ( $c )
                :
                ( $c < 0x800 ? chr ( 0xc0 | ( $c >> 6 ) ) . chr ( 0x80 | ( $c & 0x3f ) )
                :
                ( $c < 0x10000 ? chr ( 0xe0 | ( $c >> 12 ) ) . chr ( 0x80 | ( ( $c >> 6 ) & 0x3f ) ) . chr ( 0x80 | ( $c & 0x3f ) )
                :
                ( $c < 0x200000 ? chr ( 0xf0 | ( $c >> 18 ) ) . chr ( 0x80 | ( ( $c >> 12 ) & 0x3f ) ) . chr ( 0x80 | ( ( $c >> 6 ) & 0x3f ) ) . chr ( 0x80 | ( $c & 0x3f ) ) : '' ) ) )
            );
    }
  }
  return preg_replace_callback ( '~(?:\\\u|%u)([0-9a-f]{4})|&#x0*([0-9a-f]+);|&#0*([0-9]+);~i', 'decode_unicode', $string );
  }

} // class lb Normalise
