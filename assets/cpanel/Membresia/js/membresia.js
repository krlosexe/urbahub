$(document).ready(function(){
	elegirFecha('.fecha');
    telefonoInput('.telefono');
	listar();
	registrar_membresia();
	actualizar_membresia();
	verificarRadio();
	/*$("#rfc_cliente_registrar_fisica").select2();
	$("#rfc_cliente_registrar_moral").select2();
	*/
	var busqueda = false;
	$(".moralf").removeAttr("required")	
  	
	$('#domicilio_fiscal_img').change(function(){
		$(".guardado").removeAttr("required")
	})
	/*$('#clabe_registrar').click(function(){
		$(".guardado").attr("required", true)	
	})*/
});
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
				"url": url + "Membresia/listado_membresia",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_membresia",
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
						
						if(actualizar == 0)
							botones += "<span id='editar 'class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";

						if(data.cancelado == false){
						//-------------------------------
							if(data.status == true && actualizar == 0)
								botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
							else if(data.status == false && actualizar == 0)
								botones += "<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
							
							if(borrar == 0)
			              		botones += "<span class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
			          		
			          		if(data.tipo_persona=="fisica" || data.tipo_persona=="FISICA")
								botones += "<a href='"+url_jornada+"'><span class='jornada btn btn-xs btn-success waves-effect' data-toggle='tooltip' title='Ir a jornada'><i class='fa fa-calendar' style='margin-bottom:5px'></i></span></a>"
							//Nuevo boton de cancelar//
			              		botones += "<span class='cancelar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Cancelar'><i class='fa fa-times' style='margin-bottom:5px'></i></span>";
						//-------------------------------	
						}
						return botones;
		          	}
				},
				{"data":"n_membresia"},
				{"data":"serial_acceso",render : function(data, type, row) {
						if(isNaN(data)== false){
							return ''
						}else{
							return data
						}
						
	          		}},
				{"data":"identificador_prospecto_cliente"},
				{"data":"tipo_persona"},
				{"data":"nombre_datos_personales"},
				{"data":"planes"},
				{"data":"fecha_inicio",
					
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						return cambiarFormatoFecha(fecha[0]);
	          		}
				},
				{"data":"fecha_fin",
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						return cambiarFormatoFecha(fecha[0]);
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
		eliminar("#tabla tbody", table);
		desactivar("#tabla tbody", table);
		activar("#tabla tbody", table);
		cancelar("#tabla tbody", table);
		//imagen_edi("#tabla tbody", table)
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro2 para mostrar el formulario de registrar.
	*/
	function nuevoRegistro(cuadroOcultar, cuadroMostrar){
		$("[type='file']").fileinput('destroy');
		$("#alertas").css("display", "none");
		cuadros("#cuadro1", "#cuadro2");
			$(".pestana_replegal").hide()
			$("#personaMoral").hide();
			$("#personaFisica").show();
			$(".moralf").removeAttr("required")	
		$("#form_membresia_registrar")[0].reset();
		$("#form_membresia_actualizar")[0].reset();
		/***/
		/*$("#rfc_cliente_registrar_fisica").select2("val", "");
		$("#rfc_cliente_registrar_moral").select2("val", "");*/
		/**/
		$("#rfc_cliente_registrar_fisica").select2();
		$("#rfc_cliente_registrar_moral").select2();
		//$("#rfc_cliente_registrar_fisica").select2("val", "");
		//$('#rfc_cliente_registrar_fisica').val(''); // Select the option with a value of ''
		//$('#rfc_cliente_registrar_fisica').trigger('change'); 
		//$("#rfc_cliente_registrar_moral").select2("val", "");
		//$('#rfc_cliente_registrar_moral').val(''); // Select the option with a value of ''
		//$('#rfc_cliente_registrar_moral').trigger('change'); 
		/**/
		/*$("#rfc_cliente_registrar_fisica option[value='']").prop("selected",true);
		$("#rfc_cliente_registrar_moral option[value='']").prop("selected",true);*/
		$("#id_membresia").val("");
		$("#horas_jornadas").html("");
        $("#precio_plan").html("");
        $("#fecha_inicio").html("");
        $("#fecha_fin").html("");
        //Doy valor a las cajas para el envio por POST
        $("#plan_horas").val("");
        $("#plan_valor").val("");
        $("#plan_fecha_inicio").val("");
        $("#plan_fecha_fin").val("");
		$("#imagen_registrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/default-img.png');
		/*------------------------------------------------*/
		eliminarOptions3("estado_registrar");
        eliminarOptions3("ciudad_registrar");
        eliminarOptions3("municipio_registrar");
        eliminarOptions3("colonia_registrar");
        eliminarOptions3("estado_editar");
        eliminarOptions3("ciudad_editar");
        eliminarOptions3("municipio_editar");
        eliminarOptions3("colonia_editar");
        $("#estado_registrar,#ciudad_registrar,#municipio_registrar,#colonia_registrar,#estado_editar,#ciudad_editar,#municipio_editar,#colonia_editar").click();
		/*------------------------------------------------*/
		$("#nombre_cliente").focus();
		var $file;	
			var x = [];	
			$(function(){
			    $file = $("[type='file']").not('.fileeditar')	
			    $file.each(function(i,el){
			    
			    x[i] = $(el).fileinput({
			        theme: 'fa',
			        language: 'es',	

			        uploadAsync: true,
			        showUpload: false, // hide upload button
			        showRemove: false,
			        uploadUrl: base_url+'uploads/upload/cliente',
			        uploadExtraData:{
			        	name:$(el).attr('id')
			        },
			        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg"],
			        overwriteInitial: false,
			        maxFileSize: 5000,			
			        maxFilesNum: 1,
			        autoReplace:true,
			        initialPreviewAsData: false,
			        initialPreview: [ 
			            
			        ],
			        initialPreviewConfig: [
			            
			            
			        ],

			        //allowedFileTypes: ['image', 'video', 'flash'],
			        slugCallback: function (filename) {
			            return filename.replace('(', '_').replace(']', '_');
			        }
			    }).on("filebatchselected", function(event, files) {
			      $(event.target).fileinput("upload");

			    }).on("filebatchuploadsuccess",function(form, data){
			      
			      console.log(data.response)
			    });
			    })
			    
			})
	}
	/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_membresia(){
		enviarFormulario("#form_membresia_registrar", 'Membresia/registrar_membresia', '#cuadro2');
	}
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
   function verificarRadio(){
   	   	$("input[name=rad_tipoper]").change(function () {
		if($("#tipopersona input[id='moral']").is(':checked')){
			/*
			*	Pestana de trabajadores
			*/
			var id_membresia = $("#id_membresia").val();
			if(id_membresia!=""){
				$(".pestana_datosTrabajadores").show();
				urlRepLegal = base_url+'Membresia/datos_trabajadores/'+id_membresia+'/1'
				$('#iframedatosTrabajadores').attr('src',urlRepLegal)
				//urlRepLegal = base_url+'Membresia/rep_legal/'+data.id_cliente
			}

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
			$(".pestana_datosTrabajadoresE").show();
			urlRepLegal = base_url+'Membresia/datos_trabajadores/'+id_membresia+'/1'
			$('#iframedatosTrabajadoresE').attr('src',urlRepLegal)
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

/* ------------------------------------------------------------------------------- */
	/*

		Funcion que muestra el cuadro3 para la consulta
	*/
	function ver(tbody, table){
		$(tbody).on("click", "span.consultar", function(){

			$("#form_membresia_registrar")[0].reset();
			$("#form_membresia_actualizar")[0].reset();
			base_url = document.getElementById('ruta').value;

			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();

			
			//console.log(table)
			// *****   datos datos generales ****
			//----------------------------------------------------
			//Llenado de campos
			//consultarPlan(data.plan,"mostrar")
			$("#numero_membresiaC").html(data.n_membresia);
			$("#id_renovacionC").html(data.numero_renovacion);
			if (data.status == true) {
				$("#plan_activoC").attr("checked","checked");
			}else{
				$("#plan_activoC").removeAttr("checked");
				//alert("false plan");
			}
			//$("#plan_membresia_mostrar option[value='" + data.plan   + "']").prop("selected",true);
			
			/*
			*	planes y paquetes
			*/
			//alert(data.plan)
			//alert(data.paquete)
			//$("#plan_membresia_mostrar option[value='" + data.plan   + "']").prop("selected",true);
			
			consultarPaquetes(data.plan,'mostrar',data.paquete);
	
			//$("#paquetes_membresia_mostrar option[value='" + data.paquete   + "']").prop("selected",true);
			

			consultarPlan(data.paquete,data.plan,'mostrar')
			//Segun incidencia #88 le doy valor al plan, segun lo registrado en la membresia...
			$("#plan_valorC").val(data.valor);
			$("#precio_planC").html(data.valor);
			///
			/*
			*
			*/
			$("#id_membresia_mostrar").val(data.id_membresia);
			$("#id_membresia").val(data.id_membresia);
			$("#tipo_persona_mostrar").val(data.tipo_persona);	
			$("#alertas").css("display", "none");
			
			if (data.tipo_persona == "fisica" || data.tipo_persona == "FISICA"){ 
				$("#fisica_mostrar").prop('checked', true)
				$("#moral_mostrar").prop('checked', false)
				$("#personaMoralC").hide();	
				$("#personaFisicaC").show();
				$(".pestana_datosTrabajadoresC").hide();
				//--Asigno valores
				if(isNaN(data.serial_acceso)== false){
				$("#serial_acceso_mostrar_fisica").val('')
				}else{
				 $("#serial_acceso_mostrar_fisica").val(data.serial_acceso)
				}
				$("#grupo_empresarial_mostrar_fisica").val(data.grupo_empresarial);
				$("#rfc_cliente_mostrar_fisica").val(data.identificador_prospecto_cliente);
				
			}			
			if (data.tipo_persona == "moral" || data.tipo_persona == "MORAL"){
				$("#moral_mostrar").prop('checked', true)
				$("#fisica_mostrar").prop('checked', false)
				$("#personaFisicaC").hide()
				$("#personaMoralC").show();
				var id_membresia = data.id_membresia;
				$(".pestana_datosTrabajadoresC").show();
				urlRepLegal = base_url+'Membresia/datos_trabajadores/'+id_membresia
				$('#iframedatosTrabajadoresC').attr('src',urlRepLegal)
				//urlRepLegal = base_url+'Membresia/rep_legal/'+data.id_cliente
				/***/
				//--Asigno valores
				if(isNaN(data.serial_acceso)== false){
				$("#serial_acceso_mostrar_moral").val('')
				}else{
				 $("#serial_acceso_mostrar_moral").val(data.serial_acceso)
				}
				//alert(data.identificador_prospecto_cliente);
				$("#rfc_cliente_mostrar_moral").val(data.identificador_prospecto_cliente)	
			}
			//--
			//Actualizo los div correspondientes a fechas:
			fech_i = data.fecha_inicio.date
			fechaIni = fech_i.split(" ");
			$("#fecha_inicioC").html(cambiarFormatoFecha(fechaIni[0]));

			fech_f = data.fecha_fin.date
			fechaFin = fech_f.split(" ");
			$("#fecha_finC").html(cambiarFormatoFecha(fechaFin[0]));

			//--
			//#Nota: desde esta rutina activo la pestaña de saldos
			consultarClienteRFCMostrar(data.identificador_prospecto_cliente,data.tipo_persona);
			cuadros('#cuadro1', '#cuadro3');
			
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
/* 
		Funcion que muestra el cuadro4 para editar
	*/
	function editar(tbody, table){
		//verificarRadio()
		//cargar_elementos_select();
		$("#form_membresia_actualizar")[0].reset();
		$('#fisica_actualizar').attr('checked', false)
		$('#moral_acualizar').attr('checked', false)		
		$("#form_membresia_registrar")[0].reset();
		$("#form_membresia_actualizar")[0].reset();
		base_url = document.getElementById('ruta').value;
		//url_imagen = base_url+'assets/cpanel/Membresia/images/'
		$(tbody).on("click", "span.editar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			//----------------------------------------------------
			
			//Llenado de campos
			//$("#plan_membresia_actualizar option[value='" + data.plan   + "']").prop("selected",true);
			
			consultarPaquetes(data.plan,'actualizar',data.paquete);
			
			//$("#paquetes_membresia_actualizar option[value='" + data.paquete   + "']").attr("selected",true);

			consultarPlan(data.paquete,data.plan,'actualizar')
			//Segun incidencia #88 le doy valor al plan, segun lo registrado en la membresia...
			$("#plan_valorE").val(data.valor);
			$("#precio_planE").html(data.valor);
			///
			//alert(data.id_membresia);
			$("#numero_membresiaE").html(data.n_membresia);
			$("#id_renovacionE").html(data.numero_renovacion);
			if (data.status == true) {
				$("#plan_activoE").attr("checked","checked");
			}else{
				$("#plan_activoE").removeAttr("checked");
			}

			$("#id_membresia_actualizar").val(data.id_membresia);
			$("#id_membresia").val(data.id_membresia);
			$("#numero_renovacion").val(data.numero_renovacion);
			$("#tipo_persona_actualizar").val(data.tipo_persona);	
			$("#alertas").css("display", "none");

			if (data.tipo_persona == "fisica" || data.tipo_persona == "FISICA"){ 
				$('#fisica_actualizar').attr('checked', true)
				$("#personaMoralE").hide();
				$("#personaFisicaE").show();
				$(".fisicaf").attr("required",true)	
				$(".morale").removeAttr("required");
				$(".pestana_datosTrabajadoresE").hide();
				//--Asigno valores
				if(isNaN(data.serial_acceso)== false){
				$("#serial_acceso_actualizar_fisica").val('')
				}else{
				 $("#serial_acceso_actualizar_fisica").val(data.serial_acceso)
				}
				$("#grupo_empresarial_jornada_registrar option[value='" + data.id_grupo_empresarial + "']").prop("selected",true);
				$("#rfc_cliente_actualizar_fisica").val(data.identificador_prospecto_cliente);
				/*document.getElementById('nombre_cliente_editar').value = data.nombre_datos_personales;
				document.getElementById('apellido_paterno_editar').value = data.apellido_p_datos_personales;
				document.getElementById('apellido_materno_editar').value = data.apellido_m_datos_personales;
				document.getElementById('rfc_editar').value = data.rfc_datos_personales;
				document.getElementById('rfc_moral_e').value = data.rfc_datos_personales;

				document.getElementById('curp_datos_personales_editar').value = data.curp_datos_personales;
				$("#actividad_economica_editar option[value='" + data.actividad_e_cliente + "']").prop("selected",true);
				document.getElementById('fecha_nac_datos_editar').value = cambiarFormatoFecha(data.fecha_nac_datos_personales);
				document.getElementById('correo_cliente_editar').value = data.correo_contacto;
				document.getElementById('telefono_cliente_editar').value = data.telefono_principal_contacto;
				$("#nacionalidad_cliente_editar option[value='" + data.nacionalidad_datos_personales + "']").prop("selected",true);
				$("#pais_origen_editar option[value='" + data.pais_cliente + "']").prop("selected",true);*/

			}else		
			if (data.tipo_persona == "moral" || data.tipo_persona == "MORAL"){
				$('#moral_actualizar').attr('checked', true);
				$("#personaMoralE").show();
				$("#personaFisicaE").hide();
				$(".fisicaf").removeAttr("required");
				$(".morale").attr("required", true)
				var id_membresia = data.id_membresia;
				$(".pestana_datosTrabajadoresE").show();
				urlRepLegal = base_url+'Membresia/datos_trabajadores/'+id_membresia+'/1'
				$('#iframedatosTrabajadoresE').attr('src',urlRepLegal)
				//urlRepLegal = base_url+'Membresia/rep_legal/'+data.id_cliente
				/***/
				$(".morale").attr("required", true)
				$("#razon_social").focus()		
				//--Asigno valores
				if(isNaN(data.serial_acceso)== false){
				$("#serial_acceso_actualizar_moral").val('')
				}else{
				 $("#serial_acceso_actualizar_moral").val(data.serial_acceso)
				}
				$("#rfc_cliente_actualizar_moral").val(data.identificador_prospecto_cliente);
				/*document.getElementById('razon_social_e').value = data.nombre_datos_personales;
				document.getElementById('rfc_moral_e').value = data.rfc_datos_personales;
				document.getElementById('rfc_editar').value = data.rfc_datos_personales;
				document.getElementById('fecha_cons_e').value = cambiarFormatoFecha(data.fecha_nac_datos_personales);
				document.getElementById('acta_constutiva_e').value = data.acta_constitutiva;
				$("#giro_mercantil_e option[value='" + data.giro_mercantil + "']").prop("selected",true);
				document.getElementById('correo_moral_e').value = data.correo_contacto;
				document.getElementById('telefono_moral_e').value = data.telefono_principal_contacto;*/
			}
			//--------------------------------------------------------------------------
			/*
			* Activar iframe de renovaciones
			*/
			$(".pestanaRenovaciones").show();

			datos_renovaciones = data.id_membresia+"_"+data.paquete+"_"+data.plan+"_"+data.identificador_prospecto_cliente+"_"+data.n_membresia+"_"+data.numero_renovacion;

			urlRepLegal = base_url+'Membresia/renovaciones/'+datos_renovaciones+'/1'
			
			mensajesGs('#mensajesRenovaciones','info', '<span>Cargando los datos, espere unos segundos por favor!... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
			
			$('#iframedatosRenovacionE').attr('src',urlRepLegal)
			/*
			*
			*/
			//--------------------------------------------------------------------------
			fech_i = data.fecha_inicio.date
			fechaIni = fech_i.split(" ");
			$("#fecha_inicioE").html(cambiarFormatoFecha(fechaIni[0]));

			fech_f = data.fecha_fin.date
			fechaFin = fech_f.split(" ");
			$("#fecha_finE").html(cambiarFormatoFecha(fechaFin[0]));

			cuadros('#cuadro1', '#cuadro4');
			consultarClienteRFCModificar(data.identificador_prospecto_cliente,data.tipo_persona);
			/*
			*	Activo pestaña de saldos
			*/
			
			//listarJornadasSaldos(data.id_membresia);
			//listarRecargosSaldos();
			//listarReservacionesSaldos();
			/*
			*
			*/
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_membresia(){
		enviarFormulario("#form_membresia_actualizar", 'Membresia/actualizar_membresia', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

	
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('Membresia/eliminar', data.id_membresia, "¿Esta seguro de eliminar el registro?");
        });
	}
/* ------------------------------------------------------------------------------- */

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
*	Cancelar
*/
	function cancelar(tbody, table){
		$(tbody).on("click", "span.cancelar", function(){
            var data=table.row($(this).parents("tr")).data();
            statusConfirmacion('Membresia/cancelar_membresia', data.id_membresia, data.numero_renovacion, "¿Esta seguro de cancelar el registro?", 'procesar');
        });
	}
/*
*
*/
/*
        Funcion que busca los codigos
    */
    /*function buscarCodigos(codigo, type){
    	if(codigo.length>4){ 
    	if (!busqueda){
    		busqueda = true;
	    	if(type == 'create'){
	    		var estado = 'estado_registrar',
		    		ciudad = 'ciudad_registrar',
		    		municipio = 'municipio_registrar',
		    		colonia = 'colonia_registrar';
	    	}else if(type == 'edit'){
	    		var estado = 'estado_editar',
		    		ciudad = 'ciudad_editar',
		    		municipio = 'municipio_editar',
		    		colonia = 'colonia_editar';
	    	}
	        eliminarOptions(estado);
	        eliminarOptions(ciudad);
	        eliminarOptions(municipio);
	        eliminarOptions(colonia);
	        if(codigo.length>4){
	            var url=document.getElementById('ruta').value;
    	        $.ajax({
	                url:url+'Usuarios/buscar_codigos',
	                type:'POST',
	                dataType:'JSON',
	                data:{'codigo':codigo},
	                beforeSend: function(){
	                    mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
	                },
	                error: function (repuesta) {
	                    mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');         
	                },
	                success: function(respuesta){
	                    $("#alertas").html('');
	                    respuesta.estados.result_object.forEach(function(campo, index){
	                        agregarOptions("#" + estado, campo.d_estado, campo.d_estado);
	                    });
	                    respuesta.ciudades.result_object.forEach(function(campo, index){
	                        if(campo.d_ciudad!=""){
	                            agregarOptions('#' + ciudad, campo.d_ciudad, campo.d_ciudad);
	                            $("#" + ciudad).css('border-color', '#ccc');
	                        }else{
	                            agregarOptions('#' + ciudad, "N/A", "NO APLICA");
	                            $("#" + ciudad).css('border-color', '#a94442');
	                        }
	                    });
	                    respuesta.municipios.result_object.forEach(function(campo, index){
	                        agregarOptions("#"+ municipio, campo.d_mnpio, campo.d_mnpio);
	                    });
	                    respuesta.colonias.result_object.forEach(function(campo, index){
	                        agregarOptions("#"+ colonia, campo.id_codigo_postal, campo.d_asenta);
	                    });
	                }
	            });
	        }else{
	            warning('Debe colocar al menos 5 caracteres para continuar.');
	        }
		}
	    }else{
	    busqueda = false;
	    }
    }*/

/* ------------------------------------------------------------------------------- */
function buscarCodigosUs(codigo, type){

	    	if(type == 'create'){
	    		var estado = 'estado_registrar',
		    		ciudad = 'ciudad_registrar',
		    		municipio = 'municipio_registrar',
		    		colonia = 'colonia_registrar';
	    	}else if(type == 'edit'){
	    		var estado = 'estado_editar',
		    		ciudad = 'ciudad_editar',
		    		municipio = 'municipio_editar',
		    		colonia = 'colonia_editar';
	    	}

	    	buscarCodigos(codigo,estado,ciudad,municipio,colonia);
	 
    }
/* ------------------------------------------------------------------------------- */

/*
        funcion que detecta la tecla enter para la busqueda de los codigos postales.
    */
    $("#codigo_postal_registrar").keydown(function(e) {
    	var busqueda = false
        if(e.which == 13) {
            if (!busqueda){
            	setTimeout(function(){
            		//buscarCodigos(document.getElementById('codigo_postal_registrar').value, 'create');
                	buscarCodigosUs(document.getElementById('codigo_postal_registrar').value, 'create')
                	var busqueda = true;
            	},2000);
            }

        }
    });
    $("#codigo_postal_registrar").change(function(e) {
    	var busqueda = false
  
    });
    $("#codigo_postal_editar").keydown(function(e) {
    	var busqueda = false
        if(e.which == 13) {
            /*if (!busqueda){
                buscarCodigos(document.getElementById('codigo_postal_editar').value, 'edit');
                var busqueda = true;
            }*/
            if (!busqueda){
            	setTimeout(function(){
            		//buscarCodigos(document.getElementById('codigo_postal_editar').value, 'edit');
                	buscarCodigosUs(document.getElementById('codigo_postal_editar').value, 'edit')
                	var busqueda = true;
            	},2000);
            }

        }

    });
     $("#codigo_postal_editar").change(function(e) {
    	var busqueda = false
  
    });

     $("#codigo_postal_registrar").focus(function() {
        busqueda = false;
    });
    $("#codigo_postal_editar").focus(function() {
        busqueda = false;
    });

      function codigoPostal(e){
        key=e.keyCode || e.which;
        teclado=String.fromCharCode(key);
        numeros="1234567890";
        especiales="8-9-17-37-38-46";//los numeros de esta linea son especiales y es para las flechass
        teclado_escpecial=false;
        for(var i in especiales)
            if (key==especiales[i])
                teclado_escpecial=true;
        if (numeros.indexOf(teclado)==-1 && !teclado_escpecial)
            return false;
    }

	/*$('#regreso_editar').click(function(){
		$("#form_membresia_registrar")[0].reset();
		$("#form_membresia_actualizar")[0].reset();
		verificarRadio();
	  //  $('#moral_editar').attr('checked', false)
			
	})*/

	/*$(".filen").fileinput({
    language: "es",
    showUpload: false,
    dropZoneEnabled: false,
    maxFileCount: 5,
    maxFilePreviewSize: 10240,
    uploadUrl: "assets/cpanel/ClientePagador/images",
    allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg"],
    previewFileIcon: '<i class="fa fa-file"></i>',
    //allowedPreviewTypes: null, // set to empty, null or false to disable preview for all types
    //preferIconicZoomPreview: true,
    previewFileIconSettings: {
        'docx': '<i class="fa fa-file-word-o text-primary"></i>',
        'xlsx': '<i class="fa fa-file-excel-o text-success"></i>',
        'pptx': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
        'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
        },
     initialPreviewDownloadUrl: "localhost/crmventas/assets/cpanel/ClientePagador/images/3dc4511d49d0779175085a0c5c94160b.pdf"
})
*/
/*function imagen_edi(tbody, table){
$(tbody).on("click", "span.editar", function(){
var data = table.row( $(this).parents("tr") ).data();
	$("#rfc_img_editar").fileinput({
		language: "es",
		 uploadUrl: "/file-upload-batch/2",
		 maxFileCount: 1,
		allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg"],
		overwriteInitial: false,
		initialPreviewAsData: true,
		initialPreview: [
		    "https://picsum.photos/1920/1080?image=101",
		    
		],
		initialPreviewConfig: [
		    {caption: "picture-1.jpg", url: "/site/file-delete", key: 101},
		    
		],
		initialPreviewDownloadUrl: 'https://picsum.photos/1920/1080?image={key}' // the key will be dynamically replaced  
		});
	});
}*/
/*------------------------------------------------------------------------------------------------------------------------------*/
function cargar_elementos_select(){

	var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>

	$.ajax({
	                url:url+'ClientePagador/cargar_elementos_select',
	                type:'POST',
	                dataType:'JSON',
	                data:{'codigo':''},
	                beforeSend: function(){
	                    mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
	                },
	                error: function (repuesta) {
	                    mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');         
	                },
	                success: function(respuesta){
	                    alert(respuesta);
	                }
	            });
}
/*
*	Funcion para realizar consulta del cliente
*/
function consultarCliente(){
	var tipo_per = $('input:radio[name=rad_tipoper]:checked').val()
	if(tipo_per=="fisica"){
		rfc_cliente = $("#rfc_cliente_registrar_fisica").val()
	}else{
		rfc_cliente = $("#rfc_cliente_registrar_moral").val()
	}
	//alert("rfc-cliente"+rfc_cliente);
	if(rfc_cliente!=""){
		var form  = "#form_membresia_registrar"
		var controlador = "Membresia/consultarClientePagadorRfc"
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
						if(tipo_per=="fisica" || tipo_per=="FISICA"){
							//---------------------------------------------------------------------
							$("#nombre_fisica_registrar").val(respuesta[0]["nombre_datos_personales"]);
			            	$("#apellido_paterno_fisica_registrar").val(respuesta[0]["apellido_p_datos_personales"]);
		   	            	$("#apellido_materno_fisica_registrar").val(respuesta[0]["apellido_m_datos_personales"]);
		   	            	$("#fecha_nac_fisica_registrar").val(respuesta[0]["fecha_nac_datos_personales"]);
							$("#genero_membresia_registrar option[value='" + respuesta[0]["genero_datos_personales"] + "']").prop("selected",true);
							$("#edo_civil_fisica_registrar option[value='" + respuesta[0]["edo_civil_datos_personales"] + "']").prop("selected",true);
							$("#nacionalidad_fisica_registrar option[value='" + respuesta[0]["nacionalidad_datos_personales"] + "']").prop("selected",true);
							$("#curp_fisica_registrar").val(respuesta[0]["curp_datos_personales"]);
							$("#pasaporte_fisica_registrar_fisica").val(respuesta[0]["pasaporte"]);
							$("#telefono_fisica_registrar").val(respuesta[0]["telefono_principal_contacto"]);
							$("#correo_fisica_registrar").val(respuesta[0]["correo_contacto"]);
							$("#actividad_economica_fisica_registrar option[value='" + respuesta[0]["actividad_e_cliente"] + "']").prop("selected",true);
			            	// ***** datos Domicilio ****	
							$('#calle_fisica_registrar').val(respuesta[0].calle_contacto);
							$('#exterior_fisica_registrar').val(respuesta[0].exterior_contacto);
							$("#interior_contacto_registrar_fisica").val(respuesta[0].interior_contacto);
							$("#codigo_postal_fisica_registrar").val(respuesta[0].d_codigo)
							agregarOptions("#estado_fisica_registrar", respuesta[0].d_estado, respuesta[0].d_estado);
							$("#estado_fisica_registrar option[value='"+respuesta[0].d_estado+"']").prop("selected",true);
							if(respuesta[0].d_ciudad!=""){
				                agregarOptions('#ciudad_fisica_registrar', respuesta[0].d_ciudad, respuesta[0].d_ciudad);
				                $("#ciudad_fisica_registrar").css('border-color', '#ccc');
				                $("#ciudad_fisica_registrar option[value='"+respuesta[0].d_ciudad+"']").prop("selected",true);
				            }else{
				                agregarOptions('#ciudad_editar', "N/A", "NO APLICA");
				                $("#ciudad_fisica_registrar").css('border-color', '#a94442');
				                $("#ciudad_fisica_registrar option[value='N/A']").prop("selected",true);
				            }
				            agregarOptions("#municipio_fisica_registrar", respuesta[0].d_mnpio, respuesta[0].d_mnpio);
							$("#municipio_fisica_registrar option[value='"+respuesta[0].d_mnpio+"']").prop("selected",true);
							agregarOptions('#colonia_fisica_registrar', respuesta[0].id_codigo_postal, respuesta[0].d_asenta);
							$("#colonia_fisica_registrar option[value='"+respuesta[0].id_codigo_postal+"']").prop("selected",true);
							//--
							//---------------------------------------------------------------------
						}else if(tipo_per=="moral" || tipo_per=="MORAL"){
							//---------------------------------------------------------------------
							$("#razon_social_moral_registrar").val(respuesta[0]["nombre_datos_personales"]);
							$("#genero_membresia_registrar_moral option[value='" + respuesta[0]["genero_datos_personales"] + "']").prop("selected",true);
			            	$("#edo_civil_membresia_registrar_moral option[value='" + respuesta[0]["edo_civil_datos_personales"] + "']").prop("selected",true);
							$("#nacionalidad_membresia_moral_registrar option[value='" + respuesta[0]["nacionalidad_datos_personales"] + "']").prop("selected",true);
			            	$("#fecha_nac_moral_registrar").val(respuesta[0]["fecha_nac_datos_personales"]);
							$("#pasaporte_moral_registrar").val(respuesta[0]["pasaporte"]);
							$("#correo_moral_registrar").val(respuesta[0]["correo_contacto"]);
							$("#telefono_moral_registrar").val(respuesta[0]["telefono_principal_contacto"]);
							$("#giro_mercantil_moral_registrar option[value='" + respuesta[0]["giro_mercantil"] + "']").prop("selected",true);
			            	// ***** datos Domicilio ****	
							$('#calle_contacto_moral_registrar').val(respuesta[0].calle_contacto);
							$('#exterior_moral_registrar').val(respuesta[0].exterior_contacto);
							$("#interior_moral_registrar").val(respuesta[0].interior_contacto);
							$("#codigo_postal_moral_registrar").val(respuesta[0].d_codigo)
							agregarOptions("#estado_moral_registrar", respuesta[0].d_estado, respuesta[0].d_estado);
							$("#estado_moral_registrar option[value='"+respuesta[0].d_estado+"']").prop("selected",true);
							if(respuesta[0].d_ciudad!=""){
				                agregarOptions('#ciudad_moral_registrar', respuesta[0].d_ciudad, respuesta[0].d_ciudad);
				                $("#ciudad_moral_registrar").css('border-color', '#ccc');
				                $("#ciudad_moral_registrar option[value='"+respuesta[0].d_ciudad+"']").prop("selected",true);
				            }else{
				                agregarOptions('#ciudad_moral_registrar', "N/A", "NO APLICA");
				                $("#ciudad_moral_registrar").css('border-color', '#a94442');
				                $("#ciudad_moral_registrar option[value='N/A']").prop("selected",true);
				            }
				            agregarOptions("#municipio_moral_registrar", respuesta[0].d_mnpio, respuesta[0].d_mnpio);
							$("#municipio_moral_registrar option[value='"+respuesta[0].d_mnpio+"']").prop("selected",true);
							agregarOptions('#colonia_registrar_moral', respuesta[0].id_codigo_postal, respuesta[0].d_asenta);
							$("#colonia_registrar_moral option[value='"+respuesta[0].id_codigo_postal+"']").prop("selected",true);
							//--
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
						$("#rfc_cliente_registrar_fisica").val("").focus()
					}else{
						$("#rfc_cliente_registrar_moral").val("").focus()
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
/*$("#rfc_cliente_registrar_moral").keyup(function(e) {
	var busqueda = false
    if(e.which != 13) {
        if($("#rfc_cliente_registrar_moral").val()==""){
			//alert("blanco"+$("#rfc_cliente_registrar_fisica").val())
			$("#form_membresia_actualizar")[0].reset();
		}
	}	
});*/
/*function verificarBlanco(){
	if($("#rfc_cliente_registrar_fisica").val()==""){
		//alert("blanco"+$("#rfc_cliente_registrar_fisica").val())
		$("#form_membresia_registrar")[0].reset();
	}
}*/

function consultarClienteRFCModificar(rfc_cliente,tipo_per){
	if(rfc_cliente!=""){
		
		var controlador = "Membresia/consultarClientePagadorRfcModificar"
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
	            	if((respuesta[0]["tipo_cliente"]=="PROSPECTO")&&(respuesta[0]["fecha_nac_datos_personales"]=="")&&(respuesta[0]["curp_datos_personales"]=="")){
	            		//Si solo es prospecto...
	            		warning("Para registrar una membresia a esta persona, el mismo debe estar registrado como cliente desde prospecto");
	            		//--
	            	}else{//Si es un cliente
						//--
						if(tipo_per=="fisica" || tipo_per=="FISICA"){
							//---------------------------------------------------------------------
							$("#nombre_fisica_actualizar").val(respuesta[0]["nombre_datos_personales"]);
			            	$("#apellido_paterno_fisica_actualizar").val(respuesta[0]["apellido_p_datos_personales"]);
		   	            	$("#apellido_materno_fisica_actualizar").val(respuesta[0]["apellido_m_datos_personales"]);
		   	            	$("#fecha_nac_fisica_actualizar").val(respuesta[0]["fecha_nac_datos_personales"]);
							$("#genero_membresia_fisica_actualizar option[value='" + respuesta[0]["genero_datos_personales"] + "']").prop("selected",true);
							$("#edo_civil_fisica_actualizar option[value='" + respuesta[0]["edo_civil_datos_personales"] + "']").prop("selected",true);
							$("#nacionalidad_fisica_actualizar option[value='" + respuesta[0]["nacionalidad_datos_personales"] + "']").prop("selected",true);
							$("#curp_fisica_actualizar").val(respuesta[0]["curp_datos_personales"]);
							$("#pasaporte_fisica_actualizar").val(respuesta[0]["pasaporte"]);
							$("#telefono_fisica_actualizar").val(respuesta[0]["telefono_principal_contacto"]);
							$("#correo_fisica_actualizar").val(respuesta[0]["correo_contacto"]);
							$("#actividad_economica_fisica_actualizar option[value='" + respuesta[0]["actividad_e_cliente"] + "']").prop("selected",true);
			            	// ***** datos Domicilio ****	
							$('#calle_fisica_actualizar').val(respuesta[0].calle_contacto);
							$('#exterior_fisica_actualizar').val(respuesta[0].exterior_contacto);
							$("#numero_interior_fisica_actualizar").val(respuesta[0].interior_contacto);
							$("#codigo_postal_fisica_actualizar").val(respuesta[0].d_codigo)
							agregarOptions("#estado_fisica_actualizar", respuesta[0].d_estado, respuesta[0].d_estado);
							$("#estado_fisica_actualizar option[value='"+respuesta[0].d_estado+"']").prop("selected",true);
							if(respuesta[0].d_ciudad!=""){
				                agregarOptions('#ciudad_fisica_actualizar', respuesta[0].d_ciudad, respuesta[0].d_ciudad);
				                $("#ciudad_fisica_actualizar").css('border-color', '#ccc');
				                $("#ciudad_fisica_actualizar option[value='"+respuesta[0].d_ciudad+"']").prop("selected",true);
				            }else{
				                agregarOptions('#ciudad_fisica_actualizar', "N/A", "NO APLICA");
				                $("#ciudad_fisica_actualizar").css('border-color', '#a94442');
				                $("#ciudad_fisica_actualizar option[value='N/A']").prop("selected",true);
				            }
				            agregarOptions("#municipio_fisica_actualizar", respuesta[0].d_mnpio, respuesta[0].d_mnpio);
							$("#municipio_fisica_actualizar option[value='"+respuesta[0].d_mnpio+"']").prop("selected",true);
							agregarOptions('#colonia_fisica_actualizar', respuesta[0].id_codigo_postal, respuesta[0].d_asenta);
							$("#colonia_fisica_actualizar option[value='"+respuesta[0].id_codigo_postal+"']").prop("selected",true);
							//--
							//--
							/*
							*	Activando pestaña de saldos....
							*/
							//---------------------------------------------------------------------------------
							id_membresia = $("#id_membresia").val()
							numero_renovacion = $("#numero_renovacion").val()
							
							var serial_saldos = $("#serial_acceso_actualizar_fisica").val()
							var rfc_saldos = $("#rfc_cliente_actualizar_fisica").val()
							var datos_saldos = new Array()
							
							datos_saldos = id_membresia+"_"+rfc_saldos+"_"+serial_saldos+"_"+respuesta[0]["nombre_datos_personales"]+"_"+respuesta[0]["apellido_p_datos_personales"]+"_"+respuesta[0]["apellido_m_datos_personales"]+"_"+numero_renovacion
							
							//console.log(datos_saldos);
							$("#tabSaldosE").show();
							urlSaldos = base_url+'Membresia/saldos/'+datos_saldos+'/1'
							mensajesGs('#mensajesSaldos','info', '<span>Cargando los datos, espere unos segundos por favor!... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
							$('#iframedatosSaldosE').attr('src',urlSaldos)
										   	            	
							//---------------------------------------------------------------------

							//---------------------------------------------------------------------
						}else if(tipo_per=="moral" || tipo_per=="MORAL" ){
							//---------------------------------------------------------------------
							$("#razon_social_moral_actualizar").val(respuesta[0]["nombre_datos_personales"])
							$("#genero_membresia_actualizar_moral option[value='" + respuesta[0]["genero_datos_personales"] + "']").prop("selected",true);
			            	$("#edo_civil_membresia_actualizar_moral option[value='" + respuesta[0]["edo_civil_datos_personales"] + "']").prop("selected",true);
							$("#nacionalidad_membresia_moral_actualizar option[value='" + respuesta[0]["nacionalidad_datos_personales"] + "']").prop("selected",true);
			            	$("#fecha_nac_moral_actualizar").val(respuesta[0]["fecha_nac_datos_personales"]);
							$("#pasaporte_moral_actualizar").val(respuesta[0]["pasaporte"]);
							$("#correo_moral_actualizar").val(respuesta[0]["correo_contacto"]);
							$("#telefono_moral_actualizar").val(respuesta[0]["telefono_principal_contacto"]);
							$("#giro_mercantil_moral_actualizar option[value='" + respuesta[0]["giro_mercantil"] + "']").prop("selected",true);
			            	// ***** datos Domicilio ****	
							$('#calle_contacto_moral_actualizar').val(respuesta[0].calle_contacto);
							$('#exterior_moral_actualizar').val(respuesta[0].exterior_contacto);
							$("#interior_moral_actualizar").val(respuesta[0].interior_contacto);
							$("#codigo_postal_moral_actualizar").val(respuesta[0].d_codigo)
							//alert(respuesta[0].d_estado)
							agregarOptions("#estado_moral_actualizar", respuesta[0].d_estado, respuesta[0].d_estado);
							$("#estado_moral_actualizar option[value='"+respuesta[0].d_estado+"']").prop("selected",true);
							if(respuesta[0].d_ciudad!=""){
				                agregarOptions('#ciudad_moral_actualizar', respuesta[0].d_ciudad, respuesta[0].d_ciudad);
				                $("#ciudad_moral_actualizar").css('border-color', '#ccc');
				                $("#ciudad_moral_actualizar option[value='"+respuesta[0].d_ciudad+"']").prop("selected",true);
				            }else{
				                agregarOptions('#ciudad_moral_actualizar', "N/A", "NO APLICA");
				                $("#ciudad_moral_actualizar").css('border-color', '#a94442');
				                $("#ciudad_moral_actualizar option[value='N/A']").prop("selected",true);
				            }
				            agregarOptions("#municipio_moral_actualizar", respuesta[0].d_mnpio, respuesta[0].d_mnpio);
							$("#municipio_moral_actualizar option[value='"+respuesta[0].d_mnpio+"']").prop("selected",true);
							agregarOptions('#colonia_actualizar_moral', respuesta[0].id_codigo_postal, respuesta[0].d_asenta);
							$("#colonia_actualizar_moral option[value='"+respuesta[0].id_codigo_postal+"']").prop("selected",true);
							//--
							/*
							*	Oculto la pestaña de saldos
							*/
							$("#tabSaldosE").hide();
							/***/
						}
						//--Para mostrar la imagen del cliente
						if(respuesta[0]["imagenCliente"]!=""){
							$("#imagen_editar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/'+respuesta[0]["imagenCliente"]
						);
						}else{
							$("#imagen_editar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/default-img.png');
						}
						//---
	            	}
	            }else{
					mensajes('danger', "<span>No hay registros asociados al identificador consultado</span>"); 
					if(tipo_per=="fisica" || tipo_per=="FISICA"){
						$("#rfc_cliente_actualizar_fisica").val("").focus()
					}else{
						$("#rfc_cliente_actualizar_moral").val("").focus()
					}
					$("#form_membresia_registrar")[0].reset()
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
		
		var controlador = "Membresia/consultarClientePagadorRfcModificar"
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
	        	console.log(respuesta);
	            if(respuesta.length>0){
	            	if((respuesta[0]["tipo_cliente"]=="PROSPECTO")&&(respuesta[0]["fecha_nac_datos_personales"]=="")&&(respuesta[0]["curp_datos_personales"]=="")){
	            		//Si solo es prospecto...
	            		warning("Para registrar una membresia a esta persona, el mismo debe estar registrado como cliente desde prospecto");
	            		//--
	            	}else{//Si es un cliente
						//--
						if(tipo_per=="fisica" || tipo_per=="FISICA"){
							//---------------------------------------------------------------------
							$("#nombre_fisica_mostrar").val(respuesta[0]["nombre_datos_personales"]);
			            	$("#apellido_paterno_fisica_mostrar").val(respuesta[0]["apellido_p_datos_personales"]);
		   	            	$("#apellido_materno_fisica_mostrar").val(respuesta[0]["apellido_m_datos_personales"]);
		   	            	$("#fecha_nac_fisica_mostrar").val(respuesta[0]["fecha_nac_datos_personales"]);
							$("#genero_membresia_fisica_mostrar option[value='" + respuesta[0]["genero_datos_personales"] + "']").prop("selected",true);
							$("#edo_civil_fisica_mostrar option[value='" + respuesta[0]["edo_civil_datos_personales"] + "']").prop("selected",true);
							$("#nacionalidad_fisica_mostrar").val(respuesta[0]["pais_nacionalidad"])
							$("#curp_fisica_mostrar").val(respuesta[0]["curp_datos_personales"]);
							$("#pasaporte_fisica_mostrar").val(respuesta[0]["pasaporte"]);
							$("#telefono_fisica_mostrar").val(respuesta[0]["telefono_principal_contacto"]);
							$("#correo_fisica_mostrar").val(respuesta[0]["correo_contacto"]);
							$("#actividad_economica_fisica_mostrar").val(respuesta[0]["actividad_economica"]) 
			            	// ***** datos Domicilio ****	
							$('#calle_fisica_mostrar').val(respuesta[0].calle_contacto);
							$('#exterior_fisica_mostrar').val(respuesta[0].exterior_contacto);
							$("#numero_interior_fisica_mostrar").val(respuesta[0].interior_contacto);
							
							$("#codigo_postal_fisica_mostrar").val(respuesta[0].d_codigo)

							$("#estado_fisica_mostrar").val(respuesta[0].d_estado) 

				            $("#ciudad_fisica_mostrar").val(respuesta[0].d_ciudad)

							$("#municipio_fisica_mostrar").val(respuesta[0].d_mnpio)

							$("#colonia_fisica_mostrar").val(respuesta[0].d_asenta) 
							//--
							/*
							*	Activando pestaña de saldos....
							*/
							//---------------------------------------------------------------------------------
							id_membresia = $("#id_membresia").val()
							var numero_renovacion = $("#numero_renovacion").val()
							var serial_saldos = $("#serial_acceso_mostrar_fisica").val()
							var rfc_saldos = $("#rfc_cliente_mostrar_fisica").val()
							var datos_saldos = new Array()
							
							datos_saldos = id_membresia+"_"+rfc_saldos+"_"+serial_saldos+"_"+respuesta[0]["nombre_datos_personales"]+"_"+respuesta[0]["apellido_p_datos_personales"]+"_"+respuesta[0]["apellido_m_datos_personales"]+"_"+numero_renovacion
							
							console.log(datos_saldos);
							$("#tabSaldosC").show();
							urlSaldos = base_url+'Membresia/saldos/'+datos_saldos+'/1'
							mensajesGs('#mensajesSaldosC','info', '<span>Cargando los datos, espere unos segundos por favor!... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
							$('#iframedatosSaldosC').attr('src',urlSaldos)
										   	            	
							//---------------------------------------------------------------------
						}else if(tipo_per=="moral" || tipo_per=="MORAL"){

							//---------------------------------------------------------------------

							$("#razon_social_mostrar").val(respuesta[0]["nombre_datos_personales"])
							$("#genero_membresia_mostrar_moral").val(respuesta[0]["genero_datos_personales"])
			            	$("#edo_civil_membresia_mostrar_moral").val(respuesta[0]["edo_civil_datos_personales"]) 
			            	$("#nacionalidad_membresia_moral_mostrar").val(respuesta[0]["pais_nacionalidad"])
			            	$("#fecha_nac_moral_mostrar").val(respuesta[0]["fecha_nac_datos_personales"]);
							$("#pasaporte_moral_mostrar").val(respuesta[0]["pasaporte"]);
							$("#correo_moral_mostrar").val(respuesta[0]["correo_contacto"]);
							$("#telefono_moral_mostrar").val(respuesta[0]["telefono_principal_contacto"]);
							$("#giro_mercantil_moral_mostrar").val(respuesta[0]["giro_merca_desc"])
							// ***** datos Domicilio ****	
							$('#calle_contacto_moral_mostrar').val(respuesta[0].calle_contacto);
							$('#exterior_moral_mostrar').val(respuesta[0].exterior_contacto);
							$("#interior_moral_mostrar").val(respuesta[0].interior_contacto);
							$("#codigo_postal_moral_mostrar").val(respuesta[0].d_codigo)
							//alert(respuesta[0].d_estado)
							$("#estado_moral_mostrar").val(respuesta[0].d_estado) 
				            $("#ciudad_moral_mostrar").val(respuesta[0].d_ciudad) 
				            $("#municipio_moral_mostrar").val(respuesta[0].d_mnpio)
							$("#colonia_mostrar_moral").val(respuesta[0].d_asenta)
							//--
							/***/
							/*
							*	Oculto pestaña de saldos
							*/
							$("#tabSaldosC").hide();
							/***/
						}
						//--Para mostrar la imagen del cliente
						if(respuesta[0]["imagenCliente"]!=""){
							$("#imagen_motrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/'+respuesta[0]["imagenCliente"]
						);
						}else{
							$("#imagen_motrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/default-img.png');
						}
						//---
						$("#alertas").html('');
			            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
	            	}
	            }else{
					mensajes('danger', "<span>No hay registros asociados al identificador consultado</span>"); 
				}
			}	
		});	
	}else{
		warning('Debe ingresar el identificador de cliente/prospecto');
	}
}
/*
*	Función para consultar planes
*/
function consultarPlan(paquete,id_plan,proceso){
	var controlador = "Membresia/consultarPlanPaquetesTablas"
    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
    if(proceso=="guardar")
    	var id_plan = $("#plan_membresia_registrar").val();
    /*else if(proceso=="actualizar")
    	var id_plan = $("#plan_membresia_actualizar").val();
    else if(proceso=="mostrar")
    	var id_plan = $("#plan_membresia_mostrar").val();
    alert(proceso);
    alert(id_plan+"-"+paquete);*/
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
			console.log(respuesta);
			if(proceso=="guardar"){
				if(respuesta.length>0){
					if(respuesta[0]["horas_jornadas"]=="0"){
						mensajes('danger', "<span>No puede seleccionar el paquete porque no tiene asociado el servicio horas de coworking!</span>");        
						$("#horas_jornadas").html("");
			            $("#precio_plan").html("");
			            $("#fecha_inicio").html("");
			            $("#fecha_fin").html("");
			            //Doy valor a las cajas para el envio por POST
			            $("#plan_horas").val("");
			            $("#plan_valor").val("");
			            $("#plan_fecha_inicio").val("");
			            $("#plan_fecha_fin").val("");
			            //--
						$("#plan_activo").attr("checked","checked");
						$("#plan_membresia_registrar option[value='']").prop("selected",true);
						eliminarOptions("paquetes_membresia_registrar")
						$('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
					}else{
						$("#horas_jornadas").html(respuesta[0]["horas_jornadas"]);
			            $("#precio_plan").html(respuesta[0]["valor"]);
			            $("#fecha_inicio").html(respuesta[0]["inicio"]);
			            $("#fecha_fin").html(respuesta[0]["vigencia"]);
			            //Doy valor a las cajas para el envio por POST
			            $("#plan_horas").val(respuesta[0]["horas_jornadas"]);
			            $("#plan_valor").val(respuesta[0]["valor"]);
			            $("#plan_fecha_inicio").val(respuesta[0]["inicio"]);
			            $("#plan_fecha_fin").val(respuesta[0]["vigencia"]);
			            //--
						$("#plan_activo").attr("checked","checked");
						$('input[type="submit"]').removeAttr('disabled'); //desactiva el input submit
					}
					
				}else{
					$("#horas_jornadas").html("");
		            $("#precio_plan").html("");
		            $("#fecha_inicio").html("");
		            $("#fecha_fin").html("");
		            //Doy valor a las cajas para el envio por POST
		            $("#plan_horas").val("");
		            $("#plan_valor").val("");
		            $("#plan_fecha_inicio").val("");
		            $("#plan_fecha_fin").val("");
		            //--
					$("#plan_activo").attr("checked","checked");
				}
				
	  
			}else if(proceso=="actualizar"){
				console.log(respuesta);
				$("#horas_jornadasE").html(respuesta[0]["horas_jornadas"]);
	            //$("#precio_planE").html(respuesta[0]["valor"]);
	            //$("#fecha_inicioE").html(respuesta[0]["inicio"]);
	            //$("#fecha_finE").html(respuesta[0]["vigencia"]);
	            //Doy valor a las cajas para el envio por POST
	            $("#plan_horasE").val(respuesta[0]["horas_jornadas"]);
	            //$("#plan_valorE").val(respuesta[0]["valor"]);
	            $("#plan_fecha_inicioE").val(respuesta[0]["inicio"]);
	            $("#plan_fecha_finE").val(respuesta[0]["vigencia"]);
	            //--
	         
			}
			else if(proceso=="mostrar"){
				$("#horas_jornadasC").html(respuesta[0]["horas_jornadas"]);
	            //$("#precio_planC").html(respuesta[0]["valor"]);
	            //$("#fecha_inicioC").html(respuesta[0]["inicio"]);
	            //$("#fecha_finC").html(respuesta[0]["vigencia"]);
	            //Doy valor a las cajas para el envio por POST
	            $("#plan_horasC").val(respuesta[0]["horas_jornadas"]);
	            //$("#plan_valorC").val(respuesta[0]["valor"]);
	            $("#plan_fecha_inicioC").val(respuesta[0]["inicio"]);
	            $("#plan_fecha_finC").val(respuesta[0]["vigencia"]);
	            //--
	            /*if (respuesta[0].condicion == true) {
					$("#plan_activoC").attr("checked","checked");
				}else{
					$("#plan_activoC").removeAttr("checked");
				}*/
			}
		}	
	});	
}
/*
*	Función para consultar los paquetes asociados a los planes...
*/
function consultarPaquetes(plan,proceso,campo_paquete){
//----------------------------------------
var controlador = "Membresia/consultarPaquetes"
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
			console.log(respuesta);
			if(respuesta!=""){
			//----------------------
				if(proceso=="guardar"){
					eliminarOptions("paquetes_membresia_registrar")
					respuesta.forEach(function(campo, index){
						if(campo.status==true)
		                	agregarOptions("#paquetes_membresia_registrar", campo.id_paquete, campo.descripcion);
		            });
	            	$("#paquetes_membresia_registrar option[value='']").prop("selected",true);
	            }else if(proceso=="actualizar"){
					eliminarOptions("paquetes_membresia_actualizar")
	            	respuesta.forEach(function(campo, index){
		                agregarOptions("#paquetes_membresia_actualizar", campo.id_paquete, campo.descripcion);
		            });
		            $("#paquetes_membresia_actualizar option[value='" + campo_paquete  + "']").prop("selected",true);
		            $("#plan_membresia_actualizar option[value='" +plan+ "']").prop("selected",true);
	            }else if(proceso=="mostrar"){
	            	eliminarOptions("paquetes_membresia_mostrar")
	            	respuesta.forEach(function(campo, index){
		                agregarOptions("#paquetes_membresia_mostrar", campo.id_paquete, campo.descripcion);
		            });
		            $("#paquetes_membresia_mostrar option[value='" + campo_paquete  + "']").prop("selected",true);
		            $("#plan_membresia_mostrar option[value='" + plan+ "']").prop("selected",true);
	            }
			//----------------------	
			}else{
	            //--
				if(proceso=="guardar"){
					eliminarOptions("paquetes_membresia_registrar")
	            	$("#paquetes_membresia_registrar option[value='']").prop("selected",true);
	            }else if(proceso=="actualizar"){
					eliminarOptions("paquetes_membresia_actualizar")
	            	$("#paquetes_membresia_actualizar option[value='']").prop("selected",true);
	            }else if(proceso=="mostrar"){
	            	eliminarOptions("paquetes_membresia_mostrar")
	            	$("#paquetes_membresia_mostrar option[value='']").prop("selected",true);
	            }
			}
			
		}	
	});	
//----------------------------------------
}
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
/*------------------------------------------------------------------------------------------------------------------------------*/
