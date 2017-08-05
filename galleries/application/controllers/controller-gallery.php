<?php

class ControllerGallery extends Controller {
	
	function __construct()
	{
		$this->model = new ModelGallery();
		$this->view = new View();
	}	
	
	// lists all galleries
	function action_list() 
	{		
		$data = $this->model->get_data();
				
		if( is_array( $data ) && !empty( $data['error'] ) )
			$this->view->generate( 'error.php', 'template-admin.php', $data );
			
		else
			$this->view->generate( 'gallery-list.php', 'template-admin.php', $data );			
	}
	
	// Opens an editor page
	function action_add_new() {
		$this->view->generate( 'gallery-edit.php', 'template-admin.php' );
	}
	
	// saves a gallery (used with AJAX)
	function action_ajax_save() {		
		
		$id = ( !empty( $_POST['gllry-id'] ) ) ? $_POST['gllry-id'] : null;
		$name = ( !empty( $_POST['gllry-title'] ) ) ? $_POST['gllry-title'] : null;
		$files = ( !empty( $_FILES['gllry-arts'] ) ) ? $_FILES['gllry-arts'] : null;
		
		$result = $this->model->save( $id, $name, $files );
		
		echo json_encode( $result );
	}
}