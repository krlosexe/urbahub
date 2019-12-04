<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class MiCorreo_model extends CI_Model{

    private $nombre_tabla = "mi_correo";
        
    public function buscar_mi_correo(){
    	/*$resultados = $this->db->get($this->nombre_tabla);
        return $resultados->result();*/
        //-----------------------------------------------------------
	    //Migracion Mongo DB
	    $resultados = $this->mongo_db->get($this->nombre_tabla);
	    return $resultados;
	    //-----------------------------------------------------------
    }

    public function actualizar_mi_correo($id, $data){
    	$fecha = new MongoDB\BSON\UTCDateTime();
    	if($id==0){
    		/*$this->db->insert($this->nombre_tabla, $data);
	        $datos=array(
	            'tabla' => $this->nombre_tabla,
	            'cod_reg' => $this->db->insert_id(),
	            'usr_regins' =>  $this->session->userdata('id_usuario'),
	            'fec_regins' => date('Y-m-d'),
	        );
	        $this->db->insert('auditoria', $datos);*/
	        //-----------------------------------------------------------
	        //Migracion Mongo DB
	        $data["auditoria"] = [array(
														'cod_user'=>$this->session->userdata('id_usuario'),
														'nom_user'=>$this->session->userdata('nombre'),
														'fecha'=>$fecha,
														'accion'=>'Registrar mi correo',
														'operacion'=>''
												)];
			//array_push($data,$data_auditoria[0]);		
			
	        $insertar = $this->mongo_db->insert($this->nombre_tabla, $data);
			
			if ($insertar) {
			    return true;
			}
			return false;
			//------------------------------------------------------------
    	}else{
    		/*$this->db->where('id_mi_correo', $id);
	        $this->db->update($this->nombre_tabla, $data);
	        $datos=array(
	            'usr_regmod' =>  $this->session->userdata('id_usuario'),
	            'fec_regmod' => date('Y-m-d'),
	        );
	        $this->db->where('cod_reg', $id)->where('tabla', $this->nombre_tabla);
	        $this->db->update('auditoria', $datos);*/
	        //---------------------------------------------------------------
	        //Migracion Mongo DB
	        $id_mi_correo = new MongoDB\BSON\ObjectId($id);
	        $consulta = $this->mongo_db->where(array('_id'=>$id_mi_correo))->set($data)->update($this->nombre_tabla);
	        if(count($consulta)>0){
	        	$data_auditoria = array(
										'cod_user'=>$this->session->userdata('id_usuario'),
										'nom_user'=>$this->session->userdata('nombre'),
										'fecha'=>$fecha,
										'accion'=>'Modificar mi correo',
										'operacion'=>''
									);
	        	$consulta = $this->mongo_db->where(array('_id'=>$id_mi_correo))->push('auditoria',$data_auditoria)->update($this->nombre_tabla);
	        	return true;
	        }
	        return false;

	        //---------------------------------------------------------------
    	}
    }

    public function enviar_correo($asunto, $mensaje, $destinatario, $nombre_destinatario = null){
    	$res = $this->buscar_mi_correo();
    	//var_dump($res);die('');
    	if(count($res) > 0){
    		$res = $res[0];
    		
    			//$correo_remitente = "info@urbanhub.com.mx";
			    if(!empty($res["correo"])){
			    	if($res["correo"] != ""){
			    		$correo_remitente = $res["correo"];
			    	}
			    }

			    //$nombre_remitente = "CRMUrbanHub";
			    if(!empty($res["nombre"])){
			    	if($res["nombre"]!= ""){
			    		$nombre_remitente = $res["nombre"];
			    	}
			    }

    			$correo_copia = null;
			    if(!empty($res["correo_copia"])){
			    	if($res["correo_copia"] != ""){
			    		$correo_copia = $res["correo_copia"];
			    	}
			    }
    		if($res["smtp_auto"] != 1){
		    	$this->load->library('email');

			    $config['protocol'] = 'smtp';
			    $config['smtp_host'] = $res["servidor_smtp"];
			    $config['smtp_user'] = $res["usuario"];
			    $config['smtp_pass'] = $res["clave"];
			    $config['smtp_port'] = $res["puerto"];
			    $config['charset'] = 'utf-8';
			    $config['mailtype'] = 'html';
			    $config['wordwrap'] = TRUE;

			    $this->email->initialize($config);

			    //$nombre_remitente = "CRMUrbanHub";
			    
			    if(!empty($nombre_remitente)){
			    	$this->email->from($correo_remitente, $nombre_remitente);
			    }
			    else{
			    	$this->email->from($correo_remitente);
			    }

			    if(!empty($nombre_destinatario)){
			    	$this->email->to($destinatario, $nombre_destinatario);
			    }
			    else{
			    	$this->email->to($destinatario);
			    }

			    if(!empty($correo_copia)){
			    	$this->email->bcc($correo_copia);
			    }
			    
			    $this->email->subject($asunto);
			    $this->email->message($mensaje);

			    return $this->email->send();
			}
			else{

		        // Para enviar un correo HTML, debe establecerse la cabecera Content-type
		        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		        // Cabeceras adicionales
		        if(!empty($nombre_remitente)){
			    	$cabeceras .= 'From: '.$nombre_remitente.'<'.$correo_remitente . "> \r\n";
			    }
			    else{
			    	$cabeceras .= 'From: '.$correo_remitente . "\r\n";
			    }

			    if(!empty($correo_copia)){
			    	$cabeceras .= 'Bcc: '.$correo_copia . "\r\n";
			    }

		        // Enviarlo
		        if(!empty($nombre_destinatario)){
			    	return mail($nombre_destinatario." <".$destinatario.">", $asunto, $mensaje, $cabeceras);
			    }
			    else{
			    	return mail($destinatario, $asunto, $mensaje, $cabeceras);
			    }
		        
			}
		}

		return false;
    }

}
?>