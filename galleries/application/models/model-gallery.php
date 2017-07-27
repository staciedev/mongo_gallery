<?php

class ModelGallery extends Model {		
	
	function __construct()
	{
		// ...
	}
	
	function get_data()
	{		
		// collection: galleries
		$data = App::$db->galleries->find();
		return $data;		
	}
	
}