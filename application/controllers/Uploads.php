<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploads extends CI_Controller {

	function __construct()
    {
        parent::__construct();
  		header('Content-Type: application/json');
    }

	public function index()
	{
		$this->upload();
	}

	public function delete()
	{
		
		$data = array();
		
		echo json_encode($data);

		
	}
	public function upload($tipo)
	{

		if($tipo == "cliente"){// se debe agregar diferentes rutas donde quiera guardar el archivo
			$ruta = 'assets/cpanel/ClientePagador/images';
		}else if($tipo == "proyecto"){
			$ruta ='assets/cpanel/Proyectos/planos/';
		}else if ($tipo == "productos"){
			$ruta = 'assets/cpanel/Productos/planos/';
		}
		$form = $this->input->post();

		$upload = array('success'=>false);

		$config['upload_path']          = 	sys_get_temp_dir();
        $config['allowed_types']        = 	'gif|jpg|jpeg|png|txt|pdf|xml|csv|doc|';
        $config['max_size']             = 	5000;
        $config['encrypt_name']			= 	false;
        $config['remove_spaces']		=	false;
        $this->load->library('upload', $config);
        
      
        if($this->upload->do_upload('file_data')){
	        $uploadData = $this->upload->data();

	        $upload['error'] = '';
	        $upload['append'] = true;
	        $upload['initialPreviewConfig'][0] = array(
	        	'caption'	=>	$uploadData['file_name'],
	        	'key'		=>	$uploadData['file_name'],
	        	'size'		=>	$uploadData['file_size'],
	        	'url'		=>	base_url().'uploads/delete'
	        );
	        $urlImg = base_url().$ruta."/".$uploadData['file_name'];
	        // $upload['initialPreview'] = array(
	        // 	'<img src="'.$urlImg.'" class="file-preview-image kv-preview-data">
	        // 	<input name="'.$form['name'].'" value="'.$uploadData['file_name'].'" type="hidden">
	        // 	'
	        // );
	        $upload['key'] = $uploadData['file_name'];
        }else{
        	$upload['error'] = $this->upload->display_errors();
        }

       echo json_encode($upload);

	 
        
	}

	
}
