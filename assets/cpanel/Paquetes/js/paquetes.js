$(document).ready(function(){
	listar();
	registrar_paquetes();
	actualizar_paquetes();
	decimalesInput('.valor');
	decimalesInput('.precio');
});

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para cargar los datos de la base de datos en la tabla.
	*/
	function listar(cuadro){
		contarModulosIni();
		contarModulosPaquetes();
		$('#tabla tbody').off('click');
		cuadros(cuadro, "#cuadro1");
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		var table=$("#tabla").DataTable({
			"destroy":true,
			"stateSave": true,
			"serverSide":false,
			"ajax":{
				"method":"POST",
				"url": url + "Paquetes/listado_paquetes",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_paquete",
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
				{"data":"descripcion"},
				{"data":"codigo"},
				{"data": "precio",
					render : function(data, type, row) {
						return "<div style='text-align: right;'>"+data+"</div>"
					}
				},
				{"data":"titulo_plan"},
				{"data":"servicios"},
				{"data":"posicion_paquetes"},
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

	var horas = 0;

	var act = 1;
	function nuevoPlanes(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		horas = 0;
		console.log(horas);
		$("#alertas").css("display", "none");
		$("#form_paquetes_registrar")[0].reset();
		$("#tableRegistrar tbody tr").remove(); 
		$("#tipo_registrar").focus();
		$("#indicador_servicio_consumible_registrar").prop("checked", false);
		$("#indicador_servicio_consumible_registrar").val("N");
		//
		$("#muestra_web_registrar").prop("checked", false);
		$("#indicador_muestra_web_registrar").val("N");
		$("#proceso_registrar").val("registrar");
		$("#servicio_registrar").prop("disabled",false)

		act = 1;
		GetPlanes("#plan_registrar", true)
		GetServicios("#servicio_registrar", true)
		$("#membresia").val("S");

		//
	}




	
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que hace un count de los modulos registrados y el resultado se 
		despliega en un select para la seleccion de la posicion del modulo.
	*/
	function contarModulosIni(){
		$('#posicion_servicios_registrar').find('option').remove().end().append('<option value="">Seleccione</option>');
		agregarOptions("#posicion_servicios_registrar", 1, 1);
	}
/* ------------------------------------------------------------------------------- */
function contarModulosPaquetes(){
		$('#posicion_paquetes_registrar').find('option').remove().end().append('<option value="">Seleccione</option>');
		$('#posicion_paquetes_editar').find('option').remove().end().append('<option value="">Seleccione</option>');
		$.ajax({
	        url:document.getElementById('ruta').value + 'Paquetes/contar_modulos',
	        type:'POST',
	        dataType:'JSON',
	        error: function() {
				contarModulos();
	        },
	        success: function(respuesta){
	            var selectRegistrar = Object.keys(respuesta).length +1;
	            var selectActualizar = Object.keys(respuesta).length;
	            for(var i = 1; i <= selectRegistrar; i++)
	            	agregarOptions("#posicion_paquetes_registrar", i, i);
	            for(var i = 1; i <= selectActualizar; i++)
	            	agregarOptions("#posicion_paquetes_editar", i, i);
	        }
	    });
	}
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_paquetes(){
		//enviarFormulario("#form_paquetes_registrar", 'Paquetes/registrar_paquetes', '#cuadro2');
		/*-------------------------------------------------------------------------------------*/
		$("#form_paquetes_registrar").submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            
            //var plan = $("#plan_registrar").val()
            var codigo = $("#codigo_registrar").val()
            
            var precio = $("#precio_registrar").val()
            
            var plan  = $("#plan_registrar").val()

            var descripcion = $("#descripcion_registrar").val() 

            var posicion_paquetes = $("#posicion_paquetes_registrar").val() 
            
            var indicador_muestra_web_registrar = $("#indicador_muestra_web_registrar").val();

            var objeto = {
            				"servicios"  :[],
            				"valor"      :[],
            				"ilimitado" :[],
            				"consumible" :[],
            				"posicion"   :[],
            };

            $("#tableRegistrar tbody tr").each(function() {
            	
            	var servicios = [];
   	            var vector_valor = [];
            	var id = $(this).find(".id_servicio").val();
            	//--Hago push servicios
            
            	if (id != undefined){
	            	//servicios.push(id);
					objeto["servicios"].push(id);
				}
				//--Hago push a valor
				var valor  = $(this).find(".valor_servicio").text();
			
				if (valor != undefined){
					//vector_valor.push(valor);
					objeto["valor"].push(valor);
				}



				var valor_ilimitado  = $(this).find(".servicio_ilimitado").text();

				if(valor_ilimitado != undefined){
					objeto["ilimitado"].push(valor_ilimitado);
				}



				//--Hago push al consumible...
				var valor_consumible  = $(this).find(".consumible_servicio").text();

				if(valor_consumible != undefined){
					objeto["consumible"].push(valor_consumible);
				}
				//--Hagop push a la posicion
				var valor_posicion  = $(this).find(".posicion_servicio").text();

				if(valor_posicion != undefined){
					objeto["posicion"].push(valor_posicion);
				}
				
				//--Hago push al id del paquete
				/*var id_plan = $(this).find(".id_plan").val();
			
				if(id_plan != undefined){
					objeto["plan"].push(id_plan)
				}*/

			});
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url: document.getElementById('ruta').value + 'Paquetes/registrar_paquetes',
                type: 'POST',
                dataType:'JSON',
                data:{
                	'codigo' : codigo,
                	'descripcion':descripcion,
                	'precio':precio,
                	'plan':plan,
                	'posicion_paquetes':posicion_paquetes,
                	'indicador_muestra_web_registrar':indicador_muestra_web_registrar,
					'planes_servicios' : objeto,
					'membresia' : $("#membresia").val()
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
                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    mensajes('success', respuesta);
                    listar('#cuadro2');
                }
            });
        });
		/*-------------------------------------------------------------------------------------*/
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

			//$("#plan_consultar option").removeAttr("selected");
			
			//$("#plan_consultar option[value='" + data.id_plan + "']").prop("selected",true);

			/*$("#servicio_consultar option").removeAttr("selected");

			$("#servicio_consultar option[value='" + data.id_servicio + "']").prop("selected",true);
			
			document.getElementById('valor_consultar').value = data.valor;*/
			
			$("#codigo_consultar").val(data.codigo)

			$("#descripcion_consultar").val(data.descripcion)

			$("#precio_consultar").val(data.precio)
			
			$("#plan_consultar option[value='" + data.plan + "']").prop("selected",true);

			$("#posicion_paquetes_consultar").val(data.posicion_paquetes)

			modalServicios(data.id_paquete, '#tbodyConsultar');

			$(".eliminar_consulta").prop("disabled",true);

			cuadros('#cuadro1', '#cuadro3');
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
				$(".remove").css("display", "block")
			}else{
				$("#indicador_membresia_view").prop("checked", false);
				$(".remove").css("display", "none")
			}

			//---------------------------------------------------------
		});
	}
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que hace una busqueda de los servicios
		por parametro.
	*/
	function modalServicios(id, div){
		$.ajax({
	        url:document.getElementById('ruta').value + 'Paquetes/operaciones_servicios',
	        type:'POST',
	        dataType:'JSON',
	        data: {'id' : id},
	        beforeSend: function() {
	        	$(div).html(loading());
	        },
	        error: function() {
                var html ='<div class="alert alert-danger" role="alert">';
		        html += '<span>¡Se ha producido un error!. Presiona <strong style="text-decoration: underline;" onclick="modalOperaciones(' + id + ')">aquí</strong> para intentarlo de nuevo</span>';
		        html += '</div>';
		        $(div).html(html);
	        },
	        success: function(respuesta){
	            //--Migracion Mongo DB---
	            
	            console.log(respuesta);
	            
	            disabled = ""
	            cuantosServ= 0;
	            if(div == "#tbodyConsultar"){
	            	disabled = "disabled"
	            }
	            if(div =="#tbodyEditar"){
	            	editar_campo = "editar"	
	            }
	            if(respuesta.length>0){
	            	var table  = "";
	            	//----
	            	//--Ordeno la tabla segun la posicion
					respuesta.sort(function (a, b) {
						//---
						if (a.posicion > b.posicion) {
						    return 1;
						}
						if (a.posicion < b.posicion) {
						    return -1;
						}
					    // a must be equal to b
					    return 0;
					});
	            	//----
	            	Object.keys(respuesta).forEach(function(k){
					    console.log(k + ' - ' + respuesta[k]);
					    table += "<tr id='r" + respuesta[k]["id_servicio"]+ "'><td>" + respuesta[k]["id_servicio"] + "</td>";
						//table += "<td>"+respuesta[k]["titulo_plan"] + " <input type='hidden' class='id_plan' name='id_plan' value='" + respuesta[k]["id_plan"] + "'></td>";
						table += "<td class='titulo_servicio'>"+respuesta[k]["titulo_servicio"] + " <input type='hidden' class='id_servicio' name='id_servicio' value='" + respuesta[k]["id_servicio"] + "'></td>";
						table += "<td id='r_valor_servicio" + respuesta[k]["id_servicio"] + "' class='valor_servicio' >" + respuesta[k]["valor"] + "</td>";


						table += "<td id='r_valor_servicio_ilimitado" + respuesta[k]["id_servicio"] + "' class='servicio_ilimitado' >" + respuesta[k]["ilimitado"] + "</td>";



						table += "<td id='r_valor_servicio_consumible" + respuesta[k]["id_servicio"] + "' class='consumible_servicio' >" + respuesta[k]["consumible"] + "</td>";


						table += "<td id='r_valor_servicio_posicion" + respuesta[k]["id_servicio"] + "' class='posicion_servicio' >" + respuesta[k]["posicion"] + "</td>";
						table += "<td><button type='button'  "+disabled+"  class='eliminar eliminar_consulta btn btn-xs btn-danger waves-effect ' data-toggle='tooltip' title='Eliminar' onclick='eliminarServiciosPlanes(\""+ respuesta[k]["id_servicio"] +"\")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button>";

						if(div =="#tbodyEditar"){
							cuantosServ++;
							table += "<button type='button' class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar' onclick='actualizarServicios(\""+editar_campo+"\",\"" + "#r" + respuesta[k]["id_servicio"] + "\",\""+respuesta[k]["categoria"]+"\")'><i class='fa fa-pencil-square-o' style='margin-bottom:5px;'></i></button>";
						}

						table += "</td></tr>";
					});

		            $(div).html(table);
		            //---
		            if(cuantosServ>0){
		            	$('#posicion_servicios_editar').find('option').remove().end().append('<option value="">Seleccione</option>');

		            	for(i=1;i<=cuantosServ;i++){
							agregarOptions("#posicion_servicios_editar",i, i);
			            }
			            agregarOptions("#posicion_servicios_editar",i, i);
		            }
		            //---
	            }
				//--
	        }
	    });
	}
/* ------------------------------------------------------------------------------- */
function eliminarServiciosPlanes(id_servicio){
		id_paquete = $("#id_paquetes_editar").val();
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
                    url: document.getElementById('ruta').value + "Paquetes/eliminar_paquete_servicio",
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        'id_servicio': id_servicio,
                        'id_paquete': id_paquete
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                        mensajes('danger', errores);
                    },
                    success: function(respuesta){
                        mensajes('success', respuesta);
                        //alert("#r"+id_servicio+"-"+id_plan);
                        $("#tableEditar").find("tbody tr#r" + id_servicio).remove();
                        modalServicios(id_paquete, '#tbodyEditar');
                    }
                });
            } else {
                swal("Cancelado", "No se ha eliminado el registro", "error");
            }
        });
	}
/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$("#form_paquetes_editar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			
			$("#alertas").css("display", "none");
			
			var data = table.row( $(this).parents("tr") ).data();

			console.log(data);

			$("#alertas").css("display", "none");

			document.getElementById('id_paquetes_editar').value = data.id_paquete;

			//$("#plan_editar option").removeAttr("selected");
			
			//$("#plan_editar option[value='" + data.id_plan + "']").prop("selected",true);

			/*$("#servicio_editar option").removeAttr("selected");

			$("#servicio_editar option[value='" + data.id_servicio + "']").prop("selected",true);
			
			document.getElementById('valor_editar').value = data.valor;*/
			//---
			/*#Nuevos paquetes */
			$("#codigo_editar").val(data.codigo)

			$("#descripcion_editar").val(data.descripcion)

			$("#precio_editar").val(data.precio)

			

			//---
						
			cuadros('#cuadro1', '#cuadro4');
			
			//$("#codigo_editar").focus();

			$("option[status='']").prop('hidden', true);

			//$("#plan_editar").attr('disabled','disabled');
			$("#posicion_paquetes_editar option[value='"+data.posicion_paquetes+"']").attr("selected","selected");
			document.getElementById('inicial').value=data.posicion_paquetes;
			//alert(data.id_paquete)
			$("#indicador_servicio_consumible_modificar").val("N");
			$("#indicador_servicio_consumible_modificar").prop("checked", false);
			modalServicios(data.id_paquete, '#tbodyEditar');
			//------------------------------
			if (data.muestra_en_web == true) {
				$("#muestra_web_modificar").prop("checked", true);
				$("#indicador_muestra_web_modificar").val("S");
			}else{
				$("#muestra_web_modificar").prop("checked", false);
				$("#indicador_muestra_web_modificar").val("N");
			}



			$("#valor_editar").val("").removeAttr("disabled");


			
			//------------------------------
			$("#servicio_editar").prop("disabled",false)
			$("#proceso_editar").val("registrar");



			if (data.membresia == true) {
				$("#indicador_membresia_edit").prop("checked", true);
				$("#membresia_edit").val("S")
				$(".remove").css("display", "block")
				GetPlanes("#plan_editar", true, data.plan)
			}else{
				$("#indicador_membresia_edit").prop("checked", false);
				$("#membresia_edit").val("N")
				$(".remove").css("display", "none")
				GetPlanes("#plan_editar", false,data.plan)
			}
			$("#plan_editar option[value='" + data.plan + "']").prop("selected",true);

		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_paquetes(){
		//enviarFormulario("#form_paquetes_editar", 'Paquetes/actualizar_paquetes', '#cuadro4');
		$("#form_paquetes_editar").submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            
            var id_paquete = $("#id_paquetes_editar").val()

            var codigo = $("#codigo_editar").val()
            
            var precio = $("#precio_editar").val()
            
            var plan  = $("#plan_editar").val()

            var descripcion = $("#descripcion_editar").val() 

            var inicial = $("#inicial").val();

            var posicion_paquetes = $("#posicion_paquetes_editar").val()

			var indicador_muestra_web_modificar = $("#indicador_muestra_web_modificar").val()
            
            var objeto = {
            				"servicios":[],
            				"valor":[],
            				"ilimitado":[],
            				"consumible":[],
            				"posicion":[],
            };
            
            $("#tableEditar tbody tr").each(function() {
            	
            	var servicios = [];
   	            var vector_valor = [];
            	var id = $(this).find(".id_servicio").val();
            	//--Hago push servicios
            
            	if((id != undefined)&&($(this).find(".id_servicio").hasClass("editar"))){
	            	//servicios.push(id);
					objeto["servicios"].push(id);
				}
				//--Hago push a valor
				var valor  = $(this).find(".valor_servicio").text();
			
				if((valor != undefined) && ($(this).find(".valor_servicio").hasClass("editar"))){
					//vector_valor.push(valor);
					objeto["valor"].push(valor);
				}






				var valor_ilimitado  = $(this).find(".servicio_ilimitado ").text();
				
				if((valor_ilimitado != undefined)&& ($(this).find(".valor_servicio").hasClass("editar"))){
					objeto["ilimitado"].push(valor_ilimitado);
				}



				//--Hago push al consumible...
				var valor_consumible  = $(this).find(".consumible_servicio").text();
				
				if((valor_consumible != undefined)&& ($(this).find(".valor_servicio").hasClass("editar"))){
					objeto["consumible"].push(valor_consumible);
				}

				//--Hagop push a la posicion
				var valor_posicion  = $(this).find(".posicion_servicio").text();

				if(valor_posicion != undefined){
					objeto["posicion"].push(valor_posicion);
				}
				//--Hago push al id del paquete
				/*var id_plan = $(this).find(".id_plan").val();
			
				if((id_plan != undefined) && ($(this).find(".id_plan").hasClass("editar"))){
					objeto["plan"].push(id_plan)
				}*/

			});
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url: document.getElementById('ruta').value + 'Paquetes/actualizar_paquetes',
                type: 'POST',
                dataType:'JSON',
                data:{
	                	'id_paquete': id_paquete,
	                	'codigo' : codigo,
	                	'descripcion':descripcion,
						'plan': plan,
						'membresia' : $("#membresia_edit").val(),
	                	'precio':precio,
	                	'inicial':inicial,
	                	'posicion_paquetes':posicion_paquetes,
	                	'indicador_muestra_web_modificar':indicador_muestra_web_modificar,
	                	'servicios' : objeto
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
            eliminarConfirmacion('Paquetes/eliminar_paquetes', data.id_paquete, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacionPaquetes('Paquetes/status_paquetes', data.id_paquete, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacionPaquetes('Paquetes/status_paquetes', data.id_paquete, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que agrega las lista ista a la tabla
	*/

	function agregarServicio(select, tabla,valor_registrar,plan,valor_consumible,valor_posicion, servicio_ilimitado){
		
		var super_value = $(select).val();
		var vector_value = super_value.split("|");
		var value = vector_value[0];
		var tipo = vector_value[1]; 
		var categoria =  vector_value[2]; 
		var valor_servicio = $(valor_registrar).val(); 
		var consumible = $(valor_consumible).val();

		var ilimitados = $(servicio_ilimitado).val()

		var posicion = $(valor_posicion).val()
		if(tabla == "#tableEditar"){
			proceso = $("#proceso_editar").val();
		}else{
			proceso = $("#proceso_registrar").val();
		}
		if(isNaN(valor_servicio)){
			valor_servicio = valor_servicio.toUpperCase(); 
		}
		
		var text = $(select + " option:selected").html();
		
		var text_plan = $(plan + " option:selected").html();

		//alert("Plan: "+text_plan)
		
		var value_plan = $(plan).val(); 

		var validadoServicio = false;
		
		var html = '';
				
		if((tipo=="N")&&(isNaN(valor_servicio)) && (valor_servicio) != "S"){
			warning('¡EL valor de servicio debe ser numérico!');
			$("input[name='valor]").focus();
		}else if((tipo=="C")&&(!isNaN(valor_servicio))  && act == 1){
			warning('¡EL valor de servicio debe ser caracter!');
			$("input[name='valor]").focus();
		}else if((tipo=="C")&&((valor_servicio!="S") && (valor_servicio!="N")) && (act == 1)) {
			warning('¡EL valor de servicio debe ser caracter S ó N! '+valor_servicio);
			$("input[name='valor]").focus();
		}else if(posicion=="" && act == 1){
			warning('¡Debe seleccionar una posición para el servicio!');
		}else{
		
			if ((value != "")) {
				$(tabla + " tbody tr").each(function() {
				  	if (value == $(this).find(".id_servicio").val())
				  		validadoServicio = true;
				});
				editar_campo = "";
				if(tabla == "#tableEditar"){
	            	editar_campo = "editar"
	            	campoForm = "actualizar"
	            	posicionInicial = $("#posicionInicialModificar").val()
	            }else{
	            	campoForm = "registrar"
					posicionInicial = $("#posicionInicialRegistrarServicios").val()
	            }
	            
	            //---
				if (!validadoServicio) {

					if (categoria == "Horas") {
						horas = horas + 1;
					}

					if (horas >= 2) {
						warning('¡El paquete solo debe tener un servicio de categoría Hora!');
						horas = horas - 1;
					}else{
						//alert(html)
						/*if (validadoModulo) {
							$(tabla + " tbody #mv" + idModulo).after(html);
						} else {*/
						//#---Armar tablas
		            	html += "<tr id='r" + value +"'><td>" + value + "</td>";
						//html += "<td>"+text_plan + " <input type='hidden' class='id_plan "+editar_campo+"' name='id_plan' value='" + value_plan + "'></td>";
						html += "<td class='titulo_servicio "+editar_campo+"'>"+text + " <input type='hidden' class='id_servicio "+editar_campo+"' name='id_servicio' value='" + value + "'></td>";
						html += "<td id='r_valor_servicio" + value + "' class='valor_servicio "+editar_campo+"' >" + valor_servicio + "</td>";


						html += "<td id='r_valor_servicio_ilimitado" + value + "' class='servicio_ilimitado "+editar_campo+"' >" + ilimitados + "</td>";


						html += "<td id='r_valor_servicio_consumible" + value + "' class='consumible_servicio "+editar_campo+"' >" + consumible + "</td>";
						html += "<td id='r_valor_servicio_posicion" + value + "' class='posicion_servicio "+editar_campo+"' >" + posicion + "</td>";
						html += "<td><button type='button' class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar' onclick='eliminarServicios(\"" + "#r" + value + "\", \"" + categoria + "\")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button><button type='button' style='display:none' class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar' onclick='actualizarServicios(\""+editar_campo+"\",\"" + "#r" + value + "\")'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></button></td></tr>";
						//--Actualizo las posiciones antes de incluir la tabla....
						$(tabla + " tbody").append(html);
						//--
						contarServicios(tabla);
						//--Hace lo mismo pero ordenado->
						ordenarServicios(posicion,value,tabla, categoria);
						limpiarCamposSuperiores(tabla);
					}
				
					//--
					/*}*/
				} else if((validadoServicio)&&(proceso=="registrar")){
					warning('¡La opción ya se encuentra agregada en la tabla, para modificarla pulse el botón azul de actualizar!');
				}else if (validadoServicio) {
					//warning('¡La opción seleccionada ya se encuentra agregada!');
					//#---Armar tablas
					//html += "<td>"+text_plan + " <input type='hidden' class='id_plan "+editar_campo+"' name='id_plan' value='" + value_plan + "'></td>";
					html += "<td>" + value + "</td>";
					html += "<td class='titulo_servicio "+editar_campo+"'>"+text + " <input type='hidden' class='id_servicio "+editar_campo+"' name='id_servicio' value='" + value + "'></td>";
					
					html += "<td id='r_valor_servicio" + value + "' class='valor_servicio "+editar_campo+"' >" + valor_servicio + "</td>";
					
					html += "<td id='r_valor_servicio_ilimitado" + value + "' class='servicio_ilimitado "+editar_campo+"' >" + ilimitados + "</td>";

					html += "<td id='r_valor_servicio_consumible" + value + "' class='consumible_servicio "+editar_campo+"' >" + consumible + "</td>";
					html += "<td id='r_valor_servicio_posicion" + value + "' class='posicion_servicio "+editar_campo+"' >" + posicion + "</td>";
					html += "<td><button type='button' class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar' onclick='eliminarServicios(\"" + "#r" + value + "\"" + categoria + "\")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button><button type='button' style='display:none' class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar' onclick='actualizarServicios(\""+editar_campo+"\",\"" + "#r" + value + "\")'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></button></td>";

					campo_servicio = "#r" + value;	
					//--Actualizo las posiciones antes de incluir la tabla....
					posicionFinal = posicion
					proceso = $("#proceso_editar").val()
					actualizarPosicionesEditar(tabla,posicionInicial,posicionFinal);
					$(campo_servicio).html(html);
					//contarServicios();
					//--Hace lo mismo pero ordenado->
					ordenarServicios(posicion,value,tabla, categoria);
					limpiarCamposSuperiores(tabla);
					//--
					
					//--
				}
				
				$(select + " option[value='']").attr("selected","selected");
			} else {
				warning('¡Debe seleccionar una opciónsss!'+act);
			}
		}
	}
	/***/
	function actualizarPosicionesEditar(tabla,posicionInicial,posicionFinal){
		if(posicionFinal>posicionInicial){
			//---Recorro la tabla
			$(tabla+" tbody tr").each(function() {
				//--Capturo el id
				var id = $(this).find(".id_servicio").val();
				//--Capturo la posicion
				var valor_posicion  = $(this).find(".posicion_servicio").text();	
				//--Si la posicon actual es mayor a la inicial y menor igual a la final	
				if((valor_posicion>posicionInicial)&&(valor_posicion<=posicionFinal)){
					menos_uno = parseInt(valor_posicion)-1;
					$("#r_valor_servicio_posicion"+id).html(menos_uno);
				}				
				//--
			});
			//---
		}else if(posicionFinal<posicionInicial){
			//---Recorro la tabla
			$(tabla+" tbody tr").each(function() {
				//--Capturo el id
				var id = $(this).find(".id_servicio").val();
				//--Capturo la posicion
				var valor_posicion  = $(this).find(".posicion_servicio").text();	
				//--Si la posicon actual es mayor o igual a la final y menor igual a la inicial	
				if((valor_posicion>=posicionFinal)&&(valor_posicion<posicionInicial)){
					mas_uno = parseInt(valor_posicion)+1;
					$("#r_valor_servicio_posicion"+id).html(mas_uno);
				}				
				//--
			});
			//---
		}
	}
	/***/
	function actualizarServicios(tipo,valor, categoria = null){
		var tr =$(valor).html();
		var posicion = $(valor).find(".posicion_servicio").text()
		//alert(posicion)
		var id_servicio1 = $(valor).find(".id_servicio").val()+"|C|"+categoria
		var id_servicio2 = $(valor).find(".id_servicio").val()+"|N|"+categoria
		var valor_servicio = $(valor).find(".valor_servicio").text()
		var consumible_servicio = $(valor).find(".consumible_servicio").text()
		var servicio_ilimitado = $(valor).find(".servicio_ilimitado").text()
		
		/*alert("posicion:"+posicion);
		alert("id_servicio:"+id_servicio)
		alert("valor_Servicio:"+valor_servicio)
		alert("consumible_servicio:"+consumible_servicio)*/
		if(tipo=="editar"){
			$("#posicionInicialModificar").val(posicion)
			//--Para consumibles:
			if(consumible_servicio=="S"){
				$("#indicador_servicios_modificar").prop("checked", true);
				$("#indicador_servicio_consumible_modificar").val("S");
			}else if(consumible_servicio=="N"){
				$("#indicador_servicios_modificar").prop("checked", false);
				$("#indicador_servicio_consumible_modificar").val("N");
			}


			if(servicio_ilimitado=="S"){
				$("#indicador_jornadas_registrar").prop("checked", true);
				$("#indicador_jornadas_valor_registrar").val("S");
			}else if(servicio_ilimitado=="N"){
				$("#indicador_jornadas_registrar").prop("checked", false);
				$("#indicador_jornadas_valor_registrar").val("N");
			}


			//--Para valor
			$("#valor_editar").val(valor_servicio)	
			//--Select de servicio
			$("#servicio_editar option[value='" + id_servicio1 + "']").prop("selected",true);
			$("#servicio_editar option[value='" + id_servicio2 + "']").prop("selected",true);
			$("#servicio_editar").prop("disabled",true)
			//--Para orden
			$("#posicion_servicios_editar option[value='" + posicion + "']").prop("selected",true);
			$("#proceso_editar").val("actualizar");
		}else{
			//-----------------------------------------------------------
			$("#posicionInicialRegistrarServicios").val(posicion)
			if(consumible_servicio=="S"){
				$("#indicador_servicios_registrar").prop("checked", true);
				$("#indicador_servicio_consumible_registrar").val("S");
			}else if(consumible_servicio=="N"){
				$("#indicador_servicios_registrar").prop("checked", false);
				$("#indicador_servicio_consumible_registrar").val("N");
			}



			if(servicio_ilimitado=="S"){
				$("#indicador_jornadas_registrar").prop("checked", true);
				$("#indicador_jornadas_valor_registrar").val("S");
			}else if(servicio_ilimitado=="N"){
				$("#indicador_jornadas_registrar").prop("checked", false);
				$("#indicador_jornadas_valor_registrar").val("N");
			}



			//--Para valor
			$("#valor_registrar").val(valor_servicio)	
			//--Select de servicio
			$("#servicio_registrar option[value='" + id_servicio1 + "']").prop("selected",true);
			$("#servicio_registrar option[value='" + id_servicio2 + "']").prop("selected",true);
			$("#servicio_registrar").prop("disabled",true)
			//--Para orden
			$("#posicion_servicios_registrar option[value='" + posicion + "']").prop("selected",true);
			$("#proceso_registrar").val("actualizar");
			//-----------------------------------------------------------
		}
		
	}
	/*
	*
	*/
	function limpiarCamposSuperiores(tabla){
		if(tabla=="#tableRegistrar"){
			$("#servicio_registrar").val("")
			$("#valor_registrar").val("")
			$("#posicion_servicios_registrar").val("")
			$("#indicador_servicios_registrar").prop("checked", true);
			$("#indicador_servicio_consumible_registrar").val("S");
			$("#servicio_registrar").prop("disabled",false)
			$("#posicionInicialRegistrarServicios").val("")
			$("#proceso_registrar").val("registrar");
		}else if(tabla=="#tableEditar"){
			$("#servicio_editar").val("")
			$("#valor_editar").val("")
			$("#posicion_servicios_editar").val("")
			$("#indicador_servicios_modificar").prop("checked", true);
			$("#indicador_servicio_consumible_modificar").val("S");
			$("#servicio_editar").prop("disabled",false)
			$("#posicionInicialModificar").val("")
			$("#proceso_editar").val("registrar");
		}
		
	}
	/*
	*
	*/
	function contarServicios(tabla){
	 	var a = 1;
	 	if(tabla=="#tableEditar"){
	 		$('#posicion_servicios_editar').find('option').remove().end().append('<option value="">Seleccione</option>');

			$("#tableEditar tbody tr").each(function() {
				//--
				agregarOptions("#posicion_servicios_editar", a, a);
				a++;
				//--
			});
			agregarOptions("#posicion_servicios_editar", a, a);
	 	}else{
	 	//-----------------------------------------------------
		 	$('#posicion_servicios_registrar').find('option').remove().end().append('<option value="">Seleccione</option>');

			$("#tableRegistrar tbody tr").each(function() {
				//--
				agregarOptions("#posicion_servicios_registrar", a, a);
				a++;
				//--
			});
			agregarOptions("#posicion_servicios_registrar", a, a);
	 	//-----------------------------------------------------	
	 	}
	 	
	}
	/*
	*
	*/
	function ordenarServicios(posicion,id_nuevo_ingreso,tabla, categoria){
		/*alert("Posicion:"+posicion);
		alert("Id:"+id_nuevo_ingreso);*/
	 	var a = 1;
	 	var mas_uno = 0;
	 	var entro = 0;
	 	//---
	 	var superObjeto = [];
 	    var objeto = {
        				"id_servicio":"",
        				"titulo":"",
        				"valor":"",
        				"ilimitado":"",
        				"consumible":"",
        				"posicion":"",
        };
	 	//---
		 	$(tabla+" tbody tr").each(function() {
				//--
				
				//--Hago push a la posicion
				var valor_posicion  = $(this).find(".posicion_servicio").text();
				var id = $(this).find(".id_servicio").val();
				if (id != undefined){
	            	//servicios.push(id);
					objeto["id_servicio"] = id;
				}
				//--Hago push a valor
				var valor  = $(this).find(".valor_servicio").text();
			
				if (valor != undefined){
					//vector_valor.push(valor);
					objeto["valor"] = valor;
				}



				var valor_ilimitado  = $(this).find(".servicio_ilimitado").text();

				if(valor_ilimitado != undefined){
					objeto["ilimitado"] = valor_ilimitado;
				}



				//--Hago push al consumible...
				var valor_consumible  = $(this).find(".consumible_servicio").text();

				if(valor_consumible != undefined){
					objeto["consumible"] = valor_consumible;
				}
				//--Hagop push a la posicion
				var valor_posicion  = $(this).find(".posicion_servicio").text();

				if(valor_posicion != undefined){
					objeto["posicion"] = parseInt(valor_posicion);
				}
				//--Hago push al titulo
				var valor_titulo  = $(this).find(".titulo_servicio").text();
				if(valor_titulo != undefined){
					objeto["titulo"] = valor_titulo;
				}
				//--
				if(tabla == "#tableEditar"){
					proceso = $("#proceso_editar").val();
				}else{
					proceso = $("#proceso_registrar").val();
				}
				
				//--Si el proceso es registrar se modifica las posiciones sumandole uno
				if(proceso=="registrar"){
					if(valor_posicion!=""){
						//------------------------
						if((valor_posicion==posicion)&&(id!=id_nuevo_ingreso)){
							mas_uno = parseInt(valor_posicion)+1;
							$("#r_valor_servicio_posicion"+id).html(mas_uno);
							objeto["posicion"] = mas_uno;
							entro = 1;
						}
						//--
						if((entro==1)&&(id!=id_nuevo_ingreso)){
							mas_uno = parseInt(valor_posicion)+1;
							$("#r_valor_servicio_posicion"+id).html(mas_uno);
							objeto["posicion"] = mas_uno;
						}
						//------------------------
					}
				}
				superObjeto.push(objeto)
				objeto = {
	        				"id_servicio":"",
	        				"titulo":"",
	        				"valor":"",
	        				"ilimitado":"",
	        				"consumible":"",
	        				"posicion":"",
	        	};
				a++;
				//--
			});
		
		//---
		//--Ordeno la tabla segun la posicion
		superObjeto.sort(function (a, b) {
			//---
			if (a.posicion > b.posicion) {
			    return 1;
			}
			if (a.posicion < b.posicion) {
			    return -1;
			}
		    // a must be equal to b
		    return 0;
		});
		console.log(superObjeto)
		//---
		var html = "";
		var editar_campo = "";
		if(tabla == "#tableEditar"){
        	editar_campo = "editar"
        }
        //alert("editar_campo:"+editar_campo);
		//--Se pinta de nuevo el html de la tabla
		Object.keys(superObjeto).forEach(function(k){
		    console.log(k + ' - ' + superObjeto[k]);
		    //-------------------------
			html += "<tr id='r" + superObjeto[k]["id_servicio"] +"'><td>" + superObjeto[k]["id_servicio"] + "</td>";
			html += "<td class='titulo_servicio "+editar_campo+"'>"+superObjeto[k]["titulo"] + " <input type='hidden' class='id_servicio "+editar_campo+"' name='id_servicio' value='" + superObjeto[k]["id_servicio"] + "'><input type='hidden' class='categoria_servicio "+editar_campo+"' name='categoria_servicio' value='" + categoria + "'></td>";
			html += "<td id='r_valor_servicio" + superObjeto[k]["id_servicio"] + "' class='valor_servicio "+editar_campo+"' >" +  superObjeto[k]["valor"]  + "</td>";


			html += "<td id='r_valor_servicio_ilimitado" + superObjeto[k]["id_servicio"] + "' class='servicio_ilimitado "+editar_campo+"' >" + superObjeto[k]["ilimitado"] + "</td>";


			html += "<td id='r_valor_servicio_consumible" + superObjeto[k]["id_servicio"] + "' class='consumible_servicio "+editar_campo+"' >" + superObjeto[k]["consumible"] + "</td>";
			html += "<td id='r_valor_servicio_posicion" + superObjeto[k]["id_servicio"] + "' class='posicion_servicio "+editar_campo+"' >" + superObjeto[k]["posicion"] + "</td>";
			html += "<td><button type='button' class='eliminar btn btn-xs btn-danger waves-effect' ata-toggle='tooltip' title='Eliminar' onclick='eliminarServicios(\"" + "#r" + superObjeto[k]["id_servicio"] + "\",\""+categoria+"\")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button><button type='button' class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar' onclick='actualizarServicios(\""+editar_campo+"\",\"" + "#r" + superObjeto[k]["id_servicio"] + "\",\""+categoria+"\")'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></button></td></tr>";

			//-------------------------
		});
		$(tabla+" tbody").html(html);
		//---
	}
	/***/
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que agrega las lista ista a la tabla
	*/
	function eliminarServicios(tr, categoria){
		$(tr).remove(); 

		if (categoria == "Horas") {
			horas = horas - 1;
		}
	}
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
/*
    Funcion que se encarga eliminar un registro seleccionado.

/* ------------------------------------------------------------------------------- */
function statusMultiplePaquetes(controlador, status, confirmButton){
    var id=$(".checkitem:checked").map(function(){
        return $(this).val();
    }).get().join(' ');
    if(Object.keys(id).length>0)
        statusConfirmacionPaquetes(controlador, id, status, "¿Esta seguro de "+confirmButton+" los registros seleccionados?", confirmButton);
    else
        swal({
            title: "Debe seleccionar al menos una fila.",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar!",
            closeOnConfirm: true
        });
}
function statusConfirmacionPaquetes(controlador, id, status, title, confirmButton){
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
            vector_status = id.split(" ");
            $.each(vector_status, function( index, value ) {
	            $.ajax({
	                url:url+controlador,
	                type: 'POST',
	                dataType: 'JSON',
	                data:{
	                    id:value,
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
	                	console.log(respuesta);
	                    /*listar();
	                    $("#checkall").prop("checked", false);
	                    mensajes('success', "<span>Cambios realizados exitosamente!</span>");*/
	                }
	            });
	            listar();
                $("#checkall").prop("checked", false);
                mensajes('success', "<span>Cambios realizados exitosamente!</span>");
	        });    
        } else {
            swal("Cancelado", "Proceso cancelado", "error");
        }
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
$("#muestra_web_registrar").on("change", function(){
	//alert($(this).val());
	if ($("#muestra_web_registrar").is(':checked')) {
		$("#indicador_muestra_web_registrar").val("S");
		$("#posicion_paquetes_registrar").removeAttr("disabled");
	}else{
		$("#indicador_muestra_web_registrar").val("N");
		$("#posicion_paquetes_registrar").attr("disabled", "disabled");
	}
});
/* --------------------------------------------------------------------------------- */
$("#muestra_web_modificar").on("change", function(){
	//alert($(this).val());
	if ($("#muestra_web_modificar").is(':checked')) {
		$("#indicador_muestra_web_modificar").val("S");
		$("#posicion_paquetes_editar").removeAttr("disabled");
	}else{
		$("#indicador_muestra_web_modificar").val("N");
		$("#posicion_paquetes_editar").attr("disabled", "disabled");
	}
});
/*----------------------------------------------------------------------------------*/





$("#indicador_jornadas_registrar").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_jornadas_registrar").is(':checked')) {
		$("#indicador_jornadas_valor_registrar").val("S");

		//$("#valor_registrar").val("S").attr("disabled", "disabled");
	}else{
		$("#indicador_jornadas_valor_registrar").val("N");
		//$("#valor_registrar").val("").removeAttr("disabled");
	}
});




$("#indicador_jornadas_editar").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_jornadas_editar").is(':checked')) {
		$("#indicador_jornadas_valor_editar").val("S");

		$("#valor_editar").val("S").attr("disabled", "disabled");
	}else{
		$("#indicador_jornadas_valor_editar").val("N");
		$("#valor_editar").val("").removeAttr("disabled");
	}
});








$("#indicador_membresia").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_membresia").is(':checked')) {
		GetPlanes("#plan_registrar", true)
		GetServicios("#servicio_registrar", true)
		$(".remove").css("display", "block")
		 act = 1;
		 $("#membresia").val("S");
	}else{
		GetPlanes("#plan_registrar", false)
		GetServicios("#servicio_registrar", false)
		$(".remove").css("display", "none")
		act = 0;
		$("#membresia").val("N");
	}
});









$("#indicador_membresia_edit").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_membresia_edit").is(':checked')) {
		GetPlanes("#plan_editar", true)
		$(".remove").css("display", "block")
		 act = 1;
		 $("#membresia_edit").val("S");
	}else{
		GetPlanes("#plan_editar", false)
		$(".remove").css("display", "none")
		act = 0;
		$("#membresia_edit").val("N");
	}
});









function GetPlanes(select, membresia,id=''){
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
			console.log(item)
				if(item.status == true){
				if((membresia == true && item.membresia == true) || (membresia == false && item.membresia == false)){
					$(select).append($('<option>',
					{
					  value: item._id.$id,
					  text : item.titulo
					}));
				}
			}else {
				if(id !=''){	
				if(id == item.id_planes)
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

