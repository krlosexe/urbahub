<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planes extends CI_Controller {

	function __construct()
	{
	parent::__construct();
//	$this->load->database();
	$this->load->model('Bl_imagenes_model');
	$this->load->model('Bl_textos_model');
	$this->load->model('Meta_etiquetas_model');
	
    
}
	public function index()
	{
		
		//	primera zona
		$consulta_meta_data = $this->Meta_etiquetas_model->meta_etiquetas_web(3);
		$data_meta = $consulta_meta_data;
		$data["meta_data"] = $data_meta;
		$this->load->view('portal/zona1.php', $data);
		//	primera zona


		$this->load->view('portal/zona_planes.php');

		/*
		$this->load->view('portal/menu.php');
		$this->load->view('portal/planes');
		$this->load->view('portal/info3');
		$this->load->view('portal/footer.php');
		*/
	}
}