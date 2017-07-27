<?php

class ControllerGallery extends Controller {
	
	function __construct()
	{
		$this->model = new ModelGallery();
		$this->view = new View();
	}
	
	function action_index()
	{		
		echo 'This is gallery controller, index action.';		
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
}