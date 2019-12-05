<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gestor_imagenes extends CI_Controller {


	function __construct()
	{
	parent::__construct();
//	$this->load->database();
	$this->load->model('Bl_imagenes_model');
	
    
}


public function buscar_imagenes(){
     $id_seccion = $_POST['id_seccion'];

    $consulta_bl_imagenes = $this->Bl_imagenes_model->buscar_imagenes($id_seccion);

    print_r($consulta_bl_imagenes);


}

public function buscar_imagen(){
    $id_imagen = $_POST['id_imagen'];

   $consulta_bl_imagenes = $this->Bl_imagenes_model->buscar_imagen($id_imagen);

   print_r($consulta_bl_imagenes);


}

public function actualizar_imagen(){
    $uploaddir = 'assets/img/biblioteca_imagenes/';
    $namefile = $_FILES["filenames"]['name'];
    $uploadfile = $uploaddir . basename($namefile);
    if($_FILES["filenames"]['name'] != ""){
        if (move_uploaded_file($_FILES["filenames"]['tmp_name'], $uploadfile)) {
        echo "1";
    } else {
        echo "0";
    }
    }else{
        echo "error";
    }
}


public function actualizar_etiqueta_imagen(){

   $etiqueta  = $_POST['etiqueta'];
   $id_imagen =   $_POST['id_imagen'];

   
   $consulta_bl_imagenes = $this->Bl_imagenes_model->actualizar_bl($id_imagen, $etiqueta);

   print_r($consulta_bl_imagenes);

}


	


    }