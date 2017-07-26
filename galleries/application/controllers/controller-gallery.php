<?php

class ControllerGallery extends Controller {
	
	// public $model;
	// public $view;
	
	function __construct()
	{
		$this->view = new View();
	}
	
	function action_index()
	{		
		echo 'This is gallery controller, index action.';		
	}
	function action_list() 
	{
		
	}
}