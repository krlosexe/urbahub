<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Planes extends CI_Controller
{
  private $operaciones;
	function __construct()
  {
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Planes_model');
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
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('planes', $this->session->userdata('id_rol'));
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('planes');
    //--
    $data['modulos'] = $this->Menu_model->modulos();
    //--Migracion mongo db
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));    
    $data['vigencias'] = $this->Planes_model->listado_vigencia();
    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/Planes/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_planes()
  {
    //--Cambio con servicio ag2
    $listado = $this->Planes_model->listado_planes();  
    echo json_encode($listado);
  }

  public function registrar_planes()
  {
    $this->reglas_planes('insert');
    $this->mensajes_reglas_planes();


    
    $this->input->post('indicador_membresia') ? $membresia = true : $membresia = false;


    /*var_dump($this->input->post('indicador_jornadas_valor_registrar'));
    var_dump($this->input->post('indicador_plan_valor_registrar'));
    die('');*/
    $fecha = new MongoDB\BSON\UTCDateTime();
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

    if($this->form_validation->run() == true){
      //--Verifico si jornadas_limitadas valor es N valido horas de la jornada
      /*if(($this->input->post('indicador_jornadas_valor_registrar')=="N")&&($this->input->post('horas_jornadas')=="")){
           echo "<span>El campo de horas de jornadas debe ser obligatorio </span>";die('');
      }*/
      //Cambiando valores a true/false
      (trim($this->input->post('indicador_jornadas_valor_registrar'))=="S")? $jornadas = true: $jornadas = false;
      (trim($this->input->post('indicador_plan_valor_registrar'))=="S")? $plan_empresarial = true: $plan_empresarial = false;
      //--
      //$existe = $this->Planes_model->verificar_existe_plan(trim(mb_strtoupper($this->input->post('cod_planes'))),trim(mb_strtoupper($this->input->post('descripcion'))),$this->input->post('vigencia'),$this->input->post('tiempo_contrato'),$this->input->post('precio'),'');
      $existe = $this->Planes_model->verificar_existe_plan(trim(mb_strtoupper($this->input->post('cod_planes'))),trim(mb_strtoupper($this->input->post('descripcion'))),$this->input->post('vigencia'),$this->input->post('tiempo_contrato'),'','','');
      if($existe>0){
          echo "<span>Ya existe un plan con esas características</span>";die('');
      }
      //--Si existe un plan con ese codigo
      $existe = $this->Planes_model->verificar_existe_plan(trim(mb_strtoupper($this->input->post('cod_planes'))),'','','','','','');
      if($existe>0){
          echo "<span>Ya existe un plan con ese código</span>";die('');
      }
      //--Si existe un plan cone se titulo
      $existe = $this->Planes_model->verificar_existe_plan('','','','','',trim(mb_strtoupper($this->input->post('titulo'))),'');
      if($existe>0){
          echo "<span>Ya existe un plan con ese título</span>";die('');
      }
      $posicionar = array(
          'posicion' => $this->input->post('posicion_planes_registrar'),
          'tipo' => 'insert',
        );
      $this->Planes_model->posicionar_modulos($posicionar);
      //---
      (trim($this->input->post('indicador_muestra_web_registrar'))=="S")? $muestra_web = true: $muestra_web = false;
      //---
      $data = array(
          'cod_planes' =>  trim(mb_strtoupper($this->input->post('cod_planes'))),
          'titulo' => trim(mb_strtoupper($this->input->post('titulo'))),
          'descripcion' => trim(mb_strtoupper($this->input->post('descripcion'))),
          'id_vigencia' => $this->input->post('vigencia'),
          'tiempo_contrato' => $this->input->post('tiempo_contrato'),
          //'precio' => $this->input->post('precio'),
          'jornadas_limitadas' => $jornadas,
          'plan_empresarial' => $plan_empresarial,
          'posicion_planes'=>(integer)$this->input->post('posicion_planes_registrar'),
          'muestra_en_web'=>$muestra_web,
          //'horas_jornadas'=>trim($this->input->post('horas_jornadas')),
          'membresia' => $membresia,
          'status' => true,
          'eliminado' => false,
          'auditoria' => [array(
                                    "cod_user" => $id_usuario,
                                    "nomuser" => $this->session->userdata('nombre'),
                                    "fecha" => $fecha,
                                    "accion" => "Nuevo registro esquema",
                                    "operacion" => ""
                                )]
      );
  
      $this->Planes_model->registrar_planes($data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_planes()
  {
    $this->reglas_planes('update');
    $this->mensajes_reglas_planes();
    if($this->form_validation->run() == true){
      

      $this->input->post('indicador_membresia') ? $membresia = true : $membresia = false;


      //Cambiando valores a true/false
      (trim($this->input->post('indicador_jornadas_valor_actualizar'))=="S")? $jornadas = true: $jornadas = false;
      (trim($this->input->post('indicador_plan_valor_actualizar'))=="S")? $plan_empresarial = true: $plan_empresarial = false;
      //---
      //$existe = $this->Planes_model->verificar_existe_plan(trim(mb_strtoupper($this->input->post('cod_planes'))),trim(mb_strtoupper($this->input->post('descripcion'))),$this->input->post('vigencia'),$this->input->post('tiempo_contrato'),$this->input->post('precio'),$this->input->post('id_planes'));
      $existe = $this->Planes_model->verificar_existe_plan(trim(mb_strtoupper($this->input->post('cod_planes'))),trim(mb_strtoupper($this->input->post('descripcion'))),$this->input->post('vigencia'),$this->input->post('tiempo_contrato'),'','',$this->input->post('id_planes'));
      if($existe>0){
          echo "<span>Ya existe un plan con esas características</span>";die('');
      }
      //--Verificar si el existe un plan con ese codigo
       $existe = $this->Planes_model->verificar_existe_plan(trim(mb_strtoupper($this->input->post('cod_planes'))),'','','','','',$this->input->post('id_planes'));
        //--Si existe un plan cone se titulo
      if($existe>0){
          echo "<span>Ya existe un plan con ese código</span>";die('');
      }
      //--
      $existe = $this->Planes_model->verificar_existe_plan('','','','','',trim(mb_strtoupper($this->input->post('titulo'))),$this->input->post('id_planes'));
      if($existe>0){
          echo "<span>Ya existe un plan con ese título</span>";die('');
      }
      //--
      $posicionar = array(
          'inicial' => (integer)$this->input->post('inicial'),
          'tipo' => 'update',
          'final' => (integer)$this->input->post('posicion_planes_editar'),
        );
      //---
      (trim($this->input->post('indicador_muestra_web_modificar'))=="S")? $muestra_web = true: $muestra_web = false;
      //---
      $data = array(
          'id_planes' =>  $this->input->post('id_planes'),
          'cod_planes' =>  trim(mb_strtoupper($this->input->post('cod_planes'))),
          'titulo' => trim(mb_strtoupper($this->input->post('titulo'))),
          'descripcion' => trim(mb_strtoupper($this->input->post('descripcion'))),
          'id_vigencia' => $this->input->post('vigencia'),
          'tiempo_contrato' => $this->input->post('tiempo_contrato'),
          'posicion_planes'=>(integer)$this->input->post('posicion_planes_editar'),
          //'precio' => $this->input->post('precio'),
          'jornadas_limitadas' => $jornadas,
          'plan_empresarial' => $plan_empresarial,
          //'horas_jornadas'=>trim($this->input->post('horas_jornadas')),
          'muestra_en_web'=>$muestra_web,
          'status' => true,
          'eliminado' => false,
          'membresia' => $membresia,
      );   
      $this->Planes_model->posicionar_modulos($posicionar); 
      $this->Planes_model->actualizar_planes($this->input->post('id_planes'), $data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_planes($method)
  {
    if ($method == 'insert'){
      $this->form_validation->set_rules('cod_planes','Código','required');
      $this->form_validation->set_rules('titulo','Título','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
     // $this->form_validation->set_rules('vigencia','Vigencia','required');
     // $this->form_validation->set_rules('tiempo_contrato','Tiempo Contrato','required');
      //$this->form_validation->set_rules('precio','Precio','required');
    } else if ($method == 'update'){
      $this->form_validation->set_rules('cod_planes','Código','required');
      $this->form_validation->set_rules('titulo','Título','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
     // $this->form_validation->set_rules('vigencia','Vigencia','required');
      //$this->form_validation->set_rules('tiempo_contrato','Tiempo Contrato','required');
      //$this->form_validation->set_rules('precio','Precio','required');
    }
  }

  public function mensajes_reglas_planes(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo números');
    $this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya se encuentra en uso');
  }

  public function eliminar_planes()
  {
      $id = $this->input->post('id');
      /*$descuentos = $this->Esquemas_model->buscarDescuentos($id);
      $comisiones = $this->Esquemas_model->buscarComisiones($id);
      if (isset($descuentos) || isset($comisiones)){
        echo ("<span>El Esquema NO se puede eliminar ya que tiene una comisión/descuento asociado!</span>");
      }else{*/
      //}
      $paquetes = $this->Planes_model->buscarPaquetes($id);
      if ($paquetes>0){
        echo ("<span>El Plan NO se puede eliminar ya que tiene un paquete asociado!</span>");
      }else{
        $this->Planes_model->eliminar_planes($id);
      }
  }

  public function status_planes()
  {
    $this->Planes_model->status_planes($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_planes()
  {
    $ids = $this->input->post('id');
   /*foreach ($ids as $i => $id) {
    $descuentos = $this->Esquemas_model->buscarDescuentos($id);
    $comisiones = $this->Esquemas_model->buscarComisiones($id);
    if (isset($descuentos) || isset($comisiones)){
      echo ("<span>El Esquema NO se puede eliminar ya que tiene una comisión/descuento asociado!</span>");
    }else{*/
          $this->Planes_model->eliminar_multiple_planes($this->input->post('id'));
        /*}
    }*/
  }

  public function status_multiple_planes()
  {
    $this->Planes_model->status_multiple_planes($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }
  /*
  * Contar Modulos
  */
  public function contar_modulos(){
    $contador = $this->Planes_model->contar_modulos();
    echo json_encode($contador);
  }

  
  /*
  *
  */
  public function generarPosicionesPlanes(){
      $this->Planes_model->generarPosicionesPlanes(); 
  }
  /*
  *
  */
  public function generarMostrarWebPlanes(){
      $this->Planes_model->generarMostrarWebPlanes(); 
  }
  /***/
}//Fin class Planes
