<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reservaciones extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //$this->load->database();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('Membresia_model');
        $this->load->model('ClientePagador_model');
        $this->load->model('Usuarios_model');
        $this->load->model('Menu_model');
        $this->load->model('Paquetes_model');
        $this->load->model('Jornadas_model');
        $this->load->model('Reservaciones_model');

        //--
        $this->load->helper('consumir_rest');
        $this->load->helper('organizar_sepomex');
        $this->load->helper('array_push_assoc');
        //--
        if (!$this->session->userdata("login")) {
          redirect(base_url()."admin");
        }
    }
    /*
    *   Metodo que arma el index...
    */
    public function index(){
        //--Cambio con servicio ag2
        //die('Error 955: Взяв под свой контроль свою команду ....');
        $data['planes'] = $this->Paquetes_model->listado_planes();
        //----
        $datos['permiso'] = $this->Menu_model->verificar_permiso_vista('reservaciones', $this->session-> userdata('id_rol'));
        $datos['breadcrumbs'] = $this->Menu_model->breadcrumbs('reservaciones');
        //$datos['actividadesEconomicas'] = $this->ClientePagador_model->armarSelect('actividadEconomica');
        $datos['membresia'] = $this->Reservaciones_model->listado_membresia();

        $datos['salas'] = $this->Reservaciones_model->listado_salas();

        $datos["configuracion"] = $this->Reservaciones_model->consultar_configuracion();
        //var_dump($datos['salas']);die('');
        //$datos['actividadesEconomicas'] = $this->armarSelect('actividadEconomica');
             
        $data['modulos'] = $this->Menu_model->modulos();

        $data['modulos'] = (array)$data['modulos'];

        $data['vistas'] = $this->Menu_model->vistas($this->session-> userdata('id_rol'));

        $data['modulo_user'] = [];
        foreach ($data['modulos'] as $modulo) {
            foreach ($data['vistas'] as $vista) {
                if((string)$modulo["_id"]->{'$id'} == (string)$vista->id_modulo_vista){
                  $data["modulo_user"][] = $modulo["_id"]->{'$id'};
                }
            }
          }


          $ids = array_unique($data['modulo_user']);
          $data['modulos_enconctrados'] = [];
          foreach ($ids as $value) {
            $data['modulos_enconctrados'][] = $this->Menu_model->modulosbyid($value);
          } 

         

        $oneDim = array();
        foreach($data['modulos_enconctrados'] as $i) {
          $oneDim[] = $i[0];
        }

        $datos["id_membresia"] = "";
        $data['modulos_vistas'] = $oneDim;
        $this->load->view('cpanel/header');
        $this->load->view('cpanel/menu', $data);
        $this->load->view('catalogo/Reservaciones/index', $datos);
        $this->load->view('cpanel/footer');
    }
   
    /*
    *   Metodo para realizar la consulta de planes
    */
    public function consultarInfoSalas(){
        $id_sala = $this->input->post("id_sala");
        $salas = $this->Reservaciones_model->buscarSalas($id_sala);
        echo(json_encode($salas));
    }
    /*
    *   calcularHorasPagar
    */
     public function calcularHorasPagar(){

        $horas_contratadas =  $this->input->post("horas_contratadas");
        $horas_consumidas =  $this->input->post("horas_consumidas");
        $min_tolerancia =  $this->input->post("min_tolerancia");
        //paso 1: Resto las horas contratadas - las horas consumidas
        $fecha1 = new DateTime($horas_contratadas);//fecha inicial
        $fecha2 = new DateTime($horas_consumidas);//fecha de cierre
        //---------------------------------------------------------------
        //#Calculo de horas consumidas    
        $arreglo_fecha_cons =  explode(":",$horas_consumidas);
        //Llevo tiempo consumido a minutos...
        $segundosCons = $arreglo_fecha_cons[2]/3600;
        $minutosCons = $arreglo_fecha_cons[1]/60;
        $horasCons = $arreglo_fecha_cons[0]+$minutosCons+$segundosCons;
        //#Calculo de horas contratadas    
        $arreglo_fecha_cont =  explode(":",$horas_contratadas);
        //Llevo tiempo consumido a minutos...
        $segundosCont = $arreglo_fecha_cont[2]/3600;
        $minutosCont = $arreglo_fecha_cont[1]/60;
        $horasCont = $arreglo_fecha_cont[0]+$minutosCont+$segundosCont;
        //---------------------------------------------------------------
        #Caso 1: Si las horas contratadas son iguales a las consumidas...    
        if($fecha1==$fecha2){
            $respuesta = array("mensajes"=>"Se procesó su salida","opcion"=>2,"minutos"=>"","horas"=>"");
            $horasTotal = $horasCont;
        }
        #Caso 2: Si las horas contratadas son mayores a las consumidas  
        else if($fecha1>$fecha2){
            // Evaluo, si los minutos obtenidos son mayor o igual a los minutos de tolerancia debe pagar una hora más....
            /*$minutos = round($minutos);
            if($minutos>$min_tolerancia){
                 $respuesta = array("mensajes"=>"Se procesó su salida, por exceder los minutos de tolerancia(".$min_tolerancia."), transcurrieron ".$minutos." Min.  Debe cancelar el valor del tiempo consumido","opcion"=>1,"minutos"=>$minutos);
            }else{
                $respuesta = array("mensajes"=>"Se procesó su salida","opcion"=>2,"minutos"=>$minutos,"tolerancia"=>$min_tolerancia);
            }*/
            $horasTotal = $horasCont;
            //----------------------------------------------------------------------------
            $respuesta = array("mensajes"=>"Se procesó su salida","opcion"=>2,"minutos"=>"","tolerancia"=>$min_tolerancia);
            //----------------------------------------------------------------------------
        }
        # Caso 3: Si las horas consumidas son mayores a las contratadas
        else if($fecha2>$fecha1){
            #Calcular el exceso...
            $intervalo2 =$fecha2->diff($fecha1);
            #El resultado es llevado a minutos
            $resultado_minutos_exceso = $intervalo2->format('%H:%i:%s');
            $arreglo_fecha2 =  explode(":",$resultado_minutos_exceso);
            //Llevo todo a minutos...
            $horas2 = $arreglo_fecha2[0]*60;
            $segundos2 = $arreglo_fecha2[2]/60;
            $minutos_exceso = $horas2+$arreglo_fecha2[1]+$segundos2;
            #Verificar si el exceso en minutos exceden los minutos de tolerancia
            $minutos_exceso = round($minutos_exceso);
            if($minutos_exceso>$min_tolerancia){
                #LLevo los minutos de exceso a hora
                $horas = $minutos_exceso/60;
                $horas = round($horas);
                if($horas<1){
                    $horas = 1;
                }
                $horasTotal = $horasCont+$horas;
                $respuesta = array("mensajes"=>"Se procesó su salida, por exceder los minutos de tolerancia(".$min_tolerancia."), transcurrieron ".$minutos_exceso." Min de exceso.  Debe cancelar el valor de ".$horas." Horas","opcion"=>1,"minutos"=>$resultado_minutos_exceso);
            }else{
                $respuesta = array("mensajes"=>"Se procesó su salida","opcion"=>2,"minutos"=>$minutos_exceso,"tolerancia"=>$min_tolerancia);
                $horasTotal = $horasCont;
            }
        }    
        //---------------------------------------------------------------------------------
        #Bloque para generar el recibo segun las horas consumidas....
        $id_reservaciones = $this->input->post("id_reservaciones");
        
        $id_membresia = $this->input->post("id_membresia");
        
        //$monto =  $this->input->post("monto");
        $monto = str_replace(",","",$this->input->post("monto"));

        $monto_total = $monto*$horasTotal;
        /*var_dump($monto);echo "<br>";
        var_dump($horasTotal);echo "<br>";
        var_dump($monto_total);echo "<br>";*/
        if($monto_total>0){
            $fecha2 = Date("d-m-Y");
            $concepto = "RESERVACIÓN ".$fecha2;
            $recibos = $this->Reservaciones_model->generarRecibos($id_reservaciones,$id_membresia,$monto_total,$concepto);

        }
            
        //---------------------------------------------------------------------------------
        echo(json_encode($respuesta));
        
        //--------------------------------------------------------------------------------
    }
    /*
    *
    */
    public function listado_reservaciones(){
        $listado = [];
        $listado2 = [];
        $listado = $this->Reservaciones_model->listado_reservaciones();
        foreach ($listado as $value) {
            $arreglo_data = $value;
            /*$grupo_empresarial = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["id_grupo_empresarial"]));
            ($value["id_grupo_empresarial"]!="") ? $arreglo_data["grupo_empresarial"] = $grupo_empresarial->data[0]->nombre_lista_valor:$arreglo_data["grupo_empresarial"] = "";*/
                       
            $listado2[] = $arreglo_data;
        }
        echo json_encode($listado2);
    } 
    /*
    *   Listado reservaciones todas
    */   
    public function listado_reservaciones_todas(){
        $listado = [];
        $listado2 = [];
        $listado = $this->Reservaciones_model->listado_reservaciones_todas();
        foreach ($listado as $value) {
            $arreglo_data = $value;
            /*$grupo_empresarial = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["id_grupo_empresarial"]));
            ($value["id_grupo_empresarial"]!="") ? $arreglo_data["grupo_empresarial"] = $grupo_empresarial->data[0]->nombre_lista_valor:$arreglo_data["grupo_empresarial"] = "";*/
                       
            $listado2[] = $arreglo_data;
        }
        echo json_encode($listado2);
    } 
    /*
    *   Listado reservaciones todas
    */   
    public function listado_reservaciones_filtros(){
        $listado = [];
        $listado2 = [];
        var_dump($this->input->post());die('');
        $listado = $this->Reservaciones_model->listado_reservaciones_todas();
        foreach ($listado as $value) {
            $arreglo_data = $value;
            /*$grupo_empresarial = consumir_rest('Lista_Valor','consultar', array('id_lista_valor'=>$value["id_grupo_empresarial"]));
            ($value["id_grupo_empresarial"]!="") ? $arreglo_data["grupo_empresarial"] = $grupo_empresarial->data[0]->nombre_lista_valor:$arreglo_data["grupo_empresarial"] = "";*/
                       
            $listado2[] = $arreglo_data;
        }
        echo json_encode($listado2);
    } 
    /*
    *   Metodo que registra la membresia
    */
    public function registrar_reservaciones(){

        $fecha_auditoria = new MongoDB\BSON\UTCDateTime();
        
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $formulario = $this->input->post();
        
        $fecha_hora_inicio = $formulario["fecha_resevacion_registrar"]." ".$formulario["hora_inicio_reservacion_registrar"].":00";

        $fecha_hora_fin = $formulario["fecha_resevacion_registrar"]." ".$formulario["hora_fin_reservacion_registrar"].":00";
        
        //var_dump($formulario["fecha_resevacion_registrar"]);
        /*var_dump($fecha_hora_inicio);
        var_dump($fecha_hora_fin);
        die('');*/
        
        /*$fecha_ini = new MongoDB\BSON\UTCDatetime(strtotime($fecha_hora_inicio)*1000);

        $fecha_fini = new MongoDB\BSON\UTCDatetime(strtotime($fecha_hora_fin)*1000);*/

        /*$fecha_ini = new MongoDB\BSON\UTCDateTime(strtotime($fecha_hora_inicio)*1000);

        $fecha_fini = new MongoDB\BSON\UTCDateTime(strtotime($fecha_hora_fin)*1000);*/
        
        //Hora inicio
        $fecha_ini = strtotime($fecha_hora_inicio);
        $fecha_fini = strtotime($fecha_hora_fin);
        /*$fecha_ini = new MongoDB\BSON\UTCDateTime((new DateTime($fecha_hora_inicio))->getTimestamp()*1000);*/

        //Hora fin
        /*$fecha_fini = new MongoDB\BSON\UTCDateTime((new DateTime($fecha_hora_fin))->getTimestamp()*1000); */

        /*var_dump($fecha_ini);
        var_dump($fecha_fini);die('');*/
        /*$fecha_ini = new MongoDB\BSON\UTCDatetime($fecha_hora_inicio);

        $fecha_fini = new MongoDB\BSON\UTCDatetime($fecha_hora_fin);*/
                
        $fecha_inicial_validacion = strtotime($formulario["hora_inicio_reservacion_registrar"].":00");

        $fecha_final_validacion = strtotime($formulario["hora_fin_reservacion_registrar"].":00");

        $id_membresia = (isset($formulario["cliente_jornada_registrar"]))? $formulario["cliente_jornada_registrar"]: "";

        //---
        #Modificacion 31052019: consulto el numero de renovacion
        $numero_renovacion = $this->Reservaciones_model->consultarNrenovacion($id_membresia );
        //var_dump($numero_renovacion);die('');
        //---

        $id_sala = (isset($formulario["sala_registrar"]))? $formulario["sala_registrar"]: "";
     
        $fecha = strtotime($formulario["fecha_resevacion_registrar"])*1000;

        $numero_reservacion = $this->Reservaciones_model->obtener_numero_reservacion();

        $precio = str_replace(',', '',$formulario["precio_registrar"]);
        //-----------------------------------------------------------------------------
        //Bloque de validacion de fechas...
        // Validacion #1
        if($fecha_hora_inicio>$fecha_hora_fin){
            echo "<span>La hora fin no puede ser mayor a la hora inicio !</span>"; // envio de mensaje
            die('');
        }
        // Validacion #2
        if($fecha_hora_inicio>$fecha_hora_fin){
            echo "<span>La hora fin no puede ser mayor a la hora inicio !</span>"; // envio de mensaje
            die('');
        }
        // Validacion #3
        if($fecha_hora_inicio==$fecha_hora_fin){
            echo "<span>La hora fin no puede ser igual a la hora inicio !</span>"; // envio de mensaje
            die('');
        }
        //-----------------------------------------------------------------------------
        $this->reglas_validacion();
        
        $this->mensajes_reglas();

        if($this->form_validation->run() == true){
            $data = array(
                          'n_reservaciones' => $numero_reservacion,
                          'id_membresia' => $id_membresia,
                          'numero_renovacion'=>$numero_renovacion,
                          'id_servicio_sala' => $id_sala,
                          'hora_inicio' =>  $fecha_ini,
                          'hora_fin' =>  $fecha_fini,
                          'fecha_inicial_validacion' => $fecha_inicial_validacion,
                          'fecha_final_validacion' => $fecha_final_validacion,
                          'hora_ingreso' =>  '',
                          'hora_salida' =>  'Sin salir',
                          'precio'=>$precio,
                          'fecha' => $this->mongo_db->date($fecha),
                          'cancelacion' => '',
                          'motivo_cancelacion' => '',
                          'observacion' => '',
                          'condicion' => 'RESERVADA',
                          'status' => true,
                          'eliminado' => false,
                          'auditoria' => [array(
                                                    "cod_user" => $id_usuario,
                                                    "nomuser" => $this->session->userdata('nombre'),
                                                    "fecha" => $fecha_auditoria,
                                                    "accion" => "Nuevo registro reservaciones",
                                                    "operacion" => ""
                                                )]
            );
            //var_dump($data);die('');
          $this->Reservaciones_model->registrar_reservaciones($data);
        }else{
             // enviar los errores
            echo validation_errors();
        }
        //----------------------------------------------------------------------
    }
    /*
    * Consulta de datos de membresia
    */
    public function consultarMembresia(){
        $formulario = $this->input->post();
        
        $membresia = $this->Reservaciones_model->listado_membresia_filtro($formulario["id_membresia"]);
        echo(json_encode($membresia));
    }
    /*
    *   Marcar Ingreso
    */
    public function IngresarReservaciones(){
        $formulario = $this->input->post();
        $fecha_hora = date("Y-m-d H:i:s");
        //var_dump($fecha_hora);die('');
        //$fecha =  new MongoDB\BSON\UTCDatetime(strtotime($fecha_hora)*1000);
        $fecha = (integer)strtotime($fecha_hora);
        //$fecha = new MongoDB\BSON\UTCDateTime(new \DateTime($fecha_hora));
        //var_dump($fecha);
        //var_dump($fecha_hora);
        // die('');
        #valido que el ingreso sea en una hora mayor o igual a la reservada...
        $validar_ingreso = $this->Reservaciones_model->validar_fecha_ingreso($formulario["id_reservaciones"],$fecha);
        if($validar_ingreso==0){
            echo "<span>Aun no puede ingresar a la sala reservada !</span>"; // envio de mensaje exitoso ".$fecha_hora."
            die('');
        }
        #valido que el ingreso no sea mayor a la hora fin de la reservacion...
        $validar_ingreso_fin = $this->Reservaciones_model->validar_fecha_ingreso_fin($formulario["id_reservaciones"],$fecha);
        if($validar_ingreso_fin>0){
            echo "<span>No puede ingresar a la sala reservada, ya que su tiempo expiró !</span>"; // envio de mensaje exitoso
            die('');
        }
        #---
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $data = array(
                        'hora_ingreso' =>  $fecha,
                        'condicion' => 'REGISTRADA',
                    );
        //var_dump($data);die('');
        $this->Reservaciones_model->marcarReservaciones($formulario["id_reservaciones"],$data);
        echo json_encode("<span>Se realizado el marcaje del ingreso a la reservación !</span>"); // envio de mensaje exitoso
    }
    /*
    *   
    */
    /*
    *   Marcar Salida
    */
    public function marcarSalida(){
        $fecha_hora = date("Y-m-d H:i:s");
        //$fecha =  new MongoDB\BSON\UTCDatetime(strtotime($fecha_hora)*1000);
        $fecha = (integer)strtotime($fecha_hora);
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $formulario = $this->input->post();
        $data = array(
                        'hora_salida' =>  $fecha,
                        'condicion' => 'LIBERADA',
                    );
        //var_dump($data);die('');
        $this->Reservaciones_model->marcarReservaciones($formulario["id_reservaciones"],$data);
        echo json_encode("<span>Se realizado el marcaje de la salida a la reservación !</span>"); // envio de mensaje exitoso
    }
    /*
    *   Metodo cancelar reservaciones
    */
    public function cancelar_reservaciones(){
        $formulario = $this->input->post();
        //--Consulto si muestro la cancelacion...
        //$mensaje_cancelar = $this->calcularHorasCancelar($formulario["id_reservaciones"]);
        //var_dump($formulario);die('');
        $data = array(     
                            'cancelacion' => true,
                            'motivo_cancelacion' => $formulario['motivo'],
                            'condicion' => 'CANCELADA',
                            'status' => false
        );
        $this->Reservaciones_model->cancelarReservaciones($formulario["id_reservaciones"],$data);
        echo json_encode("<span>Se realizó la cancelación de la reservación !</span>"); // envio
    }
    /*
    *   Calcular horas al cancelar
    */
    public function calcularHorasCancelar(){
        $formulario = $this->input->post();
        $id_reservaciones = $formulario["id_reservaciones"];
        $min_tolerancia = $formulario["min_tolerancia"];
        //-Consulto hora de ingreso
        /*$rs_horas_inicio = $this->Reservaciones_model->consultarHorasInicio($id_reservaciones);
        $fecha_hora_inicio = new DateTime($rs_horas_inicio);*/
        
        $datos_reservaciones = $this->Reservaciones_model->consultarDatosReservaciones($id_reservaciones);

        $id_membresia = $datos_reservaciones["id_membresia"];

        $monto_total = $datos_reservaciones["monto_total"];
        
        //$recibos = $this->Reservaciones_model->generarRecibos($id_reservaciones,$id_membresia,$monto_total);
        
        $fecha_hora_inicio = $this->Reservaciones_model->consultarHorasInicio($id_reservaciones);
        
        $hoy = date("Y-m-d H:i:s");
        
        $fecha_hora_actual = new DateTime($hoy);
        /*var_dump($fecha_hora_actual);
        var_dump($fecha_hora_inicio);*/

        if($fecha_hora_actual>$fecha_hora_inicio){
        //-----------------------------------------------------
            $intervalo = $fecha_hora_actual->diff($fecha_hora_inicio);
            $fecha_hora_resultado = $intervalo->format('%H:%i:%s');
            $arreglo_fecha =  explode(":",$fecha_hora_resultado);
            //Llevo todo a minutos
            $horas = $arreglo_fecha[0]*60;
            $segundos = $arreglo_fecha[2]/60;
            $minutos = $horas+$arreglo_fecha[1]+$segundos;
            /*var_dump($horas);
            var_dump($arreglo_fecha[1]);
            var_dump($segundos);
            var_dump($minutos);
            var_dump($min_tolerancia);*/
            /*var_dump($fecha_hora_resultado);
            die(''); */
            if($minutos>$min_tolerancia){
                $minutos = round($minutos);
                $respuesta = array("mensajes"=>"Se procesó su cancelación, por exceder los minutos de tolerancia(".$min_tolerancia."), transcurrieron ".$minutos." Min.  Debe cancelar el valor de una hora de la sala reservada","opcion"=>1,"minutos"=>$minutos);
                //--Si se exceden los minutos de tolerancia se procede a cancelar el valor de una hora de reservacion
                $fecha2 = Date("d-m-Y");
                $concepto = "RESERVACIÓN CANCELADA ".$fecha2;
                $recibos = $this->Reservaciones_model->generarRecibos($id_reservaciones,$id_membresia,$monto_total,$concepto);

            }else{
                $respuesta = array("mensajes"=>"Se procesó su cancelación","opcion"=>1,"minutos"=>$minutos);
            }
        //-----------------------------------------------------   
        }else{
            $respuesta = array("mensajes"=>"Se procesó su cancelación","opcion"=>1,"minutos"=>"");
        }
        
        echo(json_encode($respuesta));

    }
    /*
    *
    */
    public function mensajes_reglas(){
      $this->form_validation->set_message('required', 'El campo %s es obligatorio');
      $this->form_validation->set_message('numeric', 'El campo %s debe poseer solo números');
    }

    public function reglas_validacion(){
        $this->form_validation->set_rules('cliente_jornada_registrar','Identificación (Prospecto/CLiente)','required');
        $this->form_validation->set_rules('sala_registrar','Sala','required');
        $this->form_validation->set_rules('fecha_resevacion_registrar','Fecha','required');
        $this->form_validation->set_rules('hora_inicio_reservacion_registrar','Hora Inicio','required');
        $this->form_validation->set_rules('hora_fin_reservacion_registrar','Hora Fin','required');
    }
    
    
    /*
    *   Status Membresia
    */
    public function status_reservaciones(){
        $id = $this->input->post('id');
        $status = $this->input->post('status'); 
        $this->Reservaciones_model->status($id,$status, 'reservaciones');
        echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
    }
    /*
    * Eliminar membresia
    */
    public function eliminar (){
        $id = $this->input->post('id');
        //--Verifico que el cliente no tenga prospecto...
        /*$prospecto = $this->Membresia_model->buscarProspecto($id);
        if (count($prospecto)>0){
          echo ("<span>El cliente NO se puede eliminar ya que tiene un prospecto asociado!</span>");
          die('');
        }
        //---
        $consulta = $this->Membresia_model->consultaCarteraCliente($id);
        if (isset($consulta)){
            echo ("<span>El Cliente NO se puede eliminar ya que tiene un Vendedor en cartera de cliente asociado!</span>");
        }else{
            $this->ClientePagador_model->eliminar($id, 'cliente');
        }*/
        $this->Membresia_model->eliminar($id, 'membresia');
    }
    /*
    *   Eliminar multiple
    */
    public function eliminar_multiple(){
        $this->Membresia_model->eliminar_multiple($this->input->post('id'));
    }
    /*
    * Status multiple
    */
    public function status_multiple(){
        $this->Reservaciones_model->status_multiple_reservaciones($this->input->post('id'), $this->input->post('status'));
        echo json_encode("<span>Cambios realizados exitosamente!</span>"); // envio de mensaje exitoso
    }
    /*----------------------------------------------------------------------------------------*/
    
    
   
    
   /*---------------------------------------------------------------------------------------*/
}      