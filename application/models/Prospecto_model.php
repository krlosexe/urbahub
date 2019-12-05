<?php 



if (!defined('BASEPATH')) exit ('No direct script access allowed');



Class Prospecto_model extends CI_Model

{



    private $tabla_prospecto        = "prospecto_vendedor";

    private $tabla_lval             = "lval";

    private $tabla_vendedor         = "vendedores";

    private $tabla_datosPersonales  = "datos_personales";

    private $tabla_contacto         = "contacto";

    private $tabla_clientePagador   = "cliente_pagador";

    private $tabla_cuenta_clientePa = "cuenta_cliente";

    private $tabla_repLegal         = "repLegal_cliente_pagador";

   

    public function listarProspecto(){

      //-------------------------------------------------------------------------------------
      //--Migracion Mongo DB
       if ($this->session->userdata('id_rol') != '5b8dc599d06020d3e9a9eb90') {
          $id_user = $this->session->userdata('id_usuario');
          $res_prospecto = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('id_usuario'=>$id_user,'eliminado'=>false,'tipo_cliente' => 'PROSPECTO'))->get('prospecto_vendedor');
      }else{
          $res_prospecto = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'tipo_cliente' => 'PROSPECTO'))->get('prospecto_vendedor');
      }
      $listado = [];
      foreach ($res_prospecto as $valor) {
          $valor["id_prospecto"] = (string)$valor["_id"]->{'$id'};
          $id_cliente_pagador = new MongoDB\BSON\ObjectId($valor["id_cliente"]);
          //---Consulto al cliente...
          $res_cliente_pagador = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_cliente_pagador))->get('cliente_pagador');

          $valor["tipo_persona_cliente"] = $res_cliente_pagador[0]["tipo_persona_cliente"];
          $valor["id_datos_personales"] = $res_cliente_pagador[0]["id_datos_personales"];
          $valor["id_contacto"] = $res_cliente_pagador[0]["id_contacto"];
          $id_datos_personales = new MongoDB\BSON\ObjectId($valor["id_datos_personales"]);
          
          //---Consulto datos personales...
          $res_datos_personales = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'_id'=>$id_datos_personales))->get('datos_personales');
          $valor["nombre_datos_personales"] = $res_datos_personales[0]["nombre_datos_personales"];
          
          (isset($res_datos_personales[0]["apellido_p_datos_personales"]))? $valor["apellido_p_datos_personales"] = $res_datos_personales[0]["apellido_p_datos_personales"]:$valor["apellido_p_datos_personales"] ="";

          (isset($res_datos_personales[0]["apellido_m_datos_personales"]))? $valor["apellido_m_datos_personales"] = $res_datos_personales[0]["apellido_m_datos_personales"]:$valor["apellido_m_datos_personales"] ="";

          (isset($res_datos_personales[0]["rfc_datos_personales"]))? $valor["rfc_datos_personales"] = $res_datos_personales[0]["rfc_datos_personales"]:$valor["rfc_datos_personales"] ="";
          
          //---Consulto contactos
          $id_contacto = new MongoDB\BSON\ObjectId($valor["id_contacto"]);
          $res_contacto = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'_id'=>$id_contacto))->get('contacto');

          $valor["telefono_principal_contacto"] = $res_contacto[0]["telefono_principal_contacto"];
          
          (isset($res_contacto[0]["telefono_movil_contacto"]))? $valor["telefono_movil_contacto"] = $res_contacto[0]["telefono_movil_contacto"]:$valor["telefono_movil_contacto"]="";
          
          (isset($res_contacto[0]["correo_opcional_contacto"]))? $valor["correo_opcional_contacto"] = $res_contacto[0]["correo_opcional_contacto"]:$valor["correo_opcional_contacto"] ="";
          (isset($res_contacto[0]["correo_contacto"]))? $valor["correo_contacto"] = $res_contacto[0]["correo_contacto"]:$valor["correo_contacto"] ="";
          (isset($res_contacto[0]["telefono_trabajo_contacto"]))? $valor["telefono_trabajo_contacto"] = $res_contacto[0]["telefono_trabajo_contacto"]:$valor["telefono_trabajo_contacto"] ="";
          (isset($res_contacto[0]["telefono_fax_contacto"]))? $valor["telefono_fax_contacto"] = $res_contacto[0]["telefono_fax_contacto"]:$valor["telefono_fax_contacto"] ="";
          (isset($res_contacto[0]["telefono_casa_contacto"]))? $valor["telefono_casa_contacto"] = $res_contacto[0]["telefono_casa_contacto"]:$valor["telefono_casa_contacto"] ="";
          
          //---Consulto proyectos
          //var_dump($valor["id_proyecto"]);die('');
          if(isset($valor["id_proyecto"]) && ($valor["id_proyecto"]!="")){
              $id_proyecto = new MongoDB\BSON\ObjectId($valor["id_proyecto"]);
              $res_proyecto = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'_id'=>$id_proyecto))->get('proyectos');
              $valor["nombre_proyecto"] = $res_proyecto[0]["nombre"];
          }else{
              $valor["nombre_proyecto"] = "No posee";
          }
          //---Consulto datos personales del usuario y vendedor
          $id_vendedor = new MongoDB\BSON\ObjectId($valor["id_vendedor"]);
          $res_vendedor = $this->mongo_db->where(array('_id'=>$id_vendedor))->get('vendedores');

          $id_usuario_vendedor =  (string)$res_vendedor[0]["id_usuario"];

          $id_us_ve = new MongoDB\BSON\ObjectId($id_usuario_vendedor);
          $res_dp2 = $this->mongo_db->where(array('id_usuario'=>$id_us_ve))->get('datos_personales');
          $valor["nombre_vendedor"] = $res_dp2[0]["nombre_datos_personales"];
          $valor["apellido_vendedor"] = $res_dp2[0]["apellido_p_datos_personales"];
          
          $vector_auditoria = reset($valor["auditoria"]);
          $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();

          //--consulto correo
          $id_registro = $valor["auditoria"][0]->cod_user;
          $id_reg = new MongoDB\BSON\ObjectId($id_registro);
           
          $res_us = $this->mongo_db->where(array('_id'=>$id_reg))->get('usuario');
          $valor["correo_usuario"] = $res_us[0]["correo_usuario"];
          $valor["id_rol"] = $res_us[0]["id_rol"];
          //---
          $valor['vendedor'] = $valor['nombre_vendedor']." ".$valor['apellido_vendedor'];

          $listado[] =$valor;
      }
      return $listado;
      //-------------------------------------------------------------------------------------
      /*$this->db->where(array('a.tabla' => 'prospecto_vendedor', 'pv.tipo_cliente' => 'PROSPECTO'));

      if ($this->session->userdata('id_rol') != 1) {

          $id_user = $this->session->userdata('id_usuario');

          $this->db->where('a.usr_regins', $id_user);
      }

        $this->db->select('
          pv.id_vendedor,
          pv.id_proyecto,
          pv.id, 
          pv.tipo_cliente,
          pv.observacion,
          pv.id_cliente,
          cp.tipo_persona_cliente,
          cp.id_datos_personales,
          cp.id_contacto,
          dp.nombre_datos_personales,
          dp.apellido_p_datos_personales,
          dp.apellido_m_datos_personales,
          dp.rfc_datos_personales,
          c.telefono_principal_contacto,
          c.telefono_movil_contacto,
          c.correo_opcional_contacto,
          c.correo_contacto,
          c.telefono_trabajo_contacto,
          c.telefono_fax_contacto,
          c.telefono_casa_contacto,
          p.nombre as nombre_proyecto,
          dp2.nombre_datos_personales as nombre_vendedor,
          dp2.apellido_p_datos_personales as apellido_vendedor,
          a.*,
          u2.correo_usuario,
          u2.id_rol');

         $this->db->join('cliente_pagador cp', 'cp.id_cliente = pv.id_cliente','left');

        $this->db->join('datos_personales dp', 'dp.id_datos_personales = cp.id_datos_personales','left');

        $this->db->join($this->tabla_contacto . ' c', 'cp.id_contacto = c.id_contacto','left');

        $this->db->join('proyectos p', 'p.id_proyecto = pv.id_proyecto','left');

        $this->db->join('vendedores v', 'v.id_vendedor = pv.id_vendedor','left');

        $this->db->join('usuario u', 'u.id_usuario = v.id_usuario','left');

        $this->db->join('datos_personales dp2', 'dp2.id_usuario = u.id_usuario','left');

        $this->db->join('auditoria a', 'pv.id = a.cod_reg');

        $this->db->join('usuario u2', 'a.usr_regins = u2.id_usuario');

        $this->db->order_by('pv.id', 'desc');

        $this->db->from('prospecto_vendedor' . ' pv');

         $resultados = $this->db->get();

     //print_r($this->db->last_query());die;

        return $resultados->result_array();*/
    }





    public function getProspecto($id)

    {

      $this->db->where('pv.id_cliente', $id);

      $this->db->select('pv.id_vendedor, pv.id_proyecto,pv.id, pv.tipo_cliente, pv.id_cliente, cp.tipo_persona_cliente, cp.id_datos_personales, cp.id_contacto, dp.nombre_datos_personales, dp.apellido_p_datos_personales, dp.apellido_m_datos_personales, dp.rfc_datos_personales, c.telefono_principal_contacto, c.telefono_movil_contacto, c.correo_opcional_contacto, c.correo_contacto, c.telefono_trabajo_contacto, c.telefono_fax_contacto, c.telefono_casa_contacto, p.nombre as nombre_proyecto, dp2.nombre_datos_personales as nombre_vendedor, dp2.apellido_p_datos_personales as apellido_vendedor, a.*, u2.correo_usuario, u2.id_rol');

      $this->db->join('cliente_pagador cp', 'cp.id_cliente = pv.id_cliente','left');

      $this->db->join('datos_personales dp', 'dp.id_datos_personales = cp.id_datos_personales','left');

      $this->db->join($this->tabla_contacto . ' c', 'cp.id_contacto = c.id_contacto','left');

      $this->db->join('proyectos p', 'p.id_proyecto = pv.id_proyecto','left');

      $this->db->join('vendedores v', 'v.id_vendedor = pv.id_vendedor','left');

      $this->db->join('usuario u', 'u.id_usuario = v.id_usuario','left');

      $this->db->join('datos_personales dp2', 'dp2.id_usuario = u.id_usuario','left');

      $this->db->join('auditoria a', 'pv.id = a.cod_reg');

      $this->db->join('usuario u2', 'a.usr_regins = u2.id_usuario');

      $this->db->order_by('pv.id', 'desc');

      $this->db->from('prospecto_vendedor' . ' pv');

      $resultados = $this->db->get();

     //print_r($this->db->last_query());die;

      return $resultados->row();

    }   



    public function getVendedores(){

        /*if ($this->session->userdata('id_rol') != 1) {

            $id_user = $this->session->userdata('id_usuario');

            $this->db->where('v.id_usuario', $id_user);

        }

        $this->db->where(array('a.tabla' => $this->tabla_vendedor, 'a.status' => 1));

        $this->db->distinct('v.id_vendedor');

        $this->db->select('v.id_vendedor, dp.nombre_datos_personales as nombre_vendedor, apellido_p_datos_personales as apellido_vendedor');

        $this->db->from($this->tabla_vendedor . ' v');

        $this->db->join('datos_personales dp', 'dp.id_usuario = v.id_usuario','left');

        $this->db->join('auditoria a', 'a.cod_reg = v.id_vendedor','left');

        $resultados = $this->db->get();

        //print_r($this->db->last_query());die;

        return $resultados->result();*/
        //-----------------------------------------------------------------------------
        //Migracion Mongo db
        //if ($this->session->userdata('id_rol') != 1) {
        if($this->session->userdata('id_rol')!="5b8dc599d06020d3e9a9eb90"){
            $id_user = $this->session->userdata('id_usuario');
            $resultados = $this->mongo_db->where(array('id_usuario'=>$id_user,'eliminado'=>false,'status'=>true))->get($this->tabla_vendedor);
        }else{
            $resultados = $this->mongo_db->where(array('eliminado'=>false,'status'=>true))->get($this->tabla_vendedor);
        }
        $listado = [];
        //var_dump($resultados);die('');

        foreach ($resultados as $valor) {
          $valor["id_vendedor"] = (string)$valor["_id"]->{'$id'};
          $id_us_ve = new MongoDB\BSON\ObjectId($valor["id_usuario"]);
          $res_dp2 = $this->mongo_db->where(array('id_usuario'=>$id_us_ve))->get('datos_personales');
          $valor["nombre_vendedor"] = $res_dp2[0]["nombre_datos_personales"];
          $valor["apellido_vendedor"] = $res_dp2[0]["apellido_p_datos_personales"];
          $listado[] = $valor; 
        }

        return $listado;
        //-----------------------------------------------------------------------------

    }

    public function obtenerProyecto($id_vendedor)

    {

       /*$this->db->where(array('a.tabla' => 'proyectos', 'a.status' => 1, 'vi.id_vendedor' => $id_vendedor));

      $this->db->select('vi.id_vendedor, vi.id_proyecto, p.nombre as nombre_proyecto');

      $this->db->from('vendedores_inmobiliarias' . ' vi');

      $this->db->join('proyectos p', 'p.id_proyecto = vi.id_proyecto','left');

      $this->db->join('auditoria a', 'a.cod_reg = vi.id_proyecto','left');

      $resultados = $this->db->get();

     // print_r($this->db->last_query());die;

      return $resultados->result();*/
      //--------------------------------------------------------
      //Migracion mongo db
      $id = new MongoDB\BSON\ObjectId($id_vendedor);
      
      $resultados = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'id_vendedor'=>$id_vendedor))->get('vendedores_inmobiliarias');
      
      $listado = [];
      
      foreach ($resultados as $valor) {

          $id_proyecto = new MongoDB\BSON\ObjectId($valor["id_proyecto"]);
          $res_proyecto = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'_id'=>$id_proyecto))->get('proyectos');
          $valor["nombre_proyecto"] = $res_proyecto[0]["nombre"];
          $listado[]= $valor; 
      
      }
      return $listado;
      //--------------------------------------------------------
    }

    public function carteraCliente($id_cliente, $id_proyecto, $id_vendedor)

    {

      $this->db->where(array('id_cliente'    => $id_cliente,

                              'id_proyecto' => $id_proyecto,

                              'id_vendedor'  => $id_vendedor ));

      $resultados = $this->db->get('prospecto_vendedor');

      return $resultados->row_array();       

    }

     public function obtenerCliente($rfc)

    

    {

      $this->db->where(array('cp.tipo_cliente' => "PROSPECTO", 'dp.rfc_datos_personales' => $rfc, 'a.status'=>1));

        $this->db->select('cp.id_cliente, cp.actividad_e_cliente, cp.rfc_img,cp.tipo_cliente ,cp.pais_cliente, cp.tipo_persona_cliente,cp.dominio_fiscal_img,cp.acta_constitutiva, acta_img,cp.giro_mercantil,lv4.descriplval as giro_merca_desc,dp.*, c.*, cop.*, lv1.descriplval as actividad_economica, lv2.descriplval as pais_origen, lv3.descriplval as pais_nacionalidad, a.*, u.correo_usuario, u.id_rol');

        $this->db->from('cliente_pagador' . ' cp');

        $this->db->join('datos_personales dp', 'dp.id_datos_personales = cp.id_datos_personales','left');

        $this->db->join($this->tabla_contacto . ' c', 'cp.id_contacto = c.id_contacto','left');

        $this->db->join('codigo_postal cop', 'c.id_codigo_postal = cop.id_codigo_postal','left');

        $this->db->join($this->tabla_lval . ' lv1', 'cp.actividad_e_cliente = lv1.codlval','left');

        $this->db->join($this->tabla_lval . ' lv2', 'cp.pais_cliente = lv2.codlval','left');

        $this->db->join($this->tabla_lval . ' lv3', 'dp.nacionalidad_datos_personales = lv3.codlval','left');

        $this->db->join($this->tabla_lval . ' lv4', 'cp.giro_mercantil = lv4.codlval','left');

        $this->db->join('auditoria a', 'cp.id_cliente = a.cod_reg');

        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');

         $resultados = $this->db->get();

      //print_r($this->db->last_query());die;

        return $resultados->result();

    }


    /*
    * Obtener cliente
    */
    public function obtenerCliente2($rfc, $id_cliente){

      /*$this->db->where(array('cp.tipo_cliente' => "CLIENTE", 'dp.rfc_datos_personales' => $rfc, 'a.status'=>1));
      $this->db->where('a.tabla', "cliente_pagador");


      $this->db->where('cp.id_cliente !=', $id_cliente);

      $this->db->select('cp.id_cliente, cp.actividad_e_cliente, cp.rfc_img,cp.tipo_cliente ,cp.pais_cliente, cp.tipo_persona_cliente,cp.dominio_fiscal_img,cp.acta_constitutiva, acta_img,cp.giro_mercantil,lv4.descriplval as giro_merca_desc,dp.*, c.*, cop.*, lv1.descriplval as actividad_economica, lv2.descriplval as pais_origen, lv3.descriplval as pais_nacionalidad, a.*, u.correo_usuario, u.id_rol, dpu.*');

        $this->db->from('cliente_pagador' . ' cp');

        $this->db->join('datos_personales dp', 'dp.id_datos_personales = cp.id_datos_personales','left');
        $this->db->join($this->tabla_contacto . ' c', 'cp.id_contacto = c.id_contacto','left');

        $this->db->join('codigo_postal cop', 'c.id_codigo_postal = cop.id_codigo_postal','left');

        $this->db->join($this->tabla_lval . ' lv1', 'cp.actividad_e_cliente = lv1.codlval','left');

        $this->db->join($this->tabla_lval . ' lv2', 'cp.pais_cliente = lv2.codlval','left');

        $this->db->join($this->tabla_lval . ' lv3', 'dp.nacionalidad_datos_personales = lv3.codlval','left');

        $this->db->join($this->tabla_lval . ' lv4', 'cp.giro_mercantil = lv4.codlval','left');
        $this->db->join('auditoria a', 'cp.id_cliente = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $this->db->join('datos_personales dpu', 'dpu.id_usuario = u.id_usuario');

      $resultados = $this->db->get();

      //print_r($this->db->last_query());die;

      return $resultados->row();*/
      //-------------------------------------------------------------------------------------
      //--Migracion Mongo DB
      $id = new MongoDB\BSON\ObjectId($id_cliente);
      $res_cliente_pagador = $this->mongo_db->where(array('eliminado'=>false,'tipo_cliente' => "CLIENTE"))->get($this->tabla_clientePagador);
      $listado = [];
      if(count($res_cliente_pagador)>0){
          //------------------------------
          foreach ($res_cliente_pagador as $clave => $valor) {
              $valor["id_cliente"] = (string)$valor["_id"]->{'$id'};
              if($valor["id_cliente"]!= $id_cliente){
                
                $valores["id_cliente"] = (string)$valor["_id"]->{'$id'};

                (isset($valor["actividad_e_cliente"]))? $valores["actividad_e_cliente"] = $valor["actividad_e_cliente"]:$valores["actividad_e_cliente"] = "";
                  
                $valores["rfc_img"] = $valor["rfc_img"];
                  
                (isset($valor["tipo_cliente"]))? $valores["tipo_cliente"] = $valor["tipo_cliente"]: $valores["tipo_cliente"] = "";
                  
                (isset($valor["pais_cliente"]))? $valores["pais_cliente"] = $valor["pais_cliente"]:$valores["pais_cliente"] = "";
                  
                $valores["tipo_persona_cliente"] = $valor["tipo_persona_cliente"];
                
                $valores["dominio_fiscal_img"] = $valor["dominio_fiscal_img"];
                  
                (isset($valor["acta_constitutiva"]))? $valores["acta_constitutiva"] = $valor["acta_constitutiva"]:$valores["acta_constitutiva"] = "";

                (isset($valor["acta_img"])) ? $valores["acta_img"] = $valor["acta_img"]: $valores["acta_img"] = "";
                (isset($valor["giro_mercantil"])) ? $valores["giro_mercantil"] = $valor["giro_mercantil"]:$valores["giro_mercantil"] = "";

                $valores["id_datos_personales"] = new MongoDB\BSON\ObjectId($valor["id_datos_personales"]);
                //-----------------------------------------------------------------------------------
                //--Consulto datos personales....
                $res_datos_personales = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$valores["id_datos_personales"],'rfc_datos_personales'=>$rfc))->get('datos_personales');
                //Sino coincide id con rfc, devuelvo 0
                if(count($res_datos_personales)==0){
                  $listado = [];
                  return $listado;
                }

                foreach ($res_datos_personales as $valor_dt) {
                      $valores["id_datos_personales"] = (string)$valor_dt["_id"]->{'$id'};
                      $valores["id_contacto"] = (string)$valor_dt["id_contacto"];
                      $valores["nombre_datos_personales"] = $valor_dt["nombre_datos_personales"];

                      (isset($valor_dt["apellido_p_datos_personales"]))? $valores["apellido_p_datos_personales"] = $valor_dt["apellido_p_datos_personales"]: $valores["apellido_p_datos_personales"] ="";

                      (isset($valor_dt["apellido_m_datos_personales"]))? $valores["apellido_m_datos_personales"] = $valor_dt["apellido_m_datos_personales"]: $valores["apellido_m_datos_personales"] ="";
                      
                      (isset($valor_dt["curp_datos_personales"]))? $valores["curp_datos_personales"] = $valor_dt["curp_datos_personales"]: $valores["curp_datos_personales"] = "";
                      
                      (isset($valor_dt["rfc_datos_personales"]))? $valores["rfc_datos_personales"] = $valor_dt["rfc_datos_personales"]:$valores["rfc_datos_personales"] = "";
                      
                      (isset($valor_dt["genero_datos_personales"]))? $valores["genero_datos_personales"] = $valor_dt["genero_datos_personales"]:$valores["genero_datos_personales"] ="";

                      (isset($valor_dt["fecha_nac_datos_personales"]))? $valores["fecha_nac_datos_personales"] = $valor_dt["fecha_nac_datos_personales"]: $valores["fecha_nac_datos_personales"] ="";
                      
                      (isset($valor_dt["edo_civil_datos_personales"])) ? $valores["edo_civil_datos_personales"] = $valor_dt["edo_civil_datos_personales"]: $valores["edo_civil_datos_personales"] ="";
                      
                      (isset($valor_dt["nacionalidad_datos_personales"])) ? $valores["nacionalidad_datos_personales"] = $valor_dt["nacionalidad_datos_personales"]:$valores["nacionalidad_datos_personales"] ="";

                }
                ///-Fin res_datos_personales
                //-----------------------------------------------------------------------------------
                //--Consulto contactos
                $id_contacto = new MongoDB\BSON\ObjectId($valor["id_contacto"]);
                $res_contacto = $this->mongo_db->where(array("_id"=>$id_contacto))->get($this->tabla_contacto);
                //Sino coincide id, devuelvo 0
                if(count($res_contacto)==0){
                  $listado = [];
                  return $listado;
                }
                //----
                foreach ($res_contacto as $clave_contacto => $valor_contacto) {
                    $valores["id_codigo_postal"] = $valor_contacto["id_codigo_postal"];
                    $valores["telefono_principal_contacto"] = $valor_contacto["telefono_principal_contacto"];
                    (isset($valor_contacto["correo_contacto"]))? $valores["correo_contacto"] = $valor_contacto["correo_contacto"]:$valores["correo_contacto"] = "";

                    (isset($valor_contacto["telefono_movil_contacto"]))? $valores["telefono_movil_contacto"]=$valor_contacto["telefono_movil_contacto"]: $valores["telefono_movil_contacto"] ="";
                    
                    (isset($valor_contacto["direccion_contacto"])) ? $valores["direccion_contacto"] = $valor_contacto["direccion_contacto"]:$valores["direccion_contacto"] ="";

                    (isset($valor_contacto["calle_contacto"])) ? $valores["calle_contacto"] = $valor_contacto["calle_contacto"]:$valores["calle_contacto"] = "";

                    (isset($valor_contacto["exterior_contacto"]))? $valores["exterior_contacto"] = $valor_contacto["exterior_contacto"]:$valores["exterior_contacto"] ="";

                    (isset($valor_contacto["interior_contacto"])) ? $valores["interior_contacto"] = $valor_contacto["interior_contacto"]:$valores["interior_contacto"] ="";

                    #$valores["status"] = $valor_contacto["status"];
                }
                //-----------------------------------------------------------------------------------
                  //--Consulto usuario
                  $id_registro = $valor["auditoria"][0]->cod_user;
                  $id = new MongoDB\BSON\ObjectId($id_registro);
                  $res_us_rg = $this->mongo_db->where(array("_id"=>$id))->get("usuario");

                  //---- //Sino coincide id, devuelvo 0
                  if(count($res_datos_personales)==0){
                    $listado = [];
                    return $listado;
                  }
                  //----
                  foreach ($res_us_rg as $clave_us_reg => $valor_us_reg) {
                      $valores["user_regis"] = $valor_us_reg["correo_usuario"];
                      $valores["id_rol"] = (string)$valor_us_reg["id_rol"];
                      $valores["correo_usuario"] = $valor_us_reg["correo_usuario"];
                  }
                  //$valores["fec_regins"] = $valor["auditoria"][0]->fecha;
                  $vector_auditoria = end($valor["auditoria"]);
                  
                  $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
                  
                  $valores["status"] = $valor["status"];
                  //-----------------------------------------------------------------------------------
                  $listado[] = $valores;
                //------------------------------------------------------------------------------------
              }
          }//fin del foreach....
          //------------------------------
      }
      //-------------------------------------------------------------------------------------
      return $listado;
    }







    public function updateContato($id_contacto, $datos_contacto){
        /*$this->db->where('id_contacto', $id_contacto);
        $this->db->update($this->tabla_contacto, $datos_contacto);*/
        //-----------------------------------------------------------
        //--Migracion Mongo Db
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_contacto2 =  new MongoDB\BSON\ObjectId($id_contacto);
        //---
       
        //---
        $mod_contactos = $this->mongo_db->where(array('_id'=>$id_contacto2))->set($datos_contacto)->update($this->tabla_contacto);

        //--Auditoria
        if($mod_contactos){
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar rol',
                                            'operacion'=>''
                                        );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_contacto2))->push('auditoria',$data_auditoria)->update($this->tabla_contacto); 
        }
        //-----------------------------------------------------------
    }

    public function updateprospecto($id, $data)
    {
        /*$this->db->where('id', $id);
        $this->db->update("prospecto_vendedor", $data);*/
        //-----------------------------------------------------------
        //--Migracion Mongo Db
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_prospecto =  new MongoDB\BSON\ObjectId($id);

        $mod_prospecto = $this->mongo_db->where(array('_id'=>$id_prospecto))->set($data)->update('prospecto_vendedor');

        //--Auditoria
        if($mod_prospecto){
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar rol',
                                            'operacion'=>''
                                        );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_prospecto))->push('auditoria',$data_auditoria)->update('prospecto_vendedor'); 
        }
        //var_dump($mod_prospecto);die('');
        //-----------------------------------------------------------
    }

    public function guardarProspectoVendedor($prospecto_vendedor){

        $this->db->insert($this->tabla_prospecto, $prospecto_vendedor);//print_r($this->db->last_query());die;
        $id_prospecto = $this->db->insert_id();
        $auditoria=array(

                  'tabla' => $this->tabla_prospecto,

                  'cod_reg' => $id_prospecto,

                  'usr_regins' => $this->session->userdata('id_usuario'),

                  'fec_regins' => date('Y-m-d'),

              );

        $this->db->insert('auditoria', $auditoria);

        echo json_encode("<span>El Prospecto se ha registrado exitosamente!</span>");
    }  
    /*
    *   ActualizarProspectoVendedor
    */
    public function actualizarProspectoVendedor($id_prospecto, $prospecto_vendedor, $id_cliente, $id_proyecto){
        
      /*   $this->db->where(array('id_cliente'=> $id_cliente, 'id_proyecto' => $id_proyecto));

        $this->db->update($this->tabla_prospecto, $prospecto_vendedor);

        //print_r($this->db->last_query());

        $datosAuditoria=array(

            'usr_regmod' => $this->session->userdata('id_usuario'),

            'fec_regmod' => date('Y-m-d'),

        );

        $this->db->where('cod_reg', $id_prospecto)->where('tabla', $this->tabla_prospecto);

        $this->db->update('auditoria', $datosAuditoria);*/
      //-----------------------------------------------------------
      //--Migracion Mongo Db}

      $fecha = new MongoDB\BSON\UTCDateTime();

      $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

      $id =  new MongoDB\BSON\ObjectId($id_prospecto);

      $mod_prospecto = $this->mongo_db->where(array('_id'=>$id,'id_proyecto' => $id_proyecto))->set($prospecto_vendedor)->update('prospecto_vendedor');

      //--Auditoria
      if($mod_prospecto){
          $data_auditoria = array(
                                          'cod_user'=>$id_usuario,
                                          'nom_user'=>$this->session->userdata('nombre'),
                                          'fecha'=>$fecha,
                                          'accion'=>'Modificar rol',
                                          'operacion'=>''
                                      );
          $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update('prospecto_vendedor'); 
      }
      //var_dump($mod_prospecto);die('');
      //-----------------------------------------------------------  
    } 

 

  


  public function guardarProspecto($datos){
    /*
      extract($datos);
      // guardado datos domicilio cliente

      $this->db->insert($this->tabla_contacto, $datos_contacto);

      $id_contacto = $this->db->insert_id();

      // guardando datos personales del cliente

      $datosPersonales['id_contacto'] = $id_contacto;

      $this->db->insert($this->tabla_datosPersonales, $datosPersonales);//print_r($this->db->last_query());die;

      $id_datosPersonales = $this->db->insert_id();

      $datosClientePa['id_datos_personales'] = $id_datosPersonales;

      $datosClientePa['id_contacto']= $id_contacto;

      //guardando datos cliente

      $this->db->insert('cliente_pagador', $datosClientePa);//

      $id_cliente= $this->db->insert_id();

      $prospecto_vendedor['id_cliente']= $id_cliente;

      $this->db->insert('prospecto_vendedor', $prospecto_vendedor);//print_r($this->db->last_query());die;

      $id_prospecto= $this->db->insert_id();

      // guardo auditoria el prospecto

      $auditoria=array(

                'tabla' => $this->tabla_prospecto,

                'cod_reg' => $id_prospecto,

                'usr_regins' => $this->session->userdata('id_usuario'),

                'fec_regins' => date('Y-m-d'),
      );

      $this->db->insert('auditoria', $auditoria);

      // guardo cuenta cliente

      $mensaje= "<span>El Prospecto se ha registrado exitosamente!</span>";
      echo json_encode($mensaje);*/
      //---------------------------------------------------------------------
      //Migracion Mongo DB
      extract($datos);
      // guardado datos domicilio cliente
      //var_dump($datosClientePa['tipo_persona_cliente']);die('xxx');
      //inserta en colceccion contactos
      if($datosClientePa['tipo_persona_cliente']=="FISICA"){
          if($datos_contacto["correo_contacto"]==$datos_contacto["correo_opcional_contacto"]){
              echo "<span>El correo contacto no puede ser igual al correo opcional</span>";die('');
          }
      }
      $insertar_contacto = $this->mongo_db->insert($this->tabla_contacto, $datos_contacto);
      
      //Obtengo el ultimo id
      $res_contacto = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_contacto);
      
      $datosPersonales['id_contacto'] = $res_contacto[0]["_id"]->{'$id'};

      // guardando datos personales del cliente
      $insertar_datos_personales = $this->mongo_db->insert($this->tabla_datosPersonales, $datosPersonales);
      
      //Obtengo el ultimo id
      $res_datos_personales= $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_datosPersonales);

      $datosClientePa['id_datos_personales'] = $res_datos_personales[0]["_id"]->{'$id'};      
      
      $datosClientePa['id_contacto']= $datosPersonales['id_contacto'];
      
      (!isset($datosClientePa['actividad_e_cliente']))? $datosClientePa['actividad_e_cliente'] = '':$a="";
      
      (!isset($datosClientePa['pais_cliente']))? $datosClientePa['pais_cliente'] = '':$a="";

      (!isset($datosClientePa['rfc_img']))? $datosClientePa['rfc_img'] = '': $a='';

      (!isset($datosClientePa['dominio_fiscal_img']))? $datosClientePa['dominio_fiscal_img'] = '' : $a='';

      (!isset($datosClientePa['giro_mercantil']))? $datosClientePa['giro_mercantil'] = '' : $a='';

      (!isset($datosClientePa['acta_constitutiva']))? $datosClientePa['acta_constitutiva'] = '' : $a='';

      (!isset($datosClientePa['acta_img']))? $datosClientePa['acta_img'] = '' : $a='';
      
      // guardando datos del cliente
      $insertar_cliente = $this->mongo_db->insert('cliente_pagador', $datosClientePa);
      
      //Obtengo el ultimo id
      $res_cliente= $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get('cliente_pagador');

      $prospecto_vendedor['id_cliente'] = $res_cliente[0]["_id"]->{'$id'}; 

      //guardando datos del prospecto vendedor
      $insertar_prospecto = $this->mongo_db->insert('prospecto_vendedor', $prospecto_vendedor);

      //obtengo el ultimo id  
      $res_prospecto= $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get('prospecto_vendedor');

      $id_prospecto = $res_prospecto[0]["_id"]->{'$id'}; 
      

      $mensaje= "<span>El Prospecto se ha registrado exitosamente!</span>";
      echo json_encode($mensaje);
      //---------------------------------------------------------------------
  }

    public function editarClientePagador($id_cliente, $id_contacto, $id_datos_personales, $datos)

    {

      extract($datos); 

        $this->db->where('id_contacto', $id_contacto);

        $this->db->update($this->tabla_contacto, $datosContacto);



        $this->db->where('id_datos_personales', $id_datos_personales);

        $this->db->update($this->tabla_datosPersonales, $datosPersonales);



        $this->db->where('id_cliente', $id_cliente);

        $this->db->update($this->tabla_clientePagador, $datosClientePa);

          $datosAuditoria=array(

            'usr_regmod' => $this->session->userdata('id_usuario'),

            'fec_regmod' => date('Y-m-d'),

        );

        $this->db->where('cod_reg', $id_cliente)->where('tabla', $this->tabla_clientePagador);

        $this->db->update('auditoria', $datosAuditoria);

    }



   public function guardarCuentaCliente($datos)

   {

    $this->db->insert($this->tabla_cuenta_clientePa, $datos);

    //print_r($this->db->last_query());die;

    $id_cuenta_cliente = $this->db->insert_id();

  

            $auditoria=array(

            'tabla' => $this->tabla_cuenta_clientePa,

            'cod_reg' => $id_cuenta_cliente,

            'usr_regins' => $this->session->userdata('id_usuario'),

            'fec_regins' => date('Y-m-d'),

        );

    $this->db->insert('auditoria', $auditoria);

    echo json_encode("<span>La Cuenta se ha registrado exitosamente!</span>");

   }
   /*
   *  guardarClientePagador
   */
  public function guardarClientePagador($datos, $id){

      //----------------------------------------------------------------------
      //Migracion Mongo DB

      $fecha = new MongoDB\BSON\UTCDateTime();

      $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

      $contador = 0;

      extract($datos);

      extract($id);

       // guardo cuenta cliente
      if(isset($datosCuenta)){
          $contador = $contador +1;

          $datosCuenta['id_cliente']= $id_cliente;
          $datosCuenta['auditoria'] = [ array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      ) ];
          $datosCuenta['status'] =true;
          $datosCuenta['eliminado'] = false;
          $insertar_datos_cliente_cuenta = $this->mongo_db->insert($this->tabla_cuenta_clientePa, $datosCuenta);
      }
      // guardar contacto
      if(isset($datosContacto)){

          $contador = $contador +1;
          $datosContacto['status'] =true;
          $datosContacto['eliminado'] = false;
          $datosContacto['auditoria'] = [ array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      ) ];
          $insertar_datos_del_contacto = $this->mongo_db->insert($this->tabla_contacto, $datosContacto);
          //Obtengo el ultimo id
          $res_del_contacto = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_contacto);
          

          $id_contactoCliente = $res_del_contacto[0]["_id"]->{'$id'};  

          $datosPersonalesContacto = array(

                                          'id_contacto' => $id_contactoCliente,

                                          'nombre_datos_personales' => $nombre_contacto,
                                          'status'=>true,
                                          'eliminado'=>false,
                                          'auditoria'=> [ array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      ) ]

                                          );
        
          //-Guardo los datos personales del contacto
          $insertar_datosPersonales_del_contacto = $this->mongo_db->insert($this->tabla_datosPersonales, $datosPersonalesContacto);
          
          //Obtengo el ultimo id
          $res_datosPersonales_del_contacto = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_datosPersonales);
          

          $contacto_cliente['id_datos_personales']  = $res_datosPersonales_del_contacto[0]["_id"]->{'$id'};
          $contacto_cliente['id_cliente'] = $id_cliente;   
          $contacto_cliente['status'] = true;   
          $contacto_cliente['eliminado'] = false;   
          $contacto_cliente['auditoria'] = [ array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      ) ];
          //Guardo los datos de contacto cliente
          $insertar_contacto_cliente = $this->mongo_db->insert('contacto_cliente', $contacto_cliente);//Obtengo el ultimo id
          $res_contacto_cliente = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get('contacto_cliente');
          $id_contacto_cliente  = $res_contacto_cliente[0]["_id"]->{'$id'};  
      }

      //guardo rep legal si aplica

      if (isset($datosRepLegal)){

          $contador = $contador +1;

          $datosRepLegal['status'] = true;   
          
          $datosRepLegal['eliminado'] = false;   
          
          $datosRepLegal['auditoria'] = [ array(
                                                        "cod_user" => $id_usuario,
                                                        "nomuser" => $this->session->userdata('nombre'),
                                                        "fecha" => $fecha,
                                                        "accion" => "Nuevo registro",
                                                        "operacion" => ""
                                                    ) ];
          $insertar_contacto_datos_personales = $this->mongo_db->insert($this->tabla_datosPersonales, $datosRepLegal);
          
          //Obtengo el ultimo id
          $res_contacto_datos_personalesRlegal = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_datosPersonales);

          $id_datosPersonalesRlegal  = $res_contacto_datos_personalesRlegal[0]["_id"]->{'$id'};  
          
          $repLegal_cliente_pagador['id_datos_personales'] = $id_datosPersonalesRlegal;
          
          $repLegal_cliente_pagador['id_cliente'] = $id_cliente;

          $repLegal_cliente_pagador['status'] = true;   
          
          $repLegal_cliente_pagador['eliminado'] = false;   
          
          $repLegal_cliente_pagador['auditoria'] = [ array(
                                                        "cod_user" => $id_usuario,
                                                        "nomuser" => $this->session->userdata('nombre'),
                                                        "fecha" => $fecha,
                                                        "accion" => "Nuevo registro",
                                                        "operacion" => ""
                                                    ) ];
          
          ///---------------------
          //Guardo en repLegal_cliente_pagador
          $insertar_repLegal_cliente_pagador = $this->mongo_db->insert('repLegal_cliente_pagador', $repLegal_cliente_pagador);
          
          //Obtengo el ultimo id
          $res_repLegal_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get('repLegal_cliente_pagador');
          
          $id_repLegal  = $res_repLegal_cliente_pagador[0]["_id"]->{'$id'}; 


      }


      // guardado datos domicilio cliente
      // En realidad no guarda, modifico tabla contacto
      $id_con =  new MongoDB\BSON\ObjectId($id_contacto);
      $mod_con = $this->mongo_db->where(array('_id'=>$id_con))->set($datosDomicilio)->update($this->tabla_contacto);

      //Auditoria...
      /*$data_auditoria = array(
                                      'cod_user'=>$id_usuario,
                                      'nom_user'=>$this->session->userdata('nombre'),
                                      'fecha'=>$fecha,
                                      'accion'=>'Modificar contacto',
                                      'operacion'=>''
                              );
      $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_con))->push('auditoria',$data_auditoria)->update($this->tabla_contacto);*/
      // guardando datos personales del cliente
      // En realidad no guarda, modifico datos personales del cliente
      $id_dp = new MongoDB\BSON\ObjectId($id_datos_personales);
      $mod_dp = $this->mongo_db->where(array('_id'=>$id_dp))->set($datosPersonales)->update($this->tabla_datosPersonales);


      //Auditoria...
      /*$data_auditoria2 = array(
                                      'cod_user'=>$id_usuario,
                                      'nom_user'=>$this->session->userdata('nombre'),
                                      'fecha'=>$fecha,
                                      'accion'=>'Modificar datos personales',
                                      'operacion'=>''
                              );
      $mod_auditoria2 = $this->mongo_db->where(array('_id'=>$id_dp))->push('auditoria',$data_auditoria2)->update($this->tabla_datosPersonales);*/
      //guardando datos cliente
      //En realidad no guarda, modifico tabla cliente
      $id_cli = new MongoDB\BSON\ObjectId($id_cliente);
      $mod_cp = $this->mongo_db->where(array('_id'=>$id_cli))->set($datosClientePa)->update($this->tabla_clientePagador);

      //Auditoria...
      /*$data_auditoria3 = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar cliente pagador',
                                        'operacion'=>''
                                );
      $mod_auditoria3 = $this->mongo_db->where(array('_id'=>$id_cli))->push('auditoria',$data_auditoria3)->update($this->tabla_clientePagador);*/
      //----------------------------------------------------------------------

     
      //print_r($contador);die;

      if ($contador >=2){

          $mensaje="<span>El Cliente se ha registrado exitosamente!</span>";

      }else{

          $mensaje="<span>Cliente Registrado parcialmente!</span>";

      }

      echo json_encode($mensaje); 
      //----------------------------------------------------------------------
      $contador = 0;
  }    
      /*//----------------------------------------------------------------------
      //--Como estaba antes de la migracion
      extract($datos);

      extract($id);

      // guardado datos domicilio cliente

      $this->db->where('id_contacto', $id_contacto);

      $this->db->update($this->tabla_contacto, $datosDomicilio);

      // guardando datos personales del cliente

      $this->db->where('id_datos_personales', $id_datos_personales);

      $this->db->update($this->tabla_datosPersonales, $datosPersonales);//print_r($this->db->last_query());die;

      //guardando datos cliente

      $this->db->where('id_cliente', $id_cliente);

      $this->db->update($this->tabla_clientePagador, $datosClientePa);//



      // guardo auditoria el cliente

      $auditoria=array(

              'tabla' => $this->tabla_clientePagador,

              'cod_reg' => $id_cliente,

              'usr_regins' => $this->session->userdata('id_usuario'),

              'fec_regins' => date('Y-m-d'),

          );



      $this->db->where('cod_reg', $id_cliente);

      $this->db->where('tabla', $this->tabla_clientePagador);

      $buscar = $this->db->get('auditoria');





      if (!$buscar->row()) {

        $this->db->insert('auditoria', $auditoria);

      }



      // guardo cuenta cliente

      if(isset($datosCuenta)){

        $contador = $contador +1;

      $datosCuenta['id_cliente']= $id_cliente;

      $this->db->insert($this->tabla_cuenta_clientePa, $datosCuenta);

      //

      $id_cuenta_cliente = $this->db->insert_id();

      $auditoriaCuenta=array(

              'tabla' => $this->tabla_cuenta_clientePa,

              'cod_reg' => $id_cuenta_cliente,

              'usr_regins' => $this->session->userdata('id_usuario'),

              'fec_regins' => date('Y-m-d'),

          );

      $this->db->insert('auditoria', $auditoriaCuenta);

      }

      // guardar contacto

      if(isset($datosContacto)){

        $contador = $contador +1;

      $this->db->insert($this->tabla_contacto, $datosContacto); //

      $id_contactoCliente= $this->db->insert_id();

      $datosPersonalesContacto = array(

                                    'id_contacto' => $id_contactoCliente,

                                    'nombre_datos_personales' => $nombre_contacto

                                    );



      $this->db->insert($this->tabla_datosPersonales, $datosPersonalesContacto);

      $id_datos_personalesContacto = $this->db->insert_id();

      $contacto_cliente['id_datos_personales'] = $id_datos_personalesContacto;

      $contacto_cliente['id_cliente'] = $id_cliente;

      $this->db->insert('contacto_cliente', $contacto_cliente);

      $id_contacto_cliente = $this->db->insert_id(); 

      $auditoriaContacto=array(

            'tabla' =>'contacto_cliente',

            'cod_reg' => $id_contacto_cliente,

            'usr_regins' => $this->session->userdata('id_usuario'),

            'fec_regins' => date('Y-m-d'),

        );

      $this->db->insert('auditoria', $auditoriaContacto);

      }

      //guardo rep legal si aplica

        if (isset($datosRepLegal)){

          $contador = $contador +1;

        $this->db->insert($this->tabla_datosPersonales, $datosRepLegal);

        $id_datosPersonalesRlegal =  $this->db->insert_id();

        $repLegal_cliente_pagador['id_datos_personales'] = $id_datosPersonalesRlegal;

        $repLegal_cliente_pagador['id_cliente'] = $id_cliente;

        $this->db->insert('repLegal_cliente_pagador', $repLegal_cliente_pagador);

        $id_repLegal = $this->db->insert_id();

          $auditoriaReple=array(

              'tabla' => $this->tabla_repLegal,

              'cod_reg' => $id_repLegal,

              'usr_regins' => $this->session->userdata('id_usuario'),

              'fec_regins' => date('Y-m-d'),

            );

            $this->db->insert('auditoria', $auditoriaReple);

        }





      //print_r($contador);die;

         if ($contador >=2){

      $mensaje="<span>El Cliente se ha registrado exitosamente!</span>";

      }else{

      $mensaje="<span>Cliente Registrado parcialmente!</span>";

      }

      echo json_encode($mensaje);
   }

      */
//--------------------------------------------------------------------------------



    public function guardarCarteraCliente($carteraCliente)

    {

             $this->db->insert('prospecto_vendedor', $carteraCliente);



                            $cod3       = $this->db->insert_id();

                            $datos_a    = array('tabla'      => 'prospecto_vendedor',

                                                'cod_reg'    => $cod3,

                                                'usr_regins' => $this->session->userdata('id_usuario'),

                                                'fec_regins' => date('Y-m-d'),

                                                );

                            $this->db->insert('auditoria', $datos_a); 

    }

   

   public function guardarRepLegalCliente($datos)

   {

    extract($datos);

    $this->db->insert($this->tabla_datosPersonales, $datosPersonales);

        $id_datosPersonalesRlegal =  $this->db->insert_id();

        $datosRepLegal['id_datos_personales'] = $id_datosPersonalesRlegal;

        $this->db->insert('repLegal_cliente_pagador', $datosRepLegal);

   //print_r($this->db->last_query());die;

        $id_repLegal = $this->db->insert_id();

          $auditoriaReple=array(

              'tabla' => $this->tabla_repLegal,

              'cod_reg' => $id_repLegal,

              'usr_regins' => $this->session->userdata('id_usuario'),

              'fec_regins' => date('Y-m-d'),

            );

            $this->db->insert('auditoria', $auditoriaReple);

        

      echo json_encode("<span>El Representante Legal se ha registrado exitosamente!</span>");

   }

    public function editarRepLegal($id_repLegal,$id_datos_personales, $datos)

    {

       extract($datos);

        //print_r($datos);die;

        $this->db->where('id_datos_personales', $id_datos_personales);

        $this->db->update($this->tabla_datosPersonales, $datosPersonales);

        $this->db->where('id_repLegal_cliente_pagador', $id_repLegal);

        $this->db->update($this->tabla_repLegal, $datosRepLegal);

        $datosAuditoria=array(

            'usr_regmod' => $this->session->userdata('id_usuario'),

            'fec_regmod' => date('Y-m-d'),

        );

        $this->db->where('cod_reg', $id_repLegal)->where('tabla', $this->tabla_repLegal);

        $this->db->update('auditoria', $datosAuditoria);

    }



    public function guardarContacto($contacto, $nombre_contacto, $id_cliente)

    {

      $contacto['id_codigo_postal'] = 1;

      $this->db->insert($this->tabla_contacto, $contacto);

      $id_contacto = $this->db->insert_id();

      $datosPersonales = array(

                              'nombre_datos_personales' => $nombre_contacto,

                              'id_contacto'             => $id_contacto

                              );

      $this->db->insert($this->tabla_datosPersonales, $datosPersonales);

      $id_datosPersonales = $this->db->insert_id();

      $contacto_cliente = array(

                                'id_datos_personales' => $id_datosPersonales,

                                'id_cliente'          => $id_cliente

      );

      $this->db->insert('contacto_cliente', $contacto_cliente);

      $id_contacto_cliente = $this->db->insert_id(); 

      $auditoria=array(

            'tabla' =>'contacto_cliente',

            'cod_reg' => $id_contacto_cliente,

            'usr_regins' => $this->session->userdata('id_usuario'),

            'fec_regins' => date('Y-m-d'),

        );

    $this->db->insert('auditoria', $auditoria);

    echo json_encode("<span>El Contacto se ha registrado exitosamente!</span>");

   

    }

    public function actualizar_contacto($id_contacto,$id_datos_personales, $datos){

      extract($datos);

      $this->db->where('id_datos_personales', $id_datos_personales);

      $this->db->update($this->tabla_datosPersonales,$datos_personales);

      $this->db->where('id_contacto', $id_contacto);

      $this->db->update($this->tabla_contacto, $contacto);

      $datosAuditoria=array(

          'usr_regmod' => $this->session->userdata('id_usuario'),

          'fec_regmod' => date('Y-m-d'),

      );

      $this->db->where('cod_reg', $id_contacto_cliente)->where('tabla', 'contacto_cliente');

      $this->db->update('auditoria', $datosAuditoria);//print_r($this->db->last_query());die;





    }


    /*
    *   consultaClientePorID
    */
    public function consultaClientePorID($id_cliente, $tipo_cliente){

      /*$this->db->where(array('id_cliente' => $id_cliente, 'tipo_cliente' => $tipo_cliente));

      $resultados = $this->db->get('prospecto_vendedor');

      return $resultados->row_array();*/
      //---------------------------------------------------
      //Migracion Mongo DB
      $res_cliente_pagador = $this->mongo_db->where(array('eliminado'=>false,'id_cliente'=>$id_cliente,'tipo_cliente'=>$tipo_cliente))->get($this->tabla_clientePagador);
      return $res_cliente_pagador;
      //---------------------------------------------------  
    }
    /***/

    /*
    * Eliminar individual...
    */
    public function eliminar ($id_cliente, $id_poyecto, $id){
        /*$this->db->delete($this->tabla_prospecto, array('id_cliente' => $id_cliente, 'id_proyecto' => $id_poyecto));
        $tabla = $this->tabla_prospecto;
        $this->db->delete('auditoria', array('cod_reg' => $id, 



                                            'tabla' => $tabla));
        // print_r($this->db->last_query());
        echo json_encode("<span>El Registro se ha eliminado exitosamente!</span>");*/
        //-------------------------------------------------------------------------
        //--Migracion Mongo DB

        //$id = new MongoDB\BSON\ObjectId($id);

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        $datos = array(
                                    'eliminado'=>true,
                );
        
        $eliminar = $this->mongo_db->where(array('id_cliente'=>$id))->set($datos)->update($this->tabla_prospecto);
        //var_dump($eliminar);die('');
        //--Auditoria
        
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar prospecto',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_prospecto); 
            
            echo json_encode("<span>El Registro se ha eliminado exitosamente!</span>");
        //-------------------------------------------------------------------------
        }
    }    
    /*
    *
    */

  /*
  * Buscar corrida
  */ 
  public function buscarClienteCorrida($id_cliente){

      /*$this->db->where('id_cliente_prospecto', $id_cliente);

      $result = $this->db->get('ventas');

      return $result->row();*/
      //--------------------------------------------------------
      //Migracion Mongo DB
      $res_corrida = $this->mongo_db->where(array('id_cliente_prospecto' => $id_cliente,'eliminado'=>false))->get('ventas');
      return $res_corrida;
      //--------------------------------------------------------
  }
  /*
  *
  */




    public function status($id, $status, $tabla){
        /*$datos=array(

            'status'=>$status,

            'fec_status'=> date('Y-m-d'),

            'usr_regmod' => $this->session->userdata('id_usuario'),

            'fec_regmod' => date('Y-m-d'),

        );

        $this->db->where('cod_reg', $id)->where('tabla', $tabla);

        $this->db->update('auditoria', $datos);*/

      //print_r($this->db->last_query());die;
      //-------------------------------------------------------------
      //--Migracion a Mongo DB
      //var_dump($id);die('');

      //$id = new MongoDB\BSON\ObjectId($id);
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
      $modificar = $this->mongo_db->where(array('id_cliente'=>$id))->set($datos)->update($tabla);
      //--Auditoria
      if($modificar){
          $data_auditoria = array(
                                      'cod_user'=>$id_usuario,
                                      'nom_user'=>$this->session->userdata('nombre'),
                                      'fecha'=>$fecha,
                                      'accion'=>'Modificar status',
                                      'operacion'=>''
                                  );
          $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($tabla); 
      } 
      //-------------------------------------------------------------  
    }
    /*
    * Status multiple
    */
    public function status_multiple($id, $status, $tabla){

        /*$clientes = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $clientes . ") AND tabla = '" . $tabla . "'");*/
        //---------------------------------------------------------------------------
        //--Migracion Mongo DB
        $arreglo_id = explode(' ',$id);
        foreach ($arreglo_id as $valor) {
            //$id = new MongoDB\BSON\ObjectId($valor);
            //var_dump($id);die('');
            $id = $valor;
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
            $datos = $data=array(
                                    'status'=>$status2,
            );
            $modificar = $this->mongo_db->where(array('id_cliente'=>$id))->set($datos)->update($tabla);
            //var_dump($modificar);die('');
            //--Auditoria
            if($modificar){
                $data_auditoria = "";
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status prospecto',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('id_cliente'=>$id))->push('auditoria',$data_auditoria)->update($tabla); 
            }
        }
        //---------------------------------------------------------------------------
    }
    /*
    *
    */


    public function eliminar_multiple($id){

        /*$eliminados=0;
        $noEliminados=0;

        foreach($id as $cliente){

            if($this->db->delete($this->tabla_prospecto, array('id_cliente' => $cliente))){

                $this->db->delete('auditoria', array('cod_reg' => $cliente, 'tabla' => $this->tabla_prospecto));

                $eliminados++;

            }else{

                $noEliminados++;

            }

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);*/
        //--------------------------------------------------------------------------------
        //--Migracion Mongo DB
        $eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        $vector_id = "";

        foreach($id as $cliente){
          $dependencia = $this->buscarClienteCorrida($cliente);
            if($dependencia){
                 $noEliminados++;
            }else{
                //------
                $datos = $data=array(
                                        'eliminado'=>true,
                );

                $eliminar = $this->mongo_db->where(array('id_cliente'=>$cliente))->set($datos)->update($this->tabla_prospecto);
                //--Auditoria
                if($eliminar){
                    $vector_id.= $cliente."*";
                    $eliminados++;
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar prospecto',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('id_cliente'=>$cliente))->push('auditoria',$data_auditoria)->update($this->tabla_prospecto);
                }else{
                    $noEliminados++;
                }
                //------
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);    
        //--------------------------------------------------------------------------------
    }

    public function consultaCarteraCliente($id)

    {
      $this->db->where('id_cliente', $id);
      $resultados = $this->db->get('cartera_clientes');
      return $resultados->row_array();    
    }


    public function getemail($email){

        /*$this->db->join('contacto ct', 'ct.id_contacto = cp.id_contacto');
        $this->db->join('prospecto_vendedor pv', 'pv.id_cliente = cp.id_cliente');
        $this->db->join('auditoria a', 'a.cod_reg = pv.id');
        $this->db->join('usuario u', 'u.id_usuario = a.usr_regins');
        $this->db->join('datos_personales dp', 'dp.id_usuario = u.id_usuario');
        $this->db->where('ct.correo_contacto', $email);
        $this->db->where('a.tabla', "prospecto_vendedor");
        $result = $this->db->get('cliente_pagador cp');
        return $result->row();*/
        //---------------------------------------------------------------------------
        //--MIGRACION MONGO DB
        $res_cliente_pagador = $this->mongo_db->where(array('eliminado'=>false))->get('cliente_pagador');

        $listado  = [];
        
        foreach ($res_cliente_pagador as $valor) {
            //--------------------------------------
            //--Consulto contacto
            $id_contacto = new MongoDB\BSON\ObjectId($valor["id_contacto"]);
            $res_contacto = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_contacto))->get('contacto');
            if(isset($res_contacto[0]["correo_contacto"])){ 
                if($res_contacto[0]["correo_contacto"]==$email){
                    //--Consulto el usuario que registro------
                    $id_usuario = $res_contacto[0]["auditoria"][0]->cod_user;
                    $id = new MongoDB\BSON\ObjectId($id_usuario);
                    $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
                    //--Consulto datos personales....
                    $res_datos_personales = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'id_usuario'=>$id_usuario))->get('datos_personales');
                    
                    $valor["nombre_datos_personales"] = $res_datos_personales[0]["nombre_datos_personales"];
                    
                    $valor["apellido_p_datos_personales"] = $res_datos_personales[0]["apellido_p_datos_personales"];
                    
                    $valor["apellido_m_datos_personales"] = $res_datos_personales[0]["apellido_m_datos_personales"];

                    $valor["correo_usuario"] =  $res_us[0]["correo_usuario"];
                    $listado [] = $valor;
                    //--------------------------------------
                }
            }    
            //--------------------------------------  
        }
        //---------------------------------------------------------------------------
        return $listado;
    }
    //Fin function getemail
    public function getemailActualizar($email,$id_contac){

        /*$this->db->join('contacto ct', 'ct.id_contacto = cp.id_contacto');
        $this->db->join('prospecto_vendedor pv', 'pv.id_cliente = cp.id_cliente');
        $this->db->join('auditoria a', 'a.cod_reg = pv.id');
        $this->db->join('usuario u', 'u.id_usuario = a.usr_regins');
        $this->db->join('datos_personales dp', 'dp.id_usuario = u.id_usuario');
        $this->db->where('ct.correo_contacto', $email);
        $this->db->where('a.tabla', "prospecto_vendedor");
        $result = $this->db->get('cliente_pagador cp');
        return $result->row();*/
        //---------------------------------------------------------------------------
        //--MIGRACION MONGO DB
        $res_cliente_pagador = $this->mongo_db->where(array('eliminado'=>false, "tipo_cliente"=> "PROSPECTO"))->get('cliente_pagador');
        $listado  = [];
        
        foreach ($res_cliente_pagador as $valor) {
            //--------------------------------------
            //--Consulto contacto
            $id_contacto = new MongoDB\BSON\ObjectId($valor["id_contacto"]);
            $res_contacto = $this->mongo_db->where(array('eliminado'=>false,'correo_contacto'=>$email))->get('contacto');
            /*var_dump($res_contacto);
            var_dump($id_contacto);
            die('');*/
            if(isset($res_contacto[0]["correo_contacto"])){ 
              $id_contactoDB = (string)$res_contacto[0]["_id"]->{'$id'};
                #Verifico si el id es diferente al id en cuestion...
                if($id_contactoDB!=$id_contac){
                    //--Consulto el usuario que registro------
                    $id_usuario = $res_contacto[0]["auditoria"][0]->cod_user;
                    $id = new MongoDB\BSON\ObjectId($id_usuario);
                    $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
                    //--Consulto datos personales....
                    $res_datos_personales = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'id_usuario'=>$id_usuario))->get('datos_personales');
                    
                    $valor["nombre_datos_personales"] = $res_datos_personales[0]["nombre_datos_personales"];
                    
                    $valor["apellido_p_datos_personales"] = $res_datos_personales[0]["apellido_p_datos_personales"];
                    
                    $valor["apellido_m_datos_personales"] = $res_datos_personales[0]["apellido_m_datos_personales"];

                    $valor["correo_usuario"] =  $res_us[0]["correo_usuario"];
                    $listado [] = $valor;
                    //--------------------------------------
                }
            }    
            //--------------------------------------  
        }
        //---------------------------------------------------------------------------
        return $listado;
    }
}

