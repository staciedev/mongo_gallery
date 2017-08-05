<?php

class ControllerArt extends Controller {
	
	function __construct()
	{
		$this->model = new ModelArt();
		//$this->view = new View();
	}
	
	function action_index()
	{		
		echo 'This is art controller, index action.';		
	}
	
	// echoes result because called by ajax
	function action_ajax_save() {
		echo json_encode( $this->model->bulk_save() );
	}
	
	
}