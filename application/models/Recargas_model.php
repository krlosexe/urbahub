<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Recargas_model extends CI_Model
{

    private $tabla_recargas = "recargas";
    private $tabla_lval = "lval";
    private $tabla_esquema = "esquemas";

    public function listar_recargas()
    {
        //$this->db->select('d.*, a.fec_regins, u.correo_usuario, a.status, tp.descriplval as tipoPlazo, tv.descriplval as tipoVendedor');
        //$this->db->join($this->tabla_lval . ' tp', 'd.tipo_plazo = tp.codlval');
        //$this->db->join($this->tabla_lval . ' tv', 'd.tipo_vendedor = tv.codlval');

        /*$this->db->where('a.tabla', $this->tabla_recargas);
        $this->db->select('d.*, a.fec_regins, u.correo_usuario, a.status');
        $this->db->from($this->tabla_recargas . ' d');
        $this->db->join('auditoria a', 'd.id_recarga = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');

        $resultados = $this->db->get();
        return $resultados->result();*/

        //------------------------------------------------------------------------------
        //--Modificacion con Mongo db
        //---------------------------------------------------------------------------
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get($this->tabla_recargas);
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
            $valor["correo_usuario"] = $res_us[0]["correo_usuario"];
            $valor["status"] = $valor["status"];
            $valor["id_recarga"] = (string)$valor["_id"]->{'$id'};
            //$recarga = $this->redondeado($valor["recarga"],3);
            $valor["recarga"] = (string)$valor["recarga"];
            $listado[] = $valor;
        }    
        //--
        $listado2 = $listado;
        return $listado2;
        //---------------------------------------------------------------------------
    }   
    /*
    *   Redondeado
    */
    public function redondeado ($numero, $decimales) { 
       var_dump($numero);var_dump($decimales); 
       $factor = pow(10, $decimales); 
       return (round($numero*$factor)/$factor); 
    }
    /*
    *   
    */
    
    /*
    *
    */
     public function getRecargosCorrida($plazo, $tipo_vendedor, $proyecto)
        {
            $this->db->where('a.tabla', $this->tabla_recargas);
            $this->db->select('r.id_recarga, r.recarga, es.descripcion as esquema');
            $this->db->from($this->tabla_recargas . ' r');
            $this->db->join('auditoria a', 'r.id_recarga = a.cod_reg');
            $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
            $this->db->join($this->tabla_lval . ' tp', 'r.tipo_plazo = tp.codlval');
            $this->db->join($this->tabla_lval . ' tv', 'r.tipo_vendedor = tv.codlval');
            $this->db->join('esquemas es', 'es.id_esquema = r.cod_esquema');
            $this->db->join('proyectos_esquemas pe', 'pe.id_esquema = es.id_esquema');
            $this->db->where('tipo_plazo', $plazo);
            $this->db->where('tipo_vendedor', $tipo_vendedor);
            $this->db->where('pe.id_proyecto', $proyecto);
            $resultados = $this->db->get();
            return $resultados->result();
        } 
        
    public function registrar_recarga($data){
        /*$this->db->where('tipo_plazo', $data['tipo_plazo']);
        $this->db->where('tipo_vendedor', $data['tipo_vendedor']);
        $this->db->where('recarga', $data['recarga']);
        $this->db->where('cod_esquema', $data['cod_esquema']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_recargas);
        if ($resultados->num_rows() == 0) {
            $this->db->insert($this->tabla_recargas, $data);
            $datos=array(
                'tabla' => $this->tabla_recargas,
                'cod_reg' => $this->db->insert_id(),
                'usr_regins' => $this->session->userdata('id_usuario'),
                'fec_regins' => date('Y-m-d'),
            );
            $this->db->insert('auditoria', $datos);
            echo json_encode("<span>El recargo ha sido registrado exitosamente!</span>");
        } else {
            echo "<span>¡Ya se encuentra registrado un recargo con las mismas características!</span>";
        }*/
        //--------------------------------------------------------------------------------
        //Migracion MongoDB
        //--------------------------------------------------------------------------------
        $res_recarga = $this->mongo_db->limit(1)->where(array('tipo_plazo'=>$data['tipo_plazo'],'tipo_vendedor'=>$data['tipo_vendedor'],'recarga'=>$data['recarga'],'cod_esquema'=>$data['cod_esquema'],"eliminado"=>false))->get($this->tabla_recargas);    
        if(count($res_recarga) == 0){
            $insertar1 = $this->mongo_db->insert($this->tabla_recargas, $data);
            echo json_encode("<span>El recargo ha sido registrado exitosamente!</span>");
        }else{
            echo "<span>¡Ya se encuentra registrado un recargo con las mismas características!</span>";
        }
        //--------------------------------------------------------------------------------
    }

    public function actualizar_recarga($id, $data)
    {
        /*$this->db->where('tipo_plazo', $data['tipo_plazo']);
        $this->db->where('tipo_vendedor', $data['tipo_vendedor']);
        $this->db->where('recarga', $data['recarga']);
        $this->db->where('cod_esquema', $data['cod_esquema']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_recargas);
        if ($resultados->num_rows() == 0) {
            $this->db->where('id_recarga', $id);
            $this->db->update($this->tabla_recargas, $data);
            $datos=array(
                'usr_regmod' => $this->session->userdata('id_usuario'),
                'fec_regmod' => date('Y-m-d'),
            );
            $this->db->insert('auditoria', $datos);
            echo json_encode("<span>El recargo se ha editado exitosamente!</span>");
        } else {
            $array = $resultados->row();
            if ($array->id_recarga == $id) {
                $this->db->where('id_recarga', $id);
                $this->db->update($this->tabla_recargas, $data);
                $datos=array(
                    'usr_regmod' => $this->session->userdata('id_usuario'),
                    'fec_regmod' => date('Y-m-d'),
                );
                $this->db->insert('auditoria', $datos);
                echo json_encode("<span>El recargo se ha editado exitosamente!</span>");
            } else {
                echo "<span>¡Ya se encuentra registrado un recargo con las mismas características!</span>";
            }
        }*/
        //-------------------------------------------------------------------------------------
        //MIGRACION MONGO DB
        //-------------------------------------------------------------------------------------
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_recargas = new MongoDB\BSON\ObjectId($id);

        //--
        $res_recargas = $this->mongo_db->limit(1)->where(array('tipo_plazo'=>$data['tipo_plazo'],'tipo_vendedor'=>$data['tipo_vendedor'],'recarga'=>$data['recarga'],'cod_esquema'=>$data['cod_esquema'],"eliminado"=>false))->get($this->tabla_recargas);    
        if(count($res_recargas) == 0){
            //Actualizo los campos
            $mod_recargas = $this->mongo_db->where(array('_id'=>$id_recargas))->set($data)->update($this->tabla_recargas);
            //Auditoria...
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar recarga',
                                            'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_recargas))->push('auditoria',$data_auditoria)->update($this->tabla_recargas);
            echo json_encode("<span>El recargo se ha editado exitosamente!</span>");

        }else{
            if ($res_recargas[0]["_id"]->{'$id'} == $id) {
                //Actualizo los campos
                $mod_recargas = $this->mongo_db->where(array('_id'=>$id_recargas))->set($data)->update($this->tabla_recargas);
                //Auditoria...
                $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Modificar recarga',
                                                'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_recargas))->push('auditoria',$data_auditoria)->update($this->tabla_recargas);
                //--
                echo json_encode("<span>El recargo se ha editado exitosamente!</span>");

            }else {
               echo "<span>¡Ya se encuentra registrado un recargo con las mismas características!</span>";
            }
        }
        //-------------------------------------------------------------------------------------
    }

    public function eliminar_recarga($id)
    {
        /*try { 
            if(!$this->db->delete($this->tabla_recargas, array('id_recarga' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_recargas));
                echo json_encode("<span>El recargo se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
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
        $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_recargas);
        //--Auditoria
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar recargo',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_recargas); 
            echo json_encode("<span>El recargo se ha eliminado exitosamente!</span>"); 
            // envio de mensaje exitoso
        }
        //------------------------------------------------------------
    }

    public function status_recarga($id, $status)
    {
        /*$datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_recargas);
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
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_recargas);
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status recarga',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_recargas); 
        }
        //------------------------------------------------------------
    }

    public function eliminar_multiple_recargas($id_recargas)
    {
        /*$eliminados=0;
        $noEliminados=0;
        foreach($id as $recarga)
        {
            if($this->db->delete($this->tabla_recargas, array('id_recarga' => $recarga))){
                $this->db->delete('auditoria', array('cod_reg' => $recarga, 'tabla' => $this->tabla_recargas));
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
        foreach($id_recargas as $recarga){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            $id = new MongoDB\BSON\ObjectId($recarga);
            $datos = $data=array(
                                    'eliminado'=>true,
            );

            $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_recargas);
            
            //--Auditoria
            if($eliminar){
                $eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar recargo',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_recargas);
            }else{
                $noEliminados++;
            }   
        //---------------------------------------------------------------------------------- 
        }   
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_recargas($id, $status)
    {
        /*$recarga = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $recarga . ") AND tabla='" . $this->tabla_recargas . "'");*/
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
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_recargas);
            //var_dump($modificar);die('');
            //--Auditoria
            if($modificar){
                $data_auditoria = "";
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status comision',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_recargas); 
            }
        }
        //---------------------------------------------------------------------------
    }

    public function tipos_plazos()
    {
        $this->db->where('tipolval', 'TIPOPLAZOS');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

    public function tipos_vendedores()
    {
        $this->db->where('tipolval', 'TIPOVENDEDOR');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

    public function esquemas($tipo){
        /*$this->db->where(array( 'a.tabla'        => $this->tabla_esquema,
                            'lv.tipolval'    => 'ESQUEMAS',
                            'lv.descriplval' => 'RECARGOS',
                            'a.status'       => 1));*/
        //$this->db->join('lval lv', 'lv.codlval = e.tipo');

        /*$this->db->where(array( 'a.tabla'        => $this->tabla_esquema,
                            'a.status'       => 1));                    
        $this->db->select('e.*');
        $this->db->from($this->tabla_esquema . ' e');
        $this->db->join('auditoria a', 'e.id_esquema = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //--------------------------------------------------------------
        //Migracion Mongo DB
        //--------------------------------------------------------------
        $resultados = $this->mongo_db->where(array('tipo'=>$tipo,'eliminado'=>false,'status'=>true))->get($this->tabla_esquema);
        $listado = [];
        foreach ($resultados as $clave => $valor) {
            $valor["id_esquema"] = (string)$valor["_id"]->{'$id'};
            $valor["tipo"] = (integer)$valor["tipo"];
            $listado[] = $valor;
        }
        return $listado;
        //--------------------------------------------------------------
    }
    //--------------------------------------------------------------------
    public function esquemas_consulta(){
        //--------------------------------------------------------------
        //Migracion Mongo DB
        //--------------------------------------------------------------
        $resultados = $this->mongo_db->where(array('eliminado'=>false))->get($this->tabla_esquema);
        $listado = [];
        foreach ($resultados as $clave => $valor) {
            $valor["id_esquema"] = (string)$valor["_id"]->{'$id'};
            $valor["tipo"] = (integer)$valor["tipo"];
            $listado[] = $valor;
        }
        return $listado;
    }
    //--------------------------------------------------------------------
}