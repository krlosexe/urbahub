<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Paquetes_model extends CI_Model
{

    private $tabla_paquetes = "paquetes";

    private $tabla_planes = "planes";



    public function GetPaquete($id)
    {
        $id_paquete = new MongoDB\BSON\ObjectId($id);

        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('_id'=>$id_paquete, 'eliminado'=>false))->get($this->tabla_paquetes);


        return $resultados;
    }
   

    public function listado_paquetes()
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
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get($this->tabla_paquetes);
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
            $valor["id_paquete"] = (string)$valor["_id"]->{'$id'};
            
            //--Consulto el plan
            $id_plan = new MongoDB\BSON\ObjectId($valor["id_plan"]);
            $res_planes = $this->mongo_db->where(array('_id'=>$id_plan))->get('planes');
            $valor["cod_planes"] = (string)$res_planes[0]["cod_planes"]; 
            $valor["descripcion_planes"] = (string)$res_planes[0]["titulo"]." ".$res_planes[0]["descripcion"]; 
            
            //--Consulto el servicio
            $id_servicio = new MongoDB\BSON\ObjectId($valor["id_servicio"]);  
            $res_servicio = $this->mongo_db->where(array('_id'=>$id_servicio))->get('servicios');
            $valor["cod_servicios"] = $res_servicio[0]["cod_servicios"];
            $valor["descripcion_servicios"] = (string)$res_servicio[0]["descripcion"]; 
           
            $valor["id_plan"] = (string)$valor["id_plan"];
            //-------------------------------------------------
               
            $valor["id_servicio"] = (string)$valor["id_servicio"];
            
            $valor["precio"] = number_format($valor["precio"],2);

            $listado[] = $valor;
        }    
        //--
        $listado2 = $listado;
        return $listado2;
        //---------------------------------------------------------------------------
    }  
    /*
    *   Listado que muestra servicios asociados a un plan que pertenecen a un paquete.....
    */
     public function listado_planes_servicios_paquetes(){
        
        //------------------------------------------------------------------------------
        //--Migración con Mongo db
        //---------------------------------------------------------------------------
        $listado = [];
        //Consulto el plan...
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true))->get($this->tabla_planes);
        foreach ($resultados as $clave => $valor) {

            $auditoria = $valor["auditoria"][0];
            $valores["id_plan"] = (string)$valor["_id"]->{'$id'};
            $valores["cod_planes"] = $valor["cod_planes"];
            $valores["titulo"] = $valor["titulo"];
            $valores["descripcion"] = $valor["descripcion"];
            $valores["descripcion_planes"] = $valores["titulo"]." ".$valores["descripcion"] ;
            //---------------------------------------------------------------------------------
            //--Consulto paquetes asociados a ese plan
            $id_pln =$valores["id_plan"]; 
            
            $res_paquete = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('eliminado'=>false,'id_plan'=>$id_pln))->get("paquetes");
            //Si el plan cuenta con paquetes....
            if(count($res_paquete)>0){
                $arreglo_valor  = [];
                $cont_serv = 0;
                $contenido_plan = "";
                //-------------------------------------------------------------------------------
                //--Datos de usuarios
                $id = new MongoDB\BSON\ObjectId($res_paquete[0]["auditoria"][0]->cod_user);
                $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
                //var_dump($res_us[0]["auditoria"]->status);die('');
                //$valor["fec_regins"] = $res_us[0]["auditoria"][0]->fecha->toDateTime();
                $vector_auditoria = end($res_paquete[0]["auditoria"]);
                $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();

                $valores["correo_usuario"] = $res_us[0]["correo_usuario"];
                $valores["status"] = $res_paquete[0]["status"];
                //----------------------------------------------------------------------------------
                //Recorro cada paquete.....
                foreach ($res_paquete as $clave_paquete => $valor_paquete) {
                    //-------------------------------------------------------------------------------
                    //Para el servicio        
                    $id_serv = new MongoDB\BSON\ObjectId($valor_paquete["id_servicio"]); 
                    //Consulto el servicio asociado a ese paquete asociado a ese plan    
                    $res_serv = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('eliminado'=>false,'_id'=>$id_serv))->get("servicios");
                    $tipo_servicio = $res_serv[0]["tipo"];
                    
                    $descripcion_servicio = $res_serv[0]["descripcion"];
                    
                    $cuantos_items_paquetes = $valor_paquete["valor"];
                    
                    if($cont_serv == 0){
                        //armo el contenido por paquete
                        ($tipo_servicio=="N")? $contenido =  $cuantos_items_paquetes." ".$descripcion_servicio: $contenido = $descripcion_servicio;
                    }else{
                        ($tipo_servicio=="N")? $contenido = ", ".$cuantos_items_paquetes." ".$descripcion_servicio: $contenido =", ".$descripcion_servicio;
                    }
                    $cont_serv++;
                    $contenido_plan.= $contenido;                
                    ///----
                   
                }
                $cont_serv = 0;
                $valores["servicios"] = $contenido_plan;
                //-----------------------------------------------------------------------------------
                $listado[] = $valores;   
            }           
        }    
        //--
        return $listado;
        //---------------------------------------------------------------------------
    }
    /*
    *
    */   
    public function ordenar_servicio_consulta($servicios){
        $listado = [];
        foreach ($servicios as $clave => $valor) {
            if($valor->eliminado==false){
                $listado[]=$valor;
            }
        }
        $this->array_sort_by($listado,"posicion",$order = SORT_ASC);
        return $listado;
    }
    /*
    *   Tercer listado creado para mostrar paquetes asociados a multiples planes, servicios
        Según modificación presentada por Sr Abrahans Marzo 2019 - Modulo original Octubre 2018 
    */
    public function listado_paquetes_planes_servicios(){
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get($this->tabla_paquetes);
        foreach ($resultados as $clave => $valor) {
            $auditoria = $valor["auditoria"][0];
            $valor["precio"] = number_format($valor["precio"],2);
            //--usuario en cuestion
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
          
            $vector_auditoria = end($valor["auditoria"]);
            $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            
            //$valor["correo_usuario"] = $res_us[0]["correo_usuario"];
            isset($res_us[0]["correo_usuario"])? $valor["correo_usuario"] = $res_us[0]["correo_usuario"]:$valor["correo_usuario"] ="";
            $valor["status"] = $valor["status"];
            $valor["id_paquete"] = (string)$valor["_id"]->{'$id'};
            /*
            *   Consulto el plan
            */

            $id_plan = new MongoDB\BSON\ObjectId($valor["plan"]);
            $res_planes = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('_id'=>$id_plan))->get("planes");
            $valor["titulo_plan"] = $res_planes[0]["titulo"];
            /*
            *   Recorro los planes-servicios
            */
            $cont_serv = 0;

            $contenido_plan = "";
            if(isset($valor["servicios"])){
                //---
                #Ordeno el arreglo
                $valor["servicios"] = $this->ordenar_servicio_consulta($valor["servicios"]);

                //---
                foreach ($valor["servicios"] as $key => $value) {
                    if($value->eliminado==false){
                    //------------------------------
                        //----
                        #Armo la estructura de informacion de planes
                        //$id_plan = new MongoDB\BSON\ObjectId($value->plan);
                        #Consulto los planes
                        //$res_planes = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('_id'=>$id_plan))->get("planes");
                        //$titulo_plan = $res_planes[0]["titulo"];
                        
                        #Armo la estructura de informacion de servicios
                        $id_serv = new MongoDB\BSON\ObjectId($value->id_servicios);
                        
                        #Consulto los servicios
                        $res_serv = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('eliminado'=>false,'_id'=>$id_serv))->get("servicios");
                        if(count( $res_serv)>0){
                            $tipo_servicio = $res_serv[0]["tipo"];
                        
                            $descripcion_servicio = $res_serv[0]["descripcion"];
                            
                            $cuantos_items_paquetes = $value->valor;
                            
                            if($cont_serv == 0){
                                //armo el contenido por paquete
                                ($tipo_servicio=="N")? $contenido ="*".$cuantos_items_paquetes." ".$descripcion_servicio: $contenido = "*".$descripcion_servicio;
                            }else{
                                ($tipo_servicio=="N")? $contenido = "<br>*".$cuantos_items_paquetes." ".$descripcion_servicio: $contenido ="<br>*".$descripcion_servicio;
                            }
                            $cont_serv++;
                        }
                        //-------------------------------------------------------
                        $contenido_plan.= $contenido;
                        //-------------------------------------------------------
                    //------------------------------    
                    }
                }
            //---    
            }
            
            $valor["servicios"] = $contenido_plan;
            /*
            *
            */
            /*
            //--Consulto el plan
            $id_plan = new MongoDB\BSON\ObjectId($valor["id_plan"]);
            $res_planes = $this->mongo_db->where(array('_id'=>$id_plan))->get('planes');
            $valor["cod_planes"] = (string)$res_planes[0]["cod_planes"]; 
            $valor["descripcion_planes"] = (string)$res_planes[0]["titulo"]." ".$res_planes[0]["descripcion"]; 
            
            //--Consulto el servicio
            $id_servicio = new MongoDB\BSON\ObjectId($valor["id_servicio"]);  
            $res_servicio = $this->mongo_db->where(array('_id'=>$id_servicio))->get('servicios');
            $valor["cod_servicios"] = $res_servicio[0]["cod_servicios"];
            $valor["descripcion_servicios"] = (string)$res_servicio[0]["descripcion"]; 
           
            $valor["id_plan"] = (string)$valor["id_plan"];
            //-------------------------------------------------
               
            $valor["id_servicio"] = (string)$valor["id_servicio"];*/
            
            $listado[] = $valor;
        }    
        //--
        $listado2 = $listado;
        return $listado2;
    }
    /*
    *   Verificar si existe el paquete
    */
    public function verificar_existe_paquetes($plan,$servicio){

        $res = $this->mongo_db->where(array("id_plan"=>$plan,"id_servicio"=>$servicio,"eliminado"=>false))->get($this->tabla_paquetes);
        if($res){
            return count($res);
        }else{
            return 0;
        }
    } 
    /*
    *   buscar membresia
    */
    public function buscarMembresia($id){

        $res = $this->mongo_db->where(array("paquete"=>$id,"eliminado"=>false))->get("membresia");
        if($res){
            return count($res);
        }else{
            return 0;
        }
    }   
    /*
    *   verificar_paquetes_servicios
    */
    public function verificar_paquetes_servicios($codigo,$descripcion,$id){
        $codigo = strtoupper($codigo);
        $descripcion = strtoupper($descripcion);
        //--Para guardar
        if($id==""){
            //--
            if(($codigo!="")&&($descripcion!="")){
               $where = array("codigo"=>$codigo,"descripcion"=>$descripcion,"eliminado"=>false);
            }else if(($codigo=="")&&($descripcion!="")){//--Si solo descripcion es diferente de blanco
                $where = array("descripcion"=>$descripcion,"eliminado"=>false);
            }else if(($codigo!="")&&($descripcion=="")){//--Si solo codigo es diferente de blanco
                $where = array("codigo"=>$codigo,"eliminado"=>false);
            }
            $res = $this->mongo_db->where($where)->get($this->tabla_paquetes);
            //var_dump($descripcion);die('');
            //--
        }else{//--Para actualizar...
            $id_paquetes = new MongoDB\BSON\ObjectId($id);
            //-Si codigo y descripcion son diferentes de blanco
            if(($codigo!="")&&($descripcion!="")){
                $where = array("codigo"=>$codigo,"descripcion"=>$descripcion,"eliminado"=>false);
            }else if(($codigo=="")&&($descripcion!="")){//--Si solo descripcion es diferente de blanco
                $where = array("descripcion"=>$descripcion,"eliminado"=>false);
            }else if(($codigo!="")&&($descripcion=="")){//--Si solo codigo es diferente de blanco
                $where = array("codigo"=>$codigo,"eliminado"=>false);
            }

            $res = $this->mongo_db->where_ne('_id',$id_paquetes)->where($where)->get($this->tabla_paquetes);
        }
        //$res = $this->mongo_db->where($where)->get($this->tabla_paquetes);
        if($res){
            return count($res);
        }else{
            return 0;
        }
    }    
    /*
    *
    */ 
    public function registrar_paquetes($data){
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
        //Consulto el tipo de servicio para validar si debe ser entero o caracter
        //
        //-------------
        //--Modificaciones: 12/03/2019... cambio indicado por sr abrahans marzo 2019 original Noviembre 2018
        //$id_servicio = new MongoDB\BSON\ObjectId($data["id_servicio"]);

        //$res_servicios = $this->mongo_db->where(array('_id'=>$id_servicio))->get("servicios");
        //-------------
        //Si tipo es string y el valor es numerico
        //var_dump($res_servicios[0]["tipo"]);die();
        //var_dump(($res_servicios[0]["tipo"]=="N")&&(!is_numeric($data["valor"])));
        //var_dump(($res_servicios[0]["tipo"]=="C")&&(is_numeric($data["valor"])));die('');
        //-----------------------------------------------------------------------------------
        //--Modificaciones: 12/03/2019... cambio indicado por sr abrahans marzo 2019 original Noviembre 2018
        /*if(($res_servicios[0]["tipo"]=="C")&&(is_numeric($data["valor"]))){
            echo "<span>¡El valor asociado al paquete debe ser caracter!</span>";die('');
        }

        if(($res_servicios[0]["tipo"]=="N")&&(!is_numeric($data["valor"]))){
            echo "<span>¡El valor asociado al paquete debe ser numérico!</span>";die('');
        }*/
        //-----------------------------------------------------------------------------------
        $insertar1 = $this->mongo_db->insert($this->tabla_paquetes, $data);
        
        if($insertar1){
            return true;
        }else{
            return false;
        }   
        //echo json_encode("<span>El paquete se ha registrado exitosamente!</span>");
        //-----------------------------------------------------------------------------
    }
   
   
    /*
    *   Actualizar encabezado de paquetes Marzo 2019, cambio de alcance indicado x Sr Abrahans
    */
    public function actualizar_paquetes_encabezado($id_paquete,$data){
        
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id = new MongoDB\BSON\ObjectId($id_paquete);

        $mod_paquetes = $this->mongo_db->where(array('_id'=>$id))->set($data)->update($this->tabla_paquetes);

        
        //Auditoria...
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar paquete',
                                        'operacion'=>''
                                );
        $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_paquete))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes);
    }
    /*
    *   Actualizar detalle de paquetes Marzo 2019 , cambio de alcance indicado x Sr Abrahans
    */
    public function actualizar_paquetes_detalle($id_paquetes,$arreglo_servicios){
        //var_dump($arreglo_servicios);die('zzz');
        //Verifico si el servicio ya existe
        $id_p = new MongoDB\BSON\ObjectId($id_paquetes);
        //-----------------------------------------------
        foreach ($arreglo_servicios as $key => $value) {
            //----
            $datos_servicio = array(
                                        "servicios.$.posicion" => $value["posicion"],
                                        "servicios.$.valor" => $value["valor"],
                                        "servicios.$.eliminado" => false,
                                    ); 
            //----
            $where_array = array('_id' => $id_p, 'servicios.id_servicios' => $value["id_servicios"]);            
            //--consultar
            $res_planes_servicios_c = $this->mongo_db->where($where_array)->get($this->tabla_paquetes); 
            $res_planes_servicios = $this->mongo_db->where($where_array)->set($datos_servicio)->update($this->tabla_paquetes); 
            
           //var_dump(count($res_planes_servicios_c));echo"<br>";
            //Ver como valido que halla modificado
            if(count($res_planes_servicios_c)==0){
                $consulta2 = $this->mongo_db->get($this->tabla_paquetes); 
                $res_paquetes = $this->mongo_db->where(array('_id'=>$id_p))->push('servicios',$value)->update($this->tabla_paquetes);
                //var_dump($value);
            }
            
        }    
        //-----------------------------------------------
        /*foreach ($arreglo_servicios as $key => $value) {
            $res_paquetes = $this->mongo_db->where(array('_id'=>$id_p))->push('servicios',$value)->update($this->tabla_paquetes);
        }*/

    }
    /*
    *
    */
    public function actualizar_paquetes($id, $data)
    {
        /*$this->db->where('cod_esquema', $data['cod_esquema']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_esquema);
        if ($resultados->num_rows() == 0) {
            $this->db->where('id_esquema', $id);
            $this->db->update($this->tabla_esquema, $data);
            $datos=array(
                'usr_regmod' => $this->session->userdata('id_usuario'),
                'fec_regmod' => date('Y-m-d'),
            );
            $this->db->insert('auditoria', $datos);
            echo json_encode("<span>El esquema se ha editado exitosamente!</span>");
        } else {
            $array = $resultados->row();
            if ($array->id_esquema == $id) {
                $this->db->where('id_esquema', $id);
                $this->db->update($this->tabla_esquema, $data);
                $datos=array(
                    'usr_regmod' => $this->session->userdata('id_usuario'),
                    'fec_regmod' => date('Y-m-d'),
                );
                $this->db->insert('auditoria', $datos);
                echo json_encode("<span>El esquema se ha editado exitosamente!</span>");
            } else {
                echo "<span>¡Ya se encuentra registrado un esquema con el código ingresado!</span>";
            }
        }*/
         //---------------------------------------------------------------------------------
        //--Migracion MONGO DB
        //Consulto el tipo de servicio para validar si debe ser entero o caracter
        $res_servicios = $this->mongo_db->where(array('_id'=>$data["id_servicio"]))->get("servicios");
        
        if(($res_servicios[0]["tipo"]=="C")&&(is_numeric($data["valor"]))){
            echo "<span>¡El valor asociado al paquete debe ser caracter!</span>";die('');
        }

        if(($res_servicios[0]["tipo"]=="N")&&(!is_numeric($data["valor"]))){
            echo "<span>¡El valor asociado al paquete debe ser numérico!</span>";die('');
        }

        

        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_paquete = new MongoDB\BSON\ObjectId($id);        
        //--Consulto si existe el descuento
        $res_paquetes = $this->mongo_db->limit(1)->where(array('_id'=>$id_paquete))->get($this->tabla_paquetes);
        //var_dump($res_planes);die('');
        //Si el registro mantiene ĺos mismos campos
        if(count($res_paquetes)==0){
            //Actualizo los campos
            $mod_paquetes = $this->mongo_db->where(array('_id'=>$id_paquete))->set($data)->update($this->tabla_paquetes);
            //Auditoria...
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar planes',
                                            'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_paquete))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes);
             echo json_encode("<span>El Plan se ha editado exitosamente!</span>");
        }else{//Si cambian sus campos
            if ($res_paquetes[0]["_id"]->{'$id'} == $id) {
                //Actualizo los campos
                $mod_paquetes = $this->mongo_db->where(array('_id'=>$id_paquete))->set($data)->update($this->tabla_paquetes);
                //Auditoria...
                $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Modificar paquetes',
                                                'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_paquete))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes);
                //--
                echo json_encode("<span>El paquete se ha editado exitosamente!</span>");
            }else {
                echo "<span>¡Ya se encuentra registrado un paquete con las mismas características!</span>";
            }
        }
        //---------------------------------------------------------------------------------
    }

    public function eliminar_paquetes($id)
    {
        //------------------------------------------------------------
        //Migracion MONGO DB:
        //Octubre 2018
        /*$id_plan = $id;

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $datos = array(
                                    'eliminado'=>true,
                );

        $rs_paquetes = $this->mongo_db->where(array('id_plan'=>$id_plan,"eliminado"=>false))->get($this->tabla_paquetes);

        foreach ($rs_paquetes as $clave_paquete => $valor_paquete) {
            
            $id_paquete = $valor_paquete["_id"]->{'$id'};
            
            $id_pq = new MongoDB\BSON\ObjectId($id_paquete);
            
            $eliminar = $this->mongo_db->where(array('_id'=>$id_pq))->set($datos)->update($this->tabla_paquetes);
            //--Auditoria
            if($eliminar){
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar paquete',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_pq))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes); 
            }   
        }
        echo json_encode("<span>El paquete se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso*/
        //------------------------------------------------------------
        //--Tercer cambio segun lo expuesto requerimiento cambio de alcance marzo 2019- Original creado octubre 2018
        $id_paquete = new MongoDB\BSON\ObjectId($id);

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $datos = array(
                                    'eliminado'=>true,
                );
        #Reset para hacer update
        $res_reset = $this->mongo_db->get("mi_correo"); 
        //--
        $eliminar = $this->mongo_db->where(array('_id'=>$id_paquete))->set($datos)->update($this->tabla_paquetes);
        //--Auditoria
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar paquete',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_paquete))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes);
            #Actualizo las posiciones de los paquetes que quedaron 
        }   
        echo json_encode("<span>El paquete se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
        //-------------------------------------------------------------
    }

    public function status_paquetes($id, $status)
    {
        
        //------------------------------------------------------------
        //Migracion MONGO DB
        /*$id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
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

        $id_plan = $id;

        $rs_paquetes = $this->mongo_db->where(array('id_plan'=>$id_plan,"eliminado"=>false))->get($this->tabla_paquetes);

        foreach ($rs_paquetes as $clave_paquete => $valor_paquete) {
            
            $id_paquete = $valor_paquete["_id"]->{'$id'};
            
            $id_pq = new MongoDB\BSON\ObjectId($id_paquete);
            
            $modificar = $this->mongo_db->where(array('_id'=>$id_pq))->set($datos)->update($this->tabla_paquetes);
            //--Auditoria
            if($modificar){
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status paquetes',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes); 
            }
        }*/
        //------------------------------------------------------------
        //--Tercer cambio segun lo expuesto requerimiento cambio de alcance marzo 2019- Original creado octubre 2018
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

        $id_paquete = new MongoDB\BSON\ObjectId($id);
        $modificar = $this->mongo_db->where(array('_id'=>$id_paquete))->set($datos)->update($this->tabla_paquetes);
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status paquetes',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_paquete))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes); 
        }
        //------------------------------------------------------------
    }
    /*
    *   Eliminar paquetes
    */
    public function eliminar_multiple_paquetes($id_paquetes){
       /*
       *    Bloque anterior al cambio de alcance señalado en el requerimiento Marzo 2019
       */ 
        /*$eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
        $id = new MongoDB\BSON\ObjectId($paquetes);
        $datos = array(
                                'eliminado'=>true,
        );
        $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_paquetes);
        //--Auditoria
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar planes',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_planes);
            return true;
        }else{
            return false;
        }  */
        //--------------------------------------------------------------------------------    
        //echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //--------------------------------------------------------------------------------------
        //MIGRACION MONGO DB

        /*$id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        $datos = array(
                        'eliminado'=>true,
        );
        //consulto los planes
        $rs_planes = $this->mongo_db->where(array('id_plan'=>$id_planes,"eliminado"=>false))->get($this->tabla_paquetes);
        $cuantos_eliminar = 0;

        foreach ($rs_planes as $clave_planes => $valor_planes) {
            $id_paquete = new MongoDB\BSON\ObjectId($valor_planes["_id"]->{'$id'});
            $vector_paquetes[] = $id_paquete;
            $eliminar = $this->mongo_db->where(array('_id'=>$id_paquete,"eliminado"=>false))->set($datos)->update($this->tabla_paquetes);
        }
           
        
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar planes',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('id_plan'=>$id_planes))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes);
            return true;
        }else{
                return false;
        }   */
        //--------------------------------------------------------------------------------------

        $eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id_paquetes as $paquetes){
        //---------------------------------------------------------------------------------
        //Verifico si el paquete tiene membresias asociadas
            $existe_membresia = $this->buscarMembresia($paquetes); 
            if($existe_membresia>0){
            //--------------------------
                $noEliminados++;
            //--------------------------    
            }else{ 
                $vector_eliminados[] = $paquetes;
                $eliminados++;
            }   
            
            $datos = array(
                            'eliminado'=>true,
            );            
        } 
        if($eliminados>=1){
            foreach ($vector_eliminados as $clave_pq => $valor_pq) {
                $id = new MongoDB\BSON\ObjectId($valor_pq);
                $eliminar = $this->mongo_db->where(array('_id'=>$id,"eliminado"=>false))->set($datos)->update($this->tabla_paquetes);
                $res_reset = $this->mongo_db->get("mi_correo"); 
                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar paquetes',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes);
                }   
            }
            //--reset
            $res_reset = $this->mongo_db->get("mi_empresa"); 
            //--
            $this->actualizarPosicionesPaquetesEliminarMultiples();
        }        
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);   
    }
    /*
    *   Consultar paquetes planes
    */
    public function consultar_paquetes_planes($id_plan){
        //consulto los paquetes segun el id del plan
        $rs_paquetes = $this->mongo_db->where(array('id_plan'=>$id_plan,"eliminado"=>false))->get($this->tabla_paquetes);

        return $rs_paquetes;
    }
    /*
    *
    */
    public function status_multiple_paquetes($id, $datos)
    {
        /*$esquemas = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $esquemas . ") AND tabla = '" . $this->tabla_esquema . "'");*/
        //---------------------------------------------------------------------------
        //--Migracion Mongo DB
        $id_paquete =  new MongoDB\BSON\ObjectId($id);    

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $modificar = $this->mongo_db->where(array('_id'=>$id_paquete,"eliminado"=>false))->set($datos)->update($this->tabla_paquetes);
        if($modificar){
            $data_auditoria = "";
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status paquetes',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes); 
            return true;
        }else{
            return false;
        }
        //$rs2 = $this->mongo_db->get($this->tabla_paquetes);

        //var_dump($modificar);
        //var_dump((string)count($modificar)."-".(string)$id);echo("proximo<br>");

        /*foreach ($rs_paquetes as $clave_paquete => $valor_paquete) {

            $id_paquete = $valor_paquete["_id"]->{'$id'};
        
            $id_pq = new MongoDB\BSON\ObjectId($id_paquete);
        
            $modificar = $this->mongo_db->where(array('_id'=>$id_pq))->set($datos)->update($this->tabla_paquetes);
            //var_dump($modificar);
            //$modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_paquetes);
            //var_dump($modificar);die('');
            //--Auditoria
            if($modificar){
                $data_auditoria = "";
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status paquetes',
                                            'operacion'=>''
                                        );
                //$mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes); 
            }
        }  */  
    }
        //---------------------------------------------------------------------------

    public function listado_planes()
    {
       $resultados = $this->mongo_db->order_by(array('titulo' => 'ASC'))->where(array('eliminado'=>false))->get('planes');
       $listado = [];
       foreach ($resultados as $clave => $valor) {
           $valor["id_planes"] =  (string)$valor["_id"]->{'$id'};

           $id_pln = new MongoDB\BSON\ObjectId($valor["id_planes"]); 
           
           $valor["descripcion_planes"] = $valor["titulo"]." ".$valor["descripcion"];
           
           $res_paquete = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('eliminado'=>false,'id_plan'=>$id_pln))->get("paquetes");
           
           (count($res_paquete))? $valor["tiene_paquete"]=true:$valor["tiene_paquete"]=false;
           
           $listado[] = $valor; 
       }

       return $listado;
    }

    public function listado_servicios()
    {
       $resultados = $this->mongo_db->order_by(array('descripcion' => 'ASC'))->where(array('eliminado'=>false,'status'=>true))->get('servicios');
       $listado = [];
       foreach ($resultados as $clave => $valor) {
           $valor["id_servicios"] =  (string)$valor["_id"]->{'$id'};
           $listado[] = $valor; 
       }
       return $listado;
    }

    public function operaciones_servicios($id_paquetes){
        //---------------------------------------------------------------------------------
        //--Consulto paquetes asociados a ese plan 
        $id = new MongoDB\BSON\ObjectId($id_paquetes);     
        $res_paquete = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('eliminado'=>false,'_id'=>$id))->get("paquetes");
        //Si el plan cuenta con paquetes....
        $valores = [];
        $listado = [];
        if(count($res_paquete)>0){
            $servicios = $res_paquete[0]["servicios"];
            //----------------------------------------------------------------------------------
            //Recorro cada paquete.....
            foreach ($servicios as $clave_paquete => $valor_paquete) {
                if($valor_paquete->eliminado==false){
                    //-------------------------------------------------------------------------------
                    //Para el servicio        
                    $id_serv = new MongoDB\BSON\ObjectId($valor_paquete->id_servicios); 
                    //Consulto el servicio asociado a ese paquete asociado a ese plan    
                    $res_serv = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('eliminado'=>false,'_id'=>$id_serv))->get("servicios");

                    if($res_serv){
                        $valores["titulo_servicio"] = $res_serv[0]["descripcion"];
                        $valores["categoria"]       = $res_serv[0]["categoria"];
                    }
                    #$id_plan = new MongoDB\BSON\ObjectId($valor_paquete->plan);
                    #Consulto los planes
                    /*$res_planes = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('_id'=>$id_plan))->get("planes");
                    
                    $valores["id_plan"] = (string)$id_plan;
                    */
                    $valores["id_servicio"] = (string)$valor_paquete->id_servicios;

                    //$valores["titulo_plan"] = $res_planes[0]["titulo"]." ".$res_planes[0]["descripcion"];

                    //$valores["titulo_servicio"] = $res_serv[0]["descripcion"];

                    $valores["valor"] = $valor_paquete->valor;
                    (isset($valor_paquete->posicion))? $valores["posicion"] = $valor_paquete->posicion:  $valores["posicion"] = "";
                    (isset($valor_paquete->ilimitado))? $valores["ilimitado"] = $valor_paquete->ilimitado:  $valores["ilimitado"] = "";
                    (isset($valor_paquete->consumible))? $valores["consumible"] = $valor_paquete->consumible:  $valores["consumible"] = "";

                    $listado[] = $valores;
                    //------------------------------------------------------------------------------- 
                }
            }
            //-----------------------------------------------------------------------------------
        }
        return $listado;           
    }

    public function eliminar_paquete_servicio($id_servicio){
        //------------------------------------------------------------
        //Migracion MONGO DB

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $datos = array(
                                    'eliminado'=>true,
                );
        $eliminar = $this->mongo_db->where(array('id_servicio'=>$id_servicio,"eliminado"=>false))->set($datos)->update($this->tabla_paquetes);
        //var_dump($eliminar);die('');
        //--Auditoria
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar paquete',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('id_servicio'=>$id_servicio))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes); 
            echo json_encode("<span>¡El registro se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso

        }  
    }
    /*
    * Metodo eliminar_planes_servicios: creado por motivo cambio alcance señalado por el Sr Abrahans Marzo 2019
    */
    /*Este no funciona no se por que...*/
    /*public function eliminar_planes_servicios($where_array,$id_servicio){
        //------------------------------------------------------------
        //Migracion MONGO DB

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        //-------------------------------------------------------------
        $datos = array(
                        'servicios.$.eliminado'=>true,
                        );

        $res_planes_servicios = $this->mongo_db->where($where_array)->set($datos)->update("paquetes");   
        var_dump($where_array);die(''); 
        var_dump($data);
        //die(''); 
        //Auditoria...
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar servicio asociado al paquete ',
                                        'operacion'=>''
                                );
        $mod_auditoria = $this->mongo_db->where(array('servicios.id_servicios' => $id_servicio))->push('servicios.$.auditoria',$data_auditoria)->update($this->tabla_paquetes);
        //var_dump($mod_auditoria);die('');
        echo json_encode("<span>Los datos se han eliminado exitosamente!</span>");
        //-------------------------------------------------------------
    }*/
    /*Este si va a funcionar*/
    /*
    *   Actualizar datos trabajadores
    */
    public function eliminar_planes_servicios($id_servicios, $id_paquete){//($where_array,$data){
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        //--
        /*$res_membresia = $this->mongo_db->where(array('trabajadores.$.id_membresia'=>$id_membresia,'trabajadores.$.serial_acceso'=>$data["serial_acceso"],"trabajadores.$.eliminado"=>false))->set($data)->update("membresia.trabajadores");*/

        $id_paquete = new MongoDB\BSON\ObjectId($id_paquete);

        $where_array = array('_id' => $id_paquete, 'servicios.id_servicios' => $id_servicios,'servicios.eliminado'=>false);
        $data = array('servicios.$.eliminado' => true);

        //comente esto mientras pruebo actualizar pos
        $res_planes_servicios = $this->mongo_db->where($where_array)->set($data)->update("paquetes"); 
        
        //Auditoria...
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar servicio en paquete ',
                                        'operacion'=>''
                                );
        //comente esto mientras pruebo actualizar pos
        $mod_auditoria = $this->mongo_db->where($where_array)->push('servicios.$.auditoria',$data_auditoria)->update("paquetes");
        $actualizarPos = $this->actualizarPosServ($id_paquete,$id_servicios);
        echo json_encode("<span>Los datos se han eliminado exitosamente!</span>");
    }
    /*
    *   actualizarPosServ
    */
    public function actualizarPosServ($id_paquete,$id_servicios){
        $id = new MongoDB\BSON\ObjectId($id_paquete);     
        $res_paquete = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('eliminado'=>false,'_id'=>$id))->get("paquetes");
        $servicios = $res_paquete[0]["servicios"];
        //Organizo y excluyo al servicio eliminado
        foreach ($servicios as $clave_servicios => $valor_servicios) {
            
            if(($valor_servicios->id_servicios!=$id_servicios)&&($valor_servicios->eliminado==false)){
                
                $arr_servicios[] = (array)$valor_servicios;

            }

        }

        $nvaPosicion = 1;
        $this->array_sort_by($arr_servicios,"posicion",$order = SORT_ASC);
        //var_dump($servicios);die('');
        //echo '<pre>' . var_export($arr_servicios, true) . '</pre>';die;
        foreach ($arr_servicios as $clave_servicios => $valor_servicios) {
            //---
            $valor_servicios["posicion"] = $nvaPosicion;
            $id_servicios = $valor_servicios["id_servicios"];
            $arr_servicios_def[] = $valor_servicios;
            //---
            $where_array = array('_id' => $id_paquete, 'servicios.id_servicios' => $id_servicios,'servicios.eliminado'=>false);
            $data = array('servicios.$.posicion' => $nvaPosicion);
            //--
            #Reset para hacer update
            $res_reset = $this->mongo_db->get("mi_correo"); 
            #Update de posicion
            $res_planes_servicios = $this->mongo_db->where($where_array)->set($data)->update("paquetes"); 
            //---
            $nvaPosicion++;

        }
        return true;
        //echo '<pre>' . var_export($arr_servicios_def, true) . '</pre>';die(" xxx");
    }
    /*
    *   Actualizar posiciones paquetes al eliminar
    */
    public function actualizarPosicionesPaquetesEliminar($id_el){
        $cuantos_paquetes = 0;
        $nvaPosicion = 0;
        #consulto todos los paquetes
        $res_paquete = $this->mongo_db->order_by(array('posicion_paquetes' => 'ASC'))->get($this->tabla_paquetes);
        //var_dump($res_paquete);die('aqui!');
        foreach ($res_paquete as $clave_paquete => $valor_paquete) {
            $id_paquete = $valor_paquete["_id"]->{'$id'};
            if((!$valor_paquete["eliminado"])&&($id_paquete!=$id_el)){
                #recorro los paquetes
                $nvaPosicion++;
                $id_paquete_mdb = new MongoDB\BSON\ObjectId($id_paquete); 
                //--
                #Reset para hacer update
                $res_reset = $this->mongo_db->get("mi_correo"); 
                #Update de posicion
                $data = array('posicion_paquetes' => $nvaPosicion);
                $res_mod_paquetes = $this->mongo_db->where(array('_id' => $id_paquete_mdb))->set($data)->update("paquetes"); 
                //--  
                $cuantos_paquetes++;
            }
        }
        //var_dump($cuantos_paquetes);die("aqui!");
    }
    /*
    *   Actualizar posiciones paquetes al eliminar Multiples
    */
    public function actualizarPosicionesPaquetesEliminarMultiples(){
        $cuantos_paquetes = 0;
        $nvaPosicion = 0;
        #consulto todos los paquetes
        $res_paquete = $this->mongo_db->order_by(array('posicion_paquetes' => 'ASC'))->get($this->tabla_paquetes);
        //var_dump($res_paquete);die('aqui!');
        foreach ($res_paquete as $clave_paquete => $valor_paquete) {
            $id_paquete = $valor_paquete["_id"]->{'$id'}; 
            //&&($id_paquete!=$id_el))
            if(!$valor_paquete["eliminado"]){
                #recorro los paquetes
                $nvaPosicion++;
                $id_paquete_mdb = new MongoDB\BSON\ObjectId($id_paquete); 
                //--
                #Reset para hacer update
                $res_reset = $this->mongo_db->get("mi_correo"); 
                #Update de posicion
                $data = array('posicion_paquetes' => $nvaPosicion);
                $res_mod_paquetes = $this->mongo_db->where(array('_id' => $id_paquete_mdb))->set($data)->update("paquetes"); 
                //--  
                $cuantos_paquetes++;
            }
        }
        //var_dump($cuantos_paquetes);die("aqui!");
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
    *   generarPosiciones
    */
    public function generarPosiciones(){
        $cuantos_paquetes = 0;
        #consulto todos los paquetes
        $res_paquete = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array("eliminado"=>false))->get("paquetes");
        foreach ($res_paquete as $clave_paquete => $valor_paquete) {
            $servicios = $valor_paquete["servicios"];
            #recorro los servicios
            $nvaPosicion = 0;
            $id_paquete = $valor_paquete["_id"]->{'$id'};
            $id_paquete_mdb = new MongoDB\BSON\ObjectId($id_paquete);   
            foreach ($servicios as $clave_servicios => $valor_servicios) {
                #Si no tiene campo posicion lo edito
                if($valor_servicios->eliminado==false){
                    $nvaPosicion++;
                    $id_servicios = $valor_servicios->id_servicios;
                    $data = array('servicios.$.posicion' => $nvaPosicion);
                    $where_array = array('_id' => $id_paquete_mdb, 'servicios.id_servicios' => $id_servicios,'servicios.eliminado'=>false);
                    //--
                    #Reset para hacer update
                    $res_reset = $this->mongo_db->get("mi_correo"); 
                    #Update de posicion
                    $res_planes_servicios = $this->mongo_db->where($where_array)->set($data)->update("paquetes"); 
                    //--
                    
                }
            }
            $cuantos_paquetes++;
        }
        echo "Se actualizaron: ".$cuantos_paquetes;die("All right!");
    }
    /*
    *
    */
    /*
    *   verificar_servicios_horas
    */
    public function verificar_servicios_horas($servicios){   
        //---
        $res_tipo_serv = $this->mongo_db->where(array('eliminado'=>false,'titulo'=>"HORAS DE COWORKING"))->get('tipo_servicios');
        $tipo_horas_coworking =  $res_tipo_serv[0]["_id"]->{'$id'};
        foreach ($servicios as $key => $value) {
            $id = new MongoDB\BSON\ObjectId($value);
            //-Consulto el tipo de coworking
            $res_serv = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id,'tipo_servicio'=>$tipo_horas_coworking))->get('servicios');

            if(count($res_serv)>0){
                return count($res_serv);
            }
        }
        return 0;
    }
    /*
    *   Verificar servicios horas actualizar
    */ 
    public function verificar_servicios_horasActualizar($id_paquetes,$servicios_actualizar){
        $id = new MongoDB\BSON\ObjectId($id_paquetes);     
        $res_paquete = $this->mongo_db->order_by(array('_id' => 'ASC'))->where(array('eliminado'=>false,'_id'=>$id))->get("paquetes");
        //Si el plan cuenta con paquetes....
      
        if(count($res_paquete)>0){
            $servicios = $res_paquete[0]["servicios"];
            foreach ($servicios as $key_serv => $value_serv) {
                if($value_serv->eliminado==false){
                    $servicios_actualizar[]= $value_serv->id_servicios;
                }    
            }
        }
        //var_dump($servicios_actualizar);die('');
        //---------------------------------------------
        $res_tipo_serv = $this->mongo_db->where(array('eliminado'=>false,'titulo'=>"HORAS DE COWORKING"))->get('tipo_servicios');
        $tipo_horas_coworking =  $res_tipo_serv[0]["_id"]->{'$id'};
        foreach ($servicios_actualizar as $key => $value) {
           
                $id = new MongoDB\BSON\ObjectId($value);
                //-Consulto el tipo de coworking
                $res_serv = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id,'tipo_servicio'=>$tipo_horas_coworking))->get('servicios');

                if(count($res_serv)>0){
                    return count($res_serv);
                }
        }
        return 0;
        //---------------------------------------------    
    }
    
    /***/
    public function contar_modulos()
    {
        /*
        *   Migracion mongo db
        */
        $res = $this->mongo_db->where(array("eliminado"=>false))->get('paquetes');
        return $res; 
    }
    /***/
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
                $resultado = $this->mongo_db->where_gte('posicion_paquetes', (int)$posicionar['posicion'])->where(array("eliminado"=>false))->get('paquetes');
                //var_dump($resultado);die();
                if(count($resultado)>0){
                    foreach ($resultado as $key => $value) {
                        $datos=array(
                            'posicion_paquetes' => $value["posicion_paquetes"] + 1,
                        );
                        $id = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});

                        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update('paquetes');

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
                $resultado = $this->mongo_db->where_gt('posicion_paquetes', (integer)$posicionar['inicial'])->where_lte('posicion_paquetes', (integer)$posicionar['final'])->where(array("eliminado"=>false))->get('paquetes');
                //var_dump($resultado);die('');
                //--
            
                if(count($resultado)>0){       
                    foreach ($resultado as $key => $value) {
    
                        $datos=array(
                            'posicion_paquetes' => $value["posicion_paquetes"] - 1,
                        );

                        $id = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});

                        $modificar1 = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update('paquetes');
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
                $resultado = $this->mongo_db->where_gte('posicion_paquetes', $posicionar['final'])->where_lt('posicion_paquetes', $posicionar['inicial'])->where(array("eliminado"=>false))->get('paquetes');
                //var_dump(count($resultado));die('');               
                if(count($resultado)>0){    
                    foreach ($resultado as $key => $value) {
                        
                        $datos=array(
                            'posicion_paquetes' => $value["posicion_paquetes"] + 1,
                        );

                        //var_dump($datos); 
                        $id = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});

                        $modificar1 = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update('paquetes');
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
    public function generarPosicionesPaquetes(){
        $cuantos_paquetes = 0;
        $nvaPosicion = 0;

        #consulto todos los paquetes
        $res_paquete = $this->mongo_db->order_by(array('_id' => 'ASC','eliminado'=>false))->where(array("eliminado"=>false))->get("paquetes");
        foreach ($res_paquete as $clave_paquete => $valor_paquete) {
            #recorro los paquetes
            $nvaPosicion++;
            $id_paquete = $valor_paquete["_id"]->{'$id'};
            $id_paquete_mdb = new MongoDB\BSON\ObjectId($id_paquete); 
            //--
            #Reset para hacer update
            $res_reset = $this->mongo_db->get("mi_correo"); 
            #Update de posicion
            $data = array('posicion_paquetes' => $nvaPosicion);
            $res_mod_paquetes = $this->mongo_db->where(array('_id' => $id_paquete_mdb))->set($data)->update("paquetes"); 
            //--  
            $cuantos_paquetes++;
        }
        echo "Se actualizaron:  ".$cuantos_paquetes;die(" All right!");
    }
    /***/
    public function generarMostrarWebPaquetes(){
        //--
        $cuantos_paquetes = 0;
        #consulto todos los paquetes
        $res_paquete = $this->mongo_db->order_by(array('_id' => 'ASC'))->get("paquetes");
        foreach ($res_paquete as $clave_paquete => $valor_paquete) {
            #recorro los paquetes
            $id_paquete = $valor_paquete["_id"]->{'$id'};
            $id_paquete_mdb = new MongoDB\BSON\ObjectId($id_paquete); 
            //--
            #Reset para hacer update
            $res_reset = $this->mongo_db->get("mi_correo"); 
            #Update de posicion
            $data = array('muestra_en_web' => true);
            $res_mod_paquetes = $this->mongo_db->where(array('_id' => $id_paquete_mdb))->set($data)->update("paquetes"); 
            //--  
            $cuantos_paquetes++;
        }
        echo "Se actualizaron:  ".$cuantos_paquetes;die(" All right!");
        //--
    }
}
