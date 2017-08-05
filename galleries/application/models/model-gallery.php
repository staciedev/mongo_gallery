<?php

class ModelGallery extends Model {
	
	protected $id_length = 6;
	protected $collection_name = 'galleries';		
	
	function __construct()
	{
		// ...
	}	
	
	function get_data()
	{		
		$collection_name = $this->collection_name;
		try {
			$data = App::$db->$collection_name->find();
		}
		catch ( Exception $e ) {    	
			$data['error'] = $e->getMessage();
		}
		
		return $data;		
	}
	
	// possible TODO
	function insert( $name=null ) {			
	}
	
	/**
	* @return array result 
	*/
	function save( $id, $name, $files ) {
		$result = array(
			'error' => true,
			'message' => '',
			'data' => []
		);
		
		$create = false; // wheather to create a new gallery
		
		if( !$id ) {
			
			$create = true;
			// generate an ID for new gallery
			$id = $this->generate_unique_id();
			
			if( $id == -1 ) {
				$result['message'] = 'DB Error. Could not generate an ID';
				return $result;
			}			
		}
		
		// upload files and insert arts to db
		
		if( !empty( $files ) ) {
			require_once('model-art.php');
			$modelArt = new ModelArt();
			$arts = $modelArt->bulk_save( $files, $id );
			
			if( gettype( $arts ) == 'integer' ) {
				switch ($arts) {
					case -1:
						$result['message'] = 'Invalid arguments passed to arts bulk_save()';
						break;					
					case -2:
						$result['message'] = 'Could not create a directory';
						break;
				}
				
				return $result;
			}
		}
		
		// $arts now contain an array of arts uploaded to the database and file system
		
		$art_ids = [];
		foreach ( $arts as $art ) {
			if( $art['success'] == true )
				$art_ids[] = $art['ID'];
		}
		
		$gallery_doc = [
			'_id' => $id,
			'name' => $name,
			'arts' => $art_ids
		];
		
		// insert or update gallery
		
		$collection_name = $this->collection_name;
		try {	
			if( $create )				
				$upserted = App::$db->$collection_name->insertOne( $gallery_doc );
			else	
				$upserted = App::$db->$collection_name->updateOne( ['_id' => $id], ['$push' => ['arts' => [ '$each' => $art_ids ] ] ] );
		}
		catch ( Exception $e ) {
			$result['message'] = 'DB Error. Could not insert gallery';
			error_log( $e->getMessage() );
			return $result;
		}		
		
		// finally
		$result['error'] = false;
		$result['data'] = [
			'galleryID' => $id,
			'arts' => $arts,
		];
		
		return $result;
	}
	
}

// Note: mongodb request to get multiple galleries with their arts 
/* 
db.galleries.aggregate(
	 [
		 {$unwind: '$arts'},
		 {$lookup: {from: 'arts', localField: 'arts', foreignField: '_id', as: 'agg_arts' } }, 
		 {$group: {_id: "$id", name: {$first: "$name"}, arts:{$push: "$agg_arts"} } }
	 ]
) */