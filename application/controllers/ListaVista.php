<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ListaVista extends CI_Controller
{
	private $operaciones;
	function __construct()
	{
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('ListaVista_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url()."admin");
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('ListaVista', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    //--Modificacion para Mongo DB
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));    
    $datos['modulos'] = $this->ListaVista_model->modulos();
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('ListaVista');
    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('perfiles/listaVista/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_listaVista()
  {
    $listado = $this->ListaVista_model->listado_listaVista();
    echo json_encode($listado);
  }

  public function contador_listaVista()
  {
    $id = new MongoDB\BSON\ObjectId($this->input->post('id'));

    $contador = $this->Menu_model->contador_listaVista($id);

    echo json_encode($contador);
  }

  public function registrar_lista_vista()
  {
    $this->reglas_lista_vista('insert');
    $this->mensajes_reglas_lista_vista();
    if ($this->form_validation->run() == true){
      $posicionar = array(
        'modulo' => $this->input->post('id_modulo_vista'),
        'posicion' => (integer)$this->input->post('posicion_lista_vista'),
      );
      $existe = $this->ListaVista_model->verificar_lista_vista(trim($this->input->post('nombre_lista_vista')));
      if(count($existe)==0){
          $this->ListaVista_model->posicionar_lista_vista_nueva($posicionar);
          if($this->input->post('visibilidad_lista_vista') == ""){
            $visible = 1;
          }else{
            $visible = $this->input->post('visibilidad_lista_vista');
          }
          $data = array(
            'nombre_lista_vista' => trim($this->input->post('nombre_lista_vista')),
            'descripcion_lista_vista' => trim($this->input->post('descripcion_lista_vista')),
            'url_lista_vista' => trim($this->input->post('url_lista_vista')),
            'id_modulo_vista' => new MongoDB\BSON\ObjectId($this->input->post('id_modulo_vista')),
            //'id_modulo_vista' => $this->input->post('id_modulo_vista'),
            'posicion_lista_vista' => (integer)$this->input->post('posicion_lista_vista'),
            'visibilidad_lista_vista' => $visible,
            'status'=>true,
            'eliminado'=>false
          );
          //--Consulto si ya existe una lista vista con esas características
          
                $this->ListaVista_model->registrar_lista_vista($data);
                echo json_encode("<span>Se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
      }else{
           echo ("<span>Ya existe una función con ese nombre!</span>"); // envio de mensaje exitoso
      }
    
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  //--

  public function actualizar_lista_vista()
  {
    $this->reglas_lista_vista('update');
    $this->mensajes_reglas_lista_vista();

    if($this->form_validation->run() == true){
      $lista_vista_verificado=$this->ListaVista_model->verificar_lista_vista($this->input->post('nombre_lista_vista')); //busca si el nombre esta registrado en la base de datos
      $posicionar = array(
        "moduloInicial" => $this->input->post('id_modulo_vista_hidden'),
        "moduloFinal" => $this->input->post('id_modulo_vista'),
        "posicionInicial" => intval($this->input->post('posicion_lista_vista_hidden')),
        "posicionFinal" => intval($this->input->post('posicion_lista_vista')),
      );
      if($this->input->post('visibilidad_lista_vista') == ""){
        $visible = 1;
      }else{
        $visible = $this->input->post('visibilidad_lista_vista');
      }
      $data = array(
        'nombre_lista_vista' => trim($this->input->post('nombre_lista_vista')),
        'descripcion_lista_vista' => trim($this->input->post('descripcion_lista_vista')),
        'url_lista_vista' => trim($this->input->post('url_lista_vista')),
        'id_modulo_vista' => new MongoDB\BSON\ObjectId($this->input->post('id_modulo_vista')),
        'posicion_lista_vista' => (integer)$this->input->post('posicion_lista_vista'),
        'visibilidad_lista_vista' => $visible,
      );
      if(count($lista_vista_verificado)>0){
        // si es mayor a cero, se verifica si el id recibido del formulario es igual al id que se verifico
        if($lista_vista_verificado[0]["_id"]->{'$id'}==$this->input->post('id_lista_vista')){
          //descomentar---------------------------------------------------------------------
          $this->ListaVista_model->posicionar_lista_vista_segun_modulo($posicionar);
          //--------------------------------------------------------------------------------
          //si son iguales, quiere decir que es el mismo registro
          //gs: Para actualizar la lista vista y registrar la auditoria--->
          //descomentar---------------------------------------------------------------------
          $this->ListaVista_model->actualizar_lista_vista($this->input->post('id_lista_vista'), $data);
          //---------------------------------------------------------------------------------
          if($posicionar['moduloInicial'] != $posicionar['moduloFinal']){
            $this->ListaVista_model->ordernar_lista_vista($posicionar['moduloInicial']);
          }
          echo json_encode("<span>Se ha editado exitosamente!</span>"); // envio de mensaje exitoso
        }else{
          //si son diferentes, quiere decir que ya el nombre del banco se encuentra en uso por otro registro
          echo "<span>El nombre de la lista vista ingresado ya se encuentra en uso!</span>";
        }
      }else{
        //--Para modificar con otro nombre....
        $this->ListaVista_model->posicionar_lista_vista_segun_modulo($posicionar);
        // si conteo del array es igual a 0, se actualiza el registro
        $this->ListaVista_model->actualizar_lista_vista($this->input->post('id_lista_vista'), $data);
        $this->ListaVista_model->posicionar_lista_vista_segun_modulo($posicionar);
        if($posicionar['moduloInicial'] != $posicionar['moduloFinal']){
            $this->ListaVista_model->ordernar_lista_vista($posicionar['moduloInicial']);
          }
        echo json_encode("<span>Se ha editado exitosamente!</span>"); // envio de mensaje exitoso
      }
      
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_lista_vista($method)
  {
    if($method == "insert"){
      #$this->form_validation->set_rules('nombre_lista_vista','Nombre de Lista Vista','required|is_unique[lista_vista.nombre_lista_vista]');
      $this->form_validation->set_rules('url_lista_vista','URL','required');
      $this->form_validation->set_rules('id_modulo_vista','Modulo','required');
      $this->form_validation->set_rules('posicion_lista_vista','Posición','required');
    }else if($method == "update"){
      $this->form_validation->set_rules('nombre_lista_vista','Nombre de Lista Vista','required');
      $this->form_validation->set_rules('url_lista_vista','URL','required');
      $this->form_validation->set_rules('id_modulo_vista','Modulo','required');
      $this->form_validation->set_rules('posicion_lista_vista','Posición','required');
    }
  }

  public function mensajes_reglas_lista_vista(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
  }

  public function eliminar_lista_vista()
  {
    $data = array(
      'id_lista_vista' => $this->input->post('id[0]'),
      'id_modulo_vista' => $this->input->post('id[1]'),
    );
    $this->ListaVista_model->eliminar_lista_vista($data);
  }

  public function status_lista_vista()
  {
    $this->ListaVista_model->status_lista_vista($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_lista_vista()
  {
    $this->ListaVista_model->eliminar_multiple_lista_vista($this->input->post('id'));
  }

  public function status_multiple_lista_vista()
  {
    $this->ListaVista_model->status_multiple_lista_vista($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos