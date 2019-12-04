<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Recargas extends CI_Controller
{
  private $operaciones;
  function __construct()
  {
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Recargas_model');
    $this->load->model('Menu_model');
    //--
    $this->load->helper('consumir_rest');
    $this->load->helper('organizar_sepomex');
    $this->load->helper('array_push_assoc');
    //--
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url()."admin");
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Recargas', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    //--Migracion Mongo DB
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));   
    //--Agregando servicio ag2
    $tipos_vendedores = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'TIPOVENDEDOR'));
    $datos['tipos_vendedores'] = $tipos_vendedores->data;
    $tipos_plazos = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'TIPOPLAZOS'));
    $datos['tipos_plazos'] = $tipos_plazos->data;
    //--
    //echo json_encode($id_vendedores);die("");
    //$datos['tipos_plazos'] = $this->Recargas_model->tipos_plazos();
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Recargas');
    //$datos['esquemas'] = $this->Recargas_model->esquemas();
   
    $arreglo_esquemas = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>"ESQUEMAS", 'cod_static_lista_valor'=>"RECARGOS"));
   
    $tipo = (is_array($arreglo_esquemas->data)?$arreglo_esquemas->data[0]->id_lista_valor:'');

    $listado_esquemas = $this->Recargas_model->esquemas($tipo);
    $listado_esquemas_consulta = $this->Recargas_model->esquemas_consulta($tipo);
    $datos['esquemas'] = $listado_esquemas;
    $datos['esquemas_consulta'] = $listado_esquemas_consulta;
    /*$esquemas = [];
    foreach ($listado_esquemas as $key => $value) {
      $arreglo_esquemas = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["tipo"]));
      if(((is_array($arreglo_esquemas->data)?$arreglo_esquemas->data[0]->cod_static_tipo_valor:'')=="ESQUEMAS")and($arreglo_esquemas->data[0]->nombre_lista_valor=='RECARGOS')){
        $esquemas[] = $value;
      }  
    }
    $datos['esquemas'] = $esquemas;*/
    /*$esquemas_consulta = [];
    foreach ($listado_esquemas_consulta as $key => $value) {
      $arreglo_esquemas = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["tipo"]));
      if(((is_array($arreglo_esquemas->data)?$arreglo_esquemas->data[0]->cod_static_tipo_valor:'')=="ESQUEMAS")and($arreglo_esquemas->data[0]->nombre_lista_valor=='RECARGOS')){
        $esquemas_consulta[] = $value;
      }  
    }
    $datos['esquemas_consulta'] = $esquemas_consulta;*/
    
    //var_dump($datos['esquemas']);die("");
    //$datos['tipos_vendedores'] = $this->Recargas_model->tipos_vendedores();
    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/Recargas/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listar_recargas()
  {
    $listado2 = array();  
    $listado = $this->Recargas_model->listar_recargas();
    foreach ($listado as $key => $value) {
      $arreglo_tipo_vendedor = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["tipo_vendedor"]));
      $arreglo_tipo_plazo = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["tipo_plazo"]));
      //var_dump($arreglo_esquemas->data[0]->nombre_lista_valor)."-";
      //Transformo el la fila de  en un array
      //$arreglo_data = get_object_vars($value);      
      $arreglo_data = $value;      
      $arreglo_data["tipoVendedor"] = is_array($arreglo_tipo_vendedor->data)?$arreglo_tipo_vendedor->data[0]->nombre_lista_valor:(is_object($arreglo_tipo_vendedor->data)?$arreglo_tipo_vendedor->data->nombre_lista_valor:'');
      $arreglo_data["tipoPlazo"] = is_array($arreglo_tipo_plazo->data)?$arreglo_tipo_plazo->data[0]->nombre_lista_valor:(is_object($arreglo_tipo_plazo->data)?$arreglo_tipo_plazo->data->nombre_lista_valor:'');
      $listado2[] = $arreglo_data;
    }
    echo json_encode($listado2);
  }



  public function getRecargosCorrida($plazo, $tipo_vendedor)
  {
    $recargos = $this->Recargas_model->getRecargosCorrida($plazo, $tipo_vendedor);
    echo json_encode($recargos);
  }

  public function registrar_recarga()
  {
    $this->reglas_recargas();
    $this->mensajes_reglas_recargas();
    
    $fecha = new MongoDB\BSON\UTCDateTime();
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

    if($this->form_validation->run() == true){
      $data=array(
        'tipo_plazo' => $this->input->post('tipo_plazo'),
        'tipo_vendedor' => $this->input->post('tipo_vendedor'),
        'recarga' => $this->input->post('recarga'),
        //'recarga' => trim(str_replace(',', '.', $this->input->post('recarga'))),
        'cod_esquema' => $this->input->post('cod_esquema'),
        'status' => true,
        'eliminado' => false,
        'auditoria' => [array(
                                  "cod_user" => $id_usuario,
                                  "nomuser" => $this->session->userdata('nombre'),
                                  "fecha" => $fecha,
                                  "accion" => "Nuevo registro recarga",
                                  "operacion" => ""
                              )]
      );
      $this->Recargas_model->registrar_recarga($data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_recarga()
  {
    $this->reglas_recargas();
    $this->mensajes_reglas_recargas();
    if($this->form_validation->run() == true){
      $data=array(
        'tipo_plazo' => $this->input->post('tipo_plazo'),
        'tipo_vendedor' => $this->input->post('tipo_vendedor'),
        'recarga' => $this->input->post('recarga'),
        //'recarga' => trim(str_replace(',', '.', $this->input->post('recarga'))),
        'cod_esquema' => $this->input->post('cod_esquema'),
      );
      $this->Recargas_model->actualizar_recarga($this->input->post('id_recarga'), $data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_recargas()
  {
    $this->form_validation->set_rules('tipo_plazo','Tipo de Plazo','required');
    $this->form_validation->set_rules('recarga','recarga','required');
    $this->form_validation->set_rules('cod_esquema','Esquema de recarga','required');
  }

  public function mensajes_reglas_recargas(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
  }

  public function eliminar_recarga()
  {
    $this->Recargas_model->eliminar_recarga($this->input->post('id'));
  }

  public function status_recarga()
  {
    $this->Recargas_model->status_recarga($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_recargas()
  {
    $this->Recargas_model->eliminar_multiple_recargas($this->input->post('id'));
  }

  public function status_multiple_recargas()
  {
    $this->Recargas_model->status_multiple_recargas($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos
