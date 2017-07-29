<?php

class Model
{
	
	// returns
	public function generate_id() {
		$length = 6;
		$base = '0123456789abcdefghijklmnopqrstuvwxyz';
		
		$id = '';
		for ( $i=0; $i < $length; $i++ ) { 
			$index = rand( 0, strlen($base)-1 );
			$sym = $base[$index];
			$id .= $sym;
		}
		
		return $id;
	}
	
	// should be overriden in descendants
	public function get_data() {}
	
}