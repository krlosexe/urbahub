<?php 
 	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	 	if (!function_exists('array_push_assoc')) {
	 		function array_push_assoc(array &$arrayDatos, array $values){
			    $arrayDatos = array_merge($arrayDatos, $values);
			    return $arrayDatos;
			}
 	}