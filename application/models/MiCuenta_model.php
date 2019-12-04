<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MiCuenta_model extends CI_Model {

    
    public function guardarCuenta($data){
        $insertar1 = $this->mongo_db->insert("mis_cuentas", $data);
    }

    public function listarCuentas()
    {
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get("mis_cuentas");



        foreach ($resultados as $clave => $valor){

            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');


            $vector_auditoria = reset($valor["auditoria"]);
            $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();


            isset($res_us[0]["correo_usuario"])? $valor["correo_usuario"] = $res_us[0]["correo_usuario"]:$valor["correo_usuario"] ="";


            $listado[] = $valor;

        }

        $listado2 = $listado;
        return $listado2;
    }





    public function listarCuentasCobranza()
    {
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get("mis_cuentas");



        $new = array();  
        $exclude = array("");  
        for ($i = 0; $i<=count($resultados)-1; $i++) {  
            if (!in_array(trim($resultados[$i]["id_banco"]) ,$exclude)) 
            {
                
                $resultados[$i]->cout = 1;
                $new[] = $resultados[$i]; 
                $exclude[] = trim($resultados[$i]["id_banco"]); 
            }  
        }




        foreach ($new as $clave => $valor){

            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');


            $vector_auditoria = reset($valor["auditoria"]);
            $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();


            isset($res_us[0]["correo_usuario"])? $valor["correo_usuario"] = $res_us[0]["correo_usuario"]:$valor["correo_usuario"] ="";

            
            $listado[] = $valor;

        }

        $listado2 = $listado;
        return $listado2;
    }






    public function actualizarCuentaCliente($id, $data){
        $id_cuenta = new MongoDB\BSON\ObjectId($id);
       
        $modificar1 = $this->mongo_db->where(array('_id'=>$id_cuenta))->set($data)->update("mis_cuentas");

        if($modificar1){
            return true;
        }
    }


}

/* End of file modelName.php */
/* Location: ./application/models/modelName.php */