<?php

class Route
{
	static function start()
	{
		// контроллер и действие по умолчанию
		$controller_name = '';
		$action_name = '';		
		
		$router = new AltoRouter();
		$router->setBasePath( App::$app_url );
		
		// add site routes here				
		$router->map( 'GET', '/', 													array( 'c' => 'main', 'a' => 'index' ) ); 	# home page
		$router->map( 'GET', '/art/[i:id]', 								array( 'c' => 'art', 'a' => 'display' ) ); 	# single art page
		
		$router->map( 'GET', '/admin/galleries', 						array( 'c' => 'gallery', 'a' => 'list' ) ); # admin list galleries
		$router->map( 'GET', '/admin/gallery/[i:id]/edit', 	array( 'c' => 'gallery', 'a' => 'edit' ) ); # admin edit gallery page
		// end of site routes		
		
		$match = $router->match();
		
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