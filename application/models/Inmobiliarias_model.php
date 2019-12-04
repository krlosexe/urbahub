<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Inmobiliarias_model extends CI_Model
{

    private $tabla_inmobiliaria = "inmobiliarias";

    public function listado_inmobiliarias()
    {
        $this->db->where('a.tabla', $this->tabla_inmobiliaria);
        $this->db->select('i.*, a.fec_regins, u.correo_usuario, a.status, dt.nombre_datos_personales AS nombres, dt.apellido_p_datos_personales AS paterno, dt.apellido_m_datos_personales AS materno, lv.descriplval');
        $this->db->from($this->tabla_inmobiliaria . ' i');
        $this->db->join('auditoria a', 'i.id_inmobiliaria = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $this->db->join('usuario c', 'i.id_coordinador = c.id_usuario');
        $this->db->join('datos_personales dt', 'c.id_usuario = dt.id_usuario');
        $this->db->join('lval lv', 'i.localidad = lv.codlval');
        $resultados = $this->db->get();
        return $resultados->result();
    }   
        
    public function registrar_inmobiliaria($data){
        $this->db->insert($this->tabla_inmobiliaria, $data);
        $datos=array(
            'tabla' => $this->tabla_inmobiliaria,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);
    }

    public function actualizar_inmobiliaria($id, $data)
    {
        $this->db->where('id_inmobiliaria', $id);
        $this->db->update($this->tabla_inmobiliaria, $data);
        $datos=array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_inmobiliaria);
        $this->db->update('auditoria', $datos);
    }

    public function verificar_inmobiliaria($data)
    {
        $this->db->where('codigo', $data['codigo']);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_inmobiliaria);
        return $resultados->result_array();
    }

    public function eliminar_inmobiliaria($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_inmobiliaria, array('id_inmobiliaria' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_inmobiliaria));
                echo json_encode("<span>La inmobiliaria se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }

    public function status_inmobiliaria($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_inmobiliaria);
        $this->db->update('auditoria', $datos);
    }

    public function eliminar_multiple_inmobiliaria($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $inmobiliaria)
        {
            if($this->db->delete($this->tabla_inmobiliaria, array('id_inmobiliaria' => $inmobiliaria))){
                $this->db->delete('auditoria', array('cod_reg' => $inmobiliaria, 'tabla' => $this->tabla_inmobiliaria));
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }

    public function status_multiple_inmobiliaria($id, $status)
    {
        $inmobiliarias = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $inmobiliarias . ") AND tabla='" . $this->tabla_inmobiliaria . "'");
    }

    public function coordinadores()
    {
        $this->db->where('r.nombre_rol', 'COORDINADOR');
        $this->db->where('a.tabla', 'usuario');
        $this->db->where('a.status', 1);
        $this->db->select('u.id_usuario, dt.nombre_datos_personales AS nombres, dt.apellido_p_datos_personales AS paterno, dt.apellido_m_datos_personales AS materno');
        $this->db->from('rol r');
        $this->db->join('usuario u', 'r.id_rol = u.id_rol');
        $this->db->join('datos_personales dt', 'u.id_usuario = dt.id_usuario');
        $this->db->join('auditoria a', 'u.id_usuario = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }

    public function localidades()
    {
        $this->db->where('lv.tipolval', 'LOCALIDAD');
        $this->db->where('a.tabla', 'lval');
        $this->db->where('a.status', 1);
        $this->db->select('lv.*');
        $this->db->from('lval lv');
        $this->db->join('auditoria a', 'lv.codlval = a.cod_reg');
        $resultados = $this->db->get();
        return $resultados->result();
    }

}