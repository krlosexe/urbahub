<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Banner_model extends CI_Model
{


    public function crear_banner($data){
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $res = $this->mongo_db->insert('banners', $data);
        if ($res)
        {
            return 1;
        }else{
            return 2;
        }
        
    }

    public function list(){
        //$this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $res = $this->mongo_db->get('banners');
    
        return $res;
    }

    public function lista(){
        //$this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $this->mongo_db->where('visible', "1");
        $res = $this->mongo_db->get('banners');
        return $res;
    }

    public function listar_un_banner($id){
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $id_final = new MongoDB\BSON\ObjectId($id);
               $this->mongo_db->where('_id', $id_final);        
        $res = $this->mongo_db->get('banners');
        return $res;
    }

    public function actualizar_estado_banner($id, $accion){

        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $id = new MongoDB\BSON\ObjectId($id);
        $this->mongo_db->where('_id', $id)->set("visible", $accion)->update('banners');

    }


    public function actualizar_banner($id, $data){
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $id = new MongoDB\BSON\ObjectId($id);
        $this->mongo_db->where('_id', $id)->set($data)->update('banners');

    }

    public function crear_beneficio($data){
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $res = $this->mongo_db->insert('beneficiowp', $data);
        if ($res)
        {
            return 1;
        }else{
            return 2;
        }
        
    }
    public function lista_b_a(){
        //$this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $this->mongo_db->where('visible', "1");
        $res = $this->mongo_db->get('beneficiowp');
        return $res;
    }
    
    public function lista_b(){
        //$this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $res = $this->mongo_db->get('beneficiowp');
        return $res;
    }

    public function actualizar_estado_beneficio($id, $accion){

        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $id = new MongoDB\BSON\ObjectId($id);
        $this->mongo_db->where('_id', $id)->set("visible", $accion)->update('beneficiowp');

    }

    public function listar_un_beneficio($id){
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $id_final = new MongoDB\BSON\ObjectId($id);
               $this->mongo_db->where('_id', $id_final);        
        $res = $this->mongo_db->get('beneficiowp');
        return $res;
    }

    public function actualizar_beneficio($id, $data){
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $id = new MongoDB\BSON\ObjectId($id);
        $this->mongo_db->where('_id', $id)->set($data)->update('beneficiowp');

    }

}