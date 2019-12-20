$(document).ready(function(){
	listar();
	registrar_jornadas()
	actualizar_jornadas()
	$('#cliente_jornada_registrar').select2();
	limpiar_form_recargos()
	//actualizar_membresia();	
	/*$('#clabe_registrar').click(function(){
		$(".guardado").attr("required", true)	
	})*/
	if($("#id_membresia_from_to").val()!=""){
		nuevoRegistro();
		consultarMembresia();
	}
});
/*
*	Limpiar fo|rm recargos
*/
function limpiar_form_recargos(){
	$("#monto_total_recargo, #monto_pagar").val("0.00");
	$("#monto_total_recargo_oculto, #monto_pagar_oculto").val("0");
	$("#arreglo_servicios_contratados").attr("data","0")
	$("#arreglo_servicios_opcionales").attr("data2","0")
	$("#arreglo_servicios_contratados,#arreglo_servicios_opcionales").html("");
}
function limpiar_form_recargos_actualizar(){
	$("#arreglo_servicios_contratados").attr("data","0")
	$("#arreglo_servicios_opcionales").attr("data2","0")
	$("#arreglo_servicios_contratados,#arreglo_servicios_opcionales").html("");
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
				"url": url + "Jornadas/listado_jornadas",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_jornada",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data": null,
					render : function(data, type, row) {
						var botones = "";
						if(consultar == 0)
							botones += "<span id='consultar' class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						if((actualizar == 0)&&(data.hora_salida!="Sin salir"))
							botones += "<span id='editar 'class='editar btn btn-xs btn-primary waves-effect disabled' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						else if((actualizar == 0)&&(data.hora_salida=="Sin salir"))
							botones += "<span id='editar 'class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						if(data.status == true && actualizar == 0)
							botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == false && actualizar == 0)
							botones += "<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						if(borrar == 0)
		              		botones += "<span class='eliminar btn btn-xs btn-danger waves-effect disabled' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
		          		return botones;
		          	}
				},
				{"data":"fec_regins",
					render : function(data, type, row) {
						var valor = data.date;
						a = valor.split(".");
						dato = a[0].split(" ")
						fecha = cambiarFormatoFecha(dato[0]);
						dato2 = dato[1].split(".")
						sinEspacio = dato2[0];
						id = fecha+sinEspacio
						singuion = id.replace(/-/g,"");
						b = singuion.replace(/:/g,"");
						return b;
	          		}
				},
				{"data":"identificador_prospecto_cliente"},
				{"data":"nombre_datos_personales_cliente"},
				{"data":"planes"},
				{"data":"hora_ingreso",
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						hora = fecha[1].split(".")
						return hora[0];
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

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro2 para mostrar el formulario de registrar.
	*/
	function nuevoRegistro(cuadroOcultar, cuadroMostrar){
		$("#alertas").css("display", "none");
		cuadros("#cuadro1", "#cuadro2");
		$("#form_jornadas_registrar")[0].reset();
		$("#form_jornadas_actualizar")[0].reset();
		$('#cliente_jornada_registrar').select2();
		limpiar_form_recargos();
	}
	/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_jornadas(){
		//enviarFormulario("#form_jornadas_registrar", 'Jornadas/registrar_jornadas', '#cuadro2');
		//---------------------------------------------------------------------------------------
		var controlador = "Jornadas/registrar_jornadas"
		var cuadro = '#cuadro2'
		var form = "#form_jornadas_registrar"
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
                    }else{
                        $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                        mensajes('success', respuesta);
                        //---
                        if($("#id_membresia_from_to").val()!=""){
							window.location=base_url+"membresia";
						}else if(cuadro!=""){
                            listar(cuadro);
						}
						//---
                    }
                }
            });
        });
		//---------------------------------------------------------------------------------------
	}
	/*
	*	Registro de recargos...
	*/
	function registrarRecargos(){
		//--
		var id_jornada = $("#id_jornada").val();
		var id_membresia = $("#cliente_jornada_actualizar").val();
		var arreglo_servicios_opcionales = $("#arreglo_servicios_opcionales").attr("data2");
		var arreglo_servicios_contratados = $("#arreglo_servicios_contratados").attr("data");
		var monto_total_recargo = $("#monto_total_recargo_oculto").val();
		var monto_pagar = $("#monto_pagar_oculto").val();
		//--------------------------------------------------------------------------------------------
		if((arreglo_servicios_opcionales!="")||(arreglo_servicios_contratados!="")&&(monto_total_recargo!="") || (monto_pagar!="")){
			var form  = "#form_recargos_actualizar"
			var controlador = "Jornadas/registrarRecargos"
		    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		    var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
		    var method = $(this).attr('method'); //obtiene el method del formulario
		    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
		    $.ajax({
		        url:url+controlador,
		        type:'POST',
		        dataType:'JSON',
		        data:{
	                        "id_jornada":id_jornada,
	                        "id_membresia":id_membresia,
	                        "arreglo_servicios_opcionales":arreglo_servicios_opcionales,
	                        "arreglo_servicios_contratados":arreglo_servicios_contratados,
	                        "monto_total_recargo":monto_total_recargo,
	                        "monto_pagar":monto_pagar,
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
		            //---
                    mensajes('success', respuesta);
           			$('html, body').animate({scrollTop:0}, 1250);
                    $(".eliminar").attr("disabled",true)
                    $("#tipo_registro").val("actualizar")
                    limpiar_form_recargos_actualizar();




		            //---
		            //--Asigno valores a ultimos monto guardado
		            var monto_total_oculto =  $("#monto_total_recargo_oculto").val()-$("#servicios_contratados_oculto").val();
		            $("#ultimo_monto_total_guardado").val(monto_total_oculto);
		            //--Asigno valores a ultimo monto pagado
		            var monto_pagar_oculto = $("#monto_pagar_oculto").val();
		            $("#ultimo_monto_pagar_guardado").val(monto_pagar_oculto);
                    consultarMembresiaActualizar();
		            //---
				}	
			});	
		}else{
			warning('Debe Seleccionar algun servicio para registrar el recargo');
		}
		//------------------------------------------------------------------------------------
	}
	/*
	*
	*/
	/*
	*	Marcar salida
	*/
	function marcarSalida(){
		var	id_jornadas =$("#id_jornada").val()
		var id_membresia = $("#cliente_jornada_actualizar").val()
		var controlador = "Jornadas/marcarSalida"
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
	    //----------------------------------------------------
	    $.ajax({
	        url:url+controlador,
	        type:'POST',
	        dataType:'JSON',
	        data:{
                        "id_membresia":id_membresia,
                        "id_jornadas":id_jornadas,
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
				//--
                var tabla_servicios = $("#contenedorTablaRegistrar").html()
                var total_pagar = $("#monto_pagar").val();
                var total_pagar_oculto = parseInt($("#monto_pagar_oculto").val());
                if(total_pagar_oculto>0){
                	$("#tabla_modal").html(tabla_servicios)
               		$("#monto_pagar_modal").val(total_pagar)
   	            	$('#modal_mensaje').modal('show')
                }
	            //--
	            mensajes('success', respuesta);
                regresar('#cuadro4')
                limpiar_form_recargos();
			}	
		});
	    //----------------------------------------------------
	}
	/*function enviarFormularioCliente(form, controlador, cuadro){
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
    }*/
/* ------------------------------------------------------------------------------- */
/*
* 	Grupo de funciones para ver el desarrollo de la jornada...
*/
   	/*

		Funcion que muestra el cuadro3 para la consulta
	*/
	function ver(tbody, table){
		//---------------------------------------
		$("#form_jornadas_mostrar")[0].reset();

		$(tbody).on("click", "span.consultar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			$("#cliente_jornada_mostrar option[value='" + data.id_membresia   + "']").prop("selected",true);
			console.log(data);
			var id_membresia = data.id_membresia;
			var id_jornada = data.id_jornada;
			$("#id_jornada_mostrar").val(id_jornada);
			/*urlRecargos = base_url+'Jornadas/recargos/'+id_membresia
			$('#iframeRecargos').attr('src',urlRecargos)*/
			cuadros('#cuadro1', '#cuadro3');
			consultarMembresiaVer()
		});
	}
	/*
		Función que se encarga de mostrar datos de membresia...
	*/
	function consultarMembresiaVer(){
		membresia = $("#cliente_jornada_mostrar").val();
		jornadas = $("#id_jornada_mostrar").val();
		//alert("Membresia:"+membresia);
		if(membresia!=""){
			var form  = "#form_jornadas_mostrar"
			var controlador = "Jornadas/consultarMembresia"
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
	                        "id_jornadas":jornadas,
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
						///--Renderizo cuadro de planes....
						
						var objeto_fecha_inicio = respuesta[0]["fecha_inicio"];

						var objeto_fecha_fin = respuesta[0]["fecha_fin"];
						//alert(objeto_fecha_inicio.date+"-"+objeto_fecha_fin.date);
						fecha_inicio = componer_fecha(objeto_fecha_inicio.date);
						
						fecha_fin = componer_fecha(objeto_fecha_fin.date)
						
						$("#numero_membresia_mostrar").html(respuesta[0]["n_membresia"]);
						
						$("#plan_mostrar").html(respuesta[0]["planes"]);
						
						$("#horas_jornadas_mostrar").html(respuesta[0]["plan_horas_jornadas"]);
						
						$("#plan_valor_mostrar").html(respuesta[0]["plan_valor"]);
						
						$("#fecha_inicio_mostrar").html(fecha_inicio);
						
						$("#fecha_fin_mostrar").html(fecha_fin);

						$("#horas_consumidas_mostrar").html(respuesta[0]["horas_transcurridas"]);

						$("#horas_disponibles_mostrar").html(respuesta[0]["horas_disponibles"]);

						mostrarServiciosPaqueteMostrar(respuesta[0]["servicios"])

						var servicios_opcionales = respuesta[0]["servicios_opcionales"]

						mostrarServiciosOpcionalesMostrar(respuesta[0]["servicios_opcionales"])

						mostrarArregloMontosMostrar(respuesta[0]["arreglo_montos"])
						//Muestro los datos del cliente
						$("#nombre_datos_personales_mostrar").val(respuesta[0]["solo_nombre"]);
						$("#apellido_p_datos_personales_mostrar").val(respuesta[0]["solo_apellidos_paternos"]);
						$("#apellido_m_datos_personales_mostrar").val(respuesta[0]["solo_apellidos_maternos"]);
						///---
						//--Para mostrar la imagen del cliente
						if(respuesta[0]["imagenCliente"]!=""){
							$("#imagen_mostrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/'+respuesta[0]["imagenCliente"]
						);
						}else{
							$("#imagen_mostrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/default-img.png');
						}
						//---
						///---
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
	*	Mostrar servicios paquetes...
	*/
	function mostrarServiciosPaqueteMostrar($servicios){
		var tbody =  "";


		$.each($servicios, function( index, value ) {
			
			if(value.codigo != "SAL" && value.codigo != "HC" ){
			tbody+="<tr class='tr_servicios_mostrar' data='"+value.id_servicios+"'>\
	                    <th>"+value.codigo+"</th>\
	                    <th>"+value.titulo+"</th>\
	                    <th>"+value.cantidad+"</th>\
	                    <th>"+new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(value.costo)+"</th>\
	                    <th>"+value.consumido+"</th>\
	                    <th>"+value.disponible+"</th>\
	                    <th style='display:none'>"+value.costo+"</th>\
	                </tr>"
			}
	    });
	    $("#tbody_servicios_mostrar").html(tbody);
	}
	/*
	*	Mostrar Servicios Opcionales Mostrar
	*/
	function mostrarServiciosOpcionalesMostrar($arreglo_servicios){
		var tbody =  "";
		html2 = "";
		$.each($arreglo_servicios, function( index, value ) {
			//----
			//Agrego en la tabla de abajo el restante
			html2 += "<tr id='r" + value.id_servicios + "'><td>" + value.codigo + "</td>";
			html2 += "<td>"+value.titulo + " <input type='hidden' class='id_servicio' name='id_servicio' value='" + value.id_servicios + "'></td>";
			html2 += "<td id='r_valor_servicio" + value.id_servicios + "' class='valor_servicio' >" + value.cantidad + "</td>";
			html2 += "<td id='r_costo_servicio" + value.id_servicios + "' class='costo_servicio' style='text-align:right' >" + value.costo + "</td>";
			html2 += "<td id='r_toral_servicio" + value.id_servicios + "' class='total_servicio' style='text-align:right' >" + value.total_servicio + "</td>";
			//---
			$monto_pagar = value.monto_pagar
			$monto_total_recargo = value.monto_total_recargo
			$monto_pagar_oculto = value.monto_pagar_sin_comas
			$monto_total_recargo_oculto = value.monto_total_recargo_sin_comas
	    });
	    $("#tableMostrar tbody").html(html2);
	    //--
	}
	/*
	*	mostrarArregloMontosMostrar
	*/
	function mostrarArregloMontosMostrar(arreglo_montos){
		$("#monto_pagar_mostrar").val(arreglo_montos["monto_pagar"]);
	    $("#monto_total_recargo_mostrar").val(arreglo_montos["monto_total_recargo"]);
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
/* 
		Funcion que muestra el cuadro4 para editar
	*/
	function editar(tbody, table){
		//---------------------------------------
		$("#form_jornadas_actualizar")[0].reset();
		limpiar_form_recargos();
		
		$(tbody).on("click", "span.editar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			$("#cliente_jornada_actualizar option[value='" + data.id_membresia   + "']").prop("selected",true);
			var id_membresia = data.id_membresia;
			var id_jornada = data.id_jornada;
			$("#id_jornada").val(id_jornada);
			$(".pestana_datosTrabajadoresC").show();
			/*urlRecargos = base_url+'Jornadas/recargos/'+id_membresia
			$('#iframeRecargos').attr('src',urlRecargos)*/
			cuadros('#cuadro1', '#cuadro4');
            limpiar_form_recargos();
			consultarMembresiaActualizar()
		});
		//---------------------------------------
	}
/* ------------------------------------------------------------------------------- */
/*
*	consultarMembresiaActualiar
*/
function consultarMembresiaActualizar(){
	membresia = $("#cliente_jornada_actualizar").val();
	jornadas = $("#id_jornada").val();
	//alert("Membresia:"+membresia);
	if(membresia!=""){
		var form  = "#form_jornadas_actualizar"
		var controlador = "Jornadas/consultarMembresia"
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
                        "id_jornadas":jornadas,
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
					///--Renderizo cuadro de planes....
					
					var objeto_fecha_inicio = respuesta[0]["fecha_inicio"];

					var objeto_fecha_fin = respuesta[0]["fecha_fin"];
					//alert(objeto_fecha_inicio.date+"-"+objeto_fecha_fin.date);
					fecha_inicio = componer_fecha(objeto_fecha_inicio.date);
					
					fecha_fin = componer_fecha(objeto_fecha_fin.date)
					
					$("#numero_membresia_actualizar").html(respuesta[0]["n_membresia"]);
					
					$("#plan_actualizar").html(respuesta[0]["planes"]);
					
					$("#horas_jornadas_actualizar").html(respuesta[0]["plan_horas_jornadas"]);
					
					$("#plan_valor_actualizar").html(respuesta[0]["plan_valor"]);
					
					$("#fecha_inicio_actualizar").html(fecha_inicio);
					
					$("#fecha_fin_actualizar").html(fecha_fin);

					$("#horas_consumidas_actualizar").html(respuesta[0]["horas_transcurridas"]);

					$("#horas_disponibles_actualizar").html(respuesta[0]["horas_disponibles"]);

					mostrarServiciosPaquete(respuesta[0]["servicios"])

					var servicios_opcionales = respuesta[0]["servicios_opcionales"]

					mostrarServiciosOpcionales(respuesta[0]["servicios_opcionales"])
				
					mostrarArregloMontos(respuesta[0]["arreglo_montos"])
					//--
					//Muestro los datos del cliente
					$("#nombre_datos_personales_actualizar").val(respuesta[0]["solo_nombre"]);
					$("#apellido_p_datos_personales_actualizar").val(respuesta[0]["solo_apellidos_paternos"]);
					$("#apellido_m_datos_personales_actualizar").val(respuesta[0]["solo_apellidos_maternos"]);
					///---
					if(respuesta[0]["imagenCliente"]!=""){
						$("#imagen_actualizar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/'+respuesta[0]["imagenCliente"]
					);
					}else{
						$("#imagen_actualizar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/default-img.png');
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
*	Funcion que muestra los servicios en la pestaña de recargas
*/
function mostrarServiciosPaquete(servicios){
	var tbody =  "";
	$.each(servicios, function( index, value ) {
		value.titulo = value.titulo.normalize('NFD').replace(/[\u0300-\u036f]/g,"");

   	if(value.codigo != "SAL" && value.codigo != "HC" ){
		tbody+="<tr class='tr_servicios' data='"+value.id_servicios+"'>\
                    <th>"+value.codigo+"<input type='hidden' class='categoria' value='"+value.categoria+"'>"+"<input type='hidden' class='categoria_case' value='"+value.titulo+"'>"+
                    "</th>\
                    <th>"+value.titulo+"</th>\
                    <th>"+value.cantidad+"</th>\
                    <th>"+new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(value.costo)+"</th>\
                    <th>"+value.consumido+"</th>\
                    <th class='service_cantidad'>"+value.disponible+"</th>\
                    <th style='display:none'>"+value.costo+"</th>\
                </tr>"
            }
    });
    $("#tbody_servicios").html(tbody);

}
/*
*	Mostrar servicios opcionales
*/
function mostrarServiciosOpcionales($arreglo_servicios){
	var tbody =  "";
	html2 = "";

	$.each($arreglo_servicios, function( index, value ) {
		//----
		//Agrego en la tabla de abajo el restante
		html2 += "<tr id='r" + value.id_servicios + "'><td>" + value.codigo + "</td>";
		html2 += "<td>"+value.titulo + " <input type='hidden' class='id_servicio' name='id_servicio' value='" + value.id_servicios + "'></td>";
		html2 += "<td id='r_valor_servicio" + value.id_servicios + "' class='valor_servicio' >" + value.cantidad + "</td>";
		html2 += "<td id='r_costo_servicio" + value.id_servicios + "' class='costo_servicio' style='text-align:right' >" + value.costo + "</td>";
		html2 += "<td id='r_toral_servicio" + value.id_servicios + "' class='total_servicio' style='text-align:right' >" + value.total_servicio + "</td>";
		html2 += "<td class='tableRegistrar'><button type='button' class='campos_acciones eliminar btn btn-xs btn-danger waves-effect disabled' ata-toggle='tooltip' title='Eliminar' onclick='eliminarServicios(\"" + "#r" + value.id_servicios + "\",\"" +value.id_servicios+ "\")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button></td></tr>";
		//---
		$monto_pagar = value.monto_pagar
		$monto_total_recargo = value.monto_total_recargo
		$monto_pagar_oculto = value.monto_pagar_sin_comas
		$monto_total_recargo_oculto = value.monto_total_recargo_sin_comas


		$("#tbody_servicios tr").each(function(i, item){
			var cat = $(this).find(".categoria").val();
			
			if (cat == value.categoria) {

				var cant  = $(this).find(".service_cantidad").text();
				var resta = cant - value.cantidad;

				$(this).find(".service_cantidad").text(resta);
			}
		});


	    $("#tipo_registro").val("actualizar");
    });
    $("#tableRegistrar tbody").html(html2);
   
    //--
}
/*
*	Mostrar arreglo montos
*/
function mostrarArregloMontos($arreglo_montos){
	$("#monto_total_recargo").val(0);
	if($("#tipo_registro").val()=="actualizar"){
		
		if ($arreglo_montos["monto_pagar"]!=""){
			$("#monto_pagar").val($arreglo_montos["monto_pagar"]);
		}

		if ($arreglo_montos["monto_pagar_sin_comas"]!=""){
	    	$("#monto_pagar_oculto").val($arreglo_montos["monto_pagar_sin_comas"]);
	    }

	    if ($arreglo_montos["monto_total_recargo"]!=""){

	   		$("#monto_total_recargo").val($arreglo_montos["monto_total_recargo"]);
	    }

	    if ($arreglo_montos["monto_total_recargo_sin_comas"]!=""){
	    	$("#monto_total_recargo_oculto").val($arreglo_montos["monto_total_recargo_sin_comas"]);
	    }
	    
	    //--Asigno valores a ultimos monto guardado
        var monto_total_oculto =  $("#monto_total_recargo_oculto").val();
        $("#ultimo_monto_total_guardado").val(monto_total_oculto);
        //--Asigno valores a ultimo monto pagado
        var monto_pagar_oculto = $("#monto_pagar_oculto").val();
        $("#ultimo_monto_pagar_guardado").val(monto_pagar_oculto);
        //---
	}
}
/* ------------------------------------------------------------------------------- */
/*
	Funcion que realiza el envio del formulario de registro
*/
function actualizar_jornadas(){
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
            statusConfirmacion('Jornadas/status_jornadas', data.id_jornada, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Jornadas/status_jornadas', data.id_jornada, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}

/* ------------------------------------------------------------------------------- */


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
function consultarMembresia(){
	membresia = $("#cliente_jornada_registrar").val();
	//alert("xxx-Membresia:"+membresia);
	if(membresia!=""){
		var form  = "#form_jornada_registrar"
		var controlador = "Jornadas/consultarMembresia"
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
   					$("#grupo_empresarial_jornada_registrar option[value='" + respuesta[0]["id_grupo_empresarial"] + "']").prop("selected",true);
					///--Renderizo cuadro de planes....
					
					var objeto_fecha_inicio = respuesta[0]["fecha_inicio"];

					var objeto_fecha_fin = respuesta[0]["fecha_fin"];
					//alert(objeto_fecha_inicio.date+"-"+objeto_fecha_fin.date);
					fecha_inicio = componer_fecha(objeto_fecha_inicio.date);
					
					fecha_fin = componer_fecha(objeto_fecha_fin.date)
					
					$("#numero_membresia").html(respuesta[0]["n_membresia"]);
					
					$("#plan").html(respuesta[0]["planes"]);
					
					$("#horas_jornadas").html(respuesta[0]["plan_horas_jornadas"]);

					$("#horas_consumidas").html(respuesta[0]["horas_transcurridas"]);

					$("#horas_disponibles").html(respuesta[0]["horas_disponibles"]);
					
					$("#plan_valor").html(respuesta[0]["plan_valor"]);
					
					$("#fecha_inicio").html(fecha_inicio);
					
					$("#fecha_fin").html(fecha_fin);
					//Muestro los datos del cliente
					$("#nombre_datos_personales_registrar").val(respuesta[0]["solo_nombre"]);
					$("#apellido_p_datos_personales_registrar").val(respuesta[0]["solo_apellidos_paternos"]);
					$("#apellido_m_datos_personales_registrar").val(respuesta[0]["solo_apellidos_maternos"]);
					///---
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
/* ------------------------------------------------------------------------------- */
/*
	Funcion que agrega las lista ista a la tabla
*/
function agregarServicio(select, tabla,valor_registrar){
	/***/
	var existe = false;
	var super_value = $(select).val();
	var vector_value = super_value.split("|");
	var value = vector_value[0];
	var tipo = vector_value[1]; 
	var codigo_servicio = vector_value[2];
	var costo_servicios_calc = vector_value[3];
	var categoria_servicio = vector_value[4];
	var valor_servicio = $(valor_registrar).val(); 
	var total_servicio = parseInt(valor_servicio)*costo_servicios_calc;
	var costo_servicio = new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(vector_value[3]);
	var total_servicio = new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(total_servicio);
	var cargo_option = false;

	if(isNaN(valor_servicio)){
		valor_servicio = valor_servicio.toUpperCase(); 
	}
	var text = $(select + " option:selected").html();
	var validadoServicio = false;
	var html = '';
	if((tipo=="N")&&(isNaN(valor_servicio))){
		warning('¡EL valor de servicio debe ser numérico!');
		$("input[name='valor]").focus();
	}else if((tipo=="C")&&(!isNaN(valor_servicio))){
		warning('¡EL valor de servicio debe ser caracter!');
		$("input[name='valor]").focus();
	}else if((tipo=="C")&&((valor_servicio!="S") && (valor_servicio!="N"))) {
		warning('¡EL valor de servicio debe ser caracter S ó N! '+valor_servicio);
		$("input[name='valor]").focus();
	}else{

		if ((value != "")&&(valor_servicio!="")) {
			$(tabla + " tbody tr").each(function() {
			  	if (value == $(this).find(".id_servicio").val())
			  		validadoServicio = true;
			});
		
			if (!validadoServicio) {
				//----Recorro la tabla de servicios contratados a ver si esta en la misma
				$(".tr_servicios").each(function(){
					// console.log($(this).text());
					id_servicio = $(this).attr("data");
					//Si el servicio esta en la tabla de arriba
					//alert(id_servicio+"=="+value)
					if(id_servicio==value ||  categoria_servicio.toUpperCase() == $(this).find(".categoria_case").val().toUpperCase()){
						disponible = parseInt($(this).find("th").eq(5).html())
						disponible_previo = parseInt($(this).find("th").eq(5).html())
						consumido = parseInt($(this).find("th").eq(4).html())
						cantidad = parseInt($(this).find("th").eq(2).html())
						//alert("disponible:"+disponible)

						if(disponible>0){

							//valor_servicio2 = parseInt(valor_servicio)
							//este valor cosumido es el mismo de la cantidad colocada en el input al agregar el servicio, si el valor disponible es negativo este valor tendra que restarse para llenar de info a la tabla superior e inferior
							valor_consumido =  parseInt(valor_servicio)
							consumido = consumido + valor_consumido
							disponible = disponible - valor_consumido

							if(disponible<0){
								//este valor consumido es el valor de lo que se condiera consumido en este recargo, si disponible es negativo quiere decir que el valor consumido para la tabla superior va a ser lo que se tenia disponible antes de agregar este servicio 
								valor_consumido = disponible_previo
								consumido = cantidad
								restarnte = (disponible)*-1
								disponible = 0
								//----
								total_servicio_restante = restarnte*vector_value[3]
								total_servicio_restante =  new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(total_servicio_restante);
								//----
								//Agrego en la tabla de abajo el restante
								html += "<tr id='r" + value + "'><td>" + codigo_servicio + "</td>";
								html += "<td>"+text + " <input type='hidden' class='id_servicio' name='id_servicio' value='" + value + "'></td>";
								html += "<td id='r_valor_servicio" + value + "' class='valor_servicio' >" + restarnte + "</td>";
								html += "<td id='r_costo_servicio" + value + "' class='costo_servicio' style='text-align:right'>" + costo_servicio + "</td>";
								html += "<td id='r_toral_servicio" + value + "' class='total_servicio' style='text-align:right'>" + total_servicio_restante + "</td>";
								html += "<td><button type='button' class='eliminar btn btn-xs btn-danger waves-effect' ata-toggle='tooltip' title='Eliminar' onclick='eliminarServicios(\"" + "#r" + value + "\",\"" +value+ "\")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button></td></tr>";
								//---	
								$(tabla + " tbody").append(html);
								//----
								if(id_servicio==value)
									data2 = $("#arreglo_servicios_opcionales").attr("data2")
								if(categoria_servicio == $(this).find(".categoria_case").val())
									data2 = id_servicio;

								if(data2!="0"){
									arreglo_servicios_opcionales = data2 +"*"+value+"|"+vector_value[3]+"|"+restarnte+"|"+categoria_servicio
								}else{
									arreglo_servicios_opcionales = value+"|"+vector_value[3]+"|"+restarnte+"|"+categoria_servicio
								}
								///alert(arreglo_servicios_contratados)
								$("#arreglo_servicios_opcionales").attr("data2",arreglo_servicios_opcionales)
								$("#arreglo_servicios_opcionales").html(arreglo_servicios_opcionales)
								
							}
							existe = true
							cargo_option = true;
							//Asigno lo consumido
							$(this).find("th").eq(4).html(consumido)
							//Asigno lo disponible
							$(this).find("th").eq(5).html(disponible)
							///
							var data = $("#arreglo_servicios_contratados").attr("data")

							if(id_servicio!=value && categoria_servicio.toUpperCase() == $(this).find(".categoria_case").val().toUpperCase()){
								data = "0";
								id_servicio = value+"*"+id_servicio
							}
				
							if(data!="0"){
								//arreglo_servicios_contratados = data +"*"+id_servicio+"|"+$(this).find("th").eq(6).html()+"|"+consumido+"|"+disponible
								arreglo_servicios_contratados = data +"*"+id_servicio+"|"+vector_value[3]+"|"+consumido+"|"+disponible+"|"+categoria_servicio
							}else{
								//arreglo_servicios_contratados = id_servicio+"|"+$(this).find("th").eq(6).html()+"|"+consumido+"|"+disponible
								arreglo_servicios_contratados = id_servicio+"|"+vector_value[3]+"|"+consumido+"|"+disponible+"|"+categoria_servicio
							}
							///alert(arreglo_servicios_contratados)
							$("#arreglo_servicios_contratados").attr("data",arreglo_servicios_contratados)
							$("#arreglo_servicios_contratados").html(arreglo_servicios_contratados)
						}
					}
					//alert(id_servicio);
					//alert($(this).find("th").eq(1).html());
				});
		
				//-----
				//Sino esta en la tabla inicial, lo agrego a la nueva tabla

				if(!existe){
					html += "<tr id='r" + value + "'><td>" + codigo_servicio + "</td>";
					html += "<td>"+text + " <input type='hidden' class='id_servicio' name='id_servicio' value='" + value + "'></td>";
					html += "<td id='r_valor_servicio" + value + "' class='valor_servicio' >" + valor_servicio + "</td>";
					html += "<td id='r_costo_servicio" + value + "' class='costo_servicio' style='text-align:right'>" + costo_servicio + "</td>";
					html += "<td id='r_toral_servicio" + value + "' class='total_servicio' style='text-align:right'>" + total_servicio + "</td>";
					html += "<td><button type='button' class='eliminar btn btn-xs btn-danger waves-effect' ata-toggle='tooltip' title='Eliminar' onclick='eliminarServicios(\"" + "#r" + value + "\",\"" +value+ "\")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button></td></tr>";
					//---	
					$(tabla + " tbody").append(html);
					//---
					data2 = $("#arreglo_servicios_opcionales").attr("data2")
					if(data2!="0"){
						arreglo_servicios_opcionales = data2+"*"+value+"|"+vector_value[3]+"|"+valor_servicio+"|"+categoria_servicio
					}else{
						arreglo_servicios_opcionales = value+"|"+vector_value[3]+"|"+valor_servicio+"|"+categoria_servicio
					}
					///alert(arreglo_servicios_contratados)
					console.log(cargo_option)
					if(cargo_option == false){
						$("#arreglo_servicios_opcionales").attr("data2",arreglo_servicios_opcionales)
						$("#arreglo_servicios_opcionales").html(arreglo_servicios_opcionales)
					}
				}
				//---
				//---Cargo los valores en los arrgelos respectivos
				cargarArreglosMontos();
				//---
			} else {
				warning('¡La opción seleccionada ya se encuentra agregada!');
			}
			$(select + " option[value='']").attr("selected","selected");
		} else {
			warning('¡Debe seleccionar una opción!');
		}
	}
}
/*
*	Eliminar Servicios
*/
function eliminarServicios(tr,id_servicio){
	//alert(id_servicio)
	var arreglo_nuevo_servicios = "";
	var servicios_opcionales = $("#arreglo_servicios_opcionales").attr("data2")
	var arreglo_servicios_opcionales = servicios_opcionales.split("*")
	var contador = 0
	var acum_serv_op = 0
	$.each(arreglo_servicios_opcionales, function( index, value ) {
		vector_interno_ini = value.split("|");
		if(id_servicio!=vector_interno_ini[0]){
			if(contador==0)
				arreglo_nuevo_servicios=value;
			else
				arreglo_nuevo_servicios+="*"+value;
			contador++;
		}else{
			acum_serv_op = (vector_interno_ini[1]*vector_interno_ini[2])
		}
	})

	//--
	if(arreglo_nuevo_servicios==""){
		arreglo_nuevo_servicios = "0";
	}

	//--
	$("#arreglo_servicios_opcionales").attr("data2",arreglo_nuevo_servicios)
	$("#arreglo_servicios_opcionales").html(arreglo_nuevo_servicios)	
	//console.log(arreglo_nuevo_servicios);
	$(tr).remove(); 
	//--Recalculo el valor del arreglo....
	var monto_pagar_oculto = parseInt($("#monto_pagar_oculto").val());
	var monto_total_recargo_oculto = parseInt($("#monto_total_recargo_oculto").val());
	//alert(acum_serv_total+"-"+monto_total_recargo_oculto)
	acum_serv_total =	monto_total_recargo_oculto-acum_serv_op
	acum_serv1 = monto_pagar_oculto-acum_serv_op
	$("#monto_total_recargo_oculto").val(acum_serv_total);
	$("#monto_pagar_oculto").val(acum_serv1);
	//alert("Monto total recargos"+acum_serv_total+"- Monto a pagar:"+acum_serv1)
	//console.log("Monto total recargos"+acum_serv_total+"- Monto a pagar:"+acum_serv1)
	
	//Cargo los montos en campos visibles
	$("#monto_total_recargo").val(new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(acum_serv_total))
	$("#monto_pagar").val(new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(acum_serv1))
	//---*/
}
/*
*	Recalcular arreglo opcional
*/
function recalcular_arreglo_opcional(){
	arreglo_servicios_opcionales = $("#arreglo_servicios_opcionales").attr("data2");
	arreglo_servicios_contratados = $("#arreglo_servicios_contratados").attr("data");
	acum_serv1 = 0;
	acum_serv2 = 0;
	
	//Recorro el vector de servicios opcionales ->abajo
	if(arreglo_servicios_opcionales!="0"){
		vector_servicios_inicial = arreglo_servicios_opcionales.split("*")
		//alert(vector_servicios_inicial.length)
		if(vector_servicios_inicial.length>0){
			$.each(vector_servicios_inicial, function( index, value ) {
				vector_interno_ini = value.split("|");
				acum_serv1 = acum_serv1+(vector_interno_ini[1]*vector_interno_ini[2])
			});
		}
	}else{
		acum_serv1 = 0;
	}

	//Recorro el vector de servicios contratados ->arriba

	if(arreglo_servicios_contratados!="0"){
		vector_servicios_segundo = arreglo_servicios_contratados.split("*")
		//alert(vector_servicios_segundo.length)
		if(vector_servicios_segundo.length>0){
			$.each(vector_servicios_segundo, function( index, value ) {
				vector_interno_seg = value.split("|");
				acum_serv2 = acum_serv2+(vector_interno_seg[1]*vector_interno_seg[2])
			});
		}
	}else{
		acum_serv2 = 0;
	}	

	acum_serv_total =	parseInt(acum_serv1) + parseInt(acum_serv2)
	$("#monto_total_recargo_oculto").val(acum_serv_total);
	$("#monto_pagar_oculto").val(acum_serv1);
	//alert("Monto total recargos"+acum_serv_total+"- Monto a pagar:"+acum_serv1)
	//console.log("Monto total recargos"+acum_serv_total+"- Monto a pagar:"+acum_serv1)
	
	//Cargo los montos en campos visibles
	$("#monto_total_recargo").val(new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(acum_serv_total))
	$("#monto_pagar").val(new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(acum_serv1))
}
/*
*	Cargo los arreglos en los input de montos
*/
function cargarArreglosMontos(){
	arreglo_servicios_opcionales = $("#arreglo_servicios_opcionales").attr("data2");
	arreglo_servicios_contratados = $("#arreglo_servicios_contratados").attr("data");
	
	acum_serv1 = 0.00;
	acum_serv2 = 0.00;

	//Recorro el vector de servicios opcionales ->abajo
	if(arreglo_servicios_opcionales!="0"){
		vector_servicios_inicial = arreglo_servicios_opcionales.split("*")
		//alert(vector_servicios_inicial.length)
		if(vector_servicios_inicial.length>0){
			$.each(vector_servicios_inicial, function( index, value ) {
				index2 = index+1
				//alert(vector_servicios_inicial.length+"-"+index2)
				vector_interno_ini = value.split("|");
				acum_serv1 = acum_serv1+(vector_interno_ini[1]*vector_interno_ini[2])

				//---
				//Si es actualizar el monto total sera solo en base al ultimo valor del vector, esto para evitar tomar todos los valores cuando se actualiza se suma uno a uno
				/*if(($("#tipo_registro").val()=="actualizar")&&(vector_servicios_inicial.length==index2)){
					acum_serv1 = (vector_interno_ini[1]*vector_interno_ini[2])
				}*/
				//---
			});
		}
	}else{
		acum_serv1 = ($('#monto_pagar').val() == '')?0:$('#monto_pagar').val();
	}

	//Recorro el vector de servicios contratados ->arriba

	if(arreglo_servicios_contratados!="0"){
		vector_servicios_segundo = arreglo_servicios_contratados.split("*")
		//alert(vector_servicios_segundo.length)
		if(vector_servicios_segundo.length>0){
			$.each(vector_servicios_segundo, function( index, value ) {
				index3 = index+1
				vector_interno_seg = value.split("|");
				if(!isNaN(acum_serv2+(vector_interno_seg[1]*vector_interno_seg[2])))
					acum_serv2 = acum_serv2+(vector_interno_seg[1]*vector_interno_seg[2])
				/*if(($("#tipo_registro").val()=="actualizar")&&(vector_servicios_segundo.length==index3)){
					acum_serv2 = (vector_interno_seg[1]*vector_interno_seg[2])
				}*/
			});
		}
	}else{
		acum_serv2 = ($('#monto_total_recargo').val() == '')?0:$('#monto_total_recargo').val();
	}	

	acum_serv_total =	parseInt(acum_serv1) + parseInt(acum_serv2)

	//alert("total:"+acum_serv_total)
	//alert(acum_serv1+"-"+acum_serv2)
	//Cargo los montos en campos  ocultos
	
	
	if($("#tipo_registro").val()=="actualizar"){
		var monto_pagar_oculto = parseInt($("#ultimo_monto_pagar_guardado").val());
		var monto_total_oculto = parseInt($("#ultimo_monto_total_guardado").val());
		//alert(acum_serv_total+"-"+monto_total_recargo_oculto)
		acum_serv_total =	acum_serv_total + monto_total_oculto
		acum_serv1 = acum_serv1 + monto_pagar_oculto

	}
	console.log(acum_serv1)
	$("#servicios_contratados_oculto").val(acum_serv2);

	$("#monto_total_recargo_oculto").val(acum_serv_total);
	$("#monto_pagar_oculto").val(acum_serv1);
	//alert("Monto total recargos"+acum_serv_total+"- Monto a pagar:"+acum_serv1)
	//console.log("Monto total recargos"+acum_serv_total+"- Monto a pagar:"+acum_serv1)
	
	//Cargo los montos en campos visibles
	$("#monto_total_recargo").val(new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(acum_serv_total))
	$("#monto_pagar").val(new Intl.NumberFormat('en-IN', {  minimumFractionDigits: 2 }).format(acum_serv1))
	//
}
/*------------------------------------------------------------------------------- */
/*------------------------------------------------------------------------------------------------------------------------------*/
