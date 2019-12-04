<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Descuento extends CI_Controller
{
  private $operaciones;
  function __construct()
  {
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Descuento_model');
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
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Descuento', $this->session->userdata('id_rol'));
    $data['modulos'] = $this->Menu_model->modulos();
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));    
    $tipos_vendedores = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'TIPOVENDEDOR'));
    $datos['tipos_vendedores'] = $tipos_vendedores->data;
    $tipos_plazos = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'TIPOPLAZOS'));
    $datos['tipos_plazos'] = $tipos_plazos->data;

    //$datos['tipos_plazos'] = $this->Descuento_model->tipos_plazos();
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Descuento');

    $arreglo_esquemas = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>"ESQUEMAS", 'cod_static_lista_valor'=>"DESCUENTO"));
    $tipo = (is_array($arreglo_esquemas->data)?$arreglo_esquemas->data[0]->id_lista_valor:'');
    $datos['esquemas'] = $this->Descuento_model->esquemas($tipo);
    
    //$datos['tipos_vendedores'] = $this->Descuento_model->tipos_vendedores();
    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/Descuento/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listar_descuentos()
  {
    $listado = $this->Descuento_model->listar_descuentos();
    $listado2 = [];
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
    //echo json_encode($listado);
  }

  public function registrar_descuento()
  {
    $this->reglas_descuento();
    $this->mensajes_reglas_descuento();
    $fecha = new MongoDB\BSON\UTCDateTime();
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
    if($this->form_validation->run() == true){
      $data=array(
        'tipo_plazo' => $this->input->post('tipo_plazo'),
        'tipo_vendedor' => $this->input->post('tipo_vendedor'),
        //'descuento' => trim(str_replace(',', '.', $this->input->post('descuento'))),
        'descuento' => $this->input->post('descuento'),
        'cod_esquema' => $this->input->post('cod_esquema'),
        'status' => true,
        'eliminado' => false,
        'auditoria' => [array(
                                  "cod_user" => $id_usuario,
                                  "nomuser" => $this->session->userdata('nombre'),
                                  "fecha" => $fecha,
                                  "accion" => "Nuevo registro descuento",
                                  "operacion" => ""
                              )]
      );
      
      $this->Descuento_model->registrar_descuento($data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_descuento()
  {
    $this->reglas_descuento();
    $this->mensajes_reglas_descuento();
    if($this->form_validation->run() == true){
      $data=array(
        'tipo_plazo' => $this->input->post('tipo_plazo'),
        'tipo_vendedor' => $this->input->post('tipo_vendedor'),
        //'descuento' => trim(str_replace(',', '.', $this->input->post('descuento'))),
        'descuento' => $this->input->post('descuento'),
        'cod_esquema' => $this->input->post('cod_esquema'),
      );
      $this->Descuento_model->actualizar_descuento($this->input->post('id_descuento'), $data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }
  

  public function getdescuentoCorrida($plazo, $tipo_vendedor)
  {
    $descuento = $this->Descuento_model->getdescuentoCorrida($plazo, $tipo_vendedor);
    echo json_encode($descuento);
  }
  public function reglas_descuento()
  {
    $this->form_validation->set_rules('tipo_plazo','Tipo de Plazo','required');
    $this->form_validation->set_rules('descuento','Descuento','required');
    $this->form_validation->set_rules('cod_esquema','Esquema de Descuento','required');
  }

  public function mensajes_reglas_descuento(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
  }

  public function eliminar_descuento()
  {
    $this->Descuento_model->eliminar_descuento($this->input->post('id'));
  }

  public function status_descuento()
  {
    $this->Descuento_model->status_descuento($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_descuento()
  {
    $this->Descuento_model->eliminar_multiple_descuento($this->input->post('id'));
  }

  public function status_multiple_descuento()
  {
    $this->Descuento_model->status_multiple_descuento($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos
