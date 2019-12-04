$(document).ready(function(){
	listar();
	registrar_servicio();
	actualizar_servicio();
	decimalesInput('.precio');

	orderSelectAlpha("#tipo_serv_registrar");
	orderSelectAlpha("#categorias_registrar");
	orderSelectAlpha("#tipo_registrar");
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
				"url": url + "Servicios/listado_servicios",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_servicios",
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
				{"data":"tipo"},
				{"data":"titulo_servicio"},
				{"data":"cod_servicios"},
				{"data":"descripcion",
					render : function(data, type, row) {
						var text = data;
						if (data != null)
							if (data.length > 20)
								text = data.substr(0,19) + "..."
						return text;
					}
				},
				/*{"data":"horas"},*/
				/*{"data":"servicio_consumible"},*/
				{"data":"monto",
					render: function(data, type, row){
						if(data){
							var precio2 = parseFloat(Math.round(data * 100) / 100).toFixed(2);
							/*var opciones = {
						        maximumFractionDigits: 2, 
						        useGrouping: false
						    };
							var valor = new Intl.NumberFormat("en").format(data);
							return valor;*/
							/*precio2 = new Intl.NumberFormat("en").format(precio2);
							var precio = String(precio2);
							var decimales = precio.indexOf(".");
							if(decimales==-1){
								precio = precio+".00";
							}*/
							var precio = '<div style="text-align:right">'+data+'</div>'  						
							return precio;
						}else{
							var precio = '<div style="text-align:right">0.00</div>' 
							return precio
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
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro2 para mostrar el formulario de registrar.
	*/
	function nuevoEsquema(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		$("#alertas").css("display", "none");
		$("#form_servicios_registrar")[0].reset();
		$("#tipo_registrar").focus();
		$("#indicador_servicio_consumible_registrar").val("N");
		$("#membresia").val("S");
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_servicio(){
		enviarFormulario("#form_servicios_registrar", 'Servicios/registrar_servicio', '#cuadro2');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta del banco.
	*/
	function ver(tbody, table){
		$(tbody).on("click", "span.consultar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			$("#alertas").css("display", "none");
			$("#tipo_consultar option[value='" + data.tipo + "']").attr("selected","selected");
			$("#categorias_view option[value='" + data.categoria + "']").attr("selected","selected");
			document.getElementById('cod_servicio_consultar').value = data.cod_servicios;
			document.getElementById('descripcion_consultar').value = data.descripcion;
			$("#tipo_consultar option[value='" + data.tipo + "']").prop("selected",true);
			document.getElementById('monto_consultar').value = data.monto;
			$("#horas_consultar").val(data.horas)
			//--------------------------------------------------------------------------------
			if (data.servicio_consumible == "S") {
				//$("#indicador_jornadas_consultar").attr("checked","checked");
				$("#indicador_servicios_consultar").prop("checked", true);
			}else{
				//$("#indicador_jornadas_consultar").removeAttr("checked");
				$("#indicador_servicios_consultar").prop("checked", false);
			}



			if (data.membresia == "S") {
				$("#indicador_membresia_view").prop("checked", true);
			}else{
				$("#indicador_membresia_view").prop("checked", false);
			}




			$("#tipo_serv_consultar option[value='" + data.tipo_servicio + "']").prop("selected",true);
			//--------------------------------------------------------------------------------
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$("#form_servicios_editar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('id_servicio_editar').value = data.id_servicios;
			$("#tipo_editar option[value='" + data.tipo + "']").attr("selected","selected");
			
			$("#categorias_edit option[value='" + data.categoria + "']").attr("selected","selected");
			document.getElementById('cod_servicio_editar').value = data.cod_servicios;
			document.getElementById('descripcion_editar').value = data.descripcion;
			document.getElementById('monto_editar').value = data.monto;
			$("#tipo_editar option[value='" + data.tipo + "']").prop("selected",true);
			cuadros('#cuadro1', '#cuadro4');


			if (data.tipo_servicio == "5d8ce5022221b4b0006ed7b3") {
				$("#monto_editar").val(0);
				$("#monto_editar").attr("disabled", "disabled");
			}else{
				$("#monto_editar").removeAttr("disabled");
			}



			if (data.membresia == "S") {
				$("#indicador_membresia_edit").prop("checked", true);
			}else{
				$("#indicador_membresia_edit").prop("checked", false);
			}


			$("#horas_modificar").val(data.horas)
			//--------------------------------------------------------------------------------
			if (data.servicio_consumible == "S") {
				//$("#indicador_jornadas_consultar").attr("checked","checked");
				$("#indicador_servicios_modificar").prop("checked", true);
			}else{
				//$("#indicador_jornadas_consultar").removeAttr("checked");
				$("#indicador_servicios_modificar").prop("checked", false);
			}
			$("#indicador_servicio_consumible_modificar").val(data.servicio_consumible);

			$("#tipo_serv_editar option[value='" + data.tipo_servicio + "']").prop("selected",true);

			//--------------------------------------------------------------------------------
			$("#tipo_editar").focus();
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_servicio(){
		enviarFormulario("#form_servicios_editar", 'Servicios/actualizar_servicio', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('Servicios/eliminar_servicio', data.id_servicios, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('Servicios/status_servicio', data.id_servicios, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Servicios/status_servicio', data.id_servicios, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */
$("#indicador_servicios_registrar").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_servicios_registrar").is(':checked')) {
		$("#indicador_servicio_consumible_registrar").val("S");
	}else{
		$("#indicador_servicio_consumible_registrar").val("N");
	}
});
//-----------------------------------------------------------------------------------
$("#indicador_servicios_modificar").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_servicios_modificar").is(':checked')) {
		$("#indicador_servicio_consumible_modificar").val("S");
	}else{
		$("#indicador_servicio_consumible_modificar").val("N");
	}
});
/* ------------------------------------------------------------------------------- */



function orderSelectAlpha(select) {
	var selectToSort = jQuery(select);
      var optionActual = selectToSort.val();
      selectToSort.html(selectToSort.children('option').sort(function (a, b) {
        return a.text === b.text ? 0 : a.text < b.text ? -1 : 1;
      })).val(optionActual);

      $(select).prepend('<option style="text-transform: capitalize;" value="" selected>Seleccione</option>');
}


$("#tipo_serv_registrar").change(function(){
	if ($(this).val() == "5d8ce5022221b4b0006ed7b3") {
		$("#monto_registrar").val(0);
		$("#monto_registrar").attr("disabled", "disabled");
	}else{
		$("#monto_registrar").removeAttr("disabled");
	}
});


$("#tipo_serv_editar").change(function(){
	if ($(this).val() == "5d8ce5022221b4b0006ed7b3") {
		$("#monto_editar").val(0);
		$("#monto_editar").attr("disabled", "disabled");
	}else{
		$("#monto_editar").removeAttr("disabled");
	}
});


$("#categorias_registrar").change(function(){

	var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
	$.ajax({
		url: url + "Servicios/ListTipos",
        type:"POST",
        dataType:"JSON",
       
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
        	$("#alertas").css("display", "none");

        	
        	$("#tipo_serv_registrar option").remove();
            $("#tipo_serv_registrar").append($('<option>',
            {
                value: "",
                text : "Seleccione"
            }));



        	$.each(respuesta, function(i, item){
        		
        		if ($("#categorias_registrar").val() == "Cafe Gourmet") {
        			if (item.titulo != "GENERAL" && item.titulo != "RECARGOS") {
        				return;
        			}
        		}


        		if ($("#categorias_registrar").val() == "Horas") {
        			if (item.titulo != "HORAS DE COWORKING") {
        				return;
        			}
        		}


        		if ($("#categorias_registrar").val() == "Sala de juntas") {
        			if (item.titulo != "GENERAL" && item.titulo != "SALAS") {
        				return;
        			}
        		}


        		if ($("#categorias_registrar").val() == "Servicios adicionales") {
        			if (item.titulo != "RECARGOS") {
        				return;
        			}
        		}



        		if ($("#categorias_registrar").val() == "Servicios generales") {
        			if (item.titulo != "GENERAL") {
        				return;
        			}
        		}




        		$("#tipo_serv_registrar").append($('<option>',
                 {
                    value: item.id_tipo_serv,
                    text : item.titulo
                }));
        	});
        	
		}	
	});	


});





$("#indicador_membresia").on("change", function(){
	if ($("#indicador_membresia").is(':checked')) {
		 $("#membresia").val("S");
	}else{
		$("#membresia").val("N");
	}
});




$("#indicador_membresia_edit").on("change", function(){
	if ($("#indicador_membresia_edit").is(':checked')) {
		 $("#membresia_edit").val("S");
	}else{
		$("#membresia_edit").val("N");
	}
});



