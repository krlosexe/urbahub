<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Secciones_model extends CI_Model
{


public function listar_secciones(){
   // print_r("llego al modelo".$id_seccion);die();

    $query = $this->db->query("SELECT * FROM secciones");

    $row = $query->row();
    
    if (isset($row))
    {
        
        return  json_encode($query->result());   
            
        }else {
            
            $mensaje = array('mensaje' => "error");
        return  json_encode($mensaje);   
            
        }

    }

    
public function buscar_imagen($id_imagen){
    // print_r("llego al modelo".$id_seccion);die();
 
     $query = $this->db->query("SELECT * FROM tabla_index where id = $id_imagen");
 
     $row = $query->row();
     
     if (isset($row))
     {
         
         return  json_encode($query->result());   
             
         }else {
             
             $mensaje = array('mensaje' => "error");
         return  json_encode($mensaje);   
             
         }
 
     }


     public function actualizar_bl($id_imagen, $etiqueta){
        // print_r("llego al modelo".$id_seccion);die();
     
         $query = $this->db->query("UPDATE tabla_index SET `etiqueta` = '$etiqueta' WHERE id = '$id_imagen'");
         $mensaje = array('mensaje' => "SUCCESS");
        return  json_encode($mensaje);   
     
         }
    

}



