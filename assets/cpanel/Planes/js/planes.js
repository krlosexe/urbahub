$(document).ready(function(){
	listar();
	registrar_planes();
	actualizar_planes();
	//decimalesInput('.precio');
});

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para cargar los datos de la base de datos en la tabla.
	*/
	function listar(cuadro){
		contarModulosPlanes()
		$('#tabla tbody').off('click');
		cuadros(cuadro, "#cuadro1");
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		var table=$("#tabla").DataTable({
			"destroy":true,
			"stateSave": true,
			"serverSide":false,
			"ajax":{
				"method":"POST",
				"url": url + "Planes/listado_planes",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_planes",
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
				{"data":"cod_planes"},
				{"data":"titulo"},
				{"data":"descripcion",
					render : function(data, type, row) {
						var text = data;
						if (data != null)
							if (data.length > 20)
								text = data.substr(0,19) + "..."
						return text;
					}
				},
				{"data":"posicion_planes"},
				{"data":"vigencia"},
				{"data":"ind_plan_empresarial"},
				//{"data":"horas_jornadas"},
				{"data":"tiempo_contrato"},
				/*{"data":"precio",
					render: function(data, type, row){
						
						var precio = '<div style="text-align:right">'+data+'</div>'  						
						return precio;
					}
				},*/
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
	function nuevoPlanes(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		$("#alertas").css("display", "none");
		$("#form_planes_registrar")[0].reset();
		$("#tipo_registrar").focus();
		$("#indicador_jornadas_valor_registrar").val("N");
		$("#jornadas_registrar").css({"display":"block"});


		$("#div_hiddens").css("display", "block")


		//$("#horas_jornadas_registrar").attr("required");
	}
/* ------------------------------------------------------------------------------- */
function contarModulosPlanes(){
	$('#posicion_planes_registrar').find('option').remove().end().append('<option value="">Seleccione</option>');
	$('#posicion_planes_editar').find('option').remove().end().append('<option value="">Seleccione</option>');
	$.ajax({
        url:document.getElementById('ruta').value + 'Planes/contar_modulos',
        type:'POST',
        dataType:'JSON',
        error: function() {
			contarModulos();
        },
        success: function(respuesta){
            var selectRegistrar = Object.keys(respuesta).length +1;
            var selectActualizar = Object.keys(respuesta).length;
            for(var i = 1; i <= selectRegistrar; i++)
            	agregarOptions("#posicion_planes_registrar", i, i);
            for(var i = 1; i <= selectActualizar; i++)
            	agregarOptions("#posicion_planes_editar", i, i);
        }
    });
}
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_planes(){
		enviarFormulario("#form_planes_registrar", 'Planes/registrar_planes', '#cuadro2');
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
			
			document.getElementById('cod_planes_consultar').value = data.cod_planes;

			document.getElementById('titulo_consultar').value = data.titulo;
			
			document.getElementById('descripcion_consultar').value = data.descripcion;
			
			document.getElementById('tiempo_contrato_consultar').value = data.tiempo_contrato;

			//document.getElementById('precio_consultar').value = data.precio;
			
			$("#vigencia_consultar option").removeAttr("selected");
			
			$("#vigencia_consultar option[value='" + data.id_vigencia + "']").prop("selected",true);

			cuadros('#cuadro1', '#cuadro3');

			//--Indicador jornadas limitadas
			$("#indicador_venta_consultar").val(data.ind_jornada);

			if (data.ind_jornada == "S") {
				//$("#indicador_jornadas_consultar").attr("checked","checked");
				$("#indicador_jornadas_consultar").prop("checked", true);
				$("#jornadas_consultar").css({"display":"none"});
			}else{
				//$("#indicador_jornadas_consultar").removeAttr("checked");
				$("#indicador_jornadas_consultar").prop("checked", false);
				$("#jornadas_consultar").css({"display":"block"});
			}
			$("#indicador_jornadas_valor_consultar").val(data.ind_ventas_mes);
			
			//--Indicador plan empresarial

			if (data.ind_plan_empresarial == "S") {
				//$("#indicador_plan_empresarial_consultar").attr("checked","checked");
				$("#indicador_plan_empresarial_consultar").prop("checked", true);
				//$("#jornadas_consultar").addClass("hidden");
			}else{
				///$("#indicador_plan_empresarial_consultar").removeAttr("checked");
				$("#indicador_plan_empresarial_consultar").prop("checked", false);
				//$("#jornadas_consultar").removeClass("hidden");
			}

			$("#posicion_planes_consultar").val(data.posicion_planes);

			//document.getElementById('horas_jornadas_consultar').value = data.horas_jornadas;
			//---------------------------------------------------------
			if (data.muestra_en_web == true) {
				$("#muestra_web_consultar").prop("checked", true);
				$("#indicador_muestra_web_consultar").val("S");
			}else{
				$("#muestra_web_consultar").prop("checked", false);
				$("#indicador_muestra_web_consultar").val("N");
			}


			if (data.membresia == true) {
				$("#indicador_membresia_view").prop("checked", true);
				$("#div_hiddens_view").css("display", "block")
			}else{
				$("#indicador_membresia_view").prop("checked", false);
				$("#div_hiddens_view").css("display", "none")
			}



			//---------------------------------------------------------
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$("#form_planes_editar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			
			$("#alertas").css("display", "none");
			
			var data = table.row( $(this).parents("tr") ).data();

			console.log(data);

			document.getElementById('id_planes_editar').value = data.id_planes;
			
			document.getElementById('cod_planes_editar').value = data.cod_planes;

			document.getElementById('titulo_editar').value = data.titulo;

			document.getElementById('descripcion_editar').value = data.descripcion;

			document.getElementById('tiempo_contrato_editar').value = data.tiempo_contrato;

			//document.getElementById('precio_editar').value = data.precio;
			
			$("#vigencia_editar option[value='" + data.id_vigencia + "']").prop("selected",true);
						
			cuadros('#cuadro1', '#cuadro4');
			
			$("#cod_planes_editar").focus();

			$("#posicion_planes_editar option[value='"+data.posicion_planes+"']").attr("selected","selected");
			document.getElementById('inicial').value=data.posicion_planes;
			//decimalesInput('.precio');
			//--Indicador jornadas limitadas
			$("#indicador_venta_actualizar").val(data.ind_ventas_mes);

			if (data.ind_jornada == "S") {
				//$("#indicador_jornadas_actualizar").attr("checked","checked");
				//$("#jornadas_actualizar").addClass("hidden");
				$("#indicador_jornadas_actualizar").prop("checked", true);

				$("#jornadas_actualizar").css({"display":"none"});
			}else{
				//$("#indicador_jornadas_actualizar").removeAttr("checked");
				$("#indicador_jornadas_actualizar").prop("checked", false);
				//$("#horas_jornadas_actualizar").attr("required");
				$("#jornadas_actualizar").css({"display":"block"});
				//$("#jornadas_actualizar").removeClass("hidden");
			}
			$("#indicador_jornadas_valor_actualizar").val(data.ind_jornada);
			
			//--Indicador plan empresarial

			if (data.ind_plan_empresarial == "S") {
				//$("#indicador_plan_empresarial_actualizar").attr("checked","checked");
				$("#indicador_plan_empresarial_actualizar").prop("checked", true);
			}else{
				//$("#indicador_plan_empresarial_actualizar").removeAttr("checked");
				$("#indicador_plan_empresarial_actualizar").prop("checked", false);
			}

			//document.getElementById('horas_jornadas_actualizar').value = data.horas_jornadas;
			//------------------------------
			if (data.muestra_en_web == true) {
				$("#muestra_web_modificar").prop("checked", true);
				$("#indicador_muestra_web_modificar").val("S");
			}else{
				$("#muestra_web_modificar").prop("checked", false);
				$("#indicador_muestra_web_modificar").val("N");
			}




			if (data.membresia == true) {
				$("#indicador_membresia_edit").prop("checked", true);
				$("#div_hiddens_edit").css("display", "block")
			}else{
				$("#indicador_membresia_edit").prop("checked", false);
				$("#div_hiddens_edit").css("display", "none")
			}

			
			//------------------------------
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_planes(){
		enviarFormulario("#form_planes_editar", 'Planes/actualizar_planes', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('Planes/eliminar_planes', data.id_planes, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('Planes/status_planes', data.id_planes, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Planes/status_planes', data.id_planes, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */
$("#indicador_jornadas_registrar").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_jornadas_registrar").is(':checked')) {
		//console.log("si");
		$("#indicador_jornadas_valor_registrar").val("S");
		//$("#jornadas_registrar").addClass("hidden");
		$("#jornadas_registrar").css({"display":"none"});
	}else{
		//console.log("no");
		$("#indicador_jornadas_valor_registrar").val("N");
		//$("#jornadas_registrar").removeClass("hidden");
		$("#jornadas_registrar").css({"display":"block"});
		//$("#horas_jornadas_registrar").attr("required");
	}
});

$("#indicador_plan_empresarial_registrar").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_plan_empresarial_registrar").is(':checked')) {
		//console.log("si");
		$("#indicador_plan_valor_registrar").val("S");
	}else{
		//console.log("no");
		$("#indicador_plan_valor_registrar").val("N");
	}
});


$("#indicador_jornadas_actualizar").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_jornadas_actualizar").is(':checked')) {
		//console.log("si");
		$("#indicador_jornadas_valor_actualizar").val("S");
		//$("#jornadas_actualizar").addClass("hidden");
		$("#jornadas_actualizar").css({"display":"none"});
	}else{
		//console.log("no");
		$("#indicador_jornadas_valor_actualizar").val("N");
		//$("#jornadas_actualizar").removeClass("hidden");
		$("#jornadas_actualizar").css({"display":"block"});
	}
});

$("#indicador_plan_empresarial_actualizar").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_plan_empresarial_actualizar").is(':checked')) {
		//console.log("si");
		$("#indicador_plan_valor_actualizar").val("S");
	}else{
		//console.log("no");
		$("#indicador_plan_valor_actualizar").val("N");
	}
});
/* ------------------------------------------------------------------------------- */
$("#muestra_web_registrar").on("change", function(){
	//alert($(this).val());
	if ($("#muestra_web_registrar").is(':checked')) {
		$("#indicador_muestra_web_registrar").val("S");
	}else{
		$("#indicador_muestra_web_registrar").val("N");
	}
});
/* --------------------------------------------------------------------------------- */
$("#muestra_web_modificar").on("change", function(){
	//alert($(this).val());
	if ($("#muestra_web_modificar").is(':checked')) {
		$("#indicador_muestra_web_modificar").val("S");
	}else{
		$("#indicador_muestra_web_modificar").val("N");
	}
});
/*----------------------------------------------------------------------------------*/








$("#indicador_membresia").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_membresia").is(':checked')) {
		$("#div_hiddens").css("display", "block")
		$(".field_requires").attr("required", "required")
	}else{
		$("#div_hiddens").css("display", "none")
		$(".field_requires").removeAttr("required")
	}
});





$("#indicador_membresia_edit").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_membresia_edit").is(':checked')) {
		$("#div_hiddens_edit").css("display", "block")
		$(".field_requires").attr("required", "required")
	}else{
		$("#div_hiddens_edit").css("display", "none")
		$(".field_requires").removeAttr("required")
	}
});



