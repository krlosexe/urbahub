<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ws_entradas extends CI_Controller {

    function __construct($config = 'rest')
	{
		header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    parent::__construct();
	
//	$this->load->database();
	$this->load->model('Entradas_model');
	  }

	public function index()
	{

	}

	public function listar_entradas(){
		$operacion = $this->Entradas_model->todas_entradas();
		print_r($operacion);
	}
}