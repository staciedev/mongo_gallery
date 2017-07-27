<?php

class App
{
	
	// configurations
	public static $app_url = '/mongodb_project/galleries';
	public static $db_name = 'galleriesdb';
	public static $db = null;
	
	// other variables for convenience
	public static $complete_url;
	
	static function init() {
		$protocol = $_SERVER['SERVER_PROTOCOL'];
		$protocol = explode( '/', $protocol );
		$protocol = $protocol[0];
		self::$complete_url = $protocol.'://'.$_SERVER['HTTP_HOST'].'/'.self::$app_url;
		
		self::db_connect();
	}
	
	// initializing db connection
	static function db_connect() {
		$client = new MongoDB\Client( "mongodb://localhost:27017" );
		$db_name = self::$db_name;
		self::$db = $client->$db_name;
	}
}