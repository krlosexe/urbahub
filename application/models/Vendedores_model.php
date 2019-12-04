<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedores_model extends CI_Model {

    private $tabla_vendedor                 = "vendedores";
    private $tabla_inmobliarias_vendedores  = "vendedores_inmobiliarias";
    private $tabla_cartera_clientes         = "prospecto_vendedor";
    private $tabla_inmobiliarias            = "inmobiliarias";

    /*
    *   getusuariosvendedores
    */
    public function getusuariosvendedores(){
        /*$this->db->select('usuario.*, datos_personales.nombre_datos_personales as nombre_usuario, datos_personales.apellido_p_datos_personales as apellido_user');
        $this->db->join('auditoria', 'usuario.id_usuario = auditoria.cod_reg');
        $this->db->join('datos_personales', 'usuario.id_usuario = datos_personales.id_usuario');
        $this->db->where('auditoria.status', 1);
        $this->db->where('auditoria.tabla', "usuario");
        $this->db->from('usuario');
        $this->db->order_by('datos_personales.nombre_datos_personales', 'asc');
        $result = $this->db->get();
        return $result->result();*/
        //----------------------------------------------------------------------------------
        //--Migracion Mongo DB
        $res_usuario = $this->mongo_db->where(array('eliminado'=>false,'status'=>true))->get("usuario");
        $listado = [];
        foreach ($res_usuario as $clave => $valor) {
            $valores = $valor;
            $valores["id_usuario"] = (string)$valor["_id"]->{'$id'};
            //var_dump($valor["_id"]->{'$id'}); echo "<br>";
            /*if($valores["id_usuario"] =="5cfeb652e31dd939fd176d62"){

            }*/
            $id_us =  new MongoDB\BSON\ObjectId($valores["id_usuario"]);
            //var_dump($id_us); echo "<br>";

            $res_dt = $this->mongo_db->where(array('id_usuario'=>$id_us))->get("datos_personales");
            //var_dump($res_dt); echo "<br>";
            if(isset($res_dt[0]["_id"]->{'$id'})){
                //----------------------------------------
                $valores["id_datos_personales"] = (string)$res_dt[0]["_id"]->{'$id'};
                //$id_dt =  new MongoDB\BSON\ObjectId($res_dt[0]["id_datos_personales"]);
                $valores["nombre_usuario"] = $res_dt[0]["nombre_datos_personales"];
                $valores["apellido_user"] = $res_dt[0]["apellido_p_datos_personales"];
                //-----------------------------------------------------------------------------------
                //--Consulto usuario
                $id_registro = $valor["auditoria"][0]->cod_user;
                $id = new MongoDB\BSON\ObjectId($id_registro);
                $res_us_rg = $this->mongo_db->where(array("_id"=>$id))->get("usuario");

                $valores["user_regis"] = $res_us_rg[0]["correo_usuario"];
                $valores["id_rol"] = (string)$res_us_rg[0]["id_rol"];
                $valores["correo_usuario"] = $res_us_rg[0]["correo_usuario"];

                //$valores["fec_regins"] = $valor["auditoria"][0]->fecha;
                $vector_auditoria = end($valor["auditoria"]);

                $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();

                $valores["status"] = $valor["status"];
                //-----------------------------------------------------------------------------------
                $listado[]=$valores;
                //----------------------------------------
            }

        }
        //var_dump($listado);die('xxx');
        return $listado;
        //----------------------------------------------------------------------------------

    }


    public function getusuariosvendedor($id)
    {
        /*$this->db->select('usuario.*, datos_personales.nombre_datos_personales as nombre_usuario, datos_personales.apellido_p_datos_personales as apellido_user');
        $this->db->join('auditoria', 'usuario.id_usuario = auditoria.cod_reg');
        $this->db->join('datos_personales', 'usuario.id_usuario = datos_personales.id_usuario');
        $this->db->where('auditoria.status', 1);
        $this->db->where('auditoria.tabla', "usuario");
        $this->db->where('usuario.id_usuario', $id);
        $this->db->from('usuario');
        $result = $this->db->get();
        return $result->result();*/
        //----------------------------------------------------------------------------------
        //--Migracion Mongo DB
        $id_us =  new MongoDB\BSON\ObjectId($id);
        $res_usuario = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'_id'=>$id_us))->get("usuario");
        $listado = [];
        foreach ($res_usuario as $clave => $valor) {
            $valores = $valor;
            if(isset($valor["id_datos_personales"])){
                //---
                $id_dt =  new MongoDB\BSON\ObjectId($valor["id_datos_personales"]);
                $res_dt = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'_id'=>$id_dt))->get("usuario");
                $valores["nombre_usuario"] = $res_dt[0]["nombre_datos_personales"];
                $valores["apellido_user"] = $res_dt[0]["apellido_p_datos_personales"];
                //------------------------------------------------------------------------
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
                //-----------------------------------------------------------------------
                $listado[]=$valores;
                //---
            }
        }
        return $listado;
        //----------------------------------------------------------------------------------
    }

    public function getvendedor($id)
    {
       /*$this->db->where('id_usuario', $id);
       $result = $this->db->get('vendedores');
       return $result->row();*/
       //-------------------------------------------------
       #Migracion Mongo db junio 2019
       //$id_us =  new MongoDB\BSON\ObjectId($id);
       $res_us = $this->mongo_db->where(array('eliminado'=>false,'id_usuario'=>$id))->get($this->tabla_vendedor);
       return $res_us;
       //-------------------------------------------------
    }
    /*
    *   Registrar Vendedor
    */
    public function registrar_vendedor($data, $proyectos, $inmobiliarias, $proyectos_clientes, $clientes){
    //----------------------------------------------------------------------------------
    //--Migracion Mongo DB
        $fecha = new MongoDB\BSON\UTCDateTime();
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $insertar_vendedor = $this->mongo_db->insert($this->tabla_vendedor, $data);
        if($insertar_vendedor){
            #Obtengo el ultimo id de la insercion
            $res_vendedor = $this->mongo_db->order_by(array('_id' => 'DESC'))->limit(1)->get($this->tabla_vendedor);
            $vendedor = $res_vendedor[0]["_id"]->{'$id'};
            if (isset($proyectos_clientes)){
                $cont2 = 0;
                foreach($clientes as $valor_cliente){
                    $array2 = array(
                        'id_vendedor'     => $vendedor,
                        'id_proyecto'     => "",
                        'id_cliente'      => $valor_cliente,
                        'tipo_cliente'    => 'CLIENTE',
                        'status'=> true,
                        'eliminado'=> false,
                        'auditoria' => [array(
                                                          "cod_user" => $id_usuario,
                                                          "nomuser" => $this->session->userdata('nombre'),
                                                          "fecha" => $fecha,
                                                          "accion" => "Nuevo registro",
                                                          "operacion" => ""
                                                      )]
                    );
                    $insertar_cartera_clientes = $this->mongo_db->insert($this->tabla_cartera_clientes, $array2);
                    $cont2 = $cont2 + 1;
                }
            }
            return true;
        }else{
            return false;
        }
    }
    //----------------------------------------------------------------------------------
        /*$insert = $this->db->insert($this->tabla_vendedor, $data);
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
                        'tipo_cliente'    => 'CLIENTE'
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
    }*/

    /*
    *   Actualizar vendedor
    */
    public function actualizar_vendedor($data, $id, $clientes){
        //-----------------------------------------------------------------------------------------
        //Migracion Mongo DB
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

       //Se inserta en prospecto si no existe el registro de la cartera
        /*if (isset($proyectos_clientes)){
            $cont2 = 0;
            foreach($proyectos_clientes as $proyecto_clientes){
                if ($proyecto_clientes != ""){*/
                foreach ($clientes as $clave_clientes => $valor_clientes) {
                    //Consulto si existe en cartera de clientes
                    $res_cartera = $this->mongo_db->where(array('id_vendedor'=>$id,'id_cliente'=>$valor_clientes))->get($this->tabla_cartera_clientes);
                    if (count($res_cartera) == 0){

                        $array3 = array(
                                'id_vendedor'     => $id,
                                'id_proyecto'     => '',
                                'id_cliente'      => $valor_clientes,
                                'tipo_cliente'    => 'CLIENTE',
                                'status'=> true,
                                'eliminado'=> false,
                                'auditoria' => [array(
                                                                  "cod_user" => $id_usuario,
                                                                  "nomuser" => $this->session->userdata('nombre'),
                                                                  "fecha" => $fecha,
                                                                  "accion" => "Nuevo registro",
                                                                  "operacion" => ""
                                                              )]
                        );
                        $insertar_cartera = $this->mongo_db->insert($this->tabla_cartera_clientes, $array3);
                    }
                }    
                /*}
                $cont2 = $cont2 + 1;
            }
        }*/

        //Modifico tabla vendedor
        $id_vendedor = new MongoDB\BSON\ObjectId($id);

        $mod_vendedor = $this->mongo_db->where(array('_id'=>$id_vendedor))->set($data)->update($this->tabla_vendedor);
        if($mod_vendedor){
            return true;
        }else{
            return false;
        }
    }
    //-----------------------------------------------------------------------------------------
    //Programacion anterior..... procedimiento migrado:
        /*$this->db->where('id_vendedor', $id);
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
                                'tipo_cliente'    => 'CLIENTE'
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
    }*/



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
                           usuario.correo_usuario as user_regis');
        $this->db->from("vendedores");
        $this->db->join('auditoria', 'vendedores.id_vendedor = auditoria.cod_reg');
        $this->db->join('usuario', 'auditoria.usr_regins = usuario.id_usuario');
        $this->db->join('usuario usr', 'vendedores.id_usuario = usr.id_usuario');
        $this->db->join('datos_personales', 'vendedores.id_usuario = datos_personales.id_usuario');
        $this->db->join('lval', 'lval.codlval = vendedores.tipo_vendedor');
        $this->db->where('auditoria.tabla', $this->tabla_vendedor);
        $resultados = $this->db->get();
        return $resultados->result();*/
        //-----------------------------------------------------------------------------------------
        //Migracion Mongo DB
        $res_vendedor = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get("vendedores");
            $listado = [];
            foreach ($res_vendedor as $clave => $valor) {
                $valores = $valor;
                $valores["id_vendedor"] = (string)$valor["_id"]->{'$id'};
                $valores["id_usuario"] = (string)$valor["id_usuario"];
                $id_usuario = new MongoDB\BSON\ObjectId($valores["id_usuario"]);
                $res_dt = $this->mongo_db->where(array('id_usuario'=>$id_usuario))->get('datos_personales');
                $valores["nombre_user"] = $res_dt[0]["nombre_datos_personales"];
                $valores["apellido_user"] = $res_dt[0]["apellido_p_datos_personales"];
                $valores["apellido_m_user"] = $res_dt[0]["apellido_m_datos_personales"];
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
                $listado[]=$valores;
            }
            return $listado;
        //-----------------------------------------------------------------------------------------
    }



    /*
    *   Eliminar vendedor
    */
    public function eliminar_vendedor($id){
        /*try {
            if(!$this->db->delete($this->tabla_vendedor, array('id_vendedor' => $id))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->db->delete('auditoria', array('cod_reg' => $id, 'tabla' => $this->tabla_vendedor));
                echo json_encode("<span>el vendedor se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
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
        $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_vendedor);
        //--Auditoria
        if($eliminar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar vendedor',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_vendedor);
            echo json_encode("<span>El vendedor se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso

        }
        //------------------------------------------------------------
    }



    public function status_vendedor($id, $status){
        /*$datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_vendedor);
        $this->db->update('auditoria', $datos);*/
        //------------------------------------------------------------
        //Migracion MONGO DB
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $fecha = new MongoDB\BSON\UTCDateTime();

        $id = new MongoDB\BSON\ObjectId($id);

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

        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_vendedor);

        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status vendedor',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_vendedor);
        }
        //------------------------------------------------------------
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
        /*$datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_cartera_clientes);
        $this->db->update('auditoria', $datos);*/
        //------------------------------------------------------------------------------
        //--Migracion Mongo DB
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

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

        $id_cartera = new MongoDB\BSON\ObjectId($id);

        $modificar = $this->mongo_db->where(array('_id'=>$id_cartera))->set($datos)->update($this->tabla_cartera_clientes);

        //var_dump($modificar);die('');
        //--Auditoria

        if($modificar){
            $data_auditoria = "";
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status vendedores',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_cartera))->push('auditoria',$data_auditoria)->update($this->tabla_cartera_clientes);
        }
        //------------------------------------------------------------------------------
    }



    /*
    *   Eliminar_multiple_vendedores
    */
    public function eliminar_multiple_vendedores($id_vendedor){
        /*$eliminados=0;
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
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);*/
        //--------------------------------------------------------------------------------------
        //MIGRACION MONGO DB
        $eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id_vendedor as $vendedores){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            $id = new MongoDB\BSON\ObjectId($vendedores);
            $datos = $data=array(
                                    'eliminado'=>true,
            );
            //-----------------------------------------------------------
            /*$descuentos = $this->buscarDescuentos($esquema);
            $comisiones = $this->buscarComisiones($esquema);
            $recargos = $this->buscarRecargos($esquema);

            if (count($descuentos)>0 || count($comisiones)>0 || count($recargos)>0){
                $noEliminados++;
            }
            //-----------------------------------------------------------
            else{*/
            $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_vendedor);
            //--Auditoria
            if($eliminar){
                $eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar vendedores',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$vendedores))->push('auditoria',$data_auditoria)->update($this->tabla_vendedor);
            }else{
                $noEliminados++;
            }
            //}
        //----------------------------------------------------------------------------------
        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //----------------------------------------------------------------------------
    }

    /*
    *   Status Multiple Vendedor
    */
    public function status_multiple_vendedores($id, $status){
        /*
        $date = date('Y-m-d');
        $proyectos = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = '$date', usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = '$date' WHERE cod_reg in (" . $proyectos . ") AND tabla='" . $this->tabla_vendedor . "'");
        */
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

            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_vendedor);

            //var_dump($modificar);die('');
            //--Auditoria

            if($modificar){
                $data_auditoria = "";
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status vendedores',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_vendedor);
            }
        }
        //---------------------------------------------------------------------------
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
        /*$this->db->select('cc.*,
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
        $this->db->where('cc.tipo_cliente', 'CLIENTE');
        $resultados = $this->db->get();
        return $resultados->result();*/
        //-----------------------------------------------------------------------------------------
        //--Migracion Mongo DB
        //$id_vendedor =  new MongoDB\BSON\ObjectId($vendedor);
        $res_cartera = $this->mongo_db->where(array('eliminado'=>false,'id_vendedor'=>$vendedor))->get($this->tabla_cartera_clientes);
        $listado = [];
        foreach ($res_cartera as $clave => $valor) {
            $valores = $valor;
            $valores["id"] = (string)$valor["_id"]->{'$id'};
            /*$id_proyecto = $valor["id_proyecto"];
            $id_proy = new MongoDB\BSON\ObjectId($id_proyecto);

            #Consulto proyectos
            $res_proyectos = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'_id'=>$id_proy))->get('proyectos');
            $valores["codigo_proyecto_cliente"] = $res_proyectos[0]["codigo"];
            $valores["name_proyecto_cliente"] = $res_proyectos[0]["nombre"];*/

            #Consulto cliente pagador
            $id_cliente = $valor["id_cliente"];
            $id_cli = new MongoDB\BSON\ObjectId($id_cliente);
            $res_cliente = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'_id'=>$id_cli))->get('cliente_pagador');
            $id_datos  = $res_cliente[0]["id_datos_personales"];
            $id_dt  = new MongoDB\BSON\ObjectId($id_datos);

            #Consulto datos personales
            $res_dt = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'_id'=>$id_dt))->get('datos_personales');
            $valores["name_cliente"] = $res_dt[0]["nombre_datos_personales"];
            
            isset($res_dt[0]["apellido_p_datos_personales"])?$valores["apellido_p_clinte"] = $res_dt[0]["apellido_p_datos_personales"]:$valores["apellido_p_clinte"] = "";
            
            isset($res_dt[0]["apellido_m_datos_personales"])?$valores["apellido_m_clinte"] = $res_dt[0]["apellido_m_datos_personales"]:$valores["apellido_m_clinte"] ="";

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

            if($valor["status"]==true){
                $valores["status"] = "1";
            }else{
                $valores["status"] = "2";
            }
            //$valores["status"] = $valor["status"];
            //-----------------------------------------------------------------------------------
            $listado[] = $valores;
        }
        return $listado;
        //-----------------------------------------------------------------------------------------
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
        /*try {
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
        }*/
        //---------------------------------------------------------------------------------------
        //Migracion Mongo DB
        //------------------------------------------------------------------------------
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $datos = array(
                                'eliminado'=>true,
        );

        $id_cartera = new MongoDB\BSON\ObjectId($id);

        $modificar = $this->mongo_db->where(array('_id'=>$id_cartera))->set($datos)->update($this->tabla_cartera_clientes);

        //var_dump($modificar);die('');
        //--Auditoria

        if($modificar){
            $data_auditoria = "";
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Eliminar vendedores',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_cartera))->push('auditoria',$data_auditoria)->update($this->tabla_cartera_clientes);

            echo json_encode("<span>El registro se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
        }
        //------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------
    }


    public function getrfc($id, $rfc)
    {
        $this->db->where('id_vendedor', $id);
        $this->db->where('rfc', $rfc);
        $result = $this->db->get('vendedores');
        return $result->row();
    }

    public function verificarExisteUsuario($id_usuario){
        $res_vendedor= $this->mongo_db->where(array('id_usuario' => $id_usuario))->get($this->tabla_vendedor);
        if(count($res_vendedor)>0){
            echo "<span>Ya existe un vendedor asociado a este usuario</span>";die('');
        }
    }

    public function verificarExisteRfc($rfc){
        $res_vendedor= $this->mongo_db->where(array('rfc' => $rfc))->get($this->tabla_vendedor);
        if(count($res_vendedor)>0){
            echo "<span>Ya existe un vendedor con ese rfc</span>";die('');
        }
    }

}

/* End of file Vendedores_model.ph///p */
/* Location: ./application/models/Vendedores_model.php */
