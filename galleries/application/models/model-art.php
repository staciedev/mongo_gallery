<?php

class ModelArt extends Model {	
	
	protected $id_length = 7;
	protected $collection_name = 'arts';		
	
	function __construct()
	{
		// ...
	}
	
	function get_data()
	{			
	}
	
	/**
	* inserts one art into the database
	* @return 
	*/
	function insert( $args ) {
		
		$fields = [
			'filename' => 'string',
		];
		
		$document = [];
		
		$id = $this->generate_unique_id();
		if( $id == -1 ) return false;
		
		$document['_id'] = $id;
		
		foreach ( $fields as $field => $type ) {
			if( !empty( $args[$field] ) && gettype( $args[$field] ) == $type )
				$document[$field] = $args[$field];
			else {
				$document[$field] = null;
			}
		}		
		
		$collection_name = $this->collection_name;
		try {					
			$result = App::$db->$collection_name->insertOne( $document );				
		}
		catch ( Exception $e ) {
			return false;			
		}
		return $result->getInsertedId();
	}
	  
	/**
	* uploads the arts and saves them to the database.
	* @return array arts on success, int error code on failure
	*/
	function bulk_save( $files, $gallery_id ) {
		
		if( 
			empty( $files['tmp_name'] ) || 
			empty( $files['name'] ) ||  
			empty( $gallery_id ) ||
			gettype( $gallery_id ) != 'string'		
		) return -1;		
		
		$basedir = App::content_dir().'/images/'.$gallery_id.'/';
		
		if ( !file_exists( $basedir ) ) {
    	if( !mkdir( $basedir, 0755, true ) ) return -2;
		}		
		
		// final array. Should contain db ID, success, file url, art_url
		$arts = [];
				
		foreach ( $files['tmp_name'] as $index => $tmp_name ) {
			$art = [
				'tempID' => $index,
				'success' => false
			];
			
			$error = $files['error'][$index];
			if ( $error == UPLOAD_ERR_OK ) {			
				// getting file name and extension
				// TODO: use a regex instead
				$fullname = basename( $files['name'][$index] );
				$name_parts = explode( '.', $fullname );
				if( count($name_parts) >= 2 ) {
					$fileext = '.' . $name_parts[ count($name_parts)-1 ];
					unset( $name_parts[ count($name_parts)-1 ] );
					$filename = implode( $name_parts );
				}
				else {
					$filename = $name_parts[0];
					$fileext = '';
				}
				
				// getting unique file name
				$unique_name = false;
				$ind = 0;
				$prefix = '';
				
				while( !$unique_name ) {
					$mbname = $basedir . $filename . $prefix  . $fileext;
					
					if( file_exists( $mbname ) ) {					
						$prefix = '_' . $ind++;
					}
					else {
						$filename = $filename . $prefix . $fileext;
						$unique_name = true;
					}
				}
				
				$moved = move_uploaded_file( $tmp_name, $basedir . $filename );				
				$art['fileURL'] = App::content_url().'/images/'.$gallery_id.'/'.$filename;
				
				$doc = [
					'filename' => $filename
				];
				
				$inserted_id = $this->insert( $doc );				
				$art['ID'] = $inserted_id;
				
				$art['success'] = $moved && $inserted_id;
			}
			
			
			$arts[] = $art;
		}
		
		return $arts;
	}
	
	
	
}