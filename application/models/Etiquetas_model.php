<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Etiquetas_model extends CI_Model
{
    // INSERTAR
    public function registrar_etiqueta($data){
        /*
        $this->db->insert('etiquetas', $data);
*/
        
        // GUARDAR ETIQUETA EN MONGO DB
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $res = $this->mongo_db->insert('etiquetas', $data);

        print_r(1);
    }
    // LISTAR
    public function listar_etiquetas(){


        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $res = $this->mongo_db->get('etiquetas');
        return json_encode($res);



  /*      
        $query = $this->db->query("SELECT * FROM etiquetas;");
        $row = $query->row();                
        if (isset($row))
        {
            $files[] = $query->result();
        }
       $json = json_encode($files);
        print_r($json);
*/
    }

    public function buscar_etiquetas($data){

        
        if($data != ""){
            $res = $this->mongo_db->like($field = "etiqueta", $value = $data, $flags = "i", $enable_start_wildcard = TRUE, $enable_end_wildcard = TRUE)->get('etiquetas');
            print_r(json_encode($res));
        }else{
          //  $res = $this->mongo_db->like($field = "tipo", $value = "Evento", $flags = "i", $enable_start_wildcard = TRUE, $enable_end_wildcard = TRUE)->get('entradas');print_r(json_encode($res));
        }
  /*      
        $query = $this->db->query("SELECT * from etiquetas where etiqueta like '%$data%'");
        $row = $query->row();                
        if (isset($row))
        {
            $files[] = $query->result();
        }else{
            $files[] = 0;
        }
       $json = json_encode($files);
        print_r($json);
*/
    }

    public function buscar_etiqueta($id){

        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $id = new MongoDB\BSON\ObjectId($id);
        $this->mongo_db->where('_id', $id);
        $res = $this->mongo_db->get('etiquetas');
        print_r(json_encode($res[0]));
  
  
  /*      $query = $this->db->query("SELECT * FROM `etiquetas` WHERE id = $id");
        $row = $query->row();                
        if (isset($row))
        {
            $files[] = $query->result();
        }else{
            $files[] = 0;
        }
       $json = json_encode($files);
        print_r($json);
*/
    }

    // ACTUALIZAR
    public function actualizar_etiqueta($id, $data){
     //   $query = $this->db->query("UPDATE etiquetas SET etiqueta = '$data' WHERE id = $id");

     $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
     $id = new MongoDB\BSON\ObjectId($id);
     $this->mongo_db->where('_id', $id)->set("etiqueta", $data)->update('etiquetas');;

        print_r(1);
    }

    // ELIMINAR

    
    public function eliminar_etiqueta($id){



        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $id = new MongoDB\BSON\ObjectId($id);
        $this->mongo_db->where('_id', $id);
        $res = $this->mongo_db->delete2('etiquetas');
       // print_r(json_encode($res[0]));
       print_r(1);

/*
        $query = $this->db->query("SELECT * FROM `union_etiquetas_entradas` WHERE id_etiqueta = $id");
        $row = $query->row();                
        if (isset($row))
        {
        print_r(0);
        }else{
            $query = $this->db->query("DELETE FROM `etiquetas` WHERE id = $id");
            print_r(1);
}
*/

        
    }
    




}