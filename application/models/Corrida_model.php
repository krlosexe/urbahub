<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Corrida_model extends CI_Model {

	private $tabla_lval = "lval";
	
	public function getTipoCuotas()
    {
        $this->db->where('tipolval', 'TIPOCUOTA');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }


    

}

/* End of file Corrida_model.php */
/* Location: ./application/models/Corrida_model.php */
