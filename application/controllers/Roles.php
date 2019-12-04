<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Roles extends CI_Controller
{
	private $operaciones;
	function __construct()
	{
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Roles_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url()."admin");
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Roles', $this->session->userdata('id_rol'));

    $data['modulos'] = $this->Menu_model->modulos();
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    //--Modificacion para Mongo DB
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));    
    //--
    $datos['modulos'] = $this->Roles_model->modulos();

    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Roles');

    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']); 

    $data['modulos_vistas'] = $this->operaciones;

    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('perfiles/Roles/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_roles()
  {
    $listado = $this->Roles_model->listado_roles();
    echo json_encode($listado);
  }

  public function buscarListaVista()
  {
    $listaVista = $this->Roles_model->buscarListaVista($this->input->post('id_modulo'));
    echo json_encode($listaVista);
  }

  public function registrar_rol()
  {
    $this->reglas_roles();
    $this->mensajes_reglas_roles();
    
    $fecha = new MongoDB\BSON\UTCDateTime();
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
    //-------------------------------------------------------------------------------
    $nombres = trim(mb_strtoupper($this->input->post('nombre_rol'), 'UTF-8'));
    $descripcion_rol = trim(mb_strtoupper($this->input->post('descripcion_rol'), 'UTF-8'));
    $existe = $this->Roles_model->verificar_existe_roles($nombres,$descripcion_rol,'');
    if($existe>0){
      echo "<span>Ya existe un rol con esas características</span>";die('');
    }
    //-------------------------------------------------------------------------------
    if($this->form_validation->run() == true){
      $data=array(
        'nombre_rol' => trim(mb_strtoupper($this->input->post('nombre_rol'), 'UTF-8')),
        'descripcion_rol' => trim(mb_strtoupper($this->input->post('descripcion_rol'), 'UTF-8')),
        'editable_rol' => 0,
        'status' => true,
        'eliminado' => false,
        'auditoria' => [array(
                                  "cod_user" => $id_usuario,
                                  "nomuser" => $this->session->userdata('nombre'),
                                  "fecha" => $fecha,
                                  "accion" => "Nuevo registro rol",
                                  "operacion" => ""
                              )]
      );

      $this->Roles_model->registrar_rol($data, $this->input->post('permisos'));
      echo json_encode("<span>El Rol se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_rol()
  {
    $this->reglas_roles();
    $this->mensajes_reglas_roles();
    //-------------------------------------------------------------------------------
    $id_rol = $this->input->post('id_rol');
    $nombres = trim(mb_strtoupper($this->input->post('nombre_rol'), 'UTF-8'));
    $descripcion_rol = trim(mb_strtoupper($this->input->post('descripcion_rol'), 'UTF-8'));
    $existe = $this->Roles_model->verificar_existe_roles($nombres,$descripcion_rol,$id_rol);
    if($existe>0){
      echo "<span>Ya existe un rol con esas características</span>";die('');
    }
    //-------------------------------------------------------------------------------
    if($this->form_validation->run() == true){
      $rol = array(
        'nombre_rol' => mb_strtoupper($this->input->post('nombre_rol'), 'UTF-8'),
        'descripcion_rol' => mb_strtoupper($this->input->post('descripcion_rol'), 'UTF-8'),
      );
      
      $this->Roles_model->actualizar_rol($this->input->post('id_rol'), $rol, $this->input->post('permisos'));
      //--
      //$this->Roles_model->actualizar_rol_ind($this->input->post('id_rol'), $rol);

      echo json_encode("<span>El Rol se ha editado exitosamente!</span>"); // envio de mensaje exitoso
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }
/***/
  public function actualizar_rol2()
    {
      $this->reglas_roles();
      $this->mensajes_reglas_roles();
      if($this->form_validation->run() == true){
        $rol = array(
          'nombre_rol' => mb_strtoupper($this->input->post('nombre_rol'), 'UTF-8'),
          'descripcion_rol' => mb_strtoupper($this->input->post('descripcion_rol'), 'UTF-8'),
        );
        
        $this->Roles_model->actualizar_rol_ind($this->input->post('id_rol'), $rol);

        echo json_encode("<span>El Rol se ha editado exitosamente!</span>"); // envio de mensaje exitoso
      }else{
        // enviar los errores
        echo validation_errors();
      }
    }
/***/
  public function reglas_roles()
  {
    $this->form_validation->set_rules('nombre_rol','Nombre de Rol','required');
  }

  public function mensajes_reglas_roles(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
  }

  public function eliminar_rol()
  {
    $this->Roles_model->eliminar_rol($this->input->post('id'));
  }

  public function status_rol()
  {
    $this->Roles_model->status_rol($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_roles()
  {
    $this->Roles_model->eliminar_multiple_roles($this->input->post('id'));
  }

  public function status_multiple_roles()
  {
    $this->Roles_model->status_multiple_roles($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function operaciones_rol()
  {
    $operaciones = $this->Roles_model->operaciones_rol($this->input->post('id'));
    echo json_encode($operaciones);
  }

  public function eliminar_rol_operacion()
  {
    $this->Roles_model->eliminar_rol_operacion($this->input->post('id'));
  }
  /* Para eliminacion individual de rol de operaciones*/
  public function eliminar_rol_operacion_individual()
  {
    $this->Roles_model->eliminar_rol_operacion_individual($this->input->post('id'));
  }
  /* PAra eliminacion multipe de rol de operaciones*/
  public function eliminar_rol_operacion_multiple(){
    //var_dump($this->input->post('id'));die('');
    $this->Roles_model->eliminar_rol_operacion_multiple($this->input->post('id'));
  }
  
}