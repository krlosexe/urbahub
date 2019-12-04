<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('Base_service.php');
class Acceso extends Base_service {

	function __construct(){
        parent::__construct();
        $this->CI->load->library('session');
		$this->CI->load->model('Membresia_model');
		$this->CI->load->model('MiCorreo_model');
    	$this->CI->load->library('form_validation');
   	    $this->CI->load->helper('array_push_assoc');
    }

	public function index($datos)
	{
		return $this->login($datos);
	}

	public function login($datos)
	{
		$response = array(
							'cod_response' => "500", 
							'detalle' => "", 
							'data' => null,
					);
		if(is_array($datos)){

  			//Busca si existe usuario en base de datos
	      	$resultado['arreglo_datos'] = $this->CI->Membresia_model->buscar_membresia((isset($datos['correo'])?$datos['correo']:""), (isset($datos['serial'])?$datos['serial']:""));

	      	//Valida si no trae ningun usuario
      		if(count($resultado['arreglo_datos']) == 0){
      			///Usuario no valido
	      		$response['cod_response'] = "401";
	      		$response['detalle'] = "Usuario o contraseña incorrectos.";
	      	}
	      	else{
	      		///Usuario valido
	      		$resultado['arreglo_datos'] = $resultado['arreglo_datos'][0];

	      		//Valida que la membresia este activa
      			if($resultado['arreglo_datos']['status'] != true){
      				///El usuario esta desactivado
      				$response['cod_response'] = "403";
	      			$response['detalle'] = "No puede acceder.";
				}
				else{
					///El usuario esta activo

	            	//Guarda datos en session
					$this->CI->session->set_userdata($resultado['arreglo_datos']);

	            	$arreglo_historial_token = array(
	            							"fecha_registro" => date("Y-m-d H:i:s"),
											"fecha_expira" => date("Y-m-d H:i:s", strtotime ("+".SISTEMA_EXPIRA_TOKEN." Hours")),
											"ip" => isset($datos['ip'])?$datos['ip']:SERVICE_IP_CLIENT,
											"token" => str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789".uniqid()),
											"fecha_valid" => date("Y-m-d"),
										);

	            	/*$buscar_token = $this->CI->Membresia_model->historial_token_buscar(
		            	array(
		            				'_id'=> new MongoDB\BSON\ObjectId($resultado['arreglo_datos']['id_membresia']),
		            				'historial_token.ip'=>$arreglo_historial_token['ip'],
		            				'historial_token.fecha_valid'=>$arreglo_historial_token['fecha_valid'],
		            			)
	            	);

	            	if(count($buscar_token) > 0){
	            		$buscar_token = $buscar_token[0];
	            		$response['cod_response'] = "203";
      					$response['detalle'] = "Ya tenia un token activo.";
	            		$response['data'] = $buscar_token;
		            	$response['data']['datos_user'] = $resultado['arreglo_datos'];
	            	}
	            	else{*/
	            		if($this->CI->Membresia_model->historial_token_registrar($arreglo_historial_token, $resultado['arreglo_datos']['id_membresia'])){
	            			$response['cod_response'] = "200";
	      					$response['detalle'] = "Se genero el token.";
		            		$response['data'] = $arreglo_historial_token;
		            		$response['data']['membresia'] = $resultado['arreglo_datos'];
	            		}
	            		else{
	            			$response['cod_response'] = "400";
      						$response['detalle'] = "Error al generar token.";
	            		}
	            	//}
				}
	      	}

		}

		return $response;
	}


	public function validar($datos, $vista_acceso = null)
	{
		$response = array(
							'cod_response' => "500", 
							'detalle' => "", 
							'data' => null,
					);

		if(is_array($datos)){

			$where_datos = array('historial_token' =>  array('$elemMatch' => 
								array(
									'ip' => SERVICE_IP_CLIENT,
									'token' => (isset($datos['token'])?$datos['token']:""),
									"fecha_valid" => date("Y-m-d"),
								)
							), "eliminado" => false);

	      	$resultado['arreglo_datos_token'] = $this->CI->Membresia_model->historial_token_buscar($where_datos);
	      	
	      	//Valida si no trae ningun usuario
      		if(!count($resultado['arreglo_datos_token']) > 0){
      			///Usuario no valido
	      		$response['cod_response'] = "402";
	      		$response['detalle'] = "Token no valido.";
	      	}
	      	else{
	      		///Usuario valido
	      		$resultado['arreglo_datos'] = $resultado['arreglo_datos_token'][0];

	      		//Valida que tanto el rol como el usuario esten activos
      			if($resultado['arreglo_datos']['status'] != true){
      				///El usuario esta desactivado
      				$response['cod_response'] = "403";
	      			$response['detalle'] = "No puede acceder.";
				}
				else{
					///El usuario esta activo
					$response['cod_response'] = "200";
	      			$response['detalle'] = "Token validado correctamente.";
	      			$response['data'] = $resultado['arreglo_datos'];
				}
	      	}

		}

		return $response;
	}
}