<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Estadistica_model extends CI_Model
{


    public function consultar()
    {

        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
         
        print_r(json_encode($this->mongo_db->get('estadisticas')));

        /*
        $query = $this->db->query("SELECT * FROM estadisticas;");

        $row = $query->row();                
        if (isset($row))
        {
            $files[] = $query->result();
        }
       $json = json_encode($files);
        print_r($json);

        //print_r("culmino operacion");
        */
    }


    
    public function actualizar_data($campo)
    {


        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
       
        $this->mongo_db->inc(array($campo => 1))->update('estadisticas');



     /*   $query = $this->db->query("SELECT $campo FROM estadisticas;");

        $row = $query->row();                
        if (isset($row))
        {
            $data_actualizada =  $row->$campo + 1;
            $query = $this->db->query("UPDATE `estadisticas` SET $campo = $data_actualizada WHERE `estadisticas`.`id` = 1;");
        }else{
            print_r("campo no encontrado");
            die();
        }

        //print_r("culmino operacion");
        */
    }

    public function truncate_acceso()
    {
        $this->db->truncate('acceso');
        print_r("culmino operacion");
        
    }


    public function login($user,$passw)
    {
        $query = $this->db->query("SELECT usuario, contra FROM acceso;");

        $row = $query->row();
        
        if (isset($row))
        {

            if(($row->contra == $passw) && ($row->usuario == $user)){
                return  1;
            }else {
                return 2;
            }

        }
    }

    
	
	public function cambio_contraseña($pass_v,$pass_n){
        $query = $this->db->query("SELECT usuario, contra FROM acceso;");

        $row = $query->row();

        if (isset($row))
{

    if(($row->contra == $pass_v)){
        $query = $this->db->query("UPDATE acceso SET contra= $pass_n WHERE id = 1");
        return  1;
    }else {
        return 0;
    }

}
}
    public function reinicio_contraseña($pass_n){
    $query = $this->db->query("UPDATE acceso SET contra= '$pass_n' WHERE id = 1");
    return 1;
}


}