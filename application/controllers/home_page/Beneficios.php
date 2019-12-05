<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beneficios extends CI_Controller {

	function __construct()
	{
	parent::__construct();
//	$this->load->database();
	$this->load->model('Bl_imagenes_model');
	$this->load->model('Bl_textos_model');
	$this->load->model('Meta_etiquetas_model');
	$this->load->model('banner_model');
	
    
}
	public function index()
	{

		//	primera zona
$consulta_meta_data = $this->Meta_etiquetas_model->meta_etiquetas_web(6);
$data_meta = $consulta_meta_data;
$data["meta_data"] = $data_meta;
$this->load->view('portal/zona1.php', $data);
//	primera zona




		$consulta_bl_imagenes = $this->Bl_imagenes_model->buscar_imagenes(4);
		$data_imagenes = json_decode($consulta_bl_imagenes);

		$data["imagenes"] = $data_imagenes;

		
		$consulta_texto = $this->Bl_textos_model->buscar_texto('4');
		$data_texto = json_decode($consulta_texto);
				 
		$data['textos'] = $data_texto;

		$data['beneficios'] = $this->banner_model->lista_b_a();

		$this->load->view('portal/beneficios.php', $data);
	

		// ultima zona
		$consulta_bl_imagenes = $this->Bl_imagenes_model->buscar_imagenes_web("1");
		$data_imagenes = $consulta_bl_imagenes;
		$data["imagenes"] = $data_imagenes;
		$consulta_texto = $this->Bl_textos_model->buscar_texto("7");;
		$data_texto = json_decode($consulta_texto);
		$data['textos'] = $data_texto;
		$this->load->view('portal/zona2.php',$data);
// ultima zona



    }

}