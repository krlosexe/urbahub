<?php 
 	if (!function_exists('load_controller_new')) {
 		function load_controller_new($controlador, $metodo = 'index', $array_request = null) {
 			if(file_exists(APPPATH . 'services/' . $controlador . '.php')){
	 			require_once(APPPATH . 'services/' . $controlador . '.php'); 
	 			$controller = new $controlador(); 
	 			if(method_exists($controller, $metodo)){
	 				return $controller->$metodo($array_request);
	 			}
	 		}
	 		return array("cod_response" => "404", "data" => null, "detalle" => "");
 		} 
 	} 
?>