<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Entradas_model extends CI_Model
{


   
    public function ultimo_id_entrada(){



        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $cantidad = $this->mongo_db->count('entradas');

        $id = $cantidad +1;
        return $id;

        
    }


    public function registrar_entrada($data, $etiquetas){
        


   // GUARDAR ENTRADA EN MONGO DB
   $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
   $res = $this->mongo_db->insert('entradas', $data);
   $id_1 = json_decode(json_encode($res['_id']), True);
   $id = new MongoDB\BSON\ObjectId($id_1['$id']);
   foreach($etiquetas as $id_etiqueta){
    $this->mongo_db->where('_id', $id)->push('etiquetas_id', $id_etiqueta)->update('entradas');
    }


    print_r("data guardada");
    
    
     
    }


    public function actualizar_entrada($id, $datos, $etiquetas) {

        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $id = new MongoDB\BSON\ObjectId($id);
  
        $this->mongo_db->where('_id', $id)->set($datos)->update('entradas');
        $this->mongo_db->where('_id', $id)->set(array('etiquetas_id' => []))->update('entradas');

       $lista_etiquetas = $this->mongo_db->where('_id', $id)->get('entradas');

       $lista_e = $lista_etiquetas[0]['etiquetas_id'];

     //  foreach ($lista_e as $etiqueta_actial){


        foreach($etiquetas as $id_etiqueta){
            $this->mongo_db->where('_id', $id)->push('etiquetas_id', $id_etiqueta)->update('entradas');

           /* if ($id_etiqueta === $etiqueta_actial){
            print_r("no hace nda <br>");
            }else {
                {
                 //   $this->mongo_db->where('_id', $id)->push('etiquetas_id', $id_etiqueta)->update('entradas');
                    print_r("se agrega id <br>");
                }
            }*/

       
        }

    //   }
        print_r(1);
    }

    


    public function cargar_entrada($id){

        $id = new MongoDB\BSON\ObjectId($id);
  
       $res = $this->mongo_db->where('_id', $id)->get('entradas');

      if(isset($res[0]['etiquetas_id'])){

        $lista_etiquetas = [];
       foreach($res[0]['etiquetas_id'] as $id_etiqueta){
           $et = $this->mongo_db->where('_id', new MongoDB\BSON\ObjectId($id_etiqueta))->get('etiquetas');
             
            array_push($lista_etiquetas, $et[0] );
        }



        array_push($res, $lista_etiquetas);

      }else{
      
      }
    

      

       return $res;




    }

    public function actualizar_visitas_entrada($id, $visitas){
        $query = $this->db->query("UPDATE entradas SET visitas = $visitas WHERE id = $id");
    }
    
    public function actualizar_estado_entrada($id, $accion){

        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $id = new MongoDB\BSON\ObjectId($id);
        $this->mongo_db->where('_id', $id)->set("estado_visible", $accion)->update('entradas');

    }
    

    public function listar_eventos(){

        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $res = $this->mongo_db->get('entradas');
        print_r(json_encode($res));

    }

    
    public function listar_noticias(){
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $res = $this->mongo_db->get('entradas');
        print_r(json_encode($res));

    }

    public function cargar_entrada_panel($id){

        
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $id = new MongoDB\BSON\ObjectId($id);
        $this->mongo_db->where('_id', $id);
        $res = $this->mongo_db->get('entradas');
        print_r(json_encode($res[0]));
  
    }


    public function getEntradas(){  
        $query = $this->db->query("SELECT * FROM `union_etiquetas_entradas`
                                      INNER JOIN `entradas` 
                                    INNER JOIN `etiquetas` 
                                    ON
                                    union_etiquetas_entradas.id_entrada = entradas.id
                                    AND 
                                    union_etiquetas_entradas.id_etiqueta = etiquetas.id");
        $row = $query->row();                
        if (isset($row))
        {
            $files[] = $query->result();
        }
       $json = json_encode($files);
        print_r($json);
    }


    public function buscar_evento($data){
if($data != ""){
    $res = $this->mongo_db->likes($field = "titulo", $value = $data, $flags = "i", $enable_start_wildcard = TRUE, $enable_end_wildcard = TRUE)->get('entradas');
    print_r(json_encode($res));
}else{
    $res = $this->mongo_db->likes($field = "tipo", $value = "Evento", $flags = "i", $enable_start_wildcard = TRUE, $enable_end_wildcard = TRUE)->get('entradas');
    print_r(json_encode($res));
}
       
        


    }

    public function buscar_noticia($data){


        if($data != ""){
            $res = $this->mongo_db->likes($field = "titulo", $value = $data, $flags = "i", $enable_start_wildcard = TRUE, $enable_end_wildcard = TRUE)->get('entradas');
            print_r(json_encode($res));
        }else{
            $res = $this->mongo_db->likes($field = "tipo", $value = "Noticia", $flags = "i", $enable_start_wildcard = TRUE, $enable_end_wildcard = TRUE)->get('entradas');
            print_r(json_encode($res));
        }

    }

    public function buscar_titulo($data){

        $query = $this->db->query("SELECT * from entradas WHERE titulo like '%$data%';");
        $row = $query->row();                
        if (isset($row))
        {
            $files[] = $query->result();
        }else{
            $files[] = 0;
        }
       $json = json_encode($files);
        print_r($json);

    }


    public function todas_entradas(){

        $res = $this->mongo_db->get('entradas');
        return json_encode($res);

    }


    public function entradas_relacionadas($id_etiqueta, $id){


        $id = new MongoDB\BSON\ObjectId($id);


        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $this->mongo_db->select(array('titulo', 'imagen', 'visitas'));
       $this->mongo_db->where('etiquetas_id', "$id_etiqueta");
       $this->mongo_db->where_not_in('_id', array("$id_etiqueta"));
       $res = $this->mongo_db->get('entradas');
       return $res;


       
    }

    
    public function entradas_relacionadas_a($etiqueta){



        
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
       // $this->mongo_db->select(array('titulo', 'imagen', 'visitas'));
       $this->mongo_db->where('etiquetas_id', "$etiqueta");
      // $this->mongo_db->where_not_in('_id', array("$id_etiqueta"));
       $res = $this->mongo_db->get('entradas');
       return $res;




       
    }


    public function conteo_e(){


        
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $this->mongo_db->where('tipo', "Evento");
        $res = $this->mongo_db->count('entradas');
        return $res;
             
    }

    
    public function conteo_n(){

        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $this->mongo_db->where('tipo', "Noticia");
        $res = $this->mongo_db->count('entradas');
        return $res;


                   
    }

    public function conteo_o(){
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $this->mongo_db->where('estado_visible', "0");
        $res = $this->mongo_db->count('entradas');
        return $res;
            
    }


    public function cambio_correo($pass_v,$correo){
      
        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $res = $this->mongo_db->where('contra', $pass_v )->count("acceso");
        if($res == 0){
            return 0;
        }else{
            $this->mongo_db->where('contra', $pass_v )->set("correo", "$correo")->update('acceso');
            return 1;
        }
      

    }

    public function correo_admin(){

        $this->load->library('mongo_db', array ('Activate' => 'default' ), 'mongo_db' );
        $res = $this->mongo_db->get("acceso");

        return $res[0]["correo"];

    }

}

