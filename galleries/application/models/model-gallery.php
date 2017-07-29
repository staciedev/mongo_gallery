<?php

class ModelGallery extends Model {		
	
	function __construct()
	{
		// ...
	}
	
	function get_data()
	{		
		// collection: galleries
		try {
			$data = App::$db->galleries->find();
		}
		catch ( Exception $e ) {    	
			$data['error'] = $e->getMessage();
		}
		
		return $data;		
	}
	
	// inserts one gallery. Uses POST method
	function insert() {
				
		$id = $this->generate_id();
		
		$document = array( 'id' => $id );
		
		// debug		
		$document['name'] = 'debug';
		
		if( !empty( $_POST['gllry-title'] ) )
			$document['name'] = $_POST['gllry-title'];
			
		try {			
			App::$db->galleries->insertOne( $document );			
		}
		catch ( Exception $e ) {
			// return $e->getMessage();
		}
		
	}
	
}