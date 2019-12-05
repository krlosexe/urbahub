<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedores extends CI_Controller {
	private $operaciones;
	function __construct(){
	    parent::__construct();
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
	      redirect(base_url());
	    }
  	}


	public function index()
	{
		  $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Vendedores', $this->session->userdata('id_rol'));
			$data['modulos']              = $this->Menu_model->modulos();
	    $data['vistas']               = $this->Menu_model->vistas($this->session->userdata('id_rol'));
	    $datos['breadcrumbs']         = $this->Menu_model->breadcrumbs('Vendedores');
	    //Consumo servicio
	    /*$arreglo_tipo_vendedor = consumir_rest('ListaValores','listado_valores','TIPOVENDEDOR','');
    	$datos['tipos_vendedores'] = $arreglo_tipo_vendedor->data->array_list;*/

    	$lista_pagos = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'TIPOVENDEDOR'));
	    $datos['tipos_vendedores'] = $lista_pagos->data;
	    //--('tipolval', 'TIPOVENDEDOR')
	    //$datos['tipos_vendedores']    = $this->Comision_model->tipos_venderores();
	    $datos['directores']          = $this->Proyectos_model->directores();
	    //$datos['inmobiliarias']       = $this->Proyectos_model->inmobiliarias();
	    $datos['clientes']            = $this->ClientePagador_model->listarClientePagador();

	    $datos['proyectos']           = $this->Proyectos_model->getproyectosactivos();

	    //$datos['clasificaciones']     = $this->Proyectos_model->clasificaciones();
	    /*$arreglo_tipo_vendedor = consumir_rest('ListaValores','listado_valores','CLASIFCAPROY','');

    	$datos['clasificaciones'] = $arreglo_tipo_vendedor->data->array_list;*/
    	$datos['clasificaciones'] = "";
	    $users_vendedor = $this->vendedores_model->getusuariosvendedores();
	    
	    foreach ($users_vendedor as $value) {
	    	$vendedor = $this->vendedores_model->getvendedor($value["id_usuario"]);
	    	if (count($vendedor)==0) {
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

	/*
	*	Listado Vendedores
	*/
	public function listado_vendedores(){
	    $listado = $this->vendedores_model->listar();
	    $listado2 = array();
	    foreach ($listado as $key => $value) {
    		$valores = $value;
	    	/*$arreglo_tipo_vendedor = consumir_rest('ListaValores','listado_valores','',$value["tipo_vendedor"]);
    		$valores["tipoVendedor"] = $tipoVendedor[0]->descriplval;
    		$tipoVendedor = $arreglo_tipo_vendedor->data->array_list;*/

    		//---
    		$lista_tipo_vendedor = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["tipo_vendedor"]));
		    $tipoVendedor = $lista_tipo_vendedor->data;
		    $valores["tipoVendedor"] = $tipoVendedor->nombre_lista_valor;
    		//---
    		$listado2[] = $valores;
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
		$fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

		$this->reglas_vendedores('insert',$id="", $rfc="");

   	    $this->mensajes_reglas_vendedores();

   	    //---
   	    #Validar que ese usuario no este usado en otro vendedor
   	    $this->vendedores_model->verificarExisteUsuario($this->input->post('id_usuario'));

   	    #Validar que el rfc no este usado en otro vendedor
   	     $this->vendedores_model->verificarExisteRfc(trim($this->input->post('rfc')));
   	    //---
   	    if ($this->form_validation->run() == true){
	      	
	      	$data = array(
		        'id_usuario'      => $this->input->post('id_usuario'),
		        'tipo_vendedor'   => trim(mb_strtoupper($this->input->post('tipo_vendedor'), 'UTF-8')),
		        'rfc'             => trim($this->input->post('rfc')),
		        'status'=>true,
                'eliminado'=>false,
                'auditoria' => [array(
                                          "cod_user" => $id_usuario,
                                          "nomuser" => $this->session->userdata('nombre'),
                                          "fecha" => $fecha,
                                          "accion" => "Nuevo registro",
                                          "operacion" => ""
                                      )]
	      	);
	      	/*
				if ($this->vendedores_model->registrar_vendedor($data, $this->input->post('proyectos'), $this->input->post('inmobiliarias'), $this->input->post('proyectos_clientes'), $this->input->post('clientes'))) 
	      	*/
			if ($this->vendedores_model->registrar_vendedor($data,'','',$this->input->post('proyectos_clientes'), $this->input->post('clientes'))) {
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

	      //if ($this->vendedores_model->actualizar_vendedor($data, $id, $this->input->post('proyectos'),$this->input->post('inmobiliarias'), $this->input->post('proyectos_clientes'), $this->input->post('clientes'))) {
	      if ($this->vendedores_model->actualizar_vendedor($data, $id, $this->input->post('clientes'))) {
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

	/*
	*	Buscar clientes
	*/
	public function buscarClientes(){

	    $clientes   = $this->vendedores_model->buscarclientes($this->input->post('vendedor'));
	    echo json_encode($clientes);
	}

	/*
	*	Eliminar vendedor
	*/
	public function eliminar_vendedor()
	{
		$this->vendedores_model->eliminar_vendedor($this->input->post('id'));
	}


	/*
	*	Status Vendedor
	*/
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




	/*
	*	Eliminar multiple vendedores
	*/
   	public function eliminar_multiple_vendedores(){
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
		      $this->form_validation->set_rules('id_usuario','Usuario','required');
		      //|is_unique[vendedores.id_usuario]
		      $this->form_validation->set_rules('tipo_vendedor','Tipo vendedor','required');
		      $this->form_validation->set_rules('rfc','RFC vendedor','required');
		      //|is_unique[vendedores.rfc]
		    }else if($method=="update"){
		      /*$rfc = $this->vendedores_model->getrfc($id, $rfc);
		      if ($rfc) {
		      	$unique="";
		      }else{
				$unique = "|is_unique[vendedores.rfc]";
			  }*/
			  $unique = "";
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
