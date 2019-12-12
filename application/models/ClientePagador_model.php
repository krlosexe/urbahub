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
        $res_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'tipo_cliente'=>'CLIENTE'))->get($this->tabla_clientePagador);
        foreach ($res_cliente_pagador as $clave => $valor) {
            $valores["id_cliente"] = (string)$valor["_id"]->{'$id'};
            
            (isset($valor["actividad_e_cliente"]))? $valores["actividad_e_cliente"] = $valor["actividad_e_cliente"]:$valores["actividad_e_cliente"] = "";
            (isset($valor["empresa_pertenece"]))? $valores["empresa_pertenece"] = $valor["empresa_pertenece"]:$valores["empresa_pertenece"] = "";
            
            if($valores["empresa_pertenece"] != ""){
                $id_empresa = new MongoDB\BSON\ObjectId($valores["empresa_pertenece"]);
                $empresa = $this->mongo_db->where(array('_id'=>$id_empresa))->get($this->tabla_clientePagador)[0];

                $id_datos_personales_empresa = new MongoDB\BSON\ObjectId($empresa["id_datos_personales"]);
                $res_datos_personales_empresa = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_datos_personales_empresa))->get('datos_personales')[0];
                $valores["name_empresa"] = $res_datos_personales_empresa["nombre_datos_personales"];
                
            }else{
                $valores["name_empresa"] = "";
            }
            
            $valores["rfc_img"] = $valor["rfc_img"];
            
            (isset($valor["imagenCliente"]))? $valores["imagenCliente"] = $valor["imagenCliente"]:$valores["imagenCliente"] = "";
            
            (isset($valor["pais_cliente"]))? $valores["pais_cliente"] = $valor["pais_cliente"]:$valores["pais_cliente"] = "";
            
            $valores["tipo_persona_cliente"] = $valor["tipo_persona_cliente"];
            $valores["dominio_fiscal_img"] = $valor["dominio_fiscal_img"];
            
            (isset($valor["acta_constitutiva"]))? $valores["acta_constitutiva"] = $valor["acta_constitutiva"]:$valores["acta_constitutiva"] = "";

            (isset($valor["acta_img"])) ? $valores["acta_img"] = $valor["acta_img"]: $valores["acta_img"] = "";
            (isset($valor["giro_mercantil"])) ? $valores["giro_mercantil"] = $valor["giro_mercantil"]:$valores["giro_mercantil"] = "";

            $valores["id_datos_personales"] = new MongoDB\BSON\ObjectId($valor["id_datos_personales"]);
            //-----------------------------------------------------------------------------------
            //--Consulto datos personales....
            $res_datos_personales = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$valores["id_datos_personales"]))->get('datos_personales');
            foreach ($res_datos_personales as $clave_dt => $valor_dt) {
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
            //-----------------------------------------------------------------------------------
            //--Consulto contactos
            $id_contacto = new MongoDB\BSON\ObjectId($valor["id_contacto"]);
            $res_contacto = $this->mongo_db->where(array("_id"=>$id_contacto))->get($this->tabla_contacto);
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
            foreach ($res_us_rg as $clave_us_reg => $valor_us_reg) {
                $valores["user_regis"] = $valor_us_reg["correo_usuario"];
                $valores["id_rol"] = (string)$valor_us_reg["id_rol"];
                $valores["correo_usuario"] = $valor_us_reg["correo_usuario"];
            }
            //$valores["fec_regins"] = $valor["auditoria"][0]->fecha;
            $vector_auditoria = reset($valor["auditoria"]);
            
            $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            
            $valores["status"] = $valor["status"];
            //-----------------------------------------------------------------------------------
            $listado[] = $valores;
        }  
        return $listado;
        //---------------------------------------------------------------------------------------
    }  
    /*
    *   Listado de clientes segun rf
    */
    public function consultarClientePagadorRfc($rfc){
        //-----------------------------------------------------------------------------------
        //--Consulto datos personales....
        $res_datos_personales = $this->mongo_db->where(array('eliminado'=>false,'rfc_datos_personales'=>$rfc))->get('datos_personales');
        $listado = [];
        $valores = [];

        foreach ($res_datos_personales as $clave_dt => $valor_dt) {
            
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
            //-------------------------------------------------------------------------------
            #Consulto cliente pagador
            //
            $res_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_datos_personales'=>$valores["id_datos_personales"]))->get($this->tabla_clientePagador);

            foreach ($res_cliente_pagador as $clave => $valor) {
                $valores["id_cliente"] = (string)$valor["_id"]->{'$id'};
                (isset($valor["actividad_e_cliente"]))? $valores["actividad_e_cliente"] = $valor["actividad_e_cliente"]:$valores["actividad_e_cliente"] = "";
                
                $valores["rfc_img"] = $valor["rfc_img"];
                
                (isset($valor["pais_cliente"]))? $valores["pais_cliente"] = $valor["pais_cliente"]:$valores["pais_cliente"] = "";
                
                $valores["tipo_persona_cliente"] = $valor["tipo_persona_cliente"];
                $valores["dominio_fiscal_img"] = $valor["dominio_fiscal_img"];
                
                (isset($valor["acta_constitutiva"]))? $valores["acta_constitutiva"] = $valor["acta_constitutiva"]:$valores["acta_constitutiva"] = "";

                (isset($valor["acta_img"])) ? $valores["acta_img"] = $valor["acta_img"]: $valores["acta_img"] = "";
                (isset($valor["giro_mercantil"])) ? $valores["giro_mercantil"] = $valor["giro_mercantil"]:$valores["giro_mercantil"] = "";

                $valores["id_datos_personales"] = new MongoDB\BSON\ObjectId($valor["id_datos_personales"]);
                //-----------------------------------------------------------------------------------
                //--Consulto contactos
                $id_contacto = new MongoDB\BSON\ObjectId($valor["id_contacto"]);
                $res_contacto = $this->mongo_db->where(array("_id"=>$id_contacto))->get($this->tabla_contacto);
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
                //--------------------------------------------------------------
                //-----------------------------------------------------------------------------------
                //--Consulto usuario
                $id_registro = $valor["auditoria"][0]->cod_user;
                $id = new MongoDB\BSON\ObjectId($id_registro);
                $res_us_rg = $this->mongo_db->where(array("_id"=>$id))->get("usuario");
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
                
                //--------------------------------------------------------------

            }//Fin cliente pagador   
        //-----------------------------------------------------------------------------------
            $listado[] = $valores;
        } //Fin datos personales
        //-------------------------------------------------------------------------------
        return $listado;
      
    } 
    //-----------------------------------------------------------------------------------
 
    /*
    *   Listar cuentas cliente
    */
    public function listarCuentasCliente($id_cliente){
        /*$this->db->where(array('a.tabla'    => $this->tabla_cuenta_clientePa,
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
        return $resultados->result();*/
        //-----------------------------------------------------------------------------------
        //Migracion Mongo db
        $res_cuenta = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_cliente'=>$id_cliente))->get($this->tabla_cuenta_clientePa);
        
        $listado = [];
        
        foreach ($res_cuenta as $valor) {
            $id_registro = $valor["auditoria"][0]->cod_user;
            $id = new MongoDB\BSON\ObjectId($id_registro);
            $res_us_rg = $this->mongo_db->where(array("_id"=>$id))->get("usuario");
            $valores = $valor;
            foreach ($res_us_rg as $clave_us_reg => $valor_us_reg) {
                $valores["user_regis"] = $valor_us_reg["correo_usuario"];
                $valores["id_rol"] = (string)$valor_us_reg["id_rol"];
                $valores["correo_usuario"] = $valor_us_reg["correo_usuario"];
            }
            //$valores["fec_regins"] = $valor["auditoria"][0]->fecha;
            $vector_auditoria = end($valor["auditoria"]);
            
            $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            $valores["id_cuenta_cliente"] = $valor["_id"]->{'$id'};
            $listado[] = $valores;
        }

        return $listado;
        //-----------------------------------------------------------------------------------
    }
  /*
  * Listar Representante legal
  */   
  public function listarRepLegal($id_cliente){
     /* $this->db->where(array('a.tabla'    => 'repLegal_cliente_pagador',
              'rp.id_cliente' => $id_cliente));
      $this->db->select('rp.*,a.tabla, a.cod_reg, a.status, a.fec_regins, u.correo_usuario,u.id_rol, dp.*');
      $this->db->from($this->tabla_repLegal . ' rp');
      $this->db->join('datos_personales dp', 'dp.id_datos_personales = rp.id_datos_personales');
      $this->db->join('auditoria a', 'rp.id_repLegal_cliente_pagador = a.cod_reg');
      $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
      
      $resultados = $this->db->get();
    //print_r($this->db->last_query());die;
      return $resultados->result();*/
    //-------------------------------------------------------------------------
    //Migracion Mongo db
        $res_rep_legal = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_cliente'=>$id_cliente))->get('repLegal_cliente_pagador');
        $listado = [];
        
        foreach ($res_rep_legal as $valor) {
            $valor["id_repLegal_cliente_pagador"] = (string)$valor["_id"]->{'$id'};
            $id_datos_personales = $valor["id_datos_personales"];
            $valores = $valor;
            $id_dp = new MongoDB\BSON\ObjectId($id_datos_personales);
           
            #Consulto datos personales....
            $res_datos_personales = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_dp))->get('datos_personales');
            foreach ($res_datos_personales as $clave_dt => $valor_dt) {
                $valores["id_datos_personales"] = (string)$valor_dt["_id"]->{'$id'};
                (isset($valor_dt["id_contacto"]))? $valores["id_contacto"] = (string)$valor_dt["id_contacto"]: $valores["id_contacto"] ="";
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
            #Consulto datos de usuario...
            $id_registro = $valor["auditoria"][0]->cod_user;
            $id_us = new MongoDB\BSON\ObjectId($id_registro);
            $res_us_rg = $this->mongo_db->where(array("_id"=>$id_us))->get("usuario");
            
            foreach ($res_us_rg as $clave_us_reg => $valor_us_reg) {
                $valores["user_regis"] = $valor_us_reg["correo_usuario"];
                $valores["id_rol"] = (string)$valor_us_reg["id_rol"];
                $valores["correo_usuario"] = $valor_us_reg["correo_usuario"];
            }
            
            //$valores["fec_regins"] = $valor["auditoria"][0]->fecha;
            $vector_auditoria = end($valor["auditoria"]);
            
            $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            //-----------------------------------------------------------------------------------
            $listado[] = $valores;
        }
        return $listado; 
    //-------------------------------------------------------------------------      
  }
  /*
  * Listar contacto
  */
  public function listarContacto($id_cliente){
    /*$this->db->where(array('a.tabla'    => 'contacto_cliente',
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
        return $resultados->result();*/
    //-------------------------------------------------------------------------
    //Migracion Mongo db
        $res_contacto_cliente = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_cliente'=>$id_cliente))->get('contacto_cliente');
        
        $listado = [];

        foreach ($res_contacto_cliente as $valor) {

            $valores = $valor;
            $valores['id_contacto_cliente'] = (string)$valor["_id"]->{'$id'};
            //Consulto el cliente pagador para obtener el id del cliente....
            $id_cliente = new MongoDB\BSON\ObjectId($valor["id_cliente"]);
            $res_cliente = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_cliente))->get('cliente_pagador');
            
            //Consulto datos personales
            $id_datos_personales =  new MongoDB\BSON\ObjectId($valores["id_datos_personales"]);
            
            $res_datos_personales = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_datos_personales))->get('datos_personales');

            $valores["nombre_datos_personales"] = $res_datos_personales[0]["nombre_datos_personales"];
            $id_contacto = new MongoDB\BSON\ObjectId($res_datos_personales[0]["id_contacto"]);
            $valores["id_contacto"] = $res_datos_personales[0]["id_contacto"];

            //Consulto contactos
            //$id_contacto = new MongoDB\BSON\ObjectId($res_cliente[0]["id_contacto"]);
            //$id_contacto = 
            $res_contacto = $this->mongo_db->where(array("_id"=>$id_contacto))->get($this->tabla_contacto);
            //$valores["id_contacto"] =$res_cliente[0]["id_contacto"];
            $valores["telefono_principal_contacto"]= $res_contacto[0]["telefono_principal_contacto"];
            
            (isset($res_contacto[0]["telefono_movil_contacto"])) ? $valores["telefono_movil_contacto"]= $res_contacto[0]["telefono_movil_contacto"]:$valores["telefono_movil_contacto"]="";
            
            $valores["correo_contacto"]= $res_contacto[0]["correo_contacto"];
            
            (isset($res_contacto[0]["correo_opcional_contacto"])) ? $valores["correo_opcional_contacto"] = $res_contacto[0]["correo_opcional_contacto"]:$valores["correo_opcional_contacto"] ="";
            
            (isset($res_contacto[0]["telefono_trabajo_contacto"]))? $valores["telefono_trabajo_contacto"] = $res_contacto[0]["telefono_trabajo_contacto"]:$valores["telefono_trabajo_contacto"] = "";
            (isset($res_contacto[0]["telefono_fax_contacto"])) ? $valores["telefono_fax_contacto"] = $res_contacto[0]["telefono_fax_contacto"]:$valores["telefono_fax_contacto"] ="";
            
            (isset($res_contacto[0]["telefono_casa_contacto"]))? $valores["telefono_casa_contacto"] = $res_contacto[0]["telefono_casa_contacto"]:$valores["telefono_casa_contacto"] ="";

            

            //Consulto datos de usuario
            $id_registro = $valor["auditoria"][0]->cod_user;
            $id = new MongoDB\BSON\ObjectId($id_registro);
            $res_us_rg = $this->mongo_db->where(array("_id"=>$id))->get("usuario");
            $valores["user_regis"] = $res_us_rg[0]["correo_usuario"];
            $valores["id_rol"] = (string)$res_us_rg[0]["id_rol"];
            $valores["correo_usuario"] = $res_us_rg[0]["correo_usuario"];
            $vector_auditoria = end($valor["auditoria"]);
            $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            $listado[] = $valores; 
        }
        return $listado;
        
    //-------------------------------------------------------------------------    
   }
   
   public function consultaPlaza ($tipo_plaza){
      $this->db->where('cod_plaza' , $tipo_plaza);
      $resultados = $this->db->get('plaza');
      return $resultados->result_array();
   }
   /*
   *    Validar email de contacto cliente pagador
   */
    public function validarCorreoClientePagador($correo_contacto){
        $res_contacto= $this->mongo_db->where(array('correo_contacto' => $correo_contacto))->get($this->tabla_contacto);
        if(count($res_contacto)>0){
            echo "<span>Ya existe un contacto con ese correo</span>";die('');
        }
    }
    /*
   *    Validar email de contacto cliente pagador editar
   */
    public function validarCorreoClientePagadorEditar($id_contacto,$correo_contacto){
        $res_contacto= $this->mongo_db->where(array('correo_contacto' => $correo_contacto))->get($this->tabla_contacto);
        if(count($res_contacto)>0){
            $id_contacto_bd = $res_contacto[0]["_id"]->{'$id'};
            //--
            if($id_contacto_bd!=$id_contacto){
                echo "<span>Ya existe un contacto con ese correo</span>";die('');
            }
            //--
        }
    }
   /*
   *  Registro cliente pagadpr
   */
   public function guardarClientePagador($datos){
      /*$contador = 0;
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
        echo json_encode($mensaje);*/
      //-----------------------------------------------------------------------------------------
      //Migracion Mongo db
      $fecha = new MongoDB\BSON\UTCDateTime();
      $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
      //
      $contador = 0;
      extract($datos);
      // guardado datos domicilio cliente -----------------------------------------------------
      $insertar_contacto = $this->mongo_db->insert($this->tabla_contacto, $datosDomicilio);
       //Obtengo el ultimo id
      $res_contacto = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_contacto);
    
      // guardando datos personales del cliente-------------------------------------------------
      $datosPersonales['id_contacto'] = $res_contacto[0]["_id"]->{'$id'};
      $insertar_datos_personales = $this->mongo_db->insert($this->tabla_datosPersonales, $datosPersonales);
      //Obtengo el ultimo id
      $res_datos_personales= $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_datosPersonales);

      // guardando datos cliente----------------------------------------------------------------
      $datosClientePa['id_datos_personales'] = $res_datos_personales[0]["_id"]->{'$id'};      
      $datosClientePa['id_contacto']= $datosPersonales['id_contacto'];  
      $insertar_datos_cliente_pagador = $this->mongo_db->insert($this->tabla_clientePagador, $datosClientePa);
      //Obtengo el ultimo id
      $res_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_clientePagador);
      
      // guardo cuenta cliente-------------------------------------------------------------------
      $id_cliente = $res_cliente_pagador[0]["_id"]->{'$id'}; 
      $datosCuenta['id_cliente']= $id_cliente;     
      if(isset($datosCuenta)){
            $contador = $contador +1;
            $insertar_datos_cliente_cuenta = $this->mongo_db->insert($this->tabla_cuenta_clientePa, $datosCuenta);
            //-Obtengo el ultimo id
            $res_cuenta_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_cuenta_clientePa);
            $id_cuenta_cliente = $res_cuenta_cliente_pagador[0]["_id"]->{'$id'};  
            //-------------------------------------------------------------------
      }

      //guardar datos del contacto - guardar contacto
        if(isset($datosContacto)){
            $contador = $contador +1;
            //--Guardo datos del contacto
            $insertar_datos_del_contacto = $this->mongo_db->insert($this->tabla_contacto, $datosContacto);
            //Obtengo el ultimo id
            $res_del_contacto = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_contacto);
            $id_contactoCliente = $res_del_contacto[0]["_id"]->{'$id'};     

            //---------------------------------------------------------------------------------------
            $datosPersonalesContacto = array(
                                            'id_contacto' => $id_contactoCliente,
                                            'nombre_datos_personales' => $nombre_contacto,
                                            'rfc_datos_personales' => "",
                                            'curp_datos_personales'=>"",
                                            'nacionalidad_datos_personales'=> "",
                                            'fecha_nac_datos_personales'=>"",
                                            'status'=>true,
                                            'eliminado'=>false,
                                            'auditoria' => [array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      )]
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
            //--------------------------------------------------------------------------------------------
            //Guardo los datos de contacto cliente
            $insertar_contacto_cliente = $this->mongo_db->insert('contacto_cliente', $contacto_cliente);//Obtengo el ultimo id
            $res_contacto_cliente = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get('contacto_cliente');
            $id_contacto_cliente  = $res_contacto_cliente[0]["_id"]->{'$id'};   
            //---------------------------------------------------------------------
            //var_dump($insertar_contacto_cliente);echo "</br>";
            //var_dump($res_contacto_cliente);
            //die('');
            //-------------------------------------------------------------------               
        }
        //guardo rep legal si aplica
        if (isset($datosRepLegal)){
            $contador = $contador +1;
            $insertar_contacto_datos_personales = $this->mongo_db->insert($this->tabla_datosPersonales, $datosRepLegal);
            //Obtengo el ultimo id
            $res_contacto_datos_personalesRlegal = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_datosPersonales);

            $id_datosPersonalesRlegal  = $res_contacto_datos_personalesRlegal[0]["_id"]->{'$id'};  
            $repLegal_cliente_pagador['id_datos_personales'] = $id_datosPersonalesRlegal;
            $repLegal_cliente_pagador['id_cliente'] = $id_cliente;
            
            //Guardo en repLegal_cliente_pagador
            $insertar_repLegal_cliente_pagador = $this->mongo_db->insert('repLegal_cliente_pagador', $repLegal_cliente_pagador);
            //Obtengo el ultimo id
            $res_repLegal_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get('repLegal_cliente_pagador');
            $id_repLegal  = $res_repLegal_cliente_pagador[0]["_id"]->{'$id'}; 
        }

        if ($contador >2){
            $mensaje="<span>El Cliente se ha registrado exitosamente!</span>";
        }else{
            $mensaje="<span>Cliente Registrado parcialmente!</span>";
        }
          echo json_encode($mensaje);  
      //-----------------------------------------------------------------------------------------  
    }
    /*
    *   Editar cliente pagador
    */
    public function editarClientePagador($id_cliente, $id_contacto, $id_datos_personales, $datos){
        /*extract($datos); 
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
        $this->db->update('auditoria', $datosAuditoria);*/
        //-------------------------------------------------------------------------------------
        //MIGRACION MONGO DB
        //-------------------------------------------------------------------------------------
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_con = new MongoDB\BSON\ObjectId($id_contacto);

        extract($datos); 
        //Modifico tabla contacto
        $mod_con = $this->mongo_db->where(array('_id'=>$id_con))->set($datosContacto)->update($this->tabla_contacto);
        //Auditoria...
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar contacto',
                                        'operacion'=>''
                                );
        $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_con))->push('auditoria',$data_auditoria)->update($this->tabla_contacto);
        //-------------------------------------------------
        //Modifico tabla datosPersonales
        $id_dp = new MongoDB\BSON\ObjectId($id_datos_personales);
        $mod_dp = $this->mongo_db->where(array('_id'=>$id_dp))->set($datosPersonales)->update($this->tabla_datosPersonales);
        //Auditoria...
        $data_auditoria2 = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar datos personales',
                                        'operacion'=>''
                                );
        $mod_auditoria2 = $this->mongo_db->where(array('_id'=>$id_dp))->push('auditoria',$data_auditoria2)->update($this->tabla_datosPersonales);
        //-------------------------------------------------
        //Modifico tabla 
        $id_cli = new MongoDB\BSON\ObjectId($id_cliente);
        $mod_dp = $this->mongo_db->where(array('_id'=>$id_cli))->set($datosClientePa)->update($this->tabla_clientePagador);
        //Auditoria...
        $data_auditoria3 = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar cliente pagador',
                                        'operacion'=>''
                                );
        $mod_auditoria3 = $this->mongo_db->where(array('_id'=>$id_cli))->push('auditoria',$data_auditoria3)->update($this->tabla_clientePagador);
        //-------------------------------------------------------------------------------------
    }
    /*
    *   Guardar Cuenta Cliente
    */
   public function guardarCuentaCliente($datos)
   {
    /*$this->db->insert($this->tabla_cuenta_clientePa, $datos);
    //print_r($this->db->last_query());die;
    $id_cuenta_cliente = $this->db->insert_id();
  
            $auditoria=array(
            'tabla' => $this->tabla_cuenta_clientePa,
            'cod_reg' => $id_cuenta_cliente,
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
    $this->db->insert('auditoria', $auditoria);
    echo json_encode("<span>La Cuenta se ha registrado exitosamente!</span>");*/
    //---------------------------------------------------------------------------
    //Mongo db
    //--Verifico que no exista una cuenta con esa clabe y con ese numero de cuenta
    $res_existe_clabe = $this->mongo_db->where(array('clabe_cuenta'=>$datos["clabe_cuenta"]))->get($this->tabla_cuenta_clientePa);
    if(count($res_existe_clabe)>0){
        echo "<span>La clabe ya se encuentra en uso</span>";die('');
    }
    //--Verifico que no exista una cuenta con esa numero y con ese numero de cuenta
    if($datos["numero_cuenta"]!=""){
    //-----------------------------------
        $res_existe_cuenta = $this->mongo_db->where(array('numero_cuenta'=>$datos["numero_cuenta"]))->get($this->tabla_cuenta_clientePa);
        if(count($res_existe_cuenta)>0){
            echo "<span>El número de cuenta ya se encuentra en uso</span>";die('');
        }
    //-----------------------------------    
    }
    
    //--
    $insertar_cuenta = $this->mongo_db->insert($this->tabla_cuenta_clientePa, $datos);
    echo json_encode("<span>La Cuenta se ha registrado exitosamente!</span>");
    //---------------------------------------------------------------------------
   }
   /*
   *    Actualizar Cuenta 
   */
   public function actualizarCuentaCliente($id_cuenta, $datos){
        /* $this->db->where('id_cuenta_cliente', $id_cuenta);
        $this->db->update($this->tabla_cuenta_clientePa, $datos);
        //print_r($this->db->last_query());
        $datosAuditoria=array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id_cuenta)->where('tabla', $this->tabla_cuenta_clientePa);
        $this->db->update('auditoria', $datosAuditoria);*/
        //---------------------------------------------------------------------------------
        //--Migracion MONGO DB
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_cta = new MongoDB\BSON\ObjectId($id_cuenta);
        //--Consulto si existe la cuenta
        $res_cta = $this->mongo_db->limit(1)->where(array('numero_cuenta'=>$datos['numero_cuenta'],'tipo_cuenta'=>$datos['tipo_cuenta'],'id_banco'=>$datos['id_banco'],'swift_cuenta'=>$datos['swift_cuenta'],'id_plaza'=>$datos['id_plaza'],'_id'=>$id_cta))->get($this->tabla_cuenta_clientePa);
        //Si el registro mantiene ĺos mismos campos
        if(count($res_cta)==0){
            //Actualizo los campos
            $mod_cta = $this->mongo_db->where(array('_id'=>$id_cta))->set($datos)->update($this->tabla_cuenta_clientePa);
            //Auditoria...
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar cuenta',
                                            'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_cta))->push('auditoria',$data_auditoria)->update($this->tabla_cuenta_clientePa);
             echo json_encode("<span>La cuenta se ha editado exitosamente!</span>");
        }else{//Si cambian sus campos
            if ($res_cta[0]["_id"]->{'$id'} == $id_cuenta) {
                //Actualizo los campos
                $mod_cta = $this->mongo_db->where(array('_id'=>$id_cta))->set($datos)->update($this->tabla_cuenta_clientePa);
                //Auditoria...
                $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Modificar cuenta',
                                                'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_cta))->push('auditoria',$data_auditoria)->update($this->tabla_cuenta_clientePa);
                //--
                echo json_encode("<span>La cuenta se ha editado exitosamente!</span>");
            }else {
                echo "<span>¡Ya se encuentra registrado una cuenta con las mismas características!</span>";
            }
        }
        //---------------------------------------------------------------------------------
    }
    /*
    *   Guardar repLegalCliente
    */
   public function guardarRepLegalCliente($datos)
   {
    /*extract($datos);
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
        
      echo json_encode("<span>El Representante Legal se ha registrado exitosamente!</span>");*/
      //------------------------------------------------------------------------------------
      //Mongo db

      extract($datos);
      //--Verificio si existe un replegal con ese nombre
      $res_rep_legal= $this->mongo_db->where(array('nombre_datos_personales' => $datosPersonales["nombre_datos_personales"],'apellido_p_datos_personales'=>$datosPersonales["apellido_p_datos_personales"],'apellido_m_datos_personales'=>$datosPersonales["apellido_m_datos_personales"]))->get($this->tabla_datosPersonales);
      if(count($res_rep_legal)>0){
        echo "<span>Ya existe un rep. legal con ese nombre</span>";die('');
      }
      //--Verificio si existe un replegal con ese rfc
      $res_rep_legal_rfc= $this->mongo_db->where(array('rfc_datos_personales' => $datosPersonales["rfc_datos_personales"]))->get($this->tabla_datosPersonales);
      if(count($res_rep_legal_rfc)>0){
        echo "<span>Ya existe un rep. legal con ese RFC</span>";die('');
      }
      //--Verificio si existe un replegal con ese curp
      $res_rep_legal_curp= $this->mongo_db->where(array('curp_datos_personales' => $datosPersonales["curp_datos_personales"]))->get($this->tabla_datosPersonales);
      if(count($res_rep_legal_curp)>0){
        echo "<span>Ya existe un rep. legal con ese curp</span>";die('');
      }
      //Inserto en datos personales...
      $insertar_datos_personales = $this->mongo_db->insert($this->tabla_datosPersonales, $datosPersonales);
      //Obtengo el ultimo id
      $res_datos_personales= $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_datosPersonales);
      // guardando datos cliente----------------------------------------------------------------
      $datosRepLegal['id_datos_personales'] = $res_datos_personales[0]["_id"]->{'$id'};     
      //Inserto en representante legal
      $insertar_repLegal_cliente_pagador = $this->mongo_db->insert('repLegal_cliente_pagador', $datosRepLegal);
      echo json_encode("<span>El Representante Legal se ha registrado exitosamente!</span>");
      //------------------------------------------------------------------------------------
   }
   /*
   *    Editar RepLegal
   */
    public function editarRepLegal($id_repLegal,$id_datos_personales, $datos)
    {
        /*extract($datos);
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
        $this->db->update('auditoria', $datosAuditoria);*/
        //---------------------------------------------------------------------------------
        //--Migracion MONGO DB
        extract($datos);

        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_rep = new MongoDB\BSON\ObjectId($id_repLegal);

        $id_dp = new MongoDB\BSON\ObjectId($id_datos_personales);
        //--Actualizo datos personales
            $mod_dp = $this->mongo_db->where(array('_id'=>$id_dp))->set($datosPersonales)->update($this->tabla_datosPersonales);
            //Auditoria...
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar datos personales',
                                            'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_dp))->push('auditoria',$data_auditoria)->update($this->tabla_datosPersonales);
        //--Actualizo representante legal
            $mod_repLegal = $this->mongo_db->where(array('_id'=>$id_rep))->set($datosRepLegal)->update($this->tabla_repLegal);
            //Auditoria...
            $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar representante legal',
                                            'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_rep))->push('auditoria',$data_auditoria)->update($this->tabla_repLegal);
        //---------------------------------------------------------------------------------

    }

    /*
    *   Guardar Contacto
    */
    public function guardarContacto($contacto, $nombre_contacto, $id_cliente){
      //Migracion Mongo DB 
      $fecha = new MongoDB\BSON\UTCDateTime();
      $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario')); 
      $contacto['id_codigo_postal'] = 1;
      $correo_contacto = $contacto['correo_contacto'];
      //var_dump($correo_contacto);die('');
      //$this->db->insert($this->tabla_contacto, $contacto);
      //$id_contacto = $this->db->insert_id();
      //--Verifico si cuenta con un contacto registrado con ese nombre
      $res_contacto= $this->mongo_db->where(array('correo_contacto' => $correo_contacto))->get($this->tabla_contacto);
      if(count($res_contacto)>0){
        echo "<span>Ya existe un contacto con ese correo</span>";die('');
      }
      //Guardo en contactos
      $insertar_contacto = $this->mongo_db->insert($this->tabla_contacto, $contacto);
      
      //Obtengo el ultimo id
      $res_contacto= $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_contacto);
      $id_contacto = $res_contacto[0]["_id"]->{'$id'};     

      $datosPersonales = array(
                              'nombre_datos_personales' => $nombre_contacto,
                              'id_contacto'             => $id_contacto,
                              'apellido_p_datos_personales'     => "",
                              'apellido_m_datos_personales'     => "",
                              'rfc_datos_personales'            => "",
                              'curp_datos_personales'           => "",
                              'nacionalidad_datos_personales'   => "",
                              'fecha_nac_datos_personales'      => "",
                              'genero_datos_personales' =>"",
                              'edo_civil_datos_personales'=>"",
                              'num_hijosdatos_personales'=>"",
                              'id_usuario'=>"",
                              'status'=>true,
                              'eliminado'=>false,
                              'auditoria' => [array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      )]
                              );
      //$this->db->insert($this->tabla_datosPersonales, $datosPersonales);
      //Guardo datos personales
      $insertar_datos_personales = $this->mongo_db->insert($this->tabla_datosPersonales, $datosPersonales);
      //Obtengo el ultimo id
      $res_datos_personales= $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_datosPersonales);
      // guardando datos contacto cliente
      $id_datosPersonales = $res_datos_personales[0]["_id"]->{'$id'};     
      //$id_datosPersonales = $this->db->insert_id();
      $contacto_cliente = array(
                                'id_datos_personales' => $id_datosPersonales,
                                'id_cliente'          => $id_cliente,
                                'status'=>true,
                                'eliminado'=>false,
                                'auditoria' => [array(
                                                              "cod_user" => $id_usuario,
                                                              "nomuser" => $this->session->userdata('nombre'),
                                                              "fecha" => $fecha,
                                                              "accion" => "Nuevo registro",
                                                              "operacion" => ""
                                                          )]
      );
      //$this->db->insert('contacto_cliente', $contacto_cliente);
      $insertar_datos_personales = $this->mongo_db->insert('contacto_cliente', $contacto_cliente);
      echo json_encode("<span>El Contacto se ha registrado exitosamente!</span>");
   
    }
    /*
    *   Actualizar contacto
    */
    public function actualizar_contacto($id_contacto,$id_datos_personales, $datos){
          /*extract($datos);
          $this->db->where('id_datos_personales', $id_datos_personales);
          $this->db->update($this->tabla_datosPersonales,$datos_personales);
          $this->db->where('id_contacto', $id_contacto);
          $this->db->update($this->tabla_contacto, $contacto);
          $datosAuditoria=array(
              'usr_regmod' => $this->session->userdata('id_usuario'),
              'fec_regmod' => date('Y-m-d'),
          );
          $this->db->where('cod_reg', $id_contacto_cliente)->where('tabla', 'contacto_cliente');
          $this->db->update('auditoria', $datosAuditoria);//print_r($this->db->last_query());die;*/
        //-------------------------------------------------------------------------------------
        //--Migracion Mongo DB

        extract($datos);
        //var_dump($contacto["correo_contacto"]);die('');
        $correo_contacto = $contacto["correo_contacto"];
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        //--Verifico si cuenta con un contacto registrado con ese nombre

        $id = new MongoDB\BSON\ObjectId($id_contacto);

        $res_contacto= $this->mongo_db->where_ne('_id', $id)->where(array('correo_contacto' => $correo_contacto))->get($this->tabla_contacto);
        if(count($res_contacto)>0){
            echo "<span>Ya existe un contacto con ese correo</span>";die('');
        }
        //------------------------------------------------------------------------------
        //Actualizo datos personales
        $id_dp = new MongoDB\BSON\ObjectId($id_datos_personales);

        $mod_datos_personales = $this->mongo_db->where(array('_id'=>$id_dp))->set($datos_personales)->update($this->tabla_datosPersonales);
        //Auditoria...
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar datos personales',
                                        'operacion'=>''
                                );
        $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_dp))->push('auditoria',$data_auditoria)->update($this->tabla_datosPersonales);
        //-------------------------------------------------------------------------------
        //Actualizo Contactos
        $id_c = new MongoDB\BSON\ObjectId($id_contacto);
        $mod_datos_contactos = $this->mongo_db->where(array('_id'=>$id_c))->set($contacto)->update($this->tabla_contacto);

        //var_dump($mod_datos_contactos);die('');

        //Auditoria...
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar contactos',
                                        'operacion'=>''
                                );

        $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_c))->push('auditoria',$data_auditoria)->update($this->tabla_contacto);    
        //-------------------------------------------------------------------------------
        echo json_encode("<span>El contacto se ha editado exitosamente!</span>");
        //--------------------------------------------------------------------------------
    }
    /*
    *
    */
     public function consultarClienteCotizacion($id){
              $resultados = $this->mongo_db->where(array('eliminado'=>false,'identificador_prospecto_cliente' => $id))->get("cotizacion");
        return $resultados;
    }

   public function eliminar ($id, $tipo){
      switch ($tipo) 
      {
        case 'cuenta':
                /*$this->db->delete($this->tabla_cuenta_clientePa, array('id_cuenta' => $id));
                $tabla = $this->tabla_cuenta_clientePa;
                    $this->db->delete('auditoria', array('cod_reg' => $id, 
                                                    'tabla' => $tabla));
               // print_r($this->db->last_query());
                echo json_encode("<span>El Registro se ha eliminado exitosamente!</span>");*/
                //-----------------------------------------------------------------------------    
                $id = new MongoDB\BSON\ObjectId($id);

                $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
                
                $fecha = new MongoDB\BSON\UTCDateTime();
                
                $datos = array(
                                'eliminado'=>true,
                                );

                $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_cuenta_clientePa);

                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar cuenta cliente',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_cuenta_clientePa); 
                    echo json_encode("<span>La cuenta se ha eliminado exitosamente!</span>"); 
                }    
                //----------------------------------------------------------------------------
          break;
          case 'contacto':
               /*$this->db->where('id_contacto_cliente', $id);
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
                echo json_encode("<span>El Registro se ha eliminado exitosamente!</span>");*/
                //-----------------------------------------------------------------------------
                //--Migracion Mongo DB
                //consulto contacto------
                $id_contacto_cliente = new MongoDB\BSON\ObjectId($id);
                
                $res_contacto_cliente= $this->mongo_db->where(array('_id' => $id_contacto_cliente))->get('contacto_cliente');
                                
                $id_datos_personales = $res_contacto_cliente[0]["id_datos_personales"];
                //-------------------------
                //consulto datos personales
                $id_dp = new MongoDB\BSON\ObjectId($id_datos_personales);

                $res_datos_personales= $this->mongo_db->where(array('_id' => $id_dp))->get('datos_personales');
                
                $id_contacto= $res_datos_personales[0]['id_contacto']; 

                $id_contac = new MongoDB\BSON\ObjectId($id_contacto);
                //--------------------------

                $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
                
                $fecha = new MongoDB\BSON\UTCDateTime();
                
                $datos = array(
                                'eliminado'=>true,
                                );

                //--------------------------------
                //Elimino contacto_cliente
                $eliminar = $this->mongo_db->where(array('_id'=>$id_contacto_cliente))->set($datos)->update('contacto_cliente');
                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar contacto cliente',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_contacto_cliente))->push('auditoria',$data_auditoria)->update('contacto_cliente');


                }   
                //--------------------------------------
                //Elimino datos personales
                $eliminar = $this->mongo_db->where(array('_id'=>$id_dp))->set($datos)->update($this->tabla_datosPersonales);
                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar datos personales',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_dp))->push('auditoria',$data_auditoria)->update($this->tabla_datosPersonales);
                } 
                //--------------------------------------
                //Elimino contactos
                $eliminar = $this->mongo_db->where(array('_id'=>$id_contac))->set($datos)->update($this->tabla_contacto);
                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar datos personales',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_contac))->push('auditoria',$data_auditoria)->update($this->tabla_contacto);
                }
                //----------------------------------------------------------------------------
                 echo json_encode("<span>El contacto se ha eliminado exitosamente!</span>");
                //-----------------------------------------------------------------------------

          break;
        case 'repLegal':
          /*$this->db->where('id_repLegal_cliente_pagador', $id);
          $consulta = $this->db->get($this->tabla_repLegal)->result_array();
          $id_datos_personales = $consulta[0]['id_datos_personales'];
          
          $this->db->delete($this->tabla_repLegal, array('id_repLegal_cliente_pagador' => $id));
          $this->db->delete($this->tabla_datosPersonales, array('id_datos_personales' => $id_datos_personales));
          $tabla = $this->tabla_cuenta_clientePa;
                 $this->db->delete('auditoria', array('cod_reg' => $id, 
                                                    'tabla' => $tabla));
               // print_r($this->db->last_query());
                echo json_encode("<span>El Registro se ha eliminado exitosamente!</span>");*/
                //-----------------------------------------------------------------------------
                //--Migracion Mongo DB
                //consulto Replegal------
                $id_repLegal = new MongoDB\BSON\ObjectId($id);
                
                $res_repLegal = $this->mongo_db->where(array('_id' => $id_repLegal))->get($this->tabla_repLegal);
                                
                $id_datos_personales = $res_repLegal[0]["id_datos_personales"];

                $id_dp = new MongoDB\BSON\ObjectId($id_datos_personales);               
                //--------------------------

                $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
                
                $fecha = new MongoDB\BSON\UTCDateTime();
                
                $datos = array(
                                'eliminado'=>true,
                                );
                //--------------------------------
                //Elimino Replegal
                $eliminar = $this->mongo_db->where(array('_id'=>$id_repLegal))->set($datos)->update($this->tabla_repLegal);
                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar ',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_repLegal))->push('auditoria',$data_auditoria)->update($this->tabla_repLegal);


                }   
                //--------------------------------------
                //Elimino datos personales
                $eliminar = $this->mongo_db->where(array('_id'=>$id_dp))->set($datos)->update($this->tabla_datosPersonales);
                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar datos personales',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_dp))->push('auditoria',$data_auditoria)->update($this->tabla_datosPersonales);
                }  

                //----------------------------------------------------------------------------
                 echo json_encode("<span>El Registro se ha eliminado exitosamente!</span>");
                //-----------------------------------------------------------------------------    
        break;
            case 'cliente':
                /*$this->db->where('id_cliente', $id);
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
                echo json_encode("<span>El Registro se ha eliminado exitosamente!</span>");*/
                //-----------------------------------------------------------------------------
                //--Migracion mongo db
                //Consulto en cliente
                $id_cli = new MongoDB\BSON\ObjectId($id);
                
                $res_cliente = $this->mongo_db->where(array('_id' => $id_cli))->get($this->tabla_clientePagador);
                //--Id datos personales                
                $id_datos_personales = $res_cliente[0]["id_datos_personales"];

                $id_dp = new MongoDB\BSON\ObjectId($id_datos_personales); 

                //--Id de contacto
                $id_contacto = $res_cliente[0]["id_contacto"];

                $id_contac = new MongoDB\BSON\ObjectId($id_contacto);
                //--------------------------------------------------
                $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
                
                $fecha = new MongoDB\BSON\UTCDateTime();
                
                $datos = array(
                                'eliminado'=>true,
                                );

                //-------------------------------------------------
                //Elimino cliente
                $eliminar = $this->mongo_db->where(array('_id'=>$id_cli))->set($datos)->update($this->tabla_clientePagador);
                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar Cliente',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_cli))->push('auditoria',$data_auditoria)->update($this->tabla_clientePagador);


                }   
                //--------------------------------------
                //Elimino datos personales
                $eliminar = $this->mongo_db->where(array('_id'=>$id_dp))->set($datos)->update($this->tabla_datosPersonales);
                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar datos personales',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_dp))->push('auditoria',$data_auditoria)->update($this->tabla_datosPersonales);
                }  
                //--------------------------------------
                //Elimino contactos
                $eliminar = $this->mongo_db->where(array('_id'=>$id_contac))->set($datos)->update($this->tabla_contacto);
                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar contactos',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_contac))->push('auditoria',$data_auditoria)->update($this->tabla_contacto);
                }   

                //--Mensaje
                echo json_encode("<span>El Registro se ha eliminado exitosamente!</span>");      
                //-----------------------------------------------------------------------------
            break;
          

        
      }
   }
    /*
    *   status
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
        //Migracion Mongo DB
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
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($tabla);
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
    *   Status multiple cuentas
    */
    public function status_multiple($id, $status, $tabla){
        /*$clientes = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $clientes . ") AND tabla = '" . $tabla . "'");*/
        //----------------------------------------------------------------------------------------
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
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($tabla);
            //var_dump($modificar);die('');
            //--Auditoria
            if($modificar){
                $data_auditoria = "";
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($tabla); 
            }
        }
        //----------------------------------------------------------------------------------------
    }
    /*
    *   Estatus multiple contactos
    */
    public function status_multiple_contacto($id, $status){
        //----------------------------------------------------------------------------------------
        //--Migracion Mongo DB
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();

        $arreglo_id = explode(' ',$id);
        
        foreach ($arreglo_id as $cliente) {
            //var_dump($id);die('');
            
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
            //----------------------------------------------------------------------------------
            //Modifico a cada tabla....
            $id_contacto_cliente = new MongoDB\BSON\ObjectId($cliente);
                
            $res_contacto_cliente= $this->mongo_db->where(array('_id' => $id_contacto_cliente))->get('contacto_cliente');
                            
            $id_datos_personales = $res_contacto_cliente[0]["id_datos_personales"];
            //-------------------------
            //consulto datos personales
            $id_dp = new MongoDB\BSON\ObjectId($id_datos_personales);

            $res_datos_personales= $this->mongo_db->where(array('_id' => $id_dp))->get('datos_personales');
            
            $id_contacto= $res_datos_personales[0]['id_contacto']; 

            $id_contac = new MongoDB\BSON\ObjectId($id_contacto);
            //----------------------------------------------------------------------------------
            //--Bloque de modificacion de cada tabla
             //--------------------------------
            //Modifico contacto_cliente
            $modificar = $this->mongo_db->where(array('_id'=>$id_contacto_cliente))->set($datos)->update('contacto_cliente');
            //--Auditoria
            if($modificar){
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_contacto_cliente))->push('auditoria',$data_auditoria)->update('contacto_cliente');


            }   
            //--------------------------------------
            //Modifico datos personales
            $modificar = $this->mongo_db->where(array('_id'=>$id_dp))->set($datos)->update($this->tabla_datosPersonales);
            //--Auditoria
            if($modificar){
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_dp))->push('auditoria',$data_auditoria)->update($this->tabla_datosPersonales);
            } 
            //--------------------------------------
            //Modifico contactos
            $modificar = $this->mongo_db->where(array('_id'=>$id_contac))->set($datos)->update($this->tabla_contacto);
                //--Auditoria
                if($modificar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Modificar status',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_contac))->push('auditoria',$data_auditoria)->update($this->tabla_contacto);
                }
            //----------------------------------------------------------------------------------
        }
        //----------------------------------------------------------------------------------------
    }
    /*
    *   Estatus multiple repLegal
    */
    public function status_multiple_repLegal($id, $status){
        //----------------------------------------------------------------------------------------
        //--Migracion Mongo DB
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();

        $arreglo_id = explode(' ',$id);
        
        foreach ($arreglo_id as $rep) {
            //var_dump($id);die('');
            
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
            //----------------------------------------------------------------------------------
            //Modifico a cada tabla....
            $id_rep = new MongoDB\BSON\ObjectId($rep);
                
            $res_rep= $this->mongo_db->where(array('_id' => $id_rep))->get($this->tabla_repLegal);
                            
            $id_datos_personales = $res_rep[0]["id_datos_personales"];
           
            $id_dp = new MongoDB\BSON\ObjectId($id_datos_personales);
            //----------------------------------------------------------------------------------
            //--Bloque de modificacion de cada tabla
             //--------------------------------
            $modificar = $this->mongo_db->where(array('_id'=>$id_rep))->set($datos)->update($this->tabla_repLegal);
            //--Auditoria
            if($modificar){
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_rep))->push('auditoria',$data_auditoria)->update($this->tabla_repLegal);


            }   
            //--------------------------------------
            //Modifico datos personales
            $modificar = $this->mongo_db->where(array('_id'=>$id_dp))->set($datos)->update($this->tabla_datosPersonales);
            //--Auditoria
            if($modificar){
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_dp))->push('auditoria',$data_auditoria)->update($this->tabla_datosPersonales);
            } 
            //--------------------------------------
        }
        //----------------------------------------------------------------------------------------
    }
    /*
    *   Eliminar multiple de cliente Pagador
    */
    public function eliminar_multiple($id){
        /*
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
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);*/
        //----------------------------------------------------------------------------------------
        //Migracion Mongo DB
        $eliminados=0;
        $noEliminados=0;
        $contador_eliminados = 0;

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id as $cliente){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            //-----------------------------------------------------------
            /*$prospecto = $this->buscarProspecto($cliente);
           
            if(count($prospecto)>0){
                $noEliminados++;
            }else{  */  
                $id_cli = new MongoDB\BSON\ObjectId($cliente);
                
                $res_cliente = $this->mongo_db->where(array('_id' => $id_cli))->get($this->tabla_clientePagador);
                //--Id datos personales                
                $id_datos_personales = $res_cliente[0]["id_datos_personales"];

                $id_dp = new MongoDB\BSON\ObjectId($id_datos_personales); 

                //--Id de contacto
                $id_contacto = $res_cliente[0]["id_contacto"];

                $id_contac = new MongoDB\BSON\ObjectId($id_contacto);
                //--------------------------------------------------
                $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
                
                $fecha = new MongoDB\BSON\UTCDateTime();
                
                $datos = array(
                                'eliminado'=>true,
                                );

                //-------------------------------------------------
                //Elimino cliente
                $eliminar = $this->mongo_db->where(array('_id'=>$id_cli))->set($datos)->update($this->tabla_clientePagador);
                //--Auditoria
                if($eliminar){
                    $contador_eliminados++;
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar Cliente',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_cli))->push('auditoria',$data_auditoria)->update($this->tabla_clientePagador);


                }   
                //--------------------------------------
                //Elimino datos personales
                $eliminar = $this->mongo_db->where(array('_id'=>$id_dp))->set($datos)->update($this->tabla_datosPersonales);
                //--Auditoria
                if($eliminar){
                    $contador_eliminados++;
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar datos personales',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_dp))->push('auditoria',$data_auditoria)->update($this->tabla_datosPersonales);
                }  
                //--------------------------------------
                //Elimino contactos
                $eliminar = $this->mongo_db->where(array('_id'=>$id_contac))->set($datos)->update($this->tabla_contacto);
                //--Auditoria
                if($eliminar){
                    $contador_eliminados++;
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar contactos',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_contac))->push('auditoria',$data_auditoria)->update($this->tabla_contacto);
                }   
                if($contador_eliminados==3){
                    $eliminados++;
                    $contador_eliminados=0;
                }else{
                    $noEliminados++;
                }   
                //----------------------------------------------------------------------------------    
           // }
        //--
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //----------------------------------------------------------------------------
    }
    /*
    *
    */
     /*
    *  Eliminar multiple  cuentas
    */
    public function eliminar_multiple_cta($id){
        //----------------------------------------------------------------------------------------
        //Migracion Mongo DB
        $eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id as $cuenta){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            $id = new MongoDB\BSON\ObjectId($cuenta);
            $datos = $data=array(
                                    'eliminado'=>true,
            );
            $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_cuenta_clientePa);
            //--Auditoria
            if($eliminar){
                $eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar cuenta cliente',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_cuenta_clientePa);
            }else{
                $noEliminados++;
            }   
            //----------------------------------------------------------------------------------    

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }
    /*
    *   Eliminar multiple contacto
    */
    public function eliminar_multiple_contacto($id){
       //----------------------------------------------------------------------------------------
        //Migracion Mongo DB
        $eliminados=0;
        $noEliminados=0;
        $contador_eliminados = 0;

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id as $cliente){

            $id_contacto_cliente = new MongoDB\BSON\ObjectId($cliente);
                
            $res_contacto_cliente= $this->mongo_db->where(array('_id' => $id_contacto_cliente))->get('contacto_cliente');
                            
            $id_datos_personales = $res_contacto_cliente[0]["id_datos_personales"];
            //-------------------------
            //consulto datos personales
            $id_dp = new MongoDB\BSON\ObjectId($id_datos_personales);

            $res_datos_personales= $this->mongo_db->where(array('_id' => $id_dp))->get('datos_personales');
            
            $id_contacto= $res_datos_personales[0]['id_contacto']; 

            $id_contac = new MongoDB\BSON\ObjectId($id_contacto);
            //--------------------------

            $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
            
            $fecha = new MongoDB\BSON\UTCDateTime();
            
            $datos = array(
                            'eliminado'=>true,
                            );

            //--------------------------------
            //Elimino contacto_cliente
            $eliminar = $this->mongo_db->where(array('_id'=>$id_contacto_cliente))->set($datos)->update('contacto_cliente');
            //--Auditoria
            if($eliminar){
                $contador_eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar contacto cliente',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_contacto_cliente))->push('auditoria',$data_auditoria)->update('contacto_cliente');


            }   
            //--------------------------------------
            //Elimino datos personales
            $eliminar = $this->mongo_db->where(array('_id'=>$id_dp))->set($datos)->update($this->tabla_datosPersonales);
            //--Auditoria
            if($eliminar){
                $contador_eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar datos personales',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_dp))->push('auditoria',$data_auditoria)->update($this->tabla_datosPersonales);
            } 
            //--------------------------------------
            //Elimino contactos
            $eliminar = $this->mongo_db->where(array('_id'=>$id_contac))->set($datos)->update($this->tabla_contacto);
                //--Auditoria
                if($eliminar){
                    $contador_eliminados++;
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar datos personales',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_contac))->push('auditoria',$data_auditoria)->update($this->tabla_contacto);
                }
            
            //--Auditoria
            if($contador_eliminados==3){
                $eliminados++;
                $contador_eliminados=0;
            }else{
                $noEliminados++;
            }   
            //----------------------------------------------------------------------------------    

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }
   /*
    *   Eliminar multiple replegal
    */
    public function eliminar_multiple_repLegal($id){
       //----------------------------------------------------------------------------------------
        //Migracion Mongo DB
        $eliminados=0;
        $noEliminados=0;
        $contador_eliminados = 0;

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();

        $datos = array(
                            'eliminado'=>true,
                            );
        
        foreach($id as $rep){

            $id_rep = new MongoDB\BSON\ObjectId($rep);
                
            $res_rep= $this->mongo_db->where(array('_id' => $id_rep))->get($this->tabla_repLegal);
                            
            $id_datos_personales = $res_rep[0]["id_datos_personales"];
            
            $id_dp = new MongoDB\BSON\ObjectId($id_datos_personales);
            
            //--------------------------------
            //Elimino contacto_cliente
            $eliminar = $this->mongo_db->where(array('_id'=>$id_rep))->set($datos)->update($this->tabla_repLegal);
            //--Auditoria
            if($eliminar){
                $contador_eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar repLegal',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_rep))->push('auditoria',$data_auditoria)->update($this->tabla_repLegal);
            }   
            //--------------------------------------
            //Elimino datos personales
            $eliminar = $this->mongo_db->where(array('_id'=>$id_dp))->set($datos)->update($this->tabla_datosPersonales);
            //--Auditoria
            if($eliminar){
                $contador_eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar datos personales',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_dp))->push('auditoria',$data_auditoria)->update($this->tabla_datosPersonales);
            } 
            //--------------------------------------
            if($contador_eliminados==2){
                $eliminados++;
                $contador_eliminados=0;
            }else{
                $noEliminados++;
            }   
            //----------------------------------------------------------------------------------    

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
    }
    public function consultaCarteraCliente($id)
    {
    
      $this->db->where('id_cliente', $id);
      $resultados = $this->db->get('cartera_clientes');
      return $resultados->row_array();       
        
    }
    /***/
    public function buscarProspecto($id){
        $res = $this->mongo_db->where(array('id_cliente'=>$id,"eliminado"=>false))->get('prospecto_vendedor');
        return $res;
    }
    /***/




}
