<?php 
 	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	 	if (!function_exists('consumir_rest')) {
	 		function consumir_rest($controlador, $metodo,/*$cod_reg,$cod_reg2*/ $array_post = array()) { 

				$curl_handle = curl_init();
				curl_setopt($curl_handle, CURLOPT_URL, URL_SOLICITAR_METODO);
				curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl_handle, CURLOPT_POST, 1);
				/*curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(
					'token' => TOKEN,
					'controlador' => $controlador,
					'metodo' => $metodo,
					'cod_reg'=> $cod_reg,
					'cod_reg2'=>$cod_reg2
				));*/

				$array_post['token'] = TOKEN;
				$arreglo_datos = array(
		                                    'controlador' => $controlador,
		                                    'metodo' => $metodo,
		                                    'array_request' => $array_post,
		                            );
		        $arreglo_post = array(
		                                "request" => json_encode($arreglo_datos),
		                            );
                
				curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $arreglo_post);

				$buffer = curl_exec($curl_handle);
				curl_close($curl_handle);
				$result = json_decode($buffer);
		 		return $result; 
		 	} 
 	}