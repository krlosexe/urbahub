<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gestor_textos extends CI_Controller {


	function __construct()
	{
	parent::__construct();
	//$this->load->database();
    $this->load->model('Bl_textos_model');
    $this->load->model('Secciones_model');
    
	
    
}


public function listar_secciones(){
    $consulta_secciones = $this->Secciones_model->listar_secciones();

    print_r($consulta_secciones);


}

public function buscar_texto(){
    $id_seccion = $_POST['id_seccion'];

   $consulta_bl_texto = $this->Bl_textos_model->buscar_texto($id_seccion);

   print_r($consulta_bl_texto);

}

public function buscar_texto_id(){
    $id_texto = $_POST['id_texto'];

   $consulta_bl_texto = $this->Bl_textos_model->buscar_texto_id($id_texto);

   print_r($consulta_bl_texto);


}



public function actualizar_texto(){

   $titulo  = $_POST['titulo'];
   $contenido  = $_POST['contenido'];
   $id_texto  = $_POST['id_texto'];
   

   
   $consulta_bl_texto = $this->Bl_textos_model->actualizar_bl($id_texto, $titulo, $contenido);

   print_r($consulta_bl_texto);

}


	


    }