<?php

class Functions {
	
	public function __construct() {
		exit('No constructor required for class: Functions');
	} 
	
	public static function dayMonthDate($dateval) {
		$days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
		$day = $days[date('w', strtotime($dateval))];
		$dateval = $day . ' ' . date('M-d', strtotime($dateval));
		return $dateval;
	} 
	
	public static function timeAmPm($timeval) {
		// receives $timeval in format: 00:00:00
		// returns $timeval in format: 00:00 am, or 00:00 pm
		if ($timeval < 12) // morning (am)
			$timeval = substr($timeval, 0, 5) . ' am';
		else { // noon-afternoon-evening (pm)
			$hour = substr($timeval,0,2);
			$hour = $hour - 12;
			if ($hour == 0) $hour = 12;
			if ($hour < 10) $hour = '0' . $hour;
			$timeval = $hour . substr($timeval,2,3) . ' pm';
		}
		return $timeval;
	}
} // end class: Functions
?>