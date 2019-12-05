<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Acceso_model extends CI_Model
{


    public function crear_usuario($user, $pass)
    {
        //$query = $this->db->query("INSERT INTO `acceso` (`usuario`, `contra`) VALUES ('admin', '123456');");

        $data = array(
            'usuario' => $user,
            'contra' => $pass
            );

        $this->db->insert('acceso',$data);

        print_r("culmino operacion");
        
    }

    public function truncate_acceso()
    {
        $this->db->truncate('acceso');
        print_r("culmino operacion");
        
    }


    public function login($user,$passw)
    {


        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $this->mongo_db->where('usuario', $user);
        $this->mongo_db->where('contra', $passw);
        $res = $this->mongo_db->count('acceso');
        if ($res == 1)
        {
            return 1;
        }else{
            return 2;
        }


        
/*
        $query = $this->db->query("SELECT usuario, contra FROM acceso;");

        $row = $query->row();
        
        if (isset($row))
        {

            if(($row->contra == $passw) && ($row->usuario == $user)){
                return  1;
            }else {
                return 2;
            }

        }*/
    }

    
	
	public function cambio_contraseña($pass_v,$pass_n){

        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $res = $this->mongo_db->where('contra', $pass_v )->count("acceso");
        //$this->mongo_db->where('contra', $pass_v )->set("contra", "$pass_n")->update('acceso');
        if($res == 0){
            return 0;
        }else{
            $this->mongo_db->where('contra', $pass_v )->set("contra", "$pass_n")->update('acceso');
            return 1;
        }



  /*      $query = $this->db->query("SELECT usuario, contra FROM acceso;");

        $row = $query->row();

        if (isset($row))
{

    if(($row->contra == $pass_v)){
        $query = $this->db->query("UPDATE acceso SET contra= $pass_n WHERE id = 1");
        return  1;
    }else {
        return 0;
    }

}*/
}


    public function reinicio_contraseña($pass_n){
  
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $this->mongo_db->where('usuario', "admin" )->set("contra", "$pass_n")->update('acceso');
        //  $query = $this->db->query("UPDATE acceso SET contra= '$pass_n' WHERE id = 1");
    return 1;
}


}