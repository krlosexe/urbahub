<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//----------------------------------------------------------------------------------
/*
*	Bloque de metodos realizados/Adaptados por @santu1987
*/
//-----------------------------------------------------------------------------------
class Cobranza extends CI_Controller {
	private $operaciones;
	function __construct(){
	    parent::__construct();
	    /*$this->load->database();*/
	    $this->load->library('session');
	    $this->load->model('Menu_model');
	    $this->load->model('Cobranza_model');
	    $this->load->model('MiCorreo_model');
	    $this->load->library('form_validation');
	    if (!$this->session->userdata("login")) {
	      redirect(base_url());
	    }
	    //--
	    $this->load->helper('consumir_rest');
	    $this->load->helper('organizar_sepomex');
	    $this->load->helper('array_push_assoc');
	    //--
  	}

  	public function index()
	{
		$datos['permiso']       = $this->Menu_model->verificar_permiso_vista('Cobranza', $this->session->userdata('id_rol'));
	    $data['modulos']        = $this->Menu_model->modulos();
	    $data['vistas']         = $this->Menu_model->vistas($this->session-> userdata('id_rol'));
	    $datos['breadcrumbs']   = $this->Menu_model->breadcrumbs('Cobranza');
	    //var_dump($datos['breadcrumbs']);die('');
	    //---
	    /*$listado_banco = consumir_rest('Bancos','listado_bancos','','');
        $datos['bancos'] = $listado_banco->data->array_list;*/
         $listado_banco = consumir_rest('Banco','buscar', array());
        $datos['bancos'] = $listado_banco->data;
	    //---
	    //$datos['forma_pagos']   = $this->Cobranza_model->getipospagos();
	    //--Migracion comsuimiendo servicios ag2....
	    /*$lista_pagos = consumir_rest('ListaValores','listado_valores','FORMAPAGO','');
	    $datos['forma_pagos'] = $lista_pagos->data->array_list;*/
	    $lista_pagos = consumir_rest('Lista_Valor','buscar', array('cod_static_tipo_valor'=>'FORMAPAGO'));
	    $datos['forma_pagos'] = $lista_pagos->data;
	    //var_dump($datos['forma_pagos']);die('');
	    //---
	    $this->operaciones      = $this->menu_rol_operaciones->control($data['modulos'], $data['vistas']);
    	$data['modulos_vistas'] = $this->operaciones;
	

    	/*$this->load->model('Autorizaciones_model');
	    if ($dataAutorizaion = $this->Autorizaciones_model->getAutorizacionCobranza()){
	    	 $datos['proyectos']  = $dataAutorizaion;
	    }else{
	  	  $datos['proyectos']     = $this->Proyectos_model->getproyectosactivos();
	    }*/

    	$this->load->view('cpanel/header');
	    $this->load->view('cpanel/menu', $data);
	    $this->load->view('ventas/Cobranza/index', $datos);
	    $this->load->view('cpanel/footer');
	    
	}
/*
*	getCotizaciones
*/
	public function getCotizaciones(){
		$listado = [];
		$listado2 = [];
		$listado = $this->Cobranza_model->listado_cobranza();
		foreach ($listado as $value) {
		  	$arreglo_data = $value;
		  	$arr_banco = []; 
		  	$arr_id_banco = [];
			foreach ($arreglo_data["banco_info"] as $clave_banco => $valor_banco) {
				/*$listado_banco = consumir_rest('Bancos','listado_bancos',$valor_banco["id_banco"],'');
        		$bancos=$listado_banco->data->array_list;*/

        		$bancos = consumir_rest('Banco','consultar', array('id_banco'=>$valor_banco["id_banco"]));
        		//----
        		/*echo "1";var_dump(in_array($valor_banco["id_banco"], $arr_id_banco));echo "<br>";
        		echo "2";var_dump($valor_banco["id_banco"]);echo "<br>";
        		echo "3";var_dump($arr_id_banco);echo "-----<br>";*/
        		//----
        		if (!in_array($valor_banco["id_banco"], $arr_id_banco)) {
        			$arr_banco[] = $bancos->data;
        			$arr_id_banco[] = $valor_banco["id_banco"];
        		}

			} 
			$arreglo_data["bancos"] = $arr_banco;
			
		  $listado2[] = $arreglo_data;
		}			
		/*var_dump($arr_banco);echo "<br>";
		var_dump($arr_id_banco);die('');*/
		echo json_encode($listado2);
	}
	/*
	*	tablaRecibos
	*/
	public function tablaRecibos(){
		$formulario = $this->input->post();
		$res_cobranza = $this->Cobranza_model->listado_cobranza_recibos($formulario["cobranza"]);
		$recibos = $res_cobranza[0]["recibos"];
		
		foreach ($recibos as $valor_recibos) {
			  $valores = $valor_recibos;
			  $valores->id_cobranza = $formulario["cobranza"];
			  $valores->id_cotizacion = $formulario["id_cotizacion"];
			  $valores->recibo = $valor_recibos->numero_recibo;  
			  $valores->numero_secuencia = $valor_recibos->numero_secuencia;	
			  //--
			  $res_comprobantes = $this->Cobranza_model->listado_comprobantes($formulario["cobranza"],$valor_recibos->numero_recibo);
	          $valores->comprobantes = $res_comprobantes; 
			  //--Numero de operacion
			  if($valor_recibos->operacion=="0"){
			  	$valores->operacion = "";
			  }
			  $valores->fecha = $valor_recibos->fecha;
			  $valores->fecha_movimiento = $valor_recibos->fecha_movimiento;
			  $valores->fecha_contable = $valor_recibos->fecha_contable;
			  $valores->cargo = number_format($valor_recibos->cargo,2);
			  $valores->abono = number_format($valor_recibos->abono,2);
			  $valores->saldo = number_format($valor_recibos->saldo,2);  
			  //--
			  #Consulto nombre de banco
			  /*$arreglo_banco = consumir_rest('Bancos','listado_bancos',$valores->banco_pago,'');
    		  $valores->bancos = $arreglo_banco->data->array_list;*/
    		//   if($valores->banco_pago){
    		//   		$arreglo_banco = consumir_rest('Banco','consultar', array('id_banco'=>$valores->banco_pago));
    		//   		$valores->bancos = $arreglo_banco->data;

    		//   		$valores->nombre_banco = $valores->bancos->nombre_banco;
    		//   }else{
    		//   		$valores->nombre_banco = "";
    		//   }
    		  #Consulto el numero de cuenta
    		//   $id_cuenta = $valores->cuenta;
    		//   $valores->id_cuenta = $id_cuenta;
    		//   if($id_cuenta!=""){
    		//   		$arr_cuenta = $this->Cobranza_model->consultar_cuenta_cliente_id($id_cuenta);
    		//   		$valores->cuenta = $arr_cuenta[0]["clabe_cuenta"]."/".$arr_cuenta[0]["numero_cuenta"];
    		//   }
    		  #Consulto forma de pago
    		  /*$lista_pagos = consumir_rest('ListaValores','listado_valores','FORMAPAGO',$valores->forma_pago);
	    	  $valores->forma_pagos = $lista_pagos->data->array_list;
	    	  $valores->fp = $valores->forma_pagos[0]->descriplval;*/
	    	//   if($valores->forma_pago!=""){
	    	//   	  $lista_pagos = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$valores->forma_pago));
		    // 	  $valores->forma_pagos = $lista_pagos->data;
		    // 	  $valores->fp = $valores->forma_pagos->nombre_lista_valor;
	    	//   }
	    	
	    	  /*$lista_pagos = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$valores->forma_pago));
	    	  $valores->forma_pagos = $lista_pagos->data;*/
    		  //$valores->fp = $valores->forma_pagos[0]->nombre_banco; 
			  //--
			  #Consulto los archivos comprobantes de pago
			  $res_comprobantes = $this->Cobranza_model->listado_comprobantes($formulario["cobranza"],$valor_recibos->numero_recibo);
			  $valores->comprobantes = $res_comprobantes;
			  //-- 
			  $listado2[] = $valores;
		}
		$this->array_sort_by2($listado2,"numero_recibo","operacion");               
		echo json_encode($listado2);
	}
	/*
	*
	*/
	public function array_sort_by(&$arrIni, $col, $order = SORT_ASC){
	    $arrAux = array();
	    foreach ($arrIni as $key=> $row)
	    {
	        $arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
	        $arrAux[$key] = strtolower($arrAux[$key]);
	    }
	    array_multisort($arrAux, $order, $arrIni);
	}
	/*
	*	
	*/
	public function array_sort_by2(&$arrIni,$col1,$col2){
	    /*$arrAux = array();
	    foreach ($arrIni as $key=> $row)
	    {
	        $arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
	        $arrAux[$key] = strtolower($arrAux[$key]);
	    }*/
	    //array_multisort($arrAux, $order, $arrIni);
	    array_multisort(array_column($arrIni, $col1),  SORT_ASC,
                array_column($arrIni,$col2), SORT_ASC,
                $arrIni);
	}


	/*
	*	armar recibos no pagados
	*/
	public function obtenerRecibosNoPagados($recibos){
		$listado = [];
		foreach ($recibos as $clave => $valor) {
			//---
			$valores = $valor;
			if($valor->pago==0){
				$listado[] = $valores;
			}
			//---
		}
		//--
		return $listado;
	}
	/*
	*	getrecibopendiente
	*/
	public function getrecibopendiente($cotizacion = ""){
		if ($cotizacion == "") {
			$id_cotizacion     = $this->input->get('id_cotizacion');
		}else{
			$id_cotizacion = $cotizacion;
		}

		$res_cobranza  = $this->Cobranza_model->getrecibopendiente($id_cotizacion);
		
		if (count($res_cobranza)>0) {

			$recibos = $this->obtenerRecibosNoPagados($res_cobranza[0]["recibos"]);

			$recibo_array =(array) $recibos;
			$data = array();
			$ingreso=0;
			$acum_saldo = 0;
			if(count($recibo_array)>0){
				$min_recibo    =  $recibo_array[0];
				//$id_min_recibo =  $recibo_array[0]->numero_recibo;
				$id_min_recibo =  $recibo_array[0]->numero_recibo;
				$data["recibo_pendiente"] = $min_recibo;
			
				//var_dump($data["recibo_pendiente"]);

				foreach ($recibos as $value) {
					//----
					//if ($id_min_recibo > $value->numero_recibo) {
					if (($id_min_recibo > $value->numero_recibo)&&($ingreso==0)){
						$data["recibo_pendiente"] = $value;
						$ingreso=1;
					}
					//----	
					$acum_saldo = $acum_saldo + $value->saldo;
					//----
				}
				$data["recibo_pendiente"]->saldo_acumulado = $acum_saldo;
				echo json_encode($data["recibo_pendiente"]);

			}else{
				echo json_encode("");
			}

		}else{
			echo json_encode("");
		}
		
	}


	/*
	*	getSaldoTotalPendiente
	*/
	/*public function getSaldoTotalPendiente(){
		$id_venta = $this->input->get('id_cotizacion');

		$venta = $this->Cobranza_model->getventasbyid($id_venta);

		$moras = $this->Cobranza_model->getMorasByVenta($id_venta);


		$montos_moras = 0;
		foreach ($moras as $key => $mora) {
			$montos_moras = $montos_moras + $mora->cargo;
		}

		$monto_total = $venta->monto_total - $venta->monto_descuento - $venta->monto_descuento_especial + $venta->monto_recargo + $montos_moras;
		
		$abonos = $this->Cobranza_model->getabonos($id_venta);

		$monto_total_abonos = 0;
		foreach ($abonos as $key => $abono) {
			$monto_total_abonos = $monto_total_abonos + $abono->abono;
		}

		echo json_encode($monto_total - $monto_total_abonos);
	}*/
	/***/
	public function registrar_cobranza($monto_pago = "", $monto = "", $id_unico = "", $id_cobranza = "", $id_cotizacion = "", $mes = "", $recibo = "", $fecha_pago = ""){
		//-------------------------------------------------------
		$fecha = new MongoDB\BSON\UTCDateTime();
        
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
		$formulario = $this->input->post();
		$monto_pago =  $monto_pago != "" ? $monto_pago : (float) str_replace(',', '', $formulario["monto_pago"]);
		$monto      =  $monto      != "" ? $monto      : (float) str_replace(',', '', $formulario["monto"]);

		$id_uncico  =  $id_unico   != "" ? $id_unico   : $formulario["numero_secuencia"];
		

		$id_cobranza = $id_cobranza  != "" ? $id_cobranza  : $formulario["id_cobranza"];

		$id_cotizacion = $id_cotizacion  != "" ? $id_cotizacion  : $formulario["id_cotizacion"];
		$fecha_pago    =  $fecha_pago    != "" ? $fecha_pago     : $formulario["fecha_pago"];
 		//--------------------------------------------------------
 		// if($formulario["fp"]!="614"){
 		// 	if($formulario["banco"]=="0"){
 		// 		echo "<span> Debe seleccionar un banco!</span>";die('');
 		// 	}
 		// 	if($formulario["cuenta"]=="0"){
 		// 		echo "<span> Debe seleccionar una cuenta!</span>";die('');
 		// 	}
 		// }
 		//--------------------------------------------------------
 		$this->reglas_validacion('insert');
        $this->mensajes_reglas_cobranza();
        
		#Consultar operacion


		$abono_sig = 0;
		if ($monto_pago > $monto) {
			$saldo_restante = 0;	
			$abono_sig = round($monto_pago- $monto, 2);
		}else{
			$saldo_restante = round($monto - $monto_pago, 2);
			$abono_sig = 0;	
		}


		


		if ($saldo_restante == 0) {
			$status_recibo = true;
		}else{
			$status_recibo = false;
		}
       
        
        $datos_recibos = $this->Cobranza_model->obtener_ultima_operacion($id_cobranza);

        //var_dump($datos_recibos);die('');
        
        $pendiente_pagar = $datos_recibos["pendiente"];
        
        $recibo_ant = $datos_recibos["recibo_ant"];

        $operacion = $this->Cobranza_model->obtenerNumeroOperacion($id_cobranza);
        //var_dump($operacion);die();
        $numero_recibo = $datos_recibos["numero_recibo"];

        $tipo_registro = $datos_recibos["tipo_registro"];

        $numero_secuencia = $recibo  != "" ? $recibo  : (integer)$formulario["recibo"];
        
        $mes =  $mes  != "" ? $mes  : $formulario["mes"];

        /*if($mes==0)
        	$mes = $mes+1;*/

        $fecha_ini = strtotime($fecha_pago)*1000;

        $fecha = $this->mongo_db->date($fecha_ini);

        $concepto = "PAGO DE ".strtoupper($datos_recibos["concepto"]);

        //$monto = str_replace(",","", $formulario["monto"]);
        //$monto_pago = str_replace(",","", $formulario["monto_pago"]);

       // $id_cobranza = $id_cobranza;

        //$id_cotizacion = $this->input->post('id_cotizaciones');

        $comprobantes = $this->input->post('comprobantes');


        //  echo $monto."  - ".$monto_pago;
        // return false;


 		
 		/*if($comprobantes==""){
        	echo "<span> Debe seleccionar una imagen!</span>";die('');
        }*/
        isset($formulario["banco"])?$banco=$formulario["banco"]:$banco="";
        
        isset($formulario["cuenta"])?$cuenta = $formulario["cuenta"]: $cuenta="";
                
        isset($formulario["numero_tarjeta"])?$numero_tarjeta = $formulario["numero_tarjeta"]: $numero_tarjeta="";


		$datos         = $this->getcobranzaventa($id_cotizacion)[0];
		$secuencia_new = sizeof($datos["recibos"]) + 1;


			
       
        //--------------------------------------------------------
         if($this->form_validation->run() == true){
          	$data = array('_id'          => new MongoDB\BSON\ObjectId(),
                          'operacion'=>$operacion,
                          "numero_secuencia"=>$secuencia_new,
                          'numero_recibo'=>$numero_secuencia,
                          'mes'=>$mes,
                          'fecha'=>new DateTime($fecha_pago),
                          'tipo_operacion'=>'A',
                          'concepto'=>$concepto,
                          'fecha_movimiento'=>new DateTime($fecha_pago),
                          'fecha_contable'=>new DateTime($fecha_pago),
                          'cargo'=>0,
                          'abono'=>$monto_pago,
                          'saldo'=>$saldo_restante,
                          'forma_pago'=>$formulario["fp"],
                          'banco_pago'=>$banco,
                          'monto_pago'=>$monto_pago,
                          'numero_tarjeta'=>$numero_tarjeta,
                          'cuenta'=>$cuenta,
                          'pago'  => $status_recibo  ? 1  : 0,
                          'status' => $status_recibo,
                          'tipo_registro'=>$tipo_registro,
                          'eliminado' => false,
                          'auditoria' => [array(
                                                    "cod_user" => $id_usuario,
                                                    "nomuser" => $this->session->userdata('nombre'),
                                                    "fecha" => $fecha = new MongoDB\BSON\UTCDateTime(),
                                                    "accion" => "Nuevo registro recibo",
                                                    "operacion" => ""
                                                )]
			);

			$this->Cobranza_model->registrar_cobranza($id_cobranza,$data,$pendiente_pagar,$recibo_ant, $id_uncico);
			if($abono_sig > 0){
				$recibo_pendiente = $this->GetReciboPendienteCobranza($id_cotizacion);
				$this->registrar_cobranza($abono_sig, $recibo_pendiente->saldo, (string)$recibo_pendiente->_id, $id_cobranza, $id_cotizacion, $recibo_pendiente->mes, $recibo_pendiente->numero_recibo, $fecha_pago);
			}
           	
            //--
            // if($comprobantes!=""){
            // 	foreach ($comprobantes as $value) {
			// 		if(!empty($value)) {
			// 		      if(file_exists(sys_get_temp_dir().'/'.$value)){
			// 		        rename(sys_get_temp_dir().'/'.$value,
			// 		                                'assets/cpanel/Cobranza/comprobantes/'.$value
			// 		                              );             
			// 		      }

			// 		      $data_file = array(
			// 		      						//'id_cotizacion' => $id_cotizacion, 
			// 		      						'id_cobranza' => $id_cobranza,
			// 		      						'numero_recibo'=>$numero_recibo,
			// 		      						'file' => $value,
			// 		      						'status' => true,
			// 		                          	'eliminado' => false,
			// 		                          	'auditoria' => [array(
			// 		                                                    "cod_user" => $id_usuario,
			// 		                                                    "nomuser" => $this->session->userdata('nombre'),
			// 		                                                    "fecha" => $fecha,
			// 		                                                    "accion" => "Nuevo registro de archivo recibo",
			// 		                                                    "operacion" => ""
			// 		                                                )]);
			// 		      $this->Cobranza_model->SaveComprobanteCobranza($data_file);

			// 	    }
			// 	}
            // }
			
           //--
        }else{
            echo validation_errors();
        }  
        //----------------------------------------------------------------------------
	} 








	public function GetReciboPendienteCobranza($id_venta){
		$res_cobranza  = $this->Cobranza_model->getrecibopendiente($id_venta);
		if (count($res_cobranza)>0) {

			$recibos = $this->obtenerRecibosNoPagados($res_cobranza[0]["recibos"]);

			$recibo_array =(array) $recibos;
			$data = array();
			$ingreso=0;
			$acum_saldo = 0;
			if(count($recibo_array)>0){
				$min_recibo    =  $recibo_array[0];
				//$id_min_recibo =  $recibo_array[0]->numero_recibo;
				$id_min_recibo =  $recibo_array[0]->numero_recibo;
				$data["recibo_pendiente"] = $min_recibo;
			
				//var_dump($data["recibo_pendiente"]);

				foreach ($recibos as $value) {
					//----
					//if ($id_min_recibo > $value->numero_recibo) {
					if (($id_min_recibo > $value->numero_recibo)&&($ingreso==0)){
						$data["recibo_pendiente"] = $value;
						$ingreso=1;
					}
					//----	
					$acum_saldo = $acum_saldo + $value->saldo;
					//----
				}
				$data["recibo_pendiente"]->saldo_acumulado = $acum_saldo;
				return $data["recibo_pendiente"];

			}else{
				return false;
			}

		}else{
			return false;
		}
	}
	/*
	*	editPago
	*/
	public function editPago(){
		//---
		$fecha = new MongoDB\BSON\UTCDateTime();
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
		//---
		$formulario = $this->input->post();
		$numero_recibo      = (integer)$this->input->post('id_recibo');
		$id_cobranza       = $this->input->post('id_cobranza');
		$id_cotizacion       = $this->input->post('id_venta');

		$comprobantes   = $this->input->post('comprobantes');
		//var_dump($comprobantes);die;
		//------------------------------------------------------
		if(count($comprobantes)>0){
			//---
			foreach ($comprobantes as $value) {
				if(!empty($value)) {
				      if(file_exists(sys_get_temp_dir().'/'.$value)){
				        rename(sys_get_temp_dir().'/'.$value,
				                                'assets/cpanel/Cobranza/comprobantes/'.$value
				                              );             
				      }

				        $data_file = array(
				      						'id_cotizacion' => $id_cotizacion, 
				      						'id_cobranza' => $id_cobranza,
				      						'numero_recibo'=>$numero_recibo,
				      						'file' => $value,
				      						'status' => true,
				                          	'eliminado' => false,
				                          	'auditoria' => [array(
				                                                    "cod_user" => $id_usuario,
				                                                    "nomuser" => $this->session->userdata('nombre'),
				                                                    "fecha" => $fecha,
				                                                    "accion" => "Nuevo registro de archivo recibo",
				                                                    "operacion" => ""
				                                                )]);
				      $this->Cobranza_model->SaveComprobanteCobranza($data_file);

			    }
			}
			//---
		}
		
		//------------------------------------------------------
		
		$fecha_ini = strtotime($this->input->post('fecha_contable'))*1000;

        $fecha_contable = $this->mongo_db->date($fecha_ini);	
		
		$data = array('recibos.$.fecha_contable' => $fecha_contable);
		$this->Cobranza_model->editPago($id_cobranza ,$numero_recibo, $data);

		echo json_encode("ok");
	}
	/*
	*	Mensaje de reglas...
	*/
	public function mensajes_reglas_cobranza(){
	      $this->form_validation->set_message('required', 'El campo %s es obligatorio');
	      $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo números');
    }
	/*
	*	
	*/
	public function reglas_validacion($method){
	    if($method=="insert"){ 
	        // Reglas para la tabla de cliente
	        $this->form_validation->set_rules('recibo','Recibo','required');
	        $this->form_validation->set_rules('mes','Mes','required');
	        $this->form_validation->set_rules('monto','Monto','required');
	       // $this->form_validation->set_rules('fp','Forma de pago','required');
	        /*$this->form_validation->set_rules('banco','Banco','required');
	        $this->form_validation->set_rules('cuenta','Cuenta','required');*/
	       // $this->form_validation->set_rules('fecha_pago','Fecha de pago','required');
	        $this->form_validation->set_rules('monto_pago','Monto Pago','required');
	        //
	    }
    }
    public function getcobranzaventa($id_cotizacion){
		
		//$id_venta = $this->input->get('id_venta');
		$cobranza = $this->Cobranza_model->getcobranzaventa($id_cotizacion);
		$listado = [];			
		foreach ($cobranza as $clave => $valor) {
			//$comprobantes = $this->Cobranza_model->getcomprobantesrecibo($recibo->id);
			//$recibo->comprobantes = $comprobantes;
			//
			$valores = $valor;
			$id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
            $vector_auditoria = reset($valor["auditoria"]);
            $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            $valores["correo_usuario"] = $res_us[0]["correo_usuario"];
            $listado[] = $valores;
		}
		//var_dump($listado);die('aqui!');
		return $listado;
	}
	/*
	*	Armar pdf
	*/
	public function pdfcobranza($id_cotizacion, $save = 0){	

		$this->load->model('MiEmpresa_model');
		$this->load->model('Corrida_model');

		$empresa = $this->MiEmpresa_model->buscar_mi_empresa();

		$temp_correo["nombre_empresa"] = $empresa[0]["nombre_mi_empresa"];
		$temp_correo["direccion"]      = $empresa[0]["direccion_contacto"].", calle: ".$empresa[0]["calle_contacto"];
		$temp_correo["telefono"]       = $empresa[0]["telefono_principal_contacto"];
		$temp_correo["correo"]         = $empresa[0]["correo_opcional_contacto"];

		$venta = $this->Cobranza_model->getCotizacionById($id_cotizacion);

		$temp_correo["cliente"]  =  $venta[0]["datos_clientes"];
		$temp_correo["nombres_clientes"] = $venta[0]["nombres_clientes"];
		$temp_correo["vendedor"] =  $venta[0]["datos_vendores"];
		$temp_correo["corrida"]  =  $venta[0]["id_cotizacion"];
		$temp_correo["status"] = $venta[0]["condicion"];
		$temp_correo["monto_inscripcion"] = number_format($venta[0]["monto_inscripcion"],2);
		$temp_correo["monto_mensualidad_total"] = number_format($venta[0]["monto_mensualidad_total"],2);
		$temp_correo["numero_cotizacion"] = $venta[0]["numero_cotizacion"];
		$temp_correo["vigencia"] = $venta[0]["vigencia"];
		$recibo_pendiente = $this->getrecibopendiente2($id_cotizacion);

        $abonos = $this->Cobranza_model->getabonos($id_cotizacion);
  		$temp_correo["recibos"] = $abonos;
        $monto_pagado = 0;
        foreach ($abonos as $key => $value) {
        	$temp_correo["monto_pagado"] = $monto_pagado + $value->abono;
        }

        if ($recibo_pendiente!="") {
	        $temp_correo["cuota_pendiente"]  = $recibo_pendiente->numero_recibo;
	        $temp_correo["saldo_cuota_pendiente"] = $recibo_pendiente->saldo_total;
	      //  $saldo_total_pendiente = $this->saldototalpendiente($id_venta);
	      //  $temp_correo["saldo_total_pendiente"] = $venta[0]["saldo"];
	        $temp_correo["saldo_total_pendiente"] = $recibo_pendiente->saldo_total;
        }else{
        	$temp_correo["cuota_pendiente"]= 0;
	        $temp_correo["saldo_cuota_pendiente"]= 0;
	        $temp_correo["saldo_total_pendiente"] = 0;
        }

		//$cantidad_producto = sizeof($productos);
		$temp_correo["cantidad_producto"] = 0;

		$cobranza = $this->getcobranzaventa($id_cotizacion);
		$temp_correo["cobranza"] = $cobranza[0];
		#Cargo liobreria pdf
        $this->load->library('libdompdf');
        #Cargo la vista 
        $html = $this->load->view('pdf/formatoCobranza', $temp_correo, true);
        $nombre_pdf = date("YmdHis")."_cobranza_".$id_cotizacion.".pdf";
        $final_ruta = "assets/outpdf/";
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
          $final_ruta = "assets\\outpdf\\";
        }
        $this->libdompdf->load_view($html, $nombre_pdf, array(''), str_replace("application\\", "", str_replace("application/", "", APPPATH)).$final_ruta);
        //----
        if($save==0)
        	//$a = 0;
			echo "<script>window.open('".base_url().'assets/outpdf/'.$nombre_pdf."', '_self');</script>";
		else
			return $nombre_pdf;
	}
	/*
	*	Send Email
	*/
	public function sendEmail($id_venta, $id_cliente){
		
		$cliente = $this->Cobranza_model->getcliente($id_cliente);
		
		$correo_cliente = $cliente["correo_cliente"];
		$nombre_cliente = $cliente["nombre_cliente"];
		
		$pdfCobranzas = $this->pdfcobranza($id_venta, 1);

		$this->load->library('email');

		//$htmlContent = '<h1>HTML email testing by CodeIgniter Email Library</h1>';
		$htmlContent = '<p>Estimado Sr.(a) '.$nombre_cliente.', utilizamos ésta vía para hacerle llegar el estado de cuenta, en ella se detallan los datos de las cuotas pendientes como de sus pagos..</p> <br> <p>Cualquier información adicional, por favor no dude en ubicarnos por nuestros # telefónicos: (+52)-999.999.99.99 o a través de info@consulta.com.mx</p>';
		
		$config['mailtype'] = 'html';
		//-----------------------------------------------------------------------------
		$res = $this->MiCorreo_model->buscar_mi_correo();
    	//var_dump($res);die('');
    	if(count($res) > 0){
    		$res = $res[0];
			$correo_remitente = $res["usuario"];
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
		}	
		//-----------------------------------------------------------------------------
		$this->email->initialize($config);
		$this->email->from($correo_remitente, $nombre_remitente);
		$this->email->to($correo_cliente, $nombre_cliente);
		$this->email->subject('Estado de Cuenta');
		$this->email->message($htmlContent);
		$this->email->attach('assets/outpdf/'.$pdfCobranzas);
		if ($this->email->send()) {
			echo json_encode("OK");
		}else{
			echo($this->email->send());
		}
	}
	/*
	*	Consultar las cuentas bancarias asociadas a un cliente y un banco
	*/
	public function consultarCuentas(){
		//-----------------------------------
		$fecha = new MongoDB\BSON\UTCDateTime();
        
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $formulario = $this->input->post();

        $listado = [];
        #consulto la coleccion cuentas_clientes
        $res_cuenta_clientes = $this->Cobranza_model->consultar_cuenta_cliente($formulario);

        foreach ($res_cuenta_clientes as $clave => $valor) {
        	$valores["id_cuenta"] =  (string)$valor["_id"]->{'$id'};
        	$valores["cuentas_clientes"] = $valor["clabe_cuenta"]."/".$valor["numero_cuenta"];
        	$listado[] = $valores;
        }
        echo json_encode($listado);die();
		//-----------------------------------
	}
/*
*
*/

//-------------------------------------------------------------------------------------
/*
*	Fin de bloque de  metodos realizados/adapatados por @santu1987
*/
//--------------------------------------------------------------------------------------


	/*public function getcobranzaventa()
	{
		$id_venta = $this->input->get('id_venta');
		$cobranza = $this->Cobranza_model->getcobranzaventa($id_venta);

		foreach ($cobranza as $key => $recibo) {
			$comprobantes = $this->Cobranza_model->getcomprobantesrecibo($recibo->id);
			$recibo->comprobantes = $comprobantes;
		}

		echo json_encode($cobranza);
	}/

	public function getenganche()
	{
		$id_venta = $this->input->get('id_venta');
		$enganche = $this->Cobranza_model->getenganche($id_venta);
		if ($enganche){
			echo json_encode("1");
		}else{
			echo json_encode("0");
		}
		
	}

	public function getabonos($id_venta)
	{
		$result = $this->Cobranza_model->getabonos($id_venta);

		echo json_encode($result);
	}



	/*public function getrecibopendiente($venta = "")
	{
		if ($venta == "") {
			$id_venta     = $this->input->get('id_venta');
		}else{
			$id_venta = $venta;
		}

		$recibo       = $this->Cobranza_model->getrecibopendiente($id_venta);

		if ($recibo) {
			$recibo_array =(array) $recibo;
			$data   = array();

			$min_recibo    =  $recibo_array[0];
			$id_min_recibo =  $recibo_array[0]->recibo;

			$data["recibo_pendiente"] = $min_recibo;

			foreach ($recibo as $value) {
				if ($id_min_recibo > $value->recibo) {
					$data["recibo_pendiente"] = $value;
				}
			}

			echo json_encode($data["recibo_pendiente"]);
		}else{
			echo json_encode("1");
		}
		
	}*/

	public function getrecibopendiente2($venta = ""){
		if ($venta == "") {
			$id_venta     = $this->input->get('id_venta');
		}else{
			$id_venta = $venta;
		}

	

		$cobranza  = $this->Cobranza_model->getrecibopendiente($id_venta);
		if(count($cobranza)>0){
			$recibo = $cobranza[0]["recibos"];
			//var_dump($recibo);die('');
			if (count($recibo)>0) {
				
				$recibo_array =(array) $recibo;
				
				$data   = array();

				$min_recibo    =  $recibo_array[0];
				
				$id_min_recibo =  $recibo_array[0]->numero_recibo;

				$data["recibo_pendiente"] = $min_recibo;
				
				$acum_saldo = 0;
				
				foreach ($recibo as $value) {
					if ($id_min_recibo > $value->numero_recibo) {
						$data["recibo_pendiente"] = $value;
					}
					///Los recibos no pagados debe sumarse su monto
					if($value->pago==0){
						$acum_saldo = $acum_saldo + $value->saldo;
					}
				}
				$data["recibo_pendiente"]->saldo_total = $acum_saldo;
				return $data["recibo_pendiente"];
			}else{
				return "";
			}		
		}else{
			return "";
		}
		
	}
	/***/

	/***/
	/*public function getrecibopendiente($venta = "")
	{
		if ($venta == "") {
			$id_venta     = $this->input->get('id_venta');
		}else{
			$id_venta = $venta;
		}

		$recibo = $this->Cobranza_model->getrecibopendiente($id_venta);

		if ($recibo) {
			$recibo_array =(array) $recibo;
			$data   = array();

			$min_recibo    =  $recibo_array[0];
			$id_min_recibo =  $recibo_array[0]->recibo;

			$data["recibo_pendiente"] = $min_recibo;

			foreach ($recibo as $value) {
				if ($id_min_recibo > $value->recibo) {
					$data["recibo_pendiente"] = $value;
				}
			}

			echo json_encode($data["recibo_pendiente"]);
		}else{
			echo json_encode("1");
		}
		
	}*/
	/***/

	public function savepago($recibo_sig="",$id_recibo="",$id_venta="",$mes="",$monto="",$fp="",$banco="", $numero_tarjeta="", $cuenta="", $fecha_pago="", $fecha_contable = "", $abono_sig="", $op = "",$mora=0, $credit = 0, $comprobantes = 0)
	{
		if ($recibo_sig != "") {
			$recibo = $recibo_sig;
		}else{
			$recibo    = $this->input->post('recibo');
		}

		if ($recibo != "") {

			if ($recibo_sig != "") {
			

				$monto_pago = $abono_sig;
			

			}else{

				$id_recibo = $this->input->post('id_recibo');
				$id_venta  = $this->input->post('id_venta');
				$mes       = $this->input->post('mes');
				$monto     =  (float) str_replace(',', '', $this->input->post('monto'));

				$fp             = $this->input->post('fp');
				$banco          = $this->input->post('banco');
				$cuenta         = $this->input->post('cuenta');
				$fecha_pago     = $this->input->post('fecha_pago');
				$fecha_contable = $this->input->post('fecha_contable');
				$monto_pago     =  (float) str_replace(',', '', $this->input->post('monto_pago'));
				$numero_tarjeta = $this->input->post('numero_tarjeta');

				$comprobantes = $this->input->post('comprobantes');
                if($comprobantes==""){
                	echo "<span> Debe seleccionar una imagen!</span>";die('');
                }

				if ($banco == '') {
	              $banco = null;
	            }

	            if ($cuenta == '') {
	              $cuenta = null;
	            }
			}



			$this->load->model('Corrida_model');
			$datos_venta = $this->Corrida_model->getdatosvetna($id_venta);
		    $plazo = $this->Corrida_model->getPlazo($datos_venta->plazo_saldo);




			if ($mes == 0) {

				if ($plazo->descriplval == "CONTADO"){
					$concepto = "PAGO CONTADO";
				}else{

					$concepto = "PAGO DE ENGANCHE";
				}
			}else{
				$concepto = "PAGO DE MENSUALIDAD";
			}

			if ($credit == 1) {
				$concepto = "PAGO DE MENSUALIDAD (NOTA DE CREDITO)";
			}
			
			$abono_sig = 0;
			if ($monto_pago > $monto) {
				$saldo_restante = 0;	
				$abono_sig = round($monto_pago - $monto, 2);

				$monto_pago = $monto;
				
			}else{
				$saldo_restante = round($monto - $monto_pago, 2);
				$abono_sig = 0;	
			}



			

			if ($saldo_restante == 0) {
				$status_recibo = 1;
			}else{
				$status_recibo = 0;
			}

			



			

			if ($op != "") {
				$operacion = $op;
			}else{
				$operaciones = $this->Cobranza_model->getoperaciones($id_venta, $recibo);
				if ($operaciones->operacion == NULL) {
					$operacion = 1;
				}else{  
					$operacion = $operaciones->operacion + 1;
				}
			}

			$data_recibo = $this->Cobranza_model->getdatarecibo($id_recibo);

			
			if(strpos($data_recibo->concepto, "MORA")) {
		      $concepto = "PAGO DE INTERES POR MORA";
		    }


			$data = array('id_venta'          => $id_venta, 
						  'recibo'            => $recibo,
						  'operacion'         => $operacion,
						  'mes'               => $mes,
						  'fecha'             => new DateTime($fecha_pago),
						  'tipo_operacion'    => 'A',
						  'concepto'          => $concepto,
						  'fecha_movimiento'  => new DateTime($fecha_pago),
						  'fecha_contable'    => new DateTime($fecha_contable),
						  'cargo'             => 0,
						  'abono'             => $monto_pago,
						  'saldo'             => $saldo_restante,
						  'forma_pago'        => $fp,
						  'banco_pago'        => $cuenta,
						  'monto_pago'        => $monto_pago,
						  'numero_tarjeta'    => $numero_tarjeta,
						  'file_comprobante'  => null,
						  'status'            => $status_recibo);

			if ($id_cobranza = $this->Cobranza_model->savepago($data, $id_recibo)) {


			    foreach ($comprobantes as $value) {
					if(!empty($value)) {
				      if(file_exists(sys_get_temp_dir().'/'.$value))
				      {
				        rename(sys_get_temp_dir().'/'.$value,
				                                'assets/cpanel/Cobranza/comprobantes/'.$value
				                              );             
				      }

				      $data_file = array('id_venta' => $id_venta, 'id_recibos' => $id_cobranza, 'file' => $value);
				      $this->Cobranza_model->SaveComprobanteCobranza($data_file);

				    }
				}



				if ($mora == 0) {
					if ($this->input->post('monto_mora') > 0) {
						$monto_mora = $this->input->post('monto_mora');
						$dias_mora  = $this->input->post('dias_mora');
						$porcentaje = $this->input->post('porcentaje');

						$data_mora = array('id_venta'          => $id_venta, 
										   'recibo'            => $recibo,
										   'mes'               => $mes,
										   'fecha'             => new DateTime($fecha_pago),
										   'tipo_operacion'    => 'C',
										   'concepto'          => "INTERES POR MORA: ".$dias_mora." DIAS",
										   'fecha_movimiento'  =>  new DateTime($fecha_pago),
										   'cargo'             => $monto_mora,
										   'abono'             => 0,
										   'saldo'             => $monto_mora,
										   'mora'              => 1,
										   'dias_mora'         => $dias_mora,
										   'porcentaje_mora'   => $porcentaje,
										   'monto_mora'        => $monto_mora,
										   'status'            => 0);
						$this->Cobranza_model->savemora($data_mora);
					}
				}

				

				if ($abono_sig > 0) {
					$recibo_pendiente = $this->getrecibopendiente2($id_venta);
					if ($recibo_pendiente) {
						$recibo    =  $recibo_pendiente->recibo;
						$id_recibo =  $recibo_pendiente->id;
						$mes       =  $recibo_pendiente->mes;
						$monto     =  $recibo_pendiente->saldo;
						
						$this->savepago($recibo,$id_recibo,$id_venta,$mes,$monto,$fp,$banco,$numero_tarjeta,$cuenta,$fecha_pago, $fecha_contable, $abono_sig,$operacion, 1, 0, $comprobantes);
					}else{
						if ($mes == 0) {
							$this->Cobranza_model->generar_credito($id_cobranza, $abono_sig);
						}else{
							$this->Cobranza_model->generar_credito($id_cobranza, $abono_sig);
						}
					}
				}else{
					//$this->savenotificacion($id_venta, $this->input->post());
				}
				//echo json_encode("Operacion Exitosa");
			}else{
				echo "A ocurrido un error, intentelo nuevamente";
			}

		}else{
			echo "No hay recibos pendientes";
		}

	}



	public function savenotificacion($id_venta, $data_pago)
	{
		$this->load->model('Cobranza_model');
		$this->load->model('vendedores_model');
		$this->load->model('ClientePagador_model');
	    $venta = $this->Cobranza_model->getventasbyid($id_venta);

	    $proyecto =  $venta->nombre_proyecto;
        $cliente  =  $venta->nombre_cliente." ".$venta->apellido_p_cliente." ".$venta->apellido_m_cliente;
		$vendedor =  $venta->nombre_vendedor." ".$venta->apellido_p_vendedor." ".$venta->apellido_m_vendedor;
		$corrida  =  $venta->id_venta;


		$vendedor_data  = $this->vendedores_model->getvendedorbyid($venta->id_vendedor);
		$id_user        = $vendedor_data->id_usuario;



		$notificacion = "Se acaba de realizar un abono correspondiente a la corrida financiera #$id_venta por un monto de $data_pago[monto_pago]";

	    $data_user = $this->ClientePagador_model->listarClientePagadorbyId($venta->id_cliente);
	
		$data_notificacion = array('descripcion' => $notificacion,
     							   'id_user'     => $venta->id_cliente,
     							   'status'      => 0,
     							   'fecha'       => date("Y-m-d")
	     	 							);

		


	    $data_email = array('email'          => $data_user->correo_contacto,
	     					'nombre'         => $data_user->nombre_datos_personales." ".$data_user->apellido_p_datos_personales." ".$data_user->apellido_m_datos_personales,
	     					'id_user'        => "xx"
	    );

	    $this->notificaciones->sendEmail($data_notificacion, $data_email);
	    
	}



	public function updateSaldo()
	{	
		$id_venta   = $this->input->get('id_venta');
		$monto_pago = $this->input->get('monto_pago');

		$this->Cobranza_model->updatesaldo($id_venta, $monto_pago);
			
	}

	public function calcularmora()
	{
		if ($this->input->get('recibo') != 1) {
			$id_venta    = $this->input->get('id_venta');

			$resultado   = $this->Cobranza_model->getdiasmoraproyecto($id_venta);
			if ($resultado->indicador_mora == 'S') {

				$fecha_cuota_original = $this->Cobranza_model->getfechacoutaoriginal($id_venta, $this->input->get('recibo'));

				
			    $fecha_pago  = $this->input->get('fecha_pago');
			    $monto       = str_replace(",", "", $this->input->get('monto'));


			    if (date_create($this->input->get('fecha_cuota')) > date_create($fecha_cuota_original)) {
			    	$fecha_cuota = $this->input->get('fecha_cuota');
			    }else{
			    	$fecha_cuota = $fecha_cuota_original;
			    }

			   // echo $fecha_cuota;
				$datetime1 = date_create($fecha_cuota);
				$datetime2 = date_create($fecha_pago);

				if ($datetime1 < $datetime2) {
					$interval           = date_diff($datetime1, $datetime2);
					$dias_transcurridos =  $interval->format('%a%');
					
					$dias_mora   = $resultado->can_dias_vencidos;
					$porcentaje  = $resultado->porcentaje_mora;
					

					$dias_vecidos = (int)($dias_transcurridos /  $dias_mora);

					if ($dias_vecidos > 0) {
						//$porcentaje_mora = round($porcentaje * $dias_vecidos, 2);	

						$porcentaje_mora = $porcentaje;	
						
						$total_mora = round((($monto / 100) * $porcentaje_mora), 2);

						$data = array('dias'       => $dias_transcurridos, 
									  'porcentaje' => $porcentaje_mora ,
									  'total_mora' => $total_mora );

						echo json_encode($data);
					}else{
						echo json_encode("N");
					}
				}else{
					echo json_encode("Nss");
				}

			}else{
				echo json_encode("N");
			}
		}else{
			echo json_encode("N");
		}

	}


	public function saldototalpendiente($id_venta)
	{
		$saldo = $this->Cobranza_model->saldototalpendiente($id_venta);
		return $saldo->saldo_pendiente;
	}

	public function mora_pendiente($id_venta)
	{
		$mora = $this->Cobranza_model->mora_pendiente($id_venta);
		return $mora;

	}

	


	


	public function listar($tipo = "")
	{
		$listado=$this->Tasa_cambio_model->listado($tipo);
   		echo json_encode($listado);
	}


	public function reglas_tasa($method){
	    if($method=="insert"){
	      $this->form_validation->set_rules('tipo_moneda','tipo de moneda','required');
	      $this->form_validation->set_rules('monto','monto','required');
	    }else if($method=="update"){
	      $this->form_validation->set_rules('monto_editar','monto','required');
	    }
	 }

	 public function mensajes_reglastasa(){
	    $this->form_validation->set_message('required', 'El campo %s es obligatorio');
	    $this->form_validation->set_message('min_length', 'El Campo %s debe tener un Mínimo de %d Caracteres');
	    $this->form_validation->set_message('max_length', 'El Campo %s debe tener un Máximo de %d Caracteres');
	}


	public function checksaldoventa()
	{
		$this->load->model('Productos_model');
		$id_venta =  $this->input->get('id_venta');

		$venta = $this->Cobranza_model->getventasbyid($id_venta);

		if ($venta->saldo == 0) {

			$stslval = $this->Productos_model->listado_valores_sts();
			foreach ($stslval as $sts) {
				if ($sts->descriplval == "LIQUIDADO") {
					$sts_producto = $sts->codlval;
					break;
				}
			}	
		
			$this->Cobranza_model->liquidar($id_venta, $sts_producto);

			echo json_encode("1");
		}
	}


	public function devolution()
	{
		$id_venta =  $this->input->get('id_venta');
		$credito  =  $this->Cobranza_model->buscar_credito($id_venta);


		if ($credito) {
			$data = array('id_venta'          => $id_venta, 
						  'recibo'            => $credito->recibo,
						  'operacion'         => $credito->operacion,
						  'mes'               => $credito->mes,
						  'fecha'             => $credito->fecha,
						  'tipo_operacion'    => 'D',
						  'concepto'          => "DEVOLUCION",
						  'fecha_movimiento'  => $credito->fecha_movimiento,
						  'fecha_contable'    => $credito->fecha_contable,
						  'cargo'             => 0,
						  'abono'             => (($credito->monto) * (-1)),
						  'saldo'             => 0,
						  'forma_pago'        => $credito->fp,
						  'banco_pago'        => $credito->cuenta,
						  'monto_pago'        => $credito->monto_pago,
						  'numero_tarjeta'    => $credito->numero_tarjeta,
						  'file_comprobante'  => null,
						  'status'            => 1);

			$this->Cobranza_model->SaveDevolution($data);
			$this->Cobranza_model->updatecredito($credito->id_operacion);

			echo json_encode("1");
		}else{
			echo json_encode("0");
		}



	}


	public function buscar_credito()
	{
		$id_venta =  $this->input->get('id_venta');
		$credito  = $this->Cobranza_model->buscar_credito($id_venta);

	
		 if ($credito) {
		 	$recibo_pendiente = $this->getrecibopendiente2($id_venta);
		 	$id_operacion = $credito->id_operacion;

			if ($recibo_pendiente) {
				$recibo    =  $recibo_pendiente->recibo;
				$id_recibo =  $recibo_pendiente->id;
				$mes       =  $recibo_pendiente->mes;
				$monto     =  $recibo_pendiente->saldo;
				
				$fp             = $credito->forma_pago;
				$fc             = $credito->fecha_contable;
				$banco          = $credito->banco_pago;
				$numero_tarjeta = $credito->numero_tarjeta;
				$cuenta         = $credito->banco_pago;
				$fecha_pago     = $credito->fecha_movimiento;
				$abono_sig      = $credito->monto;
				$operacion      = $credito->operacion;

				 $this->savepago($recibo,$id_recibo,$id_venta,$mes,$monto,$fp,$banco,$numero_tarjeta,$cuenta,$fecha_pago, $fc,$abono_sig,$operacion, 1, 1);

				$this->Cobranza_model->updatecredito($id_operacion);
				$this->Cobranza_model->updatesaldo($id_venta, $abono_sig);
			}
		 }


		$venta = $this->Cobranza_model->getventasbyid($id_venta);
		echo json_encode($venta->saldo);
		
	}

	public function deleteabonos()
	{
		$id_venta =  $this->input->get('id_venta');

		$this->Cobranza_model->deleteabonos($id_venta);
		echo json_encode("1");
	}


	public function liquidar()
	{
		$this->load->model('Productos_model');
		$id_venta =  $this->input->get('id_venta');

		$venta = $this->Cobranza_model->getventasbyid($id_venta);

		$stslval = $this->Productos_model->listado_valores_sts();
		foreach ($stslval as $sts) {
			if ($sts->descriplval == "LIQUIDADO") {
				$sts_producto = $sts->codlval;
				break;
			}
		}	
		$this->Cobranza_model->liquidar($id_venta, $sts_producto);
		echo json_encode("1");
	}


	public function updateabonos($id_venta)
	{
		$liberar_recibo = $this->Cobranza_model->liberarRecibo($id_venta);

		$recibo_pendiente = $this->getrecibopendiente2($id_venta);

		$operaciones = $this->Cobranza_model->getoperaciones2($id_venta);

		$data = array();
		foreach ($operaciones as  $operacion){
			echo json_encode($operacion)."<br>";

			$data["recibo"]          =  $recibo_pendiente->recibo;
			$data["id_recibo"]       =  $recibo_pendiente->id;
			$data["mes"]             =  $recibo_pendiente->mes;
			$data["monto"]           =  $recibo_pendiente->saldo;
			$data["fp"]              =  $operacion->forma_pago;
			$data["banco"]           =  $operacion->banco_pago;
			$data["numero_tarjeta"]  =  $operacion->numero_tarjeta;
			$data["cuenta "]         =  $operacion->banco_pago;
			$data["fecha_pago"]      =  $operacion->fecha_movimiento;
			$data["abono"]           =  $operacion->abono;
			$data["operacion"]       =  $operacion->operacion;
			$data["id_venta"]        =  $id_venta;


			$this->savepago2($data);
			
		}
		// $cargos = $this->Cobranza_model->getcargos($id_venta);
		// foreach ($cargos as  $cargo) {
		// 	echo json_encode($cargo->recibo)."<br>";
		// }
		// $operaciones = $this->Cobranza_model->getoperaciones2($id_venta);

		// foreach ($operaciones as  $value) {

		// 	$operacion = $this->Cobranza_model->getoperaciondetalle($value->operacion, $id_venta);
			
		// 	foreach ($operacion as  $value2) {

		// 		//echo json_encode($value2)."<br>";
		// 		// $abono = $value2->abono;
		// 		 $recibo = $this->Cobranza_model->getrecibo($value2->recibo, $id_venta);

		// 		 echo json_encode($recibo)."<br>";
		// 		// if ($abono > $recibo->cargo) {
		// 		// 	$monto_abono = $recibo->cargo;
		// 		// }else{
		// 		// 	$monto_abono = $abono;
		// 		// }

		// 		// echo $monto_abono."<br>";
				
		// 	}		

		// 	echo "<br>";		
		// }
	}


	public function savepago2($data = array())
	{
		if ($data["mes"] == 0) {
			$concepto = "PAGO DE ENGANCHE";
		}else{
			$concepto = "PAGO DE MENSUALIDAD";
		}

		$abono_sig = 0;
		if ($data["abono"] > $data["monto"]) {
			$saldo_restante = 0;	
			$abono_sig = round($data["abono"] - $data["monto"], 2);
			$monto_pago = $data["monto"];
			
		}else{
			$saldo_restante = round($data["monto"] - $data["abono"], 2);
			$abono_sig = 0;	
			$monto_pago = $data["abono"];
		}

		

		if ($saldo_restante == 0) {
			$status_recibo = 1;
		}else{
			$status_recibo = 0;
		}



		$update = array(
					  'abono'             => $monto_pago,
					  'saldo'             => $saldo_restante,
					  'status'            => $status_recibo);


		$this->Cobranza_model->update_recibo($update, $data["id_venta"]);

		echo $concepto."<br>";
		
	}


	



	


	public function deleteComprobante($id)
	{
		$this->Cobranza_model->deleteComprobante($id);
		echo json_encode("Ok");
	}



	public function UpdateComprobantesRecibos()
	{
		$recibos = $this->Cobranza_model->GetComprobantesRecibos();

		foreach ($recibos as $key => $comprobante) {
			$this->Cobranza_model->insertComprobanteVenta($comprobante->id, $comprobante->id_cotizacion, $comprobante->file_comprobante);
		}
	}

	/*
	*
	*/

}

/* End of file tasaCambio.php */
/* Location: ./application/controllers/tasaCambio.php */
	
