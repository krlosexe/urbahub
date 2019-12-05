<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meta_etiquetas extends CI_Controller {

	function __construct()
	{
	parent::__construct();
//	$this->load->database();
    $this->load->model('Meta_etiquetas_model');
	
    
}
	public function index()
	{ 

		$consulta_meta_etiquetas = $this->Meta_etiqutas_model->listar_meta_etiquetas();
		$data['lista_meta_etiquetas'] = $consulta_meta_etiquetas;
print_r($consulta_meta_etiquetas);

die();
        $this->load->view('panel/header');
		$this->load->view('panel/Meta_etiquetas', $data);
		$this->load->view('panel/footer');



	}
	

	public function editar()
	{ 

		$id_meta = $_POST['id_meta'];

		$consulta_meta_etiquetas = $this->Meta_etiquetas_model->editar_meta_etiquetas($id_meta);
		print_r($consulta_meta_etiquetas);



    }

	public function actualizar()
	{ 

		$titulo = $_POST['titulo'];

		$descripcion = $_POST['descripcion'];

		$keywords = $_POST['keywords'];

		$id_meta = $_POST['id_meta'];


		$consulta_meta_etiquetas = $this->Meta_etiquetas_model->actualizar_meta_etiquetas($id_meta, $titulo, $descripcion, $keywords);
		print_r($consulta_meta_etiquetas);



    }


}