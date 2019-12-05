<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedores_model extends CI_Model {

    private $tabla_vendedor                 = "vendedores";
    private $tabla_inmobliarias_vendedores  = "vendedores_inmobiliarias";
    private $tabla_cartera_clientes         = "cartera_clientes";
    private $tabla_inmobiliarias            = "inmobiliarias";

    public function getusuariosvendedores()
    {
        /*$this->db->select('usuario.*, datos_personales.nombre_datos_personales as nombre_usuario, datos_personales.apellido_p_datos_personales as apellido_user');
        $this->db->join('auditoria', 'usuario.id_usuario = auditoria.cod_reg');
        $this->db->join('datos_personales', 'usuario.id_usuario = datos_personales.id_usuario');
        $this->db->where('auditoria.status', 1);
        $this->db->where('auditoria.tabla', "usuario");
        $this->db->from('usuario');
        $this->db->order_by('datos_personales.nombre_datos_personales', 'asc');
        $result = $this->db->get();
        return $result->result();*/

         $this->db->select('usuario.*, datos_personales.nombre_datos_personales as nombre_usuario, datos_personales.apellido_p_datos_personales as apellido_user');
        $this->db->join('auditoria', 'usuario.id_usuario = auditoria.cod_reg');
        $this->db->join('datos_personales', 'usuario.id_usuario = datos_personales.id_usuario');
        $this->db->where('auditoria.status', 1);
        $this->db->where('auditoria.tabla', "usuario");
        $this->db->from('usuario');
        $this->db->order_by('datos_personales.nombre_datos_personales', 'asc');
        $result = $this->db->get();
        return $result->result();

    }


    public function getusuariosvendedor($id)
    {
        $this->db->select('usuario.*, datos_personales.nombre_datos_personales as nombre_usuario, datos_personales.apellido_p_datos_personales as apellido_user');
        $this->db->join('auditoria', 'usuario.id_usuario = auditoria.cod_reg');
        $this->db->join('datos_personales', 'usuario.id_usuario = datos_personales.id_usuario');
        $this->db->where('auditoria.status', 1);
        $this->db->where('auditoria.tabla', "usuario");
        $this->db->where('usuario.id_usuario', $id);
        $this->db->from('usuario');
        $result = $this->db->get();
        return $result->result();
    }

    public function getvendedor($id)
    {
       $this->db->where('id_usuario', $id);
       $result = $this->db->get('vendedores');
       //return $result->row();
       if($result)
        return $result->result();
       else
        return $result; 
    }

    public function registrar_vendedor($data, $proyectos, $inmobiliarias, $proyectos_clientes, $clientes)
    {
        $insert = $this->db->insert($this->tabla_vendedor, $data);
        if ($insert) {
            $vendedor = $this->db->insert_id();
            $datos    = array(
                            'tabla'      => $this->tabla_vendedor,
                            'cod_reg'    => $vendedor,
                            'usr_regins' => $this->session->userdata('id_usuario'),
                            'fec_regins' => date('Y-m-d'),
                        );
            $result = $this->db->insert('auditoria', $datos);

            if (isset($proyectos)){
                $cont = 0;
                foreach($inmobiliarias as $inmobiliaria)
                {
                    $array = array(
                        'id_vendedor'     => $vendedor,
                        'id_proyecto'     => $proyectos[$cont],
                        'id_inmobiliaria' => $inmobiliaria,
                    );
                    $this->db->insert($this->tabla_inmobliarias_vendedores, $array);

                    $cod = $this->db->insert_id();
                    $datos_a    = array('tabla'      => $this->tabla_inmobliarias_vendedores,
                                        'cod_reg'    => $cod,
                                        'usr_regins' => $this->session->userdata('id_usuario'),
                                        'fec_regins' => date('Y-m-d'),
                                        );
                    $this->db->insert('auditoria', $datos_a);


                    $cont = $cont + 1;
                }
            }

             if (isset($proyectos_clientes)){
                $cont2 = 0;
                foreach($proyectos_clientes as $proyecto_clientes)
                {
                    $array2 = array(
                        'id_vendedor'     => $vendedor,
                        'id_proyecto'     => $proyecto_clientes,
                        'id_cliente'      => $clientes[$cont2],
                    );
                    $this->db->insert($this->tabla_cartera_clientes, $array2);

                    $cod2       = $this->db->insert_id();
                    $datos_a    = array('tabla'      => $this->tabla_cartera_clientes,
                                        'cod_reg'    => $cod2,
                                        'usr_regins' => $this->session->userdata('id_usuario'),
                                        'fec_regins' => date('Y-m-d'),
                                        );
                    $this->db->insert('auditoria', $datos_a);

                    $cont2 = $cont2 + 1;
                }
            }


            return $result;
        }else{
            return false;
        }
    }


    public function actualizar_vendedor($data, $id, $proyectos, $inmobiliarias, $proyectos_clientes, $clientes)
    {   
        $this->db->where('id_vendedor', $id);
        $update = $this->db->update($this->tabla_vendedor, $data);
        if ($update) {
            $datos=array(
                'usr_regmod' => $this->session->userdata('id_usuario'),
                'fec_regmod' => date('Y-m-d'),
            );
            $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_vendedor);
            $result = $this->db->update('auditoria', $datos);

            if (isset($proyectos)){
                $cont = 0;
                foreach($proyectos as $proyecto){
                    if ($proyecto != "") {
                       $query = $this->db->query("SELECT * FROM ".$this->tabla_inmobliarias_vendedores." WHERE id_vendedor=".$id." AND id_proyecto=".$proyecto);
                    if (sizeof($query->result()) == 0) {
                        $array = array(
                            'id_vendedor'     => $id,
                            'id_proyecto'     => $proyectos[$cont],
                            'id_inmobiliaria' => $inmobiliarias[$cont],
                        );
                            $this->db->insert($this->tabla_inmobliarias_vendedores, $array);

                            $cod = $this->db->insert_id();
                            $datos_a    = array('tabla'      => $this->tabla_inmobliarias_vendedores,
                                                'cod_reg'    => $cod,
                                                'usr_regins' => $this->session->userdata('id_usuario'),
                                                'fec_regins' => date('Y-m-d'),
                                                );
                            $this->db->insert('auditoria', $datos_a);
                        }
                    }
                    $cont = $cont + 1;
                }
            }

            if (isset($proyectos_clientes)){
                $cont2 = 0;
                foreach($proyectos_clientes as $proyecto_clientes)
                {
                    if ($proyecto_clientes != ""){
                        $query = $this->db->query("SELECT * FROM ".$this->tabla_cartera_clientes." WHERE id_vendedor=".$id." AND id_proyecto=".$proyecto_clientes." AND id_cliente = ".$clientes[$cont2]);
                        if (sizeof($query->result()) == 0){
                            $array3 = array(
                                'id_vendedor'     => $id,
                                'id_proyecto'     => $proyecto_clientes,
                                'id_cliente'      => $clientes[$cont2],
                            );
                            $this->db->insert($this->tabla_cartera_clientes, $array3);

                            $cod3       = $this->db->insert_id();
                            $datos_a    = array('tabla'      => $this->tabla_cartera_clientes,
                                                'cod_reg'    => $cod3,
                                                'usr_regins' => $this->session->userdata('id_usuario'),
                                                'fec_regins' => date('Y-m-d'),
                                                );
                            $this->db->insert('auditoria', $datos_a);    
                        }
                               
                    }

                    $cont2 = $cont2 + 1;
                }
            }


            return $result;
        }else{
            return false;
        }
    }



    public function listar()
    {
        /*$this->db->select('vendedores.*,
                           datos_personales.nombre_datos_personales as nombre_user,
                           datos_personales.apellido_p_datos_personales as apellido_user,
                           datos_personales.apellido_m_datos_personales as apellido_m_user,
                           lval.descriplval as tipovendedor,
                           auditoria.status,
                           auditoria.usr_regins,
                           auditoria.fec_regins,
                           usr.correo_usuario as email,
                           usuario.correo_usuario as user_regis');*/
        $this->db->select('vendedores.*,
                           datos_personales.nombre_datos_personales as nombre_user,
                           datos_personales.apellido_p_datos_personales as apellido_user,
                           datos_personales.apellido_m_datos_personales as apellido_m_user,
                           auditoria.status,
                           auditoria.usr_regins,
                           auditoria.fec_regins,
                           usr.correo_usuario as email,
                           usuario.correo_usuario as user_regis');
        $this->db->from("vendedores");
        $this->db->join('auditoria', 'vendedores.id_vendedor = auditoria.cod_reg');
        $this->db->join('usuario', 'auditoria.usr_regins = usuario.id_usuario');
        $this->db->join('usuario usr', 'vendedores.id_usuario = usr.id_usuario');
        $this->db->join('datos_personales', 'vendedores.id_usuario = datos_personales.id_usuario');
        //$this->db->join('lval', 'lval.codlval = vendedores.tipo_vendedor');
        $this->db->where('auditoria.tabla', $this->tabla_vendedor);
        $resultados = $this->db->get();
        if($resultados)
            return $resultados->result();
        else
            return $resultados;
    }




    public function eliminar_vendedor($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_vendedor, array('id_vendedor' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_vendedor));
                echo json_encode("<span>el vendedor se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }



    public function status_vendedor($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_vendedor);
        $this->db->update('auditoria', $datos);
    }

    public function status_vendedor_proyecto($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_inmobliarias_vendedores);
        $this->db->update('auditoria', $datos);
    }



    public function status_cartera_cliente($id, $status)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_cartera_clientes);
        $this->db->update('auditoria', $datos);
    }

    


    public function eliminar_multiple_vendedores($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $vendedor)
        {
            if($this->db->delete($this->tabla_vendedor, array('id_vendedor' => $vendedor))){
                $this->db->delete('auditoria', array('cod_reg' => $vendedor, 'tabla' => $this->tabla_vendedor));
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }


     public function status_multiple_vendedores($id, $status)
    {
        $date = date('Y-m-d');
        $proyectos = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = '$date', usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = '$date' WHERE cod_reg in (" . $proyectos . ") AND tabla='" . $this->tabla_vendedor . "'");
    }



    public function buscarInmobiliarias($vendedor)
    {
        $this->db->select('p.id_proyecto as idproyecto, p.nombre as nombre_proyecto, p.codigo as codigo_proyecto, i.codigo, i.nombre, dt.nombre_datos_personales AS nombres, dt.apellido_p_datos_personales AS paterno, dt.apellido_m_datos_personales AS materno, iv.*, a.status');
        $this->db->from($this->tabla_inmobliarias_vendedores . ' iv');
        $this->db->join($this->tabla_inmobiliarias . ' i', 'iv.id_inmobiliaria = i.id_inmobiliaria');
        $this->db->join('proyectos p', 'iv.id_proyecto = p.id_proyecto');
        $this->db->join('usuario u', 'i.id_coordinador = u.id_usuario');
        $this->db->join('datos_personales dt', 'u.id_usuario = dt.id_usuario');
        $this->db->join('auditoria a', 'a.cod_reg = iv.id');
        $this->db->where('a.tabla', $this->tabla_inmobliarias_vendedores);
        $this->db->where('iv.id_vendedor', $vendedor);
        $resultados = $this->db->get();
        return $resultados->result();
    }



    public function buscarClientes($vendedor)
    {
        $this->db->select('cc.*, 
                           p.codigo as codigo_proyecto_cliente,
                           p.nombre as name_proyecto_cliente,
                           dp.nombre_datos_personales as name_cliente,
                           dp.apellido_p_datos_personales as apellido_p_clinte,
                           dp.apellido_m_datos_personales as apellido_m_clinte,
                           a.status'
                          );
        $this->db->join('proyectos p', 'p.id_proyecto = cc.id_proyecto');
        $this->db->join('cliente_pagador cp', 'cp.id_cliente = cc.id_cliente');
        $this->db->join('datos_personales dp', 'dp.id_datos_personales = cp.id_datos_personales');
        $this->db->join('auditoria a', 'a.cod_reg = cc.id');
        $this->db->from($this->tabla_cartera_clientes . ' cc');
        $this->db->where('a.tabla', $this->tabla_cartera_clientes);
        $this->db->where('cc.id_vendedor', $vendedor);
        $resultados = $this->db->get();
        return $resultados->result();
    }




    public function eliminar_inmobiliaria_vendedor($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_inmobliarias_vendedores, array('id' => $id))){
                throw new Exception("<span>Ha ocurrido un error, intentelo de nuevo!</span>");
            }else{
                $datos=array(
                    'usr_regmod' => $this->session->userdata('id_usuario'),
                    'fec_regmod' => date('Y-m-d'),
                );
                $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_inmobliarias_vendedores);
                $this->db->update('auditoria', $datos);
                echo json_encode("<span>La inmobiliaria se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }


    public function eliminar_cartera_cliente($id)
    {
        try { 
            if(!$this->db->delete($this->tabla_cartera_clientes, array('id' => $id))){
                throw new Exception("<span>Ha ocurrido un error, intentelo de nuevo!</span>");
            }else{
                $datos=array(
                    'usr_regmod' => $this->session->userdata('id_usuario'),
                    'fec_regmod' => date('Y-m-d'),
                );
                $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_cartera_clientes);
                $this->db->update('auditoria', $datos);
                echo json_encode("<span>El registro se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }
    }


    public function getrfc($id, $rfc)
    {
        $this->db->where('id_vendedor', $id);
        $this->db->where('rfc', $rfc);
        $result = $this->db->get('vendedores');
        return $result->row();
    }


}

/* End of file Vendedores_model.ph///p */
/* Location: ./application/models/Vendedores_model.php */
