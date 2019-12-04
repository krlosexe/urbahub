<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Modulos_model extends CI_Model
{

    private $nombre_tabla = "modulo_vista";

    public function listar_modulos()
    {
        /*$this->db->where('a.tabla', $this->nombre_tabla);
        $this->db->select('mv.*, a.fec_regins, u.correo_usuario, a.status');
        $this->db->from($this->nombre_tabla . ' mv');
        $this->db->join('auditoria a', 'mv.id_modulo_vista = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //--Modificacion con Mongo db
        //---------------------------------------------------------------------------
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get($this->nombre_tabla);
        foreach ($resultados as $clave => $valor) {
        $auditoria = $valor["auditoria"][0];
        //var_dump($auditoria->cod_user);die('');
        //--usuario en cuestion
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
            //var_dump($res_us[0]["auditoria"]->status);die('');
            //var_dump(end($res_us[0]["auditoria"]));die('');
            $vector_auditoria = reset($valor["auditoria"]);
            $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            //$valor["fec_regins"] = $res_us[0]["auditoria"][0]->fecha->toDateTime();
            $valor["correo_usuario"] = $res_us[0]["correo_usuario"];
            $valor["status"] = $valor["status"];
            $valor["id_modulo_vista"] = $valor["_id"]->{'$id'};
            $listado[] = $valor;
        }    
        //--
        $listado2 = $listado;
        return $listado2;
        //---------------------------------------------------------------------------

    }   
        
    public function registrar_modulo($data){
        /*$this->db->insert($this->nombre_tabla, $data);
        $datos = array(
            'tabla' => $this->nombre_tabla,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);*/
        //---------------------------------------------
        //Migracion Mongo DB
        $insertar = $this->mongo_db->insert($this->nombre_tabla, $data);
            
        if ($insertar) {
            return true;
        }

        return false;
        //---------------------------------------------
    }

    public function actualizar_modulo($id, $data)
    {
        /*$this->db->where('id_modulo_vista', $id);
        $this->db->update($this->nombre_tabla, $data);
        $datos = array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);*/
        //----------------------------------------------------------------------
        //-Migracion MongoDB
        $id_modulo = new MongoDB\BSON\ObjectId($id);
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        $modificar1 = $this->mongo_db->where(array('_id'=>$id_modulo))->set($data)->update($this->nombre_tabla);
        if($modificar1){
        //--Auditoria
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar modulo',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_modulo))->push('auditoria',$data_auditoria)->update($this->nombre_tabla);
        }
        //----------------------------------------------------------------------
    }

    public function verificar_modulo($nombre_modulo_vista)
    {
        /*$this->db->where('nombre_modulo_vista', $nombre_modulo_vista);
        $this->db->limit(1);
        $resultados = $this->db->get($this->nombre_tabla);
        return $resultados->result_array();*/
        
        //-----------------------------------------------------------
        //Migracion Mongo DB
        $res_modulo = $this->mongo_db->where(array('nombre_modulo_vista' => $nombre_modulo_vista,'eliminado'=>false))->get($this->nombre_tabla);
        if($res_modulo){
            return $res_modulo;
        }else{
            return false;
        }
        //-----------------------------------------------------------
    }
    /*
    *   Consultar dependencia a otras tablas
    */
    public function consultar_dependencia($tabla,$filtro){
        $id = new MongoDB\BSON\ObjectId($filtro);
        $res = $this->mongo_db->where(array('eliminado'=>false,'id_modulo_vista'=>$id))->get($tabla);
        return count($res);
    }    
    /*
    *
    */

    public function eliminar_modulo($id)
    {
        $dependencia = $this->consultar_dependencia("lista_vista",$id);
        if($dependencia>0){
            echo "<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>";die('');
        }
        try { 

            /*if(!$this->db->delete($this->nombre_tabla, array('id_modulo_vista' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->order_by('posicion_modulo_vista', 'DESC');
                $resultados = $this->db->get($this->nombre_tabla);
                $contador = $resultados->num_rows();
                foreach($resultados->result() as $row)
                {
                    $datos = array(
                        'posicion_modulo_vista' => $contador,
                    );
                    $this->db->where('id_modulo_vista', $row->id_modulo_vista);
                    $this->db->update($this->nombre_tabla, $datos);
                    $contador--;
                }
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->nombre_tabla));
                echo json_encode("<span>El módulo se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }*/
        //--------------------------------------------------------------------------------
        //--Migracion con Mongo DB    
           /* $id_modulo = new MongoDB\BSON\ObjectId($id);
            $borrar = $this->mongo_db->delete($this->nombre_tabla,array('_id'=>$id_modulo));
            var_dump($borrar);die('');
            if(!$borrar){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{

            }*/
            //--Migracion Mongo DB
            $id = new MongoDB\BSON\ObjectId($id);

            $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
            $fecha = new MongoDB\BSON\UTCDateTime();
            
            $datos = array(
                                    'eliminado'=>true,
            );
            $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->nombre_tabla);
            //--Auditoria
            if($eliminar){
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar modulo',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->nombre_tabla); 
                

                //------------------
                //--Actaulizo sus posiciones
                $res_modulo = $this->mongo_db->order_by(array('posicion_modulo_vista' => 'DESC'))->get($this->nombre_tabla);
                $contador = count($res_modulo);
                foreach ($res_modulo as $row) {
                    
                    $id_modulo = new MongoDB\BSON\ObjectId($row["_id"]->{'$id'});

                    $datos = array(
                        'posicion_modulo_vista' => $contador,
                    );
                    $mod_pos = $this->mongo_db->where(array('_id'=>$id_modulo))->set($datos)->update($this->nombre_tabla);
                    $contador--;
                }
                //------------------
                echo json_encode("<span>El módulo se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso

            }
        //---------------------------------------------------------------------------
        //--------------------------------------------------------------------------------    
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function status_modulo($id, $status)
    {
        /*$datos = array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
        $this->db->update('auditoria', $datos);*/
        //---------------------------------------------------------------------------
        //--Migracion Mongo DB
        $id = new MongoDB\BSON\ObjectId($id);

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
         switch ($status) {
            case '1':
                $status2 = true;
                break;
            case '2':
                $status2 = false;
                break;
        }
        $datos = array(
                                'status'=>$status2,
        );
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->nombre_tabla);
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status modulo',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->nombre_tabla); 
        }
        //---------------------------------------------------------------------------
    }

    public function eliminar_multiple_modulos($id)
    {
       
        $eliminar ="";
        $eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        $datos = array(
                            'eliminado'=>true,
            );  
        foreach($id as $modulo)
        {
            $dependencia = $this->consultar_dependencia("lista_vista",$modulo);
            if($dependencia>0){
            //--------------------------
                $noEliminados++;
            //--------------------------    
            }else{ 
                $vector_eliminados[] = $modulo;
                $eliminados++;
            } 
        }      
        //----
        foreach ($vector_eliminados as $clave_pq => $valor_modulo) {
            $id = new MongoDB\BSON\ObjectId($valor_modulo);
            $eliminar = $this->mongo_db->where(array('_id'=>$id,"eliminado"=>false))->set($datos)->update("modulo_vista");
                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar modulos',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("modulo_vista");
                }  
        }    
        //----
       
        //----
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);   
         //-------------------------------------------------------------------------------- #Asi funcionaba el codigo antes...    
            /*if($dependencia>0){
                 $noEliminados++;
            }else{
            //--Migracion Mongo DB
                $id = new MongoDB\BSON\ObjectId($modulo);
                $datos = $data=array(
                                        'eliminado'=>true,
                );
              //array('_id'=>$id))->set($datos)->update($this->nombre_tabla);
                //--Auditoria
                if($eliminar){
                    $eliminados++;
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar modulo',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->nombre_tabla);
                }else{
                    $noEliminados++;
                }   
            //----------------------------------------------------------------------------------    
            }    
        }*/
        /*$this->db->order_by('posicion_modulo_vista', 'DESC');
        $resultados = $this->db->get($this->nombre_tabla);
        $contador = $resultados->num_rows();
        foreach($resultados->result() as $row)
        {
            $datos = array(
                'posicion_modulo_vista' => $contador,
            );
            $this->db->where('id_modulo_vista', $row->id_modulo_vista);
            $this->db->update($this->nombre_tabla, $datos);
            $contador--;
        }*/

        //-------------------------------------------------------------------------------------
        //Migracion mongo db
        /*$res_modulo = $this->mongo_db->order_by(array('posicion_modulo_vista' => 'DESC'))->get($this->nombre_tabla);
        $contador = count($res_modulo);
        foreach ($res_modulo as $row) {
            
            $id_modulo = new MongoDB\BSON\ObjectId($row["_id"]->{'$id'});

            $datos = array(
                'posicion_modulo_vista' => $contador,
            );
            $mod_pos = $this->mongo_db->where(array('_id'=>$id_modulo))->set($datos)->update($this->nombre_tabla);
        }
        //-------------------------------------------------------------------------------------
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        $contador--;*/
    }
    /***/
    public function orden_eliminar(){
         $res_modulo = $this->mongo_db->order_by(array('posicion_modulo_vista' => 'DESC'))->where(array("eliminado"=>false))->get("modulo_vista");

        $contador = count($res_modulo);

        //var_dump($contador);die('');
        foreach ($res_modulo as $row) {
            $id_modulo = new MongoDB\BSON\ObjectId($row["_id"]->{'$id'});

            $datos = array(
                'posicion_modulo_vista' => $contador,
            );
            $mod_pos = $this->mongo_db->where(array('_id'=>$id_modulo))->set($datos)->update("modulo_vista");
            $contador--;
        }
         echo json_encode($contador);
    }
    /***/
    public function status_multiple_modulos($id, $status)
    {
        /*$modulos = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $modulos . ") AND tabla='" . $this->nombre_tabla . "'");*/
        //---------------------------------------------------------------------------
        //--Migracion Mongo DB
        $arreglo_id = explode(' ',$id);
        foreach ($arreglo_id as $valor) {
            $id = new MongoDB\BSON\ObjectId($valor);
            //var_dump($id);die('');
            $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
            $fecha = new MongoDB\BSON\UTCDateTime();
            switch ($status) {
                case '1':
                    $status2 = true;
                    break;
                case '2':
                    $status2 = false;
                    break;
            }
            $datos = $data=array(
                                    'status'=>$status2,
            );
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->nombre_tabla);
            //var_dump($modificar);die('');
            //--Auditoria
            if($modificar){
                $data_auditoria = "";
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status modulo',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->nombre_tabla); 
            }
        }
        //---------------------------------------------------------------------------
        //---------------------------------------------------------------------------
    }

    public function posicionar_modulos($posicionar)
    {
        if($posicionar['tipo'] == 'insert')
        {
            /*$this->db->where('posicion_modulo_vista >= ' . $posicionar['posicion']);
            $resultados = $this->db->get($this->nombre_tabla);
            if($resultados->num_rows() > 0){
                foreach ($resultados->result() as $row)
                {
                    $datos=array(
                        'posicion_modulo_vista' => $row->posicion_modulo_vista + 1,
                    );
                    $this->db->where('id_modulo_vista', $row->id_modulo_vista);
                    $this->db->update($this->nombre_tabla, $datos);
                }
            }*/
            //-------------------------------------------------------------------------
            //--Migracion Mongo DB
                $resultado = $this->mongo_db->where_gte('posicion_modulo_vista', (int)$posicionar['posicion'])->where(array("eliminado"=>false))->get($this->nombre_tabla);
                //var_dump($resultado);die();
                if(count($resultado)>0){
                    foreach ($resultado as $key => $value) {
                        $datos=array(
                            'posicion_modulo_vista' => $value["posicion_modulo_vista"] + 1,
                        );
                        $id = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});

                        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->nombre_tabla);

                    }     
                }
            //-------------------------------------------------------------------------
        }
        else if($posicionar['tipo'] == 'update')
        {
            if($posicionar['final'] > $posicionar['inicial'])
            {
                /* $this->db->where('posicion_modulo_vista > ' . $posicionar['inicial'] . ' AND posicionar_modulos <= ' . $posicionar['final']);
                $resultados = $this->db->get($this->nombre_tabla);
                if($resultados->num_rows() > 0){
                    foreach ($resultados->result() as $row){
                        $datos=array(
                            'posicion_modulo_vista' => $row->posicion_modulo_vista - 1,
                        );
                        $this->db->where('id_modulo_vista', $row->id_modulo_vista);
                        $this->db->update($this->nombre_tabla, $datos);
                    }
                }*/
                //--Migracion Mongo DB
                $resultado = $this->mongo_db->where_gt('posicion_modulo_vista', (integer)$posicionar['inicial'])->where_lte('posicion_modulo_vista', (integer)$posicionar['final'])->where(array("eliminado"=>false))->get($this->nombre_tabla);
                //var_dump($resultado);die('');
                //--
            
                if(count($resultado)>0){       
                    foreach ($resultado as $key => $value) {
    
                        $datos=array(
                            'posicion_modulo_vista' => $value["posicion_modulo_vista"] - 1,
                        );

                        $id = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});

                        $modificar1 = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->nombre_tabla);
                    }
                }
            }
            else if($posicionar['final'] < $posicionar['inicial'])
            {
                 /*$this->db->where('posicion_modulo_vista >= ' . $posicionar['final'] . ' AND posicion_modulo_vista < ' . $posicionar['inicial']);
                $resultados = $this->db->get($this->nombre_tabla);
                if($resultados->num_rows() > 0){
                    foreach ($resultados->result() as $row)
                        /*$datos=array(
                            'posicion_modulo_vista' => $row->posicion_modulo_vista + 1,
                        );
                        $this->db->where('id_modulo_vista', $row->id_modulo_vista);
                        $this->db->update($this->nombre_tabla, $datos);
                }*/        

                //--Migracion Mongo DB
                $resultado = $this->mongo_db->where_gte('posicion_modulo_vista', $posicionar['final'])->where_lt('posicion_modulo_vista', $posicionar['inicial'])->where(array("eliminado"=>false))->get($this->nombre_tabla);
                //var_dump(count($resultado));die('');               
                if(count($resultado)>0){    
                    foreach ($resultado as $key => $value) {
                        
                        $datos=array(
                            'posicion_modulo_vista' => $value["posicion_modulo_vista"] + 1,
                        );

                        //var_dump($datos); 
                        $id = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});

                        $modificar1 = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->nombre_tabla);
                    }
                    //die('');
                }
            }
        }
    }

}