<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");*/
class ServicioUrbanhub extends CI_Controller
{
    private $operaciones;
  	function __construct()
    {
        parent::__construct();
        $this->load->model('Planes_model');
        //--
        $this->load->helper('consumir_rest');
        $this->load->helper('organizar_sepomex');
        $this->load->helper('array_push_assoc');
        //--   
    }

    public function index(){
        echo "Hola!";
    }

    public function consumir_planes(){
        $listado = $this->Planes_model->listado_planes_servicio(); 
        header('Content-type:application/json;charset=utf-8');
        echo json_encode($listado,JSON_PRETTY_PRINT); 
    }
}  