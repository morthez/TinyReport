<?php
if (!defined ("FromIndex") && FromIndex !== true) { header ("HTTP/1.0 404 Not Found"); header ("Location: ../../index.php"); die (); }
	
function getKeyPositionInArray($haystack, $keyNeedle){
	$i = 0;
	foreach($haystack as $key => $value)
	{
		if($key == $keyNeedle)
		{
			return ($haystack[$i][1]);
		}
		$i++;
	}
}
	
/**
 * Check request for bad data, and validate that the input are correct. Thanks Firemorph!
 *
 * Example usage:
	try {
		validate_request($_GET, array('DaData' => TYPE_STRING, 'DaCassa' => TYPE_NUMERIC));
	} catch (Exception $e) {
		echo "DID NOT VALIDATE :O:O";
		die();
	}
 */

define("TYPE_STRING", 	1);
define("TYPE_INT",	1<<1);
define("TYPE_NUMERIC",	1<<2);
define("TYPE_LINK", 1<<3);
define("TYPE_DATE", 1<<4);
define("TYPE_TIME",1<<5);

class MissingArgsException extends Exception {}
class MissingFieldException extends Exception {}

function validate_request($input /* .. valist*/)
{
	$argc = func_num_args();
	if($argc <= 1)
		throw new MissingArgsException();

	$args = func_get_args();

	//for($i=1; $i++; $i<$argc) /* This is not right!!!  */
	for($i=1; $i<$argc; $i++)
	{
		$key = $args[$i];
		
		if(!isset($input[$key]))
			throw new MissingFieldException("$key missing in request");

		$i++;
		$type = $args[$i];

		if($type === null)
			throw new Exception("Missing type!");

		switch($type)
		{
				// Validate string. See  Val_string(); For more information.
			case TYPE_STRING:
				if(empty($input[$key]))
					throw new Exception("$key not valid");

				if($args[$i+1] !== null)
				{
					$i++;
					Val_string($input[$key], $args[$i]);
				}
			break;
				
				// Need to be prased as INTEGER! passing a string with value 2 will result in error.
			case TYPE_INT:
				if(!is_int($input[$key]))
					throw new Exception("$key not valid");
					
				if($args[$i+1] !== null)
				{
					$i++;
					Val_int($input[$key], $args[$i]);
				}
			break;
				
				// Any number format. eq to TYPE_INT, but abit "looser" (you can prase string variables to numeric)
			case TYPE_NUMERIC:
				if(!is_numeric($input[$key]))
					throw new Exception("$key not valid");
					
				if($args[$i+1] !== null)
				{
					$i++;
					Val_num($input[$key], $args[$i]);
				}
			break;
				// Format: any http, ftp, mailto link or eq.
			case TYPE_LINK:
				if(empty($input[$key]))
					throw new Exception("$key not valid");
					
				if($args[$i+1] !== null)
				{
					$i++;
					Val_href($input[$key], $args[$i]);
				}
			break;
				
				// Format: 31/12/2010 (10 chars!)
			case TYPE_DATE:
				if(empty($input[$key]))
					throw new Exception("$key not valid");
				if(strlen($input[$key]) < 10 || strlen($input[$key]) > 10)
					throw new Exception("$key is not the right length. Please try again!\n");
				if($args[$i+1] !== null)
				{
					$i++;
					Val_date($input[$key], $args[$i]);
				}
			break;
				
				// Format: 16:59
			case TYPE_TIME:
				if(empty($input[$key]))
					throw new Exception("$key not valid");
				if(strlen($input[$key]) < 5 || strlen($input[$key]) > 5)
					throw new Exception("$key is not the right length. Please try again!\n");
				if($args[$i+1] !== null)
				{
					$i++;
					Val_time($input[$key], $args[$i]);
				}
			break;				
		}
		if($args[$i+1] === null)
		$i++;
	}
}	
	
	
	
/**
 * Input validation functions
 * Thanks Hellkeepa (moderator on NWF www.norskwebforum.no)
 *
 *
 * Validates input as hyperlink, URL or e-mail address. Returns string on success, false on failure.
 *
 * @param string $String
 * @param mixed $Length
 * @return mixed
 */
function Val_href ($String, $Length = '') {
	if ($Length = "*" && $String == '') {
		return '';
	}
 
	if (substr ($String, 0, 7) == "mailto:") {
		if (Val_EMail (substr ($String, 7))) {
			return $String;
		}
 
		return false;
	}
 
	$RegExp = '#^(?:(?:http|https|ftp)://)?((?:[\\w\\pL-]+\\.)+[a-z\\pL]{2,5})((?:/[\\w\\%-]*)+(?:\\.\\w{1,6})*(\\?(?:[\\w-]+=[\\w-]+)?(?:\\&[\\w-]+=[\\w-]+)*\\&?)?)?\\z#ui';
 
	if (preg_match ($RegExp, $String)) {
		return $String;
	}
 
	return false;
}
 
/**
 * Validates e-mail addresses. Returns string on success, false on failure.
 *
 * @param string $String
 * @return mixed
 */
function Val_email ($String) {
	$RegExp = "/^[a-zA-Z][\\w\\pL\\.-]*[a-zA-Z0-9]@[a-zA-Z0-9][\\w\\pL\\.-]*[a-zA-Z0-9]\\.[a-zA-Z][a-zA-Z\\.]*[a-zA-Z]\\z/u";
 
	if (preg_match ($RegExp, $String)) {
		return $String;
	}
 
	return false;
}
 
/**
 * Validates that given input is all numbers, within $MaxLength if set.
 * Returns number on succes, false if fails.
 *
 * @param mixed $String
 * @param mixed $MaxLength
 * @return mixed
 */
function Val_int ($String, $MaxLength = '+') {
	if ($MaxLength == "*" && $String == '') {
		return '';
	}
 
	if (is_int ($MaxLength)) {
		$MaxLength = "{1,$MaxLength}";
	} elseif ($MaxLength != "*") {
		$MaxLength = '+';
	}
 
	if (preg_match ('/^\\d'.$MaxLength.'\\z/', $String)) {
		return $String;
	}
 
	return false;
}
 
 
/**
 * Validates that given input is all numbers, within $MaxLength if set.
 * Returns number on succes, false if fails.
 *
 * @param mixed $String
 * @param mixed $MaxLength
 * @return mixed
 */
function Val_num ($String, $MaxLength = '+') {
	if ($MaxLength == "*" && $String == '') {
		return '';
	}
 
	if (is_int ($MaxLength)) {
		$MaxLength = "{1,$MaxLength}";
	} elseif ($MaxLength != "*") {
		$MaxLength = '+';
	}
 
	if (preg_match ('/^\\d\,\.'.$MaxLength.'\\z/', $String)) {
		return $String;
	}
 
	return false;
} 
 
 
/**
 * Validates that given string contains only legal characters, optional characters and max length can be set.
 * Default chars that are accepter are: Letters, numbers, space, (, ), & and -
 *
 * @param string $String
 * @param string $Extra
 * @param mixed $MaxLength
 * @return mixed
 */
function Val_string ($String, $Extra = '', $MaxLength = '+') {
	if ($MaxLength == "*" && $String == '') {
		return '';
	}
 
	if (is_int ($MaxLength)) {
		$MaxLength = "{1,$MaxLength}";
	} elseif ($MaxLength != "*") {
		$MaxLength = '+';
	}
 
	if ($Extra == "comment") { return $String; }
 
	$OKChars = addcslashes ($Extra, '."!?.\'*|$[]<>%#^/\\').'\\w\\pL \\(\\)\\&-';
 
	if (preg_match ('/^['.$OKChars.']'.$MaxLength.'\\z/u', $String)) {
		return $String;
	}
 
	return false;
}
 
/**
 * Validates that string contains only characters legal for names.
 * Optionally extra characters can be selected, as well as max length.
 *
 * @param string $String
 * @param string $Extra
 * @param mixed $MaxLength
 * @return mixed
 */
function Val_name ($String, $Extra = '', $MaxLength = '+') {
	if ($MaxLength == "*" && $String == '') {
		return '';
	}
 
	if (is_int ($MaxLength)) {
		$MaxLength = "{1,$MaxLength}";
	} elseif ($MaxLength != "*") {
		$MaxLength = '+';
	}
 
	$OKChars = addcslashes ($Extra, '_[]')."\\w\\pL' \\.\\-";
 
	if (preg_match ('/^\\w['.$OKChars.']'.$MaxLength.'\\z/u', $String)) {
		return $String;
	}
 
	return false;
}
 
/**
 * Validates given string as an IP address, returns string on success or false on failure.
 *
 * @param string $String
 * @return mixed
 */
function Val_ip ($String) {
	// Check that the string is a valid IP.
	if (long2ip (ip2long ($String)) != $String) {
		// Wasn't, return error.
		return false;
	}
 
	// Everything was OK, return string.
	return $String;
}
 
/**
 * Valiadtes that string is a valid time; Hours:Minutes
 *
 * @param string $String
 * @return mixed
 */
function Val_time ($String) {
	list ($Hours, $Minutes) = explode (":", $String);
	if (Val_int ($Hours, 2) === false || Val_num ($Minutes, 2) === false) {
		return false;
	}
 
	if ($Hours > 24 || $Minutes > 60) {
		return false;
	}
 
	return $String;
}
 
/**
 * Valiadtes that string is a valid date; DD/MM/YYYY
 *
 * Not compatiabol longer than Year: 2500 :-)
 * @param string $String
 * @return mixed
 */
function Val_date ($String) {
	list ($Day, $Month, $Year) = explode ("/", $String);
	if (Val_int ($Day, 2) === false || Val_num ($Month, 2) === false || Val_num ($Year, 4) === false) {
		return false;
	}
 
	if ($Day > 31 || $Month > 60 ||  $Year > 2500) {
		return false;
	}
 
	return $String;
} 
 
 
/**
 * Validates that all elements of $Check are keys in array $Source.
 * Returns a comma-delimited list of the matching values in $Source on success.
 *
 * @param array $Check
 * @param array $Source
 * @return mixed
 */
function Val_Array ($Check, &$Source) {
	if (!is_array ($Check)) {
		return false;
	}
 
	if (!is_array ($Source)) {
		return false;
	}
 
	$Retval = '';
 
	foreach ($Check as $Key) {
		if (!isset ($Source[$Key])) {
			return false;
		}
 
		$Retval .= $Source[$Key].", ";
	}
 
	return substr ($Retval, 0, -2);
}
 
 
 
 
/**
 * Returns a HTML selection list, for use with selecting the IDpdv
 * Usage: idpdv_select (@$_GET['idpdv'])
 *
 * @param array $array
 * @param string $default_value
 * @return array
 */
 
function idpdv_select($array, $default_value = '') {	
	$output = '<option value="' . $default_value . '">Punto Vendita</option>';
	$i = 0;
	foreach ($array as $row){
		$output .= ('<option value="' . $array[$i][0] . '">' . $array[$i][0] . ' - ' . $array[$i][1] . '</option>');
		$i ++;
		}
	return $output;
 }
 
function idpdv_select_name($array, $idpdv) {	
		$i = 0;
	foreach ($array as $row){
		if ($array[$i][0] == $idpdv) {
				$output = ($array[$i][0] .' - '. $array[$i][1]);
			}
		}
	if (!isset ($output)) {
		throw new Exception("There is no IDpdv/list selected.. Try again.");
	}
	return $output;
 }
 
 	

/**
* pasrse_numeric takes an array, and convert numeric values 
* that are stored as string, to integers. It also trims 
* away trailng spaces on string elements. 
* example: "f00 bar               " becomes "f00 bar"
* 
* Params:
* @param $mixed array
* return $mixed array 
*/

function parse_numeric ( &$mixed ) { 
	if ( is_array ( $mixed ) ) {
		foreach ( $mixed as &$val ) {
			if ( is_array ( $val ) ) { 
				parse_numeric ( $val );
			} else if ( is_numeric ( trim ( $val ) ) ) {
				$val = (int) $val;
			} else if ( preg_match ( '/(\d),(\d)/' , $val ) ) {
				$val = preg_replace ( '/(\d),(\d)/' , '$1.$2' , $val);
			} else if ( is_string ( trim ( $val ) ) ) {
				$val = (string) trim ( $val );
			}
		}
	} else if ( is_numeric ( $mixed ) ) { 
		$mixed = (int) $mixed; 
	} 
}
 
function smartreadfile($location, $filename, $mimeType='application/octet-stream') { 
	if ( !file_exists ( $location ) ) { 
		header ( "HTTP/1.0 404 Not Found" );
		return;
	}
	
	$size = filesize ( $location );
	$time = date ( 'r', filemtime ( $location ) );
	
	$fm = @fopen ( $location, 'rb' );
	if ( !$fm ) {
		header ("HTTP/1.0 505 Internal server error");
		return;
	}
	
	$begin = 0;
	$end = $size;
	
	if ( isset ( $_SERVER['HTTP_RANGE'] ) ) {
		if ( preg_match ( '/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches ) ) {
			$begin = intval ( $matches[0] );
			if ( !empty ( $matches[1] ) ) {
				$end = intval ( $matches[1] );
			}
		}
	}
	
	if ( $begin > 0 || $end < $size )
		header('HTTP/1.0 206 Partial Content');
	else
		header('HTTP/1.0 200 OK');	
		header("Content-Type: $mimeType"); 
		header('Cache-Control: public, must-revalidate, max-age=0');
		header('Pragma: no-cache');	
		header('Accept-Ranges: bytes');
		header('Content-Length:'.($end-$begin));
		header("Content-Range: bytes $begin-$end/$size");
		header("Content-Disposition: inline; filename=$filename");
		header("Content-Transfer-Encoding: binary\n");
		header("Last-Modified: $time");
		header('Connection: close');	
	
	$cur=$begin;
	fseek($fm,$begin,0);

	while ( !feof ( $fm ) && $cur < $end && ( connection_status () == 0 ) ) { 
		print fread ( $fm, min ( 1024*16, $end-$cur ) );
		$cur += 1024*16;
	}
}
?>