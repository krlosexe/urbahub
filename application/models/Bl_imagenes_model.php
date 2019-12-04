<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Bl_imagenes_model extends CI_Model
{


public function buscar_imagenes($id_seccion){


    
    $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
    $res = $this->mongo_db->where('seccion', $id_seccion);
    $res = $this->mongo_db->get('imagenes');
    return json_encode($res);


   // print_r("llego al modelo".$id_seccion);die();
/*
    $query = $this->db->query("SELECT * FROM tabla_index where seccion = $id_seccion");

    $row = $query->row();
    
    if (isset($row))
    {
        
        return  json_encode($query->result());   
            
        }else {
            
            $mensaje = array('mensaje' => "error");
        return  json_encode($mensaje);   
            
        }
*/
    }

    
public function buscar_imagenes_web($id_seccion){


    
    $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
    $res = $this->mongo_db->where('seccion', $id_seccion);
    $res = $this->mongo_db->get('imagenes');
    return $res;
    }

    
public function buscar_imagen($id_imagen){


$id  = new MongoDB\BSON\ObjectId($id_imagen);
    
    $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
    $res = $this->mongo_db->where('_id', $id);
    $res = $this->mongo_db->get('imagenes');
    return json_encode($res);



    // print_r("llego al modelo".$id_seccion);die();
 
   /*  $query = $this->db->query("SELECT * FROM tabla_index where id = $id_imagen");
 
     $row = $query->row();
     
     if (isset($row))
     {
         
         return  json_encode($query->result());   
             
         }else {
             
             $mensaje = array('mensaje' => "error");
         return  json_encode($mensaje);   
             
         }
 */
     }

     


     public function actualizar_bl($id_imagen, $etiqueta){


        $id  = new MongoDB\BSON\ObjectId($id_imagen);

        $this->mongo_db->where('_id', $id )->set("etiqueta", $etiqueta)->update('imagenes');
        $mensaje = array('mensaje' => "SUCCESS");
        return  json_encode($mensaje);   



        // print_r("llego al modelo".$id_seccion);die();
     /*
         $query = $this->db->query("UPDATE tabla_index SET `etiqueta` = '$etiqueta' WHERE id = '$id_imagen'");
         $mensaje = array('mensaje' => "SUCCESS");
        return  json_encode($mensaje);   
     */
         }
    

}



