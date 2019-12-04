<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Modulos extends CI_Controller
{
	private $operaciones;
	function __construct()
	{
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Modulos_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url()."admin");
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('modulos', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    //--Modificacion para Mongo DB
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));    
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('modulos');
    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('perfiles/modulos/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_modulos()
  {
    $listado = $this->Modulos_model->listar_modulos();
    echo json_encode($listado);
  }

  public function contar_modulos()
  {
    $contador = $this->Menu_model->contar_modulos();
    echo json_encode($contador);
  }

  public function registrar_modulo()
  {
      $this->reglas_modulos('insert');
      $this->mensajes_reglas_modulos();
      $fecha = new MongoDB\BSON\UTCDateTime();
      $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
      //--------------------------------------------------------------------------------
      $modulo_verificado=$this->Modulos_model->verificar_modulo(mb_strtoupper($this->input->post('nombre_modulo_vista'), 'UTF-8')); //busca si el nombre del modulo esta registrado en la base de datos
      //var_dump(count($modulo_verificado));die('');
      //--------------------------------------------------------------------------------
      //var_dump($modulo_verificado==false);die();
      if($modulo_verificado==false){
          if($this->form_validation->run() == true){
            $posicionar = array(
              'posicion' => $this->input->post('posicion_modulo_vista'),
              'tipo' => 'insert',
            );
            $this->Modulos_model->posicionar_modulos($posicionar);
            $data=array(
              'nombre_modulo_vista' => trim(mb_strtoupper($this->input->post('nombre_modulo_vista'), 'UTF-8')),
              'descripcion_modulo_vista' => trim(mb_strtoupper($this->input->post('descripcion_modulo_vista'), 'UTF-8')),
              'posicion_modulo_vista' => (integer)$this->input->post('posicion_modulo_vista'),
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
            $this->Modulos_model->registrar_modulo($data);
            echo json_encode("<span>El módulo se ha registrado exitosamente!</span>"); // envio de mensaje exitoso
          }else{
            // enviar los errores
            echo validation_errors();
          }
      }else{
          echo "<span>El nombre del módulo ingresado ya se encuentra en uso!</span>";
      }    
  }

  public function actualizar_modulo()
  {
    $this->reglas_modulos('update');
    $this->mensajes_reglas_modulos();
    $fecha = new MongoDB\BSON\UTCDateTime();
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
    if($this->form_validation->run() == true){

      $modulo_verificado=$this->Modulos_model->verificar_modulo(mb_strtoupper($this->input->post('nombre_modulo_vista'), 'UTF-8')); //busca si el nombre del modulo esta registrado en la base de datos
      $posicionar = array(
        'inicial' => (integer)$this->input->post('inicial'),
        'tipo' => 'update',
        'final' => (integer)$this->input->post('posicion_modulo_vista'),
      );

      $data=array(
          'nombre_modulo_vista' => trim(mb_strtoupper($this->input->post('nombre_modulo_vista'), 'UTF-8')),
          'descripcion_modulo_vista' => trim(mb_strtoupper($this->input->post('descripcion_modulo_vista'), 'UTF-8')),
          'posicion_modulo_vista' => (integer)$this->input->post('posicion_modulo_vista'),
          'status'=>true,
          'eliminado'=>false,
          /*'auditoria' => [array(
                                  "cod_user" => $id_usuario,
                                  "nomuser" => $this->session->userdata('nombre'),
                                  "fecha" => $fecha,
                                  "accion" => "Nuevo registro",
                                  "operacion" => ""
                              )]*/
      );
      
      if($modulo_verificado){
          $cuantos_modulos = count($modulo_verificado);
      }else{
          $cuantos_modulos = 0;
      }

      if($cuantos_modulos>0){
        // si es mayor a cero, se verifica si el id recibido del formulario es igual al id que se verifico
        //var_dump($modulo_verificado[0]["_id"]->{'$id'}."-".$this->input->post('id_modulo_vista'));die('');
        //if($modulo_verificado[0]["_id"]==$this->input->post('id_modulo_vista')){
        if($modulo_verificado[0]["_id"]->{'$id'}==$this->input->post('id_modulo_vista')){  
          //si son iguales, quiere decir que es el mismo registro
          $this->Modulos_model->posicionar_modulos($posicionar);
          $this->Modulos_model->actualizar_modulo($this->input->post('id_modulo_vista'), $data);
          echo json_encode("<span>El módulo se ha editado exitosamente!</span>"); // envio de mensaje exitoso
        }else{
          //si son diferentes, quiere decir que ya el nombre del banco se encuentra en uso por otro registro
          echo "<span>El nombre del módulo ingresado ya se encuentra en uso!</span>";
        }
      }else{
        $this->Modulos_model->posicionar_modulos($posicionar);
        // si conteo del array es igual a 0, se actualiza el registro
        $this->Modulos_model->actualizar_modulo($this->input->post('id_modulo_vista'), $data);
        echo json_encode("<span>El módulo se ha editado exitosamente!</span>"); // envio de mensaje exitoso
      }
      
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_modulos($method)
  {
    if($method == "insert"){
      $this->form_validation->set_rules('nombre_modulo_vista','Nombre de Modulo','required[modulo_vista.nombre_modulo_vista]');
      $this->form_validation->set_rules('posicion_modulo_vista','Posición','required');
    }else if($method == "update"){
      $this->form_validation->set_rules('nombre_modulo_vista','Nombre de Modulo','required');
      $this->form_validation->set_rules('posicion_modulo_vista','Posición','required');
    }
  }

  public function mensajes_reglas_modulos(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
  }

  public function eliminar_modulo()
  {
    $this->Modulos_model->eliminar_modulo($this->input->post('id'));
  }
  public function orden_eliminar(){
    $this->Modulos_model->orden_eliminar();
  }
  public function status_modulo()
  {
    $this->Modulos_model->status_modulo($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_modulos()
  {
    $this->Modulos_model->eliminar_multiple_modulos($this->input->post('id'));
  }

  public function status_multiple_modulos()
  {

    $this->Modulos_model->status_multiple_modulos($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos