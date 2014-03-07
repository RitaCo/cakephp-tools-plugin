<?php
function is_odd($num){
	if (!is_numeric($num)) {
		trigger_error( $num . " is not numberic");
		return;
	}
	return ($num&1);
}

function is_even($num){
	if (!is_numeric($num)) {
		trigger_error( $num . " is not numberic");
		return;
	}

  return (!($num&1));
}
