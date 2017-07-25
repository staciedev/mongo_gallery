<?php

class App
{
	
	// configurations
	public static $app_url = 'mongodb_project/galleries';
	public static $db_name = 'galleriesdb';
	
	// other variables for convenience
	public static $complete_url;
	
	static function init() {
		$protocol = $_SERVER['SERVER_PROTOCOL'];
		$protocol = explode( '/', $protocol );
		$protocol = $protocol[0];
		self::$complete_url = $protocol.'://'.$_SERVER['HTTP_HOST'].'/'.self::$app_url;
	}
}