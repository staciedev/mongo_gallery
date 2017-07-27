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
		$this->view->generate( 'gallery-list.php', 'template-admin.php', $data );
	}
}