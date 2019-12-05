<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

	private $operaciones;
	function __construct()
	{
		parent::__construct();
		//$this->load->database();
		$this->load->library('session');
		$this->load->model('Menu_model');
		if (!$this->session->userdata("login")) {
			redirect(base_url()."admin");
		}
	}

	public function index()
	{
	    $data['modulos'] = $this->Menu_model->modulos();
	    //antes buscaba por id del usuario...
	    $data['vistas'] = $this->Menu_model->vistas($this->session->userdata('id_rol'));
	    //var_dump($data['vistas']);die();
		$this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    	$data['modulos_vistas'] = $this->operaciones;
    	$this->load->view('cpanel/header');
		$this->load->view('cpanel/menu', $data);
		$this->load->view('admin/index');
		$this->load->view('cpanel/footer');
	}

}
