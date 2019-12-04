<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Corrida extends CI_Controller {
	private $operaciones;
	function __construct()
	{
	    parent::__construct();
	    //$this->load->database();
	    $this->load->library('session');
	    $this->load->model('Proyectos_model');
	    $this->load->model('Productos_model');
	    $this->load->model('Comision_model');
	    $this->load->model('Menu_model');
	    $this->load->library('form_validation');
	    if (!$this->session->userdata("login")) {
	      redirect(base_url()."admin");
	    }
	}
	public function index()
	{
	    $datos['permiso']       = $this->Menu_model->verificar_permiso_vista('Corrida', $this->session->userdata('id_rol'));
	    $data['modulos']        = $this->Menu_model->modulos();
	    $data['vistas']         = $this->Menu_model->vistas($this->session->userdata('id_usuario'));
	    $datos['breadcrumbs']   = $this->Menu_model->breadcrumbs('Corrida');

	    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
	    $data['modulos_vistas'] = $this->operaciones;


	    $datos['proyectos']              = $this->Proyectos_model->getproyectosactivos();
	    $datos['productos']              = $this->Productos_model->getproductosdisponibles();
	    $datos['plazo_saldos']           = $this->Comision_model->tipos_plazos();
	    $datos['plazo_anticipos']        = $this->Comision_model->tipos_plazos_anticipo();


	    $datos['forma_pagos']            = $this->Comision_model->tipos_pagos();

	   
	    $datos['fecha_actual']           = date("Y-m-d");


	    $this->load->view('cpanel/header');
	    $this->load->view('cpanel/menu', $data);
	    $this->load->view('ventas/corrida_financiera/index', $datos);
	    $this->load->view('cpanel/footer');
	}

}

/* End of file Corrida.php */
/* Location: ./application/controllers/Corrida.php */
