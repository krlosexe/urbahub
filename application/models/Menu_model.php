<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Menu_model extends CI_Model
{

    public function modulos()
    {
        //--Modificacion con Mongo db
        //---------------------------------------------------------------------------
        $resultados = $this->mongo_db->order_by(array('posicion_modulo_vista' => 'ASC'))->where(array('status' => true))->get('modulo_vista');
        //$this->mongo_db->order_by(array('foo' => 'ASC'))->get('foobar');
        //var_dump($resultados);die('');
        if(count($resultados)>0){
            return (object)$resultados;
        }else{
                return false;
        }
        //---------------------------------------------------------------------------
        /*$this->db->where('a.status', 1);
        $this->db->where('a.tabla', 'modulo_vista');
        $this->db->select('mv.*');
        $this->db->from('modulo_vista mv');
        $this->db->join('auditoria a', 'mv.id_modulo_vista = a.cod_reg');
        $this->db->order_by('posicion_modulo_vista', 'ASC');
        $resultados = $this->db->get();
        return $resultados->result();*/
    }

    public function modulosbyid($id)
    {
        //--Modificacion con Mongo db
        //---------------------------------------------------------------------------
        $id = new MongoDB\BSON\ObjectId($id);
        $resultados = $this->mongo_db->order_by(array('posicion_modulo_vista' => 'ASC'))->where(array('status' => true,"_id" => $id))->get('modulo_vista');
        if(count($resultados)>0){
            return $resultados;
        }else{
                return false;
        }
        //---------------------------------------------------------------------------
        /*$this->db->where('a.status', 1);
        $this->db->where('a.tabla', 'modulo_vista');
        $this->db->where('id_modulo_vista', $id);
        $this->db->select('mv.*');
        $this->db->from('modulo_vista mv');
        $this->db->join('auditoria a', 'mv.id_modulo_vista = a.cod_reg');
        $this->db->order_by('posicion_modulo_vista', 'ASC');
        $resultados = $this->db->get();
        return $resultados->result();*/
    }

    public function vistas($id_rol)
    {
        //Antes recibia id_usuario
        //where_or(array('general'=>'0', 'detallada'=>'0','registrar'=>'0','actualizar'=>'0','eliminar'=>'0'))->
        //--Modificacion con Mongo db
        //---------------------------------------------------------------------------
        //Lista vista
        $res_lista = $this->mongo_db->order_by(array('posicion_lista_vista' => 'ASC'))->where(array('visibilidad_lista_vista'=>"0",'status' => true))->get('lista_vista');
        //var_dump($res_lista);die('');
        $listado = [];
        foreach ($res_lista as $clave => $valor) {
        //--rol de operaciones del usuario en cuestion
            $id = new MongoDB\BSON\ObjectId($valor["_id"]->{'$id'});
            $id_rol2 = new MongoDB\BSON\ObjectId($id_rol);
            $res_rol_op = $this->mongo_db->where(array('id_lista_vista'=>$id,'id_rol'=>$id_rol2,'eliminado'=>false,'status'=>true))->where_or(array('general'=>'0', 'detallada'=>'0','registrar'=>'0','actualizar'=>'0','eliminar'=>'0'))->get('rol_operaciones');
            //agrupo todo de lista vista si corresponde a ese rol de usuario...
            if(count($res_rol_op)>0){
                $listado[]= (object)$valor;
            }     
        }    
        //--
        $listado2 = (object)$listado;
        return $listado2;
        //---------------------------------------------------------------------------

        /*$this->db->where('u.id_usuario', $id_usuario);
        $this->db->where('lv.visibilidad_lista_vista', 0);
        $this->db->where('a.tabla', 'rol');
        $this->db->where('a.status', 1);
        $this->db->where('(ro.general=0 OR ro.detallada=0 OR ro.registrar=0 OR ro.actualizar=0 OR eliminar=0)');
        $this->db->select('lv.*');
        $this->db->from('usuario u');
        $this->db->join('rol r', 'u.id_rol = r.id_rol');
        $this->db->join('rol_operaciones ro', 'r.id_rol = ro.id_rol');
        $this->db->join('lista_vista lv', 'ro.id_lista_vista = lv.id_lista_vista');
        $this->db->join('auditoria a', 'a.cod_reg = r.id_rol');
        $this->db->order_by('posicion_lista_vista', 'ASC');
        $resultados = $this->db->get();
        return $resultados->result();*/
    }

    public function contar_modulos()
    {
        /*$resultados = $this->db->get('modulo_vista');
        return $resultados->result();*/
        /*
        *   Migracion mongo db
        */
        $res = $this->mongo_db->where(array("eliminado"=>false))->get('modulo_vista');
        return $res; 
    }

    public function verificar_permiso_vista($url, $rol)
    {
        //--Modificacion con Mongo db
        //---------------------------------------------------------------------------
        //Rol de operaciones
        $listado = [];
        $id = new MongoDB\BSON\ObjectId($rol);
        $res_ro = $this->mongo_db->where(array("id_rol" => $id))->get('rol_operaciones');
        foreach ($res_ro as $clave => $valor) {
            
            $id = new MongoDB\BSON\ObjectId($valor["id_lista_vista"]);
            //Consulto lista vista...
            $res_lista_vista = $this->mongo_db->where(array("_id" => $id,"url_lista_vista"=>$url))->get('lista_vista');
            if(count($res_lista_vista)>0){
                $valor["id_modulo_vista"] = (string)$res_lista_vista[0]["id_modulo_vista"];
                $valor["id_lista_vista"] = (string)$valor["id_lista_vista"];
                $valor["status"] = $res_lista_vista[0]["status"];
                $listado[] = (object)$valor;
            }
            //-------------------------
        }
        $listado2 = $listado;
        return $listado2;
        //-----------------------------------------------------------------------------    
        
        //--
        /*$this->db->where('ro.id_rol', $rol);
        $this->db->where('lv.url_lista_vista', $url);
        $this->db->where('a.tabla', 'rol');
        $this->db->select('ro.*, lv.id_lista_vista, mv.id_modulo_vista, a.status');
        $this->db->from('rol_operaciones ro');
        $this->db->join('lista_vista lv', 'ro.id_lista_vista = lv.id_lista_vista');
        $this->db->join('modulo_vista mv', 'lv.id_modulo_vista = mv.id_modulo_vista');
        $this->db->join('auditoria a', 'a.cod_reg = '. $rol);
        $resultados = $this->db->get();
        return $resultados->result();*/
    }

    public function contador_listaVista($id_modulo_vista)
    {
        /*$this->db->where('id_modulo_vista', $id_modulo_vista);
        $resultados = $this->db->get('lista_vista');
        return $resultados->result();*/
        //----------------------------------------------------
        //Migracion mongo db
        //$valor["_id"]->{'$id'}
        //$id = new MongoDB\BSON\ObjectId($id_modulo_vista['$oid']);
        $res_modulo_vista = $this->mongo_db->where(array("id_modulo_vista" => $id_modulo_vista))->get('lista_vista');
        return $res_modulo_vista;
        //----------------------------------------------------
    }

    public function breadcrumbs($listaVista)
    {
        /*$this->db->where('lv.url_lista_vista', $listaVista);
        $this->db->limit(1);
        $this->db->select('lv.nombre_lista_vista, mv.nombre_modulo_vista');
        $this->db->from('lista_vista lv');
        $this->db->join('modulo_vista mv', 'lv.id_modulo_vista = mv.id_modulo_vista');
        $resultados = $this->db->get();
        return $resultados->row();*/
        //---------------------------------------------------------
        //Migracion Mongo DB
        //Consulto lista vista
        $res_lista_vista = $this->mongo_db->where(array("url_lista_vista" => $listaVista))->get('lista_vista');
        $listado = [];
        foreach ($res_lista_vista as $clave => $valor) {
            //Consulto modulo vista
            $id = new MongoDB\BSON\ObjectId($valor["id_modulo_vista"]); 
            $res_modulo_vista = $this->mongo_db->where(array("_id" => $id))->get('modulo_vista');
            foreach ($res_modulo_vista as $clave2 => $valor2) {
                $valor["nombre_modulo_vista"] = $valor2["nombre_modulo_vista"];
            }
            $listado[] = $valor;
        }



        return $listado;
        //---------------------------------------------------------
    }

}
