<?php

class LibRepo{

	const CDN = 'cdn';
	const LOCAL = 'local';


	public static function jquery($cdn = false ,$version = null) {
		
		if ($cdn) {
			return 'aaa';
		}
		
		if (!$cdn) {
			return '//code.jquery.com/jquury.js';
		}
		
		return false;
		
	}
	
}