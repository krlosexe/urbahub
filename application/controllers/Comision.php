<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Comision extends CI_Controller
{
  private $operaciones;
	function __construct()
  {
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('Comision_model');
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
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('Comision', $this->session->userdata('id_rol'));
    //--Consumiendo servicio ag2 ...
    $id_vendedores = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'IDVENDEDOR'));
    $datos['id_vendedores'] = $id_vendedores->data;
    $tipos_vendedores = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'TIPOVENDEDOR'));
    $datos['tipos_vendedores'] = $tipos_vendedores->data;
    $tipos_plazos = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'TIPOPLAZOS'));

    //echo json_encode($id_vendedores);die("");

    $datos['tipos_plazos'] = $tipos_plazos->data;
    //$datos['id_vendedores'] = $this->Comision_model->id_venderores();
    //$datos['tipos_vendedores'] = $this->Comision_model->tipos_venderores();
    //$datos['tipos_plazos'] = $this->Comision_model->tipos_plazos();
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('Comision');
    $arreglo_esquemas = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>"ESQUEMAS", 'cod_static_lista_valor'=>"COMISION"));
    $tipo = (is_array($arreglo_esquemas->data)?$arreglo_esquemas->data[0]->id_lista_valor:'');
    $datos['esquemas'] = $this->Comision_model->esquemas($tipo);
    //--
    //--
    $data['modulos'] = $this->Menu_model->modulos();
    //--Migracion Mongo DB
    //$data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));   
    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/Comision/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_comision()
  {
    $listado2 = [];
    $listado = $this->Comision_model->listado_comision();
    foreach ($listado as $key => $value) {
      $arreglo_vendedor = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["id_vendedor"]));
      $arreglo_tipo_vendedor = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["tipo_vendedor"]));
      $arreglo_tipo_plazo = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["tipo_plazo"]));
      //var_dump($arreglo_esquemas->data[0]->nombre_lista_valor)."-";
      //Transformo el la fila de  en un array
      //$arreglo_data = get_object_vars($value);
      $arreglo_data = $value;
      $arreglo_data["idVendedor"] = is_array($arreglo_vendedor->data)?$arreglo_vendedor->data[0]->nombre_lista_valor:(is_object($arreglo_vendedor->data)?$arreglo_vendedor->data->nombre_lista_valor:'');
      $arreglo_data["tipoVendedor"] = is_array($arreglo_tipo_vendedor->data)?$arreglo_tipo_vendedor->data[0]->nombre_lista_valor:(is_object($arreglo_tipo_vendedor->data)?$arreglo_tipo_vendedor->data->nombre_lista_valor:'');
      $arreglo_data["tipoPlazo"] = is_array($arreglo_tipo_plazo->data)?$arreglo_tipo_plazo->data[0]->nombre_lista_valor:(is_object($arreglo_tipo_plazo->data)?$arreglo_tipo_plazo->data->nombre_lista_valor:'');
      
      $listado2[] = $arreglo_data;
    }
    echo json_encode($listado2);
  }

  public function registrar_comision()
  {
    $this->reglas_comision();
    
    $this->mensajes_reglas_comision();

    $max = (float)$this->input->post('num_ventas_max_mes');
    
    $min = (float)$this->input->post('num_ventas_min_mes');
    if($min>$max){
      echo "<span>Las ventas min. no pueden ser mayores a ventas max.</span>";die('');
    }
    //--Valido que ventas min no pueda ser ayor a ventas max------------------------
     if(($this->input->post('indicador_venta_registrar')=="S")&&($min=="")){
      echo "<span>Las ventas min. no pueden estar en blanco.</span>";die('');
    }
    if(($this->input->post('indicador_venta_registrar')=="S")&&($max=="")){
      echo "<span>Las ventas max. no pueden estar en blanco.</span>";die('');
    }

    //-------------------------------------------------------------------------------
    
    $fecha = new MongoDB\BSON\UTCDateTime();
    
    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

    if($this->form_validation->run() == true){
      $data = array(
                      'id_vendedor'              => $this->input->post('id_vendedor'),
                      'tipo_vendedor'            => $this->input->post('tipo_vendedor'),
                      'cod_esquema'              => $this->input->post('cod_esquema'),
                      'ind_ventas_mes'           => $this->input->post('indicador_venta_registrar'),
                      'cantidad_max_ventas_mes'  => trim($this->input->post('num_ventas_max_mes')),
                      'cantidad_min_ventas_mes'  => trim($this->input->post('num_ventas_min_mes')),
                      'tipo_plazo'               => $this->input->post('tipo_plazo'),
                      //'porctj_comision'          => trim(str_replace(',', '.', $this->input->post('porctj_comision'))),
                      'porctj_comision'          => $this->input->post('porctj_comision'),
                      'status' => true,
                      'eliminado' => false,
                      'auditoria' => [array(
                                                "cod_user" => $id_usuario,
                                                "nomuser" => $this->session->userdata('nombre'),
                                                "fecha" => $fecha,
                                                "accion" => "Nuevo registro comisiones",
                                                "operacion" => ""
                                            )]
      );
      $this->Comision_model->registrar_comision($data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_comision()
  {
    $this->reglas_comision();
    $this->mensajes_reglas_comision();
    //------------------------------------------------------------------------------
    $max = (float)$this->input->post('num_ventas_max_mes');
    
    $min = (float)$this->input->post('num_ventas_min_mes');
    if($min>$max){
      echo "<span>Las ventas min. no pueden ser mayores a ventas max.</span>";die('');
    }
    if(($this->input->post('indicador_venta_actualizar')=="S")&&($min=="")){
      echo "<span>Las ventas min. no pueden estar en blanco.</span>";die('');
    }
    if(($this->input->post('indicador_venta_actualizar')=="S")&&($max=="")){
      echo "<span>Las ventas max. no pueden estar en blanco.</span>";die('');
    }
    //------------------------------------------------------------------------------
    if($this->form_validation->run() == true){
      $data = array(
        'id_vendedor'              => $this->input->post('id_vendedor'),
        'tipo_vendedor'            => $this->input->post('tipo_vendedor'),
        'cod_esquema'              => $this->input->post('cod_esquema'),
        'ind_ventas_mes'           => $this->input->post('indicador_venta_actualizar'),
        'cantidad_max_ventas_mes'  => trim($this->input->post('num_ventas_max_mes')),
        'cantidad_min_ventas_mes'  => trim($this->input->post('num_ventas_min_mes')),
        'tipo_plazo'               => $this->input->post('tipo_plazo'),
        'porctj_comision'          => $this->input->post('porctj_comision'),
        //'porctj_comision'          => trim(str_replace(',', '.', $this->input->post('porctj_comision'))),
      );
      $this->Comision_model->actualizar_comision($this->input->post('id_comision'), $data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_comision()
  {
    $this->form_validation->set_rules('id_vendedor','Id de Vendedor','required');
    $this->form_validation->set_rules('tipo_vendedor','Tipo de Vendedor','required');
    $this->form_validation->set_rules('tipo_plazo','Tipo de Plazo','required');
    //$this->form_validation->set_rules('num_ventas_mes','Ventas al mes','required|numeric');
    $this->form_validation->set_rules('porctj_comision','Porcentaje de Comisión','required');
  }

  public function mensajes_reglas_comision(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo números');
  }

  public function eliminar_comision()
  {
    $this->Comision_model->eliminar_comision($this->input->post('id'));
  }

  public function status_comision()
  {
    $this->Comision_model->status_comision($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_comision()
  {
    $this->Comision_model->eliminar_multiple_comision($this->input->post('id'));
  }

  public function status_multiple_comision()
  {
    $this->Comision_model->status_multiple_comision($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos
