<?php

class Route
{
	static function start()
	{
		// контроллер и действие по умолчанию
		$controller_name = '';
		$action_name = '';	
		
		// add site routes here				
		App::$router->map( 'GET', '/', 														array( 'c' => 'main', 'a' => 'index' ) ); 	# home page
		App::$router->map( 'GET', '/art/[a:id]', 									array( 'c' => 'art', 'a' => 'display' ) ); 	# single art page
		
		App::$router->map( 'GET', '/admin/galleries', 						array( 'c' => 'gallery', 'a' => 'list' ) ); # admin list galleries
		App::$router->map( 'GET', '/admin/gallery/new', 					array( 'c' => 'gallery', 'a' => 'add_new' ), 'admin_new_gallery' ); # admin add new gallery
		App::$router->map( 'GET', '/admin/gallery/[a:id]/edit', 	array( 'c' => 'gallery', 'a' => 'edit' ) ); # admin edit gallery page		
		
		App::$router->map( 'POST', '/ajax/gallery/save', 					array( 'c' => 'gallery', 'a' => 'ajax_save' ), 'ajax_save_gallery' ); # save gallery with ajax
		App::$router->map( 'POST', '/ajax/art/save', 							array( 'c' => 'art', 'a' => 'ajax_save' ), 'ajax_save_art' ); # save art with ajax
		App::$router->map( 'DELETE', '/ajax/gallery/[a:id]', 			array( 'c' => 'gallery', 'a' => 'ajax_delete' ), 'ajax_delete_gallery' ); # delete gallery with ajax
		// end of site routes		
		
		$match = App::$router->match();
		
		if ( !empty( $match['target']['c'] ) && !empty( $match['target']['a'] ) )
		{
			$controller_name = $match['target']['c'];
			$action_name = $match['target']['a'];
		}	

		// подцепляем файл с классом модели (файла модели может и не быть)		
		$model_name = $controller_name;
		$model_file = 'model-'.$model_name.'.php';
		$model_path = "application/models/".$model_file;
		if(file_exists($model_path))
		{
			include "application/models/".$model_file;
		}

		// подцепляем файл с классом контроллера
		$controller_file = strtolower( 'controller-' . $controller_name ) . '.php';
		$controller_path = "application/controllers/" . $controller_file;
		if ( file_exists( $controller_path ) )
		{
			include "application/controllers/" . $controller_file;
		}
		else
		{
			/*
			правильно было бы кинуть здесь исключение,
			но для упрощения сразу сделаем редирект на страницу 404
			*/
			
			Route::ErrorPage404();
			return;
		}
		
		// создаем контроллер
		$controller_class = 'Controller'.ucfirst($controller_name);
		$controller = new $controller_class;
		$action = 'action_'.$action_name;
		
		if( method_exists( $controller, $action ) )
		{			
			$params = $match['params'];			
			
			// calling controller action
			$controller->$action( $params );			
		}
		else
		{
			// здесь также разумнее было бы кинуть исключение			
			Route::ErrorPage404();
			return;
		}
	
	}
	
	static function ErrorPage404()
	{      
      header('HTTP/1.1 404 Not Found');
		  header("Status: 404 Not Found");
		  
			$view = new View();
			$view->generate('404.php', 'template.php');			
  }
}