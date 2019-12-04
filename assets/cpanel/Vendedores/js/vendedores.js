$(document).ready(function(){
	listar();
	registrar_vendedor();
	actualizar_vendedor();
	porcentajeInput('.precio');
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
				"url": url + "vendedores/listado_vendedores",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_vendedor",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data": null,
					render : function(data, type, row) {
						var botones = "";
						if(consultar == 0)
							botones += "<span class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						if(actualizar == 0)
							botones += "<span class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						if(data.status == true && actualizar == 0)
							botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == false && actualizar == 0)
							botones += "<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						if(borrar == 0)
		              		botones += "<span class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
		          		return botones;
		          	}
				},
				{"data":"id_vendedor"},
				{"data":"nombre_user"},
				{"data":"apellido_user"},
				{"data":"apellido_m_user"},
				{"data":"tipoVendedor"},
				{"data":"rfc"},
				{"data":"fec_regins",
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						return cambiarFormatoFecha(fecha[0]);
	          		}
				},
				{"data":"user_regis"},
				
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
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro2 para mostrar el formulario de registrar.
	*/
	function nuevoVendedor(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		$("#alertas").css("display", "none");
		$("#form_vendedores_registrar").trigger("reset");
		$("#tableInmobiliariaRegistrar tbody tr").remove();
		$("#tableClasificacionRegistrar tbody tr").remove(); 
		$("#tableClienteRegistrar tbody tr").remove(); 

		//$('#proyecto_clientes_registrar').attr("disabled", "disabled");
		//$('#cliente_registrar').attr("disabled", "disabled");
		/*$('#proyecto_clientes_registrar option').remove();
		$('#proyecto_clientes_registrar').append($('<option>', {
		    value: "",
		    text: "Seleccione",
		}));*/
	}
/* ------------------------------------------------------------------------------- */


/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para filtras inmobiliarias por proyectos
	*/
	$("#proyecto_registrar").on("change", function(){
		var proyecto = $(this).val();
		var data = new FormData();
		data.append('proyecto', proyecto);
		$.ajax({
            url: document.getElementById('ruta').value + 'proyectos/buscarInmobiliariasVendedor/',
            type: 'POST',
            dataType:'JSON',
            data: data,
            cache: false,
			processData: false,
			contentType: false,
            beforeSend: function(){
            	$('#inmobiliaria_registrar option').remove();
                $('#inmobiliaria_registrar').append($('<option>',
			    {
			        value: "",
			        text : "Espere por favor..."
			    }));
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
                 var clasificaciones = respuesta;
                 $('#inmobiliaria_registrar').removeAttr('disabled');
				$('#inmobiliaria_registrar option').remove();
                if (clasificaciones.length == 0) {
                	$('#inmobiliaria_registrar').append($('<option>',
				    {
				        value: "",
				        text : "No Aplica..."
				    }));
				    $('#inmobiliaria_registrar').attr('disabled', 'disabled');
				    $('#inmobiliaria_registrar').removeAttr('required');
                }else{
                	//$('#inmobiliaria_registrar').attr('required', 'required');
                	$('#inmobiliaria_registrar').append($('<option>',
				    {
				        value: "",
				        text : "Seleccione..."
				    }));
				    $.each(clasificaciones, function(i, item){
		           		$('#inmobiliaria_registrar').append($('<option>',
					     {
					        value: item.id_inmobiliaria,
					        text : item.codigo+" --- "+item.nombre
					    }));
		           	});
                }
	           
            }
        });
	});
/* ------------------------------------------------------------------------------- */




/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para filtras inmobiliarias por proyectos
	*/
	$("#proyecto_editar").on("change", function(){
		var proyecto = $(this).val();
		var data = new FormData();
		data.append('proyecto', proyecto);
		$.ajax({
            url: document.getElementById('ruta').value + 'proyectos/buscarInmobiliarias/',
            type: 'POST',
            dataType:'JSON',
            data: data,
            cache: false,
			processData: false,
			contentType: false,
            beforeSend: function(){
            	$('#inmobiliaria_editar option').remove();
                $('#inmobiliaria_editar').append($('<option>',
			    {
			        value: "",
			        text : "Espere por favor..."
			    }));
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
                 var clasificaciones = respuesta;
                $('#inmobiliaria_editar').removeAttr('disabled');
				$('#inmobiliaria_editar option').remove();
                if (clasificaciones.length == 0) {
                	$('#inmobiliaria_editar').append($('<option>',
				    {
				        value: "",
				        text : "No Aplica..."
				    }));
				    $('#inmobiliaria_editar').attr('disabled', 'disabled');
				    $('#inmobiliaria_editar').removeAttr('required');
                }else{
                	//$('#inmobiliaria_editar').attr('required', 'required');
                	$('#inmobiliaria_editar').append($('<option>',
				    {
				        value: "",
				        text : "Seleccione..."
				    }));
				    $.each(clasificaciones, function(i, item){
		           		$('#inmobiliaria_editar').append($('<option>',
					     {
					        value: item.id_inmobiliaria,
					        text : item.codigo+" --- "+item.nombre
					    }));
		           	});
                }
	           
            }
        });
	});
/* ------------------------------------------------------------------------------- */

	




 function valida_r(e){
	tecla = (document.all) ? e.keyCode : e.which;
	patron =/^[0-9a-zA-Z]+$/;
	tecla_final = String.fromCharCode(tecla);
      
	return patron.test(tecla_final);
}



/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_vendedor(){
		$("#form_vendedores_registrar").submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
           
            var id_usuario               = $("#id_usuario").val();
            var tipo_vendedor            = $("#tipo_vendedor").val();
            var id_inmobiliaria          = $("#id_inmobiliaria").val();
            var rfc                      = $("#rfc").val();
            var objetoInmobiliaria       = [];
            var objetoProyectos          = [];
            var objetoProyectosClientes  = [];
            var objetoClientes           = [];



            $("#tableInmobiliariaRegistrar tbody tr").each(function() {
            	var inmobiliaria = [];
            	var id = $(this).find(".id_inmobiliaria").val();
            	inmobiliaria.push(id);
				objetoInmobiliaria.push(inmobiliaria);
			});

			$("#tableInmobiliariaRegistrar tbody tr").each(function() {
            	var proyectos = [];
            	var id_pro = $(this).find(".id_proyecto").val();
            	proyectos.push(id_pro);
				objetoProyectos.push(proyectos);
			});

			$("#tableClienteRegistrar tbody tr").each(function() {
            	var proyectos_clientes = [];
            	var id_pro = $(this).find(".id_proyecto_cliente").val();
            	proyectos_clientes.push(id_pro);
				objetoProyectosClientes.push(proyectos_clientes);
			});


			$("#tableClienteRegistrar tbody tr").each(function() {
            	var clientes = [];
            	var id_cliente = $(this).find(".id_cliente").val();
            	clientes.push(id_cliente);
				objetoClientes.push(clientes);
			});

			var data = new FormData();
			data.append('id_usuario', id_usuario);
			data.append('tipo_vendedor', tipo_vendedor);
			data.append('rfc', rfc);
			for (var i = 0; i < objetoInmobiliaria.length; i++) {
				data.append('inmobiliarias[]', objetoInmobiliaria[i]);
			}


			for (var i = 0; i < objetoProyectos.length; i++) {
				data.append('proyectos[]', objetoProyectos[i]);
			}


			for (var i = 0; i < objetoProyectosClientes.length; i++) {
				data.append('proyectos_clientes[]', objetoProyectosClientes[i]);
			}

			for (var i = 0; i < objetoClientes.length; i++) {
				data.append('clientes[]', objetoClientes[i]);
			}




            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url: document.getElementById('ruta').value + 'vendedores/registrar_vendedor',
                type: 'POST',
                dataType:'JSON',
                data:data,
                cache: false,
				processData: false,
				contentType: false,
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
                	 mensajes('success', respuesta);
                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    listar('#cuadro2');
                }
            });
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta del banco.
	*/
	function ver(tbody, table){
		//$("#tableInmobiliariaConsultar tbody tr").remove(); 
		//$("#tableClasificacionConsultar tbody tr").remove(); 
		$(tbody).on("click", "span.consultar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			console.log(data)
			/*$.ajax({
                url: document.getElementById('ruta').value + 'vendedores/listado_vendedor/'+data.id_usuario,
                type: 'POST',
                dataType:'JSON',
                success: function(respuesta){
                    $('#id_usuario_view').append($('<option>', {
					    value: respuesta[0].id_usuario,
					    text: respuesta[0].nombre_usuario+" "+respuesta[0].apellido_user,
					    selected: true
					}));
                }
            });*/
            if ($('#tipo_vendedor_view option:selected').val() != data.tipo_vendedor) {
	            $('#tipo_vendedor_view option:selected').removeAttr('selected');
			    $("#tipo_vendedor_view option[value='" + data.tipo_vendedor + "']").prop("selected",true);
            }
            $('#codigo').val(data.id_vendedor);
            $('#nombres').val(data.nombre_user);
            $('#apellido_p').val(data.apellido_user);
            $('#apellido_m').val(data.apellido_m_user);
            $('#email').val(data.email);
            $('#rfc_view').val(data.rfc);
			buscarClientes('#tableClientesConsultar',  data.id_vendedor);
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$("#form_vendedor_editar")[0].reset();
		/*$("#tableInmobiliariaEditar tbody tr").remove(); 
		$("#tableClasificacionEditar tbody tr").remove();*/
		$(tbody).on("click", "span.editar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			if ($('#tipo_vendedor_editar option:selected').val() != data.tipo_vendedor) {
	            $('#tipo_vendedor_editar option:selected').removeAttr('selected');
			    $("#tipo_vendedor_editar option[value='" + data.tipo_vendedor + "']").prop("selected",true);
            }
            $('#rfc_editar').val(data.rfc);
            $('#codigo_edit').val(data.id_vendedor);
            $('#nombres_edit').val(data.nombre_user);
            $('#apellido_p_edit').val(data.apellido_user);
            $('#apellido_m_edit').val(data.apellido_m_user);
		    document.getElementById('id_vendedor_editar').value=data.id_vendedor;
		    //buscarInmobiliarias('#tableInmobiliariaEditar', data.id_vendedor);
		    buscarClientesEditar('#tableClientesEditar',  data.id_vendedor)
			cuadros('#cuadro1', '#cuadro4');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_vendedor(){
		$("#form_vendedor_editar").submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            var id_vendedor     = $("#id_vendedor_editar").val();
            var tipo_vendedor   = $("#tipo_vendedor_editar").val();
            var rfc             = $("#rfc_editar").val();

           	var objetoInmobiliaria = [];
           	var objetoProyectos    = [];

           	var objetoProyectosClientes  = [];
            var objetoClientes           = [];



            /*$("#tableInmobiliariaEditar tbody tr").each(function() {
            	var inmobiliaria = [];
            	var id = $(this).find(".id_inmobiliaria").val();
            	inmobiliaria.push(id);
				objetoInmobiliaria.push(inmobiliaria);
			});


			$("#tableInmobiliariaEditar tbody tr").each(function() {
            	var proyectos = [];
            	var id_pro = $(this).find(".id_proyecto").val();
            	proyectos.push(id_pro);
				objetoProyectos.push(proyectos);
			});*/


			/*$("#tableClientesEditar tbody tr").each(function() {
            	var proyectos_clientes = [];
            	var id_pro = $(this).find(".id_proyecto_cliente").val();
            	proyectos_clientes.push(id_pro);
				objetoProyectosClientes.push(proyectos_clientes);
			});*/


			$("#tableClientesEditar tbody tr").each(function() {
            	var clientes = [];
            	var id_cliente = $(this).find(".id_cliente").val();
            	clientes.push(id_cliente);
				objetoClientes.push(clientes);
			});



			var data = new FormData();
			data.append('id_vendedor', id_vendedor);
			data.append('tipo_vendedor', tipo_vendedor);
			data.append('rfc', rfc);
			/*for (var i = 0; i < objetoInmobiliaria.length; i++) {
				data.append('inmobiliarias[]', objetoInmobiliaria[i]);
			}


			for (var i = 0; i < objetoProyectos.length; i++) {
				data.append('proyectos[]', objetoProyectos[i]);
			}


			for (var i = 0; i < objetoProyectosClientes.length; i++) {
				data.append('proyectos_clientes[]', objetoProyectosClientes[i]);
			}*/

			for (var i = 0; i < objetoClientes.length; i++) {
				data.append('clientes[]', objetoClientes[i]);
			}
			
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url: document.getElementById('ruta').value + 'vendedores/actualizar_vendedor',
                type: 'POST',
                dataType:'JSON',
                data:data,
                cache: false,
				processData: false,
				contentType: false,
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
                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    mensajes('success', respuesta);
                    listar('#cuadro4');
                }
            });
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('vendedores/eliminar_vendedor', data.id_vendedor, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('vendedores/status_vendedor', data.id_vendedor, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function status_proyecto_vendedor(id, status, id_vendedor){
        if (status == 1) {
        	var mensaje   = "¿Esta seguro de activar el registro?";
        	var confirmar = "activar";
        }else if(status == 2){
        	var mensaje   = "¿Esta seguro de desactivar el registro?";
        	var confirmar = "desactivar";
        }
        statusConfirmacion_vendedor('vendedores/status_vendedor_proyecto', id, status, id_vendedor, mensaje, confirmar);

	}



/* ------------------------------------------------------------------------------- */



/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function status_cartera_cliente(id, status, id_vendedor){
        if (status == 1) {
        	var mensaje   = "¿Esta seguro de activar el registro?";
        	var confirmar = "activar";
        }else if(status == 2){
        	var mensaje   = "¿Esta seguro de desactivar el registro?";
        	var confirmar = "desactivar";
        }
        statusConfirmacionCarteraCliente('vendedores/status_cartera_cliente', id, status, id_vendedor, mensaje, confirmar);

	}



/* ------------------------------------------------------------------------------- */


    /*
        Funcion que se encarga de cambiar el status de un registro seleccionado.
        status -> valor (1, 2, n...)
        confirmButton -> activar, desactivar
    */
    function statusConfirmacion_vendedor(controlador, id, status, id_vendedor, title, confirmButton){
        swal({
            title: title,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, "+confirmButton+"!",
            cancelButtonText: "No, Cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
                $.ajax({
                    url:url+controlador,
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        id:id,
                        status:status
                    },
                    beforeSend: function(){
                        mensajes('info', '<span>Guardando cambios, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                        mensajes('danger', errores);
                    },
                    success: function(respuesta){
                        //buscarInmobiliarias('#tableInmobiliariaEditar', id_vendedor);
                        $("#checkall").prop("checked", false);
                        mensajes('success', respuesta);
                    }
                });
            } else {
                swal("Cancelado", "Proceso cancelado", "error");
            }
        });
    }
/* ------------------------------------------------------------------------------- */


/* ------------------------------------------------------------------------------- */



/* ------------------------------------------------------------------------------- */


    /*
        Funcion que se encarga de cambiar el status de un registro seleccionado.
        status -> valor (1, 2, n...)
        confirmButton -> activar, desactivar
    */
    function statusConfirmacionCarteraCliente(controlador, id, status, id_vendedor, title, confirmButton){
        swal({
            title: title,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, "+confirmButton+"!",
            cancelButtonText: "No, Cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
                $.ajax({
                    url:url+controlador,
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        id:id,
                        status:status
                    },
                    beforeSend: function(){
                        mensajes('info', '<span>Guardando cambios, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                        mensajes('danger', errores);
                    },
                    success: function(respuesta){
                        buscarClientesEditar('#tableClientesEditar', id_vendedor);
                        $("#checkall").prop("checked", false);
                        mensajes('success', respuesta);
                    }
                });
            } else {
                swal("Cancelado", "Proceso cancelado", "error");
            }
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
            statusConfirmacion('vendedores/status_vendedor', data.id_vendedor, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}



/* ------------------------------------------------------------------------------- */
	/*
		Funcion que agrega las inombiliaria a la tabla
	*/
	function agregarInmobiliaria(proyecto, select, tabla){
		var idInmobiliaria = $(select).val();
		var nombreInmobiliaria = $(select + " option:selected").html();

		var idproyecto = $(proyecto).val();
		var nombreproyecto = $(proyecto + " option:selected").html();

		var validadoInmobiliaria = false;
		var html = '';
		//if ( idInmobiliaria != "" ){
			$(tabla + " tbody tr").each(function() {
			  	if ((idproyecto == $(this).find(".id_proyecto").val()))
			  		validadoInmobiliaria = true;
			});
			if (!validadoInmobiliaria) {
				html += "<tr id='i" + idproyecto + "'><td>" + nombreproyecto + " <input type='hidden' name='id_proyecto'  class='id_proyecto' value='" + idproyecto + "'></td><td>" + nombreInmobiliaria + " <input type='hidden' class='id_inmobiliaria' name='id_inmobiliaria' value='" + idInmobiliaria + "'></td>";
				html += "<td><button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar' onclick='eliminarTr(\"" + "#i" + idproyecto + "\", "+idproyecto+")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button></td></tr>";
				$(".dataTables_empty").remove();
				$(tabla + " tbody").append(html);


				if (tabla == "#tableInmobiliariaEditar") {
					$("#proyecto_clientes_editar").removeAttr("disabled");
					$("#cliente_editar").removeAttr("disabled");
					
					$('#proyecto_clientes_editar').append($('<option>', {
						    value: idproyecto,
						    text: nombreproyecto,
					}));
				}else{
					$("#proyecto_clientes_registrar").removeAttr("disabled");
					$("#cliente_registrar").removeAttr("disabled");
					
					$('#proyecto_clientes_registrar').append($('<option>', {
						    value: idproyecto,
						    text: nombreproyecto,
					}));
				}
				//$('#inmobiliaria_registrar').attr('disabled', 'disabled');
				//$('#inmobiliaria_editar').attr('disabled', 'disabled');
			} else {
				warning('¡Un vendedor solo puede estar asignado a una inmobiliaria por proyecto!');
			}
			$(select + " option[value='']").attr("selected","selected");
		/*} else {
			warning('¡Debe seleccionar una opción!');
		}*/
		
	}


	/* ------------------------------------------------------------------------------- */


/* ------------------------------------------------------------------------------- */
	/*
		Funcion que agrega las inombiliaria a la tabla
	*/
	var conta = 1;
	function agregarCliente(proyecto, select, tabla){
		var idCliente = $(select).val();
		var nombreCliente = $(select + " option:selected").html();

		var array = nombreCliente.split(' - ');

		

		var idproyecto = $(proyecto).val();
		var nombreproyecto = $(proyecto + " option:selected").html();

		var validadoCliente = false;
		var html = '';
		if ( idCliente != "" ){
			$(tabla + " tbody tr").each(function() {
			  	if ((idCliente == $(this).find(".id_cliente").val()) && (idproyecto == $(this).find(".id_proyecto_cliente").val()))
			  		validadoCliente = true;
			});

			
			if (!validadoCliente) {
				html += "<tr id='ic" + conta + "'><td>" + array[0] + " <input type='hidden' class='id_cliente' name='id_cliente' value='" + idCliente + "'></td><td>" + array[1] + "</td><td>" + array[2] + "</td>";
				html += "<td><button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar' onclick='eliminarTrCliente(\"" + "#ic" + conta + "\", "+idproyecto+")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button></td></tr>";
				$(tabla + " tbody").append(html);
				conta = conta + 1;
			} else {
				warning('¡Un Cliente puede tener un solo vendedor!');
			}
			$(select + " option[value='']").attr("selected","selected");
			$(".dataTables_empty").remove();

		} else {
			warning('¡Debe seleccionar una opción!');
		}
		
	}


	/* ------------------------------------------------------------------------------- */

	/*
		Funcion que busca las inmobiliarias asociadas al proyecto.
	*/
	function buscarInmobiliarias(tabla, vendedor){
		$(tabla + " tbody").html("");
		var url = document.getElementById('ruta').value;
		///alert("Este es el vendedor:"+vendedor);
		if ( tabla == "#tableInmobiliariaConsultar"){
			var table=$(tabla).DataTable({
				"destroy":true,
				"stateSave": true,
				"serverSide":false,
				"ajax":{
					"method":"POST",
					"url": url + "vendedores/buscarInmobiliarias",
					"data": {'vendedor' : vendedor},
					"dataSrc":""
				},
				"columns":[
					
					{"data":"nombre_proyecto",
						render : function(data, type, row) {
							return row.codigo_proyecto + " - " + row.nombre_proyecto;
						}
				    },
					{"data":"nombre",
	                   render : function(data, type, row) {
							return row.nombre + " - Coordinador " + row.nombres + ' ' + row.paterno +  ' ' + row.materno;
						}
				    }
				],
				"language": idioma_espanol,
				"dom": 'Bfrtip',
				"responsive": true,
				"buttons":[
					
				]
			});
		}else if ( tabla == "#tableInmobiliariaEditar"){

			//$('#proyecto_clientes_editar option').remove();
			/*$('#proyecto_clientes_editar').append($('<option>', {
			    value: "",
			    text: "Seleccione",
			}));*/
			var table=$(tabla).DataTable({
				"destroy":true,
				"stateSave": true,
				"serverSide":false,
				"ajax":{
					"method":"POST",
					"url": url + "vendedores/buscarInmobiliarias",
					"data": {'vendedor' : vendedor},
					"dataSrc":""
				},
				"columns":[
					
					{"data":"nombre_proyecto",
						render : function(data, type, row) {
							return row.codigo_proyecto + " - " + row.nombre_proyecto;
						}
				    },
					{"data":"nombre",
	                   render : function(data, type, row) {
							return row.nombre + " - Coordinador " + row.nombres + ' ' + row.paterno +  ' ' + row.materno;
						}
				    },
				    {"data":"nombre",
	                   render : function(data, type, row) {
							var table = "";
							if (row.status == 1) {
		            			var title = "Desactivar";
		            			var icon  = "<i class='fa fa-unlock' style='margin-bottom:5px'></i>";
		            			var status = 2;
		            		}else if (row.status == 2) {
		            			var title = "Activar";
		            			var icon  = "<i class='fa fa-lock' style='margin-bottom:5px'></i>";
		            			var status = 1;
		            		}

		            		

							table = "<input type='hidden' class='id_proyecto' name='id_proyecto' value='" + row.idproyecto + "'><input type='hidden' class='id_inmobiliaria' name='id_inmobiliaria' value='" + row.id_inmobiliaria + "'><button type='button' class='btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='"+title+"' onclick='status_proyecto_vendedor(" + row.id + ", "+status+", "+row.id_vendedor+")'>"+icon+"</button>&nbsp;<button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar'  onclick='eliminarConfirmarInmobiliaria(" + row.id + ", " + row.id_vendedor + ")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button>";

							return table;
						}
				    }
				],
				"language": idioma_espanol,
				"dom": 'Bfrtip',
				"responsive": true,
				"buttons":[
					
				]
			});

			$.ajax({
		        url:document.getElementById('ruta').value + 'vendedores/buscarInmobiliarias',
		        type:'POST',
		        dataType:'JSON',
		        data: {'vendedor' : vendedor},
		        error: function() {
	                buscarInmobiliarias(tabla, vendedor);
		        },
		        success: function(respuesta){
		        	console.log(respuesta);
		            respuesta.forEach(function(inmobiliaria, index){

	            	   $('#proyecto_clientes_editar').append($('<option>', {
						    value: inmobiliaria.id_proyecto,
						    text: inmobiliaria.codigo_proyecto+ " - " +inmobiliaria.nombre_proyecto,
						}));
						
					
	            	});
	            }
		     
		    });



		}
		


		// $.ajax({
	 //        url:document.getElementById('ruta').value + 'vendedores/buscarInmobiliarias',
	 //        type:'POST',
	 //        dataType:'JSON',
	 //        data: {'vendedor' : vendedor},
	 //        error: function() {
  //               buscarInmobiliarias(tabla, vendedor);
	 //        },
	 //        success: function(respuesta){
	 //        	console.log(respuesta);
	 //            respuesta.forEach(function(inmobiliaria, index){
	 //            	if ( tabla == "#tableInmobiliariaConsultar") {
		// 				table = '<tr><td>' + inmobiliaria.codigo_proyecto + ' - ' + inmobiliaria.nombre_proyecto + '</td><td>' + inmobiliaria.codigo + ' - ' + inmobiliaria.nombre + ' - Coordinador: ' + inmobiliaria.nombres + ' ' + inmobiliaria.paterno + ' ' + inmobiliaria.materno + '</td><tr>';
	 //            	} else if( tabla == "#tableInmobiliariaEditar") {

	 //            		if (inmobiliaria.status == 1) {
	 //            			var title = "Desactivar";
	 //            			var icon  = "<i class='fa fa-unlock' style='margin-bottom:5px'></i>";
	 //            			var status = 2;
	 //            		}else if (inmobiliaria.status == 2) {
	 //            			var title = "Activar";
	 //            			var icon  = "<i class='fa fa-lock' style='margin-bottom:5px'></i>";
	 //            			var status = 1;
	 //            		}
		// 				table = "<tr id='i" + inmobiliaria.id_proyecto + "'><td>" + inmobiliaria.codigo_proyecto + " - " + inmobiliaria.nombre_proyecto + "<input type='hidden' class='id_proyecto' name='id_proyecto' value='" + inmobiliaria.idproyecto + "'></td><td>" + inmobiliaria.codigo + " - " + inmobiliaria.nombre + "<input type='hidden' class='id_inmobiliaria' name='id_inmobiliaria' value='" + inmobiliaria.id_inmobiliaria + "'></td><td><button type='button' class='btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='"+title+"' onclick='status_proyecto_vendedor(" + inmobiliaria.id + ", "+status+", "+inmobiliaria.id_vendedor+")'>"+icon+"</button>&nbsp;<button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar'  onclick='eliminarConfirmarInmobiliaria(" + inmobiliaria.id + ", " + inmobiliaria.id_proyecto + ")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button></td></tr>";
	 //            	}
		// 			$(tabla + " tbody").append(table);
	 //            });
	 //        }
	 //    });
	}
/* ------------------------------------------------------------------------------- */



function buscarClientes(tabla, vendedor){
	$(tabla + " tbody").html("");
		var url = document.getElementById('ruta').value;
		var table=$(tabla).DataTable({
		"destroy":true,
		"stateSave": true,
		"serverSide":false,
		"ajax":{
			"method":"POST",
			"url": url + "vendedores/buscarClientes",
			"data": {'vendedor' : vendedor},
			"dataSrc":""
		},
		"columns":[
			{"data":"name_cliente",
		    },

		    {"data":"apellido_p_clinte",
		    },

		    {"data":"apellido_m_clinte",
		    }
		],
		"language": idioma_espanol,
		"dom": 'Bfrtip',
		"responsive": true,
		"buttons":[
			
		]
	});
}




function buscarClientesEditar(tabla, vendedor){
	$(tabla + " tbody").html("");
		var url = document.getElementById('ruta').value;
		var table=$(tabla).DataTable({
		"destroy":true,
		"stateSave": true,
		"serverSide":false,
		"ajax":{
			"method":"POST",
			"url": url + "vendedores/buscarClientes",
			"data": {'vendedor' : vendedor},
			"dataSrc":""
		},
		"columns":[
			
			/*{"data":"codigo_proyecto_cliente",
				render : function(data, type, row) {
					return row.codigo_proyecto_cliente + " - " + row.name_proyecto_cliente;
				}
		    },*/
			{"data":"name_cliente",
		    },

		    {"data":"apellido_p_clinte",
		    },

		    {"data":"apellido_m_clinte",
		    },
		    {"data":"status",
		    	render : function(data, type, row) {
					var table = "";
					if (row.status == 1) {
            			var title = "Desactivar";
            			var icon  = "<i class='fa fa-unlock' style='margin-bottom:5px'></i>";
            			var status = 2;
            		}else if (row.status == 2) {
            			var title = "Activar";
            			var icon  = "<i class='fa fa-lock' style='margin-bottom:5px'></i>";
            			var status = 1;
            		}
            		//var parameters=String("'" + item.id + "','"+item.projectID+"'");
            		var parameters = String("status_cartera_cliente('"+row.id +"','"+status+"','"+row.id_vendedor+"')")
            		var parameters2 = String("eliminarConfirmarCliente('"+row.id +"','"+row.id_vendedor+"')")
					//table = "<input type='hidden' class='id_proyecto_cliente' name='id_proyecto_cliente' value='" + row.id_proyecto + "'><input type='hidden' class='id_cliente' name='id_cliente' value='" + row.id_cliente + "'><button type='button' class='btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='"+title+"' onclick='status_cartera_cliente(\'+ row.id +\', "+status+", "+row.id_vendedor+")'>"+icon+"</button>&nbsp;<button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar'  onclick='eliminarConfirmarCliente(" + row.id + ", " + row.id_vendedor + ")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button>";
					table = "<input type='hidden' class='id_proyecto_cliente' name='id_proyecto_cliente' value='" + row.id_proyecto + "'><input type='hidden' class='id_cliente' name='id_cliente' value='" + row.id_cliente + "'><button type='button' class='btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='"+title+"' onclick="+parameters+">"+icon+"</button>&nbsp;<button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar'  onclick="+parameters2+"><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button>";
					return table;
				}
		  
		    }
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
		Funcion que elimina la inmobiliaria de la tabla
	*/
	function eliminarTr(tr, id){
		alert(tr);
		//$(tr).remove(); 
		//$("#proyecto_clientes_registrar option[value='"+id+"']").remove();
	}


	function eliminarTrCliente(tr, id){
		$(tr).remove(); 
	}


	/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que hace una busqueda de las operaciones que tiene el rol por cada
		lista vista y mostrar los resultados para su edicion
	*/
	function eliminarConfirmarInmobiliaria(id, id_vendedor){
		swal({
            title: '¿Esta seguro de eliminar este registro?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Eliminar!",
            cancelButtonText: "No, Cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                swal.close();
                $.ajax({
                    url: document.getElementById('ruta').value + "vendedores/eliminar_inmobiliaria_vendedor",
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        'id' : id,
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                        mensajes('danger', errores);
                    },
                    success: function(respuesta){
                        mensajes('success', respuesta);
                       // $("#tableInmobiliariaEditar").find("tbody tr#i" + proyecto).remove();
                       //buscarInmobiliarias('#tableInmobiliariaEditar', id_vendedor);
                    }
                });
            } else {
                swal("Cancelado", "No se ha eliminado el registro", "error");
            }
        });
	}







/* ------------------------------------------------------------------------------- */
	/*
		Funcion que hace una busqueda de las operaciones que tiene el rol por cada
		lista vista y mostrar los resultados para su edicion
	*/
	function eliminarConfirmarCliente(id, id_vendedor){
		swal({
            title: '¿Esta seguro de eliminar este registro?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Eliminar!",
            cancelButtonText: "No, Cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                swal.close();
                $.ajax({
                    url: document.getElementById('ruta').value + "vendedores/eliminar_cartera_cliente",
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        'id' : id,
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                        mensajes('danger', errores);
                    },
                    success: function(respuesta){
                        mensajes('success', respuesta);
                       // $("#tableInmobiliariaEditar").find("tbody tr#i" + proyecto).remove();
                       buscarClientesEditar('#tableClientesEditar', id_vendedor);
                    }
                });
            } else {
                swal("Cancelado", "No se ha eliminado el registro", "error");
            }
        });
	}


// $('#tableInmobiliariaConsultar').DataTable({
//         "lengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
//         "language": {
//             "lengthMenu": "Mostrar _MENU_ registros por pagina",
//             "zeroRecords": "No se encontraron resultados en su busqueda",
//             "searchPlaceholder": "Buscar registros",
//             "info": "Mostrando registros de _START_ al _END_ de un total de  _TOTAL_ registros",
//             "infoEmpty": "No existen registros",
//             "infoFiltered": "(filtrado de un total de _MAX_ registros)",
//             "search": "Buscar:",
//             "paginate": {
//                 "first": "Primero",
//                 "last": "Último",
//                 "next": "Siguiente",
//                 "previous": "Anterior"
//             },
//         }
//     });


// $('#tableInmobiliariaEditar').DataTable({
//         "lengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
//         "language": {
//             "lengthMenu": "Mostrar _MENU_ registros por pagina",
//             "zeroRecords": "No se encontraron resultados en su busqueda",
//             "searchPlaceholder": "Buscar registros",
//             "info": "Mostrando registros de _START_ al _END_ de un total de  _TOTAL_ registros",
//             "infoEmpty": "No existen registros",
//             "infoFiltered": "(filtrado de un total de _MAX_ registros)",
//             "search": "Buscar:",
//             "paginate": {
//                 "first": "Primero",
//                 "last": "Último",
//                 "next": "Siguiente",
//                 "previous": "Anterior"///
//             },
//         }
//     });
