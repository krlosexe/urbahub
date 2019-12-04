<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MiCorreo extends CI_Controller
{
	private $operaciones;
	function __construct()
	{
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('MiCorreo_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url()."admin");
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('MiCorreo', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    //Migración Mongo DB
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));
    //---
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('MiCorreo');
    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('configuracion/MiCorreo/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function buscar_mi_correo()
  {
    $datos=$this->MiCorreo_model->buscar_mi_correo();
    echo json_encode($datos);
  }

  public function actualizar_mi_correo()
  {
    $this->reglas_mi_correo();
    $this->mensajes_reglas_mi_correo();
    if($this->form_validation->run() == true){
     /* $dt = new DateTime(date('Ym-d'), new DateTimeZone('UTC'));
      $ts = $dt->getTimestamp(); 
      $today = new MongoDate($ts); 
      $fecha = new MongoDB\BSON\UTCDateTime((new DateTime($today))->getTimestamp()*1000); */
      $fecha = new MongoDB\BSON\UTCDateTime();
      $data=array(
        'smtp_auto' => trim($this->input->post('smtp_auto')),
        'nombre' => trim($this->input->post('nombre')),
        'correo' => trim($this->input->post('correo')),
        'servidor_smtp' => trim($this->input->post('servidor_smtp')),
        'puerto' => trim($this->input->post('puerto')),
        'usuario' => trim($this->input->post('usuario')),
        'clave' => trim($this->input->post('clave')),
        'status' => true,
        'eliminado' => false
      );
      $this->MiCorreo_model->actualizar_mi_correo($this->input->post('id_mi_correo'), $data);
      echo json_encode("<span>Correo editado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_mi_correo()
  {
    $this->form_validation->set_rules('correo','Correo a Mostrar','required');
    $this->form_validation->set_rules('nombre','Nombre a Mostrar','required');
    $this->form_validation->set_rules('servidor_smtp','Servidor SMTP','required');
    $this->form_validation->set_rules('puerto','Puerto','required|max_length[4]');
    $this->form_validation->set_rules('smtp_auto','SMTP Server','required|max_length[1]');
  }

  public function mensajes_reglas_mi_correo(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
  }

}//Fin class Bancos