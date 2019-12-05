<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends CI_Controller {
	function __construct(){
	    parent::__construct();
	    //$this->load->database();
	    $this->load->library('session');
	    $this->load->model('Menu_model');
	    $this->load->model('Proyectos_model');
	    $this->load->model('Productos_model');
	    $this->load->library('form_validation');
	    if (!$this->session->userdata("login")) {
	      redirect(base_url()."admin");
	    }
  	}

  	public function prueva()
  	{
  		$stslval = $this->Productos_model->listado_valores_sts();
		foreach ($stslval as $sts) {
			if ($sts->nombre_lista_valor == "DISPONIBLE") {
				$sts_producto = $sts->codlval;
				break;
			}
		}
		echo $sts_producto;
  	}
	public function index()
	{
		$datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Productos', $this->session->userdata('id_rol'));

	    $data['modulos']                 = $this->Menu_model->modulos();
	    $data['vistas']                  = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
	    $datos['breadcrumbs']            = $this->Menu_model->breadcrumbs('Productos');
	    $datos['proyectos']              = $this->Proyectos_model->getproyectosactivos();
	    $datos['clasificaciones']        = $this->Proyectos_model->buscarClasificacionesAll();

	    foreach ($data['modulos'] as $modulo) {
        foreach ($data['vistas'] as $vista) {
            if($modulo->id_modulo_vista == $vista->id_modulo_vista){
              $data["modulo_user"][] = $modulo->id_modulo_vista;
            }
        }
      }


      $ids = array_unique($data['modulo_user']);
      foreach ($ids as $value) {
        $data['modulos_enconctrados'][] = $this->Menu_model->modulosbyid($value);
      } 

     

      $oneDim = array();
    foreach($data['modulos_enconctrados'] as $i) {
      $oneDim[] = $i[0];
    }

    $data['modulos_vistas'] = $oneDim;
    $this->load->view('cpanel/header');
	    $this->load->view('cpanel/menu', $data);
	    $this->load->view('catalogo/Productos/index', $datos);
	    $this->load->view('cpanel/footer');
	}


	public function registrar_producto()
	{
		$imagen = $this->input->post('plano');
		if(!empty($imagen))
    {
      if(file_exists(sys_get_temp_dir().'/'.$imagen))
      {
        rename(sys_get_temp_dir().'/'.$imagen,
                                'assets/cpanel/Productos/planos/'.$imagen
                              );
                              //unlink(sys_get_temp_dir().'/'.$imagen);                        
      }
    }
		$this->reglas_productos('insert');
   	    $this->mensajes_reglas_productos();

   	    if ($this->input->post('cod_proyecto_clasificacion') == "") {
   	    	$codigo_clasificacion = null;
   	    }else{
   	    	$codigo_clasificacion = $this->input->post('cod_proyecto_clasificacion');
   	    }

   	    $precio      = $this->input->post('precio');
   	    $superficie  = $this->input->post('superficie');
   	    $preio_venta = $precio * $superficie;

   	    $stslval = $this->Productos_model->listado_valores_sts();
		foreach ($stslval as $sts) {
			if ($sts->nombre_lista_valor == "DISPONIBLE") {
				$sts_producto = $sts->codlval;
				break;
			}
		}

		if (isset($sts_producto)) {
			if ($this->form_validation->run() == true){
		      $data = array(
			        'descripcion'                => trim(mb_strtoupper($this->input->post('descripcion'), 'UTF-8')),
			        'cod_proyecto'               => trim(mb_strtoupper($this->input->post('cod_proyecto'), 'UTF-8')),
			        'cod_proyecto_clasificacion' => $codigo_clasificacion,
			        'etapas'                     => trim(mb_strtoupper($this->input->post('etapas'), 'UTF-8')),
			        'lote_anterior'              => trim(mb_strtoupper($this->input->post('lote_anterior'), 'UTF-8')),
			        'lote_nuevo'                 => trim(mb_strtoupper($this->input->post('lote_nuevo'), 'UTF-8')),
			        'superficie'                 => trim(mb_strtoupper($this->input->post('superficie'), 'UTF-8')),
			        'precio_m2'                  => $preio_venta,
			        'observacion'                => trim(mb_strtoupper($this->input->post('observacion'), 'UTF-8')),
			        'plano'                      => $imagen,
			        'STSPRODUCTO'                => $sts_producto
		      );

		      if ($this->Productos_model->registrar_producto($data)) {
		      	 echo json_encode("<span>El producto se ha registrado exitosamente!</span>");
		      	    // envio de mensaje exitoso
		      }else{
		      	echo json_encode("<span>A ocurrido un error!</span>");
		      }
		    }else{
		      // enviar los errores
		      echo validation_errors();
		    }
		}else{
			$datos = array('success' => false,

		                    'message' => 'Estatus no esta definido en la lista de Valores');

	      	echo json_encode($datos);
		}

   	    
	}



	public function actualizar_productos()
	{
		$this->reglas_productos('update');
   	    $this->mensajes_reglas_productos();
   	    $id = $this->input->post('id_producto');
   	    if ($this->form_validation->run() == true){

	      if ($this->input->post('cod_proyecto_clasificacion') == "") {
   	    		$codigo_clasificacion = null;
	   	  }else{
	   	    	$codigo_clasificacion = $this->input->post('cod_proyecto_clasificacion');
	   	  }

	   	  $imagen 	  	= $this->input->post('plano');
	   	  	if(!empty($imagen))
    {
      if(file_exists(sys_get_temp_dir().'/'.$imagen))
      {
        rename(sys_get_temp_dir().'/'.$imagen,
                                'assets/cpanel/Productos/planos/'.$imagen
                              );
                              //unlink(sys_get_temp_dir().'/'.$imagen);                        
      }
    }
		
	   	  $precio     	= $this->input->post('precio');
   	      $superficie 	= $this->input->post('superficie');
   	      $preio_venta	= $precio * $superficie;
	   	  if ($this->form_validation->run() == true){
		    $data = array(
			    'descripcion'                => trim(mb_strtoupper($this->input->post('descripcion'), 'UTF-8')),
			    'cod_proyecto'               => trim(mb_strtoupper($this->input->post('cod_proyecto'), 'UTF-8')),
			    'cod_proyecto_clasificacion' => $codigo_clasificacion,
			    'lote_anterior'              => trim(mb_strtoupper($this->input->post('lote_anterior'), 'UTF-8')),
			    'lote_nuevo'                 => trim(mb_strtoupper($this->input->post('lote_nuevo'), 'UTF-8')),
			    'etapas'                     => trim(mb_strtoupper($this->input->post('etapas'), 'UTF-8')),
			    'superficie'                 => trim(mb_strtoupper($this->input->post('superficie'), 'UTF-8')),
			    'precio_m2'                  => $preio_venta,
			    'observacion'                => trim(mb_strtoupper($this->input->post('observacion'), 'UTF-8')),
		    );
		  //  if ($imagen != "") {
				$data["plano"] = $imagen;
			//}

	      if ($this->Productos_model->actualizar_producto($data, $id)) {
	      	 echo json_encode("<span>El producto se ha actualizado exitosamente!</span>");
	      	    // envio de mensaje exitoso
	      }else{
	      	 echo json_encode("<span>Ha ocurrido un error!</span>");
	      }
	    }else{
		      // enviar los errores
		      echo validation_errors();
		    }
		}
	}





	public function listado_productos()
	{
		$listado = $this->Productos_model->listar();
	    echo json_encode($listado);
	}

	public function getproducto($producto)
	{
		$producto = $this->Productos_model->getproducto($producto);
	    echo json_encode($producto);
	}


	public function getproductosCorrida($proyecto, $etapas, $zonas)
	{
		$producto = $this->Productos_model->getproductosCorrida($proyecto, $etapas, $zonas);
	    echo json_encode($producto);
	}


	public function eliminar_producto()
	{
		$id_producto = $this->input->post('id');
		$this->Productos_model->eliminar_producto($id_producto);
	}

	public function eliminar_multiple_productos()
	{
	    $this->Productos_model->eliminar_multiple_productos($this->input->post('id'));
	}


	public function status_producto()
	{
	    $this->Productos_model->status_producto($this->input->post('id'), $this->input->post('status'));
	    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
	}

	public function status_multiple_productos()
	{
	    $this->Productos_model->status_multiple_productos($this->input->post('id'), $this->input->post('status'));
	    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
	}




	public function reglas_productos($method){
	    if($method=="insert"){
	      $this->form_validation->set_rules('descripcion','descripcion','required');
	      $this->form_validation->set_rules('cod_proyecto','proyecto','required');
	      
	     // $this->form_validation->set_rules('lote_anterior','lote anterior','required');
	     // $this->form_validation->set_rules('lote_nuevo','lote nuevo','required');
	      $this->form_validation->set_rules('superficie','superficie','required');
	      $this->form_validation->set_rules('precio_m2','precio','required');
	    }else if($method=="update"){
	      $this->form_validation->set_rules('descripcion','descripcion','required');
	      $this->form_validation->set_rules('cod_proyecto','proyecto','required');
	     
	     // $this->form_validation->set_rules('lote_anterior','lote anterior','required');
	      //$this->form_validation->set_rules('lote_nuevo','lote nuevo','required');
	      $this->form_validation->set_rules('superficie','superficie','required');
	      $this->form_validation->set_rules('precio_m2','precio','required');
	    }
    }

	public function mensajes_reglas_productos(){
	    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
	    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
	    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo numeros enteros');
	    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
	    $this->form_validation->set_message('matches', 'El valor ingresado en el campo %s no coincide');
	}

}

/* End of file Productos.php */
/* Location: ./application/controllers/database/Productos.php */
