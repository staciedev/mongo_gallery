<?php

class Model
{
	
	protected $id_length;
	protected $collection_name;
	
	public function generate_id() {
		
		$base = '0123456789abcdefghijklmnopqrstuvwxyz';
		
		$id = '';
		for ( $i=0; $i < $this->id_length; $i++ ) { 
			$index = rand( 0, strlen($base)-1 );
			$sym = $base[$index];
			$id .= $sym;
		}
		
		return $id;
	}
	
	/**
	* @return string id on success, int error code on failure
	*/
	public function generate_unique_id() {
		$unique = false;
		$id = 0;	
		
		$collection_name = $this->collection_name;
		// generating a unique id
		while( !$unique ) {
			
			$id = $this->generate_id();
				
			try {
				$exist = App::$db->$collection_name->count(['_id' => $id]);
				if( $exist === 0 ) {
					$unique = true;					
				}				
				
			} catch ( Exception $e ) {
				error_log( $e->getMessage() );
				return -1;				
			}					
		}
		return $id;
	}
	
	// should be overriden in descendants
	public function get_data() {}
	
}