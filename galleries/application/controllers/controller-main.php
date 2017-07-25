<?php

class ControllerMain extends Controller {
	
	// public $model;
	// public $view;
	
	function __construct()
	{
		$this->view = new View();
	}
	
	function action_index()
	{
		$this->view->generate('front.php', 'template.php');
	}
}