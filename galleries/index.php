<?php

// My awesome project with mongodb
require '../vendor/autoload.php';
ini_set('display_errors', 1);

ini_set( 'log_errors', 1 );
ini_set( 'error_log', dirname(__FILE__) . '/debug.log' );

require_once 'application/bootstrap.php';

//var_dump( get_class_methods( get_class( App::$db->galleries ) ) );