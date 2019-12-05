<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('Acceso.php');
class Reservacion extends Acceso {

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
	      	$datos_busqueda = $this->CI->Reservaciones_model->listado_reservaciones_todas();
	      	if(count($datos_busqueda) > 0){
	      		$lista_pertecene = [];
	      		foreach ($datos_busqueda as $key => $value) {
	      			if($usuario['_id']->{'$id'} == $value['id_membresia']){

			            /*$fecha_hora_inicio = $value["fecha"]->toDateTime();

			            $fecha_hora_ini = $fecha_hora_inicio->format('Y-m-d');

			            $fecha_actual = date('Y-m-d');*/
	      				//if($fecha_actual == $fecha_hora_ini){
			            $lista_pertecene[] = $value;
	      				//}
	      			}
	      		}

	      		if(count($lista_pertecene) > 0){
	      			$response['cod_response'] = "200";
	      			$response['detalle'] = "Exitosa.";
	      			$response['data'] = $lista_pertecene;
	      		}
		      	else{
		      		$response['cod_response'] = "no_reservaciones_pertenece";
		      		$response['detalle'] = "No se encuentran datos de reservaciones para membresia.";
		      	}
	      	}
	      	else{
	      		$response['cod_response'] = "no_reservaciones";
	      		$response['detalle'] = "No se encuentran datos de reservaciones.";
	      	}
		}

		return $response;
	}

	public function buscar_fecha($datos)
	{
		$response = $this->validar($datos, 'service/'.__CLASS__."/".__FUNCTION__);
		if($response['cod_response'] == "200"){
			$usuario = $response['data'];
			$response['data'] = null;
			$response['cod_response'] = "998";
	      	$response['detalle'] = "Error en metodo.";
	      	$datos_busqueda = $this->CI->Reservaciones_model->listado_reservaciones_todas();
	      	if(count($datos_busqueda) > 0){
	      		$lista_pertecene = [];
	      		foreach ($datos_busqueda as $key => $value) {
	      			$fecha_hora_inicio = $value["fecha"]->toDateTime();

		            $fecha_hora_ini = $fecha_hora_inicio->format('Y-m-d');

	      			if($datos['fecha'] == $fecha_hora_ini){
			            $lista_pertecene[] = $value;
	      			}
	      		}

	      		if(count($lista_pertecene) > 0){
	      			$response['cod_response'] = "200";
	      			$response['detalle'] = "Exitosa.";
	      			$response['data'] = $lista_pertecene;
	      		}
		      	else{
		      		$response['cod_response'] = "no_reservaciones_pertenece";
		      		$response['detalle'] = "No se encuentran datos de reservaciones para membresia.";
		      	}
	      	}
	      	else{
	      		$response['cod_response'] = "no_reservaciones";
	      		$response['detalle'] = "No se encuentran datos de reservaciones.";
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
	      	/*
	      	$datos_busqueda = $this->CI->Banco_model->buscar("tabla_p.id_".$this->CI->Banco_model->nombre_tabla."=".$this->CI->Base_model->escape((isset($datos['id_banco'])?$datos['id_banco']:"")), "nombre_".$this->CI->Banco_model->nombre_tabla);
	      	if(count($datos_busqueda) > 0){
      			$response['cod_response'] = "200";
      			$response['detalle'] = "Exitosa.";
      			$response['data'] = $datos_busqueda[0];
	      	}
	      	else{
	      		$response['cod_response'] = "no_reservacion";
	      		$response['detalle'] = "No se encuentran datos de reservación.";
	      	}*/
		}

		return $response;
	}

	public function registrar($datos)
	{
		$response = $this->validar($datos, 'service/'.__CLASS__."/".__FUNCTION__);
		if($response['cod_response'] == "200"){
			$usuario = $response['data'];
			$response['data'] = null;
			$response['cod_response'] = "998";
	      	$response['detalle'] = "Error en metodo.";

	      	if(!isset($datos['fecha'])){
	      		$response['cod_response'] = "406";
	      		$response['detalle'] = "Debe enviar campo de fecha.";
	      	}
	      	elseif(!isset($datos['hora_inicio'])){
	      		$response['cod_response'] = "406";
	      		$response['detalle'] = "Debe enviar campo de hora de inicio.";
	      	}
	      	elseif(!isset($datos['hora_fin'])){
	      		$response['cod_response'] = "406";
	      		$response['detalle'] = "Debe enviar campo de hora de fin.";
	      	}
	      	elseif(!isset($datos['id_sala'])){
	      		$response['cod_response'] = "406";
	      		$response['detalle'] = "Debe enviar campo de sala.";
	      	}
	      	else{
		      	$fecha_auditoria = new MongoDB\BSON\UTCDateTime();

		      	$fecha_hora_inicio = $datos['fecha']." ".$datos['hora_inicio'].":00";
	        	$fecha_hora_fin = $datos['fecha']." ".$datos['hora_fin'].":00";
	        	$fecha_ini = strtotime($fecha_hora_inicio);
	        	$fecha_fini = strtotime($fecha_hora_fin);
	        	$fecha_inicial_validacion = strtotime($datos['hora_inicio'].":00");
	        	$fecha_final_validacion = strtotime($datos['hora_fin'].":00");
	        	$fecha = strtotime($datos['fecha'])*1000;
	        	$id_membresia = $usuario['_id']->{'$id'};
	       	 	$numero_renovacion = $this->CI->Reservaciones_model->consultarNrenovacion($id_membresia);

	       	 	$id_sala = $datos['id_sala'];
	       	 	$sala_encontrada = $this->CI->Reservaciones_model->buscarSalas($id_sala);

	       	 	$precio = str_replace(',', '',$sala_encontrada[0]["monto"]);

	       	 	$numero_reservacion = $this->CI->Reservaciones_model->obtener_numero_reservacion();

	       	 	if($fecha_hora_inicio>$fecha_hora_fin){
		            $response['cod_response'] = "no_valid_fecha_reservacion";
		      		$response['detalle'] = "La hora fin no puede ser mayor a la hota inicio.";
		        }
		        else{
		        	$data = array(
		                          'n_reservaciones' => $numero_reservacion,
		                          'id_membresia' => $id_membresia,
		                          'numero_renovacion'=>$numero_renovacion,
		                          'id_servicio_sala' => $id_sala,
		                          'hora_inicio' =>  $fecha_ini,
		                          'hora_fin' =>  $fecha_fini,
		                          'fecha_inicial_validacion' => $fecha_inicial_validacion,
		                          'fecha_final_validacion' => $fecha_final_validacion,
		                          'hora_ingreso' =>  '',
		                          'hora_salida' =>  'Sin salir',
		                          'precio'=>$precio,
		                          'fecha' => $this->CI->mongo_db->date($fecha),
		                          'cancelacion' => '',
		                          'motivo_cancelacion' => '',
		                          'observacion' => '',
		                          'condicion' => 'RESERVADA',
		                          'status' => true,
		                          'eliminado' => false,
		                          'auditoria' => [array(
		                                                    "cod_user" => "5d03d5d9e31dd963174e9b02",
		                                                    "nomuser" => "SISTEMA",
		                                                    "fecha" => $fecha_auditoria,
		                                                    "accion" => "Nuevo registro reservaciones",
		                                                    "operacion" => ""
		                                                )]
		            );
		        	ob_end_clean();
		          	$this->CI->Reservaciones_model->registrar_reservaciones($data);
		          	$salida1_temp = ob_get_contents();
		          	ob_end_clean();
		          	if (strpos($salida1_temp, 'exitosamente') !== false) {
		          		$response['cod_response'] = "200";
	      				$response['detalle'] = "Exitosa.";
	      				$response['data'] = $data;
		          	}
		          	else{
		          		$response['cod_response'] = "406";
	      				$response['detalle'] = str_replace("<span>", "", str_replace("</span>", "", $salida1_temp));
	      				ob_end_clean();

			            $datos_busqueda = $this->CI->Reservaciones_model->listado_reservaciones_todas();
				      	if(count($datos_busqueda) > 0){
				      		$lista_pertecene = [];
				      		foreach ($datos_busqueda as $key => $value) {

					            $fecha_hora_inicio = $value["fecha"]->toDateTime();

					            $fecha_hora_ini = $fecha_hora_inicio->format('Y-m-d');

				      			if($datos['fecha'] == $fecha_hora_ini){
						            $lista_pertecene[] = $value;
				      			}
				      		}
				      		$response['data'] = $lista_pertecene;
				      	}
	      				ob_end_clean();
		          	}
		        }
		    }

		}

		return $response;
	}

}