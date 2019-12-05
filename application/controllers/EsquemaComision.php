<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class EsquemaComision extends CI_Controller
{
	private $operaciones;
	function __construct()
	{
    parent::__construct();
    //$this->load->database();
    $this->load->library('session');
    $this->load->model('EsquemaComision_model');
    $this->load->model('Menu_model');
    $this->load->library('form_validation');
    if (!$this->session->userdata("login")) {
      redirect(base_url()."admin");
    }
  }

  public function index()
  {
    $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('esquemacomision', $this->session->userdata('id_rol'));
    $datos['id_vendedores'] = $this->EsquemaComision_model->id_venderores();
    $datos['tipos_vendedores'] = $this->EsquemaComision_model->tipos_venderores();
    $datos['tipos_plazos'] = $this->EsquemaComision_model->tipos_plazos();
    $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('EsquemaComision');
    $data['modulos'] = $this->Menu_model->modulos();
    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    $data['modulos_vistas'] = $this->operaciones;
    $this->load->view('cpanel/header');
    $this->load->view('cpanel/menu', $data);
    $this->load->view('catalogo/EsquemaComision/index', $datos);
    $this->load->view('cpanel/footer');
  }

  public function listado_esquema_comision()
  {
    $listado = $this->EsquemaComision_model->listado_esquema_comision();
    echo json_encode($listado);
  }

  public function registrar_esquema_comision()
  {
    $this->reglas_esquema_comision();
    $this->mensajes_reglas_esquema_comision();
    if($this->form_validation->run() == true){
      $data = array(
        'id_vendedor' => $this->input->post('id_vendedor'),
        'tipo_vendedor' => $this->input->post('tipo_vendedor'),
        'tipo_plazo' => $this->input->post('tipo_plazo'),
        'num_ventas_mes' => $this->input->post('num_ventas_mes'),
        'porctj_comision' => str_replace(',', '.', $this->input->post('porctj_comision')),
      );
      $this->EsquemaComision_model->registrar_esquema_comision($data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function actualizar_esquema_comision()
  {
    $this->reglas_esquema_comision();
    $this->mensajes_reglas_esquema_comision();
    if($this->form_validation->run() == true){
      $data = array(
        'id_vendedor' => $this->input->post('id_vendedor'),
        'tipo_vendedor' => $this->input->post('tipo_vendedor'),
        'tipo_plazo' => $this->input->post('tipo_plazo'),
        'num_ventas_mes' => $this->input->post('num_ventas_mes'),
        'porctj_comision' => str_replace(',', '.', $this->input->post('porctj_comision')),
      );
      $this->EsquemaComision_model->actualizar_esquema_comision($this->input->post('id_esquema_comision'), $data);
    }else{
      // enviar los errores
      echo validation_errors();
    }
  }

  public function reglas_esquema_comision()
  {
    $this->form_validation->set_rules('id_vendedor','Id de Vendedor','required');
    $this->form_validation->set_rules('tipo_vendedor','Tipo de Vendedor','required');
    $this->form_validation->set_rules('tipo_plazo','Tipo de Plazo','required');
    $this->form_validation->set_rules('num_ventas_mes','Ventas al mes','required|numeric');
    $this->form_validation->set_rules('porctj_comision','Porcentaje de Comisión','required');
  }

  public function mensajes_reglas_esquema_comision(){
    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
    $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo números');
  }

  public function eliminar_esquema_comision()
  {
    $this->EsquemaComision_model->eliminar_esquema_comision($this->input->post('id'));
  }

  public function status_esquema_comision()
  {
    $this->EsquemaComision_model->status_esquema_comision($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

  public function eliminar_multiple_esquema_comision()
  {
    $this->EsquemaComision_model->eliminar_multiple_esquema_comision($this->input->post('id'));
  }

  public function status_multiple_esquema_comision()
  {
    $this->EsquemaComision_model->status_multiple_esquema_comision($this->input->post('id'), $this->input->post('status'));
    echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
  }

}//Fin class Bancos