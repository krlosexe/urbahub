<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Comision_model extends CI_Model
{

    private $tabla_comision = "comisiones";
    private $tabla_lval = "lval";
    private $tabla_esquema = "esquemas";

    public function listado_comision()
    {
        /*$this->db->where('a.tabla', $this->tabla_comision);
        $this->db->select('c.*,e.descripcion as nombre_esquema,a.fec_regins, u.correo_usuario, a.status,c.id_vendedor, c.tipo_vendedor,c.tipo_plazo, c.cantidad_max_ventas_mes, c.cantidad_min_ventas_mes');
        $this->db->from($this->tabla_comision . ' c');
        $this->db->join('esquemas e', 'e.id_esquema = c.cod_esquema');
        $this->db->join('auditoria a', 'c.id_comision = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
       
        $resultados = $this->db->get();
        return $resultados->result();*/
        //----------------------------------------------------------------------------------
        //--Migracion Mongo DB
        //---------------------------------------------------------------------------
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get($this->tabla_comision);
        foreach ($resultados as $clave => $valor) {
        $auditoria = $valor["auditoria"][0];
        //--usuario en cuestion
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
            //var_dump($res_us[0]["auditoria"]->status);die('');
            //$valor["fec_regins"] = $res_us[0]["auditoria"][0]->fecha->toDateTime();
            $vector_auditoria = reset($valor["auditoria"]);
            $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            $valor["correo_usuario"] = $res_us[0]["correo_usuario"];
            $valor["status"] = $valor["status"];
            $valor["id_comision"] = (string)$valor["_id"]->{'$id'};
            $valor["cod_esquema"] = (string)$valor["cod_esquema"];
            // $porctj_comision = $this->redondeado($valor["porctj_comision"],3);
            // $valor["porctj_comision"] = (string)$porctj_comision;
             //consultamos esquemas
            $res_esquemas= $this->mongo_db->where(array('eliminado'=>false,'_id'=>$valor["cod_esquema"]))->get('esquemas');
            foreach ($res_esquemas as $clave1 => $valor1) {
                $valor["nombre_esquema"] = $valor1["nombre_esquema"];
            }
            $listado[] = $valor;
        }    
        //--
        $listado2 = $listado;
        return $listado2;
        //----------------------------------------------------------------------------------
    }   
    
      /*
    *   Redondeado
    */
    public function redondeado ($numero, $decimales) { 
       $factor = pow(10, $decimales); 
       return (round($numero*$factor)/$factor); 
    }
    /*
    *
    */
        
    public function registrar_comision($data){
       /* $this->db->where('id_vendedor', $data['id_vendedor']);
        $this->db->where('tipo_vendedor', $data['tipo_vendedor']);
        $this->db->where('cantidad_max_ventas_mes', $data['cantidad_max_ventas_mes']);
        $this->db->where('cantidad_min_ventas_mes', $data['cantidad_min_ventas_mes']);
        $this->db->where('tipo_plazo', $data['tipo_plazo']);
        $this->db->where('cod_esquema', $data['cod_esquema']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_comision);
        if ($resultados->num_rows() == 0) {
            $this->db->insert($this->tabla_comision, $data);
            $datos=array(
                'tabla' => $this->tabla_comision,
                'cod_reg' => $this->db->insert_id(),
                'usr_regins' => $this->session->userdata('id_usuario'),
                'fec_regins' => date('Y-m-d'),
            );
            $this->db->insert('auditoria', $datos);
            echo json_encode("<span>La comisión se ha registrado exitosamente!</span>");
        } else {
            echo "<span>¡Ya se encuentra registrado un esquema de comisión con las mismas características!</span>";
        }*/
        //--------------------------------------------------------------------------------
        //Migracion MongoDB
        //--------------------------------------------------------------------------------

        $res_comision = $this->mongo_db->limit(1)->where(array('id_vendedor'=>$data['id_vendedor'],'tipo_vendedor'=>$data['tipo_vendedor'],'cantidad_max_ventas_mes'=>$data['cantidad_max_ventas_mes'],'cantidad_min_ventas_mes'=>$data['cantidad_min_ventas_mes'],'tipo_plazo'=>$data['tipo_plazo'],'cod_esquema'=>$data['cod_esquema'],"eliminado"=>false))->get($this->tabla_comision);    
        if(count($res_comision) == 0){
            $insertar1 = $this->mongo_db->insert($this->tabla_comision, $data);
            echo json_encode("<span>La comisión se ha registrado exitosamente!</span>");
        }else{
            echo "<span>¡Ya se encuentra registrado un esquema de comisión con las mismas características!</span>";
        }
        //--------------------------------------------------------------------------------
    }

    public function actualizar_comision($id, $data)
    {
        /*$this->db->where('id_vendedor', $data['id_vendedor']);
        $this->db->where('tipo_vendedor', $data['tipo_vendedor']);
        $this->db->where('cantidad_max_ventas_mes', $data['cantidad_max_ventas_mes']);
        $this->db->where('cantidad_min_ventas_mes', $data['cantidad_min_ventas_mes']);
        $this->db->where('tipo_plazo', $data['tipo_plazo']);
        $this->db->where('cod_esquema', $data['cod_esquema']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_comision);
        if ($resultados->num_rows() == 0) {
            $this->db->where('id_comision', $id);
            $this->db->update($this->tabla_comision, $data);
            $datos=array(
                'usr_regmod' => $this->session->userdata('id_usuario'),
                'fec_regmod' => date('Y-m-d'),
            );
            $this->db->insert('auditoria', $datos);
            echo json_encode("<span>El esquema de comisión se ha editado exitosamente!</span>");
        } else {
            $array = $resultados->row();
            if ($array->id_comision == $id) {
                $this->db->where('id_comision', $id);
                $this->db->update($this->tabla_comision, $data);
                $datos=array(
                    'usr_regmod' => $this->session->userdata('id_usuario'),
                    'fec_regmod' => date('Y-m-d'),
                );
                $this->db->insert('auditoria', $datos);
                echo json_encode("<span>La comisión se ha editado exitosamente!</span>");
            } else {
                echo "<span>¡Ya se encuentra registrado un esquema de comisión con las mismas características!</span>";
            }
        }*/
        //-------------------------------------------------------------------------------------
        //MIGRACION MONGO DB
        //-------------------------------------------------------------------------------------
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_comision = new MongoDB\BSON\ObjectId($id);

        //--
        $res_comision = $this->mongo_db->limit(1)->where(array('id_vendedor'=>$data['id_vendedor'],'tipo_vendedor'=>$data['tipo_vendedor'],'cantidad_max_ventas_mes'=>$data['cantidad_max_ventas_mes'],'cantidad_min_ventas_mes'=>$data['cantidad_min_ventas_mes'],'tipo_plazo'=>$data['tipo_plazo'],'cod_esquema'=>$data['cod_esquema'],"eliminado"=>false))->get($this->tabla_comision);    
        if(count($res_comision) == 0){
            //Actualizo los campos
            $mod_comision = $this->mongo_db->where(array('_id'=>$id_comision))->set($data)->update($this->tabla_comision);
            //Auditoria...
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar comision',
                                            'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_comision))->push('auditoria',$data_auditoria)->update($this->tabla_comision);
            echo json_encode("<span>El esquema de comisión se ha editado exitosamente!</span>");

        }else{
            if ($res_comision[0]["_id"]->{'$id'} == $id) {
                //Actualizo los campos
                $mod_esquema = $this->mongo_db->where(array('_id'=>$id_comision))->set($data)->update($this->tabla_comision);
                //Auditoria...
                $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Modificar comision',
                                                'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_comision))->push('auditoria',$data_auditoria)->update($this->tabla_comision);
                //--
                echo json_encode("<span>La comisión se ha editado exitosamente!</span>");

            }else {
                echo "<span>¡Ya se encuentra registrado un esquema de comisión con las mismas características!</span>";
            }
        }
        //-------------------------------------------------------------------------------------
    }

    public function eliminar_comision($id)
    {
       /* try { 
            if(!$this->db->delete($this->tabla_comision, array('id_comision' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_comision));
                echo json_encode("<span>La comisión se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
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
        $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_comision);
        //--Auditoria
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar comision',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_comision); 
            echo json_encode("<span>La comisión se ha eliminado exitosamente!</span>"); 
            // envio de mensaje exitoso
        }
        //------------------------------------------------------------
    }

    public function status_comision($id, $status)
    {
        /*$datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_comision);
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
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_comision);
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status comision',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_comision); 
        }
        //------------------------------------------------------------
    }

    public function eliminar_multiple_comision($id_comision)
    {
       /* $eliminados=0;
        $noEliminados=0;
        foreach($id as $comision)
        {
            if($this->db->delete($this->tabla_comision, array('id_comision' => $comision))){
                $this->db->delete('auditoria', array('cod_reg' => $comision, 'tabla' => $this->tabla_comision));
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
        foreach($id_comision as $comision){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            $id = new MongoDB\BSON\ObjectId($comision);
            $datos = $data=array(
                                    'eliminado'=>true,
            );
            $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_comision);
            //--Auditoria
            if($eliminar){
                $eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar comision',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_comision);
            }else{
                $noEliminados++;
            }   
            //----------------------------------------------------------------------------------    

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //----------------------------------------------------------------------------
    }

    public function status_multiple_comision($id, $status)
    {
        /*$esquemas_comisiones = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $esquemas_comisiones . ") AND tabla='" . $this->tabla_comision . "'");*/
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
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_comision);
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
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_comision); 
            }
        }
        //---------------------------------------------------------------------------
    }

    public function id_venderores()
    {
        $this->db->where('tipolval', 'IDVENDEDOR');
        $this->db->order_by('descriplval', 'ASC');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

    public function tipos_venderores()
    {
        $this->db->where('tipolval', 'TIPOVENDEDOR');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

    public function tipos_plazos()
    {
        $this->db->where('tipolval', 'TIPOPLAZOS');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }


     public function tipos_pagos()
    {
        $this->db->where('tipolval', 'TIPOPAGO');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

    public function tipos_plazos_anticipo()
    {
        $this->db->where('tipolval', 'TIPOPLASOANT');
        $resultados = $this->db->get($this->tabla_lval);
        return $resultados->result();
    }

    public function esquemas($tipo)
    {
        /*$this->db->where(array( 'a.tabla'    => $this->tabla_esquema,
                                'e.tipo'    => 297,
                                'a.status'  => 1));
        $this->db->select('e.*');
        $this->db->from($this->tabla_esquema . ' e');
        $this->db->join('auditoria a', 'e.id_esquema = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //--------------------------------------------------------------
        //Migracion Mongo DB
        //--------------------------------------------------------------
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'tipo'=>$tipo))->get($this->tabla_esquema);
        $listado = [];
        foreach ($resultados as $clave => $valor) {
            $valor["id_esquema"] = (string)$valor["_id"]->{'$id'};
            $listado[] = $valor;
        }
        return $listado;
        //--------------------------------------------------------------
    }

}
