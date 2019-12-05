<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Meta_etiquetas_model extends CI_Model
{


public function listar_meta_etiquetas(){

    
    $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
    $res = $this->mongo_db->get('meta_etiquetas');

    return $res;

   // print_r("llego al modelo".$id_seccion);die();

  /*  $query = $this->db->query("SELECT * FROM meta_etiquetas");

    $row = $query->row();
    
    if (isset($row))
    {
        return $query->result();
    }
*/



}

public function editar_meta_etiquetas($id_meta){

    
    $id  = new MongoDB\BSON\ObjectId($id_meta);
    
    $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
    $res = $this->mongo_db->where('_id', $id);
    $res = $this->mongo_db->get('meta_etiquetas');
    return json_encode($res[0]);


    // print_r("llego al modelo".$id_seccion);die();
 /*
     $query = $this->db->query("SELECT * FROM meta_etiquetas where id = '$id_meta'");
 
     $row = $query->row();
     
     if (isset($row))
     {


$data = array(
"titulo" => $row->titulo,
"descripcion" => $row->descripcion,
"keywords" => $row->keywords,
"pagina" =>$row->pagina);

$this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
$res = $this->mongo_db->insert('meta_etiquetas', $data);
return $res;*/


//         return json_encode($query->result());
     }


     public function meta_etiquetas_web($n_pagina){
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $res = $this->mongo_db->where('pagina', "$n_pagina");
        $res = $this->mongo_db->get('meta_etiquetas');
        return $res;
         }
 
 
 
 
 

 


 public function actualizar_meta_etiquetas($id_meta, $titulo, $descripcion, $keywords){
    // print_r("llego al modelo".$id_seccion);die();
 

    
$data = array(
    "titulo" => $titulo,
    "descripcion" => $descripcion,
    "keywords" => $keywords);
    

$this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );       
$id  = new MongoDB\BSON\ObjectId($id_meta);


$this->mongo_db->where('_id', $id )->set($data)->update('meta_etiquetas');
$mensaje = array('mensaje' => "SUCCESS");
return  json_encode($mensaje);  


   /*  $query = $this->db->query("UPDATE meta_etiquetas SET `titulo` = '$titulo', `descripcion` = '$descripcion', `keywords` = '$keywords' WHERE id = '$id_meta'");
     $mensaje = array('mensaje' => "SUCCESS");
    return  json_encode($mensaje);   
 */
     }

}