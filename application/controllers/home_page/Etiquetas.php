<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Etiquetas extends CI_Controller {


	function __construct()
	{
	parent::__construct();
//	$this->load->database();
	$this->load->model('Etiquetas_model');
	
    
}
      
    public function crear_etiqueta(){
        
        $etiqueta = $_POST["etiqueta"];
        
        $datos = array(
			'etiqueta' => $etiqueta,
        );
        
		$operacion = $this->Etiquetas_model->registrar_etiqueta($datos);
		print_r($operacion);
    }

	public function actualizar_etiqueta(){
        $etiqueta = $_POST["etiqueta_editar"];
        $id = $_POST["id_etiqueta"];
		$operacion = $this->Etiquetas_model->actualizar_etiqueta($id, $etiqueta);
		print_r($operacion);
    }



    public function listar_etiquetas(){
        
		$operacion = $this->Etiquetas_model->listar_etiquetas();
		print_r($operacion);
	}
	

	public function buscar_etiquetas(){

        $etiqueta = $_POST["buscador"];
        
		$operacion = $this->Etiquetas_model->buscar_etiquetas($etiqueta);
		print_r($operacion);
	}

	
	public function buscar_etiqueta($id){
		$operacion = $this->Etiquetas_model->buscar_etiqueta($id);
		print_r($operacion);
	}
	
	public function eliminar_etiqueta($id){
		$operacion = $this->Etiquetas_model->eliminar_etiqueta($id);
		print_r($operacion);
	}
	


	


    }