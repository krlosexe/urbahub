<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class MiEmpresa_model extends CI_Model{

    private $tabla_empresa = "mi_empresa";
    private $tabla_contacto = "contacto";
    
    public function buscar_codigos($codigo)
    {
        $estados=$this->db->query("SELECT DISTINCT d_estado FROM codigo_postal WHERE d_codigo='$codigo'");
        $estados->result();
        $ciudades=$this->db->query("SELECT DISTINCT d_ciudad FROM codigo_postal WHERE d_codigo='$codigo'");
        $ciudades->result();
        $municipios=$this->db->query("SELECT DISTINCT d_mnpio FROM codigo_postal WHERE d_codigo='$codigo'");
        $municipios->result();
        $colonias=$this->db->query("SELECT id_codigo_postal, d_asenta FROM codigo_postal WHERE d_codigo='$codigo'");
        $colonias->result();
        $data=array(
            'estados' => $estados,
            'ciudades' => $ciudades,
            'municipios' => $municipios,
            'colonias' => $colonias,
        );
        return $data;
    }

    public function buscar_mi_empresa()
    {
        //$this->db->select(' e.*, c.*, cp.*');
        /*$this->db->select(' e.*, c.*');
        $this->db->from($this->tabla_empresa . " e");
        $this->db->limit(1);
        $this->db->join($this->tabla_contacto . " c", 'e.id_contacto = c.id_contacto');
        //$this->db->join("codigo_postal cp", 'c.id_codigo_postal = cp.id_codigo_postal');
        $empresa = $this->db->get();
        return $empresa->result();*/
        //-----------------------------------------------------------
        //Migracion Mongo DB
        $res_empresa = $this->mongo_db->get($this->tabla_empresa);
        $listado = [];
        if(count($res_empresa)>0){
            foreach ($res_empresa as $clave => $valor) {
               // var_dump($valor["_id"]);die('');
                //Transformo la fila en un array
                $id = new MongoDB\BSON\ObjectId($valor["id_contacto"]);
                $arreglo_contacto = $this->mongo_db->where(array('_id' => $id))->get('contacto');
                $super_lista = array_push_assoc($arreglo_contacto[0],$valor);
                $super_lista["_id"] = $valor["_id"];
                $listado[] = $super_lista;
            }
            
            if(count($listado)>0){
                //var_dump($listado);die('');
                return $listado;

            }else{
                return false;
            }
        }else{
                return false;
        }
        //-----------------------------------------------------------
    }

    public function actualizar_mi_empresa($idEmpresa, $nombreEmpresa, $rfcEmpresa, $idContacto, $dataContacto){

        $fecha = new MongoDB\BSON\UTCDateTime();
        //$idEmpresa=0;
        if($idEmpresa==0){
            /*---------------------------------------------------------------*/
            //                      CONTACTO                                 //
                /*$this->db->insert($this->tabla_contacto, $dataContacto);
                $idContacto=$this->db->insert_id(); // id del insert de contacto
                $datosContacto=array(
                    'tabla' => $this->tabla_contacto,
                    'cod_reg' => $idContacto,
                    'usr_regins' =>  $this->session->userdata('id_usuario'),
                    'fec_regins' => date('Y-m-d'),
                );
                $this->db->insert('auditoria', $datosContacto);*/
                //------------------------------------------------------------
                //--Migracion a Mongo DB
                
                $dataContacto["auditoria"] = [array(
                                                        'cod_user'=>$this->session->userdata('id_usuario'),
                                                        'nom_user'=>$this->session->userdata('nombre'),
                                                        'fecha'=>$fecha,
                                                        'accion'=>'Registrar contacto',
                                                        'operacion'=>''
                                                )];
                $insertar1 = $this->mongo_db->insert($this->tabla_contacto, $dataContacto);
                //var_dump($insertar1);die('');
                $res_contacto =$this->mongo_db->order_by(array('_id' => 'DESC'))->get($this->tabla_contacto);
                $idContacto2 = new MongoDB\BSON\ObjectId($res_contacto[0]["_id"]->{'$id'});
                //------------------------------------------------------------

            /*---------------------------------------------------------------*/

            /*---------------------------------------------------------------*/
            //                      EMPRESA                                  //
                /*$dataEmpresa=array(
                    'nombre_mi_empresa' => strtoupper($nombreEmpresa),
                    'rfc_mi_empresa' => strtoupper($rfcEmpresa),
                    'id_contacto' => $idContacto,
                );
                $this->db->insert($this->tabla_empresa, $dataEmpresa);
                $datosEmpresa=array(
                    'tabla' => $this->tabla_empresa,
                    'cod_reg' => $this->db->insert_id(),
                    'usr_regins' =>  $this->session->userdata('id_usuario'),
                    'fec_regins' => date('Y-m-d'),
                );
                $this->db->insert('auditoria', $datosEmpresa);*/
                //-----------------------------------------------------------
                //--Migracion  a mongo DB

                $dataEmpresa=array(
                    'nombre_mi_empresa' => strtoupper($nombreEmpresa),
                    'rfc_mi_empresa' => strtoupper($rfcEmpresa),
                    'id_contacto' => $idContacto2,
                    'status'=>true,
                    'eliminado'=>false,
                    'auditoria' => [array(
                                            'cod_user'=>$this->session->userdata('id_usuario'),
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Registrar empresa',
                                            'operacion'=>''
                                    )]
                );
                $insertar2 = $this->mongo_db->insert($this->tabla_empresa, $dataEmpresa);
                
                if (($insertar1)&&($insertar2)) {
                    return true;
                }
                return false;
                //-----------------------------------------------------------
            /*---------------------------------------------------------------*/

        }else{

            /*---------------------------------------------------------------*/
            //                      EMPRESA                                  //
                /*$this->db->where('id_mi_empresa', $idEmpresa);
                $dataEmpresa=array(
                    'nombre_mi_empresa' => strtoupper($nombreEmpresa),
                    'rfc_mi_empresa' => strtoupper($rfcEmpresa),
                );
                $this->db->update($this->tabla_empresa, $dataEmpresa);
                $datosEmpresa=array(
                    'usr_regmod' =>  $this->session->userdata('id_usuario'),
                    'fec_regmod' => date('Y-m-d'),
                );
                $this->db->where('cod_reg', $idEmpresa)->where('tabla', $this->tabla_empresa);
                $this->db->update('auditoria', $datosEmpresa);*/
                //-----------------------------------------------------------
                //--Migracion a Mongo DB
                //------------------------------------------------------------
                $dataEmpresa=array(
                    'nombre_mi_empresa' => strtoupper($nombreEmpresa),
                    'rfc_mi_empresa' => strtoupper($rfcEmpresa),
                    'status'=>true,
                    'eliminado'=>false,
                );
                $id_empresa = new MongoDB\BSON\ObjectId($idEmpresa);
                //var_dump($id_empresa);die('');
                $modificar1 = $this->mongo_db->where(array('_id'=>$id_empresa))->set($dataEmpresa)->update($this->tabla_empresa);
                //Auditoria...
                $data_auditoria = array(
                                    'cod_user'=>$this->session->userdata('id_usuario'),
                                    'nom_user'=>$this->session->userdata('nombre'),
                                    'fecha'=>$fecha,
                                    'accion'=>'Modificar mi empresa',
                                    'operacion'=>''
                                );
                $consulta1 = $this->mongo_db->where(array('_id'=>$id_empresa))->push('auditoria',$data_auditoria)->update($this->tabla_empresa);
                //------------------------------------------------------------

                //------------------------------------------------------------
            /*---------------------------------------------------------------*/

            /*---------------------------------------------------------------*/
            //                      CONTACTO                                 //
                /*$this->db->where('id_contacto', $idContacto);
                $this->db->update($this->tabla_contacto, $dataContacto);
                $datosContacto=array(
                    'usr_regmod' =>  $this->session->userdata('id_usuario'),
                    'fec_regmod' => date('Y-m-d'),
                );
                $this->db->where('cod_reg', $idContacto)->where('tabla', $this->tabla_contacto);
                $this->db->update('auditoria', $datosContacto);*/
                //-----------------------------------------------------------
                //--Migracion a Mongo DB
                $id_contacto = new MongoDB\BSON\ObjectId($idContacto);

                $modificar2 = $this->mongo_db->where(array('_id'=>$id_contacto))->set($dataContacto)->update($this->tabla_contacto);

                $data_auditoria2 = array(
                                        'cod_user'=>$this->session->userdata('id_usuario'),
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar contacto',
                                        'operacion'=>''
                                    );

                $consulta2 = $this->mongo_db->where(array('_id'=>$id_contacto))->push('auditoria',$data_auditoria2)->update($this->tabla_contacto);
                
                
                return true;
                //-----------------------------------------------------------
            /*---------------------------------------------------------------*/
        }
    }
    
}
?>