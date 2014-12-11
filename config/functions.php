<?php
/**
 * is_odd()
 * 
 * @param mixed $num
 * @return
 */
function is_odd($num){
	if (!is_numeric($num)) {
		trigger_error( $num . " is not numberic");
		return;
	}
	return ($num&1);
}

/**
 * is_even()
 * 
 * @param mixed $num
 * @return
 */
function is_even($num){
	if (!is_numeric($num)) {
		trigger_error( $num . " is not numberic");
		return;
	}

  return (!($num&1));
}


 function onLocalhost() {

		if (strpos(env('REMOTE_ADDR'),'127.') === false) {
			return false;
		}
		return true;
}

function searchNestedArray(array $array, $search, $mode = 'value') {
	
	    foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $key => $value) {
	        if ($search === ${${"mode"}})
	            return true;
	    }
	    return false;
	}
    
    
if (!function_exists('json_last_error_msg')) {
     function json_last_error_msg() {
         static $errors = array(
             JSON_ERROR_NONE             => null,
             JSON_ERROR_DEPTH            => 'Maximum stack depth exceeded',
             JSON_ERROR_STATE_MISMATCH   => 'Underflow or the modes mismatch',
             JSON_ERROR_CTRL_CHAR        => 'Unexpected control character found',
             JSON_ERROR_SYNTAX           => 'Syntax error, malformed JSON',
             JSON_ERROR_UTF8             => 'Malformed UTF-8 characters, possibly incorrectly encoded'
         );
         $error = json_last_error();
         return array_key_exists($error, $errors) ? $errors[$error] : "Unknown error ({$error})";
     }
 } 
 
 
 
 