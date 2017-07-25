<?php

class Route
{
	static function start()
	{
		// контроллер и действие по умолчанию
		$controller_name = 'main';
		$action_name = 'index';
		
		// adjustments in case the application has extra url parts
		$uri = trim( $_SERVER['REQUEST_URI'], '/' );		
		if( strpos( $uri, App::$app_url ) === 0 ) {
			$uri = substr_replace( $uri, '' , 0 , strlen( App::$app_url ) );
			$uri = trim( $uri, '/' );
		}		
		
		$routes = explode('/', $uri);		

		// получаем имя контроллера
		if ( !empty($routes[0]) )
		{	
			$controller_name = $routes[0];
		}
		
		// получаем имя экшена
		if ( !empty($routes[1]) )
		{
			$action_name = $routes[1];
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
		$controller_file = strtolower('controller-'.$controller_name).'.php';
		$controller_path = "application/controllers/".$controller_file;
		if(file_exists($controller_path))
		{
			include "application/controllers/".$controller_file;
		}
		else
		{
			/*
			правильно было бы кинуть здесь исключение,
			но для упрощения сразу сделаем редирект на страницу 404
			*/
			Route::ErrorPage404();
		}
		
		// создаем контроллер
		$controller_class = 'Controller'.ucfirst($controller_name);
		$controller = new $controller_class;
		$action = 'action_'.$action_name;
		
		if(method_exists($controller, $action))
		{
			// вызываем действие контроллера
			$controller->$action();
		}
		else
		{
			// здесь также разумнее было бы кинуть исключение
			Route::ErrorPage404();
		}
	
	}
	
	function ErrorPage404()
	{
      $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
      header('HTTP/1.1 404 Not Found');
		  header("Status: 404 Not Found");
		  header('Location:'.$host.'404');
			
			echo 'Not found.';
  }
}