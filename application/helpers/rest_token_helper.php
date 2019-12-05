<?php 
 	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	 	if (!function_exists('rest_token')) {
	 		function rest_token($ip) {
            	///Arreglo
                $arreglo_datos = array(
                                    'controlador' => "Acceso",
                                    'metodo' => "login",
                                    'array_request' => array(
                                                                'user' => NOMBRE_USUARIO,
                                                                'password' => CLAVE_USUARIO,
                                                            ),
                            );
        		$arreglo_post = array(
                                "request" => json_encode($arreglo_datos),
                            );
				//$ip = "";
				$curl_handle = curl_init();
				curl_setopt($curl_handle, CURLOPT_URL, URL_TOKEN);
				curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl_handle, CURLOPT_POST, 1);
				/*curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(
					'correo_usuario' => NOMBRE_USUARIO,
		        	'clave_usuario' => CLAVE_USUARIO,
					'ip'=> $ip
				));*/
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $arreglo_post);

				$buffer = curl_exec($curl_handle);
				curl_close($curl_handle);
				$result = json_decode($buffer);
		 		return $result; 
		 	} 
 	}