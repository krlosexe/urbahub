<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('Acceso.php');
class Sala extends Acceso {

	function __construct(){
        parent::__construct();
		$this->CI->load->model('Reservaciones_model');
    }

	public function buscar($datos)
	{
		$response = $this->validar($datos, 'service/'.__CLASS__."/".__FUNCTION__);
		if($response['cod_response'] == "200"){
			$usuario = $response['data'];
			$response['data'] = null;
			$response['cod_response'] = "998";
	      	$response['detalle'] = "Error en metodo.";
	      	$datos_busqueda = $this->CI->Reservaciones_model->listado_salas();
	      	if(count($datos_busqueda) > 0){
      			$response['cod_response'] = "200";
      			$response['detalle'] = "Exitosa.";
      			$response['data'] = $datos_busqueda;
	      	}
	      	else{
	      		$response['cod_response'] = "no_salas";
	      		$response['detalle'] = "No se encuentran datos de salas.";
	      	}
		}

		return $response;
	}

	public function consultar($datos)
	{
		$response = $this->validar($datos, 'service/'.__CLASS__."/".__FUNCTION__);
		if($response['cod_response'] == "200"){
			$usuario = $response['data'];
			$response['data'] = null;
			$response['cod_response'] = "998";
	      	$response['detalle'] = "Error en metodo.";
	      	
	      	$datos_busqueda = $this->CI->Reservaciones_model->buscarSalas((isset($datos['_id'])?$datos['_id']:""));
	      	if(count($datos_busqueda) > 0){
      			$response['cod_response'] = "200";
      			$response['detalle'] = "Exitosa.";
      			$response['data'] = $datos_busqueda[0];
	      	}
	      	else{
	      		$response['cod_response'] = "no_sala";
	      		$response['detalle'] = "No se encuentran datos de banco.";
	      	}
		}

		return $response;
	}

}