<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Temporadas extends CI_Controller
{
  private $operaciones;
	function __construct()
  {
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Temporadas_model');
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
      $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('temporadas', $this->session->userdata('id_rol'));
      $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('temporadas');
      //--
      $data['modulos'] = $this->Menu_model->modulos();
      //--Migracion mongo db
      //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
      $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));    
      $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
      $data['modulos_vistas'] = $this->operaciones;
      $this->load->view('cpanel/header');
      $this->load->view('cpanel/menu', $data);
      $this->load->view('catalogo/Temporadas/index', $datos);
      $this->load->view('cpanel/footer');
  }
  /*
  *** Metodo que permite agregar temporadas ...
  */
  public function agregarTemporadas(){
    $datos["fila"] = $this->input->post("fila");
    $this->load->view('catalogo/Temporadas/filaTemporada', $datos);
  }
  
  public function listado_planes()
  {
    //--Cambio con servicio ag2
    $listado = $this->Planes_model->listado_planes();  
    echo json_encode($listado);
  }

  public function registrarTemporadas(){
      $vector = $this->input->post('vector');
      //Valido las posiciones
      $this->validar_vector($vector);
      //Registro el vector segun su recorrido
      $this->recorrer_guardar($vector);
  }

  public function consultarTemporadas(){
    //var_dump($this->input->post('numero_temporada'));die('');
      if($this->input->post('numero_temporada')!=""){
          $numero_temporada =  $this->input->post('numero_temporada');
      }else{
          $numero_temporada = "";
      }
      if($numero_temporada!=""){
          $datos["datos"] = $this->Temporadas_model->consultarMapasTemporadas($numero_temporada); 
          $this->load->view('catalogo/Temporadas/mapaTemporadas', $datos);  
      }else{
          die('vacio');
      }
      
  }

  public function validar_vector($vector){
      $c = count($vector["desde"]);
      for($i=0;$i<$c;$i++){
          $i2 = $i+1;
          if($vector["desde"][$i]==""){
              echo "<span>Debe ingresar la fecha desde de la fila # ".$i2."</span>";die('');
          }
          if($vector["hasta"][$i]==""){
              echo "<span>Debe ingresar la fecha hasta de la fila # ".$i2."</span>";die('');
          }
          if($vector["ajuste_precio"][$i]==""){
              echo "<span>Debe ingresar el ajuste_precio de la fila # ".$i2."</span>";die('');
          }
          if($vector["condicion"][$i]==""){
              echo "<span>Debe ingresar la condicion de la fila # ".$i2."</span>";die('');
          }
      }
  }

  public function consultarTemporadasExistentes(){
      $datos["datos"] = $this->Temporadas_model->consultarTemporadasExistentes();  
      $this->load->view('catalogo/Temporadas/filaTemporadaConsulta', $datos);
  }

  public function recorrer_guardar($vector){
      $c = count($vector["desde"]);
      $fecha = new MongoDB\BSON\UTCDateTime();
      $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
      for($i=0;$i<$c;$i++){
          $i2 = $i+1;
          $fecha_desde = new MongoDB\BSON\UTCDateTime((new DateTime($vector["desde"][$i]))->getTimestamp()*1000); 
          $fecha_hasta = new MongoDB\BSON\UTCDateTime((new DateTime($vector["hasta"][$i]))->getTimestamp()*1000); 
          if($vector["id"][$i]==""){
              //--Guardo
              $data = array(
                  'temporada' => (integer)$vector["temporada"][$i],
                  'fecha_desde' => $fecha_desde,
                  'fecha_hasta' => $fecha_hasta,
                  'ajuste' =>$vector["ajuste_precio"][$i],
                  'operacion' => (bool)$vector["condicion"][$i],
                  'aplicar' => (bool)$vector["aplicar"][$i],
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
            $this->Temporadas_model->registrar_temporadas($data);
          }else{
            //--Modifico
              $data = array(
                  'id'=> $vector["id"][$i],
                  'temporada' => $i2,
                  'fecha_desde' => $fecha_desde,
                  'fecha_hasta' => $fecha_hasta,
                  'ajuste' =>$vector["ajuste_precio"][$i],
                  'operacion' => (bool)$vector["condicion"][$i],
                  'aplicar' => (bool)$vector["aplicar"][$i],
                  'status' => true,
                  'eliminado' => false
              );
              $this->Temporadas_model->modificar_temporadas($data);
          }
           
      }  
  }

  public function actualizar_planes()
  {
    $this->reglas_planes('update');
    $this->mensajes_reglas_planes();
    if($this->form_validation->run() == true){
      $data = array(
          'id_planes' =>  $this->input->post('id_planes'),
          'cod_planes' =>  trim(mb_strtoupper($this->input->post('cod_planes'))),
          'descripcion' => trim(mb_strtoupper($this->input->post('descripcion'))),
          'id_vigencia' => $this->input->post('vigencia'),
          'tiempo_contrato' => $this->input->post('tiempo_contrato'),
          'precio' => $this->input->post('precio'),
          'status' => true,
          'eliminado' => false,
      );    
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
      $this->form_validation->set_rules('descripcion','Descripción','required');
      $this->form_validation->set_rules('vigencia','Vigencia','required');
      $this->form_validation->set_rules('tiempo_contrato','Tiempo Contrato','required');
      $this->form_validation->set_rules('precio','Precio','required');
    } else if ($method == 'update'){
      $this->form_validation->set_rules('cod_planes','Código','required');
      $this->form_validation->set_rules('descripcion','Descripción','required');
      $this->form_validation->set_rules('vigencia','Vigencia','required');
      $this->form_validation->set_rules('tiempo_contrato','Tiempo Contrato','required');
      $this->form_validation->set_rules('precio','Precio','required');
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
      $this->Planes_model->eliminar_planes($id);
      //}
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

}//Fin class Bancos
