<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class ClientePagador_model extends CI_Model
{

    private $tabla_clientePagador = "cliente_pagador";
    private $tabla_lval       = "lval";
    private $tabla_cuenta_clientePa = "cuenta_cliente";
    private $tabla_repLegal     = "repLegal_cliente_pagador";
    private $tabla_datosPersonales  = "datos_personales";
    private $tabla_contacto     = "contacto";
   
    public function listarClientePagador()
    {
        /*$this->db->select('cp.id_cliente, cp.actividad_e_cliente, cp.rfc_img, cp.pais_cliente, cp.tipo_persona_cliente,cp.dominio_fiscal_img,cp.acta_constitutiva, acta_img,cp.giro_mercantil,lv4.descriplval as giro_merca_desc,dp.*, c.*, cop.*, lv1.descriplval as actividad_economica, lv2.descriplval as pais_origen, lv3.descriplval as pais_nacionalidad, a.*, u.correo_usuario, u.id_rol');*/
         //$this->db->join('codigo_postal cop', 'c.id_codigo_postal = cop.id_codigo_postal','left');
        //$this->db->join($this->tabla_lval . ' lv1', 'cp.actividad_e_cliente = lv1.codlval','left');
        //$this->db->join($this->tabla_lval . ' lv2', 'cp.pais_cliente = lv2.codlval','left');
        //$this->db->join($this->tabla_lval . ' lv3', 'dp.nacionalidad_datos_personales = lv3.codlval','left');
        //$this->db->join($this->tabla_lval . ' lv4', 'cp.giro_mercantil = lv4.codlval','left');
        //---Como funcionaba con mysql:
        /*$this->db->where('a.tabla' , $this->tabla_clientePagador);
        $this->db->select('cp.id_cliente, cp.actividad_e_cliente, cp.rfc_img, cp.pais_cliente, cp.tipo_persona_cliente,cp.dominio_fiscal_img,cp.acta_constitutiva, acta_img,cp.giro_mercantil,dp.*, c.*,a.*, u.correo_usuario, u.id_rol,c.id_codigo_postal,dp.nacionalidad_datos_personales');
        $this->db->from($this->tabla_clientePagador . ' cp');
        $this->db->join('datos_personales dp', 'dp.id_datos_personales = cp.id_datos_personales','left');
        $this->db->join($this->tabla_contacto . ' c', 'cp.id_contacto = c.id_contacto','left');
        $this->db->join('auditoria a', 'cp.id_cliente = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
         $resultados = $this->db->get();
        //print_r($this->db->last_query());die;
        return $resultados->result();*/

        //---------------------------------------------------------------------------------------
        //--Migracion Mongo DB
        //---------------------------------------------------------------------------------------
        $listado = [];
        $res_cliente_pagador = $this->mongo_db->where(array('eliminado'=>false))->get($this->tabla_clientePagador);
        foreach ($res_cliente_pagador as $clave => $valor) {
            $valores["id_cliente"] = (string)$valor["_id"]->{'$id'};
            $valores["actividad_e_cliente"] = $valor["actividad_e_cliente"];
            $valores["rfc_img"] = $valor["rfc_img"];
            $valores["pais_cliente"] = $valor["pais_cliente"];
            $valores["tipo_persona_cliente"] = $valor["tipo_persona_cliente"];
            $valores["dominio_fiscal_img"] = $valor["dominio_fiscal_img"];
            $valores["acta_constitutiva"] = $valor["acta_constitutiva"];
            $valores["acta_img"] = $valor["acta_img"];
            $valores["giro_mercantil"] = $valor["giro_mercantil"];
            $valores["id_datos_personales"] = new MongoDB\BSON\ObjectId($valor["id_datos_personales"]);
            //-----------------------------------------------------------------------------------
            //--Consulto datos personales....
            $res_datos_personales = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$valores["id_datos_personales"]))->get('datos_personales');
            foreach ($res_datos_personales as $clave_dt => $valor_dt) {
                $valores["id_datos_personales"] = (string)$valor_dt["_id"]->{'$id'};
                $valores["id_contacto"] = (string)$valor_dt["id_contacto"];
                $valores["nombre_datos_personales"] = $valor_dt["nombre_datos_personales"];
                $valores["apellido_p_datos_personales"] = $valor_dt["apellido_p_datos_personales"];
                $valores["apellido_m_datos_personales"] = $valor_dt["apellido_m_datos_personales"];
                $valores["curp_datos_personales"] = $valor_dt["curp_datos_personales"];
                #$valores["rfc_datos_personales"] = $valor_dt["rfc_datos_personales"];
                $valores["genero_datos_personales"] = $valor_dt["genero_datos_personales"];
                $valores["fecha_nac_datos_personales"] = $valor_dt["fecha_nac_datos_personales"];
                $valores["edo_civil_datos_personales"] = $valor_dt["edo_civil_datos_personales"];
                $valores["nacionalidad_datos_personales"] = $valor_dt["nacionalidad_datos_personales"];
            } 
            //-----------------------------------------------------------------------------------
            //--Consulto contactos
            $id_contacto = new MongoDB\BSON\ObjectId($valor["id_contacto"]);
            $res_contacto = $this->mongo_db->where(array("_id"=>$id_contacto))->get($this->tabla_contacto);
            foreach ($res_contacto as $clave_contacto => $valor_contacto) {
                $valores["id_codigo_postal"] = $valor_contacto["id_codigo_postal"];
                $valores["telefono_principal_contacto"] = $valor_contacto["telefono_principal_contacto"];
                $valores["telefono_movil_contacto"]=$valor_contacto["telefono_movil_contacto"];
                $valores["direccion_contacto"] = $valor_contacto["direccion_contacto"];
                $valores["calle_contacto"] = $valor_contacto["calle_contacto"];
                $valores["exterior_contacto"] = $valor_contacto["exterior_contacto"];
                $valores["interior_contacto"] = $valor_contacto["interior_contacto"];
                #$valores["status"] = $valor_contacto["status"];
            }
            //-----------------------------------------------------------------------------------
            //--Consulto usuario
            $id_registro = $valor["auditoria"][0]->cod_user;
            $id = new MongoDB\BSON\ObjectId($id_registro);
            $res_us_rg = $this->mongo_db->where(array("_id"=>$id))->get("usuario");
            foreach ($res_us_rg as $clave_us_reg => $valor_us_reg) {
                $valores["user_regis"] = $valor_us_reg["correo_usuario"];
                $valores["id_rol"] = (string)$valor_us_reg["id_rol"];
            }
            $valores["fec_regins"] = $valor["auditoria"][0]->fecha;
            $valores["status"] = $valor["status"];
            //-----------------------------------------------------------------------------------
            $listado[] = $valores;
        }  

        return $listado;
        //---------------------------------------------------------------------------------------
    }   
    public function listarCuentasCliente($id_cliente)
    {
        $this->db->where(array('a.tabla'    => $this->tabla_cuenta_clientePa,
              'cc.id_cliente' => $id_cliente));
        //$this->db->select('cc.*,lv.descriplval,b.id_banco,b.nombre_banco, a.tabla, a.cod_reg, a.status, a.fec_regins, u.correo_usuario, u.id_rol, cc.id_plaza, p.nombre_plaza');
        $this->db->select('cc.*,a.tabla, a.cod_reg, a.status, a.fec_regins, u.correo_usuario, u.id_rol');
        $this->db->from($this->tabla_cuenta_clientePa . ' cc');
        $this->db->join('auditoria a', 'cc.id_cuenta_cliente = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        //$this->db->join($this->tabla_lval . ' lv', 'cc.tipo_cuenta = lv.codlval','left');
        //$this->db->join('banco b', 'b.id_banco = cc.id_banco','left');
        // $this->db->join('plaza p', 'p.id_plaza = cc.id_plaza','left');
        $resultados = $this->db->get();
        //print_r($this->db->last_query());die;
        return $resultados->result();
    }   
  public function listarRepLegal($id_cliente)
    {
     $this->db->where(array('a.tabla'    => 'repLegal_cliente_pagador',
              'rp.id_cliente' => $id_cliente));
        $this->db->select('rp.*,a.tabla, a.cod_reg, a.status, a.fec_regins, u.correo_usuario,u.id_rol, dp.*');
        $this->db->from($this->tabla_repLegal . ' rp');
        $this->db->join('datos_personales dp', 'dp.id_datos_personales = rp.id_datos_personales');
        $this->db->join('auditoria a', 'rp.id_repLegal_cliente_pagador = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        
        $resultados = $this->db->get();
      //print_r($this->db->last_query());die;
        return $resultados->result();
    } 
   public function listarContacto($id_cliente){
    $this->db->where(array('a.tabla'    => 'contacto_cliente',
                           'c.id_cliente' => $id_cliente));
        $this->db->select('c.id_contacto_cliente,co.id_contacto, co.telefono_principal_contacto, co.telefono_movil_contacto,co.correo_contacto , co.correo_opcional_contacto,
co.telefono_trabajo_contacto, co.telefono_fax_contacto, co.telefono_casa_contacto, c.id_datos_personales, dp.nombre_datos_personales, c.id_cliente, a.*, u.id_rol, u.correo_usuario');
        $this->db->from('contacto_cliente' . ' c');
        $this->db->join('datos_personales dp', 'dp.id_datos_personales = c.id_datos_personales');
        $this->db->join('contacto co', 'co.id_contacto = dp.id_contacto');
        $this->db->join('auditoria a', 'a.cod_reg = c.id_contacto_cliente');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $resultados = $this->db->get();
      //print_r($this->db->last_query());die;
        return $resultados->result();
   }
   public function armarSelect($tipo) 
   {
        switch ($tipo) 
        {
            case 'banco':
                $resultados = $this->db->get($tipo);
                break;
            case 'tipoCuenta':
            $this->db->where('tipolval', 'TIPOCUENTA');
            $resultados = $this->db->get($this->tabla_lval);
                break;
            case 'actividadEconomica':
            $this->db->order_by("descriplval", "asc");
            $this->db->where('tipolval', 'OCUPACION');
            $resultados = $this->db->get($this->tabla_lval);
                break;
            case 'plaza':
                $resultados = $this->db->get($tipo);
                break;
            case 'giro':
            $this->db->order_by("descriplval", "asc");
            $this->db->where('tipolval', 'GIROMERCA');
                $resultados = $this->db->get($this->tabla_lval);
            break;
            
        }
        return $resultados->result();
   }

   public function consultaPlaza ($tipo_plaza){
      $this->db->where('cod_plaza' , $tipo_plaza);
      $resultados = $this->db->get('plaza');
      return $resultados->result_array();
   }
   public function guardarClientePagador($datos)
   {
    $contador = 0;
      extract($datos);
      // guardado datos domicilio cliente
    $this->db->insert($this->tabla_contacto, $datosDomicilio);
    $id_contacto = $this->db->insert_id();
    // guardando datos personales del cliente
    $datosPersonales['id_contacto'] = $id_contacto;
    $this->db->insert($this->tabla_datosPersonales, $datosPersonales);
    $id_datosPersonales = $this->db->insert_id();
    $datosClientePa['id_datos_personales'] = $id_datosPersonales;
    $datosClientePa['id_contacto']= $id_contacto;
    //guardando datos cliente
    $this->db->insert($this->tabla_clientePagador, $datosClientePa);//print_r($this->db->last_query());die;
    $id_cliente= $this->db->insert_id();
    // guardo auditoria el cliente
    $auditoria=array(
              'tabla' => $this->tabla_clientePagador,
              'cod_reg' => $id_cliente,
              'usr_regins' => $this->session->userdata('id_usuario'),
              'fec_regins' => date('Y-m-d'),
          );
      $this->db->insert('auditoria', $auditoria);
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

         if ($contador >2){
      $mensaje="<span>El Cliente se ha registrado exitosamente!</span>";
      }else{
      $mensaje="<span>Cliente Registrado parcialmente!</span>";
      }
      echo json_encode($mensaje);

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
   public function actualizarCuentaCliente($id_cuenta, $datos)
    {
       
        $this->db->where('id_cuenta_cliente', $id_cuenta);
        $this->db->update($this->tabla_cuenta_clientePa, $datos);
        //print_r($this->db->last_query());die();
        $datosAuditoria=array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id_cuenta)->where('tabla', $this->tabla_cuenta_clientePa);
        $this->db->update('auditoria', $datosAuditoria);
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
      $this->db->update('auditoria', $datosAuditoria);
      ///print_r($this->db->last_query());die;
      //echo json_encode("<span>El Contacto se ha actualizado exitosamente!</span>");

    }


   public function eliminar ($id, $tipo)
   {
      switch ($tipo) 
      {
        case 'cuenta':
          $this->db->delete($this->tabla_cuenta_clientePa, array('id_cuenta' => $id));
          $tabla = $this->tabla_cuenta_clientePa;
                    $this->db->delete('auditoria', array('cod_reg' => $id, 
                                                    'tabla' => $tabla));
               // print_r($this->db->last_query());
                echo json_encode("<span>El Registro se ha eliminado exitosamente!</span>");
          break;
          case 'contacto':
          $this->db->where('id_contacto_cliente', $id);
           $consulta = $this->db->get('contacto_cliente')->result_array(); 
           $id_datos_personales = $consulta[0]['id_datos_personales'];
          $this->db->where('id_datos_personales', $id_datos_personales); 
          $consuta2 = $this->db->get('datos_personales')->result_array();  
          $id_contacto= $consuta2[0]['id_contacto']; 
          $this->db->delete('contacto_cliente', array('id_contacto_cliente' => $id));
          $this->db->delete($this->tabla_datosPersonales, array('id_datos_personales' => $id_datos_personales));
          $this->db->delete($this->tabla_contacto, array('id_contacto' => $id_contacto));
          $tabla = $this->tabla_cuenta_clientePa;
                 $this->db->delete('auditoria', array('cod_reg' => $id, 
                                                    'tabla' => $tabla));
               // print_r($this->db->last_query());
                echo json_encode("<span>El Registro se ha eliminado exitosamente!</span>");
          break;
        case 'repLegal':
          $this->db->where('id_repLegal_cliente_pagador', $id);
          $consulta = $this->db->get($this->tabla_repLegal)->result_array();
          $id_datos_personales = $consulta[0]['id_datos_personales'];
          
          $this->db->delete($this->tabla_repLegal, array('id_repLegal_cliente_pagador' => $id));
          $this->db->delete($this->tabla_datosPersonales, array('id_datos_personales' => $id_datos_personales));
          $tabla = $this->tabla_cuenta_clientePa;
                 $this->db->delete('auditoria', array('cod_reg' => $id, 
                                                    'tabla' => $tabla));
               // print_r($this->db->last_query());
                echo json_encode("<span>El Registro se ha eliminado exitosamente!</span>");
        break;
            case 'cliente':
                $this->db->where('id_cliente', $id);
                $consulta = $this->db->get($this->tabla_clientePagador)->result_array();
                $id_datos_personales = $consulta[0]['id_datos_personales'];
                $id_contacto = $consulta[0]['id_contacto'];
                $this->db->delete($this->tabla_clientePagador, array('id_cliente' => $id));
                $this->db->delete($this->tabla_datosPersonales, array('id_datos_personales' => $id_datos_personales));
                $this->db->delete($this->tabla_contacto, array('id_contacto' => $id_contacto));
                $tabla = $this->tabla_cuenta_clientePa;
                 $this->db->delete('auditoria', array('cod_reg' => $id, 
                                                    'tabla' => $tabla));
               // print_r($this->db->last_query());
                echo json_encode("<span>El Registro se ha eliminado exitosamente!</span>");
            break;
          

        
      }
   }
    public function status($id, $status, $tabla)
    {
        $datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $tabla);
        $this->db->update('auditoria', $datos);
      //print_r($this->db->last_query());die;
    }
     public function status_multiple($id, $status, $tabla)
    {
        $clientes = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $clientes . ") AND tabla = '" . $tabla . "'");
    }

    public function eliminar_multiple($id)
    {
        $eliminados=0;
        $noEliminados=0;
        foreach($id as $cliente)
        {
            if($this->db->delete($this->tabla_clientePagador, array('id_cliente' => $cliente))){
                $this->db->delete('auditoria', array('cod_reg' => $cliente, 'tabla' => $this->tabla_clientePagador));
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }
    public function consultaCarteraCliente($id)
    {
    
      $this->db->where('id_cliente', $id);
      $resultados = $this->db->get('cartera_clientes');
      return $resultados->row_array();       
        
    }




}
