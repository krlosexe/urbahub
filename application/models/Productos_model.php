<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_model extends CI_Model {

    private $tabla_productos = "productos";

    public function registrar_producto($data)
    {

        $insert = $this->db->insert("productos", $data);
        if ($insert) {
            $producto = $this->db->insert_id();
            $datos    = array(
                            'tabla'      => $this->tabla_productos,
                            'cod_reg'    => $producto,
                            'usr_regins' => $this->session->userdata('id_usuario'),
                            'fec_regins' => date('Y-m-d'),
                        );
            $result = $this->db->insert('auditoria', $datos);
            return $result;
        }else{
            return false;
        }
    }


    public function actualizar_producto($data, $id)
    {   
        $this->db->where('id_producto', $id);
        $update = $this->db->update($this->tabla_productos, $data);
        if ($update) {
            $datos=array(
                'usr_regmod' => $this->session->userdata('id_usuario'),
                'fec_regmod' => date('Y-m-d'),
            );
            $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_productos);
            $result = $this->db->update('auditoria', $datos);

            return $result;
        }else{
            return false;
        }
    }


    public function listar()
    {

        $this->db->select('productos.*,
                           proyectos.nombre as name_proyecto,
                           proyectos.codigo as codigo_proyecto,
                           proyectos.id_proyecto as id_proyecto,
                           auditoria.status,
                           lval.descriplval as nombre_clasificacion,
                           lvsts.descriplval as sts_producto,
                           u.correo_usuario,
                           auditoria.fec_regins, lv2.descriplval as nom_etapa ');
        $this->db->from("productos");
        $this->db->join('proyectos', 'proyectos.id_proyecto = productos.cod_proyecto', 'left');
        $this->db->join('auditoria', 'productos.id_producto = auditoria.cod_reg', 'left');
        $this->db->join('proyectos_clasificacion', 'proyectos_clasificacion.id_proyecto_clasificacion = productos.cod_proyecto_clasificacion', 'left');
        $this->db->join('lval', 'lval.codlval = proyectos_clasificacion.clasificacion','left');
        $this->db->join('lval lvsts', 'lvsts.codlval = productos.STSPRODUCTO','left');
        $this->db->join('usuario u', 'u.id_usuario = auditoria.usr_regins','left');
        $this->db->join('lval lv2', 'lv2.codlval = productos.etapas','left');
        $this->db->where('auditoria.tabla', $this->tabla_productos);
        $resultados = $this->db->get();//print_r($this->db->last_query());die;
        return $resultados->result();
    }


    public function getproductosdisponibles()
    {

        $this->db->select('productos.id_producto, productos.descripcion');
        $this->db->from("productos");
        $this->db->join('proyectos', 'proyectos.id_proyecto = productos.cod_proyecto', 'left');
        $this->db->join('auditoria', 'productos.id_producto = auditoria.cod_reg', 'left');
        $this->db->join('proyectos_clasificacion', 'proyectos_clasificacion.id_proyecto_clasificacion = productos.cod_proyecto_clasificacion', 'left');
        $this->db->join('lval', 'lval.codlval = proyectos_clasificacion.clasificacion','left');
        $this->db->join('lval lvsts', 'lvsts.codlval = productos.STSPRODUCTO','left');
        $this->db->join('usuario u', 'u.id_usuario = auditoria.usr_regins','left');
        $this->db->join('lval lv2', 'lv2.codlval = productos.etapas','left');
        $this->db->where('auditoria.tabla', $this->tabla_productos);
        $this->db->where('lvsts.tipolval', "STSPRODUCTO");
        $this->db->where('lvsts.descriplval', "DISPONIBLE");
        $resultados = $this->db->get();//print_r($this->db->last_query());die;
        return $resultados->result();
    }



    public function getproducto($productos)
    {

        $this->db->select('productos.*,
                           proyectos.nombre as name_proyecto,
                           proyectos.codigo as codigo_proyecto,
                           proyectos.id_proyecto as id_proyecto,
                           auditoria.status,
                           lval.descriplval as zona,
                           lvsts.descriplval as sts_producto,
                           u.correo_usuario,
                           auditoria.fec_regins, lv2.descriplval as nom_etapa ');
        $this->db->from("productos");
        $this->db->join('proyectos', 'proyectos.id_proyecto = productos.cod_proyecto', 'left');
        $this->db->join('auditoria', 'productos.id_producto = auditoria.cod_reg', 'left');
        $this->db->join('proyectos_clasificacion', 'proyectos_clasificacion.id_proyecto_clasificacion = productos.cod_proyecto_clasificacion', 'left');
        $this->db->join('lval', 'lval.codlval = proyectos_clasificacion.clasificacion','left');
        $this->db->join('lval lvsts', 'lvsts.codlval = productos.STSPRODUCTO','left');
        $this->db->join('usuario u', 'u.id_usuario = auditoria.usr_regins','left');
        $this->db->join('lval lv2', 'lv2.codlval = productos.etapas','left');
        $this->db->where('auditoria.tabla', $this->tabla_productos);
        $this->db->where('productos.id_producto', $productos);
        $resultados = $this->db->get();//print_r($this->db->last_query());die;
        return $resultados->result();
    }


    public function getproductosCorrida($proyecto, $etapas, $zonas)
    {

        $this->db->select('productos.id_producto, productos.descripcion');
        $this->db->from("productos");
        $this->db->join('proyectos', 'proyectos.id_proyecto = productos.cod_proyecto', 'left');
        $this->db->join('auditoria', 'productos.id_producto = auditoria.cod_reg', 'left');
        $this->db->join('proyectos_clasificacion', 'proyectos_clasificacion.id_proyecto_clasificacion = productos.cod_proyecto_clasificacion', 'left');
        $this->db->join('lval', 'lval.codlval = proyectos_clasificacion.clasificacion','left');
        $this->db->join('lval lvsts', 'lvsts.codlval = productos.STSPRODUCTO','left');
        $this->db->join('usuario u', 'u.id_usuario = auditoria.usr_regins','left');
        $this->db->join('lval lv2', 'lv2.codlval = productos.etapas','left');
        $this->db->join('proyectos_clasificacion pc', 'pc.id_proyecto_clasificacion = productos.cod_proyecto_clasificacion', 'left');
        $this->db->where('auditoria.tabla', $this->tabla_productos);
        $this->db->where('lvsts.tipolval', "STSPRODUCTO");
        $this->db->where('lvsts.descriplval', "DISPONIBLE");
        $this->db->where('productos.cod_proyecto', $proyecto);
        $this->db->where('productos.cod_proyecto_clasificacion', $zonas);
        $this->db->where('pc.etapa', $etapas);
        $resultados = $this->db->get();//print_r($this->db->last_query());die;
        return $resultados->result();
    }


    public function status_producto($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_productos);
        $this->db->update('auditoria', $datos);
    }


    public function eliminar_producto($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_productos, array('id_producto' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_productos));
                echo json_encode("<span>el producto se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }


    public function status_multiple_productos($id, $status)
    {
        $date = date('Y-m-d');
        $productos = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = '$date', usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = '$date' WHERE cod_reg in (" . $productos . ") AND tabla='" . $this->tabla_productos . "'");
    }

    public function eliminar_multiple_productos($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $producto)
        {
            if($this->db->delete($this->tabla_productos, array('id_producto' => $producto))){
                $this->db->delete('auditoria', array('cod_reg' => $producto, 'tabla' => $this->tabla_productos));
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }




    public function listado_valores_sts()
    {
        $this->db->where('a.tabla', "lval");
        $this->db->where('lv.tipolval', "STSPRODUCTO");
        $this->db->select('lv.codlval, lv.tipolval, lv.descriplval, a.fec_regins, u.correo_usuario, a.status, tlv.descriplval as descriptipolval');
        $this->db->from("lval" . " lv");
        $this->db->join('auditoria a', 'lv.codlval = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $this->db->join('tipolval tlv', 'tlv.tipolval = lv.tipolval');
        $resultados = $this->db->get();
        return $resultados->result();
    }

}

/* End of file Productos_model.php */
/* Location: ./application/models/Productos_model.php */
