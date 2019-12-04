<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class ListaVista_model extends CI_Model
{

    private $tabla_lista_vista = "lista_vista";
    private $tabla_modulo = "modulo_vista";

    public function listado_listaVista()
    {
        /*$this->db->where('a.tabla', $this->tabla_lista_vista);
        $this->db->select('lv.*, a.fec_regins, u.correo_usuario, a.status, mv.nombre_modulo_vista');
        $this->db->from($this->tabla_lista_vista . ' lv');
        $this->db->join('auditoria a', 'lv.id_lista_vista = a.cod_reg');
        $this->db->join('usuario u', 'a.usr_regins = u.id_usuario');
        $this->db->join($this->tabla_modulo . ' mv', 'lv.id_modulo_vista = mv.id_modulo_vista');
        $resultados = $this->db->get();
        return $resultados->result();*/

        //--Modificacion con Mongo db
        //---------------------------------------------------------------------------
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get($this->tabla_lista_vista);
        foreach ($resultados as $clave => $valor) {
            $auditoria = $valor["auditoria"][0];
            //var_dump($auditoria->cod_user);die('');
            //--usuario en cuestion
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
            //var_dump($res_us[0]["auditoria"]->status);die('');
            //$valor["fec_regins"] = $valor["auditoria"][0]->fecha->toDateTime();
            $vector_auditoria = reset($valor["auditoria"]);
            $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            $valor["correo_usuario"] = $res_us[0]["correo_usuario"];
            $valor["status"] = $valor["status"];
            $valor["id_lista_vista"] = $valor["_id"]->{'$id'};
            $valor["id_modulo_vista"] = $valor["id_modulo_vista"];
            $valor["posicion_lista_vista"] = (integer)$valor["posicion_lista_vista"];
            //new MongoDB\BSON\ObjectId(
            //--Consulto el modulo vista
            $res_modulo_vista = $this->mongo_db->where(array('_id'=>$valor["id_modulo_vista"],'eliminado'=>false))->get($this->tabla_modulo);
            foreach ($res_modulo_vista as $value) {
                $valor["nombre_modulo_vista"] = $value["nombre_modulo_vista"];
            }
            //Lo transformo en string para poder usarlo en la vista....
            $valor["id_modulo_vista"] =  $id = (string)$valor["id_modulo_vista"];
            $listado[] = $valor;
        }    
        //--
        $listado2 = $listado;
        return $listado2;
        //---------------------------------------------------------------------------
    }

    public function modulos()
    {
        /*$this->db->where('a.tabla', $this->tabla_modulo);
        $this->db->where('a.status', 1);
        $this->db->select('mv.id_modulo_vista, mv.nombre_modulo_vista,a.status,a.id_auditoria');
        $this->db->from($this->tabla_modulo. " mv");
        $this->db->join('auditoria a', 'a.cod_reg = mv.id_modulo_vista');
        $resultados = $this->db->get();
        return $resultados->result();*/
        $resultados = $this->mongo_db->where(array('eliminado'=>false))->get($this->tabla_modulo);
        return $resultados;
    }
        
    public function registrar_lista_vista($data){
        /*$this->db->insert($this->tabla_lista_vista, $data);
        $datos=array(
            'tabla' => $this->tabla_lista_vista,
            'cod_reg' => $this->db->insert_id(),
            'usr_regins' => $this->session->userdata('id_usuario'),
            'fec_regins' => date('Y-m-d'),
        );
        $this->db->insert('auditoria', $datos);*/
        //-------------------------------------------------------------
        //Migracion Mongo DB
        $fecha = new MongoDB\BSON\UTCDateTime();
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        //Auditoria...
        $data["auditoria"] = [array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Registrar Lista Vista',
                                                'operacion'=>''
                                        )];
        $insertar1 = $this->mongo_db->insert($this->tabla_lista_vista, $data);
        //var_dump($insertar1);die('');
        //-------------
    }
    //--
    public function actualizar_lista_vista($id, $data)
    {
        /*var_dump($id); die('');
        $this->db->where('id_lista_vista', $id);
        $this->db->update($this->tabla_lista_vista, $data);
        $datos=array(
            'usr_regmod' => $this->session->userdata('id_usuario'),
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_lista_vista);
        $this->db->update('auditoria', $datos);*/
        //-------------------------------------------------------
        //Migracion Mongo DB
        $id = new MongoDB\BSON\ObjectId($id);
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        $modificar1 = $this->mongo_db->where(array('_id'=>$id))->set($data)->update($this->tabla_lista_vista);
        if($modificar1){
        //--Auditoria
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar lista vista',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_lista_vista);
        }
        //var_dump($modificar1);die('');
        //-------------------------------------------------------
    }

    public function verificar_lista_vista($nombre_lista_vista)
    {
        /*$this->db->where('nombre_lista_vista', $nombre_lista_vista);
        $this->db->limit(1);
        $resultados = $this->db->get($this->tabla_lista_vista);
        return $resultados->result_array();*/
        //-----------------------------------------------------------
        //Migracion MONGO DB
        $res = $this->mongo_db->where(array("nombre_lista_vista" => $nombre_lista_vista,'eliminado'=>false))->get('lista_vista');
        return $res;
        //->where(array("nombre_lista_vista" => $nombre_lista_vista,"eliminado"=>false))
        //-----------------------------------------------------------
    }
    /*
    *   Consultar dependencia a otras tablas
    */
    public function consultar_dependencia($tabla,$filtro){
        $id = new MongoDB\BSON\ObjectId($filtro);
        $res = $this->mongo_db->where(array('eliminado'=>false,'id_lista_vista'=>$id))->get($tabla);
        //$res2 = $this->mongo_db->where(array('eliminado'=>false))->get($tabla);
        //echo "<br>(x)";var_dump($id);var_dump(count($res));var_dump(count($res2));
        /*if(count($res)>0){
            $id_roles = (string)$res[0]["id_rol"];
            //var_dump($id_roles);die('');
            $id_rol = new MongoDB\BSON\ObjectId($id_roles); 
            $roles =  $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_rol))->get("rol");
            $recordset = $roles;    
        }else{
            $recordset = [];
        }*/
        $recordset = $res;
        return count($recordset);
    }    
    /*
    *
    */
    public function eliminar_lista_vista($data)
    {
        $dependencia = $this->consultar_dependencia("rol_operaciones",$data['id_lista_vista']);
        if($dependencia>0){
            echo "<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>";die('');
        }
        /*try { 
            if(!$this->db->delete($this->tabla_lista_vista, array('id_lista_vista' => $data['id_lista_vista']))){
                throw new Exception("<span>No se puede eliminar el registro porque tiene dependencia en otras tablas!</span>");
            }else{
                $this->ordernar_lista_vista($data['id_modulo_vista']);
                $this->db->delete('auditoria', array('cod_reg' => $data['id_lista_vista'], 'tabla' => $this->tabla_lista_vista));
                echo json_encode("<span>La función se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso
            }
        } catch(Exception $e){ 
            echo $e->getMessage(); // envio de mensaje de error
        }*/
        //------------------------------------------------------------------------------------
        //Migracion MONGO DB

            $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
            
            $fecha = new MongoDB\BSON\UTCDateTime();
            
            $id_lista_vista = new MongoDB\BSON\ObjectId($data['id_lista_vista']);

            $datos = $data=array(
                                    'eliminado'=>true,
            );
            $eliminar = $this->mongo_db->where(array('_id'=>$id_lista_vista))->set($datos)->update($this->tabla_lista_vista);
            //--Auditoria
            if($eliminar){
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar modulo',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_lista_vista))->push('auditoria',$data_auditoria)->update($this->tabla_lista_vista); 
                echo json_encode("<span>El módulo se ha eliminado exitosamente!</span>"); // envio de mensaje exitoso

            }
        //------------------------------------------------------------------------------------
    }

    public function status_lista_vista($id, $status)
    {
        /*$datos=array(
            'status'=>$status,
            'fec_status'=> date('Y-m-d'),
            'usr_regmod' => '1',
            'fec_regmod' => date('Y-m-d'),
        );
        $this->db->where('cod_reg', $id)->where('tabla', $this->tabla_lista_vista);
        $this->db->update('auditoria', $datos);*/
        //--------------------------------------------------------------------------
        //Migracion mongo DB
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
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_lista_vista);
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status lista vista',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_lista_vista); 
        }
        //---------------------------------------------------------------------------
    }
    /*
    *   consultar_dependencia_multiple
    */
    public function consultar_dependencia_multiple($id){
        $c = 1;
        $recordset["no_eliminados"]=0;
        $recordset["lista_a_eliminar"]=[];
        foreach ($id as $lista_vista) {
            $id_lista = new MongoDB\BSON\ObjectId($lista_vista);
            //-1: Verifico si esta en rol de operaciones
            $res = $this->mongo_db->where(array('eliminado'=>false,'id_lista_vista'=>$id_lista))->get('rol_operaciones');
            if(count($res)>0){
                //-2: Verifico si esta en roles
                /*$id_roles = (string)$res[0]["id_rol"];
                //var_dump($id_roles);die('');
                $id_rol = new MongoDB\BSON\ObjectId($id_roles); 
                $roles =  $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_rol))->get("rol");
                var_dump($id_rol);die('');

                //-3: SI esta en roles no lo debo eliminar
                if(count($roles)>0){*/
                    $recordset["no_eliminados"] = $c;
                    $c++;
               /* }else{
                    //-4: SINO esta en roles lo agrupo para eliminarlo
                    $recordset["lista_a_eliminar"][] = $lista_vista;
                //} */  
            }else{
                //-5: Si no esta en rol de operaciones lo agrupo para eliminarlo
                $recordset["lista_a_eliminar"][] = $lista_vista;
            }
        }
        return $recordset;
    }
    /*
    *
    */
    public function eliminar_multiple_lista_vista($id)
    {
        /*$eliminados=0;
        $noEliminados=0;
        foreach($id as $listaVista)
        {
            if($this->db->delete($this->tabla_lista_vista, array('id_lista_vista' => $listaVista))){
                $this->db->delete('auditoria', array('cod_reg' => $listaVista, 'tabla' => $this->tabla_lista_vista));
                $eliminados++;
            }else{
                $noEliminados++;
            }
        }
        $this->ordernar_todas_listas_vistas();
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);*/
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
        //Nuevo script de eliminar multiples....
        //Abril 2019
        $eliminados=0;
        $noEliminados=0;
        $datos = $data=array(
                                        'eliminado'=>true,
                );
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        $resultado_dependencia = $this->consultar_dependencia_multiple($id);
        //var_dump($resultado_dependencia);die('');
        $noEliminados = $resultado_dependencia["no_eliminados"];
        $lista_a_eliminar = $resultado_dependencia["lista_a_eliminar"];
        foreach ($lista_a_eliminar as $IdlistaVista) {
            $id = new MongoDB\BSON\ObjectId($IdlistaVista);
            $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_lista_vista);
            //--Auditoria
            if($eliminar){
                $eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar lista vista',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_lista_vista);
            }else{
                $noEliminados++;
            }   
        }
        $this->ordernar_todas_listas_vistas();
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //---------------------------------------------------------------------------------
        /*$eliminados=0;
        $noEliminados=0;
        
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id as $listaVista){
            //Verificacion si tienen dependencia con otras tablas...
            $dependencia = $this->consultar_dependencia("rol_operaciones",$listaVista);
            if($dependencia>0){
                 $noEliminados++;
            }else{

                $id_lista_vista = new MongoDB\BSON\ObjectId($listaVista);
                $datos = $data=array(
                                        'eliminado'=>true,
                );
                $eliminar = $this->mongo_db->where(array('_id'=>$id_lista_vista))->set($datos)->update($this->tabla_lista_vista);
                //--Auditoria
                if($eliminar){
                    $eliminados++;
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar lista vista',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_lista_vista))->push('auditoria',$data_auditoria)->update($this->tabla_lista_vista);
                }else{
                    $noEliminados++;
                }   
            }
            
            //----------------------------------------------------------------------------------    

        }*/
    
        //-------------------------------------------------------------------------------------
        /*$this->ordernar_todas_listas_vistas();
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);*/
    }

    public function status_multiple_lista_vista($id, $status)
    {
        /*$listas_vistas = str_replace(' ', ',', $id);
        $this->db->query("UPDATE auditoria SET status = " . $status . ", fec_status = " . date('Y-m-d') . ", usr_regmod = " . $this->session->userdata('id_usuario') . ", fec_regmod = " . date('Y-m-d') . " WHERE cod_reg in (" . $listas_vistas . ") AND tabla='" . $this->tabla_lista_vista . "'");*/
         //---------------------------------------------------------------------------
        //--Migracion Mongo DB
        $arreglo_id = explode(' ',$id);
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        
        foreach ($arreglo_id as $valor) {
            $id = new MongoDB\BSON\ObjectId($valor);
            //var_dump($id);//die('');
            
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

            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_lista_vista);
            //var_dump($modificar);//die('');
            //--Auditoria
            if($modificar){
                $data_auditoria = "";
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status lista vista',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($this->tabla_lista_vista); 
            }
        }
        //---------------------------------------------------------------------------
    }

    public function posicionar_lista_vista_segun_modulo($posicionar)
    {
        if ($posicionar['moduloInicial'] == $posicionar['moduloFinal'])
        {
            if ($posicionar['posicionFinal'] > $posicionar['posicionInicial'])
            {
                /*$this->ordenar_lista_vista_resta('posicion_lista_vista > '.$posicionar['posicionInicial'].' AND posicion_lista_vista <= '.$posicionar['posicionFinal'].' AND id_modulo_vista = '.$posicionar['moduloFinal']);*/
                //--
                //Migracion MONGODB
                $this->ordenar_lista_vista_resta($posicionar);
                //--
            }
            else if ($posicionar['posicionFinal'] < $posicionar['posicionInicial'])
            {
                /*$this->ordenar_lista_vista_suma('posicion_lista_vista >= '.$posicionar['posicionFinal'].' AND posicion_lista_vista < '.$posicionar['posicionInicial'].' AND id_modulo_vista = '.$posicionar['moduloFinal']);*/
                //--
                //Migracion MONGODB
                $this->ordenar_lista_vista_suma($posicionar);
                //--
            }
        }
        else if ($posicionar['moduloInicial'] != $posicionar['moduloFinal'])
        {
            $datos = array(
                'posicion' => $posicionar['posicionFinal'],
                'modulo' => $posicionar['moduloFinal'],
            );
            $this->posicionar_lista_vista_nueva($datos);
        }
    }

    public function ordenar_lista_vista_resta($posicionar)
    {
       /*$this->db->where($sql);
        $resultados = $this->db->get($this->tabla_lista_vista);
        if ($resultados->num_rows() > 0) {
            foreach ($resultados->result() as $row)
            {
                $datos=array(
                    'posicion_lista_vista' => $row->posicion_lista_vista - 1,
                );
                $this->db->where('id_lista_vista', $row->id_lista_vista);
                $this->db->update($this->tabla_lista_vista, $datos);
            }
        }*/
        //---------------------------------------------------
        //Migracion MONGO DB
        //--Migramos la siguiente sentencia a través del orm de la libreria CIMONGO
       /*$this->ordenar_lista_vista_resta('posicion_lista_vista > '.$posicionar['posicionInicial'].' AND posicion_lista_vista <= '.$posicionar['posicionFinal'].' AND id_modulo_vista = '.$posicionar['moduloFinal']);*/
        $id_modulo_final = new MongoDB\BSON\ObjectId($posicionar['moduloFinal']);
        $resultado = $this->mongo_db->where_gt('posicion_lista_vista', $posicionar['posicionInicial'])->where_lte('posicion_lista_vista', $posicionar['posicionFinal'])->where(array('id_modulo_vista'=>$id_modulo_final))->get($this->tabla_lista_vista);

        if(count($resultado)>0){
            foreach ($resultado as $value)
            {
                $datos=array(
                    'posicion_lista_vista' => $resultado[0]["posicion_lista_vista"] - 1,
                );
                $id = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});
                $modificar1 = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_lista_vista);
                //var_dump($modificar1);
            }   
        }
        //---------------------------------------------------
    }

    public function ordenar_lista_vista_suma($posicionar)
    {
        /*$this->db->where($sql);
        $resultados = $this->db->get($this->tabla_lista_vista);
        if ($resultados->num_rows() > 0) {
            foreach ($resultados->result() as $row)
            {
                $datos=array(
                    'posicion_lista_vista' => $row->posicion_lista_vista + 1,
                );
                $this->db->where('id_lista_vista', $row->id_lista_vista);
                $this->db->update($this->tabla_lista_vista, $datos);
            }
        }*/
         //--------------------------------------------------------------
        //Migracion  a MONGODB
        //Migramos la siguiente sentencia a traves de la libreria CIMONGO
        /*$this->ordenar_lista_vista_suma('posicion_lista_vista >= '.$posicionar['posicionFinal'].' AND posicion_lista_vista < '.$posicionar['posicionInicial'].' AND id_modulo_vista = '.$posicionar['moduloFinal'])*/
        //--
        $id_modulo_final = new MongoDB\BSON\ObjectId($posicionar['moduloFinal']);
        $resultado = $this->mongo_db->where_gte('posicion_lista_vista', $posicionar['posicionFinal'])->where_lt('posicion_lista_vista', $posicionar['posicionInicial'])->where(array('id_modulo_vista'=>$id_modulo_final))->get($this->tabla_lista_vista);
        if(count($resultado)>0){
            foreach ($resultado as $value)
            {
                //var_dump($value["posicion_lista_vista"]);

                $datos=array(
                    'posicion_lista_vista' => $value["posicion_lista_vista"] + 1,
                );

                $id = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});

                $modificar1 = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_lista_vista);
                
            }
            //var_dump($modificar1); 
            //die('');  
        }
        //--------------------------------------------------------------

    }

    public function posicionar_lista_vista_nueva($posicionar)
    {
        /*$this->db->where('posicion_lista_vista >= ' . $posicionar['posicion'] . ' AND id_modulo_vista = ' . $posicionar['modulo']);
        $resultados = $this->db->get($this->tabla_lista_vista);
        if ($resultados->num_rows() > 0) {
            foreach ($resultados->result() as $row)
            {
                $datos=array(
                    'posicion_lista_vista' => $row->posicion_lista_vista + 1,
                );
                $this->db->where('id_lista_vista', $row->id_lista_vista);
                $this->db->update($this->tabla_lista_vista, $datos);
            }
        }*/
        //Migracion  a MONGODB
        //Migramos la siguiente sentencia a traves de la libreria CIMONGO
        /*this->db->where('posicion_lista_vista >= ' . $posicionar['posicion'] . ' AND id_modulo_vista = ' . $posicionar['modulo']);*/
        //--
        $id_modulo = new MongoDB\BSON\ObjectId($posicionar['modulo']);
        $rs = $this->mongo_db->where(array('id_modulo_vista'=>$id_modulo))->where_gte('posicion_lista_vista', $posicionar['posicion'])->get($this->tabla_lista_vista);
        if(count($rs)>0){
            foreach ($rs as $value)
            {

                $datos=array(
                    'posicion_lista_vista' => $value["posicion_lista_vista"] + 1,
                );
                $id = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});
                $modificar1 = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($this->tabla_lista_vista);
                //var_dump($modificar1);
                
            }
        }      
    }

    public function ordernar_lista_vista($id_modulo_vista)
    {
        /*$this->db->where('id_modulo_vista', $id_modulo_vista);
        $this->db->order_by('posicion_lista_vista', 'DESC');
        $resultados = $this->db->get($this->tabla_lista_vista);
        $contador = $resultados->num_rows();
        foreach ($resultados->result() as $row)
        {
            $datos = array(
                'posicion_lista_vista' => $contador,
            );
            $this->db->where('id_lista_vista', $row->id_lista_vista);
            $this->db->update($this->tabla_lista_vista, $datos);
            $contador--;
        }*/
        //--------------------------------------------------------------
        //Migracion Mongo DB
        $id_modulo_vista = new MongoDB\BSON\ObjectId($id_modulo_vista);
              
        $listasVistas = $this->mongo_db->where(array('id_modulo_vista'=>$id_modulo_vista))->order_by(array('posicion_lista_vista' => 'DESC'))->get($this->tabla_lista_vista);
        //$listasVistas2 = $this->mongo_db->get($this->tabla_lista_vista);

        $contador = count($listasVistas);
        
        if(count($listasVistas)>0){
            foreach ($listasVistas as $value){
                $id_lista_vista = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});
                $datos = array(
                    'posicion_lista_vista' => $contador,
                );
                $contador--;
                $modificar1 = $this->mongo_db->where(array('_id'=>$id_lista_vista))->set($datos)->update($this->tabla_lista_vista);
                
            }
        }
        //--------------------------------------------------------------
       
    }

    public function ordernar_todas_listas_vistas()
    {
        /*$this->db->select('id_modulo_vista');
        $modulos = $this->db->get($this->tabla_modulo);
        if ($modulos->num_rows() > 0) {
            foreach ($modulos->result() as $modulo) {
                $this->db->where('id_modulo_vista', $modulo->id_modulo_vista);
                $this->db->order_by('posicion_lista_vista', 'DESC');
                $this->db->select('id_lista_vista');
                $listasVistas = $this->db->get($this->tabla_lista_vista);
                $contador = $listasVistas->num_rows();
                if ($contador > 0) {
                    foreach ($listasVistas->result() as $listaVista) {
                        $datos = array(
                            'posicion_lista_vista' => $contador,
                        );
                        $this->db->where('id_lista_vista', $listaVista->id_lista_vista);
                        $this->db->update($this->tabla_lista_vista, $datos);
                        $contador--;
                    }
                }
            }
        }*/
        //-------------------------------------------------------------------------------
        //Migracion Mongo DB
        //1-Consulto todos los registros de modulosdulo:       
        $modulos = $this->mongo_db->get($this->tabla_modulo);

       //$contador = count($listasVistas);
        //2-Si tiene registros  recorro
        if(count($modulos)>0){
            foreach ($modulos as $value){
                //3- Consulto cada lista vista
                $id_modulo_vista = new MongoDB\BSON\ObjectId($value["_id"]->{'$id'});    
                $listasVistas = $this->mongo_db->where(array('id_modulo_vista'=>$id_modulo_vista))->order_by(array('posicion_lista_vista' => 'DESC'))->get($this->tabla_lista_vista);
                //4-Cuento contador...
                $contador = count($listasVistas);
                if ($contador > 0) {
                    //5-Recorro la lista vista consultada en el punto 3
                    foreach ($listasVistas as $value2){
                        $datos = array(
                            'posicion_lista_vista' => $contador,
                        );
                        //6--Modifico lista vista segun su id
                        $id_lista_vista = new MongoDB\BSON\ObjectId($value2["_id"]->{'$id'});
                        $modificar1 = $this->mongo_db->where(array('_id'=>$id_lista_vista))->set($datos)->update($this->tabla_lista_vista);
                        //7-- Disminuyo el controlador
                        $contador--;
                        //--
                    }
                }
            }
        }
        //-------------------------------------------------------------------------------
    }

}
