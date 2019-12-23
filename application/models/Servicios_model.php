<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Servicios_model extends CI_Model
{

    private $tabla_servicios = "servicios";

    public function listado_tipo_servicios(){
        //------------------------------------------------------------------------------
        //--Modificacion con Mongo db
        //---------------------------------------------------------------------------
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get("tipo_servicios");
        foreach ($resultados as $clave => $valor) {
            $auditoria = $valor["auditoria"][0];
            //--usuario en cuestion
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
           
            $vector_auditoria = end($valor["auditoria"]);
            $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            
            //$valor["correo_usuario"] = $res_us[0]["correo_usuario"];
            isset($res_us[0]["correo_usuario"])? $valor["correo_usuario"] = $res_us[0]["correo_usuario"]:$valor["correo_usuario"] ="";
            $valor["status"] = $valor["status"];
            $valor["id_tipo_serv"] = (string)$valor["_id"]->{'$id'};
            $listado[] = $valor;
        }    
        //--
        $listado2 = $listado;
        return $listado2;
        //---------------------------------------------------------------------------
    }
    public function listado_esquema()
    {
        /*$this->db->where('a.tabla', $this->tabla_esquema);
        //$this->db->select('e.*, a.fec_regins, u.correo_usuario, a.status, lv.descriplval');
        $this->db->select('e.*, a.fec_regins, u.correo_usuario, a.status');
        $this->db->from($this->tabla_esquema . ' e');
        $this->db->join('auditoria a', 'e.id_esquema = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        //$this->db->join($this->tabla_lval . ' lv', 'e.tipo = lv.codlval');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //------------------------------------------------------------------------------
        //--Modificacion con Mongo db
        //---------------------------------------------------------------------------
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get($this->tabla_servicios);
        foreach ($resultados as $clave => $valor) {
            $auditoria = $valor["auditoria"][0];
            //var_dump($auditoria->cod_user);die('');
        //--usuario en cuestion
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
            //var_dump($res_us[0]["auditoria"]->status);die('');
            //$valor["fec_regins"] = $res_us[0]["auditoria"][0]->fecha->toDateTime();
            $vector_auditoria = reset($valor["auditoria"]);
            $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            
            //$valor["correo_usuario"] = $res_us[0]["correo_usuario"];
             isset($res_us[0]["correo_usuario"])? $valor["correo_usuario"] = $res_us[0]["correo_usuario"]:$valor["correo_usuario"] ="";
            $valor["status"] = $valor["status"];
            if(!isset($valor["tipo_servicio"])){
                $valor["tipo_servicio"] = "";
                $valor["titulo_servicio"] = "";
            }else{
                $id = new MongoDB\BSON\ObjectId($valor["tipo_servicio"]);
                $res_tipo_serv = $this->mongo_db->where(array('_id'=>$id))->get('tipo_servicios');
                $valor["titulo_servicio"] = $res_tipo_serv[0]["titulo"];
            }

            $valor["monto"] = number_format($valor["monto"],2);
            $valor["id_servicios"] = (string)$valor["_id"]->{'$id'};
            $listado[] = $valor;
        }    
        //--
        $listado2 = $listado;
        return $listado2;
        //---------------------------------------------------------------------------
    }   
        
    public function registrar_servicio($data){
        /*$this->db->insert($this->tabla_esquema, $data);
        $datos=array(
            'tabla' => $this->tabla_esquema,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
        echo json_encode("<span>El esquema se ha registrado exitosamente!</span>");*/
        //-----------------------------------------------------------------------------
        //Migracion MONGO DB
        $insertar1 = $this->mongo_db->insert($this->tabla_servicios, $data);
        echo json_encode("<span>El servicio se ha registrado exitosamente!</span>");
        //-----------------------------------------------------------------------------
    }

    public function actualizar_servicio($id, $data)
    {
        
         //---------------------------------------------------------------------------------
        //--Migracion MONGO DB
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_servicio= new MongoDB\BSON\ObjectId($id);
        //--Consulto si existe el descuento
        $res_servicio = $this->mongo_db->limit(1)->where(array('cod_servicio'=>$data['cod_servicios'],))->get($this->tabla_servicios);
        //Si el registro mantiene ĺos mismos campos
        if(count($res_servicio)==0){
            //Actualizo los campos
            $mod_servicios= $this->mongo_db->where(array('_id'=>$id_servicio))->set($data)->update($this->tabla_servicios);
            //Auditoria...
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar esquema',
                                            'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_servicio))->push('auditoria',$data_auditoria)->update($this->tabla_servicios);
             echo json_encode("<span>El servicio se ha editado exitosamente!</span>");
        }else{//Si cambian sus campos
            if ($res_servicio[0]["_id"]->{'$id'} == $id) {
                //Actualizo los campos
                $mod_servicios = $this->mongo_db->where(array('_id'=>$id_servicio))->set($data)->update($this->tabla_servicios);
                //Auditoria...
                $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Modificar esquema',
                                                'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_servicio))->push('auditoria',$data_auditoria)->update($this->tabla_servicios);
                //--
                echo json_encode("<span>El servicio se ha editado exitosamente!</span>");
            }else {
                echo "<span>¡Ya se encuentra registrado un servicio con las mismas características!</span>";
            }
        }
        //---------------------------------------------------------------------------------
    }

    /*
    *   Consultar dependencia a otras tablas
    */
    public function consultar_dependencia($tabla,$filtro){
        $id = new MongoDB\BSON\ObjectId($filtro);
        $res = 0;
        if($tabla=="reservaciones"){
            $res = $this->mongo_db->where(array('eliminado'=>false,'id_servicio_sala'=>$filtro))->get("reservaciones");
        }
        return count($res);
    }    
    /*
    *
    */
    public function eliminar_servicio($id)
    {
        //$dependencia = $this->consultar_dependencia("paquetes",$id);
        
        //--No me permite agregar claves foraneas por lo que valido que no este en la tabla de recargos
        /*$this->db->where('a.tabla', $this->tabla_esquema);
        $this->db->where('r.cod_esquema', $id);
        $this->db->select('e.*');
        $this->db->from($this->tabla_esquema . ' e');
        $this->db->join('recargas r', 'r.cod_esquema = e.id_esquema');
        $this->db->join('auditoria a', 'e.id_esquema = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        
        $resultados = $this->db->get();
        
        if($resultados){
            if($resultados->num_rows()>0){
                echo("<span>No se puede eliminar el registro porque tiene dependencia en recargos !</span>");
                die('');
             }
        }
       
        //--
        try { 
            if(!$this->db->delete($this->tabla_esquema, array('id_esquema' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_esquema));
                echo json_encode("<span>El esquema se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
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
        $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_servicios);
        //--Auditoria
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar servicio',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_servicios); 
            echo json_encode("<span>El servicio se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso

        }
        //------------------------------------------------------------
    }

    public function status_servicio($id, $status)
    {
        /*$datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_esquema);
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
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_servicios);
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status servicios',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_servicios); 
        }
        //------------------------------------------------------------
    }

    public function eliminar_multiple_servicios($id_servicio)
    {
        //--------------------------------------------------------------------------------------
        //MIGRACION MONGO DB
        /*$vector_eliminados=[];
        $eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id_servicio as $servicios){
            $dependencia = $this->buscarPaquetes($servicios);
            if($dependencia>0){
                 $noEliminados++;
            }else{
                //---------------------------------------------------------------------------------
                //--Migracion Mongo DB
                    $id = new MongoDB\BSON\ObjectId($servicios);
                    $datos = array(
                                            'eliminado'=>true,
                    );
                    $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_servicios);
                    //--Auditoria
                    if($eliminar){
                        $eliminados++;
                        $data_auditoria = array(
                                                    'cod_user'=>$id_usuario,
                                                    'nom_user'=>$this->session->userdata('nombre'),
                                                    'fecha'=>$fecha,
                                                    'accion'=>'Eliminar servicios',
                                                    'operacion'=>''
                                                );
                        $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_servicios);
                    }else{
                        $noEliminados++;
                    }   
                    //
            //}        
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);*/
        $vector_eliminados=[];
        $eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id_servicio as $servicios){
            //$dependencia = $this->consultar_dependencia("paquetes",$servicios);
            $dependencia = $this->buscarPaquetes($servicios);
            $dependencia_reservaciones = $this->consultar_dependencia("reservaciones",$servicios);
            if(($dependencia>0)||($dependencia_reservaciones>0)){
                $noEliminados++;
            }else{
                $vector_eliminados[] = $servicios;
                $eliminados++;
            }
        }
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
        //var_dump($vector_eliminados);die('');
        foreach ($vector_eliminados as $clave_serv => $valor_serv) {
            //--------------------------
            $id = new MongoDB\BSON\ObjectId($valor_serv);
            $datos = array(
                                    'eliminado'=>true,
            );
            $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_servicios);

            //var_dump($eliminar);die('');
            //--Auditoria
           
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar servicios',
                                            'operacion'=>''
                                        );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_servicios);
           //-------------------------- 
        }
        
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_servicio($id, $status)
    {
        /*$esquemas = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $esquemas . ") AND tabla = '" . $this->tabla_esquema . "'");*/
        //---------------------------------------------------------------------------
        //--Migracion Mongo DB
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();

        $arreglo_id = explode(' ',$id);
        
        foreach ($arreglo_id as $valor) {
            $id = new MongoDB\BSON\ObjectId($valor);
            //var_dump($id);die('');
            
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
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_servicios);
            //var_dump($modificar);die('');
            //--Auditoria
            if($modificar){
                $data_auditoria = "";
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status servicios',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_servicios); 
            }
        }
        //---------------------------------------------------------------------------

    }
    /*
    * Verificar si existe servicios
    */
    public function verificar_existe_servicios($tipo,$cod_servicio,$descripcion,$id){
        if(($tipo!="")&&($cod_servicio!="")&&($descripcion!="")){
            
            $res = $this->mongo_db->where(array("tipo"=>$tipo,"cod_servicios"=>$cod_servicio,"descripcion"=>$descripcion,"eliminado"=>false))->get($this->tabla_servicios);
        
        }else if(($tipo=="")&&($cod_servicio!="")&&($descripcion=="")){

            $res = $this->mongo_db->where(array("cod_servicios"=>$cod_servicio,"eliminado"=>false))->get($this->tabla_servicios);

        }else if (($tipo=="")&&($cod_servicio=="")&&($descripcion!="")) {
            $res = $this->mongo_db->where(array("descripcion"=>$descripcion,"eliminado"=>false))->get($this->tabla_servicios);
        }
        
        //var_dump($tipo); var_dump($cod_servicio); var_dump($descripcion);var_dump($res);
        //die('');
        if($res){
            if($id!=""){
                $id_servicio = $res[0]["_id"]->{'$id'};
                if($id==$id_servicio){
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
             
    }
    /*
    *
    */
    public function tipos_esquemas()
    {
        $this->db->where('tipolval', 'ESQUEMAS');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }
    public function buscarDescuentos($id)
    {
        $this->db->where('cod_esquema', $id);
        $resultados = $this->db->get('descuentos');
        return $resultados->row_array();
    }
    public function buscarComisiones($id)
    {
        $this->db->where('cod_esquema', $id);
        $resultados = $this->db->get('comisiones');
        return $resultados->row_array();
    }
    /***/
    /*
    *   buscar paquetes
    */
    public function buscarPaquetes($id){

        #1- Consulto paquetes y los recorros...

        $paquetes = $this->mongo_db->where(array("eliminado"=>false))->get("paquetes");

        foreach ($paquetes as $key => $value) {
            
            #2- Recorro los servicios asociados a ese paquete
            $servicios = $value["servicios"];
            foreach ($servicios as $key_serv => $value_serv) {
                $servicio = $value_serv->id_servicios;
                if($id==$servicio){
                    if($value_serv->eliminado==false){
                        //var_dump($value);
                        return 1;                   
                    }
                }
            }
        }

        return 0;
    }   
    /***/
}
