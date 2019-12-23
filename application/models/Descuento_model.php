<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Descuento_model extends CI_Model
{

    private $tabla_descuento = "descuentos";
    private $tabla_lval = "lval";
    private $tabla_esquema = "esquemas";

    public function listar_descuentos()
    {
        /*$this->db->where('a.tabla', $this->tabla_descuento);
        $this->db->select('d.*, a.fec_regins, u.correo_usuario, a.status');
        $this->db->from($this->tabla_descuento . ' d');
        $this->db->join('auditoria a', 'd.id_descuento = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //------------------------------------------------------------------------------
        //--Modificacion con Mongo db
        //---------------------------------------------------------------------------
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get($this->tabla_descuento);
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
            $valor["id_descuento"] = (string)$valor["_id"]->{'$id'};
            $valor["cod_esquema"] = (string)$valor["cod_esquema"];
            //$descuento = $this->redondeado($valor["descuento"],3);
           // $valor["descuento"] = (string)$descuento;
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
       $factor = pow(10, $decimales); 
       return (round($numero*$factor)/$factor); 
    }
    /*
    *
    */

    public function getdescuentoCorrida($plazo, $tipo_vendedor, $proyecto)
    {

        $this->db->where('a.tabla', $this->tabla_descuento);
        $this->db->select('d.id_descuento, d.descuento, es.descripcion as esquema, es.id_esquema');
        $this->db->from($this->tabla_descuento . ' d');
        $this->db->join('auditoria a', 'd.id_descuento = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $this->db->join($this->tabla_lval . ' tp', 'd.tipo_plazo = tp.codlval');
        $this->db->join($this->tabla_lval . ' tv', 'd.tipo_vendedor = tv.codlval');
        $this->db->join('esquemas es', 'es.id_esquema = d.cod_esquema');
        $this->db->join('proyectos_esquemas pe', 'pe.id_esquema = es.id_esquema');
        $this->db->where('tipo_plazo', $plazo);
        $this->db->where('tipo_vendedor', $tipo_vendedor);
        $this->db->where('pe.id_proyecto', $proyecto);

        $resultados = $this->db->get();
        return $resultados->result();
    }   
        
    public function registrar_descuento($data){
        /*$this->db->where('tipo_plazo', $data['tipo_plazo']);
        $this->db->where('tipo_vendedor', $data['tipo_vendedor']);
        $this->db->where('descuento', $data['descuento']);
        $this->db->where('cod_esquema', $data['cod_esquema']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_descuento);
        if ($resultados->num_rows() == 0) {
            $this->db->insert($this->tabla_descuento, $data);
            $datos=array(
                'tabla' => $this->tabla_descuento,
                'cod_reg' => $this->db->insert_id(),
                'usr_regins' => $this->session->userdata('id_usuario'),
                'fec_regins' => date('Y-m-d'),
            );
            $this->db->insert('auditoria', $datos);
            echo json_encode("<span>El descuento se ha registrado exitosamente!</span>");
        } else {
            echo "<span>¡Ya se encuentra registrado un descuento con las mismas características!</span>";
        }*/
        //---------------------------------------------------------------------------------------
        //MIGRACION MONGO DB
        $res_descuento = $this->mongo_db->limit(1)->where(array('tipo_plazo'=>$data['tipo_plazo'],'tipo_vendedor'=>$data['tipo_vendedor'],'descuento'=>$data['descuento'],'cod_esquema'=>$data['cod_esquema'],"eliminado"=>false))->get($this->tabla_descuento);
        if(count($res_descuento)==0){

            $insertar1 = $this->mongo_db->insert($this->tabla_descuento, $data);
            
            echo json_encode("<span>El descuento se ha registrado exitosamente!</span>");
        
        }else {
            
            echo "<span>¡Ya se encuentra registrado un descuento con las mismas características!</span>";
        }
        //---------------------------------------------------------------------------------------
    }

    public function actualizar_descuento($id, $data)
    {
   
        //---------------------------------------------------------------------------------
        //--Migracion MONGO DB
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_descuento = new MongoDB\BSON\ObjectId($id);
        //--Consulto si existe el descuento
        $res_descuento = $this->mongo_db->limit(1)->where(array('tipo_plazo'=>$data['tipo_plazo'],'tipo_vendedor'=>$data['tipo_vendedor'],'plan_paquete'=>$data['plan_paquete'],'servicio'=>$data['servicio'],'descuento'=>$data['descuento'],'cod_esquema'=>$data['cod_esquema'],"eliminado"=>false))->get($this->tabla_descuento);
        //Si el registro mantiene ĺos mismos campos
        if(count($res_descuento)==0){
    
            //Actualizo los campos
            $mod_descuento = $this->mongo_db->where(array('_id'=>$id_descuento))->set($data)->update($this->tabla_descuento);
            //Auditoria...
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar descuento',
                                            'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_descuento))->push('auditoria',$data_auditoria)->update($this->tabla_descuento);
             echo json_encode("<span>El descuento se ha editado exitosamente!</span>");
        }else{//Si cambian sus campos
            if ($res_descuento[0]["_id"]->{'$id'} == $id) {
                //Actualizo los campos
                $mod_descuento = $this->mongo_db->where(array('_id'=>$id_descuento))->set($data)->update($this->tabla_descuento);
                //Auditoria...
                $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Modificar descuento',
                                                'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_descuento))->push('auditoria',$data_auditoria)->update($this->tabla_descuento);
                //--
                echo json_encode("<span>El descuento se ha editado exitosamente!</span>");
            }else {
                echo "<span>¡Ya se encuentra registrado un descuento con las mismas características!</span>";
            }
        }
        //---------------------------------------------------------------------------------
    }

    public function eliminar_descuento($id)
    {
        /*try { 
            if(!$this->db->delete($this->tabla_descuento, array('id_descuento' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_descuento));
                echo json_encode("<span>El descuento se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
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
        $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_descuento);
        //--Auditoria
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar descuento',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_descuento); 
            echo json_encode("<span>El descuento se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso

        }
        //------------------------------------------------------------
    }

    public function status_descuento($id, $status)
    {
        /*$datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_descuento);
        $this->db->update('auditoria', $datos);
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
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_descuento);
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status descuento',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_descuento); 
        }
        //------------------------------------------------------------
    }

    public function eliminar_multiple_descuento($id_descuento)
    {
        /*$eliminados=0;
        $noEliminados=0;
        foreach($id as $descuento)
        {
            if($this->db->delete($this->tabla_descuento, array('id_descuento' => $descuento))){
                $this->db->delete('auditoria', array('cod_reg' => $descuento, 'tabla' => $this->tabla_descuento));
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
        foreach($id_descuento as $descuento){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            $id = new MongoDB\BSON\ObjectId($descuento);
            $datos = $data=array(
                                    'eliminado'=>true,
            );
            $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_descuento);
            //--Auditoria
            if($eliminar){
                $eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar descuento',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_descuento);
            }else{
                $noEliminados++;
            }   
            //----------------------------------------------------------------------------------    

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //----------------------------------------------------------------------------
    }

    public function status_multiple_descuento($id, $status)
    {
        /*$descuentos = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $descuentos . ") AND tabla='" . $this->tabla_descuento . "'");*/

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
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_descuento);
            //var_dump($modificar);die('');
            //--Auditoria
            if($modificar){
                $data_auditoria = "";
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status descuento',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_descuento); 
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

    public function esquemas($tipo)
    {
        /*$this->db->where(array( 'a.tabla'    => $this->tabla_esquema,
                                'e.tipo'    => 298,
                                'a.status'  => 1));
        $this->db->select('e.*');
        $this->db->from($this->tabla_esquema . ' e');
        $this->db->join('auditoria a', 'e.id_esquema = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //-----------------------------------------------------
        //-- Migracion Mongo DB
        $res_esquemas = $this->mongo_db->where(array('tipo'=>$tipo,'eliminado'=>false))->get($this->tabla_esquema);
        //var_dump($res_esquemas);die('');
        return $res_esquemas;
        //-----------------------------------------------------
    }

}
