<?php 
 	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	 	if (!function_exists('organizar_sepomex')) {
	 		function organizar_sepomex($sepomex, $crear_list = false) { 



				/*$estados = $sepomex->data->array_list->estados[0];
				$ciudades = $sepomex->data->array_list->ciudades[0];
				$municipios = $sepomex->data->array_list->municipios[0];
				$colonias = $sepomex->data->array_list->colonias[0];*/
				if($crear_list){
					if(is_array($sepomex->data)){
					    foreach ($sepomex->data as $key => $value) {
					        if(isset($data['estados'])){
					          if(!(array_search($value->d_estado, array_column($data['estados'], 'd_estado')) !== False)) {
					            $data['estados'][] = $value;
					          }
					        }
					        else{
					          $data['estados'][] = $value;
					        }

					        if(isset($data['ciudades'])){
					          if(!(array_search($value->d_ciudad, array_column($data['ciudades'], 'd_ciudad')) !== False)) {
					            $data['ciudades'][] = $value;
					          }
					        }
					        else{
					          $data['ciudades'][] = $value;
					        }

					        if(isset($data['municipios'])){
					          if(!(array_search($value->d_mnpio, array_column($data['municipios'], 'd_mnpio')) !== False)) {
					            $data['municipios'][] = $value;
					          }
					        }
					        else{
					          $data['municipios'][] = $value;
					        }

					        if(isset($data['colonias'])){
					          if(!(array_search($value->d_asenta, array_column($data['colonias'], 'd_asenta')) !== False)) {
					            $data['colonias'][] = $value;
					          }
					        }
					        else{
					          $data['colonias'][] = $value;
					        }
					    }
					    
						$sepo= $data;
					}
					else{
						$sepo=array(
			                    'estados' => array(),
			                    'ciudades' => array(),
			                    'municipios'  => array(),
			                    'colonias' => array(),
	                	);
					}
				}
				else{
					if(is_array($sepomex->data)){
						$sepo=array(
			                    'd_estado' => $sepomex->data[0]->d_estado,
			                    'd_ciudad' => $sepomex->data[0]->d_ciudad,
			                    'd_mnpio'  => $sepomex->data[0]->d_mnpio,
			                    'd_asenta' => $sepomex->data[0]->d_asenta,	
			                    'd_codigo' => $sepomex->data[0]->d_codigo,
			                    'id_codigo_postal'=> $sepomex->data[0]->id_codigo_postal
	                	);
					}
					elseif(is_object($sepomex->data)){
						$sepo=array(
			                    'd_estado' => $sepomex->data->d_estado,
			                    'd_ciudad' => $sepomex->data->d_ciudad,
			                    'd_mnpio'  => $sepomex->data->d_mnpio,
			                    'd_asenta' => $sepomex->data->d_asenta,	
			                    'd_codigo' => $sepomex->data->d_codigo,
			                    'id_codigo_postal'=> $sepomex->data->id_codigo_postal
	                	);
					}
					else{
						$sepo=array(
			                    'd_estado' => "",
			                    'd_ciudad' => "",
			                    'd_mnpio'  => "",
			                    'd_asenta' => "",	
			                    'd_codigo' => "",
			                    'id_codigo_postal'=> ""
	                	);
					}
				}

		 		return $sepo; 
		 	} 
 	}