<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Servicios extends CI_Controller
{
  private $operaciones;
	function __construct()
  {
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Servicios_model');
    $this->load->model('Menu_model');
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
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('servicios', $this->session->userdata('id_rol'));
   
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('servicios');
    //--
    $data['modulos'] = $this->Menu_model->modulos();
    //--Migracion mongo db
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));    

    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    //--
    $datos["tipoServ"] = $this->Servicios_model->listado_tipo_servicios();
    //--
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/Servicios/index', $datos);
    $this->load->view('cpanel/footer');
  }



  public function ListTipos()
  {
    echo json_encode($this->Servicios_model->listado_tipo_servicios());
  }

  public function listado_servicios()
  {
    //--Cambio con servicio ag2
    $listado = $this->Servicios_model->listado_esquema();
    
    echo json_encode($listado);
  }

  public function registrar_servicio()
  {
    $this->reglas_servicios('insert');
    $this->mensajes_reglas_sevicios();

    $fecha = new MongoDB\BSON\UTCDateTime();
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
    
    if($this->form_validation->run() == true){
      //--Verifico si existe un servicio
      $existe = $this->Servicios_model->verificar_existe_servicios($this->input->post('tipo'),trim(mb_strtoupper($this->input->post('cod_servicio'))),trim(mb_strtoupper($this->input->post('descripcion'))),'');
      if($existe>0){
          echo "<span>Ya existe un servicio con esas características</span>";die('');
      }
      //--Verifico si existe un servicio con ese codigo
      $existe = $this->Servicios_model->verificar_existe_servicios('',trim(mb_strtoupper($this->input->post('cod_servicio'))),'','');
      if($existe>0){
          echo "<span>Ya existe un servicio con ese código</span>";die('');
      }
      //--Verifico si existe un servicio con esa descripcion
      $existe = $this->Servicios_model->verificar_existe_servicios('','',trim(mb_strtoupper($this->input->post('descripcion'))),'');
      if($existe>0){
          echo "<span>Ya existe un servicio con esa descripción</span>";die('');
      }
      //--
      $data = array(
        'tipo'          => $this->input->post('tipo'),
        'tipo_servicio' => $this->input->post('tipo_serv_registrar'),
        'categoria'     => $this->input->post('categorias'),
        'cod_servicios' => trim(mb_strtoupper($this->input->post('cod_servicio'))),
        'descripcion'   => trim(mb_strtoupper($this->input->post('descripcion'))),
        'monto'         => str_replace(',', '', $this->input->post('monto')),
        'membresia'     => $this->input->post('membresia'),
        //'horas' => trim(mb_strtoupper($this->input->post('horas_registrar'))),
        //'servicio_consumible' => trim(mb_strtoupper($this->input->post('indicador_servicio_consumible_registrar'))),
        'status'        => true,
        'eliminado'     => false,
        'auditoria'     => [array(
                                  "cod_user"  => $id_usuario,
                                  "nomuser"   => $this->session->userdata('nombre'),
                                  "fecha"     => $fecha,
                                  "accion"    => "Nuevo registro esquema",
                                  "operacion" => ""
                              )]
      );

      $this->Servicios_model->registrar_servicio($data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_servicio()
  {
    $this->reglas_servicios('update');
    $this->mensajes_reglas_sevicios();
    if($this->form_validation->run() == true){
      $existe = $this->Servicios_model->verificar_existe_servicios($this->input->post('tipo'),trim(mb_strtoupper($this->input->post('cod_servicio'))),trim(mb_strtoupper($this->input->post('descripcion'))),$this->input->post('id_servicio'));
      if($existe>0){
          echo "<span>Ya existe un servicio con esas características</span>";die('');
      }
      //--Verifico si existe un servicio con ese codigo
      $existe = $this->Servicios_model->verificar_existe_servicios('',trim(mb_strtoupper($this->input->post('cod_servicio'))),'',$this->input->post('id_servicio'));
      if($existe>0){
          echo "<span>Ya existe un servicio con ese código</span>";die('');
      }
      //--Verifico si existe un servicio con esa descripcion
      $existe = $this->Servicios_model->verificar_existe_servicios('','',trim(mb_strtoupper($this->input->post('descripcion'))),$this->input->post('id_servicio'));
      if($existe>0){
          echo "<span>Ya existe un servicio con esa descripción</span>";die('');
      }
      //--
      $data = array(
        'tipo' => $this->input->post('tipo'),
        'tipo_servicio' => $this->input->post('tipo_serv_editar'),
        'categoria'     => $this->input->post('categorias'),
        'cod_servicios' => trim(mb_strtoupper($this->input->post('cod_servicio'))),
        'descripcion' => trim(mb_strtoupper($this->input->post('descripcion'))),
        /*'horas' => trim(mb_strtoupper($this->input->post('horas_modificar'))),
        'servicio_consumible' => trim(mb_strtoupper($this->input->post('indicador_servicio_consumible_modificar'))),*/
        'monto' => str_replace(',', '', $this->input->post('monto')),
        'membresia'     => $this->input->post('membresia'),
        'status' => true,
        'eliminado' => false,
      );
      $this->Servicios_model->actualizar_servicio($this->input->post('id_servicio'), $data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_servicios($method)
  {
    if ($method == 'insert'){
      $this->form_validation->set_rules('tipo','Tipo','required');
      $this->form_validation->set_rules('cod_servicio','Código de Servicio','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
    } else if ($method == 'update'){
      $this->form_validation->set_rules('tipo','Tipo','required');
      $this->form_validation->set_rules('cod_servicio','Código de Servicio','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
    }
  }

  public function mensajes_reglas_sevicios(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo números');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
  }

  public function eliminar_servicio()
  {
      $id = $this->input->post('id');
      //**
      $dependencia_reservaciones = $this->Servicios_model->consultar_dependencia("reservaciones",$id);
      if($dependencia_reservaciones>0){
          echo "<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>";die('');
      }
      //**
      $paquetes = $this->Servicios_model->buscarPaquetes($id);
      if ($paquetes>0){
          echo ("<span>El Servicio NO se puede eliminar ya que tiene un paquete asociado!</span>");
      }else{
        $this->Servicios_model->eliminar_servicio($id);
      }
  }

  public function status_servicio()
  {
    $this->Servicios_model->status_servicio($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_servicios()
  {
      //$ids = $this->input->post('id');
      //foreach ($ids as $i => $id) {
      //$descuentos = $this->Planes_model->buscarDescuentos($id);
      //$comisiones = $this->Esquemas_model->buscarComisiones($id);
      /*if (isset($descuentos) || isset($comisiones)){
        echo ("<span>El Esquema NO se puede eliminar ya que tiene una comisión/descuento asociado!</span>");
      }else{*/
      $this->Servicios_model->eliminar_multiple_servicios($this->input->post('id'));
          //}
      //}
  }

  public function status_multiple_servicio()
  {
    $this->Servicios_model->status_multiple_servicio($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }





 public function storeTipoServicio($name)
 {  

  $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
  $fecha = new MongoDB\BSON\UTCDateTime();

  $data = array("titulo"=> $name,
             "status"=> true,
             "eliminado"=> false,
             "auditoria"=> [
               
                 array("cod_user"=> $id_usuario,
                 "nomuser"   => "",
                                  "fecha"     => $fecha,
                 "accion"=> "Nuevo registro tipo servicio",
                 "operacion"=> "")
              
            ]);
  $insertar1 = $this->mongo_db->insert("tipo_servicios", $data);
 }


 
}//Fin class Bancos
