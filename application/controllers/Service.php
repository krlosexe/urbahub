<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Service extends CI_Controller {

    function __construct(){
        parent::__construct();
        ob_start();
        $this->load->model('Membresia_model');
        $this->load->model('ClientePagador_model');
        $this->load->model('Usuarios_model');
        $this->load->model('Menu_model');
        $this->load->model('Paquetes_model');

        $this->load->helper('consumir_rest');
        $this->load->helper('organizar_sepomex');
        $this->load->helper('array_push_assoc');
        $this->load->helper('load_controller_new');

        define("SERVICE_IP_CLIENT", $this->get_client_ip());
    }

    public function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    private $var_errores = array(
    								"200" => true, //Exitoso
    								"203" => true, //Exitoso pero no por las vias correctas
    								"400" => false, //Error en el funcionamiento del sistema
    								"401" => false, //Error al autentificar
    								"403" => false, //Permisos denegados
    								"404" => false, //No encontrada
    								"405" => false, //Metodo no soportado o disponible
    								"406" => false, //Peticion no aceptada
    								"500" => false, //Error en servicio por parametros
    								"503" => false, //Mal funcionamiento del servicio
    								"520" => false, //No envio el json correctamente
    								"998" => false, //Error en metodo
    							);

	public function index()
	{
		$cod_response = "520";
		$data = null;
        $detalle = "";
        $request = $this->input->post("request");

        json_decode($request, true);

        if($request == "fecha"){
            $cod_response = "200";
            $data = date("Y-m-d H:i:s");
        }
		elseif(json_last_error() == JSON_ERROR_NONE){
			$request = json_decode($request, true);
			$controlador = isset($request['controlador'])?$request['controlador']:"Acceso";
			$metodo = isset($request['metodo'])?$request['metodo']:"index";
			$array_request = isset($request['array_request'])?$request['array_request']:null;
			$retorno = load_controller_new($controlador, $metodo, $array_request);
			$cod_response = $retorno['cod_response'];
			$data = $retorno['data'];
			$detalle = $retorno['detalle'];
		}

		//Consulto los mensajes del sistema
		$res_error = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>"COD_SERVICIO", 'cod_static_lista_valor'=>$cod_response));

		if(count($res_error->data) > 0){
            $res_error = $res_error->data;
			$res_error = $res_error[0];
			$error = $res_error->nombre_lista_valor;
        	$cod_error = $res_error->id_lista_valor;
		}
		else{
			$error = "Ocurrió un error al cargar el mensaje [".$cod_response."]";
			$cod_response = "400";
			$cod_error = "0";
		}

		$mensaje = array(
                        'header' => 
                            array(
                                'result' => isset($this->var_errores[$cod_response])?$this->var_errores[$cod_response]:false, 
                                'error' => $error,
                                'cod_error' => $cod_error,
                                'cod_response' => $cod_response,
                                'sys_detail' => $detalle,
                            ),
                        'data' => 
                            $data,
                    );
        ob_end_clean();
        header('Content-type:application/json;charset=utf-8');
        $resultado_json = json_encode($mensaje);
        
        echo $resultado_json;
	}

}
