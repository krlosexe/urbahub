<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Bl_textos_model extends CI_Model
{


public function buscar_texto($id_seccion){

    
    $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
    $res = $this->mongo_db->where('seccion', $id_seccion);
    $res = $this->mongo_db->get('textos');
    return json_encode($res);


   //print_r("llego al modelo".$id_seccion);die();

  /*  $query = $this->db->query("SELECT * FROM tabla_textos where seccion = $id_seccion");

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

    
public function buscar_texto_id($id_texto){


    $id  = new MongoDB\BSON\ObjectId($id_texto);
    
    $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
    $res = $this->mongo_db->where('_id', $id);
    $res = $this->mongo_db->get('textos');
    return json_encode($res[0]);

    // print_r("llego al modelo".$id_seccion);die();
 
   /*  $query = $this->db->query("SELECT * FROM tabla_textos where id = $id_texto");
 
     $row = $query->row();
     
     if (isset($row))
     {
         
         return  json_encode($query->result()[0]);   
             
         }else {
             
             $mensaje = array('mensaje' => "error");
         return  json_encode($mensaje);   
             
         }
 */
     }


     public function actualizar_bl($id_texto,$titulo, $contenido){

        //print_r($id_texto);die();

$data = array("titulo_texto" =>$titulo,
        "contenido" =>$contenido );

 
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );       
        $id  = new MongoDB\BSON\ObjectId($id_texto);

 
        $this->mongo_db->where('_id', $id )->set($data)->update('textos');
        $mensaje = array('mensaje' => "SUCCESS");
        return  json_encode($mensaje);  


        // print_r("llego al modelo".$id_seccion);die();
     
       /*  $query = $this->db->query("UPDATE tabla_textos SET `titulo_texto` = '$titulo', `contenido` = '$contenido'  WHERE id = '$id_texto'");
         $mensaje = array('mensaje' => "SUCCESS");
        return  json_encode($mensaje);   
     */
         }
    

}



