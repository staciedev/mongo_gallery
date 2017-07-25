<?php

// Require base classes

require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/route.php';
require_once 'core/config.php';
App::init(); // configurations 
Route::start(); // запускаем маршрутизатор