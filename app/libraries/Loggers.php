<?php

class textFileLogger {

  public static function log($message)
  {
    try {
      $logfile = base_path().'/lebaneseBlogsLog.log';
      $resource = fopen($logfile, 'a');
      $now = (new Carbon\Carbon('now'))->format("Y-M-d H:i:s");
      $message = "$now - $message \n";
      fwrite($resource, $message);
      return true;
    } catch (Exception $e) {
      return false;
    }
  }
}

?>
