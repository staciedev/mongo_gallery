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
	
}