<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Temporadas_model extends CI_Model
{

    private $tabla_temporadas = "temporadas";
    private $tabla_planes = "planes";
    private $tabla_mapas_temporadas = "mapas_temporadas";
    /*
    *   Registrar temporadas
    */
    public function registrar_temporadas($data){
        //-----------------------------------------------------------------------------
        //Migracion MONGO DB
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $operacion = $data["operacion"];
        //Realizo la insercion

        $insertar1 = $this->mongo_db->insert($this->tabla_temporadas, $data);
        //Inserto en mapa_temporadas
        //--Consulto los planes activos....
        $res_planes = $this->mongo_db->where(array('status'=>true,'eliminado'=>false))->get($this->tabla_planes);
        //--Debo buscar el ultimo id registrado...
        $res_temporadas = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_temporadas);
        $id_temporada = new MongoDB\BSON\ObjectId($res_temporadas[0]["_id"]->{'$id'});
        //--Recorro planes
        foreach ($res_planes as $clave => $valor) {
            //$id_temporadas = new MongoDB\BSON\ObjectId($valor["_id"]->{'$id'} );
            
            $ajuste = ($valor["precio"]*$res_temporadas[0]["ajuste"])/100;
            
            if($operacion=="mas"){
                $costo_temporada = (float)$valor["precio"]+(float)$ajuste;    
            }else{
                $costo_temporada = (float)$valor["precio"]-(float)$ajuste;
            }
           
            $id_planes = new MongoDB\BSON\ObjectId($valor["_id"]->{'$id'} );
            
            $data = array(
              'id_planes' => $id_planes,
              'id_temporadas' =>$id_temporada,
              'ajuste'=>$ajuste,
              'costo_temporada'=>$costo_temporada,
              'status' => true,
              'eliminado' => false,
              'auditoria' => [array(
                                        "cod_user" => $id_usuario,
                                        "nomuser" => $this->session->userdata('nombre'),
                                        "fecha" => $fecha,
                                        "accion" => "Nuevo registro mapa temporadas",
                                        "operacion" => ""
                                    )]
            );
            //--Guardo data en la coleccion mapa_temporadas....
            $insertar = $this->mongo_db->insert($this->tabla_mapas_temporadas, $data);
            //--
        }
        //--
        echo json_encode("1");
        //-----------------------------------------------------------------------------
    }
    /*
    *   Modificar temporadas
    */
    public function modificar_temporadas($data){
        //-----------------------------------------------------------------------------
        //Migracion MONGO DB
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $operacion = $data["operacion"];
        //Realizo la modificacion

        $id_temporada = new MongoDB\BSON\ObjectId($data["id"]);

        $mod_temporadas = $this->mongo_db->where(array('_id'=>$id_temporada))->set($data)->update($this->tabla_temporadas);
        //Modifico en mapa_temporadas
        //--Consulto los planes activos....
        $res_planes = $this->mongo_db->where(array('status'=>true,'eliminado'=>false))->get($this->tabla_planes);
        //--Recorro planes
        foreach ($res_planes as $clave => $valor) {
            //$id_temporadas = new MongoDB\BSON\ObjectId($valor["_id"]->{'$id'} );
            
            $ajuste = ($valor["precio"]*$data["ajuste"])/100;
            
            if($operacion=="mas"){
                $costo_temporada = (float)$valor["precio"]+(float)$ajuste;    
            }else{
                $costo_temporada = (float)$valor["precio"]-(float)$ajuste;
            }
           
            $id_planes = new MongoDB\BSON\ObjectId($valor["_id"]->{'$id'} );
            
            $data = array(
              'id_planes' => $id_planes,
              'id_temporadas' =>$id_temporada,
              'ajuste'=>$ajuste,
              'costo_temporada'=>$costo_temporada,
              'status' => true,
              'eliminado' => false
            );
            //--Actualizo data en la coleccion mapa_temporadas....
            $mod_mapas_temporadas = $this->mongo_db->where(array('id_temporada'=>$id_temporada,'id_planes'=>$id_planes))->set($data)->update($this->tabla_mapas_temporadas); 
            //--
        }
        //--
        echo json_encode("1");
        //-----------------------------------------------------------------------------
    }
    public function consultarMapasTemporadas($numero){
            $listado = [];
            //--Consulto cual es el id para ese numero de temporada
            $res_temporadas = $this->mongo_db->where(array('temporada'=>(integer)$numero))->get($this->tabla_temporadas);
            if(count($res_temporadas)>0){
            //-----------------------------
                $id = $res_temporadas[0]["_id"]->{'$id'};  
                $id_temporada = new MongoDB\BSON\ObjectId($id);  
                //--Consulto el mapa de temporadas
                $res_mapas_temporadas = $this->mongo_db->where(array('id_temporadas'=>$id_temporada,'status'=>true,'eliminado'=>false))->get($this->tabla_mapas_temporadas);

                foreach ($res_mapas_temporadas as $clave => $valor) {
                    //Consulto el plan
                    
                    $id_planes = new MongoDB\BSON\ObjectId($valor["id_planes"]);
                    
                    $res_planes = $this->mongo_db->where(array('_id'=>$id_planes,'status'=>true,'eliminado'=>false))->get($this->tabla_planes);
                        //--Organizo los campos
                        $valor["codigo_plan"] = $res_planes[0]["cod_planes"];
                        $valor["nombre_plan"] = $res_planes[0]["descripcion"];
                        $valor["temporada"] = $res_temporadas[0]["temporada"];
                        $valor["fecha_desde"] = $res_temporadas[0]["fecha_desde"]->toDateTime()->format('d-m-Y');
                        $valor["fecha_hasta"] = $res_temporadas[0]["fecha_hasta"]->toDateTime()->format('d-m-Y');
                        $valor["costo_original"] = $res_planes[0]["precio"];
                        $listado[] = $valor;
                        //--
                }
            //-----------------------------    
            }    
            
           return $listado;
    }

    /*
    *   Consulta de temporadas existentes....
    */
    public function consultarTemporadasExistentes(){
        $listado = [];
        $res_temporadas = $this->mongo_db->where(array('status'=>true,'eliminado'=>false))->get($this->tabla_temporadas);
            $c = 1;
        foreach ($res_temporadas as $clave => $valor) {
            $valor["fecha_desde"] = $valor["fecha_desde"]->toDateTime()->format('d-m-Y');
            $valor["fecha_hasta"] = $valor["fecha_hasta"]->toDateTime()->format('d-m-Y');
            $valor["id_temporadas"] = (string)$valor["_id"]->{'$id'};
            $valor["fila"] = $c;
            $c++;
            $listado[] = $valor;        
        }    
        return $listado;
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
        $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_paquetes);
        //--Auditoria
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar paquete',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes); 
            echo json_encode("<span>El paquete se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
        }
        //------------------------------------------------------------
    }

    public function status_paquetes($id, $status)
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
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_paquetes);
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
        //------------------------------------------------------------
    }

    public function eliminar_multiple_paquetes($id_planes)
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
        $eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id_planes as $planes){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            $id = new MongoDB\BSON\ObjectId($planes);
            $datos = array(
                                    'eliminado'=>true,
            );
            $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_paquetes);
            //--Auditoria
            if($eliminar){
                $eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar planes',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes);
            }else{
                $noEliminados++;
            }   
            //----------------------------------------------------------------------------------    

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //----------------------------------------------------------------------------

    }

    public function status_multiple_paquetes($id, $status)
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
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_paquetes);
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
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_paquetes); 
            }
        }
        //---------------------------------------------------------------------------

    }

    public function listado_planes()
    {
       $resultados = $this->mongo_db->where(array('eliminado'=>false))->get('planes');
       foreach ($resultados as $clave => $valor) {
           $valor["id_planes"] =  (string)$valor["_id"]->{'$id'};
           $listado[] = $valor; 
       }
       return $listado;
    }

    public function listado_servicios()
    {
       $resultados = $this->mongo_db->where(array('eliminado'=>false))->get('servicios');
       foreach ($resultados as $clave => $valor) {
           $valor["id_servicios"] =  (string)$valor["_id"]->{'$id'};
           $listado[] = $valor; 
       }
       return $listado;
    }

}
