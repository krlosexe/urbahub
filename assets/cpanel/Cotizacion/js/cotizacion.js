$(document).ready(function(){
	elegirFecha('.fecha');
    telefonoInput('.telefono');
	listar();
	registrar_cotizacion();
	aprobar_cotizacion();
	actualizar_cotizacion();
	verificarRadio();
	aplicarCotizacion();
	var busqueda = false;
	$(".moralf").removeAttr("required")	
});
/* ------------------------------------------------------------------------------ */
	/*
	*	Funcion para nuevo registro
	*/
	/* ------------------------------------------------------------------------------- */
	function nuevoRegistro(cuadroOcultar, cuadroMostrar){
		$("[type='file']").fileinput('destroy');
		$("#alertas").css("display", "none");
		cuadros("#cuadro1", "#cuadro2");
		
	
		$("#personaMoral").hide();
		$("#personaFisica").show();
		
		$(".moralf").removeAttr("required")	
		$(".fisicaf").attr("required")	
		
		$("#form_cotizacion_registrar")[0].reset();
		$("#form_cotizacion_actualizar")[0].reset();

		$("#rfc_cotizacion_registrar_fisica").select2();
		$("#rfc_cotizacion_registrar_moral").select2();
		$("#id_cotizacion").val("");
        //Doy valor a las cajas para el envio por POST
		$("#imagen_registrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/default-img.png');
		$("#nombre_cliente").focus();
		$("#tableRegistrarMoral tbody,#tableRegistrarFisica tbody,#tableActualizarMoral tbody,#tableActualizarFisica tbody").html("");

		GetPlanes("#plan_cotizacion_registrar_fisica", true)

		$("#servicios_cotizacion, #tableRegistrarServiceFisica").css("display", "none")
		$("#membresia").val("S");

		$("#plan-membresia").css("display", "block")
		$("#servicios_cotizacion").css("display", "none")


		/*------------------------------------------------*/
	}
	/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para cargar los datos de la base de datos en la tabla.
	*/
	function listar(cuadro){
		$('#tabla tbody').off('click');
		cuadros(cuadro, "#cuadro1");
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		var table=$("#tabla").DataTable({
			"destroy":true,
			"stateSave": true,
			"serverSide":false,
			"ajax":{
				"method":"POST",
				"url": url + "Cotizacion/listado_cotizacion",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_cotizacion",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data": null,
					render : function(data, type, row) {
						var botones = "";
						var url_jornada = base_url+'Jornadas/from_to_membresia/'+data.id_membresia

						if(consultar == 0)
							botones += "<span class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						
						if((actualizar == 0)&&(data.condicion!="APROBADO")&&(data.condicion!="CANCELADO")&&(data.condicion!="VENTAS"))
							//botones += "<span id='editar 'class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";

						if((actualizar == 0)&&(data.condicion!="APROBADO")&&(data.condicion!="CANCELADO")&&(data.condicion!="VENTAS"))
							botones += "<span class='aprobar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Aprobar'><i class='fa fa-check' style='margin-bottom:5px'></i></span> ";
												
						if((borrar == 0)&&(!data.tiene_correo)&&(data.condicion!="APROBADO")&&(data.condicion!="CANCELADO")&&(data.condicion!="VENTAS"))
		              		botones += "<span class='cancelar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Cancelar'><i class='fa fa-times' style='margin-bottom:5px'></i></span>";
			          	
						if((actualizar == 0)&&(data.tiene_cobranza==true)&&(data.condicion!="VENTAS"))
							botones += "<span class='aceptar btn btn-xs btn-success waves-effect' data-toggle='tooltip' title='Aceptar'><i class='fa fa-money' style='margin-bottom:5px'></i></span> ";
						
						if(data.condicion=="VENTAS")
							botones += "<span class='ver_documentos btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Ver Documentos'><i class='fa fa-file-word-o' style='margin-bottom:5px'></i></span> ";
	
						//-------------------------------	
						return botones;
		          	}
				},
				{"data":"numero_cotizacion"},
				{"data":"datos_clientes"},
				{"data":"datos_vendores"},
				{"data":"condicion"},
				{"data":"fec_regins",
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						return "";
	          		}
				},
				{"data":"fec_regins",
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						return "";
	          		}
				},
				{"data":"fec_regins",
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						return "";
	          		}
				},
				{"data":"fec_regins",
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						return cambiarFormatoFecha(fecha[0]);
	          		}
				},
				{"data":"correo_usuario"},
			],
			"language": idioma_espanol,
			"dom": 'Bfrtip',
			"responsive": true,
			"buttons":[
				'copy', 'csv', 'excel', 'pdf', 'print'
			]
		});
		ver("#tabla tbody", table);
		editar("#tabla tbody", table);
		cancelar("#tabla tbody", table);
		aprobar("#tabla tbody", table);
		aceptar("#tabla tbody", table);
		ver_documentos("#tabla tbody", table);
	}
/* ------------------------------------------------------------------------------- */

	
	function enviarFormularioCliente(form, controlador, cuadro){
        $(form).submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
            var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
            console.log(formData);
            var method = $(this).attr('method'); //obtiene el method del formulario
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url:url+controlador,
                type:method,
                dataType:'JSON',
                data:formData,
                cache:false,
                contentType:false,
                processData:false,
                beforeSend: function(){
                    mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                },
                error: function (repuesta) {
                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    var errores=repuesta.responseText;
                    if(errores!="")
                        mensajes('danger', errores);
                    else
                        mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
                },
                success: function(respuesta){

                    if (respuesta.success == false) {
                         mensajes('danger', respuesta.message);
                         $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    }else if (respuesta.success == true){
                        $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                        mensajes('success', respuesta);
                        if(cuadro!="")
                            listar(cuadro);
                    }else{
                    	$('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                        mensajes('success', respuesta);
                    }
                }
            });
        });
    }
/* ------------------------------------------------------------------------------- */
function registrar_cotizacion(){
	enviarFormulario("#form_cotizacion_registrar", 'Cotizacion/registrar_cotizacion', '#cuadro2');
}

/* ------------------------------------------------------------------------------- */
function aprobar_cotizacion(){
	enviarFormulario("#form_aprobar", 'Cotizacion/aprobar_cotizacion', '#cuadro5');
}

/* ------------------------------------------------------------------------------- */
function actualizar_cotizacion(){
	enviarFormulario("#form_cotizacion_actualizar", 'Cotizacion/actualizar_cotizacion', '#cuadro4');
}
/*---------------------------------------------------------------------------------*/
function imprimir_cotizacion(){
	var base_url = document.getElementById('ruta').value;
	$("#accion").val("imprimir");
	$('#form_cotizacion_actualizar').submit()
}
/* ------------------------------------------------------------------------------- */
/* 
	Funcion que muestra el cuadro4 para editar
*/
function editar(tbody, table){
	//verificarRadio()
	//cargar_elementos_select();
	//$("#form_cotizacion_actualizar")[0].reset();
	$('#fisica_actualizar').attr('checked', false)
	$('#moral_acualizar').attr('checked', false)		
	$("#form_cotizacion_registrar")[0].reset();
	$("#form_cotizacion_actualizar")[0].reset();
	base_url = document.getElementById('ruta').value;
	//url_imagen = base_url+'assets/cpanel/Membresia/images/'
	$(tbody).on("click", "span.editar", function(){
		var data = table.row( $(this).parents("tr") ).data();
		//----------------------------------------------------
		//Aqui doy lavorl al boton imprimir para que envie al metodo señalado...
		var url_imprimir = base_url+'Cotizacion/imprimir_cotizacion/'+data.id_cotizacion+"/imprimir"
		$("#btn_imprimir").attr("href",url_imprimir)

		var url_mail = base_url+'Cotizacion/sendventaemail/'+data.id_cotizacion+"/"
		$("#btn_mail").attr("href",url_mail)
		//Llenado de campos			
		
		$("#numero_cotizacionE").val(data.numero_cotizacion);
		$("#id_cotizacion_actualizar").val(data.id_cotizacion);
		$("#tipo_persona_actualizar").val(data.tipo_persona);	
		$("#alertas").css("display", "none");
		consultarPaquetes(data.plan,'actualizar',data.paquete);
		consultarPlan(data.paquete,data.plan,'actualizar')
		//--Persona fisica
		if (data.tipo_persona == "fisica"){ 
			$('#fisica_actualizar').attr('checked', true)
			$("#personaMoralE").hide();
			$("#personaFisicaE").show();
			$(".fisicaf").attr("required",true)	
			$(".moralf").removeAttr("required");
			//--Asigno valores
			$("#rfc_cotizacion_actualizar_fisica").val(data.identificador_prospecto_cliente);
			$("#id_vendedor_actualizar option[value='"+data.id_vendedor+"']").prop("selected",true);
			//$("#plan_cotizacion_actualizar_fisica option[value='"+data.id_plan+"']").prop("selected",true);
			//$("#vigencia_actualizar_fisica").val(data.vigencia);
			$("#cantidad_trabajadores_actualizar_fisica").val(data.cantidad_usuarios);

			
		}else		//Persona moral
		if (data.tipo_persona == "moral"){
			$('#moral_actualizar').attr('checked', true);
			$("#personaMoralE").show();
			$("#personaFisicaE").hide();
			$(".fisicaf").removeAttr("required");
			$(".moralf").attr("required", true)
			var id_cotizacion = data.id_cotizacion;
			
			//--Asigno valores
			$("#rfc_cotizacion_actualizar_moral").val(data.identificador_prospecto_cliente);
			$("#id_vendedor_moral_actualizar option[value='"+data.id_vendedor+"']").prop("selected",true);
			$("#plan_cotizacion_actualizar_moral option[value='"+data.id_plan+"']").prop("selected",true);
		//	$("#vigencia_actualizar_moral").val(data.vigencia);
			//$("#cantidad_trabajadores_actualizar_moral").val(data.cantidad_usuarios);

		
		}


		//--Asigno montos
			$("#monto_inscripcion_actualizar_fisica").val(data.monto_inscripcion);
			$("#monto_paquete_actualizar_fisica").val(data.monto_mensualidad_individual)
			$("#monto_total_paquete_actualizar_fisica").val(data.monto_mensualidad_total)
			$("#monto_total_actualizar_fisica").val(data.monto_total)
			//--Asigno montos ocultos
			$("#monto_inscripcion_actualizar_fisica_oculto").val(data.monto_inscripcion_oculto);
			$("#monto_paquete_actualizar_fisica_oculto").val(data.monto_mensualidad_individual_oculto)
			$("#monto_total_paquete_actualizar_fisica_oculto").val(data.monto_mensualidad_total_oculto)
			$("#monto_total_actualizar_fisica_oculto").val(data.monto_total_oculto)
		//--------------------------------------------------------------------------
		
		/*fech_i = data.fecha_inicio.date
		fechaIni = fech_i.split(" ");
		$("#fecha_inicioE").html(cambiarFormatoFecha(fechaIni[0]));*/
		

		cuadros('#cuadro1', '#cuadro4');
		/*alert("Cliente:"+data.identificador_prospecto_cliente);
		alert("TipoPersona:"+data.tipo_persona);*/
		consultarClienteRFCModificar(data.identificador_prospecto_cliente,data.tipo_persona);

		GetDataPlan(data.data_plan, "#tbodyActualizarFisica");
		/*
		*
		*/
	});
}
/*---------------------------------------------------------------------------------*/
   function verificarRadio(){
   	   	$("input[name=rad_tipoper]").change(function () {
		if($("#tipopersona input[id='moral']").is(':checked')){
			/*
			*	Pestana de trabajadores
			*/
			var id_membresia = $("#id_membresia").val();
			$("#personaMoral").show();
			$("#personaFisica").hide();
			$(".fisicaf").removeAttr("required")	
			$(".moralf").attr("required", true)
			$("#razon_social").focus()		
		}
		else{
			$(".pestana_datosTrabajadores").hide()
			$("#personaMoral").hide();
			$("#personaFisica").show();
			$(".moralf").removeAttr("required")
			$(".fisicaf").attr("required", true)
			$("#nombre_cliente").focus();	
		}
	});
	$("input[name=rad_tipoper_editar]").change(function () {
		if($("#tipopersona_editar input[id='moral_actualizar']").is(':checked')){
			//solo de prueba colocare una membresia estatica....
			var id_membresia = $("#id_membresia_actualizar").val();
			$(".fisicae").removeAttr("required")	
			$("#personaMoralE").show();
			$("#personaFisicaE").hide();
			///$(".moralf").attr("required", true)
			$("#razon_social").focus()		
		}
		else{
			$(".pestana_datosTrabajadoresE").hide()
			$("#personaMoral").hide();
			$("#personaFisica").show();
			$(".morale").removeAttr("required")	
		}
	});

	   }

/*
*	Funcion para realizar consulta del cliente
*/
function consultarCliente(){
	var tipo_per = $('input:radio[name=rad_tipoper]:checked').val()
	if(tipo_per=="fisica"){
		rfc_cliente = $("#rfc_cotizacion_registrar_fisica").val()
	}else{
		rfc_cliente = $("#rfc_cotizacion_registrar_moral").val()
	}
	if(rfc_cliente!=""){
		var form  = "#form_cotizacion_registrar"
		var controlador = "Cotizacion/consultarClientePagadorRfc"
	    //e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
	    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
	    var method = $(this).attr('method'); //obtiene el method del formulario
	    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
	    $.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:formData,
	        cache:false,
	        contentType:false,
	        processData:false,
	        beforeSend: function(){
	            mensajes('info', '<span>Buscando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
	        },
	        error: function (repuesta) {
	            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
	            var errores=repuesta.responseText;
	            if(errores!="")
	                mensajes('danger', errores);
	            else
	                mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
	        },
	        success: function(respuesta){
	        	$("#alertas").html('');
	            console.log(respuesta);
	            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
	            if(respuesta.length>0){
	            	if((respuesta[0]["tipo_cliente"]=="PROSPECTO")&&(respuesta[0]["fecha_nac_datos_personales"]=="")&&(respuesta[0]["curp_datos_personales"]=="")){
	            		//Si solo es prospecto...
	            		warning("Para registrar una membresia a esta persona, el mismo debe estar registrado como cliente desde prospecto");
	            		//--
	            	}else{//Si es un cliente
						//--
						if(tipo_per=="fisica"){
							//---------------------------------------------------------------------
							$("#nombre_fisica_registrar").val(respuesta[0]["nombre_datos_personales"]);
			            	$("#apellido_paterno_fisica_registrar").val(respuesta[0]["apellido_p_datos_personales"]);
		   	            	$("#apellido_materno_fisica_registrar").val(respuesta[0]["apellido_m_datos_personales"]);
		   	            	$("#fecha_nac_fisica_registrar").val(respuesta[0]["fecha_nac_datos_personales"]);
							$("#telefono_fisica_registrar").val(respuesta[0]["telefono_principal_contacto"]);
							$("#correo_fisica_registrar").val(respuesta[0]["correo_contacto"]);
							//---------------------------------------------------------------------
						}else if(tipo_per=="moral"){
							//---------------------------------------------------------------------
							$("#razon_social_moral_registrar").val(respuesta[0]["nombre_datos_personales"]);
							$("#correo_moral_registrar").val(respuesta[0]["correo_contacto"]);
							$("#telefono_moral_registrar").val(respuesta[0]["telefono_principal_contacto"]);
			         		//---------------------------------------------------------------------
						}
						//--Para mostrar la imagen del cliente
						if(respuesta[0]["imagenCliente"]!=""){
							$("#imagen_registrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/'+respuesta[0]["imagenCliente"]
						);
						}else{
							$("#imagen_registrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/default-img.png');
						}
						//---
	            	}
	            }else{
					mensajes('danger', "<span>No hay registros asociados al identificador consultado</span>"); 
					if(tipo_per=="fisica"){
						$("#rfc_cotizacion_registrar_fisica").val("").focus()
					}else{
						$("#rfc_cotizacion_registrar_moral").val("").focus()
					}

				}
			}	
		});	
	}else{
		warning('Debe ingresar el identificador de cliente/prospecto');
	}
	
}
/*
*	Verificar Blanco
*/
$("#rfc_cliente_registrar_fisica").keyup(function(e) {
    if($("#rfc_cliente_registrar_fisica").val()=="") {
			var plan = $("#plan_membresia_registrar").val()
			var serial_acceso =$("#serial_acceso_registrar_fisica").val();
			$("#form_membresia_registrar")[0].reset();
			$("#plan_membresia_registrar option[value='" + plan   + "']").prop("selected",true);
			$("#serial_acceso_registrar_fisica").val( serial_acceso)
	}	
});


function consultarClienteRFCModificar(rfc_cliente,tipo_per){
	if(rfc_cliente!=""){
		
		var controlador = "Cotizacion/consultarClientePagadorRfcModificar"
	    //e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
	    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    var method = $(this).attr('method'); //obtiene el method del formulario
	    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
	    $.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
                        "tipo_per":tipo_per,
                        "rfc_cliente":rfc_cliente
        	},
	        cache:false,
	        beforeSend: function(){
	            mensajes('info', '<span>Buscando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
	        },
	        error: function (repuesta) {
	            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
	            var errores=repuesta.responseText;
	            if(errores!="")
	                mensajes('danger', errores);
	            else
	                mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
	        },
	        success: function(respuesta){
	        	$("#alertas").html('');
	            console.log(respuesta);
	            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
	            if(respuesta.length>0){
					//--
					if(tipo_per=="fisica"){
						$("#nombre_fisica_actualizar").val(respuesta[0]["nombre_datos_personales"]);
		            	$("#apellido_paterno_fisica_actualizar").val(respuesta[0]["apellido_p_datos_personales"]);
	   	            	$("#apellido_materno_fisica_actualizar").val(respuesta[0]["apellido_m_datos_personales"]);
						$("#telefono_fisica_actualizar").val(respuesta[0]["telefono_principal_contacto"]);
						$("#correo_fisica_actualizar").val(respuesta[0]["correo_contacto"]);
		            	
			        }else if(tipo_per=="moral"){
						//---------------------------------------------------------------------
						$("#razon_social_actualizar").val(respuesta[0]["nombre_datos_personales"])
						$("#correo_moral_actualizar").val(respuesta[0]["correo_contacto"]);
						$("#telefono_moral_actualizar").val(respuesta[0]["telefono_principal_contacto"]);
		            
					}
					//--Para mostrar la imagen del cliente
					if(respuesta[0]["imagenCliente"]!=""){
						$("#imagen_actualizar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/'+respuesta[0]["imagenCliente"]
					);
					}else{
						$("#imagen_actualizar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/default-img.png');
					}
					//---
	            }else{
					mensajes('danger', "<span>No hay registros asociados al identificador consultado</span>"); 
					if(tipo_per=="fisica"){
						$("#rfc_cotizacion_actualizar_fisica").val("").focus()
					}else{
						$("#rfc_cotizacion_actualizar_moral").val("").focus()
					}
					$("#form_cotizacion_registrar")[0].reset()
				}
			}	
		});	
	}else{
		warning('Debe ingresar el identificador de cliente/prospecto');
	}
}
/*
*
*/
function consultarClienteRFCMostrar(rfc_cliente,tipo_per){
	if(rfc_cliente!=""){
		var controlador = "Cotizacion/consultarClientePagadorRfcModificar"
	    //e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
	    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    var method = $(this).attr('method'); //obtiene el method del formulario
	    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
	    $.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
                        "tipo_per":tipo_per,
                        "rfc_cliente":rfc_cliente
        	},
	        cache:false,
	        beforeSend: function(){
	            mensajes('info', '<span>Buscando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
	        },
	        error: function (repuesta) {
	            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
	            var errores=repuesta.responseText;
	            if(errores!="")
	                mensajes('danger', errores);
	            else
	                mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
	        },
	        success: function(respuesta){
	        	$("#alertas").html('');
	          
	            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
	            if(respuesta.length>0){
					//--
					if(tipo_per=="fisica"){
						$("#nombre_fisica_mostrar").val(respuesta[0]["nombre_datos_personales"]);
		            	$("#apellido_paterno_fisica_mostrar").val(respuesta[0]["apellido_p_datos_personales"]);
	   	            	$("#apellido_materno_fisica_mostrar").val(respuesta[0]["apellido_m_datos_personales"]);
						$("#telefono_fisica_mostrar").val(respuesta[0]["telefono_principal_contacto"]);
						$("#correo_fisica_mostrar").val(respuesta[0]["correo_contacto"]);
		            	
			        }else if(tipo_per=="moral"){
						//---------------------------------------------------------------------
						$("#razon_social_mostrar").val(respuesta[0]["nombre_datos_personales"])
						$("#correo_moral_mostrar").val(respuesta[0]["correo_contacto"]);
						$("#telefono_moral_mostrar").val(respuesta[0]["telefono_principal_contacto"]);
		            
					}
					//--Para mostrar la imagen del cliente
					if(respuesta[0]["imagenCliente"]!=""){
						$("#imagen_mostrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/'+respuesta[0]["imagenCliente"]
					);
					}else{
						$("#imagen_mostrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/default-img.png');
					}
					//---
	            }else{
					mensajes('danger', "<span>No hay registros asociados al identificador consultado</span>"); 
					if(tipo_per=="fisica"){
						$("#rfc_cotizacion_mostrar_fisica").val("").focus()
					}else{
						$("#rfc_cotizacion_mostrar_moral").val("").focus()
					}
					$("#form_cotizacion_mostrar")[0].reset()
				}
			}	
		});	
	}else{
		warning('Debe ingresar el identificador de cliente/prospecto');
	}
}
/***/
function limpiar_form_moral_montos(){
	$("#vigencia_actualizar_moral").val("");
	$("#monto_total_actualizar_moral").val("");
	$("#monto_paquete_actualizar_moral").val("");
	$("#monto_paquete_actualizar_moral_oculto").val("");
	$("#cantidad_trabajadores_actualizar_moral,#monto_total_paquete_actualizar_moral,#monto_total_paquete_actualizar_moral_oculto").val("")
	$("#tableActualizarMoral tbody").html("");
}
/***/
function limpiar_form_fisica_montosE(){
	$("#vigencia_actualizar_fisica").val("");
	$("#monto_total_actualizar_fisica").val("");
	$("#monto_paquete_actualizar_fisica").val("");
	$("#monto_paquete_actualizar_fisica_oculto").val("");
	//$("#tableActualizarFisica tbody").html("");
}
/*
*	Función para consultar planes
*/
function consultarPlan(paquete,id_plan,proceso){

	$("#tableMostrarFisica").css("display", "inline-block")
	$("#tableServiceMostrar").css("display", "none")


	var controlador = "Cotizacion/consultarPlanPaquetesTablas"
    var url=document.getElementById('ruta').value //obtiene la ruta del input hidden con la variable <?=base_url()?>
    var limpiar_moral = false
    var cambiar_valores_fisica = false
    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
    if(proceso=="guardar"){
    	//---Si es moral
    	var id_plan = $("#plan_cotizacion_registrar_fisica").val();
    }else if((proceso=="actualizar")&&(id_plan=="")){

		var id_plan = $("#plan_cotizacion_actualizar_fisica").val();
			cambiar_valores_fisica = true
    }
    /*else if(proceso=="mostrar")
    	var id_plan = $("#plan_cotizacion_mostrar").val();*/
    $.ajax({
        url:url+controlador,
        type:"POST",
        dataType:"JSON",
        data:{
                        "id_plan":id_plan,
                        "paquete":paquete
        },
        cache:false,
        beforeSend: function(){
            mensajes('info', '<span>Buscando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
        },
        error: function (repuesta) {
            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
            var errores=repuesta.responseText;
            if(errores!="")
                mensajes('danger', errores);
            else
                mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
        },
        success: function(respuesta){
        	$("#alertas").html('');
        	$('input[type="submit"]').removeAttr('disabled'); //activa el input submit
			//console.log(respuesta);
			if(proceso=="guardar"){
				if(respuesta.length>0){
					if(respuesta[0]["horas_jornadas"]=="0"){
						mensajes('danger', "<span>No puede seleccionar el paquete porque no tiene asociado el servicio horas de coworking!</span>");
						$('input[type="submit"]').attr('disabled');
					}else{
						//---
						/* Aqui muestro el resultado de la consulta*/
						//---
						$('input[type="submit"]').removeAttr('disabled');

						//---Si es moral
						
							tabla = "#tableRegistrarMoral"
							html = ""
							$("#vigencia_registrar_fisica").val(respuesta[0]["vigencia"]);
							//$("#vigencia_actualizar_fisica").val(respuesta[0]["vigencia"]);
							// $("#monto_total_registrar_moral").val(respuesta[0]["valor"]);
							// $("#monto_paquete_registrar_moral").val(respuesta[0]["valor"]);
							 $("#monto_paquete_registrar_fisica_oculto").val(respuesta[0]["valor_oculto"]);
							// $("#cantidad_trabajadores_moral,#monto_total_paquete_registrar_moral,#monto_total_paquete_registrar_moral_oculto").val("")
							//Nota: el caclulo en persona moral se realiza luego de colocar la cantidad de usuarios...
							//---armo la tabla de servicios
							servicios = respuesta[0]["servicios"]
							// $(tabla + " tbody").html("");
							// $.each(servicios, function( index, value ) {
							// //--
							// 	html += "<tr><td>" + value["codigo_servicios"] + "</td>";
							// 	html += "<td>"+value["disponible"]+"</td>";
							// 	html += "<td>"+value["titulo_servicios"]+"</td></tr>";
							// 	$(tabla + " tbody").append(html);
							// 	html =  "";
							// //-- 
							// });
					
						console.log(respuesta[0]);
					}
					
				}else{
					//---
					/* Coloco todo en blanco*/
				}
				
	  
			}else if(proceso=="actualizar"){
				//--
				if(respuesta.length>0){
					if(respuesta[0]["horas_jornadas"]=="0"){
						mensajes('danger', "<span>No puede seleccionar el paquete porque no tiene asociado el servicio horas de coworking!</span>");
						if($("#tipopersonaE input[id='moral_actualizar']").is(':checked')){
							limpiar_form_moral_montosE();
						}else{
							limpiar_form_fisica_montosE();
						}	
					}else{
						//---
						/* Aqui muestro el resultado de la consulta*/
						//---
						//---Si es moral
							if(paquete!=""){
								//--
								tabla = "#tableActualizarMoral"
								html = ""
								$("#vigencia_actualizar_fisica").val(respuesta[0]["vigencia"]);
								//$("#monto_total_actualizar_moral").val(respuesta[0]["valor"]);
								// $("#monto_paquete_actualizar_moral").val(respuesta[0]["valor"]);
								$("#monto_paquete_actualizar_fisica_oculto").val(respuesta[0]["valor_oculto"]);
								if(limpiar_moral)
									$("#cantidad_trabajadores_actualizar_moral,#monto_total_paquete_actualizar_moral,#monto_total_paquete_actualizar_moral_oculto").val("")

								//Nota: el caclulo en persona moral se realiza luego de colocar la cantidad de usuarios...
								//---armo la tabla de servicios
								servicios = respuesta[0]["servicios"]
								//$(tabla + " tbody").html("");
								$.each(servicios, function( index, value ) {
								//--
									html += "<tr><td>" + value["codigo_servicios"] + "</td>";
									html += "<td>"+value["disponible"]+"</td>";
									html += "<td>"+value["titulo_servicios"]+"</td></tr>";
									$(tabla + " tbody").append(html);
									html =  "";
								//-- 
								});
								//--
							}
					}
					
				}else{
					//---
					/* Coloco todo en blanco*/
				}
	            //--
			}
			else if(proceso=="mostrar"){
				//--
				if(respuesta.length>0){
					//---
					/* Aqui muestro el resultado de la consulta*/
					//---
					//---Si es moral
					tabla = "#tableMostrarFisica"
					html = ""
					if(cambiar_valores_fisica){
						//Si el consultarPlanes viene por valor del metodo actualizar no eentra en esta condicion, por el contrario si se pulso el select de planes si entra aqui ya que se deben actualizar las cantidades de los montos...
						$("#vigencia_mostrar_fisica").val(respuesta[0]["vigencia"]);
						$("#monto_paquete_mostrar_fisica").val(respuesta[0]["valor"]);
						$("#monto_paquete_mostrar_fisica_oculto").val(respuesta[0]["valor_oculto"]);
						//Sumo inscripcion + costo de paquete
						var monto_ins = $("#monto_inscripcion_mostrar_fisica_oculto").val();
						var monto_paquete = $("#monto_paquete_mostrar_fisica_oculto").val();
						monto_total =	parseFloat(monto_ins) + parseFloat(monto_paquete)
						
						$("#monto_total_mostrar_fisica_oculto").val(monto_total);
						$("#monto_total_mostrar_fisica").val(new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(monto_total))

					}						
					//---armo la tabla de servicios
					servicios = respuesta[0]["servicios"]
					//$(tabla + " tbody").html("");
					$.each(servicios, function( index, value ) {
					//--
						html += "<tr><td>" + value["codigo_servicios"] + "</td>";
						html += "<td>"+value["disponible"]+"</td>";
						html += "<td>"+value["titulo_servicios"]+"</td></tr>";
						//$(tabla + " tbody").append(html);
						html =  "";
					//-- 
					});
					console.log(respuesta[0]);

				}else{
					//---
					/* Coloco todo en blanco*/
				}
	            //--
			}
		}	
	});	
}


var count = 0;
function addPlan() {
	count++;
	var plan_name    = $("#plan_cotizacion_registrar_fisica option:selected").text();
	var paquete_name = $("#paquetes_cotizacion_registrar_fisica option:selected").text();

	var plan_id    = $("#plan_cotizacion_registrar_fisica option:selected").val();
	var paquete_id = $("#paquetes_cotizacion_registrar_fisica option:selected").val();

	var plazos     = $("#vigencia_registrar_fisica").val();

	var html = "";


	var input_plan_id    = "<input type='hidden' name='plan_id[]' value='"+plan_id+"'>"
	var input_paquete_id = "<input type='hidden' name='paquete_id[]' class='paquete_add' value='"+paquete_id+"'>"


	var select_plazos     = '<input type="text" value="'+plazos+'" name="plazos[]" class="form-control" placeholder="Plazos" readonly>'
	var catn_trabajadores = '<input type="text"  name="cant_trabajadores[]" class="form-control cant_trabajadores" placeholder="Cantidad de Trabajadores" required>'

	var btn_view   = "<span onclick='showModalServices(\"" + paquete_id + "\")' class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span>";

	var btn_delete = "<span class='btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='eliminar' onclick='deleteTr(\"" + "#tr_" + count + "\")'><i class='fa fa-times' style='margin-bottom:5px'></i></span>";


	var monto = parseFloat($("#monto_paquete_registrar_fisica_oculto").val())


	var monto_input = "<input type='hidden' class='monto_input' id='monto_tr' value='"+monto+"'>"

	var valid = true;


	$("#tableRegistrarFisica tbody tr").each(function(){
		var id_paquete = $(this).find(".paquete_add").val();
		if(id_paquete == paquete_id){
			valid = false;
		}
	});


	if(plan_id == "" || paquete_id == ""){
		warning("Todos los campos son requeridos");
		return false;
	}


	
	html += "<tr id='tr_"+count+"'>";
		html+= "<td>"+btn_view+" "+btn_delete+" "+monto_input+""+input_plan_id+input_paquete_id+"</td>";
		html+= "<td>"+plan_name+"</td>";
		html+= "<td>"+paquete_name+"</td>";
		html+= "<td>"+select_plazos+"</td>";
		html+= "<td>"+catn_trabajadores+"</td>";
		html+= "<td>"+number_format(monto, 2)+"</td>";
	html += "</tr>";


	if(valid){
		$("#tableRegistrarFisica").append(html);
		cantidadTrabajadoreSumar();
		sumar();
	}else{
		warning("El registro ya se encuentra agregado");
	}
}










var count_service = 0;
function addService() {
	count_service++;
	var service_name  = $("#services option:selected").text();
	var service_id    = $("#services option:selected").val();

	var monto_service    = parseFloat($("#value_service").val());
	var cantidad_service = $("#cantidad_service").val();

	
	var html = "";

	var input_service_id    = "<input type='hidden' name='service[]' value='"+service_id+"'>"
	var btn_delete = "<span class='btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='eliminar' onclick='deleteTr(\"" + "#tr_" + count_service + "\")'><i class='fa fa-times' style='margin-bottom:5px'></i></span>";


	var monto_input    = "<input type='hidden' name='monto_service[]' class='monto_input' value='"+monto_service+"'>"
	var cantidad_input = "<input type='hidden' name='cantidad_service[]' class='cantidad_input' value='"+cantidad_service+"'>"

	var valid = true;

	// $("#tableRegistrarFisica tbody tr").each(function(){
	// 	var id_paquete = $(this).find(".paquete_add").val();
	// 	if(id_paquete == paquete_id){
	// 		valid = false;
	// 	}
	// });


	// if(plan_id == "" || paquete_id == ""){
	// 	warning("Todos los campos son requeridos");
	// 	return false;
	// }


	
	html += "<tr id='tr_"+count_service+"'>";
		html+= "<td>"+btn_delete+" "+monto_input+""+input_service_id+cantidad_input+"</td>";
		html+= "<td>"+service_name+"</td>";
		html+= "<td>"+number_format(monto_service, 2)+"</td>";
		html+= "<td>"+cantidad_service+"</td>";
		html += "<td>"+number_format((inNum(cantidad_service) * inNum(monto_service)), 2)+"</td>"
		
	html += "</tr>";


	if(valid){
		$("#tableRegistrarServiceFisica tbody").append(html);
		
		sumarService();
	}else{
		warning("El registro ya se encuentra agregado");
	}
}






function cantidadTrabajadoreSumar() {
	$(".cant_trabajadores").keyup(function () {
		sumar();
	});
}




var count2 = 0;
function addPlanEdit() {
	count2++;
	var plan_name    = $("#plan_cotizacion_actualizar_fisica option:selected").text();
	var paquete_name = $("#paquetes_cotizacion_actualizar_fisica option:selected").text();

	var plan_id    = $("#plan_cotizacion_actualizar_fisica option:selected").val();
	var paquete_id = $("#paquetes_cotizacion_actualizar_fisica option:selected").val();


	var plazos     = $("#vigencia_actualizar_fisica").val();


	var html = "";


	var input_plan_id    = "<input type='hidden' name='plan_id[]' value='"+plan_id+"'>"
	var input_paquete_id = "<input type='hidden' name='paquete_id[]' value='"+paquete_id+"'>"


	var select_plazos     = '<input type="text" value="'+plazos+'" name="plazos[]" class="form-control" placeholder="Plazos" readonly>'
	var catn_trabajadores = '<input type="text" name="cant_trabajadores[]" class="form-control cant_trabajadores_edit" placeholder="Cantidad de Trabajadores" required>'

	var btn_view   = "<span class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span>";

	var btn_delete = "<span class='btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='eliminar' onclick='deleteTr(\"" + "#tr_" + count2 + "\")'><i class='fa fa-times' style='margin-bottom:5px'></i></span>";


	var monto = parseFloat($("#monto_paquete_actualizar_fisica_oculto").val())


	var monto_input = "<input type='hidden' class='monto_input_edit' id='monto_tr' value='"+monto+"'>"


	
	html += "<tr id='tr_"+count2+"'>";
		html+= "<td>"+btn_view+" "+btn_delete+" "+monto_input+""+input_plan_id+input_paquete_id+"</td>";
		html+= "<td>"+plan_name+"</td>";
		html+= "<td>"+paquete_name+"</td>";
		html+= "<td>"+select_plazos+"</td>";
		html+= "<td>"+catn_trabajadores+"</td>";
		html+= "<td>"+number_format(monto, 2)+"</td>";
	html += "</tr>";



	if(plan_id == "" || paquete_id == ""){
		warning("Todos los campos son requeridos");
		return false;
	}

	


	$("#tableActualizarFisica").append(html);
	cantidadTrabajadoreSumarEdit()
	sumarEdit();
}



function cantidadTrabajadoreSumarEdit() {
	$(".cant_trabajadores_edit").keyup(function () {
		sumarEdit();
	});
}




function sumar(){
	var suma = 0;
	var total_trabajadores = 0;
	$("#tableRegistrarFisica tbody tr").each(function() {
    	var monto             = inNum($(this).find(".monto_input").val());
    	var cant_trabajadores = inNum($(this).find(".cant_trabajadores").val());
    	suma      = suma + monto * cant_trabajadores;

    	total_trabajadores = total_trabajadores + cant_trabajadores;
	});

	var monto_inscripcion = 1000 * total_trabajadores;

	$("#monto_inscripcion_registrar_fisica").val(number_format(monto_inscripcion, 2))


	$("#monto_paquete_registrar_fisica").val(number_format(suma, 2))


	$("#monto_total_registrar_fisica").val(number_format(suma + parseFloat(inNum($("#monto_inscripcion_registrar_fisica").val())), 2))
}



function sumarService(){
	var suma = 0;
	var cantidad_services = 0;
	$("#tableRegistrarServiceFisica tbody tr").each(function() {
    	var monto             = inNum($(this).find(".monto_input").val());
		var cantidad          = inNum($(this).find(".cantidad_input").val());
		
    	suma      = suma + monto * cantidad;

	});

	 $("#monto_total_registrar_fisica").val(number_format(suma, 2))
}




function sumarEdit(){
	var suma = 0;
	var total_trabajadores = 0;
	$("#tableActualizarFisica tbody tr").each(function() {
    	var monto             = parseFloat($(this).find(".monto_input_edit").val());
    	var cant_trabajadores = inNum($(this).find(".cant_trabajadores_edit").val());
		
    	suma               = suma + monto * cant_trabajadores;
    	total_trabajadores = total_trabajadores + cant_trabajadores;
	});

	var monto_inscripcion = 1000 * total_trabajadores;

	$("#monto_inscripcion_actualizar_fisica").val(number_format(monto_inscripcion, 2))

	$("#monto_paquete_actualizar_fisica").val(number_format(suma, 2))


	$("#monto_total_actualizar_fisica").val(number_format(suma + parseFloat(inNum($("#monto_inscripcion_actualizar_fisica").val())), 2))


}




function deleteTr(tr) {
	$(tr).remove();
	sumar();
	sumarEdit();
}


function deleteTr2(tr, id) {
	$(tr).remove();
	$(".tr_"+id).remove();
}

/*
*	Función para consultar los paquetes asociados a los planes...
*/
function consultarPaquetes(plan,proceso,campo_paquete){
//----------------------------------------
	var controlador = "Cotizacion/consultarPaquetes"
    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
    var id_plan = plan
    $.ajax({
        url:url+controlador,
        type:"POST",
        dataType:"JSON",
        data:{
                        "id_plan":id_plan,
        },
        cache:false,
        beforeSend: function(){
            mensajes('info', '<span>Buscando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
        },
        error: function (repuesta) {
            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
            var errores=repuesta.responseText;
            if(errores!="")
                mensajes('danger', errores);
            else
                mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
        },
        success: function(respuesta){
        	$("#alertas").html('');
        	$('input[type="submit"]').removeAttr('disabled'); //activa el input submit
			if(respuesta!=""){
			//----------------------
				if(proceso=="guardar"){
					//---Si es persona moral
					caja_paquete = "paquetes_cotizacion_registrar_fisica";
					eliminarOptions(caja_paquete)
					respuesta.forEach(function(campo, index){
						if(campo.status==true)
		                	agregarOptions("#"+caja_paquete, campo.id_paquete, campo.descripcion);
		            });
	            	$("#"+caja_paquete+" option[value='']").prop("selected",true);
	            }else if(proceso=="actualizar"){
					caja_paquete = "paquetes_cotizacion_actualizar_fisica";
						caja_plan = "plan_cotizacion_actualizar_fisica";
					
					eliminarOptions(caja_paquete)
	            	respuesta.forEach(function(campo, index){
		                agregarOptions("#"+caja_paquete, campo.id_paquete, campo.descripcion);
		            });
		            if(campo_paquete!=""){
		            	$("#"+caja_paquete+" option[value='" + campo_paquete  + "']").prop("selected",true);
		            }else{
		            	$("#"+caja_paquete+" option[value='']").prop("selected",true);
		            }
		            $("#"+caja_plan+" option[value='" +plan+ "']").prop("selected",true);
	            }else if(proceso=="mostrar"){
	            	//---------------------------
	            	//---Si es persona moral
					if($("#tipopersonaC input[id='moral_mostrar']").is(':checked')){
						caja_paquete = "paquetes_cotizacion_mostrar_moral";
						caja_plan = "plan_cotizacion_mostrar_moral";
					}
					else{
					//---Si es fisica
						caja_paquete = "paquetes_cotizacion_mostrar_fisica";
						caja_plan = "plan_cotizacion_mostrar_fisica";
					}
					eliminarOptions(caja_paquete)
	            	respuesta.forEach(function(campo, index){
		                agregarOptions("#"+caja_paquete, campo.id_paquete, campo.descripcion);
		            });
		            if(campo_paquete!=""){
		            	$("#"+caja_paquete+" option[value='" + campo_paquete  + "']").prop("selected",true);
		            }else{
		            	$("#"+caja_paquete+" option[value='']").prop("selected",true);
		            }
		            $("#"+caja_plan+" option[value='" +plan+ "']").prop("selected",true);
	            	//---------------------------
	            }
			//----------------------	
			}else{
				
				/*if(proceso=="guardar"){
					eliminarOptions("paquetes_membresia_registrar")
	            	$("#paquetes_membresia_registrar option[value='']").prop("selected",true);
	            }else if(proceso=="actualizar"){
					eliminarOptions("paquetes_membresia_actualizar")
	            	$("#paquetes_membresia_actualizar option[value='']").prop("selected",true);
	            }else if(proceso=="mostrar"){
	            	eliminarOptions("paquetes_membresia_mostrar")
	            	$("#paquetes_membresia_mostrar option[value='']").prop("selected",true);
	            }*/
			}
			
		}	
	});	
//----------------------------------------
}
/*
*
*/
/* ------------------------------------------------------------------------------- */
/*

	Funcion que muestra el cuadro3 para la consulta
*/
	function ver(tbody, table){
		$('#fisica_mostrar').attr('checked', false)
		$('#moral_mostrar').attr('checked', false)		
		$("#form_cotizacion_mostrar")[0].reset();

		base_url = document.getElementById('ruta').value;
		$(tbody).on("click", "span.consultar", function(){
			//----------------------------------------------------------------------

			var data = table.row( $(this).parents("tr") ).data();


			//Llenado de campos			
			$("#numero_cotizacionC, #n_cotizacion").val(data.numero_cotizacion);
			$("#fecha_aprobacion_view").val(data.fecha_aprobacion);
			$("#id_cotizacion_mostrar").val(data.id_cotizacion);
			$("#tipo_persona_mostrar").val(data.tipo_persona);	
			$("#alertas").css("display", "none");
			
			var url_imprimir = base_url+'Cotizacion/pdf/'+data.id_cotizacion
			$("#btn_imprimirC").attr("href",url_imprimir)
			
			var url_mail = base_url+'Cotizacion/sendventaemail/'+data.id_cotizacion+"/"
			$("#btn_mailC").attr("href",url_mail)
			


			//--Persona fisica
			if (data.tipo_persona == "fisica"){ 
				$('#fisica_mostrar').attr('checked', true)
				$("#personaMoralC").hide();
				$("#personaFisicaC").show();
				$(".fisicaf").attr("required",true)	
				$(".moralf").removeAttr("required");
				//--Asigno valores
				$("#rfc_cotizacion_mostrar_fisica").val(data.identificador_prospecto_cliente);
				$("#id_vendedor_mostrar option[value='"+data.id_vendedor+"']").prop("selected",true);
				$("#plan_cotizacion_mostrar_fisica option[value='"+data.id_plan+"']").prop("selected",true);
				$("#vigencia_mostrar_fisica").val(data.vigencia);
				//--Asigno montos
				
			}else		//Persona moral
			if (data.tipo_persona == "moral"){
				$('#moral_mostrar').attr('checked', true);
				$("#personaMoralC").show();
				$("#personaFisicaC").hide();
				$(".fisicaf").removeAttr("required");
				$(".moralf").attr("required", true)
				var id_cotizacion = data.id_cotizacion;
				
				//--Asigno valores
				$("#rfc_cotizacion_mostrar_moral").val(data.identificador_prospecto_cliente);
				$("#id_vendedor_moral_mostrar option[value='"+data.id_vendedor+"']").prop("selected",true);
				$("#plan_cotizacion_mostrar_moral option[value='"+data.id_plan+"']").prop("selected",true);
				$("#vigencia_mostrar_moral").val(data.vigencia);
				$("#cantidad_trabajadores_mostrar_moral").val(data.cantidad_usuarios);

				//--Asigno montos
				
			}


			$("#monto_inscripcion_mostrar_fisica").val(data.monto_inscripcion);
				$("#monto_paquete_mostrar_fisica").val(data.monto_mensualidad_individual)
				$("#monto_total_mostrar_fisica").val(data.monto_total)
				//--Asigno montos ocultos
				$("#monto_inscripcion_mostrar_fisica_oculto").val(data.monto_inscripcion_oculto);
				$("#monto_paquete_mostrar_fisica_oculto").val(data.monto_mensualidad_individual_oculto)
				$("#monto_total_mostrar_fisica_oculto").val(data.monto_total_oculto)


			//--------------------------------------------------------------------------
			
			consultarPlan(data.paquete,data.plan,'mostrar')
			cuadros('#cuadro1', '#cuadro3');
			consultarClienteRFCMostrar(data.identificador_prospecto_cliente,data.tipo_persona);


			$("#tbodyMostrarFisica tr").remove();
			GetDataPlan(data.data_plan, "#tbodyMostrarFisica");



			if (data.membresia == true) {
				$("#indicador_jornadas_mostrar").prop("checked", true);
				$(".remove").css("display", "block")
				consultarPaquetes(data.plan,'mostrar',data.paquete);
			}else{
				$("#indicador_jornadas_mostrar").prop("checked", false);
				$(".remove").css("display", "none")

				consultarServicios(data.data_service,'mostrar');

			}


			//----------------------------------------------------------------------
		});
	}







	function GetDataPlan(data_plan, table) {

		
		$(table+" tr").remove();
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    var controlador = "Cotizacion/GetDataPlan";
	    var method = "POST";

	    var monto_total = 0;
	    
	    var count = 0;
		$.each(data_plan, function(i, item){
			

		    $.ajax({
		        url:url+controlador,
		        type:method,
		        dataType:'JSON',
		        data:item,
		        beforeSend: function(){
		            
		        },
		        error: function (repuesta) {
		            $('#btn-aceptar').removeAttr('disabled'); //activa el input submit
		            var errores=repuesta.responseText;
		            if(errores!="")
		                mensajes('danger', errores);
		            else
		                mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");     

		        },
		         success: function(respuesta){

		         	count++;

		         	console.log(respuesta);
		         	var name_plan    = respuesta.plan.titulo+" "+respuesta.plan.descripcion
		         	var name_paquete = respuesta.paquete.descripcion

		         	monto_total = parseFloat(monto_total) + parseFloat(respuesta.paquete.precio)
		         	var html = "";

		         	var input_plan_id    = "<input type='hidden' name='plan_id[]' value='"+respuesta.paquete.plan+"'>"
					var input_paquete_id = "<input type='hidden' name='paquete_id[]' value='"+respuesta.paquete._id.$id+"'>"

					var select_plazos     = '<input type="text" name="plazos[]" class="form-control" placeholder="Cantidad de Trabajadores" value="'+item.plazo+'" readonly>'

					var catn_trabajadores = '<input type="text" name="cant_trabajadores[]" class="form-control cant_trabajadores_edit" placeholder="Cantidad de Trabajadores" value="'+item.cant_trabajadore+'" required>'


					var monto_input = "<input type='hidden' class='monto_input_edit' id='monto_tr' value='"+respuesta.paquete.precio+"'>"



					 html += "<tr id='tredit_"+count+"'>";
					 
					 var btn_view   = "<span onclick='showModalServices2(\"" + respuesta.paquete._id.$id + "\")' class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span>";
					 var btn_delete = "<span class='btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='eliminar' onclick='deleteTr(\"" + "#tredit_" + 	count + "\")'><i class='fa fa-times' style='margin-bottom:5px'></i></span>";
					
					 html+= "<td>"+btn_view+"</td>";	

						html+= "<td>"+name_plan+"</td>";
					    html+= "<td>"+name_paquete+"</td>";
					    html+= "<td>"+select_plazos+"</td>";
						html+= "<td>"+catn_trabajadores+"</td>";
						html+= "<td>"+number_format(respuesta.paquete.precio, 2)+"</td>";
					html += "</tr>";

					$(table).append(html);

					$("#plazos_edit_"+count).val(item.plazo)
					cantidadTrabajadoreSumarEdit()


		        }

		    });
        });
	    
	}
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function desactivar(tbody, table){
		$(tbody).on("click", "span.desactivar", function(){
            var data=table.row($(this).parents("tr")).data();
            statusConfirmacion('Membresia/status_membresia', data.id_membresia, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function activar(tbody, table){
		$(tbody).on("click", "span.activar", function(){
            var data=table.row($(this).parents("tr")).data();
            statusConfirmacion('Membresia/status_membresia', data.id_membresia, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}

/* ------------------------------------------------------------------------------- */
/*

/*
*	Mensajes en cualquier div
*/
function mensajesGs(div,type, msj){
    html='<div class="alert alert-'+type+'" role="alert">';
    html+='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
    html+=msj;
    html+='</div>';
    return $(div).html(html).css("display", "block");
}
/*
*	Calcular monto paquetes....
*/
function calcularMontoPaquetes(proceso){
	if(proceso=="guardar"){
	//---
		var cantidad_usuarios = parseFloat($("#cantidad_trabajadores_moral").val());
		
		if(cantidad_usuarios=="")
			cantidad_usuarios=0
		
		var total_paquetes = parseFloat($("#monto_paquete_registrar_moral_oculto").val());
		var total_paquetes = cantidad_usuarios * total_paquetes;
		$("#monto_total_paquete_registrar_moral_oculto").val(total_paquetes);
		$("#monto_total_paquete_registrar_moral").val(new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(total_paquetes))
		//--Realizo el calculo
		var monto_ins = $("#monto_inscripcion_registrar_moral_oculto").val();
		var monto_paquete = $("#monto_total_paquete_registrar_moral_oculto").val();
		monto_total =	parseFloat(monto_ins) + parseFloat(monto_paquete)
		//alert(monto_total)
		$("#monto_total_registrar_moral_oculto").val(monto_total);
		$("#monto_total_registrar_moral").val(new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(monto_total))
	//---	
	}else if(proceso=="actualizar"){
	//---
		var cantidad_us= $("#cantidad_trabajadores_actualizar_moral").val();
		
		if(cantidad_us=="")
			cantidad_usuarios=0
		else
			cantidad_usuarios = parseInt(cantidad_us)

		var total_paquetes = parseFloat($("#monto_paquete_actualizar_moral_oculto").val());
		var total_paquetes = cantidad_usuarios * total_paquetes;
		$("#monto_total_paquete_actualizar_moral_oculto").val(total_paquetes);
		$("#monto_total_paquete_actualizar_moral").val(new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(total_paquetes))
		//--Realizo el calculo
		var monto_ins = $("#monto_inscripcion_actualizar_moral_oculto").val();
		var monto_paquete = $("#monto_total_paquete_actualizar_moral_oculto").val();
		monto_total =	parseFloat(monto_ins) + parseFloat(monto_paquete)
		$("#monto_total_actualizar_moral_oculto").val(monto_total);
		$("#monto_total_actualizar_moral").val(new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(monto_total))

	//---
	}
	
	//---
}
/*
*	Enviar email
*/
//--El envio de email se realiza a través de una petición desde el href de una etiqueta a, sin embargo esta funcion regresa al lista y manda el mensaje del envio...
function enviar_email(){
	//regresar('#cuadro4');
	mensajes('success', "<span>Se realizó el envio del email!</span>");
	listar('#cuadro4');
}
/*
*	Envio de email consulta
*/
function enviar_emailC(){
	//regresar('#cuadro3');
	mensajes('success', "<span>Se realizó el envio del email!</span>");
	listar('#cuadro3');
}
/* ------------------------------------------------------------------------------- */
/*
*	Cancelar
*/
	function cancelar(tbody, table){
		$(tbody).on("click", "span.cancelar", function(){
            var data=table.row($(this).parents("tr")).data();
            statusConfirmacion('Cotizacion/cancelar_cotizacion', data.id_cotizacion, 1, "¿Esta seguro de cancelar el registro?", 'procesar');
        });
	}
/*------------------------------------------------------------------------------------------------------------------------------*/
/*
*	Aprobar
*/
	function aprobar(tbody, table){
		$(tbody).on("click", "span.aprobar", function(){
			var data=table.row($(this).parents("tr")).data();
			
			swal({
				title: "¿Esta seguro de aprobar el registro?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Si, Procesar!",
				cancelButtonText: "No, Cancelar!",
				closeOnConfirm: true,
				closeOnCancel: false
			},
			function(isConfirm){
				if (isConfirm) {

					if(data.membresia == false){
						GenerarCobranzaOtrosServicios(data._id.$id, data.data_service, data.numero_cotizacion, data.identificador_prospecto_cliente)
					}else{
						GetDataPlanAprobar(data.data_plan, data.identificador_prospecto_cliente, "#tableAprobarListPlanes tbody", data.tipo_persona)
						$("#id_cotizacion").val(data._id.$id)
						cuadros("#cuadro1", "#cuadro5");
					}
					
					
				} else {
					swal("Cancelado", "Proceso cancelado", "error");
				}
			});


            //statusConfirmacion('Cotizacion/aprobar_cotizacion', data.id_cotizacion, 1, "¿Esta seguro de aprobar el registro?", 'procesar');
        });
	}


	function GenerarCobranzaOtrosServicios(id, services, numero_cotizacion, id_cliente){

		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		var controlador = "Cotizacion/AprobarCotizacionOtrosServicios";
		

		var data = {
			"id_cotizacion"     : id,
			"data_service"      : services,
			"numero_cotizacion" : numero_cotizacion,
			"id_cliente"        : id_cliente
		}
		$.ajax({
			url:url+controlador,
			type:"post",
			dataType:'JSON',
			data:data,
			beforeSend: function(){
				mensajes('info', '<span>Guardando cambios, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
			},
			error: function (repuesta) {
				var errores=repuesta.responseText;
				mensajes('danger', errores);
			},
			 success: function(respuesta){
				listar();
                        $("#checkall").prop("checked", false);
                        mensajes('success', respuesta);
			}

		});
	}


	var ObjCliente =  [];
	function GetDataPlanAprobar(data_plan, id_cliente, table, tipo_persona) {
		
		$(table+" tr").remove();
		$("#tableAprobarFacturar tbody tr").remove();
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    var controlador = "Cotizacion/GetDataPlan";
	    var method = "POST";

	    var monto_total = 0;
	    
		var count = 0;

		getClientesObj("ClientePagador/listado_clientePagador_performance")

		var optionsClient = "";
		$.each(ObjCliente, function (i, item) { 
			if(item.tipo_persona_cliente == "FISICA"){
				if((tipo_persona == "moral" && item.empresa_pertenece == id_cliente) || (tipo_persona == "fisica")){
					optionsClient += "<option value='"+item.id_cliente+"'>"+item.nombre_datos_personales+" "+item.apellido_p_datos_personales+"</option>"
				}
			}
		});



		var optionsClientFacturar = "";
		$.each(ObjCliente, function (i, item) { 
			optionsClientFacturar += "<option value='"+item.id_cliente+"'>"+item.nombre_datos_personales+" "+item.apellido_p_datos_personales+"</option>"
		});

		
		$.each(data_plan, function(key, item){

		    $.ajax({
		        url:url+controlador,
		        type:method,
		        dataType:'JSON',
		        data:item,
		        beforeSend: function(){
		            
		        },
		        error: function (repuesta) {
		            $('#btn-aceptar').removeAttr('disabled'); //activa el input submit
		            var errores=repuesta.responseText;
		            if(errores!="")
		                mensajes('danger', errores);
		            else
		                mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");     

		        },
		         success: function(respuesta){
					
		         	count++;
		         	var name_plan    = respuesta.plan.titulo+" "+respuesta.plan.descripcion
		         	var name_paquete = respuesta.paquete.descripcion
					var html = "";
					 
					html += "<tr id='tredit_"+count+"'>";
						var btn_delete = "<span class='btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='eliminar' onclick='deleteTr2(\"" + "#tredit_" + 	count + "\", \"" + "" + 	respuesta.paquete._id.$id + "\")'><i class='fa fa-times' style='margin-bottom:5px'></i></span>";
						
						html+= "<td>"+name_plan+"</td>";
						html+= "<td>"+name_paquete+"</td>";
						html+= "<td>"+item.cant_trabajadore+"</td>";
						html+= "<td>"+btn_delete+"</td>";	
					html += "</tr>";

					$(table).append(html);

					
					var id_paquete = "<input type='hidden' name='paquete[]' value='"+respuesta.paquete._id.$id+"'>"
					
					var html2 = "";
					for (var i=0; i< item.cant_trabajadore; i++) {
						
						
						var select_cliente   = "<select name='cliente[]' id='select_client_"+respuesta.paquete._id.$id+"' required class='form-control select_client'><option value=''>Seleccione</option><option value=''>"+optionsClient+"</option></select>"
						var select_facturar  = "<select name='facturar[]' class='form-control select_facturar_"+respuesta.paquete._id.$id+"' id='select_facturar_"+respuesta.paquete._id.$id+"' required ><option value=''>Seleccione</option><option value=''>"+optionsClientFacturar+"</option></select>"

						var monto_paquete = "<input type='hidden' name='mensualidad[]' value='"+respuesta.paquete.precio+"'>"
						html2 += "<tr class='tr_"+respuesta.paquete._id.$id+"' id='tredit_"+count+"'>";
							html2+= "<td>"+name_plan+" / "+name_paquete+id_paquete+monto_paquete+"</td>";
							html2+= "<td>"+select_cliente+"</td>";
							html2+= "<td>"+select_facturar+"</td>";	
						html2 += "</tr>";
					}

					$("#tableAprobarFacturar tbody").append(html2)

					

					if(tipo_persona != "moral"){
						key == 0 ? $("#select_client_"+respuesta.paquete._id.$id).val(id_cliente) : ''
						$(".select_facturar_"+respuesta.paquete._id.$id).val(id_cliente)
					}else{
						$(".select_facturar_"+respuesta.paquete._id.$id).val(id_cliente)
					}	
					select_client_change()
		        }

		    });
		});
		

		
	    
	}


	function select_client_change(){
		$(".select_client").change(function (e) { 
			var id_select = $(this).val();
			var select = $(this)

			$("#tableAprobarFacturar tr").each(function (index, element) {
				var id_clients = $(this).find(".select_client").not(select).val()
				
				if(id_select == id_clients){
					warning("El cliente ya esta seleccionado");

					select.val("")
					return false;
				}
			});
		});
	}



	function getClientesObj(controlador){
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		$.ajax({
			url:url+controlador,
			type:"POST",
			dataType:'JSON',
			async: false,
			success: function(respuesta){
				ObjCliente = respuesta
			}

		});
	}
/*--------------------------------------------------------------------------------------------------------------------------------*/	
/*
*	Aceptar cotizacion
*/
function aceptar(tbody, table){
	$(tbody).on("click", "span.aceptar", function(){
		var data = table.row( $(this).parents("tr") ).data();
		//Llenado de campos			
		$("#id_cotizacionA").val(data.id_cotizacion);
		$("#numero_cotizacionA").val(data.numero_cotizacion);
		$("#modal_aceptar").modal("show");
		//-------------------------------------------
		$('#carta_aceptar_cotizacion').fileinput("destroy");
        $('#carta_aceptar_cotizacion').fileinput({
            theme: 'fa',
            language: 'es', 

            uploadAsync: true,
            showUpload: false, // hide upload button
            showRemove: false,
            uploadUrl: base_url+'uploads/upload/productos',
            uploadExtraData:{
                name:$('#carta_aceptar_cotizacion').attr('id')
            },
            allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
            overwriteInitial: false,
            maxFileSize: 5000,          
            maxFilesNum: 1,
            autoReplace:true,
            initialPreviewAsData: false,
            initialPreview:[],
            initialPreviewConfig:[],

            //allowedFileTypes: ['image', 'video', 'flash'],
            slugCallback: function (filename) {
                return filename.replace('(', '_').replace(']', '_');
            }
        }).on("filebatchselected", function(event, files) {
          $(event.target).fileinput("upload");

        }).on("filebatchuploadsuccess",function(form, data){
          
          //console.log(data.response)
        })
		//-------------------------------------------
	});
}
function aplicarCotizacion(){
	//------------------------------------
	$("#form_aceptar_cotizacion").submit(function(e){
		//------------------------------------
	    e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
	    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    var controlador = "Cotizacion/aceptar_cotizacion";
	    var formData=new FormData($("#form_aceptar_cotizacion")[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros

	    console.log(formData);
	    
	    var method = $(this).attr('method'); //obtiene el method del formulario
	    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
	    $.ajax({
	        url:url+controlador,
	        type:method,
	        dataType:'text',
	        data:formData,
	        cache:false,
	        contentType:false,
	        processData:false,
	        beforeSend: function(){
	            mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
	            $("#btn-aceptar").attr("disabled", "disabled");
	        },
	        error: function (repuesta) {
	            $('#btn-aceptar').removeAttr('disabled'); //activa el input submit
	            var errores=repuesta.responseText;
	            if(errores!="")
	                mensajes('danger', errores);
	            else
	                mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");     

	        },
	         success: function(respuesta){
	         	if(respuesta=="no-carta"){
					$('#btn-aceptar').removeAttr('disabled');
	                mensajes('danger', "Debe seleccionar la carta de cotización");
	                $("#modal_aceptar").modal("hide");
	                listar()
	         	}else{
	         		$('#btn-aceptar').removeAttr('disabled');
	                mensajes('success', "Operacion Exitosa");
	                $("#modal_aceptar").modal("hide");
	                listar()
	                swal("Exito!", "La cotización fue aceptada", "success");
	         	}
	        }

	    });
	});    
	//------------------------------------
}
/*
*	Ver documentos
*/
function ver_documentos(tbody, table){
	$(tbody).on("click", "span.ver_documentos", function(){
		$("#modal_ver_documentos").modal("show");
		var data = table.row( $(this).parents("tr") ).data();
		//-------------------------------------------
		var base_url = document.getElementById('ruta').value;

      	var files  = [];
      	var config = [];
      	url_imagen = base_url+'assets/cpanel/Cotizacion/images/'

    	comprobante_file = '<img src="'+url_imagen+data.carta_actividad_comercial+'" class="file-preview-image kv-preview-data">'
        files.push(comprobante_file); 

        var caption_file = {
            caption: data.carta_actividad_comercial,downloadUrl: url_imagen+data.carta_actividad_comercial  ,url: base_url+"uploads/delete", key: data.carta_actividad_comercial
        };

        config.push(caption_file); 

          
		$('#carta_aceptar_cotizacion_view').fileinput("destroy");
		$('#carta_aceptar_cotizacion_view').fileinput({
			theme: 'fa',
			language: 'es', 

			uploadAsync: true,
			showUpload: false, // hide upload button
			showRemove: false,
			uploadUrl: base_url+'uploads/upload/productos',
			uploadExtraData:{
			    name:$('#carta_aceptar_cotizacion_view').attr('id')
			},
			allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
			overwriteInitial: false,
			maxFileSize: 5000,          
			maxFilesNum: 1,
			autoReplace:true,
			initialPreviewAsData: false,
			initialPreview: files,
			initialPreviewConfig: config,

		    //allowedFileTypes: ['image', 'video', 'flash'],
		    slugCallback: function (filename) {
		        return filename.replace('(', '_').replace(']', '_');
		    }
		}).on("filebatchselected", function(event, files) {
		  $(event.target).fileinput("upload");

		}).on("filebatchuploadsuccess",function(form, data){
		});
		//-------------------------------------------
	});
}



function showModalServices(id){

	var controlador = "Paquetes/GetPaquete"
	    //e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
	    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    var method = $(this).attr('method'); //obtiene el method del formulario
	   
	    $.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
				"id_paquete":id
			},
	        success: function(respuesta){
	        	console.log(respuesta)
				$('#modal-service').modal('show');
				var html = "";
				$.each(respuesta, function(i, item){
					html += "<tr>";
						html+= "<td>"+item.data_service.cod_servicios+"</td>";
						html+= "<td>"+item.valor+"</td>";
						html+= "<td>"+item.data_service.descripcion+"</td>";
					html += "</tr>";
				});

				$("#table-services tbody").html(html);

			}	
		});	

}




function showModalServices2(id){

	var controlador = "Paquetes/GetPaquete"
	    //e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
	    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    var method = $(this).attr('method'); //obtiene el method del formulario
	   
	    $.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
				"id_paquete":id
			},
	        success: function(respuesta){
	        	console.log(respuesta)
				$('#modal-service-view').modal('show');
				var html = "";
				$.each(respuesta, function(i, item){
					html += "<tr>";
						html+= "<td>"+item.data_service.cod_servicios+"</td>";
						html+= "<td>"+item.valor+"</td>";
						html+= "<td>"+item.data_service.descripcion+"</td>";
					html += "</tr>";
				});

				$("#table-services-view tbody").html(html);

			}	
		});	

}






function GetPlanes(select, membresia){

	var url=document.getElementById('ruta').value;
	$.ajax({
	   url:url+"Planes/listado_planes/",
	  type:'GET',
	  dataType:'JSON',
	  async: false,
	  beforeSend: function(){
	  
	  },
	  error: function (data) {
			 
	  },
	  success: function(data){
		$(select+" option").remove();
		$(select).append($('<option>',
		{
		  value: "",
		  text : "Seleccione"
		}));
		$.each(data, function(i, item){
		  

			if(item.status == true){
				if((membresia == true && item.membresia == true) || (membresia == false && item.membresia == false)){
					$(select).append($('<option>',
					{
					  value: item._id.$id,
					  text : item.titulo
					}));
				}
			}
		 
		  
		});
  
	  }
	});
  }





  
  function GetServicios(select, membresia){

	var url=document.getElementById('ruta').value;
	$.ajax({
	   url:url+"Servicios/listado_servicios/",
	  type:'GET',
	  dataType:'JSON',
	  async: false,
	  beforeSend: function(){
	  
	  },
	  error: function (data) {
			 
	  },
	  success: function(data){
		$(select+" option").remove();
		$(select).append($('<option>',
		{
		  value: "",
		  text : "Seleccione"
		}));
		$.each(data, function(i, item){
			if(item.status == true){
				if((membresia == true && item.membresia == "S") || (membresia == false && item.membresia == "N")){
					$(select).append($('<option>',
					{
					  value: item.id_servicios,
					  text : item.descripcion
					}));
				}
			}
		  
		});
  
	  }
	});
  }


  $("#services").change(function (e) { 
	 var id_service = $(this).val();

	 
	 var url=document.getElementById('ruta').value;
		$.ajax({
		url:url+"Servicios/listado_servicios/",
		type:'GET',
		dataType:'JSON',
		async: false,
		beforeSend: function(){
		
		},
		error: function (data) {
				
		},
		success: function(data){
			$.each(data, function(i, item){
				if(item.status == true){
					if(id_service == item.id_servicios){
						$("#value_service").val(item.monto)
					}
				}
			
			});
		}
		});

	  
  });



  $("#indicador_jornadas_registrar").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_jornadas_registrar").is(':checked')) {
		GetPlanes("#plan_cotizacion_registrar_fisica", true)
		$(".remove").css("display", "block")
		 act = 1;
		 $("#membresia").val("S");
		 $("#plan-membresia").css("display", "block")
		 $("#servicios_cotizacion").css("display", "none")


		 $("#tableRegistrarFisica").css("display", "inline-block")
		 $("#tableRegistrarServiceFisica").css("display", "none")


	}else{
		GetPlanes("#plan_cotizacion_registrar_fisica", false)
		$(".remove").css("display", "none")
		act = 0;
		$("#membresia").val("N");
		$("#plan-membresia").css("display", "none")
		$("#servicios_cotizacion").css("display", "block")


		$("#tableRegistrarFisica").css("display", "none")
		$("#tableRegistrarServiceFisica").css("display", "inline-block")

		$("#tableRegistrarServiceFisica tbody tr").remove()

		GetServicios("#services", false)


	}
});








/*
*	Función para consultar los paquetes asociados a los planes...
*/
function consultarServicios(service,proceso){

	$("#tableMostrarFisica").css("display", "none")
	$("#tableServiceMostrar").css("display", "inline-block")
	var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	$.ajax({
		url:url+"Servicios/listado_servicios/",
		type:'GET',
		dataType:'JSON',
		async: false,
		beforeSend: function(){
		
		},
		error: function (data) {
				
		},
		success: function(data){
			var html = "";
			$.each(data, function (key2, dataService) { 

				$.each(service, function (key, item) { 
					if(item.service == dataService._id.$id){
						html += "<tr>"
							html += "<td>"+dataService.descripcion+"</td>"
							html += "<td>"+item.monto+"</td>"
							html += "<td>"+item.cantidad+"</td>"
							html += "<td>"+(inNum(item.cantidad) * inNum(item.monto)) +"</td>"
						html += "</tr>"
					}	
				});
					
			});

			$("#tableServiceMostrar tbody").html(html)
		}
	});



	


		
	//----------------------------------------
}

