<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Planes_model extends CI_Model
{

    private $tabla_planes = "planes";
   
    public function listado_planes(){
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
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get($this->tabla_planes);
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
            
            isset($res_us[0]["correo_usuario"])? $valor["correo_usuario"] = $res_us[0]["correo_usuario"]:$valor["correo_usuario"] ="";
            
            $valor["status"] = $valor["status"];
            $valor["id_planes"] = (string)$valor["_id"]->{'$id'};
            
            if(isset($valor["jornadas_limitadas"])){
                ($valor["jornadas_limitadas"]== true)? $valor["ind_jornada"] = "S" : $valor["ind_jornada"] = "N";
            }else{
                $valor["jornadas_limitadas"] = "";
                $valor["ind_jornada"] = "";
            }

            if(isset($valor["plan_empresarial"])){
                ($valor["plan_empresarial"]== true)? $valor["ind_plan_empresarial"] = "S" : $valor["ind_plan_empresarial"] = "N";
            }else{
                $valor["plan_empresarial"] = "";
                $valor["ind_plan_empresarial"] = "";
            }

            //(!isset($valor["horas_jornadas"]))? $valor["horas_jornadas"] = ""  : $valor["horas_jornadas"] = $valor["horas_jornadas"];
            //$precio = str_replace('.', '*', $valor["precio"]);
            //$precio = str_replace(',', '', $precio);
            //$precio = str_replace('*', ',', $precio);
            //var_dump($precio);die('');
            //$valor["precio"] = number_format($precio, 2, '.', ',');
            //--Consulto la vigencia


            if($valor["membresia"] == false){
                $valor["vigencia"] = "";
            }else{
                $id_vigencia = new MongoDB\BSON\ObjectId($valor["id_vigencia"]);            
                $res_vigencia = $this->mongo_db->where(array('_id'=>$id_vigencia))->get('vigencia');
                $valor["vigencia"] = $res_vigencia[0]["descripcion"];
            }


            
           

            $listado[] = $valor;
        }    
        //--
        $listado2 = $listado;
        return $listado2;
        //---------------------------------------------------------------------------
    } 
    /*
    *   Servicio de planes
    */
     public function listado_planes_servicio(){
        
        //------------------------------------------------------------------------------
        //--Migración con Mongo db
        //---------------------------------------------------------------------------
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true,'muestra_en_web'=>true))->get($this->tabla_planes);
        foreach ($resultados as $clave => $valor) {

            $auditoria = $valor["auditoria"][0];
        //var_dump($auditoria->cod_user);die('');
            $valores["id_planes"] = (string)$valor["_id"]->{'$id'};
            $valores["cod_planes"] = $valor["cod_planes"];
           
            $valores["titulo"] = $valor["titulo"];
            $valores["membresia"] = $valor["membresia"];
            $valores["descripcion"] = $valor["descripcion"];
            $valores["status"] = $valor["status"];
            $valores["muestra_en_web"] = $valor["muestra_en_web"];
            $valores["posicion_planes"] = $valor["posicion_planes"];
        //--usuario en cuestion
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
          
            $vector_auditoria = end($valor["auditoria"]);
            $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            
            isset($res_us[0]["correo_usuario"])?$valores["correo_usuario"] = $res_us[0]["correo_usuario"]:$valores["correo_usuario"] = "";

            $valores["status"] = $valor["status"];
         
            $id_vigencia = new MongoDB\BSON\ObjectId($valor["id_vigencia"]);            
            $res_vigencia = $this->mongo_db->where(array('_id'=>$id_vigencia))->get('vigencia');
            $valores["id_vigencia"] = $valor["id_vigencia"];
            $valores["vigencia"] = $res_vigencia[0]["descripcion"];
            $valores["tiempo_contrato"] = $valor["tiempo_contrato"];
            //$valores["precio"] = $valor["precio"];

            //---------------------------------------------------------------------------------
            //--Consulto paquetes asociados a ese plan
            //$id_pln = new MongoDB\BSON\ObjectId($valores["id_planes"]); 
            $id_pln = $valores["id_planes"];
            $res_paquete = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('eliminado'=>false,'status'=>true,'muestra_en_web'=>true,'plan'=>$id_pln))->get("paquetes");
            $contenido_plan = [];
            $super_contenido = [];

            #Ciclo para armar datos del paquete----->
            foreach ($res_paquete as $clave_paquete => $valor_paquete) {   
                $servicios = [];
                $servicios2 = [];
                $serviciosArr = []; 
                $contenido = [];            
                #Codigo
                isset($valor_paquete["codigo"])? $contenido["codigo_paquete"] = $valor_paquete["codigo"]:  $contenido["codigo_paquete"] = "";
                #Descripcion:
                isset($valor_paquete["descripcion"])? $contenido["descripcion_paquete"] = $valor_paquete["descripcion"]:  $contenido["descripcion_paquete"] = "";
                #Precio:
                isset($valor_paquete["precio"])? $contenido["precio_paquete"] = number_format($valor_paquete["precio"],2):  $contenido["precio_paquete"] = "";
                #Posicion
                isset($valor_paquete["posicion_paquetes"])? $contenido["posicion_paquetes"] = $valor_paquete["posicion_paquetes"]:  $contenido["posicion_paquetes"] = "";

                isset($valor_paquete["servicios"])? $servicios = $valor_paquete["servicios"]:  $servicios = [];

                isset($valor_paquete["muestra_en_web"])? $contenido["muestra_en_web"] = $valor_paquete["muestra_en_web"]:  $contenido["muestra_en_web"] = "";

                #Servicios
                $servicios = (array)$servicios;
                //return $servicios;
                if($servicios){

                    foreach ($servicios as $clave => $valor) {
                        $serviciosArr["id"] = $valor->id_servicios;
                        $serviciosArr["valor"] = $valor->valor;
                        (isset($valor->posicion))? $serviciosArr["posicion"] = $valor->posicion: $serviciosArr["posicion"] = "";
                        //---------------------------------------------
                        $id_serv = new MongoDB\BSON\ObjectId($valor->id_servicios); 
                        //Consulto el servicio asociado a ese paquete asociado a ese plan    
                        $res_serv = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('eliminado'=>false,'_id'=>$id_serv))->get("servicios");
                        if($res_serv){
                            $tipo_servicio = $res_serv[0]["tipo"];
                            $descripcion_servicio = $res_serv[0]["descripcion"];
                            ($tipo_servicio=="N")? $contenidoServ =  $valor->valor." ".$descripcion_servicio: $contenidoServ = $descripcion_servicio;
                            $serviciosArr["descripcion_servicio"] = $contenidoServ;
                        }else{
                            $serviciosArr["descripcion_servicio"] = "";
                        }
                       
                        //---------------------------------------------
                        $servicios2[] = $serviciosArr;
                        //---------------------------------------------
                    }
                }
                $this->array_sort_by($servicios2,"posicion",$order = SORT_ASC);

                $contenido["servicios"] = $servicios2;
                /*
                $cuantos_items_paquetes = $valor_paquete["valor"];
                
                $id_serv = new MongoDB\BSON\ObjectId($valor_paquete["id_servicio"]); 
                //Consulto el servicio asociado a ese paquete asociado a ese plan    
                $res_serv = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('eliminado'=>false,'status'=>true,'_id'=>$id_serv))->get("servicios");
                
                $tipo_servicio = $res_serv[0]["tipo"];
                
                $descripcion_servicio = $res_serv[0]["descripcion"];
                
                //armo el contenido por paquete
                ($tipo_servicio=="N")? $contenido =  $cuantos_items_paquetes." ".$descripcion_servicio: $contenido = $descripcion_servicio;
                */
                //$contenido_plan[] = $contenido;
                
                //$contenido = "";
                $super_contenido[] = $contenido;
            }
            $this->array_sort_by($super_contenido,"posicion_paquetes",$order = SORT_ASC);
            $valores["contenido"] = $super_contenido;
            
            //-----------------------------------------------------------------------------------
            $listado[] = $valores;
        }  
        $this->array_sort_by($listado,"posicion_planes",$order = SORT_ASC);  
        //--
        $listado2 = $listado;
        return (object)$listado2;
        //---------------------------------------------------------------------------
    }
     /*
    *   Ordenar array
    */
    public function array_sort_by(&$arrIni, $col, $order = SORT_ASC){
        $arrAux = array();
        foreach ($arrIni as $key=> $row)
        {
            $arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
            $arrAux[$key] = strtolower($arrAux[$key]);
        }
        array_multisort($arrAux, $order, $arrIni);
    }
    /*
    *
    */  
    /*
    *   Verificar si existe el plan
    */
    public function verificar_existe_plan($cod_planes,$descripcion,$vigencia,$tiempo_contrato,$precio,$titulo,$id){
        
        //$res = $this->mongo_db->where(array("cod_planes"=>$cod_planes,"descripcion"=>$descripcion,"id_vigencia"=>$vigencia,"tiempo_contrato"=>$tiempo_contrato,"precio"=>$precio))->get('planes');
        if(($cod_planes!="")&&($descripcion!="")&&($vigencia!="")&&($tiempo_contrato!="")){
            $res = $this->mongo_db->where(array("cod_planes"=>$cod_planes,"descripcion"=>$descripcion,"id_vigencia"=>$vigencia,"tiempo_contrato"=>$tiempo_contrato,'eliminado'=>false))->get('planes');
        }else if(($cod_planes!="")&&($descripcion=="")&&($vigencia=="")&&($tiempo_contrato=="")){
            $res = $this->mongo_db->where(array("cod_planes"=>$cod_planes,"eliminado"=>false))->get('planes');
        }else if(($cod_planes=="")&&($descripcion=="")&&($vigencia=="")&&($tiempo_contrato=="")&&($titulo!="")){
            $res = $this->mongo_db->where(array("titulo"=>$titulo,"eliminado"=>false))->get('planes');
        }
        
        if($res){
            if($id!=""){
                $id_plan = $res[0]["_id"]->{'$id'};
                if($id==$id_plan){
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
    public function registrar_planes($data){
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
        $insertar1 = $this->mongo_db->insert($this->tabla_planes, $data);
        echo json_encode("<span>El plan se ha registrado exitosamente!</span>");
        //-----------------------------------------------------------------------------
    }

    public function actualizar_planes($id, $data)
    {
        
         //---------------------------------------------------------------------------------
        //--Migracion MONGO DB
        
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_planes = new MongoDB\BSON\ObjectId($id);
        //--Consulto si existe el descuento
        $res_planes = $this->mongo_db->limit(1)->where(array('_id'=>$id_planes))->get($this->tabla_planes);
        //var_dump($res_planes);die('');
        //Si el registro mantiene ĺos mismos campos
        if(count($res_planes)==0){
            //Actualizo los campos
            $mod_esquema = $this->mongo_db->where(array('_id'=>$id_planes))->set($data)->update($this->tabla_planes);
            //Auditoria...
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar planes',
                                            'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_planes))->push('auditoria',$data_auditoria)->update($this->tabla_planes);
             echo json_encode("<span>El Plan se ha editado exitosamente!</span>");
        }else{//Si cambian sus campos
            if ($res_planes[0]["_id"]->{'$id'} == $id) {
                //Actualizo los campos
                $mod_esquema = $this->mongo_db->where(array('_id'=>$id_planes))->set($data)->update($this->tabla_planes);
                //Auditoria...
                $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Modificar planes',
                                                'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_planes))->push('auditoria',$data_auditoria)->update($this->tabla_planes);
                //--
                echo json_encode("<span>El plan se ha editado exitosamente!</span>");
            }else {
                echo "<span>¡Ya se encuentra registrado un plan con las mismas características!</span>";
            }
        }
        //---------------------------------------------------------------------------------
    }
    /*
    *   Consultar dependencia a otras tablas
    */
    public function consultar_dependencia($tabla,$filtro){
        $id = new MongoDB\BSON\ObjectId($filtro);
        $res = $this->mongo_db->where(array('eliminado'=>false,'id_plan'=>$id))->get($tabla);
        return count($res);
    }    
    /*
    *
    */

    public function eliminar_planes($id)
    {
        $dependencia = $this->consultar_dependencia("paquetes",$id);
        if($dependencia>0){
            echo "<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>";die('');
        }
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
        $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_planes);
        //--Auditoria
        if($eliminar){
            $res_reset = $this->mongo_db->get("mi_empresa"); 
            $this->actualizarPosicionesPlanesEliminar();
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar plan',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_planes); 
            echo json_encode("<span>El plan se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
        }
        //------------------------------------------------------------
    }

    public function status_planes($id, $status)
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
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_planes);
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status planes',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_planes); 
        }
        //------------------------------------------------------------
    }

    public function eliminar_multiple_planes($id_planes)
    {
        /*$eliminados=0;
        $noEliminados=0;
        foreach($id as $esquema)
        {
            if($this->db->delete($this->tabla_esquema, array('id_esquema' => $esquema))){
                $this->db->delete('auditoria', array('cod_reg' => $esquema, 'tabla' => $this->tabla_esquema));
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);*/
        //--------------------------------------------------------------------------------------
        //MIGRACION MONGO DB
        $eliminar = "";
        $eliminados=0;
        $noEliminados=0;
        $vector_eliminados = [];
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        $datos = array(
                                    'eliminado'=>true,
            );
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar planes',
                                        'operacion'=>''
                                    );
        foreach($id_planes as $planes){
            $existe_planes = $this->buscarPaquetes($planes); 
            //var_dump($existe_planes);die('');
            if($existe_planes>0){
                 $noEliminados++;
            }else{
                //$vector_eliminados[] = new MongoDB\BSON\ObjectId($planes);
                $id_eliminado = new MongoDB\BSON\ObjectId($planes);
                $eliminar = $this->mongo_db->where(array('_id'=>$id_eliminado))->set($datos)->update($this->tabla_planes);
                $res_reset = $this->mongo_db->get("mi_correo"); 
                $eliminados++;
                //Auditoria
                $mod_auditoria = $this->mongo_db->where(array('_id', $id_eliminado))->push('auditoria',$data_auditoria)->update($this->tabla_planes);
            }
        }  
        /*$eliminar = "";
        $eliminados=0;
        $noEliminados=0;
        $vector_eliminados = [];
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id_planes as $planes){
            $existe_planes = $this->buscarPaquetes($planes); 
            //var_dump($existe_planes);die('');
            if($existe_planes>0){
                 $noEliminados++;
            }else{
                $vector_eliminados[] = new MongoDB\BSON\ObjectId($planes);
                $eliminados++;
            }
        }  
        if($eliminados>=1){

            //---------------------------------------
            $id = new MongoDB\BSON\ObjectId($planes);
            $datos = array(
                                    'eliminado'=>true,
            );
            $eliminar = $this->mongo_db->where_in('_id', array($vector_eliminados))->set($datos)->update($this->tabla_planes);
            //--Auditoria

            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar planes',
                                        'operacion'=>''
                                    );
           $mod_auditoria = $this->mongo_db->where_in('_id', $vector_eliminados)->push('auditoria',$data_auditoria)->update($this->tabla_planes);
            //---------------------------------------
        } */

        //---------------------------------------------------------------------------------
                //--Migracion Mongo DB
                /*$id = new MongoDB\BSON\ObjectId($planes);
                $datos = array(
                                        'eliminado'=>true,
                );
                $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_planes);
                //--Auditoria
                if($eliminar!=""){
                    $eliminados++;
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar planes',
                                                'operacion'=>''
                                            );
                   $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_planes);
                }else{
                    $noEliminados++;
                }   
            //--------------------------------------------------------------------------------    
            }    
        }*/
        #reset
        $res_reset = $this->mongo_db->get("acceso"); 

        $this->actualizarPosicionesPlanesEliminar();
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //----------------------------------------------------------------------------

    }

    public function status_multiple_planes($id, $status)
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
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_planes);
            //var_dump($modificar);die('');
            //--Auditoria
            if($modificar){
                $data_auditoria = "";
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status esquema',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_planes); 
            }
        }
        //---------------------------------------------------------------------------

    }

    public function listado_vigencia()
    {
       $resultados = $this->mongo_db->where(array('eliminado'=>false))->get('vigencia');
       foreach ($resultados as $clave => $valor) {
           $valor["id_vigencia"] =  (string)$valor["_id"]->{'$id'};
           $listado[] = $valor; 
       }
       return $listado;
    }
    /*
    *   buscar paquetes
    */
    public function buscarPaquetes($id){

        $res = $this->mongo_db->where(array("plan"=>$id,"eliminado"=>false))->get("paquetes");
        if($res){
            return count($res);
        }else{
            return 0;
        }
    }
    /***/
      /***/
    public function contar_modulos(){
        /*
        *   Migracion mongo db
        */
        $res = $this->mongo_db->where(array("eliminado"=>false))->get('planes');
        return $res; 
    }
    /***/
    public function posicionar_modulos($posicionar){
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
                $resultado = $this->mongo_db->where_gte('posicion_planes', (int)$posicionar['posicion'])->where(array("eliminado"=>false))->get('planes');
                //var_dump($resultado);die();
                if(count($resultado)>0){
                    foreach ($resultado as $key => $value) {
                        $datos=array(
                            'posicion_planes' => $value["posicion_planes"] + 1,
                        );
                        $id = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});

                        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update('planes');

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
                $resultado = $this->mongo_db->where_gt('posicion_planes', (integer)$posicionar['inicial'])->where_lte('posicion_planes', (integer)$posicionar['final'])->where(array("eliminado"=>false))->get('planes');
                //var_dump($resultado);die('');
                //--
            
                if(count($resultado)>0){       
                    foreach ($resultado as $key => $value) {
    
                        $datos=array(
                            'posicion_planes' => $value["posicion_planes"] - 1,
                        );

                        $id = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});

                        $modificar1 = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update('planes');
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
                $resultado = $this->mongo_db->where_gte('posicion_planes', $posicionar['final'])->where_lt('posicion_planes', $posicionar['inicial'])->where(array("eliminado"=>false))->get('planes');
                //var_dump(count($resultado));die('');               
                if(count($resultado)>0){    
                    foreach ($resultado as $key => $value) {
                        
                        $datos=array(
                            'posicion_planes' => $value["posicion_planes"] + 1,
                        );

                        //var_dump($datos); 
                        $id = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});

                        $modificar1 = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update('planes');
                    }
                    //die('');
                }
            }
        }
    }
    /*
    *
    */
      /*
    *   generarPosiciones
    */
    public function generarPosicionesPlanes(){
        $cuantos_planes = 0;
        $nvaPosicion = 0;

        #consulto todos los paquetes
        $res_planes = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array("eliminado"=>false))->get("planes");
        foreach ($res_planes as $clave_planes => $valor_planes) {
            #recorro los paquetes
            $nvaPosicion++;
            $id_planes = $valor_planes["_id"]->{'$id'};
            $id_planes_mdb = new MongoDB\BSON\ObjectId($id_planes); 
            //--
            #Reset para hacer update
            $res_reset = $this->mongo_db->get("mi_correo"); 
            #Update de posicion
            $data = array('posicion_planes' => $nvaPosicion);
            $res_mod_planes = $this->mongo_db->where(array('_id' => $id_planes_mdb))->set($data)->update("planes"); 
            //--  
            $cuantos_planes++;
        }
        echo "Se actualizaron:  ".$cuantos_planes;die(" All right!");
    }
    /*
    *
    */
     public function generarMostrarWebPlanes(){
        $cuantos_planes = 0;
        $nvaPosicion = 0;

        #consulto todos los paquetes
        $res_planes = $this->mongo_db->order_by(array('_id' => 'ASC'))->get("planes");
        foreach ($res_planes as $clave_planes => $valor_planes) {
            #recorro los paquetes
            $id_planes = $valor_planes["_id"]->{'$id'};
            $id_planes_mdb = new MongoDB\BSON\ObjectId($id_planes); 
            //--
            #Reset para hacer update
            $res_reset = $this->mongo_db->get("mi_correo"); 
            #Update de posicion
            $data = array('muestra_en_web' => true);
            $res_mod_planes = $this->mongo_db->where(array('_id' => $id_planes_mdb))->set($data)->update("planes"); 
            //--  
            $cuantos_planes++;
        }
        echo "Se actualizaron:  ".$cuantos_planes;die(" All right!");
    }
    /***/
     /*
    *   Actualizar posiciones paquetes al eliminar
    */
    public function actualizarPosicionesPlanesEliminar(){
        $cuantos_planes = 0;
        $nvaPosicion = 0;
        #consulto todos los paquetes
        $res_planes = $this->mongo_db->order_by(array('posicion_planes' => 'ASC'))->where(array("eliminado"=>false))->get($this->tabla_planes);
        foreach ($res_planes as $clave_planes => $valor_planes) {
            $id_plan = $valor_planes["_id"]->{'$id'};
            if(!$valor_planes["eliminado"]){
                #recorro los paquetes
                $nvaPosicion++;
                $id_plan_mdb = new MongoDB\BSON\ObjectId($id_plan); 
                //--
                #Reset para hacer update
                $res_reset = $this->mongo_db->get("mi_empresa"); 
                #Update de posicion
                $data = array('posicion_planes' => $nvaPosicion);
                $res_mod_paquetes = $this->mongo_db->where(array('_id' => $id_plan_mdb))->set($data)->update($this->tabla_planes); 
                //--  
                $cuantos_planes++;
            }
        }
        //var_dump($cuantos_planes);die("aqui!");
    }
    /***/
}
