<?php
class ExitLog extends Eloquent{
	
	protected $table = 'exit_log';
    public $timestamps = false;

	public function has($ip, $url){
		// returns true if there's a record with $ip and $url
		
		$record = $this->where('ip_address',$ip)->where('exit_url', $url)->first();
		if (!empty($record)) {
			return true;	
		} else {
			return false;
		}
	}

}