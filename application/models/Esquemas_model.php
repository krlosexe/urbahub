<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Esquemas_model extends CI_Model
{

    private $tabla_esquema = "esquemas";
    private $tabla_recargas = "recargas";
    private $tabla_lval = "lval";

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
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get($this->tabla_esquema);
        foreach ($resultados as $clave => $valor) {
        $auditoria = $valor["auditoria"][0];
        //var_dump($auditoria->cod_user);die('');
        //--usuario en cuestion
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
            //var_dump($res_us[0]["auditoria"]->status);die('');
            $vector_auditoria = reset($valor["auditoria"]);
            $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            //$valor["fec_regins"] = $res_us[0]["auditoria"][0]->fecha->toDateTime();
            $valor["correo_usuario"] = $res_us[0]["correo_usuario"];
            $valor["status"] = $valor["status"];
            $valor["id_esquema"] = (string)$valor["_id"]->{'$id'};
            $valor["tipo"] = (integer)$valor["tipo"];
            $listado[] = $valor;
        }    
        //--
        $listado2 = $listado;
        return $listado2;
        //---------------------------------------------------------------------------
    }   
        
    public function registrar_esquema($data){
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
        $insertar1 = $this->mongo_db->insert($this->tabla_esquema, $data);
        echo json_encode("<span>El esquema se ha registrado exitosamente!</span>");
        //-----------------------------------------------------------------------------
    }

    public function actualizar_esquema($id, $data)
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
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_esquema = new MongoDB\BSON\ObjectId($id);
        //--Consulto si existe el descuento
        $res_esquema = $this->mongo_db->limit(1)->where(array('cod_esquema'=>$data['cod_esquema'],'eliminado'=>false))->get($this->tabla_esquema);
        //Si el registro mantiene ĺos mismos campos
        if(count($res_esquema)==0){
            //Actualizo los campos
            $mod_esquema = $this->mongo_db->where(array('_id'=>$id_esquema))->set($data)->update($this->tabla_esquema);
            //Auditoria...
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar esquema',
                                            'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_esquema))->push('auditoria',$data_auditoria)->update($this->tabla_esquema);
             echo json_encode("<span>El esquema se ha editado exitosamente!</span>");
        }else{//Si cambian sus campos
            if ($res_esquema[0]["_id"]->{'$id'} == $id) {
                //Actualizo los campos
                $mod_esquema = $this->mongo_db->where(array('_id'=>$id_esquema))->set($data)->update($this->tabla_esquema);
                //Auditoria...
                $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Modificar esquema',
                                                'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_esquema))->push('auditoria',$data_auditoria)->update($this->tabla_esquema);
                //--
                echo json_encode("<span>El esquema se ha editado exitosamente!</span>");
            }else {
                echo "<span>¡Ya se encuentra registrado un esquema con las mismas características!</span>";
            }
        }
        //---------------------------------------------------------------------------------
    }

    public function eliminar_esquema($id)
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
        $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_esquema);
        //--Auditoria
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar esquema',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_esquema); 
            echo json_encode("<span>El esquema se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso

        }
        //------------------------------------------------------------
    }

    public function status_esquema($id, $status)
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
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_esquema);
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status esquemas',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_esquema); 
        }
        //------------------------------------------------------------
    }

    public function eliminar_multiple_esquema($id_esquema)
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
        foreach($id_esquema as $esquema){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            $id = new MongoDB\BSON\ObjectId($esquema);
            $datos = $data=array(
                                    'eliminado'=>true,
            );
            //-----------------------------------------------------------
            $descuentos = $this->buscarDescuentos($esquema);
            $comisiones = $this->buscarComisiones($esquema);
            $recargos = $this->buscarRecargos($esquema);

            if (count($descuentos)>0 || count($comisiones)>0 || count($recargos)>0){
                $noEliminados++;
            }
            //-----------------------------------------------------------    
            else{
                $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_esquema);
                //--Auditoria
                if($eliminar){
                    $eliminados++;
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar esquema',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_esquema);
                }else{
                    $noEliminados++;
                }  
            }     
            //----------------------------------------------------------------------------------    

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //----------------------------------------------------------------------------

    }

    public function status_multiple_esquema($id, $status)
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
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_esquema);
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
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_esquema); 
            }
        }
        //---------------------------------------------------------------------------

    }
    /*
    *   Verificar existe esquema con ese codigo o con nombre
    */
    public function verificar_existe($tipo,$codigo,$descripcion){
        $res = $this->mongo_db->where(array('tipo'=>$tipo,'cod_esquema'=>$codigo, 'descripcion'=>$descripcion,"eliminado"=>false))->get($this->tabla_esquema);
        return $res;
    }
    /*
    *   Verificar existe esquema con ese codigo 
    */
    public function verificar_existe_codigo($codigo){
        $res = $this->mongo_db->where(array('cod_esquema'=>$codigo,"eliminado"=>false))->get($this->tabla_esquema);
        return $res;
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
        /*$this->db->where('cod_esquema', $id);
        $resultados = $this->db->get('descuentos');
        return $resultados->row_array();*/

        $res = $this->mongo_db->where(array('cod_esquema'=>$id,"eliminado"=>false))->get('descuentos');
        return $res;
    }
    
    public function buscarComisiones($id)
    {
       /*$this->db->where('cod_esquema', $id);
        $resultados = $this->db->get('comisiones');
        return $resultados->row_array();*/

        $res = $this->mongo_db->where(array('cod_esquema'=>$id,"eliminado"=>false))->get('comisiones');
        return $res;
    }

    public function buscarRecargos($id)
    {
        /*$this->db->where('cod_esquema', $id);
        $resultados = $this->db->get('descuentos');
        return $resultados->row_array();*/

        $res = $this->mongo_db->where(array('cod_esquema'=>$id,"eliminado"=>false))->get('recargas');
        return $res;
    }

}
