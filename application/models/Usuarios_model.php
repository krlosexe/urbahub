<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Usuarios_model extends CI_Model
{

    private $tabla_usuario = "usuario";
    private $tabla_lval = "lval";
    private $tabla_roles = "rol";
    private $tabla_contacto = "contacto";
    private $tabla_personal = "datos_personales";

    public function listar_usuarios($id_usuario = null, $correo_usuario = null)
    {
        /*$this->db->where('a.tabla', $this->tabla_usuario);
        if(!empty($id_usuario)){
            $this->db->where('u.id_usuario', $id_usuario);
        }
        elseif(!empty($correo_usuario)){
            $this->db->where('u.correo_usuario', $correo_usuario);
        }

        $this->db->select('u.id_usuario, u.id_rol, u.correo_usuario, u.avatar_usuario, u.fec_ult_acceso_usuario, a.status, a.fec_regins, r.nombre_rol, dt.*, c.*,ur.correo_usuario as user_regis,c.id_codigo_postal');
        $this->db->from($this->tabla_usuario . ' u');
        $this->db->join('auditoria a', 'u.id_usuario = a.cod_reg');
        $this->db->join('usuario ur', 'ur.id_usuario = a.usr_regins');
        $this->db->join($this->tabla_roles . ' r', 'u.id_rol = r.id_rol');
        $this->db->join($this->tabla_personal . ' dt', 'u.id_usuario = dt.id_usuario');
        $this->db->join($this->tabla_contacto . ' c', 'dt.id_contacto = c.id_contacto');

        $resultados = $this->db->get();
        return $resultados->result();*/
        //-------------------------------------------------------------------------------
        //--Migracion Mongo DB
        $arreglo_usuarios = [];
        $arreglo_correo = [];
        $arreglo_where = array("eliminado"=>false);
        $id_usuario_login = $this->session->userdata('id_usuario');
        if(!empty($id_usuario)){
            $id = new MongoDB\BSON\ObjectId($id_usuario);
            $arreglo_where = array("_id"=>$id,"eliminado"=>false);
        }elseif(!empty($correo_usuario)){
            $arreglo_where = array("correo_usuario"=>$correo_usuario,"eliminado"=>false);
        }

        $res_usuarios = $this->mongo_db->order_by(array('_id' => 'DESC'))->where($arreglo_where)->get($this->tabla_usuario);
        $listado = [];
        foreach ($res_usuarios as $clave => $valor) {
            //--
            $valores["id_usuario"] = (string)$valor["_id"]->{'$id'};
            $valores["id_rol"] = (string)$valor["id_rol"];
            $valores["correo_usuario"] = $valor["correo_usuario"];
            $valores["avatar_usuario"] = $valor["avatar_usuario"];
            if($valor["fec_ult_acceso_usuario"]!=""){
                $valores["fec_ult_acceso_usuario"] = $valor["fec_ult_acceso_usuario"]->toDateTime();
            }else{
                 $valores["fec_ult_acceso_usuario"] = "";
            }
            
            $valores["status"] = $valor["status"];
            $valores["fec_regins"] = $valor["auditoria"][0]->fecha->toDateTime();
            //$vector_auditoria = end($valor["auditoria"]);
            //$valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            //--Consulto rol
            $id_rol = new MongoDB\BSON\ObjectId($valor["id_rol"]);
            $res_rol = $this->mongo_db->where(array("_id"=>$id_rol))->get($this->tabla_roles);
            foreach ($res_rol as $clave_rol => $valor_rol) {
                $valores["nombre_rol"] = $valor_rol["nombre_rol"];
            }
            //--
            //--Consulto datos personales
            $id_usuario = new MongoDB\BSON\ObjectId($valores["id_usuario"]);
            $res_dt = $this->mongo_db->where(array("id_usuario"=>$id_usuario))->get($this->tabla_personal);

            foreach ($res_dt as $clave_dt => $valor_dt) {
                $valores["id_datos_personales"] = (string)$valor_dt["_id"]->{'$id'};
                $valores["id_contacto"] = (string)$valor_dt["id_contacto"];
                $valores["nombre_datos_personales"] = $valor_dt["nombre_datos_personales"];
                $valores["apellido_p_datos_personales"] = $valor_dt["apellido_p_datos_personales"];
                $valores["apellido_m_datos_personales"] = $valor_dt["apellido_m_datos_personales"];
                $valores["curp_datos_personales"] = $valor_dt["curp_datos_personales"];
                #$valores["rfc_datos_personales"] = $valor_dt["rfc_datos_personales"];
                $valores["genero_datos_personales"] = $valor_dt["genero_datos_personales"];
                $valores["fecha_nac_datos_personales"] = $valor_dt["fecha_nac_datos_personales"];
                $valores["edo_civil_datos_personales"] = $valor_dt["edo_civil_datos_personales"];
                $valores["nacionalidad_datos_personales"] = $valor_dt["nacionalidad_datos_personales"];
                #$valores["num_hijosdatos_personales"] = $valor_dt["num_hijosdatos_personales"];
            }
            //var_dump($valores);die('');
            //--Consulto contactos
            $id_contacto = new MongoDB\BSON\ObjectId($valores["id_contacto"]);
            $res_contacto = $this->mongo_db->where(array("_id"=>$id_contacto))->get($this->tabla_contacto);
            foreach ($res_contacto as $clave_contacto => $valor_contacto) {
                $valores["id_codigo_postal"] = $valor_contacto["id_codigo_postal"];
                $valores["telefono_principal_contacto"] = $valor_contacto["telefono_principal_contacto"];
                $valores["telefono_movil_contacto"]=$valor_contacto["telefono_movil_contacto"];
                $valores["direccion_contacto"] = $valor_contacto["direccion_contacto"];
                $valores["calle_contacto"] = $valor_contacto["calle_contacto"];
                $valores["exterior_contacto"] = $valor_contacto["exterior_contacto"];
                $valores["interior_contacto"] = $valor_contacto["interior_contacto"];
                #$valores["status"] = $valor_contacto["status"];
            }
            //--
            //--Consulto el correo del usuario que registro
            $id_registro = $valor["auditoria"][0]->cod_user;
            $id = new MongoDB\BSON\ObjectId($id_registro);
            $res_us_rg = $this->mongo_db->where(array("_id"=>$id))->get($this->tabla_usuario);
            foreach ($res_us_rg as $clave_us_reg => $valor_us_reg) {
                $valores["user_regis"] = $valor_us_reg["correo_usuario"];
            }
            //--
            //if($id_usuario_login!=$valores["id_usuario"])
            $listado[] = $valores;
        }
        return $listado;
        //-------------------------------------------------------------------------------
    }

    public function nacionalidades()
    {
        $this->db->where('lv.tipolval', 'NACIONALIDAD');
        $this->db->where('a.status', 1);
        $this->db->where('a.tabla', $this->tabla_lval);
        $this->db->select('lv.*');
        $this->db->from($this->tabla_lval . ' lv');
        $this->db->join('auditoria a', 'lv.codlval = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function estados_civiles()
    {
        $this->db->where('lv.tipolval', 'EDOCIVIL');
        $this->db->where('a.status', 1);
        $this->db->where('a.tabla', $this->tabla_lval);
        $this->db->select('lv.*');
        $this->db->from($this->tabla_lval . ' lv');
        $this->db->join('auditoria a', 'lv.codlval = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function sexos()
    {
        $this->db->where('lv.tipolval', 'SEXO');
        $this->db->where('a.status', 1);
        $this->db->where('a.tabla', $this->tabla_lval);
        $this->db->select('lv.*');
        $this->db->from($this->tabla_lval . ' lv');
        $this->db->join('auditoria a', 'lv.codlval = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function roles()
    {
        /*$this->db->where('a.status', 1);
        $this->db->where('a.tabla', $this->tabla_roles);
        $this->db->select('*');
        $this->db->from($this->tabla_roles . ' r');
        $this->db->join('auditoria a', 'r.id_rol = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //-------------------------------------------------------------------------------------
        //Migracion MONGO DB
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'status'=>true))->get($this->tabla_roles);
        foreach ($resultados as $clave => $valor) {
            $id = new MongoDB\BSON\ObjectId($valor["_id"]->{'$id'});
            $valor["id_rol"] = (string)$id;
            $listado[] =$valor;
        }
        return $listado;
        //-------------------------------------------------------------------------------------    
    }
    /*
    *
    */
     public function roles_consulta()
    {
        /*$this->db->where('a.status', 1);
        $this->db->where('a.tabla', $this->tabla_roles);
        $this->db->select('*');
        $this->db->from($this->tabla_roles . ' r');
        $this->db->join('auditoria a', 'r.id_rol = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //-------------------------------------------------------------------------------------
        //Migracion MONGO DB
        $resultados = $this->mongo_db->where(array('eliminado'=>false))->get($this->tabla_roles);
        foreach ($resultados as $clave => $valor) {
            $id = new MongoDB\BSON\ObjectId($valor["_id"]->{'$id'});
            $valor["id_rol"] = (string)$id;
            $listado[] =$valor;
        }
        return $listado;
        //-------------------------------------------------------------------------------------    
    }
    /*
    *
    */
    public function buscar_codigos($codigo)
    {
        $estados = $this->db->query("SELECT DISTINCT d_estado FROM codigo_postal WHERE d_codigo='$codigo'");
        $estados->result();
        $ciudades = $this->db->query("SELECT DISTINCT d_ciudad FROM codigo_postal WHERE d_codigo='$codigo'");
        $ciudades->result();
        $municipios = $this->db->query("SELECT DISTINCT d_mnpio FROM codigo_postal WHERE d_codigo='$codigo'");
        $municipios->result();
        $colonias = $this->db->query("SELECT id_codigo_postal, d_asenta FROM codigo_postal WHERE d_codigo='$codigo'");
        $colonias->result();
        $data = array(
            'estados' => $estados,
            'ciudades' => $ciudades,
            'municipios' => $municipios,
            'colonias' => $colonias,
        );
        return $data;
    }
        
    public function registrar_usuario($usuarioArray, $contactoArray, $personalArray){
        /*$this->db->insert($this->tabla_usuario, $usuarioArray);
        $datos = array(
            'tabla' => $this->tabla_usuario,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $personalArray['id_usuario'] = $this->db->insert_id();
        $this->db->insert($this->tabla_contacto, $contactoArray);
        $personalArray['id_contacto']  = $this->db->insert_id();
        $this->db->insert($this->tabla_personal, $personalArray);
        $this->db->insert('auditoria', $datos);*/
        //----------------------------------------------------------------------------
        //--Migracion MONGO DB
        $fecha = new MongoDB\BSON\UTCDateTime();
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        //--Registro en usuario
        $insertar = $this->mongo_db->insert($this->tabla_usuario, $usuarioArray);
        if ($insertar) {
            //--Consultar ultimo id....
            $res_us = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_usuario);
            $personalArray['id_usuario'] = new MongoDB\BSON\ObjectId($res_us[0]["_id"]->{'$id'});
            //--Registro en contacto
            $insertar2 = $this->mongo_db->insert($this->tabla_contacto, $contactoArray);
            if ($insertar2) {
                //--Consultar ultimo id....
                $res_us = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_contacto);
                //--Registro e datos personales
                 $personalArray['id_contacto']  = new MongoDB\BSON\ObjectId($res_us[0]["_id"]->{'$id'});
                 $insertar3 = $this->mongo_db->insert($this->tabla_personal, $personalArray);
            }
        }
        //----------------------------------------------------------------------------
    }

    public function super_modificar($tabla,$id,$super_array){
        
        $super_id =  new MongoDB\BSON\ObjectId($id);

        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        //---Modificar
        $mod_us = $this->mongo_db->where(array('_id'=>$super_id))->set($super_array)->update($tabla);
       
        //---Auditoria
        $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar usuario',
                                            'operacion'=>''
                                        );
        $mod_auditoria = $this->mongo_db->where(array('_id'=>$super_id))->push('auditoria',$data_auditoria)->update($tabla);
        /*if($tabla=="datos_personales"){
            var_dump($mod_auditoria);die('');
        }**/
        
    }

    public function actualizar_usuario($usuarioArray, $contactoArray, $personalArray, $idArray, $imagen, $auditoria_bool = true){
        //----------------------------------------------------------------------------
        //--MIGRACION MONGO DB
        if(!empty($usuarioArray)){
            $this->super_modificar($this->tabla_usuario,$idArray['id_usuario'], $usuarioArray);
        }
        if(!empty($contactoArray)){
            $this->super_modificar($this->tabla_contacto,$idArray['id_contacto'], $contactoArray);
        }
        if(!empty($personalArray)){
            $this->super_modificar($this->tabla_personal,$idArray['id_datos_personales'], $personalArray);
        }
        if($imagen != ""){
            $data = array(
                'avatar_usuario' => $imagen,
            );
            $id = new MongoDB\BSON\ObjectId($idArray['id_usuario']);
            $mod_us = $this->mongo_db->where(array('_id'=>$id))->set($data)->update($this->tabla_usuario);
        }

        //----------------------------------------------------------------------------
        /*if(!empty($usuarioArray)){
            $this->db->where('id_usuario', $idArray['id_usuario']);
            if($this->db->update($this->tabla_usuario, $usuarioArray)){
            }
        }
        if(!empty($contactoArray)){
            $this->db->where('id_contacto', $idArray['id_contacto']);
            if($this->db->update($this->tabla_contacto, $contactoArray)){
            }
        }
        if(!empty($personalArray)){
            $this->db->where('id_datos_personales', $idArray['id_datos_personales']);
            if($this->db->update($this->tabla_personal, $personalArray)){
            }
        }
        if($imagen != ""){
            $data = array(
                'avatar_usuario' => $imagen,
            );
            $this->db->where('id_usuario', $idArray['id_usuario']);
            if($this->db->update($this->tabla_usuario, $data)){
            }
        }

        if($auditoria_bool){
            $datos=array(
                'usr_regmod' => $this->session->userdata('id_usuario'),
                'fec_regmod' => date('Y-m-d'),
            );
            $this->db->where('cod_reg', $idArray['id_usuario'])->where('tabla', $this->tabla_usuario);
            if($this->db->update('auditoria', $datos)){
            }
        }*/
    }

    public function verificar_usuario($correo_usuario)
    {
        /*$this->db->where('correo_usuario', $correo_usuario);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_usuario);
        return $resultados->result_array();*/
        //--------------------------------------------------------------------------------
        //--Migracion MONGO DB
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'correo_usuario'=>$correo_usuario))->get($this->tabla_usuario);
        return $resultados;
        //--------------------------------------------------------------------------------
    }
    /*
    *   Verificar Curp
    */
    public function verificar_curp($curp){
       //--Migracion MONGO DB
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'curp_datos_personales'=>$curp))->get($this->tabla_personal);
        return $resultados;
        //-------------------------------------------------------------------------------- 
    }
    /*
    *
    */
    public function eliminar_usuario($id)
    {
        /*try { 
            if(!$this->db->delete($this->tabla_usuario, array('id_usuario' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_usuario));
                echo json_encode("<span>El usuario se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }*/
        //------------------------------------------------------------
        //Migracion MONGO DB
        $id = new MongoDB\BSON\ObjectId($id);

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $datos = array(
                                    'eliminado'=>true,
                );
        $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_usuario);
        //--Auditoria
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar usuario',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_usuario); 
            echo json_encode("<span>El usuario se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso

        }
        //------------------------------------------------------------
    }

    public function status_usuario($id, $status)
    {
        /*$datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_usuario);
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
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_usuario);
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status usuario',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_usuario); 
        }
        //------------------------------------------------------------
    }

    public function eliminar_multiple_usuario($id_usuario)
    {
        /*$eliminados=0;
        $noEliminados=0;
        foreach($id as $usuario)
        {
            if($this->db->delete($this->tabla_usuario, array('id_usuario' => $usuario))){
                $this->db->delete('auditoria', array('cod_reg' => $usuario, 'tabla' => $this->tabla_usuario));
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);*/
          //var_dump($id_rol);die('');
        $eliminados=0;
        $noEliminados=0;
        $id_usuario_a = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id_usuario as $usuario){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            $id = new MongoDB\BSON\ObjectId($usuario);
            $datos = $data=array(
                                    'eliminado'=>true,
            );
            $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_usuario);
            //--Auditoria
            if($eliminar){
                $eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario_a,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar modulo',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_usuario);
            }else{
                $noEliminados++;
            }   
            //----------------------------------------------------------------------------------    

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //----------------------------------------------------------------------------
    }

    public function status_multiple_usuario($id, $status)
    {
        /*$usuarios = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $usuarios . ") AND tabla='" . $this->tabla_usuario . "'");*/
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
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_usuario);
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
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_usuario); 
            }
        }
        //---------------------------------------------------------------------------
    }

    public function login($correo_usuario, $clave_usuario)
    {
        //--Modificacion con mongo db   
        //------------------------------------------------------------------------
       //$this->load->library('mongo_db', array('activate'=>'newdb'),'mongo_db');
        $res = $this->mongo_db->where(array('correo_usuario' => $correo_usuario))->where(array('clave_usuario' => $clave_usuario))->get('usuario');
        if(count($res)>0){
            foreach ($res as $clave => $valor) {

                //Transformo la fila en un array
                $id = new MongoDB\BSON\ObjectId($valor["_id"]->{'$id'});
                $arreglo_datos_personales["datos_personales"] = $this->mongo_db->where(array('id_usuario' => $id))->get('datos_personales');
                //$listado = array_push_assoc($valor,$arreglo_datos_personales["datos_personales"]);
                $valor["datos_personales"] = $arreglo_datos_personales["datos_personales"];
                $listado[] =$valor;
            }
            if(count($listado)>0){
                //$listado2 = $listado;
                return $listado;
            }else{
                return false;
            }
        }else{
            return false;
        }
        //--------------------------------------------------------------------------
        /*$this->db->where('u.correo_usuario', $correo_usuario);
        $this->db->where('u.clave_usuario', $clave_usuario);
        $this->db->where('a.tabla', $this->tabla_usuario);
        $this->db->select('u.*, dt.nombre_datos_personales, dt.apellido_p_datos_personales, dt.apellido_m_datos_personales, a.status');
        $this->db->from($this->tabla_usuario . ' u');
        $this->db->join($this->tabla_personal . ' dt', 'u.id_usuario = dt.id_usuario');
        $this->db->join('auditoria a', 'u.id_usuario = a.cod_reg');
        $resultados = $this->db->get();
        if ($resultados->num_rows() > 0) {
            return $resultados->row();
        } else {
            return false;
        }*/
    }
    public function verificar_roles($id_rol){
        //Antes recibia $id_usuario
        //--Modificacion con mongo db
        //------------------------------------------------------------------------
        //$this->load->library('mongo_db', array('activate'=>'newdb'),'mongo_db');
        $id = new MongoDB\BSON\ObjectId($id_rol);
        $res = $this->mongo_db->where(array('_id' => $id,'status'=>true,'eliminado'=>false))->get('rol');
        if(count($res)>0){
            return $res;
        }else{
                return false;
        }
        //-------------------------------------------------------------------------
       /* $this->db->where('u.id_usuario', $id_usuario);
        $this->db->where('a.tabla', $this->tabla_usuario);
        $this->db->select('u.*,r.nombre_rol');
        $this->db->from($this->tabla_usuario . ' u');
        $this->db->join($this->tabla_roles . ' r', 'u.id_rol = r.id_rol');
        $this->db->join('rol_operaciones o','r.id_rol = o.id_rol');
        $this->db->join('auditoria a', 'u.id_usuario = a.cod_reg');
        
        $resultados = $this->db->get();
        //print_r($this->db->last_query());die;
        if ($resultados) {
            if ($resultados->num_rows()>0) {
                return true;
            } else {
                return false;
            }
        }    */
    }
     public function verificar_roles_consulta($id_rol){
        //Antes recibia $id_usuario
        //--Modificacion con mongo db
        //------------------------------------------------------------------------
        //$this->load->library('mongo_db', array('activate'=>'newdb'),'mongo_db');
        $id = new MongoDB\BSON\ObjectId($id_rol);
        $res = $this->mongo_db->where(array('_id' => $id,'eliminado'=>false))->get('rol');
        if(count($res)>0){
            return $res;
        }else{
                return false;
        }
    }    
    public function ultima_conexion($id_usuario)
    {
        $id = new MongoDB\BSON\ObjectId($id_usuario);
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $data = array(
            'fec_ult_acceso_usuario' => $fecha,
        );
        
        $mod_us = $this->mongo_db->where(array('_id'=>$id))->set($data)->update($this->tabla_usuario);
    }
    
    public function consultar_proyecto($id_usuario)
    {
      $this->db->where('director', $id_usuario);
      $resultados = $this->db->get('proyectos'); 
      return $resultados->row_array();           
    }
      public function consultar_inmobiliaria($id_usuario)
    {
      $this->db->where('id_coordinador', $id_usuario);
      $resultados = $this->db->get('inmobiliarias'); 
      return $resultados->row_array();           
    }

}
