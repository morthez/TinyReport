<?php
class dateTime {
	function __construct() {
		date_default_timezone_set( 'Europe/Paris' );
	}
	
	function yesterday() {
		$yesterday = date('d/m/Y', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
		
		return $yesterday;
	}
	
	function thisyear() {
		$thisyear = date("Y");
		return $thisyear;
	}
}

?>
