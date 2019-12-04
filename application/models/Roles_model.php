<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Roles_model extends CI_Model
{

    private $tabla_rol = "rol";
    private $tabla_lista_vista = "lista_vista";
    private $tabla_rol_operaciones = "rol_operaciones";
    private $tabla_modulos = "modulo_vista";

    public function listado_roles()
    {
        /*$roles = array();
        $this->db->where('a.tabla', $this->tabla_rol);
        $this->db->group_by('r.nombre_rol');
        $this->db->select('r.*, a.fec_regins, u.correo_usuario, a.status');
        $this->db->from($this->tabla_rol . ' r');
        $this->db->join('auditoria a', 'r.id_rol = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $resultados = $this->db->get();
        foreach ($resultados->result() as $rol) {
            $this->db->where('ro.id_rol', $rol->id_rol);
            $this->db->select('GROUP_CONCAT(lv.nombre_lista_vista SEPARATOR " - ") AS nombre_lista_vista');
            $this->db->from($this->tabla_rol_operaciones . ' ro');
            $this->db->join($this->tabla_lista_vista . ' lv', 'ro.id_lista_vista = lv.id_lista_vista');
            $query = $this->db->get();
            $array = array(
                'id_rol' => $rol->id_rol,
                'nombre_rol' => $rol->nombre_rol,
                'descripcion_rol' => $rol->descripcion_rol,
                'editable_rol' => $rol->editable_rol,
                'nombre_lista_vista' => $query->result(),
                'fec_regins' => $rol->fec_regins,
                'correo_usuario' => $rol->correo_usuario,
                'status' => $rol->status,
            );
            array_push($roles, $array);
        }
        return $roles;*/
        //----------------------------------------------------------------------------
        //Migracion MONGO DB
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get($this->tabla_rol);
        $listado = array();
        foreach ($resultados as $clave => $valor) {

            //if($valor["nombre_rol"]!="ADMINISTRADOR"){
                //----------------------------------------------------------------------
                $nombre_lista_vista = "";
                
                //--Id Rol
                $id_rol = new MongoDB\BSON\ObjectId($valor["_id"]->{'$id'});
               
                //--usuario en cuestion
                $id_auditoria = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
                //--Para usuarios
                $res_us = $this->mongo_db->where(array('_id'=>$id_auditoria))->get('usuario');
                //consultamos rol de operaciones
                $res_rol_op= $this->mongo_db->where(array('eliminado'=>false,'id_rol'=>$id_rol))->get($this->tabla_rol_operaciones);

                //var_dump(count($res_rol_op));die('');
                $co = 0;
                foreach ($res_rol_op as $clave1 => $valor1) {
                    //--Id Lista Vista
                    $id_lista_vista = new MongoDB\BSON\ObjectId($valor1["id_lista_vista"]);
                    //Para nombres de lista vistas
                    $res_lista_vista = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_lista_vista))->get($this->tabla_lista_vista);
                    
                    
                    foreach ($res_lista_vista as $clave2 => $valor2) {

                        //var_dump($valor2["nombre_lista_vista"]);die('');
                        ($co>0)? $nombre_lista_vista.= " - ".$valor2["nombre_lista_vista"]: $nombre_lista_vista.= $valor2["nombre_lista_vista"];
                        
                        $co++;
                    }

                }
                //--
                //$valor["fec_regins"] = $valor["auditoria"][0]->fecha->toDateTime();
                $vector_auditoria = reset($valor["auditoria"]);
                $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();
                $valor["correo_usuario"] = $res_us[0]["correo_usuario"];
                $valor["status"] = $valor["status"];
                $valor["nombre_lista_vista"] = $nombre_lista_vista;
                $valor["id_rol"] = $valor["_id"]->{'$id'};
                //$valor["id_lista_vista_string"] = $valor["id_lista_vista"];

                $co = 0;
                
                $listado[] = $valor;
                //----------------------------------------------------------------------
            //}
            
        }    
        return $listado;
        //----------------------------------------------------------------------------
        /* foreach ($resultados->result() as $rol) {
            $this->db->where('ro.id_rol', $rol->id_rol);
            $this->db->select('GROUP_CONCAT(lv.nombre_lista_vista SEPARATOR " - ") AS nombre_lista_vista');
            $this->db->from($this->tabla_rol_operaciones . ' ro');
            $this->db->join($this->tabla_lista_vista . ' lv', 'ro.id_lista_vista = lv.id_lista_vista');
            $query = $this->db->get();
            $array = array(
                'id_rol' => $rol->id_rol,
                'nombre_rol' => $rol->nombre_rol,
                'descripcion_rol' => $rol->descripcion_rol,
                'editable_rol' => $rol->editable_rol,
                'nombre_lista_vista' => $query->result(),
                'fec_regins' => $rol->fec_regins,
                'correo_usuario' => $rol->correo_usuario,
                'status' => $rol->status,
            );
            array_push($roles, $array);
        }
        return $roles*/
    }

    public function modulos()
    {
        /*$this->db->where('a.tabla', $this->tabla_modulos);
        $this->db->where('a.status', 1);
        $this->db->select('*');
        $this->db->from($this->tabla_modulos. " mv");
        $this->db->join('auditoria a', 'mv.id_modulo_vista = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //----------------------------------------------------------------
        //Migracion MONGO DB
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'status'=>true))->get($this->tabla_modulos);
        return $resultados;
        //----------------------------------------------------------------
    }

    public function buscarListaVista($id_modulo)
    {
        /*$this->db->where('lv.id_modulo_vista', $id_modulo);
        $this->db->where('a.tabla', $this->tabla_lista_vista);
        $this->db->where('a.status', 1);
        $this->db->select('lv.id_lista_vista, lv.nombre_lista_vista');
        $this->db->from($this->tabla_lista_vista. " lv");
        $this->db->join('auditoria a', 'lv.id_lista_vista = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //Migracion MONGO DB
        $id =  new MongoDB\BSON\ObjectId($id_modulo);
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'id_modulo_vista'=>$id))->get($this->tabla_lista_vista);
        foreach ($resultados as $clave => $valor) {
            $arr["id_lista_vista"] = $valor["_id"]->{'$id'};
            $arr["nombre_lista_vista"] = $valor["nombre_lista_vista"];
            $listado[ ] = $arr;
        }
        return $listado;
        //----------------------------------------------------------------
    }
    /*
    *   verificar_existe_roles
    */
    public function verificar_existe_roles($nombre,$descripcion,$id){
        //---
        //--Verificacion al guardar
        $res = $this->mongo_db->where(array("nombre_rol"=>$nombre,"descripcion_rol"=>$descripcion,"eliminado"=>false))->get($this->tabla_rol);
        
        if($res){
            if($id!=""){
                $id_roles = $res[0]["_id"]->{'$id'};
                if($id==$id_roles){
                    return 0;
                }else{
                    return count($res);
                } 
            }else{
                return count($res);
            } 
        }else{
            return 0;
        }
        //---
    }   
    /*
    *
    */
    public function registrar_rol($data, $permisos){
        /*$this->db->insert($this->tabla_rol, $data);
        $rol = $this->db->insert_id();
        $datos=array(
            'tabla' => $this->tabla_rol,
            'cod_reg' => $rol,
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
        if (sizeof($permisos) > 0){
            foreach($permisos as $permiso)
            {
                $array = array(
                    'id_rol' => $rol,
                    'id_lista_vista' => $permiso[0],
                    'general' => $permiso[1],
                    'detallada' => $permiso[2],
                    'registrar' => $permiso[3],
                    'actualizar' => $permiso[4],
                    'eliminar' => $permiso[5],
                );
                $this->db->insert($this->tabla_rol_operaciones, $array);
            }
        }*/
        //------------------------------------------------------------------
        //--MIGRACION MONGO DB
        $fecha = new MongoDB\BSON\UTCDateTime();
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $insertar = $this->mongo_db->insert($this->tabla_rol, $data);
        if ($insertar) {
            //--Consultar ultimo id....
            $res_modulo = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_rol);
            $id_rol = new MongoDB\BSON\ObjectId($res_modulo[0]["_id"]->{'$id'});
            //--
            if (sizeof($permisos) > 0){
                foreach($permisos as $permiso){
                    
                    $id_lista_vista = new MongoDB\BSON\ObjectId($permiso[0]);
                    
                    $array = array(
                        'id_rol' => $id_rol,
                        'id_lista_vista' => $id_lista_vista,
                        'general' => $permiso[1],
                        'detallada' => $permiso[2],
                        'registrar' => $permiso[3],
                        'actualizar' => $permiso[4],
                        'eliminar' => $permiso[5],
                        'status' => true,
                        'eliminado' => false,
                        'auditoria' => [array(
                                  "cod_user" => $id_usuario,
                                  "nomuser" => $this->session->userdata('nombre'),
                                  "fecha" => $fecha,
                                  "accion" => "Nuevo registro rol",
                                  "operacion" => ""
                              )]
                    );
                    
                    $insertar2 = $this->mongo_db->insert($this->tabla_rol_operaciones, $array);
                }
            }
        }
        //------------------------------------------------------------------
    }
    /***/
    public function actualizar_rol_ind($id,$rol){

        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_rol2 =  new MongoDB\BSON\ObjectId($id);

        $mod_rol = $this->mongo_db->where(array('_id'=>$id_rol2))->set($rol)->update($this->tabla_rol);
        //var_dump($mod_rol);die('');
        //--Auditoria
        if($mod_rol){
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar rol',
                                            'operacion'=>''
                                        );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_rol2))->push('auditoria',$data_auditoria)->update($this->tabla_rol); 
        }
    }
    /***/
    public function actualizar_rol($id, $rol, $permisos)
    {
        /*$this->db->where('id_rol', $id);
        $this->db->update($this->tabla_rol, $rol);
        $datos=array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_rol);
        $this->db->update('auditoria', $datos);
        foreach($permisos as $permiso)
        {
            $query = $this->db->query("SELECT * FROM ".$this->tabla_rol_operaciones." WHERE id_rol=".$id." AND id_lista_vista=".$permiso[0]);
            if (sizeof($query->result()) > 0) {
                $array = array(
                    'general' => $permiso[1],
                    'detallada' => $permiso[2],
                    'registrar' => $permiso[3],
                    'actualizar' => $permiso[4],
                    'eliminar' => $permiso[5],
                );
                $this->db->where('id_rol', $id)->where('id_lista_vista', $permiso[0]);
                $this->db->update($this->tabla_rol_operaciones, $array);
            } else {
                $array = array(
                    'id_rol' => $id,
                    'id_lista_vista' => $permiso[0],
                    'general' => $permiso[1],
                    'detallada' => $permiso[2],
                    'registrar' => $permiso[3],
                    'actualizar' => $permiso[4],
                    'eliminar' => $permiso[5],
                );
                $this->db->insert($this->tabla_rol_operaciones, $array);
            }
        }*/
        //---------------------------------------------------------------------------
        //--Migracion MONGO DB
        
        //--Actualizo rol
        $id_rol =  new MongoDB\BSON\ObjectId($id);

        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        //--Actualizo permisos : rol_operaciones
        if(count($permisos)>0){
            //-----------------------------------
            foreach($permisos as $permiso){
                $id_lista_vista = new MongoDB\BSON\ObjectId($permiso[0]);
                $resultados = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'id_rol'=>$id_rol,'id_lista_vista'=>$id_lista_vista))->get($this->tabla_rol_operaciones);
                if(count($resultados)>0){
                    $array = array(
                        'general' => $permiso[1],
                        'detallada' => $permiso[2],
                        'registrar' => $permiso[3],
                        'actualizar' => $permiso[4],
                        'eliminar' => $permiso[5],
                    );
                    $mod_rol_op = $this->mongo_db->where(array('id_rol'=>$id_rol,'id_lista_vista'=>$id_lista_vista))->set($array)->update($this->tabla_rol_operaciones);
                    //var_dump($mod_rol_op);die('');
                    //--Auditoria
                    /*if($mod_rol_op){
                        $data_auditoria1 = array(
                                                        'cod_user'=>$id_usuario,
                                                        'nom_user'=>$this->session->userdata('nombre'),
                                                        'fecha'=>$fecha,
                                                        'accion'=>'Modificar rol operaciones',
                                                        'operacion'=>''
                                                    );
                        $mod_auditoria1 = $this->mongo_db->where(array('_id'=>$id_rol))->push('auditoria',$data_auditoria1)->update($this->tabla_rol); */
                } else {
                    $array = array(
                        'id_rol' => $id_rol,
                        'id_lista_vista' => $id_lista_vista,
                        'general' => $permiso[1],
                        'detallada' => $permiso[2],
                        'registrar' => $permiso[3],
                        'actualizar' => $permiso[4],
                        'eliminar' => $permiso[5],
                        'status' => true,
                        'eliminado' => false,
                        'auditoria' => [array(
                                      "cod_user" => $id_usuario,
                                      "nomuser" => $this->session->userdata('nombre'),
                                      "fecha" => $fecha,
                                      "accion" => "Nuevo registro rol operaciones",
                                      "operacion" => ""
                                  )]
                    );
                    //$this->db->insert($this->tabla_rol_operaciones, $array);
                    $insertar = $this->mongo_db->insert($this->tabla_rol_operaciones, $array);
                }
            }
            //-----------------------------------
        }
        //---------------------------------------------------------------------------
        /*$id_rol2 =  new MongoDB\BSON\ObjectId($id);
        $mod_rol = $this->mongo_db->where(array('_id'=>$id_rol2))->set($rol)->update($this->tabla_rol);
        var_dump($mod_rol);die('');
        //--Auditoria
        if($mod_rol){
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar rol',
                                            'operacion'=>''
                                        );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_rol))->push('auditoria',$data_auditoria)->update($this->tabla_rol); 
        }*/
    }
    /*
    *   Consultar dependencia a otras tablas
    */
    public function consultar_dependencia($tabla,$filtro){
        //$id = new MongoDB\BSON\ObjectId($filtro);
        $res = $this->mongo_db->where(array('eliminado'=>false,'id_rol'=>$filtro))->get($tabla);
        return count($res);
    }    
    /*
    *
    */
    public function eliminar_rol($id)
    {
        /*try { 
            if(!$this->db->delete($this->tabla_rol, array('id_rol' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_rol));
                echo json_encode("<span>El rol se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }*/
        //------------------------------------------------------------
        //Migracion MONGO DB
        
        $dependencia = $this->consultar_dependencia("usuario",$id);
        if($dependencia>0){
            echo "<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>";die('');
        }

        $id = new MongoDB\BSON\ObjectId($id);

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $datos = array(
                                    'eliminado'=>true,
                );
        
        
        $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_rol);
        //$eliminar3 = $this->mongo_db->where(array('id_rol'=>$id))->set($datos)->update("rol_operaciones");
        //--Auditoria
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar modulo',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_rol); 
            echo json_encode("<span>El rol se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso

        }
        //------------------------------------------------------------
    }
    /*
    *   Eliminar rol de operacion
    */
    public function eliminar_rol_operacion_individual($id){
        //Migracion MONGO DB

        $id = new MongoDB\BSON\ObjectId($id);

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $datos = array(
                                    'eliminado'=>true,
                );
        
        //$eliminar = $this->mongo_db->where(array('id_rol'=>$id))->set($datos)->update($this->tabla_rol_operaciones);
        $res_roles = $this->mongo_db->where(array('id_rol'=>$id))->get($this->tabla_rol_operaciones);
        foreach ($res_roles as $valor) {
            $id_rol_op = new MongoDB\BSON\ObjectId($valor["_id"]->{'$id'});
            $eliminar = $this->mongo_db->where(array('_id'=>$id_rol_op))->set($datos)->update($this->tabla_rol_operaciones); 
            //--------------------------------------------------------------
            if($eliminar){
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar rol',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_rol); 
                

            }
            //------------------------------------------------------------

        }
        echo json_encode("<span>El rol se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
    }
    /*
    *
    */
    public function eliminar_rol_operacion_multiple($id_rol){
        foreach($id_rol as $rol){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            if($rol!=""){
                 $id = new MongoDB\BSON\ObjectId($rol);
                $datos = $data=array(
                                        'eliminado'=>true,
                );
               
                $eliminar_rol_operacion = $this->mongo_db->where(array('id_rol'=>$id))->set($datos)->update($this->tabla_rol_operaciones);
            }
        }
        echo json_encode("<span>El rol se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
        //----------------------------------------------------------------------------------
    }    
    public function status_rol($id, $status)
    {
        /*$datos = array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_rol);
        $this->db->update('auditoria', $datos);*/
        //------------------------------------------------------------
        //Migracion MONGO DB
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
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_rol);
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status rol',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_rol); 
        }
        //------------------------------------------------------------
    }

    public function eliminar_multiple_roles($id_rol)
    {
        //---------------------------------------------------------------------------
        #Modificacion de mihgracion mongo db mayo 2019
        $eliminar ="";
        $eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        $datos = array(
                            'eliminado'=>true,
        );  
        foreach($id_rol as $rol)
        {
            $dependencia = $this->consultar_dependencia("usuario",$rol);
            if($dependencia>0){
            //--------------------------
                $noEliminados++;
            //--------------------------    
            }else{ 
                $vector_eliminados[] = $rol;
                $eliminados++;
                $vector_id.= $rol."*";
            } 
        }      
        //----
        foreach ($vector_eliminados as $clave_pq => $valor_modulo) {
            $id = new MongoDB\BSON\ObjectId($valor_modulo);
            $eliminar = $this->mongo_db->where(array('_id'=>$id,"eliminado"=>false))->set($datos)->update($this->tabla_rol);
                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar rol',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_rol);
                }   
        }           
        //----
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados."|".$vector_id); 
        //----------------------------------------------------------------------------
        #Migracion anterior Mongo DB 
        /*$eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        $vector_id = "";
        foreach($id_rol as $rol){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            $dependencia = $this->consultar_dependencia("usuario",$rol);
            if($dependencia>0){
                 $noEliminados++;
            }else{
                //-------------------------------------------------------------------------------
                $id = new MongoDB\BSON\ObjectId($rol);
                $datos = $data=array(
                                        'eliminado'=>true,
                );
                $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_rol);

                //--Auditoria
                if($eliminar){
                    $vector_id.= $rol."*";
                    $eliminados++;
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar modulo',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_rol);
                }else{
                    $noEliminados++;
                }
                //-------------------------------------------------------------------------------
            }       
            //----------------------------------------------------------------------------------    

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados."|".$vector_id);*/
        //----------------------------------------------------------------------------
    }

    public function status_multiple_roles($id, $status)
    {
        /*$roles = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $roles . ") AND tabla='" . $this->tabla_rol . "'");*/
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
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_rol);
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
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_rol); 
            }
        }
        //---------------------------------------------------------------------------
    }

    public function operaciones_rol($id_rol)
    {
        /*$this->db->where('ro.id_rol', $id_rol);
        $this->db->select('ro.*, lv.nombre_lista_vista, mv.nombre_modulo_vista, mv.id_modulo_vista');
        $this->db->from($this->tabla_rol_operaciones . ' ro');
        $this->db->join($this->tabla_lista_vista . ' lv', 'ro.id_lista_vista = lv.id_lista_vista');
        $this->db->join($this->tabla_modulos . ' mv', 'lv.id_modulo_vista = mv.id_modulo_vista');
        $this->db->order_by('mv.id_modulo_vista', 'ASC');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //---------------------------------------------------------------------------------------
        //Migracion MONGO DB
        $id =  new MongoDB\BSON\ObjectId($id_rol);
        //Consulto rol de operaciones
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'id_rol'=>$id))->get($this->tabla_rol_operaciones);
        //Consulto tabla lista vista
        $listado = [];
        foreach ($resultados as $clave => $valor) {
            $id_lista_vista = new MongoDB\BSON\ObjectId($valor["id_lista_vista"]);
            $res_lista_vista = $this->mongo_db->where(array('_id'=>$id_lista_vista))->get($this->tabla_lista_vista);
            $valor["id_lista_vista"] = (string)$valor["id_lista_vista"];
            $valor["id_rol_operaciones"] = (string)$valor["_id"]->{'$id'};
            foreach ($res_lista_vista as $clave2 => $valor2) {
                $valor["nombre_lista_vista"] =  $valor2["nombre_lista_vista"];
                //Consulto tabla modulos
                $id_modulo_vista = new MongoDB\BSON\ObjectId($valor2["id_modulo_vista"]);
                $res_modulo_vista = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('_id'=>$id_modulo_vista))->get($this->tabla_modulos);
                foreach ($res_modulo_vista as $clave3 => $valor3) {
                    $valor["nombre_modulo_vista"] = $valor3["nombre_modulo_vista"];
                    $valor["id_modulo_vista"] = $valor3["_id"]->{'$id'};
                }
            }
            $listado[] = $valor;

        }
        return $listado;
        //---------------------------------------------------------------------------------------
    }

    public function eliminar_rol_operacion($id_rol_op)
    {
        
        //------------------------------------------------------------
        //Migracion MONGO DB
        $id = new MongoDB\BSON\ObjectId($id_rol_op);

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $datos = array(
                                    'eliminado'=>true,
                );
        $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_rol_operaciones);
        //--Auditoria
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar modulo',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_rol_operaciones); 
            echo json_encode("<span>¡El registro se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso

        }
        //------------------------------------------------------------
    }

}