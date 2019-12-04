<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Proyectos_model extends CI_Model
{

    private $tabla_proyecto = "proyectos";
    private $tabla_inmobiliarias = "inmobiliarias";
    private $tablaEsquema = "esquemas";
    private $tabla_inmobliarias_proyectos = "inmobiliarias_proyectos";
    private $tabla_proyectos_esquemas = "proyectos_esquemas";
    private $tabla_lval = "lval";
    private $tabla_proyectos_clasificacion = "proyectos_clasificacion";

    public function listado_proyectos()
    {
        $this->db->where('a.tabla', $this->tabla_proyecto);
        $this->db->select('p.*, a.fec_regins, u.correo_usuario, a.status, dt.nombre_datos_personales AS nombres, dt.apellido_p_datos_personales AS paterno, dt.apellido_m_datos_personales AS materno');
        $this->db->from($this->tabla_proyecto . ' p');
        $this->db->join('auditoria a', 'p.id_proyecto = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $this->db->join('usuario c', 'p.director = c.id_usuario');
        $this->db->join('datos_personales dt', 'c.id_usuario = dt.id_usuario');
        $resultados = $this->db->get();
        return $resultados->result();
    }   

    public function getproyectosactivos()
    {
       /*$this->db->where('a.tabla', $this->tabla_proyecto);
       $this->db->where('a.status', 1);
        $this->db->select('p.*, a.fec_regins, u.correo_usuario, a.status, dt.nombre_datos_personales AS nombres, dt.apellido_p_datos_personales AS paterno, dt.apellido_m_datos_personales AS materno');
        $this->db->from($this->tabla_proyecto . ' p');
        $this->db->join('auditoria a', 'p.id_proyecto = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $this->db->join('usuario c', 'p.director = c.id_usuario');
        $this->db->join('datos_personales dt', 'c.id_usuario = dt.id_usuario');
        $this->db->join('inmobiliarias_proyectos ip', 'ip.id_proyecto = p.id_proyecto');
        $this->db->group_by('p.id_proyecto');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //---------------------------------------------------------------------------------------
        //Migracion MONGO DB
        //---------------------------------------------------------------------------------------
        $listado = [];
        $res_proyecto = $this->mongo_db->where(array('eliminado'=>false))->get($this->tabla_proyecto);
        foreach ($res_proyecto as $clave => $valor) {
            $valores["id_proyecto"] = $valor["_id"]->{'$id'};
            $valores["codigo"] = $valor["codigo"];
            $valores["nombre"] = $valor["nombre"];
            $valores["descripcion"] = $valor["descripcion"];
            $valores["director"] = $valor["director"];
            $valores["plano"] = $valor["plano"];
            $valores["indicador_mora"] = $valor["indicador_mora"];
            $valores["can_dias_vencidos"] = $valor["can_dias_vencidos"];
            $valores["porcentaje_mora"] = $valor["porcentaje_mora"];
            //--usuario en cuestion
            $id_auditoria = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            //--Para usuarios
            $res_us = $this->mongo_db->where(array('_id'=>$id_auditoria))->get('usuario');

            $valores["fec_regins"] = $valor["auditoria"][0]->fecha;
            $valores["correo_usuario"] = $res_us[0]["correo_usuario"];
            $valores["status"] = $valor["status"];

            //--Para datos personales...
            $res_dt = $this->mongo_db->where(array('id_usuario'=>$id_auditoria))->get('datos_personales');
            foreach ($res_dt as $clave_dt => $valor_dt) {
                $valores["nombres"] = $valor_dt["nombre_datos_personales"];
                $valores["paterno"] = $valor_dt["apellido_p_datos_personales"];
                $valores["materno"] = $valor_dt["apellido_m_datos_personales"];
            }
            $listado[] = $valores;
        }
        //---------------------------------------------------------------------------------------
        return $listado;
    }
        
    public function registrar_proyecto($data, $inmobiliarias, $clasificaciones, $esquemas){
        $this->db->insert($this->tabla_proyecto, $data);//print_r($this->db->last_query());die;
        $proyecto = $this->db->insert_id();
        $datos=array(
            'tabla' => $this->tabla_proyecto,
            'cod_reg' => $proyecto,
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
        if (isset($inmobiliarias) > 0){
            foreach($inmobiliarias as $inmobiliaria)
            {
                $array = array(
                    'id_inmobiliaria' => $inmobiliaria,
                    'id_proyecto' => $proyecto,
                );
                $this->db->insert($this->tabla_inmobliarias_proyectos, $array);
                $id_inmobiliaria_proyecto = $this->db->insert_id();
                $auditoria_imob = array(
                                    'tabla' => $this->tabla_inmobliarias_proyectos,
                                    'cod_reg' => $id_inmobiliaria_proyecto,
                                    'usr_regins' => $this->session->userdata('id_usuario'),
                                    'fec_regins' => date('Y-m-d'),
                                        );
                $this->db->insert('auditoria', $auditoria_imob);       
            }
        }
        if (isset($clasificaciones) > 0){
            foreach($clasificaciones as $clasificacion)
            {
                $clasificacion = explode(",", $clasificacion,3); 
                $arraycla = array(
                    'id_proyecto' => $proyecto,
                    'etapa' => $clasificacion[0],
                    'clasificacion' => $clasificacion[1],
                    'precio' => trim(str_replace(',', '', $clasificacion[2])),
                );
                $this->db->insert($this->tabla_proyectos_clasificacion, $arraycla);
                 $id_clasificacion_pro = $this->db->insert_id();
                $auditoria_pro = array(
                                    'tabla' => $this->tabla_proyectos_clasificacion,
                                    'cod_reg' => $id_clasificacion_pro,
                                    'usr_regins' => $this->session->userdata('id_usuario'),
                                    'fec_regins' => date('Y-m-d'),
                                        );
                $this->db->insert('auditoria', $auditoria_pro);  
            }
        }
        if (isset($esquemas) > 0){
            foreach($esquemas as $esquema)
            {
                $array_esquema = array(
                    'id_esquema' => $esquema,
                    'id_proyecto' => $proyecto,
                );
                $this->db->insert($this->tabla_proyectos_esquemas, $array_esquema);
                $id_esquema_proyecto = $this->db->insert_id();
                $auditoria_esquema = array(
                                    'tabla' => $this->tabla_proyectos_esquemas,
                                    'cod_reg' => $id_esquema_proyecto,
                                    'usr_regins' => $this->session->userdata('id_usuario'),
                                    'fec_regins' => date('Y-m-d'),
                                        );
                $this->db->insert('auditoria', $auditoria_esquema);      
            }
        }
    }
    public function editarClasificacionAjax($id_proyecto_clasificacion,$precio)
    {
        $condicion = array('precio' => $precio);
        $this->db->where('id_proyecto_clasificacion', $id_proyecto_clasificacion);
        $this->db->update($this->tabla_proyectos_clasificacion,$condicion); 
        $datos=array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id_proyecto_clasificacion)->where('tabla', $this->tabla_proyectos_clasificacion);
        $this->db->update('auditoria', $datos);
    }

    public function actualizar_proyecto($proyectoArray, $id, $imagen, $inmobiliarias, $clasificaciones, $esquemas)
    {
        $this->db->where('id_proyecto', $id);
        $this->db->update($this->tabla_proyecto, $proyectoArray);
        if($imagen != ""){
            $data = array(
                'plano' => $imagen,
            );
            $this->db->where('id_proyecto', $id);
            $this->db->update($this->tabla_proyecto, $data);
        }
        $datos=array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_proyecto);
        $this->db->update('auditoria', $datos);
        if (isset($inmobiliarias) > 0){
            foreach($inmobiliarias as $inmobiliaria)
            {
                $query = $this->db->query("SELECT * FROM ".$this->tabla_inmobliarias_proyectos." WHERE id_proyecto=".$id." AND id_inmobiliaria=".$inmobiliaria);
                if (sizeof($query->result()) == 0) {
                    $array = array(
                        'id_inmobiliaria' => $inmobiliaria,
                        'id_proyecto' => $id,
                    );
                    $this->db->insert($this->tabla_inmobliarias_proyectos, $array);
                    $id_inmobiliaria_proyecto = $this->db->insert_id();
                    $auditoria_imob = array(
                                    'tabla' => $this->tabla_inmobliarias_proyectos,
                                    'cod_reg' => $id_inmobiliaria_proyecto,
                                    'usr_regins' => $this->session->userdata('id_usuario'),
                                    'fec_regins' => date('Y-m-d'),
                                        );
                $this->db->insert('auditoria', $auditoria_imob);  
                }
            }
        }
        if (isset($clasificaciones) > 0){
            foreach($clasificaciones as $clasificacion)
            {
                $clasificacion = explode(",", $clasificacion,3); 
                $query = $this->db->query("SELECT * FROM ".$this->tabla_proyectos_clasificacion." WHERE id_proyecto=".$id." AND clasificacion=".$clasificacion[1]." AND etapa =".$clasificacion[0]); 
                if (sizeof($query->result()) == 0) {
                    $array = array(
                        'etapa' => $clasificacion[0],
                        'id_proyecto' => $id,
                        'clasificacion' => $clasificacion[1],
                        'precio' => trim(str_replace(',', '', $clasificacion[2])),
                    ); 
                    $this->db->insert($this->tabla_proyectos_clasificacion, $array);
               $id_clasificacion_cla = $this->db->insert_id();
               $auditoria_cla = array(
                                    'tabla' => $this->tabla_proyectos_clasificacion,
                                    'cod_reg' => $id_clasificacion_cla,
                                    'usr_regins' => $this->session->userdata('id_usuario'),
                                    'fec_regins' => date('Y-m-d'),
                                        );
                $this->db->insert('auditoria', $auditoria_cla); 
                }
            }
        }
        if (isset($esquemas) > 0){
            foreach($esquemas as $esquema)
            {
                $query = $this->db->query("SELECT * FROM ".$this->tabla_proyectos_esquemas." WHERE id_proyecto=".$id." AND id_esquema=".$esquema);
                if (sizeof($query->result()) == 0) {
                    $array = array(
                        'id_esquema' => $esquema,
                        'id_proyecto' => $id,
                    );
                    $this->db->insert($this->tabla_proyectos_esquemas, $array);
                    $id_proyectos_esquemas = $this->db->insert_id();
                    $auditoria_esquema = array(
                                    'tabla' => $this->tabla_proyectos_esquemas,
                                    'cod_reg' => $id_proyectos_esquemas,
                                    'usr_regins' => $this->session->userdata('id_usuario'),
                                    'fec_regins' => date('Y-m-d'),
                                        );
                $this->db->insert('auditoria', $auditoria_esquema);  
                }
            }
        }
    }

    public function verificar_proyecto($data)
    {
        $this->db->where('codigo', $data['codigo']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_proyecto);
        return $resultados->result_array();
    }

    public function eliminar_proyecto($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_proyecto, array('id_proyecto' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_proyecto));
                echo json_encode("<span>El Proyecto se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function productoExiste($id_proyecto)
    {
        
        $this->db->where('cod_proyecto', $id_proyecto);
        $resultados = $this->db->get('productos');
        return $resultados->row_array();       
        
    }
    public function clasificacionExiste($id_clasificacion)
    {  
        $this->db->where('cod_proyecto_clasificacion', $id_clasificacion);
        $resultados = $this->db->get('productos');
        return $resultados->row_array();          
    }
     public function inmobiliariaExiste($id_inmobiliaria)
    {  
        $this->db->where('id_inmobiliaria', $id_inmobiliaria);
        $resultados = $this->db->get('vendedores_inmobiliarias');
        return $resultados->row_array();          
    }


    public function status_proyecto($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_proyecto);
        $this->db->update('auditoria', $datos);
    }
       public function status_inmob_clasi($id, $status, $tabla)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $tabla);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_multiple_proyecto($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $proyecto)
        {
            if($this->db->delete($this->tabla_proyecto, array('id_proyecto' => $proyecto))){
                $this->db->delete('auditoria', array('cod_reg' => $proyecto, 'tabla' => $this->tabla_proyecto));
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_proyecto($id, $status)
    {
        $proyectos = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $proyectos . ") AND tabla='" . $this->tabla_proyecto . "'");
    }

    public function directores()
    {
        /*$this->db->where('r.nombre_rol', 'DIRECTOR');
        $this->db->where('a.tabla', 'usuario');
        $this->db->where('a.status', 1);
        $this->db->select('u.id_usuario, dt.nombre_datos_personales AS nombres, dt.apellido_p_datos_personales AS paterno, dt.apellido_m_datos_personales AS materno');
        $this->db->from('rol r');
        $this->db->join('usuario u', 'r.id_rol = u.id_rol');
        $this->db->join('datos_personales dt', 'u.id_usuario = dt.id_usuario');
        $this->db->join('auditoria a', 'u.id_usuario = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //----------------------------------------------------------------------------------------
        //--Migracion MONGO DB
         $res_rol = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true,'nombre_rol'=>'DIRECTOR'))->get("rol");
            $listado = [];
            foreach ($res_rol as $clave => $valor) {
                $valores = $valor;
                $valores["id_rol"] = (string)$valor["_id"]->{'$id'};
                //Consulto tabla usuarios
                $res_us = $this->mongo_db->where(array('eliminado'=>false,'id_rol'=>$valores["id_rol"]))->get("usuario");
                $valores["id_usuario"] =$res_us[0]["_id"]->{'$id'};
                $id_usuario = new MongoDB\BSON\ObjectId($valores["id_usuario"]); 
                //Consulto tabla datos personales 
                $res_dt = $this->mongo_db->where(array('eliminado'=>false,'id_usuario'=>$id_usuario))->get("datos_personales");
                $valores["nombre_datos_personales"] = $res_dt[0]["nombre_datos_personales"];
                $valores["apellido_p_datos_personales"] = $res_dt[0]["apellido_p_datos_personales"];
                $valores["apellido_m_datos_personales"] = $res_dt[0]["apellido_m_datos_personales"];
                $listado [] = $valores;
                //----
            }
            return $listado;
        //----------------------------------------------------------------------------------------
    }

    public function inmobiliarias()
    {
        $this->db->where('a.tabla', 'inmobiliarias');
        $this->db->where('a.status', 1);
        $this->db->select('i.*');
        $this->db->from($this->tabla_inmobiliarias . ' i');
        $this->db->join('auditoria a', 'i.id_inmobiliaria = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function buscarInmobiliarias($proyecto)
    {
        $this->db->where(array('ip.id_proyecto'=> $proyecto,
                                'a.tabla' => $this->tabla_inmobliarias_proyectos
                            ));
        $this->db->select('i.id_inmobiliaria, i.codigo, i.nombre, dt.nombre_datos_personales AS nombres, dt.apellido_p_datos_personales AS paterno, dt.apellido_m_datos_personales AS materno, ip.*, a.status');
        $this->db->from($this->tabla_inmobliarias_proyectos . ' ip');
        $this->db->join($this->tabla_inmobiliarias . ' i', 'ip.id_inmobiliaria = i.id_inmobiliaria');
        $this->db->join('usuario u', 'i.id_coordinador = u.id_usuario');
        $this->db->join('datos_personales dt', 'u.id_usuario = dt.id_usuario');
        $this->db->join('auditoria a','a.cod_reg = ip.id_inmobiliaria_proyecto', 'left');
        $resultados = $this->db->get();
        //print_r($this->db->last_query());die;
        return $resultados->result();
    }

    public function buscarInmobiliariasVendedor($proyecto)
    {
        $this->db->where(array('ip.id_proyecto'=> $proyecto,
                                'a.tabla' => $this->tabla_inmobliarias_proyectos,
                                'a.status' => 1
                            ));
        $this->db->select('i.id_inmobiliaria, i.codigo, i.nombre, dt.nombre_datos_personales AS nombres, dt.apellido_p_datos_personales AS paterno, dt.apellido_m_datos_personales AS materno, ip.*, a.status');
        $this->db->from($this->tabla_inmobliarias_proyectos . ' ip');
        $this->db->join($this->tabla_inmobiliarias . ' i', 'ip.id_inmobiliaria = i.id_inmobiliaria');
        $this->db->join('usuario u', 'i.id_coordinador = u.id_usuario');
        $this->db->join('datos_personales dt', 'u.id_usuario = dt.id_usuario');
        $this->db->join('auditoria a','a.cod_reg = ip.id_inmobiliaria_proyecto', 'left');
        $resultados = $this->db->get();
        return $resultados->result();
    }




    public function getclientesproyecto($proyecto)
    {

        $this->db->select('cc.id_cliente, dp.nombre_datos_personales as nombre, 
                          dp.apellido_p_datos_personales as p_apellido,
                          dp.apellido_m_datos_personales as m_apellido');
        $this->db->where('id_proyecto', $proyecto);
        $this->db->join('cliente_pagador cp', 'cp.id_cliente = cc.id_cliente');
        $this->db->join('datos_personales dp', 'dp.id_datos_personales = cp.id_datos_personales');
        $this->db->from('cartera_clientes cc');
        $resultados = $this->db->get();
        return $resultados->result();
    }


    public function getvendedorproyecto($proyecto, $cliente)
    {

        $this->db->select('cl.id_vendedor, dp.nombre_datos_personales as nombre, 
                          dp.apellido_p_datos_personales as p_apellido,
                          dp.apellido_m_datos_personales as m_apellido,
                          vd.tipo_vendedor');
        $this->db->join('vendedores vd', 'vd.id_vendedor = cl.id_vendedor');
        $this->db->join('datos_personales dp', 'dp.id_usuario = vd.id_usuario');
        $this->db->from('cartera_clientes cl');
        $this->db->where('id_proyecto', $proyecto);
        $this->db->where('id_cliente', $cliente);
        $resultados = $this->db->get();
        return $resultados->result();
    }


    public function getImbobiliariaVendedor($vendedor, $proyecto)
    {
        $this->db->select('i.id_inmobiliaria, i.codigo, i.nombre');
        $this->db->join($this->tabla_inmobiliarias . ' i', 'vi.id_inmobiliaria = i.id_inmobiliaria');
        $this->db->from('vendedores_inmobiliarias vi');
        $this->db->where('id_vendedor', $vendedor);
        $this->db->where('id_proyecto', $proyecto);
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function buscarInmobiliariasID($proyecto, $id_inmobiliaria_proyecto)
    {
        $this->db->where(array('ip.id_proyecto'=> $proyecto,
                                'a.tabla' => $this->tabla_inmobliarias_proyectos,
                                'ip.id_inmobiliaria_proyecto' => $id_inmobiliaria_proyecto
                            ));
        $this->db->select('i.id_inmobiliaria, i.codigo, i.nombre, dt.nombre_datos_personales AS nombres, dt.apellido_p_datos_personales AS paterno, dt.apellido_m_datos_personales AS materno, ip.*, a.status');
        $this->db->from($this->tabla_inmobliarias_proyectos . ' ip');
        $this->db->join($this->tabla_inmobiliarias . ' i', 'ip.id_inmobiliaria = i.id_inmobiliaria');
        $this->db->join('usuario u', 'i.id_coordinador = u.id_usuario');
        $this->db->join('datos_personales dt', 'u.id_usuario = dt.id_usuario');
        $this->db->join('auditoria a','a.cod_reg = ip.id_inmobiliaria_proyecto', 'left');
        $resultados = $this->db->get();
        return $resultados->result();
    }
    public function buscarEsquemas($proyecto)
    {
        $this->db->where(array( 'pe.id_proyecto'=> $proyecto,
                                'a.tabla' => $this->tabla_proyectos_esquemas
                            ));
        $this->db->select('pe.*, a.status, e.descripcion');
        $this->db->from($this->tabla_proyectos_esquemas . ' pe');
        $this->db->join($this->tablaEsquema . ' e', 'e.id_esquema = pe.id_esquema');
        $this->db->join('auditoria a','a.cod_reg = pe.id_proyectos_esquemas', 'left');
        $resultados = $this->db->get(); 
        return $resultados->result();
    }
    public function buscarEsquemasID($proyecto, $id_proyectos_esquemas)
    {
        $this->db->where(array( 'pe.id_proyecto'=> $proyecto,
                                'a.tabla' => $this->tabla_proyectos_esquemas,
                                'id_proyectos_esquemas' => $id_proyectos_esquemas
                            ));
        $this->db->select('pe.*, a.status, e.descripcion');
        $this->db->from($this->tabla_proyectos_esquemas . ' pe');
        $this->db->join($this->tablaEsquema . ' e', 'e.id_esquema = pe.id_esquema');
        $this->db->join('auditoria a','a.cod_reg = pe.id_proyectos_esquemas', 'left');
        $resultados = $this->db->get(); 
        return $resultados->result();
    }
    public function eliminar_inmobiliaria_proyecto($id, $id_inmobiliaria_proyecto)
    {
        try { 
            if(!$this->db->delete($this->tabla_inmobliarias_proyectos, array('id_inmobiliaria' => $id, 'id_inmobiliaria_proyecto' => $id_inmobiliaria_proyecto))){
                throw new Exception("<span>Ha ocurrido un error, intentelo de nuevo!</span>");
            }else{
                echo json_encode("<span>La inmobiliaria se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }
     public function eliminar_proyectos_esquemas($id, $id_proyectos_esquemas)
    {
        try { 
            if(!$this->db->delete($this->tabla_proyectos_esquemas, array('id_esquema' => $id, 'id_proyectos_esquemas' => $id_proyectos_esquemas))){
                throw new Exception("<span>Ha ocurrido un error, intentelo de nuevo!</span>");
            }else{
                echo json_encode("<span>El Esquema se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function clasificaciones()
    {
        /*$this->db->where('l.tipolval', "CLASIFCAPROY");
        $this->db->where('a.tabla', $this->tabla_lval);
        $this->db->where('a.status', 1);
        $this->db->select('l.*');
        $this->db->from($this->tabla_lval . ' l');
        $this->db->join('auditoria a', 'l.codlval = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();*/
    }
        public function etapas()
    {
        $this->db->where('l.tipolval', "ETAPA_CONSTR");
        $this->db->where('a.tabla', $this->tabla_lval);
        $this->db->where('a.status', 1);
        $this->db->select('l.*');
        $this->db->from($this->tabla_lval . ' l');
        $this->db->join('auditoria a', 'l.codlval = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }
     public function esquemas()
    {
       $this->db->where(array('a.status' => 1,
                            'a.tabla' => 'esquemas'));
        $this->db->select('es.*, a.status');
        $this->db->from($this->tablaEsquema . ' es');
        $this->db->join('auditoria a', 'es.id_esquema = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
        
    }

    public function buscarClasificacionesget($proyecto)
    {
        $this->db->where(array('pc.id_proyecto'=> $proyecto,
                             'a.tabla'=> $this->tabla_proyectos_clasificacion,
                            'a.status' => 1));
        $this->db->distinct();
        $this->db->select('pc.etapa,l2.descriplval as etapa_nomb');
        $this->db->from($this->tabla_proyectos_clasificacion . ' pc');
        $this->db->join($this->tabla_lval . ' l', 'pc.clasificacion = l.codlval');
        $this->db->join($this->tabla_lval . ' l2', 'pc.etapa = l2.codlval', 'left');
        $this->db->join('auditoria a','a.cod_reg = pc.id_proyecto_clasificacion', 'left');
        $resultados = $this->db->get();
        return $resultados->result();
    }
    public function buscarClasificacionesEtapaget($proyecto, $etapa)
    {
        $this->db->where(array('pc.id_proyecto'=> $proyecto,
                                'pc.etapa'    => $etapa,
                             'a.tabla'=> $this->tabla_proyectos_clasificacion,
                            'a.status' => 1));
        $this->db->select('pc.*, l.descriplval, l2.descriplval as etapa_nomb,a.status');
        $this->db->from($this->tabla_proyectos_clasificacion . ' pc');
        $this->db->join($this->tabla_lval . ' l', 'pc.clasificacion = l.codlval');
        $this->db->join($this->tabla_lval . ' l2', 'pc.etapa = l2.codlval', 'left');
        $this->db->join('auditoria a','a.cod_reg = pc.id_proyecto_clasificacion', 'left');
        $resultados = $this->db->get();
        return $resultados->result();
    }
    public function buscarClasificaciones($proyecto)
    {
        $this->db->where('pc.id_proyecto', $proyecto)->where('a.tabla', $this->tabla_proyectos_clasificacion);
        $this->db->select('pc.*, l.descriplval,l2.descriplval as etapa_nom, a.status');
        $this->db->from($this->tabla_proyectos_clasificacion . ' pc');
        $this->db->join($this->tabla_lval . ' l', 'pc.clasificacion = l.codlval');
        $this->db->join($this->tabla_lval . ' l2', 'pc.etapa = l2.codlval', 'left');
        $this->db->join('auditoria a','a.cod_reg = pc.id_proyecto_clasificacion', 'left');
        $resultados = $this->db->get();
        return $resultados->result();
    }
     public function buscarClasificacionesID($proyecto, $id_proyecto_clasificacion)
    {
        $this->db->where(array('pc.id_proyecto'=> $proyecto,
                                'a.tabla'=> $this->tabla_proyectos_clasificacion,
                                'pc.id_proyecto_clasificacion' => $id_proyecto_clasificacion));
        $this->db->select('pc.*, l.descriplval,l2.descriplval as etapa_nom, a.status');
        $this->db->from($this->tabla_proyectos_clasificacion . ' pc');
        $this->db->join($this->tabla_lval . ' l', 'pc.clasificacion = l.codlval');
        $this->db->join($this->tabla_lval . ' l2', 'pc.etapa = l2.codlval', 'left');
        $this->db->join('auditoria a','a.cod_reg = pc.id_proyecto_clasificacion', 'left');
        $resultados = $this->db->get();
        return $resultados->result();
    }


    public function buscarClasificacionesAll()
    {
        $this->db->select('pc.*, l.descriplval');
        $this->db->from($this->tabla_proyectos_clasificacion . ' pc');
        $this->db->join($this->tabla_lval . ' l', 'pc.clasificacion = l.codlval');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function eliminar_clasificacion_proyecto($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_proyectos_clasificacion, array('id_proyecto_clasificacion' => $id))){
                throw new Exception("<span>Ha ocurrido un error, intentelo de nuevo!</span>");
            }else{
                echo json_encode("<span>La clasificación se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

}
