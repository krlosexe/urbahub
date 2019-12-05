<?php 
 	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	 	if (!function_exists('consultar_existe_mongodb')) {
	 		function consultar_existe_mongodb($tabla) {
	 			$res = mongo_db->where(array('eliminado'=>false))->get($tabla);
	 			return count($res);
	 		} 
	 	}
?>	 		