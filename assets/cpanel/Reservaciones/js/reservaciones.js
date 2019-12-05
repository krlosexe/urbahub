$(document).ready(function(){
	listar();
	registrar_reservaciones()
	actualizar_reservaciones()
	$("#fecha_desde").datetimepicker({
        format: 'D-MM-YYYY',
    });
    $("#fecha_hasta").datetimepicker({
        format: 'D-MM-YYYY',
    });
	$("#fecha_resevacion_registrar").datetimepicker({
        format: 'D-MM-YYYY',
        minDate: new Date(),
    });
	$('#cliente_jornada_registrar').select2();
  
    var dateNow = new Date();

	$('#hora_inicio_reservacion_registrar').datetimepicker({
        format: 'HH:mm',
        defaultDate:moment(dateNow),
        minDate: moment(dateNow),
        sideBySide: true
    });

    $('#hora_fin_reservacion_registrar').datetimepicker({
        format: 'HH:mm',
		defaultDate:moment(dateNow),
        minDate: moment(dateNow),
        sideBySide: true
    });
});
/* ------------------------------------------------------------------------------- */
/*function validarHoraFin(caja){
	alert(caja.val());
}*/
function infoSalas(){
	var id_sala =  $("#sala_registrar").val();
	var controlador = "Reservaciones/consultarInfoSalas"
    //e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	$.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
                        "id_sala":id_sala,
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
	            var servicios = respuesta[0]
	            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
				//--
            	$("#tbl_sala_registrar").html(servicios.descripcion)
           		$("#tbl_precio").html(servicios.monto)
           		$("#precio_registrar").val(servicios.monto);
	            //--
			}	
		});
}    
/*---------------------------------------------------------------------------------*/
function infoSalasActualizar(id_sala){
	var controlador = "Reservaciones/consultarInfoSalas"
    //e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	$.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
                        "id_sala":id_sala,
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
	            var servicios = respuesta[0]
	            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
				//--
				$("#sala_actualizar").val(servicios.descripcion)
            	$("#tbl_sala_actualizar").html(servicios.descripcion)
           		//$("#tbl_precio_actualizar").html(servicios.monto)
	            //--
			}	
		});
}   
/*---------------------------------------------------------------------------------*/
function infoSalasConsultar(id_sala){
	var controlador = "Reservaciones/consultarInfoSalas"
    //e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	$.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
                        "id_sala":id_sala,
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
	            var servicios = respuesta[0]
	            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
				//--
				$("#sala_consultar").val(servicios.descripcion)
            	$("#tbl_sala_consultar").html(servicios.descripcion)
           		//$("#tbl_precio_consultar").html(servicios.monto)
	            //--
			}	
		});
}    
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
				"url": url + "Reservaciones/listado_reservaciones",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_reservaciones",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data": null,
					render : function(data, type, row) {
						var botones = "";
						if(consultar == 0)
							botones += "<span id='consultar' class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						if((actualizar == 0)&&(data.hora_salida!="Sin salir")||(data.condicion=="CANCELADA")||(data.condicion=="LIBERADA"))
							botones += "<span id='editar 'class='editar btn btn-xs btn-primary waves-effect hide' disabled data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						else if((actualizar == 0)&&(data.hora_salida=="Sin salir"))
							botones += "<span id='editar 'class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						//Bloquear activar
						if(data.status == true && data.condicion=="CANCELADA" ||(data.condicion=="LIBERADA"))
							botones += "<span class=' desactivar btn btn-xs btn-warning waves-effect hide' data-toggle='tooltip' disabled title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == false && data.condicion=="CANCELADA" ||(data.condicion=="LIBERADA"))
							botones += "<span class='activar btn btn-xs btn-warning waves-effect hide' data-toggle='tooltip' disabled title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						//Activar descativar sin bloquear
						else if(data.status == true && actualizar == 0)
							botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == false && actualizar == 0)
							botones += "<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						//Bloquear eliminar
						if(borrar == 0 && data.condicion=="CANCELADA" ||(data.condicion=="LIBERADA"))
		              		botones += "<span class=' eliminar btn btn-xs btn-danger waves-effect hide' data-toggle='tooltip' disabled title='Cancelar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
		          		//Eliminar sin bloquear
		          		else if(borrar == 0 && data.condicion!="CANCELADA" ||(data.condicion=="LIBERADA"))
		              		botones += "<span class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Cancelar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
		          		return botones;
		          	}
				},
				{"data":"n_reservaciones"},
				{"data":"n_membresia"},
				{"data":"identificador_prospecto_cliente"},
				{"data":"nombre_datos_personales_cliente"},
				{"data":"sala"},
				{"data":"fecha_reservacion",
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						return cambiarFormatoFecha(fecha[0]);
	          		}
				},
				{"data":"hora_ingreso",
					render : function(data, type, row) {
						if(data!=""){
							var valor = data.date;
							fecha = valor.split(" ");
							hora = fecha[1].split(".")
							return hora[0];
						}else{
							return data;
						}		
	          		}
	          	},
				{"data":"hora_salida",
					render : function(data, type, row) {
						if(data!="Sin salir"){
							var valor = data.date;
							fecha = valor.split(" ");
							hora = fecha[1].split(".")
							return hora[0];
						}else{
							return data;
						}	
	          		}
	          	},
	          	{"data":"hora_liberada",
					render : function(data, type, row) {
						if(data!="Sin salir"){
							var valor = data.date;
							fecha = valor.split(" ");
							hora = fecha[1].split(".")
							return hora[0];
						}else{
							return data;
						}	
	          		}
	          	},
	          	{"data":"condicion"},
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
		//imagen_edi("#tabla tbody", table)
	}
/* ------------------------------------------------------------------------------- */
/*
*	Ver tabla de reservaciones
*/
function verModalReservaciones(){
	$('#modal_tabla').modal('show')
}
/*
*
*/
/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro2 para mostrar el formulario de registrar.
	*/
	function nuevoRegistro(cuadroOcultar, cuadroMostrar){
		$("#alertas").css("display", "none");
		cuadros("#cuadro1", "#cuadro2");
		limpiarCuadroSuperior();
		$("#form_reservaciones_registrar")[0].reset();
		$("#form_reservaciones_actualizar")[0].reset();
		$('#cliente_jornada_registrar').select2();
		$("#cliente_jornada_registrar option[value='']").prop("selected",true);
		$("#imagen_registrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/default-img.png');
		armarTablaModel();
	}
	/*---------------------------------------------------------------------------------*/
	function limpiarCuadroSuperior(){
		$("#tbl_numero_reservaciones").html("N")
		$("#tbl_precio").html("");
		$("#tbl_sala_registrar").html("");
		$("#tbl_condicion").html("");
		$("#tbl_fecha").html("dd-mm-yyyy");
	}
	/* ------------------------------------------------------------------------------- */
	function armarTablaModel(){
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		var table=$("#tablaModal").DataTable({
			"destroy":true,
			"stateSave": true,
			"serverSide":false,
			"ajax":{
				"method":"POST",
				"url": url + "Reservaciones/listado_reservaciones_todas",
				"dataSrc":""
			},
			"columns":[
				{"data":"n_reservaciones"},
				{"data":"n_membresia"},
				{"data":"identificador_prospecto_cliente"},
				{"data":"nombre_datos_personales_cliente"},
				{"data":"sala"},
				{"data":"fecha_reservacion",
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						return cambiarFormatoFecha(fecha[0]);
	          		}
				},
				{"data":"hora_ingreso",
					render : function(data, type, row) {
						if(data!=""){
							var valor = data.date;
							fecha = valor.split(" ");
							hora = fecha[1].split(".")
							return hora[0];
						}else{
							return data;
						}		
	          		}
	          	},
				{"data":"hora_salida",
					render : function(data, type, row) {
						if(data!="Sin salir"){
							var valor = data.date;
							fecha = valor.split(" ");
							hora = fecha[1].split(".")
							return hora[0];
						}else{
							return data;
						}	
	          		}
	          	},
	          	{"data":"hora_liberada",
					render : function(data, type, row) {
						if(data!="Sin salir"){
							var valor = data.date;
							fecha = valor.split(" ");
							hora = fecha[1].split(".")
							return hora[0];
						}else{
							return data;
						}	
	          		}
	          	},
	          	{"data":"condicion"},
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
			]
		});
	}
/*---------------------------------------------------------------------------------*/
function filtrarReservaciones(){
		//alert("aqui");
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		var table=$("#tablaModal").DataTable({
			"destroy":true,
			"stateSave": true,
			"serverSide":false,
			"ajax":{
				"method":"POST",
				"url": url + "Reservaciones/listado_reservaciones_filtros",
				"dataSrc":"",
				type:'POST',
		        dataType:'JSON',
		        data:{
	                        "fecha_hasta":fecha_hasta,
	                        "fecha_desde":fecha_desde,
	        	},
			},
			"columns":[
				{"data":"id_reservaciones"},
				{"data":"n_membresia"},
				{"data":"identificador_prospecto_cliente"},
				{"data":"nombre_datos_personales_cliente"},
				{"data":"sala"},
				{"data":"fecha_reservacion",
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						return cambiarFormatoFecha(fecha[0]);
	          		}
				},
				{"data":"hora_ingreso",
					render : function(data, type, row) {
						if(data!=""){
							var valor = data.date;
							fecha = valor.split(" ");
							hora = fecha[1].split(".")
							return hora[0];
						}else{
							return data;
						}		
	          		}
	          	},
				{"data":"hora_salida",
					render : function(data, type, row) {
						if(data!="Sin salir"){
							var valor = data.date;
							fecha = valor.split(" ");
							hora = fecha[1].split(".")
							return hora[0];
						}else{
							return data;
						}	
	          		}
	          	},
	          	{"data":"hora_liberada",
					render : function(data, type, row) {
						if(data!="Sin salir"){
							var valor = data.date;
							fecha = valor.split(" ");
							hora = fecha[1].split(".")
							return hora[0];
						}else{
							return data;
						}	
	          		}
	          	},
	          	{"data":"condicion"},
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
			]
		});
	}
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_reservaciones(){
		//enviarFormulario("#form_jornadas_registrar", 'Jornadas/registrar_jornadas', '#cuadro2');
		//---------------------------------------------------------------------------------------
		var controlador = "Reservaciones/registrar_reservaciones"
		var cuadro = '#cuadro2'
		var form = "#form_reservaciones_registrar"
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
                    	//---
                        mensajes('danger', respuesta.message);
                        $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    	//---
                    }else{
                    	//---
                        $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                        mensajes('success', respuesta);
                        if(cuadro!=""){
                            listar(cuadro);
						}
                        //---
                    }
                }
            });
        });
		//---------------------------------------------------------------------------------------
	}
	
	
	
/* ------------------------------------------------------------------------------- */
/*
* 	Grupo de funciones para ver el desarrollo de la jornada...
*/
   	/*

		Funcion que muestra el cuadro3 para la consulta
	*/
	function ver(tbody, table){
		//---------------------------------------
		$("#form_reservaciones_consultar")[0].reset();

		$(tbody).on("click", "span.consultar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			console.log(data);
			$("#cliente_jornada_consultar option[value='" + data.id_membresia   + "']").prop("selected",true);
			consultarMembresiaConsultar(data.id_membresia)
			//$("#sala_consultar option[value='" + data.id_servicio_sala   + "']").prop("selected",true);
			infoSalasConsultar(data.id_servicio_sala);
			$("#tbl_precio_consultar").html(data.precio);
			//-- Hora inicio reservacion
			hora_inicio = data.hora_inicio
			if(hora_inicio!=""){
				var valor = hora_inicio.date;
				fecha = valor.split(" ");
				hora = fecha[1].split(".")
				hora_inicio_reservacion = hora[0];
			}else{
				hora_inicio_reservacion = "";
			}
			//--Hora Fin  reservacion...
			hora_fin = data.hora_fin
			if(hora_inicio!=""){
				var valor = hora_fin.date;
				fecha_fin = valor.split(" ");
				hora = fecha_fin[1].split(".")
				hora_fin_reservacion = hora[0];
			}else{
				hora_fin_reservacion = "";
			}
			//--Fecha reservacion...
			fecha_r = data.fecha_reservacion
			if(fecha_r!=""){
				var valor = fecha_r.date;
				fecha_vector = valor.split(" ");
				fecha_v = fecha_vector[0].split(".")
				fecha_reservacion = fecha_v[0];
			}else{
				fecha_reservacion = "";
			}
			//--
			$("#tbl_condicion_consultar").html(data.condicion);
			$("#tbl_numero_reservaciones_consultar").html(data.n_reservaciones);
			$("#fecha_reservacion_consultar").val(componer_fecha(fecha_reservacion));
			$("#tbl_fecha_consultar").html(componer_fecha(fecha_reservacion));
			$("#hora_inicio_reservacion_consultar").val(hora_inicio_reservacion);
			$("#hora_fin_reservacion_consultar").val(hora_fin_reservacion);
			$("#hora_contratadas_reservacion_consultar").val(data.horas_contratadas)
			$("#hora_consumidas_reservacion_consultar").val(data.horas_consumidas2)
			$("#hora_por_consumir_reservacion_consultar").val(data.horas_disponibles)

			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
/* 
		Funcion que muestra el cuadro4 para editar
	*/
	function editar(tbody, table){
		//---------------------------------------
		$("#form_reservaciones_actualizar")[0].reset();
			
		$(tbody).on("click", "span.editar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			console.log(data);
			$("#cliente_jornada_actualizar option[value='" + data.id_membresia   + "']").prop("selected",true);
			consultarMembresiaActualizar(data.id_membresia)
			//$("#sala_actualizar option[value='" + data.id_servicio_sala   + "']").prop("selected",true);
			infoSalasActualizar(data.id_servicio_sala);
			$("#tbl_precio_actualizar").html(data.precio);
			//-- Hora inicio reservacion
			hora_inicio = data.hora_inicio
			if(hora_inicio!=""){
				var valor = hora_inicio.date;
				fecha = valor.split(" ");
				hora = fecha[1].split(".")
				hora_inicio_reservacion = hora[0];
			}else{
				hora_inicio_reservacion = "";
			}
			//--Hora Fin  reservacion...
			hora_fin = data.hora_fin
			if(hora_inicio!=""){
				var valor = hora_fin.date;
				fecha_fin = valor.split(" ");
				hora = fecha_fin[1].split(".")
				hora_fin_reservacion = hora[0];
			}else{
				hora_fin_reservacion = "";
			}
			//--Fecha reservacion...
			fecha_r = data.fecha_reservacion
			if(fecha_r!=""){
				var valor = fecha_r.date;
				fecha_vector = valor.split(" ");
				fecha_v = fecha_vector[0].split(".")
				fecha_reservacion = fecha_v[0];
			}else{
				fecha_reservacion = "";
			}
			//--Hora de ingreso para modificar la condiciones
			hora_ingreso_r = data.hora_ingreso
			if(hora_ingreso_r!=""){
				var valor = hora_ingreso_r.date;
				fecha = valor.split(" ");
				hora = fecha[1].split(".")
				hora_ingreso = hora[0];
				$("#btn-salir").css({"display":"inline-block"})
				$("#btn-ingresar").css({"display":"none"})
			}else{
				$("#btn-salir").css({"display":"none"})
				$("#btn-ingresar").css({"display":"inline-block"})
			}	
			//--
			$("#tbl_condicion_actualizar").html(data.condicion);
			$("#tbl_numero_reservaciones_actualizar").html(data.n_reservaciones);
			$("#fecha_resevacion_actualizar").val(componer_fecha(fecha_reservacion));
			$("#tbl_fecha_actualizar").html(componer_fecha(fecha_reservacion));
			$("#hora_inicio_reservacion_actualizar").val(hora_inicio_reservacion);
			$("#hora_fin_reservacion_actualizar").val(hora_fin_reservacion);
			$("#id_reservaciones_actualizar").val(data.id_reservaciones);
			//--campos deshabilitados...
			$("#hora_contratadas_reservacion_actualizar").val(data.horas_contratadas)
			$("#hora_consumidas_reservacion_actualizar").val(data.horas_consumidas2)
			$("#hora_por_consumir_reservacion_actualizar").val(data.horas_disponibles)

			//--
			cuadros('#cuadro1', '#cuadro4');
			//--
			armarTablaModel();
		});
		//---------------------------------------
	}
	/*-----------------------------------------------------------------------------*/
	function ingresarReservacion(){
		//--
		var id_reservaciones = $("#id_reservaciones_actualizar").val()
		var controlador = "Reservaciones/IngresarReservaciones"
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
	    //----------------------------------------------------
	    $.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
                        "id_reservaciones":id_reservaciones        	},
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
				mensajes('success', respuesta);
                regresar('#cuadro4')
			}	
		});
		//--
	}
/*---------------------------------------------------------------------------------*/	

function salirReservacion(){
		//--
		var id_reservaciones = $("#id_reservaciones_actualizar").val()
		var controlador = "Reservaciones/marcarSalida"
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
	    //----------------------------------------------------
	    $.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
                        "id_reservaciones":id_reservaciones        	},
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
				//mensajes('success', respuesta);
				calcularHorasaPagar()
                regresar('#cuadro4')
			}	
		});
		//--
	}
/*----------------------------------------------------------------------------------*/
	/*
	*	calcularHorasaPagar
	*/
	function calcularHorasaPagar(){
		monto = $("#tbl_precio_actualizar").html();
		min_tolerancia = $("#minutos_tolerancia").val(),
		horas_contratadas = $("#hora_contratadas_reservacion_actualizar").val();
		horas_consumidas = $("#hora_consumidas_reservacion_actualizar").val();
		//---
		var id_reservaciones = $("#id_reservaciones_actualizar").val()
		var id_membresia = $("#cliente_jornada_actualizar").val()
		//---
		var controlador = "Reservaciones/calcularHorasPagar"
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		//-------------------------------------------------------------------------
		 $.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
                  		"monto":monto,      
                        "min_tolerancia":min_tolerancia,
                        "horas_contratadas":horas_contratadas,
                        "horas_consumidas":horas_consumidas,
                        "id_reservaciones":id_reservaciones,
                        "id_membresia":id_membresia,
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
	        	//alert(respuesta.minutos);
	            console.log(respuesta.horas);
	            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
				//---
				swal({
	                title: respuesta.mensajes,
	                type: "success",
	                confirmButtonColor: "#DD6B55",
	                confirmButtonText: "Aceptar!",
	                closeOnConfirm: true
	            });
								
                //---
			}	
		});
		//-------------------------------------------------------------------------
	}	
/* ------------------------------------------------------------------------------- */
/*
	Funcion que realiza el envio del formulario de registro
*/
function actualizar_reservaciones(){
	enviarFormulario("#form_reservaciones_actualizar", 'Membresia/actualizar_membresia', '#cuadro4');
}
/* ------------------------------------------------------------------------------- */

	
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacionReservaciones(url+'Reservaciones/cancelar_reservaciones/', data.id_reservaciones, "");
        });
	}
	/*----------------------------------------------------------------------------------*/
	/*
	*	calcularHorasCancelar
	*/
	function calcularHorasCancelar(id_reservaciones){
		min_tolerancia = $("#minutos_tolerancia").val()
		var controlador = "Reservaciones/calcularHorasCancelar"
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		//-------------------------------------------------------------------------
		$.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
	        	        "id_reservaciones":id_reservaciones,
                        "min_tolerancia":min_tolerancia,
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
	            console.log(respuesta.minutos);
	            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
				//---
				if(respuesta.opcion=="1"){
					swal({
		                title: respuesta.mensajes,
		                type: "success",
		                confirmButtonColor: "#DD6B55",
		                confirmButtonText: "Aceptar!",
		                closeOnConfirm: true
		            });
				}
			}	
		});
		//-------------------------------------------------------------------------
	}	
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
/*
*	Funcion para eliminar segun confirmacion
*/
function eliminarConfirmacionReservaciones(controlador, id, title){
    $('#modal_mensaje_eliminar').modal('show')
    $("#id_reservaciones_modal").html(id);
    $("#motivo_cancelacion_reservacion").val("");

}
/* ------------------------------------------------------------------------------- */
/*
	Funcion que capta y envia los datos a desactivar
*/
function desactivar(tbody, table){
	$(tbody).on("click", "span.desactivar", function(){
        var data=table.row($(this).parents("tr")).data();
        statusConfirmacion('Reservaciones/status_reservaciones', data.id_reservaciones, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
    });
}


/*
	Funcion que realiza el envio del formulario de registro
*/
function cancelar_reservacion_motivo(){
	
	var id_reservaciones = $("#id_reservaciones_modal").html()
	
	var caja = $("#motivo_cancelacion_reservacion").val()
	if(caja!=""){
		//---------------------------------------------------------------------------------------
		var controlador = "Reservaciones/cancelar_reservaciones"
		var cuadro = '#cuadro2'
	    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
	    $.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
	                    "id_reservaciones":id_reservaciones,
	                    "motivo":caja
	    	},
	        cache:false,
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
	            	//---
	                mensajes('danger', respuesta.message);
	                $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
	            	//---
	            }else{
	            	//---
	                $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
	                mensajes('success', respuesta);
	                
	                calcularHorasCancelar(id_reservaciones)

	                $("#id_reservaciones_modal").val("");
	                if(cuadro!=""){
	                    listar(cuadro);
					}
	                //---
	            }
	        }
	    });
		//---------------------------------------------------	
	}else{
		 mensajes('danger', "Debe ingresar un motivo a la cancelación!");

	}
	//---------------------------------------------------------------------------------------
}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function activar(tbody, table){
		$(tbody).on("click", "span.activar", function(){
            var data=table.row($(this).parents("tr")).data();
            statusConfirmacion('Reservaciones/status_reservaciones', data.id_reservaciones, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}

/* ------------------------------------------------------------------------------- */
/*
*	Funcion para realizar consulta del cliente
*/
function consultarMembresia(){
	membresia = $("#cliente_jornada_registrar").val();
	//alert("xxx-Membresia:"+membresia);
	if(membresia!=""){
		var form  = "#form_reservaciones_registrar"
		var controlador = "Reservaciones/consultarMembresia"
	    //e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
	    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
	    var method = $(this).attr('method'); //obtiene el method del formulario
	    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
	    $.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
                        "id_membresia":membresia,
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
					//--Para mostrar la imagen del cliente
					if(respuesta[0]["imagenCliente"]!=""){
						$("#imagen_registrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/'+respuesta[0]["imagenCliente"]
					);
					}else{
						$("#imagen_registrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/default-img.png');
					}
					//---
					///---
			    }else{
					mensajes('danger', "<span>No hay registros asociados al identificador consultado</span>"); 
					if(tipo_per=="fisica"){
						$("#cliente_jornada_registrar").val("").focus()
					}else{
						$("#cliente_jornada_registrar").val("").focus()
					}

				}
			}	
		});	
	}else{
		warning('Debe ingresar el identificador de cliente/prospecto');
	}
	
}
/* ------------------------------------------------------------------------------- */
/*
*	Funcion para realizar consulta del cliente al actualizar
*/
function consultarMembresiaActualizar(membresia){
	//alert("xxx-Membresia:"+membresia);
	if(membresia!=""){
		var form  = "#form_reservaciones_actualizar"
		var controlador = "Reservaciones/consultarMembresia"
	    //e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
	    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
	    var method = $(this).attr('method'); //obtiene el method del formulario
	    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
	    $.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
                        "id_membresia":membresia,
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
						$("#cliente_jornada_actualizar").val("").focus()
					}else{
						$("#cliente_jornada_actualizar").val("").focus()
					}
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
/* ------------------------------------------------------------------------------- */
/*
*	Funcion para realizar consulta del cliente al consultar
*/
function consultarMembresiaConsultar(membresia){
	//alert("xxx-Membresia:"+membresia);
	if(membresia!=""){
		var form  = "#form_reservaciones_consultar"
		var controlador = "Reservaciones/consultarMembresia"
	    //e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
	    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
	    var method = $(this).attr('method'); //obtiene el method del formulario
	    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
	    $.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
                        "id_membresia":membresia,
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
					//--Para mostrar la imagen del cliente
					if(respuesta[0]["imagenCliente"]!=""){
						$("#imagen_consultar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/'+respuesta[0]["imagenCliente"]
					);
					}else{
						$("#imagen_consultar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/default-img.png');
					}
					//---
			    }else{
					mensajes('danger', "<span>No hay registros asociados al identificador consultado</span>"); 
					if(tipo_per=="fisica"){
						$("#cliente_jornada_consultar").val("").focus()
					}else{
						$("#cliente_jornada_consultar").val("").focus()
					}
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
/*
*	Componer fecha
*/
function componer_fecha(fecha){
	var vector_fecha = fecha.split(" ");
	var fecha = vector_fecha[0].split("-");
	return fecha[2]+"-"+fecha[1]+"-"+fecha[0]
}
/*
*	Función para consultar planes
*/
function consultarPlan(plan,proceso){
	var controlador = "Jornadas/consultarPlan"
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
			if(proceso=="guardar"){
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
	            if (respuesta[0].condicion == true) {
					$("#plan_activo").attr("checked","checked");
				}else{
					$("#plan_activo").removeAttr("checked");
				}
			}else if(proceso=="actualizar"){
				$("#horas_jornadasE").html(respuesta[0]["horas_jornadas"]);
	            $("#precio_planE").html(respuesta[0]["valor"]);
	            $("#fecha_inicioE").html(respuesta[0]["inicio"]);
	            $("#fecha_finE").html(respuesta[0]["vigencia"]);
	            //Doy valor a las cajas para el envio por POST
	            $("#plan_horasE").val(respuesta[0]["horas_jornadas"]);
	            $("#plan_valorE").val(respuesta[0]["valor"]);
	            $("#plan_fecha_inicioE").val(respuesta[0]["inicio"]);
	            $("#plan_fecha_finE").val(respuesta[0]["vigencia"]);
	            //--
	            if (respuesta[0].condicion == true) {
					$("#plan_activoE").attr("checked","checked");
				}else{
					$("#plan_activoE").removeAttr("checked");
				}
			}
			else if(proceso=="mostrar"){
				$("#horas_jornadasC").html(respuesta[0]["horas_jornadas"]);
	            $("#precio_planC").html(respuesta[0]["valor"]);
	            $("#fecha_inicioC").html(respuesta[0]["inicio"]);
	            $("#fecha_finC").html(respuesta[0]["vigencia"]);
	            //Doy valor a las cajas para el envio por POST
	            $("#plan_horasC").val(respuesta[0]["horas_jornadas"]);
	            $("#plan_valorC").val(respuesta[0]["valor"]);
	            $("#plan_fecha_inicioC").val(respuesta[0]["inicio"]);
	            $("#plan_fecha_finC").val(respuesta[0]["vigencia"]);
	            //--
	            if (respuesta[0].condicion == true) {
					$("#plan_activoC").attr("checked","checked");
				}else{
					$("#plan_activoC").removeAttr("checked");
				}
			}
		}	
	});	
	

}
/*------------------------------------------------------------------------------------------------------------------------------*/