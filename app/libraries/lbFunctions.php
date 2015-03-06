<?php

  /**
  * utility functions for lebanese blogs
  */

  class lbFunctions
  {
    public static function isArabic($string){
      if (preg_match("/([ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz]).+/", $string)){
        return false;
      } else {
        return true;
      }
    }

    public static function hours_to_days($hours){
    $seconds = $hours * 3600;
    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                  30 * 24 * 60 * 60       =>  'month',
                  24 * 60 * 60            =>  'day',
                  60 * 60                 =>  'hour',
                  60                      =>  'minute',
                  1                       =>  'second'
                  );

      foreach ($a as $secs => $str)
      {
          $d = $seconds / $secs;
          if ($d >= 1)
          {
              $r = round($d);
              return $r . ' ' . $str . ($r > 1 ? 's' : '') ;
          }
      }

  }

    static function time_elapsed_string($ptime)
    {
        $etime = time() - $ptime;

        if ($etime < 1)
        {
            return '0 seconds';
        }

        $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                    30 * 24 * 60 * 60       =>  'month',
                    24 * 60 * 60            =>  'day',
                    60 * 60                 =>  'hour',
                    60                      =>  'min',
                    1                       =>  'sec'
                    );

        foreach ($a as $secs => $str)
        {
            $d = $etime / $secs;
            if ($d >= 1)
            {
                $r = round($d);
                return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
            }
        }
    }

      /**
 *  utf8 safe parse url
 *  Source: http://bluebones.net/2013/04/parse_url-is-not-utf-8-safe/
 */
  public static function utf8_parse_url($url)
  {
    $result = false;

    // Build arrays of values we need to decode before parsing
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%24', '%2C', '%2F', '%3F', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "$", ",", "/", "?", "#", "[", "]");

    // Create encoded URL with special URL characters decoded so it can be parsed
    // All other characters will be encoded
    $encodedURL = str_replace($entities, $replacements, urlencode($url));

    // Parse the encoded URL
    $encodedParts = parse_url($encodedURL);

    // Now, decode each value of the resulting array
    if ($encodedParts)
    {
      foreach ($encodedParts as $key => $value)
      {
        $result[$key] = urldecode(str_replace($replacements, $entities, $value));
      }
    }
    return $result;
  }
  }
?>
