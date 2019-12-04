<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedores extends CI_Controller {
	private $operaciones;
	function __construct(){
	    parent::__construct();
	    $this->load->database();
	    $this->load->library('session');
	    $this->load->model('Menu_model');
	    $this->load->model('Comision_model');
	    $this->load->model('Proyectos_model');
	    $this->load->model('vendedores_model');
	    $this->load->model('ClientePagador_model');
	    $this->load->library('form_validation');
	    //--
	    $this->load->helper('consumir_rest');
	    $this->load->helper('organizar_sepomex');
	    $this->load->helper('array_push_assoc');
	    //--
	    if (!$this->session->userdata("login")) {
	      redirect(base_url()."admin");
	    }
  	}


	public function index()
	{
		
		//--Consumiendo servicios ag2
		$tipos_vendedores = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'TIPOVENDEDOR'));
    	$datos['tipos_vendedores'] = $tipos_vendedores->data;
    	$clasificaciones = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'CLASIFCAPROY'));
    	$datos["clasificaciones"] = $clasificaciones->data; 
		//--
		$datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Vendedores', $this->session->userdata('id_rol'));


	    $data['modulos']              = $this->Menu_model->modulos();
	    //$data['vistas']               = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
	    //--Migracion Mongo DB
	    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol')); 
	    $datos['breadcrumbs']         = $this->Menu_model->breadcrumbs('Vendedores');
	    //$datos['tipos_vendedores']    = $this->Comision_model->tipos_venderores();
	    $datos['directores']          = $this->Proyectos_model->directores();
	    $datos['inmobiliarias']       = $this->Proyectos_model->inmobiliarias();
	    //$datos['clientes']            = $this->ClientePagador_model->listarClientePagador();
	    //---
	    $listado_clientes = $this->ClientePagador_model->listarClientePagador();
	    //Recorro el arreglo del cliente para obtener los valores de codigo postal y lista de valores
	    foreach ($listado_clientes as $key => $value) {
	    	//$arreglo_data = get_object_vars($value);
	    	$arreglo_data = $value;
	    	//1-Para obtener giro mercantil segun lista de valores
	    	if(($value["giro_mercantil"]!=NULL) && ($value["giro_mercantil"]!=" ")){
	    		$arreglo_giro_mercantil = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["giro_mercantil"]));
	    		$arreglo_data["giro_merca_desc"] = is_array($arreglo_giro_mercantil->data)?$arreglo_giro_mercantil->data[0]->nombre_lista_valor:(is_object($arreglo_giro_mercantil->data)?$arreglo_giro_mercantil->data->nombre_lista_valor:'');
	    	}else{
	    		$arreglo_data["giro_merca_desc"] = "";
	    	}
	    	//2-Para obtener nacionalidad
			if($value["nacionalidad_datos_personales"]!=NULL){
	    		$arreglo_nac = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["nacionalidad_datos_personales"]));
	    		$arreglo_data["pais_nacionalidad"] = is_array($arreglo_nac->data)?$arreglo_nac->data[0]->nombre_lista_valor:(is_object($arreglo_nac->data)?$arreglo_nac->data->nombre_lista_valor:'');
	    	}else{
	    		$arreglo_data["pais_nacionalidad"] = "";
	    	}
	    	//3-Para obtener pais de origen
	    	if($value["pais_cliente"]!=NULL){
	    		$arreglo_pais = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["pais_cliente"]));
	    		$arreglo_data["pais_origen"] = is_array($arreglo_pais->data)?$arreglo_pais->data[0]->nombre_lista_valor:(is_object($arreglo_pais->data)?$arreglo_pais->data->nombre_lista_valor:'');
	    	}else{
	    		$arreglo_data["pais_origen"] = "";
	    	}
			//4-Para obtener actividad ecnómica
	    	if(($value["actividad_e_cliente"]!=NULL)&&($value["actividad_e_cliente"]!=0)){
	    		$value["actividad_e_cliente"] = (integer)$value["actividad_e_cliente"];
	    		$arreglo_actividad = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["actividad_e_cliente"]));
	    		$arreglo_data["actividad_economica"] = is_array($arreglo_actividad->data)?$arreglo_actividad->data[0]->nombre_lista_valor:(is_object($arreglo_actividad->data)?$arreglo_actividad->data->nombre_lista_valor:'');
	    	}else{
	    		$arreglo_data["actividad_economica"] = "";
	    	}
	    	//5-Para obtener codigo postal
	    	if(($value["id_codigo_postal"]!=NULL)&&($value["id_codigo_postal"]!=0)){
	    		$arreglo_sepomex = consumir_rest('Sepomex','consultar', array('id_codigo_postal'=>$value["id_codigo_postal"]));
	    		$arreglo_data["sepomex"] = $arreglo_sepomex->data;
	    	}else{
	    		$arreglo_data["sepomex"] = "";
	    	}
	    	$datos['clientes'][] = (object)$arreglo_data;
      	}	
      	//var_dump($datos['clientes']);die("");
	    //---
	    $datos['proyectos'] = $this->Proyectos_model->getproyectosactivos();
	    //--Me quede por aqui....
	    //$datos['clasificaciones']     = $this->Proyectos_model->clasificaciones();

	    $users_vendedor = $this->vendedores_model->getusuariosvendedores();

	    foreach ($users_vendedor as $value) {
	    	$vendedor = $this->vendedores_model->getvendedor($value->id_usuario);
	    	if (!$vendedor) {
	    	   $datos['usuarios_vendedores'][] = $value;
	    	}
	    }

	    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    	$data['modulos_vistas'] = $this->operaciones;
    	$this->load->view('cpanel/header');
	    $this->load->view('cpanel/menu', $data);
	    $this->load->view('catalogo/Vendedores/index', $datos);
	    $this->load->view('cpanel/footer');
	}


	public function listado_vendedores()
	{
	    $listado = $this->vendedores_model->listar();
		foreach ($listado as $key => $value) {
	      $arreglo_tipo_vendedor = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value->tipo_vendedor));
	      //Transformo el la fila de  en un array
	      $arreglo_data = get_object_vars($value);
	      $arreglo_data["tipoVendedor"] = is_array($arreglo_tipo_vendedor->data)?$arreglo_tipo_vendedor->data[0]->nombre_lista_valor:(is_object($arreglo_tipo_vendedor->data)?$arreglo_tipo_vendedor->data->nombre_lista_valor:'');	      
	      $listado2[] = $arreglo_data;
	    }
	    echo json_encode($listado2);
	}


	public function listado_vendedor($id)
	{
	    $listado = $this->vendedores_model->getusuariosvendedor($id);
	    echo json_encode($listado);
	}

	



	public function registrar_vendedor()
	{
		$this->reglas_vendedores('insert',$id="", $rfc="");
   	    $this->mensajes_reglas_vendedores();


   	    if ($this->form_validation->run() == true){
	      $data = array(
		        'id_usuario'      => trim(mb_strtoupper($this->input->post('id_usuario'), 'UTF-8')),
		        'tipo_vendedor'   => trim(mb_strtoupper($this->input->post('tipo_vendedor'), 'UTF-8')),
		        'rfc'             => trim($this->input->post('rfc'))
	      );

	      if ($this->vendedores_model->registrar_vendedor($data, $this->input->post('proyectos'), $this->input->post('inmobiliarias'), $this->input->post('proyectos_clientes'), $this->input->post('clientes'))) {
	      	 echo json_encode("<span>El vendedor se ha registrado exitosamente!</span>");
	      	    // envio de mensaje exitoso
	      }else{
	      	echo json_encode("<span>A ocurrido un error!</span>");
	      }
	    }else{
	      // enviar los errores
	      echo validation_errors();
	    }
	}


	public function actualizar_vendedor()
	{
   	    $id = $this->input->post('id_vendedor');
		$this->reglas_vendedores('update',$id, $this->input->post('rfc'));
   	    $this->mensajes_reglas_vendedores();
   	    if ($this->form_validation->run() == true){

	      $data = array(
		        'tipo_vendedor'   => trim(mb_strtoupper($this->input->post('tipo_vendedor'), 'UTF-8')),
		        'rfc'             => trim(mb_strtoupper($this->input->post('rfc'), 'UTF-8'))
	      );

	      if ($this->vendedores_model->actualizar_vendedor($data, $id, $this->input->post('proyectos'),$this->input->post('inmobiliarias'), $this->input->post('proyectos_clientes'), $this->input->post('clientes'))) {
	      	 echo json_encode("<span>El vendedor se ha actualizado exitosamente!</span>");
	      	    // envio de mensaje exitoso
	      }else{
	      	 echo json_encode("<span>A ocurrido un error!</span>");
	      }
	    }else{
	      // enviar los errores
	      echo validation_errors();
	    }
	}



	  public function buscarInmobiliarias()
	  {
	    $inmobiliarias = $this->vendedores_model->buscarInmobiliarias($this->input->post('vendedor'));
	    echo json_encode($inmobiliarias);
	  }


	   public function buscarClientes()
	  {
	    $clientes   = $this->vendedores_model->buscarclientes($this->input->post('vendedor'));
	    echo json_encode($clientes);
	  }


	public function eliminar_vendedor()
	{
		$this->vendedores_model->eliminar_vendedor($this->input->post('id'));
	}



	public function status_vendedor()
	{
	    $this->vendedores_model->status_vendedor($this->input->post('id'), $this->input->post('status'));
	    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
	}


	public function status_vendedor_proyecto()
	{
	    $this->vendedores_model->status_vendedor_proyecto($this->input->post('id'), $this->input->post('status'));
	    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
	}



	public function status_cartera_cliente()
	{
		$this->vendedores_model->status_cartera_cliente($this->input->post('id'), $this->input->post('status'));
	    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
	}

	



	public function eliminar_multiple_vendedores()
  {
    $this->vendedores_model->eliminar_multiple_vendedores($this->input->post('id'));
  }


  public function status_multiple_vendedores()
  {
    $this->vendedores_model->status_multiple_vendedores($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }


  public function eliminar_inmobiliaria_vendedor()
  {
    $this->vendedores_model->eliminar_inmobiliaria_vendedor($this->input->post('id'));
  }

  public function eliminar_cartera_cliente()
  {
    $this->vendedores_model->eliminar_cartera_cliente($this->input->post('id'));
  }







	 public function reglas_vendedores($method, $id="", $rfc=""){
		    if($method=="insert"){
		      $this->form_validation->set_rules('id_usuario','Usuario','required|is_unique[vendedores.id_usuario]');
		      $this->form_validation->set_rules('tipo_vendedor','Tipo vendedor','required');
		      $this->form_validation->set_rules('rfc','RFC vendedor','required|is_unique[vendedores.rfc]');
		    }else if($method=="update"){

		      $rfc = $this->vendedores_model->getrfc($id, $rfc);

		      if ($rfc) {
		      	$unique="";
		      }else{
				$unique = "|is_unique[vendedores.rfc]";
			  }
		      $this->form_validation->set_rules('tipo_vendedor','Tipo vendedor','required');
		      $this->form_validation->set_rules('rfc','RFC vendedor','required'.$unique);
		    }
	  }

	  public function mensajes_reglas_vendedores(){
	    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
	    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
	    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo numeros enteros');
	    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
	    $this->form_validation->set_message('matches', 'El valor ingresado en el campo %s no coincide');
	  }

}

/* End of file Vendedores.///php */
/* Location: ./application/controllers/Vendedores.php */
