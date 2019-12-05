$(document).ready(function(){
	//listar();
	//elegirFecha('.fecha');
	//registrarTemporada();
	//actualizar_planes();
	consultar_temporadas_existentes();
});
/*---------------------------------------------------------------------------------*/
$("#agregar_temporadas").click(function(e){
	e.preventDefault();
	var fila = $("#tbody-temporada").attr("data");
	fila = parseInt(fila)+1;
	$("#tbody-temporada").attr({"data":fila});
	var data = {
					"fila":fila
	}
	$.ajax({
					url:"Temporadas/agregarTemporadas",
					type:'POST',
					cache:false,
					data:data,
					beforeSend: function(){
			                mensajes('info', '<span> Espere unos segundos mientras se cargan las temporadas... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
			            },
					error: function(resp){
						console.log(resp);
					},
					success: function(resp){
						$("#tbody-temporada").append(resp).fadeIn('slow');
						elegirFecha('.fecha');
						mensajes('','');
					}
		});
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
				"url": url + "Planes/listado_planes",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_planes",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data":"cod_planes"},
				{"data":"descripcion",
					render : function(data, type, row) {
						var text = data;
						if (data != null)
							if (data.length > 20)
								text = data.substr(0,19) + "..."
						return text;
					}
				},
				{"data":"vigencia"},
				{"data":"tiempo_contrato"},
				{"data":"fec_regins",
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						return cambiarFormatoFecha(fecha[0]);
	          		}
				},
				{"data":"precio"},
				{"data":"correo_usuario"},
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
				}
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
 	function consultar_temporada_id(id){
 		if($("#check"+id).prop("checked")){
 		//------------------------------------
 			var data = {
						"numero_temporada":id
			}
			$.ajax({
							url:"Temporadas/consultarTemporadas",
							type:'POST',
							cache:false,
							data:data,
							beforeSend: function(){
					                mensajes('info', '<span> Espere unos segundos mientras se carga el mapa de temporada... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
					            },
							error: function(resp){
								console.log(resp);
							},
							success: function(resp){
								if(resp!="vacio"){
									$("#tbody-mapa-temporada").html(resp);
									mensajes('','');
								}else{
									$("#tbody-mapa-temporada").html("");
									mensajes('','');
								}
								
							}
			});
 		//------------------------------------	
 		}else{
 			$("#tbody-mapa-temporada").html("");
			mensajes('','');
 		}
 		
 	}
/* ------------------------------------------------------------------------------- */
	function consultar_temporadas_existentes(){
		var fila = $("#tbody-temporada").attr("data");
		fila = parseInt(fila)+1;
		var data = {
						"fila":fila
		}
		$.ajax({
						url:"Temporadas/consultarTemporadasExistentes",
						type:'POST',
						cache:false,
						data:data,
						beforeSend: function(){
				                mensajes('info', '<span> Espere unos segundos mientras se cargan las temporadas... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
				            },
						error: function(resp){
							console.log(resp);
						},
						success: function(resp){
							if(resp==""){
								$("#agregar_temporadas").click();
							}else{
								$("#tbody-temporada").append(resp).fadeIn('slow');
								$( ".fila" ).each(function( index ) {
									fila = index +1; 
									$("#tbody-temporada").attr({"data":fila});
								});
								elegirFecha('.fecha');
								mensajes('','');
							}
						}
			});
	}
/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro2 para mostrar el formulario de registrar.
	*/
	function nuevoPlanes(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		$("#alertas").css("display", "none");
		$("#form_planes_registrar")[0].reset();
		$("#tipo_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrarTemporada(){
		var contador = 0;
		var vector = {
							"desde":[],
							"hasta":[],
							"ajuste_precio":[],
							"condicion":[],
							"aplicar":[],
							"temporada":[],
							"id":[]		
		}
		$( ".fila" ).each(function( index ) {
		  	var id = $( this ).attr("data");
			
		  	if($("#check"+id).prop("checked")){
		  		vector["temporada"].push(id);
		  		vector.desde.push($("#fecha_desde"+id).val());
		  		vector["hasta"].push($("#fecha_hasta"+id).val());
		  		vector["ajuste_precio"].push($("#ajuste_precio"+id).val());
		  		vector["condicion"].push($('input:radio[name=radio_ajustes'+id+']:checked').val());
		  		vector["aplicar"].push($("#check_ajuste"+id).prop("checked"));
		  		//Verifico si la fila tiene un id/ es decir existe....
		  		var id = $("#check"+id).attr("data");
		  		id !="" ? vector["id"].push(id) : vector["id"].push("");
		  		//---
		  	}
		});
	  	console.log(vector);
	  	var data = {
					"vector":vector
		}
		//--
		$.ajax({
						url:"Temporadas/registrarTemporadas",
						type:'POST',
						cache:false,
						data:data,
						 beforeSend: function(){
			                mensajes('info', '<span> Espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
			            },
						error: function(resp){
							console.log(resp);
						},
						success: function(resp){
							var respuesta =$.parseJSON(resp);
							if(respuesta=="1"){
								ir_consultar_planes_temporada();
								mensajes('success', '<span>El proceso se ha realizado exitosamente</span>');
							}else{
								mensajes('danger', '<span>Ha ocurrido un error inesperado</span>');
							}
						}
		});
	}
/* ------------------------------------------------------------------------------- */
	/*
	*	ir_consultar_planes_temporada
	*/
	
	function ir_consultar_planes_temporada(){
			var fila = $("#tbody-temporada").attr("data");
			data = {
					"numero_temporada":fila
			}
			
			$.ajax({
							url:"Temporadas/consultarTemporadas",
							type:'POST',
							cache:false,
							data:data,
							error: function(resp){
								console.log(resp);
							},
							success: function(resp){
								$("#tbody-mapa-temporada").append(resp);
							}
			});
	}
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
			
			document.getElementById('descripcion_consultar').value = data.descripcion;
			
			document.getElementById('tiempo_contrato_consultar').value = data.tiempo_contrato;

			document.getElementById('precio_consultar').value = data.precio;
			
			$("#vigencia_consultar option").removeAttr("selected");
			
			$("#vigencia_consultar option[value='" + data.id_vigencia + "']").prop("selected",true);

			cuadros('#cuadro1', '#cuadro3');
			
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

			document.getElementById('descripcion_editar').value = data.descripcion;

			document.getElementById('tiempo_contrato_editar').value = data.tiempo_contrato;

			document.getElementById('precio_editar').value = data.precio;
			
			$("#vigencia_editar option[value='" + data.id_vigencia + "']").prop("selected",true);
						
			cuadros('#cuadro1', '#cuadro4');
			
			$("#cod_planes_editar").focus();
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
