<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Inmobiliarias extends CI_Controller
{
	private $operaciones;
	function __construct()
	{
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Inmobiliarias_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url()."admin");
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Inmobiliarias', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Inmobiliarias');
    $datos['coordinadores'] = $this->Inmobiliarias_model->coordinadores();
    $datos['localidades'] = $this->Inmobiliarias_model->localidades();
    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/Inmobiliarias/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_inmobiliarias()
  {
    $listado=$this->Inmobiliarias_model->listado_inmobiliarias();
    echo json_encode($listado);
  }

  public function registrar_inmobiliaria()
  {
    $this->reglas_inmobiliarias('insert');
    $this->mensajes_reglas_inmobiliarias();
    if($this->form_validation->run() == true){
      $data=array(
        'codigo' => trim(mb_strtoupper($this->input->post('codigo'), 'UTF-8')),
        'nombre' => trim(mb_strtoupper($this->input->post('nombre'), 'UTF-8')),
        'id_coordinador' => $this->input->post('coordinador'),
        'localidad' => $this->input->post('localidad'),
      );
      $this->Inmobiliarias_model->registrar_inmobiliaria($data);
      echo json_encode("<span>La Inmobiliaria se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_inmobiliaria()
  {
    $this->reglas_inmobiliarias('update');
    $this->mensajes_reglas_inmobiliarias();
    if($this->form_validation->run() == true){
      $data=array(
        'codigo' => trim(mb_strtoupper($this->input->post('codigo'), 'UTF-8')),
        'nombre' => trim(mb_strtoupper($this->input->post('nombre'), 'UTF-8')),
        'id_coordinador' => $this->input->post('coordinador'),
        'localidad' => $this->input->post('localidad'),
      );
      $inmobiliaria_verificado=$this->Inmobiliarias_model->verificar_inmobiliaria($data); //busca si el nombre del banco esta registrado en la base de datos
      if(count($inmobiliaria_verificado)>0){
        // si es mayor a cero, se verifica si el id recibido del formulario es igual al id que se verifico
        if($inmobiliaria_verificado[0]['id_inmobiliaria']==$this->input->post('id_inmobiliaria')){
          //si son iguales, quiere decir que es el mismo registro
          $this->Inmobiliarias_model->actualizar_inmobiliaria($this->input->post('id_inmobiliaria'), $data);
          echo json_encode("<span>La inmobiliaria se ha editado exitosamente!</span>"); // envio de mensaje exitoso
        }else{
          //si son diferentes, quiere decir que ya el nombre del banco se encuentra en uso por otro registro
          echo "<span>El código de la inmobiliaria ingresado ya se encuentra en uso!</span>";
        }
      }else{
        // si conteo del array es igual a 0, se actualiza el registro
        $this->Inmobiliarias_model->actualizar_inmobiliaria($this->input->post('id_inmobiliaria'), $data);
        echo json_encode("<span>a inmobiliaria se ha editado exitosamente!</span>"); // envio de mensaje exitoso
      }
      
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_inmobiliarias($method)
  {
    if($method=="insert"){
      $this->form_validation->set_rules('codigo','Código','required|max_length[50]|is_unique[inmobiliarias.codigo]');
      $this->form_validation->set_rules('nombre','Nombre','required|max_length[255]');
      $this->form_validation->set_rules('coordinador','Coordinador','required');
      $this->form_validation->set_rules('localidad','Localidad','required|max_length[255]');
    }else if($method=="update"){
      $this->form_validation->set_rules('codigo','Código','required|max_length[50]');
      $this->form_validation->set_rules('nombre','Nombre','required|max_length[255]');
      $this->form_validation->set_rules('coordinador','Coordinador','required');
      $this->form_validation->set_rules('localidad','Localidad','required|max_length[255]');
    }
  }

  public function mensajes_reglas_inmobiliarias(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('min_length', 'El Campo %s debe tener un Mínimo de %d Caracteres');
    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo numeros enteros');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
  }

  public function eliminar_inmobiliaria()
  {
    $this->Inmobiliarias_model->eliminar_inmobiliaria($this->input->post('id'));
  }

  public function status_inmobiliaria()
  {
    $this->Inmobiliarias_model->status_inmobiliaria($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_inmobiliaria()
  {
    $this->Inmobiliarias_model->eliminar_multiple_inmobiliaria($this->input->post('id'));
  }

  public function status_multiple_inmobiliaria()
  {
    $this->Inmobiliarias_model->status_multiple_inmobiliaria($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos