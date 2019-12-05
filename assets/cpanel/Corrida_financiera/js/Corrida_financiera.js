$(document).ready(function(){
	listar();
	registrar_banco();
	actualizar_banco();


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
				"url": url + "Bancos/listado_bancos",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_banco",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data":"cod_banco"},
				{"data":"nombre_banco"},
				{"data":"fec_regins",
					render : function(data, type, row) {
						return cambiarFormatoFecha(data);
	          		}
				},
				{"data":"correo_usuario"},
				{"data": null,
					render : function(data, type, row) {
						var botones = "";
						if(consultar == 0)
							botones += "<span class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						if(actualizar == 0)
							botones += "<span class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						if(data.status == 1 && actualizar == 0)
							botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == 2 && actualizar == 0)
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

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro2 para mostrar el formulario de registrar.
	*/
	function nuevoRegistro(cuadroOcultar, cuadroMostrar){
		$("#alertas").css("display", "none");
		cuadros("#cuadro1", "#cuadro2");
		$("#form_corrida_registrar")[0].reset();
		$("#vendedor").val("").attr("disabled", "disabled");
		$("#inmobiliaria").val("").attr("disabled", "disabled");
		$("#cliente").val("").attr("disabled", "disabled");
		$("#etapas_proyecto").attr("disabled", "disabled");
		$("#zonas_proyecto").attr("disabled", "disabled");

		$(".monto_totals").val(number_format(0, 2));
	}
/* ------------------------------------------------------------------------------- */






/* ------------------------------------------------------------------------------- */
	/*
		ACCION PARA FILTRA LA CLASIFICACION DEL PROYECTO SEGUN EL PROYECTO SELECCIONADO
	*/

	$("#proyecto").on("change", function(){
		 var proyecto = $("#proyecto").val();

		$("#vendedor").val("").attr("disabled", "disabled");
		$("#inmobiliaria").val("").attr("disabled", "disabled");

		$('#zonas_proyecto').val("").attr("disabled", "disabled");
		$('#productos').val("").attr("disabled", "disabled");
		$('#lote_anterior').val("");
    	$('#lote').val("");
    	$('#status').val("");

    	$('#fecha_producto').val("");
    	$('#superficie_producto').val("");
    	$('#precio_m').val("");
    	$('#total_producto').val("");

    	$("#tableProductoRegistrar .tr_pro").remove();
    	  calcular_saldo();

    	  $("#saldo").val(0);
    	  $("#monto_totals").val(0);

		  buscar_cliente(proyecto);
		  buscar_etapas(proyecto, "#etapas_proyecto");
	});


	$("#cliente").on("change", function(){
        var proyecto = $("#proyecto").val();
        var cliente  = $("#cliente").val();
        buscar_vendedor(proyecto, cliente);
		
	});



	function buscar_etapas(proyecto, select, etapa_id = 0){
		if (proyecto != "") {
		 $.ajax({
            url: document.getElementById('ruta').value + 'proyectos/getclasificaciones/'+proyecto,
            type: 'POST',
            dataType:'JSON',
            cache: false,
			processData: false,
			contentType: false,
            beforeSend: function(){
            	$(select+' option').remove();
                $(select+'').append($('<option>',
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
                //console.log(respuesta);
                var clasificaciones = respuesta;
                $(select).removeAttr('disabled');
				$(select+' option').remove();
                if (clasificaciones.length == 0) {
                	$(select).append($('<option>',
				    {
				        value: "",
				        text : "No Aplica..."
				    }));
				    $(select).attr('disabled', 'disabled');
				    $(select).removeAttr('required');
				    //$("#precio_m2").removeAttr("disabled");
				    //$("#superficie_m2").removeAttr("disabled");
                }else{
                	$(select).attr('required', 'required');
                	//$("#precio_m2").attr("disabled", "disabled");
				    //$("#superficie_m2").attr("disabled", "disabled");
                	$(select).append($('<option>',
				    {
				        value: "",
				        text : "Seleccione..."
				    }));
				    $.each(clasificaciones, function(i, item){
		           		$(select).append($('<option>',
					     {
					        value: item.etapa,
					        text : item.etapa_nomb//+" --- "+number_format(item.precio,2)
					    }));
		           	});
                }

               
               // buscar_vendedor(proyecto);
                //buscar_inmobiliaria(proyecto);

                if (etapa_id != 0) {
                	$(select+" option[value='" + etapa_id + "']").prop("selected",true);
                	$(select+' option[value=""]').remove();
                }else{
                	 buscar_cliente(proyecto);
                }
	           
            }
        });	
	 }else{
	 	$(select+' option').remove();
	 	$(select).append($('<option>',
	    {
	        value: "",
	        text : "Seleccione..."
	    }));
	 }
	}

	function buscar_cliente(proyecto) {
		$.ajax({
            url: document.getElementById('ruta').value + 'proyectos/getclientesproyecto/'+proyecto,
            type: 'POST',
            dataType:'JSON',
            cache: false,
			processData: false,
			contentType: false,
            beforeSend: function(){
            	$('#cliente option').remove();
                $('#cliente').append($('<option>',
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
                //console.log(respuesta);
                var clientes = respuesta;
                $('#cliente').removeAttr('disabled');
				$('#cliente option').remove();
                if (clientes.length == 0) {
                	$('#cliente').append($('<option>',
				    {
				        value: "",
				        text : "No Aplica..."
				    }));
				    $('#cliente').attr('disabled', 'disabled');
				    $('#cliente').removeAttr('required');
				    //$("#precio_m2").removeAttr("disabled");
				    //$("#superficie_m2").removeAttr("disabled");
                }else{
                	$('#cliente').attr('required', 'required');
                	$('#cliente').append($('<option>',
				    {
				        value: "",
				        text : "Seleccione..."
				    }));
				    $.each(clientes, function(i, item){
		           		$('#cliente').append($('<option>',
					     {
					        value: item.id_cliente,
					        text : item.nombre + " " + item.p_apellido + " " + item.m_apellido//+" --- "+number_format(item.precio,2)
					    }));
		           	});
                }
	           
            }
        });
	}






	function buscar_vendedor(proyecto, cliente) {
		$.ajax({
            url: document.getElementById('ruta').value + 'proyectos/getvendedorproyecto/'+proyecto+'/'+cliente,
            type: 'POST',
            dataType:'JSON',
            cache: false,
			processData: false,
			contentType: false,
            beforeSend: function(){
            	$('#vendedor option').remove();
                $('#vendedor').append($('<option>',
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
                //console.log(respuesta);
                var vendedors = respuesta;
				$('#vendedor option').remove();
                if (vendedors.length == 0) {
                	$('#vendedor').append($('<option>',
				    {
				        value: "",
				        text : "No Aplica..."
				    }));
				    $('#vendedor').attr('disabled', 'disabled');
				    $('#vendedor').removeAttr('required');
				    //$("#precio_m2").removeAttr("disabled");
				    //$("#superficie_m2").removeAttr("disabled");
                }else{
                	$('#vendedor').attr('required', 'required');
				    $.each(vendedors, function(i, item){
				    	
		           		$('#vendedor').append($('<option>',
					     {
					        value: item.id_vendedor,
					        text : item.nombre + " " + item.p_apellido + " " + item.m_apellido//+" --- "+number_format(item.precio,2)
					    }));

					    $("#tipo_vendedor").val(item.tipo_vendedor);
		           	});
                }
                $("#plazo_saldo").removeAttr("disabled");
                var id_vendedor = $('#vendedor').val();
                buscar_inmobiliaria(id_vendedor, proyecto);
	           
            }
        });
	}







	function buscar_inmobiliaria(vendedor, proyecto) {
		$.ajax({
            url: document.getElementById('ruta').value + 'proyectos/getImbobiliariaVendedor/'+vendedor+'/'+proyecto,
            type: 'POST',
            dataType:'JSON',
            cache: false,
			processData: false,
			contentType: false,
            beforeSend: function(){
            	$('#inmobiliaria option').remove();
                $('#inmobiliaria').append($('<option>',
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
                //console.log(respuesta);
                var vendedors = respuesta;

				$('#inmobiliaria option').remove();
                if (vendedors.length == 0) {
                	$('#inmobiliaria').append($('<option>',
				    {
				        value: "",
				        text : "No Aplica..."
				    }));
				    $('#inmobiliaria').attr('disabled', 'disabled');
				    $('#inmobiliaria').removeAttr('required');
				    //$("#precio_m2").removeAttr("disabled");
				    //$("#superficie_m2").removeAttr("disabled");
                }else{
                	$('#inmobiliaria').attr('required', 'required');
				    $.each(vendedors, function(i, item){
		           		$('#inmobiliaria').append($('<option>',
					     {
					        value: item.id_inmobiliaria,
					        text : item.nombre
					    }));
		           	});
                }
	           
            }
        });
	}












	/* ------------------------------------------------------------------------------- */
	/*
		ACCION PARA FILTRA LOS DATOS DE LOS PRODUCTOS
	*/

	$("#productos").on("change", function(){
		 var producto = $("#productos").val();
		 $.ajax({
            url: document.getElementById('ruta').value + 'Productos/getproducto/'+producto,
            type: 'POST',
            dataType:'JSON',
            cache: false,
			processData: false,
			contentType: false,
            beforeSend: function(){
            	$('#lote_anterior').val("Espere por favor...");
            	$('#lote').val("Espere por favor...");
            	$('#status').val("Espere por favor...");

            	$('#fecha_producto').val("Espere por favor...");
            	$('#superficie_producto').val("Espere por favor...");
            	$('#precio_m').val("Espere por favor...");
            	$('#total_producto').val("Espere por favor...");

            	$("#btn-agregar").attr("disabled", "disabled");
                
            },
            error: function (repuesta) {
                
                var errores=repuesta.responseText;
                if(errores!="")
                    mensajes('danger', errores);
                else
                    mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
            },
            success: function(respuesta){
            	console.log(respuesta);
                $('#lote_anterior').val(respuesta[0].lote_anterior);
            	$('#lote').val(respuesta[0].lote_nuevo);
            	$('#status').val(respuesta[0].sts_producto);

            	$('#fecha_producto').val(respuesta[0].fec_regins);
            	$('#superficie_producto').val(respuesta[0].superficie);
            	$('#precio_m').val(number_format(respuesta[0].precio_m2, 2));
            	$('#total_producto').val(number_format(respuesta[0].precio_m2 * respuesta[0].superficie, 2));

            	$("#btn-agregar").removeAttr("disabled");
            }
        });
		 
	});












	$("#etapas_proyecto").on("change", function(){
		 var proyecto = $("#proyecto").val();
		 var etapa = $("#etapas_proyecto").val();

		$('#productos').val("").attr("disabled", "disabled");
		$('#lote_anterior').val("");
    	$('#lote').val("");
    	$('#status').val("");

    	$('#fecha_producto').val("");
    	$('#superficie_producto').val("");
    	$('#precio_m').val("");
    	$('#total_producto').val("");

    	buscar_zona("#zonas_proyecto", proyecto, etapa);
		 
	})





	$("#zonas_proyecto").on("change", function(){
		var proyecto = $("#proyecto").val();
		var etapas = $("#etapas_proyecto").val();
		var zonas  = $("#zonas_proyecto").val();

		$('#productos').val("");
		$('#lote_anterior').val("");
    	$('#lote').val("");
    	$('#status').val("");

    	$('#fecha_producto').val("");
    	$('#superficie_producto').val("");
    	$('#precio_m').val("");
    	$('#total_producto').val("");


    	buscar_producto('#productos', proyecto, etapas,  zonas);
		 
	})






	/*
		Funcion que agrega los productos  a la tabla
	*/
	var conta       = 1;
	var super_total = 0;
	function agregarProductos(){
		var idProducto     = $("#productos").val();
		var nombreProducto = $("#productos option:selected").html();

		var fecha_venta    = $("#fecha_producto").val();
		var fecha              = [];
	    fecha          = fecha_venta.split("-");
	    var fecha_venta    = fecha[2] + "/" + fecha[1] + "/" + fecha[0];


	    var etapa          = $("#etapas_proyecto option:selected").html();
	    var zona           = $("#zonas_proyecto option:selected").html();

	    var etapa_id       = $("#etapas_proyecto").val();

	    var zona_id        = $("#zonas_proyecto").val();

	    var lote_anterior  = $("#lote_anterior").val();

	    var lote           = $("#lote").val();

	    var superficie     = $("#superficie_producto").val();

	    var precio         = number_format($("#precio_m").val(), 2);

	    var monto_total    = number_format($("#total_producto").val(), 2);

		var validadoProducto = false;
		var html = '';
		if ( idProducto != "" ){
			$("#tableProductoRegistrar tbody tr").each(function() {
			  	if ((idProducto == $(this).find(".id_producto").val()))
			  		validadoProducto = true;
			});
			
			if (!validadoProducto) {
				html += "<tr class='tr_pro' id='ip" + conta + "'>";

				html += "<td><select class='select-edit-producto' disabled id='etapas_lista_"+conta+"'><option value='"+etapa_id+"'>"+ etapa + "</option></select></td>";

				html += "<td><select class='select-edit-producto zona_add' disabled id='zonas_lista_"+conta+"'><option value='"+zona_id+"'>" + zona + "</option></select></td>";


				html += "<td><select class='select-edit-producto id_producto' disabled id='producto_lista_"+conta+"'><option value='"+idProducto+"'>" + nombreProducto + "</option></select></td>>";

				html += "<td class='fecha_venta_"+conta+"'>" + fecha_venta + "</td>";

				html += "<td class='lote_anterior_"+conta+"'>" + lote_anterior + "</td>";

				html += "<td class='lote_"+conta+"'>" + lote + "<input type='hidden' class='lote_add' value='"+lote+"'></td>";


				html += "<td class='superficie_"+conta+"'>" + superficie + "<input type='hidden' class='superficie_add' value='"+superficie+"'></td>";

				html += "<td class='precio_"+conta+"'>" + precio + "<input type='hidden' class='precio_superficie_add' value='"+precio+"'></td>";

				html += "<td class='monto_total_"+conta+" monto_add'>" + monto_total + "<input type='hidden' class='monto_total_add' value='"+monto_total+"'></td>";

				html += "<td><span onclick='editar_producto(\"" + "#etapas_lista_" + conta + "\", "+conta+")' class='editar_detalle_producto btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span>  <button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar' onclick='eliminarTr(\"" + "#ip" + conta + "\", \""+monto_total+"\")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button> </td></tr>";
				$("#tableProductoRegistrar tbody").append(html);
				conta = conta + 1;

				var myNumeral_monto_total = numeral($("#monto_totals").val());
				var monto                 =  myNumeral_monto_total.value();


				var myNumeral_total       = numeral($("#total_producto").val());
				var total_producto        =  myNumeral_total.value();

				var total = total_producto + monto;


				$(".monto_totals").val(number_format(total, 2));

				

				$("#productos").val("");
				$("#lote_anterior").val("");
				$("#lote").val("");
				$("#superficie_producto").val("");
				$("#precio_m").val("");
				$("#total_producto").val("");
				$("#status").val("");
				$("#fecha_producto").val("");


				anticipo_m();
                
				calcular_saldo();

			} else {
				warning('¡No se puede vender el mismo producto dos veces.!');
			}
		} else {
			warning('¡Debe seleccionar una opción!');
		}
		
	}


function editar_producto(element, conta){
	var etapa_id = $(element).val();
	$(element).removeAttr("disabled").focus();
	var proyecto = $("#proyecto").val();
	buscar_etapas(proyecto, element, etapa_id);

	buscar_zona_edit(element, conta);
}

function buscar_zona_edit(select_etapa, conta){
	var proyecto     = $("#proyecto").val();
	var select_zona  = "#zonas_lista_"+conta;

	$(select_etapa).on("change", function(){
		var etapa_id = $(select_etapa).val();
		buscar_zona(select_zona, proyecto, etapa_id);

		buscar_producto_edit(select_zona, etapa_id,  conta);
	});
}


function buscar_producto_edit(select_zona, etapa_id,  conta){
	var proyecto         = $("#proyecto").val();
	var select_producto  = "#producto_lista_"+conta;

	$(select_zona).on("change", function(){
		var zona_id = $(select_zona).val();
		buscar_producto(select_producto, proyecto, etapa_id,  zona_id);

		var fecha_venta   = ".fecha_venta_"+conta;
		var lote_anterior = ".lote_anterior_"+conta;
		var lote          = ".lote_"+conta;
		var superficie    = ".superficie_"+conta;
		var precio        = ".precio_"+conta;
		var monto_total   = ".monto_total_"+conta;

		editar_productos_select(select_producto, fecha_venta, lote_anterior, lote, superficie, precio, monto_total);
	});
}



function editar_productos_select(select_producto, fecha_venta, lote_anterior, lote, superficie, precio, monto_total){
	$(select_producto).on("change", function(){
		var producto = $(select_producto).val();

		var validadoProductoSelelect = false;
		$(select_producto).removeClass("id_producto");
		$("#tableProductoRegistrar tbody tr").each(function() {
			if ((producto == $(this).find(".id_producto").val()))
			  		validadoProductoSelelect = true;
		});

		if (!validadoProductoSelelect){
			$.ajax({
	            url: document.getElementById('ruta').value + 'Productos/getproducto/'+producto,
	            type: 'POST',
	            dataType:'JSON',
	            cache: false,
				processData: false,
				contentType: false,
	            beforeSend: function(){
	            	$(fecha_venta).text("Espere por favor...");
	            	$(lote_anterior).text("Espere por favor...");

	            	$(lote).text("Espere por favor...");
	            	$(superficie).text("Espere por favor...");
	            	$(precio).text("Espere por favor...");
	            	$(monto_total).text("Espere por favor...");

	            	$("#btn-agregar").attr("disabled", "disabled");
	                
	            },
	            error: function (repuesta) {
	                
	                var errores=repuesta.responseText;
	                if(errores!="")
	                    mensajes('danger', errores);
	                else
	                    mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
	            },
	            success: function(respuesta){
	            	//console.log(respuesta);

	            	var fecha_venta_2    = respuesta[0].fec_regins;
					var fecha            = [];
				        fecha            = fecha_venta_2.split("-");
				    var fecha_venta_2    = fecha[2] + "/" + fecha[1] + "/" + fecha[0];


	                $(lote_anterior).text(respuesta[0].lote_anterior);


	            	$(lote).text(respuesta[0].lote_nuevo);
	            	$(lote).append("<input type='hidden' class='lote_add' value='"+respuesta[0].lote_nuevo+"'>");
	            	
	            	

	            	$(fecha_venta).text(fecha_venta_2);

	            	$(superficie).text(respuesta[0].superficie);
	            	$(superficie).append("<input type='hidden' class='superficie_add' value='"+respuesta[0].superficie+"'>");
	            	

	            	$(precio).text(number_format(respuesta[0].precio_m2, 2));
	            	$(precio).append("<input type='hidden' class='precio_superficie_add' value='"+respuesta[0].precio_m2+"'>");


	            	$(monto_total).text(number_format(respuesta[0].precio_m2 * respuesta[0].superficie, 2));
	            	$(monto_total).append("<input type='hidden' class='monto_total_add' value='"+number_format(respuesta[0].precio_m2 * respuesta[0].superficie, 2)+"'>");


	            	$(".select-edit-producto").attr("disabled", "disabled");

	            	$("#btn-agregar").removeAttr("disabled");

	            	$(select_producto).addClass("id_producto");


	            	calcular_monto_total();
	            	calcular_saldo();


	            }
	        });
		}else {
			warning('¡No se puede vender el mismo producto dos veces.!');
		}
		
	});
}



function buscar_zona(select, proyecto, etapa){
	if (etapa != "") {
		 //sumar();
		 $.ajax({
            url: document.getElementById('ruta').value + 'proyectos/getclasificacionesEtapas/'+proyecto+'/'+etapa,
            type: 'POST',
            dataType:'JSON',
            cache: false,
			processData: false,
			contentType: false,
            beforeSend: function(){
            	$(select+' option').remove();
                $(select).append($('<option>',
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
                $(select).removeAttr('disabled');
				$(select+' option').remove();
                if (clasificaciones.length == 0) {
                	$('#etapas_proyecto').append($('<option>',
				    {
				        value: "",
				        text : "No Aplica..."
				    }));
				    $(select).attr('disabled', 'disabled');
				    $(select).removeAttr('required');
				    $("#precio_m2").removeAttr("disabled");
				    $("#superficie_m2").removeAttr("disabled");
                }else{
                	$(select).attr('required', 'required');
                	$("#precio_m2").attr("disabled", "disabled");
				    $("#superficie_m2").attr("disabled", "disabled");
                	$(select).append($('<option>',
				    {
				        value: "",
				        text : "Seleccione..."
				    }));
				    $.each(clasificaciones, function(i, item){
		           		$(select).append($('<option>',
					     {
					        value: item.id_proyecto_clasificacion,
					        text : item.nombre_lista_valor
					    }));
		           	});
                }
	           
            }
        });	
	 }else{
	 	$(select+' option').remove();
	 	$(select).append($('<option>',
	    {
	        value: "",
	        text : "Seleccione..."
	    }));
	 }
}


function buscar_producto(select_producto, proyecto, etapas,  zonas){
	if (etapas != "") {
		 //sumar();
		 $.ajax({
            url: document.getElementById('ruta').value + 'productos/getproductosCorrida/'+proyecto+'/'+etapas+'/'+zonas,
            type: 'POST',
            dataType:'JSON',
            cache: false,
			processData: false,
			contentType: false,
            beforeSend: function(){
            	$(select_producto +' option').remove();
                $(select_producto).append($('<option>',
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
                $(select_producto).removeAttr('disabled');
				$(select_producto +' option').remove();
                if (clasificaciones.length == 0) {
                	$('#etapas_proyecto').append($('<option>',
				    {
				        value: "",
				        text : "No Aplica..."
				    }));
				    $(select_producto).attr('disabled', 'disabled');
				    $(select_producto).removeAttr('required');
                }else{
                	$(select_producto).attr('required', 'required');
                	$(select_producto).append($('<option>',
				    {
				        value: "",
				        text : "Seleccione..."
				    }));
				    $.each(clasificaciones, function(i, item){
		           		$(select_producto).append($('<option>',
					     {
					        value: item.id_producto,
					        text : item.descripcion
					    }));
		           	});
                }
	           
            }
        });	
	 }else{
	 	$(select_producto +' option').remove();
	 	$(select_producto).append($('<option>',
	    {
	        value: "",
	        text : "Seleccione..."
	    }));
	 }
}



$("#plazo_saldo").on("change", function(){
	var tipo_plazo    = $(this).val();
	var tipo_vendedor = $("#tipo_vendedor").val();
	var proyecto      = $("#proyecto").val();

	if ($("#plazo_saldo option:selected").text() != "CONTADO") {
		$("#tipo_cuota").removeAttr("disabled");
	}else{
		$("#tipo_cuota").attr("disabled","disabled");
	}

	
	$("#descuento_select").val(0);
	calcular_saldo();


	$.ajax({
        url: document.getElementById('ruta').value + 'Descuento/getdescuentoCorrida/'+tipo_plazo+'/'+tipo_vendedor+'/'+proyecto,
        type: 'POST',
        dataType:'JSON',
        beforeSend: function(){
        	$('#descuento option').remove();
            $("#descuento").append($('<option>',
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
            $("#descuento").removeAttr('disabled');
            $("#forma_pago").removeAttr('disabled');
			$('#descuento option').remove();
            if (clasificaciones.length == 0) {
            	$('#etapas_proyecto').append($('<option>',
			    {
			        value: "",
			        text : "No Aplica..."
			    }));
			    $("#descuento").attr('disabled', 'disabled');
			    $("#descuento").removeAttr('required');
            }else{
            	$("#descuento").attr('required', 'required');
            	$("#descuento").append($('<option>',
			    {
			        value: "",
			        text : "Seleccione..."
			    }));
			    $.each(clasificaciones, function(i, item){
	           		$("#descuento").append($('<option>',
				     {
				        value: item.id_descuento,
				        text : item.esquema+" - "+item.descuento+"%"
				    }));
	           	});
            }


            buscar_recargo(tipo_plazo, tipo_vendedor, proyecto);

            
           
        }
    });	
});




function buscar_recargo(tipo_plazo, tipo_vendedor, proyecto){
	$.ajax({
        url: document.getElementById('ruta').value + 'Recargas/getRecargosCorrida/'+tipo_plazo+'/'+tipo_vendedor+'/'+proyecto,
        type: 'POST',
        dataType:'JSON',
        beforeSend: function(){
            $("#recargo").val("Espere por favor");
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
            
            $("#recargo").removeAttr('disabled');
            if (clasificaciones.length == 0) {
			    $("#recargo").attr('disabled', 'disabled');
			    $("#recargo").removeAttr('required');
			    monto_recargo(0);
            }else{
            	$("#recargo").attr('required', 'required');
            	monto_recargo(clasificaciones[0].recarga);
			    
            }


            var plazo = $("#plazo_saldo option:selected").text()
            calcular_mensualidad(plazo);
           
        }
    });	
}

$("#descuento").on("change", function(){
	var descuento   = $("#descuento option:selected").text();

	var myNumeral_monto_total = numeral($("#monto_totals").val());
	var monto_total           =  myNumeral_monto_total.value();
	
	var array      = descuento.split(" - ");
	var porcentaje = array[1];

	var myNumeral_porcentaje = numeral(porcentaje);
	var porcentaje_monto     =  myNumeral_porcentaje.value();

	var descuento_monto = ((monto_total / 100) * porcentaje_monto);
	$("#descuento_select").val(number_format(descuento_monto.toFixed(2), 2));

	calcular_saldo();
});




$("#forma_pago").on("change", function(){
	var forma = $("#forma_pago option:selected").text();
	if (forma == "MENSUAL") {
		var div = 1;
	}else if(forma == "BIMENSUAL"){
		var div = 2;
	}else if(forma == "TRIMESTRAL"){
		var div = 3;
	}else if(forma == "CUATRIMENSUAL"){
		var div = 4;
	}

	var plazo  = $("#plazo_saldo option:selected").text();

	var myNumeral_meses  = numeral(plazo);
	var meses            =  myNumeral_meses.value();



	var myNumeral_saldo = numeral($("#saldo").val());
	var saldo           = myNumeral_saldo.value();


	var cuotas       = meses / div;
	var cuotas_monto = saldo / cuotas;
	$("#cuotas").val(cuotas);
	$("#monto_cuotas").val(number_format(cuotas_monto, 2));

	calcular_totales();
});




function monto_recargo(porcentaje){
	 console.log(porcentaje);
	var myNumeral_monto_total = numeral($("#monto_totals").val());
	var monto_total           =  myNumeral_monto_total.value();

	var myNumeral_porcentaje = numeral(porcentaje);
	var porcentaje_monto     =  myNumeral_porcentaje.value();
	

	var recargo_monto = ((monto_total / 100) * porcentaje_monto);
	$("#recargo").val(number_format(recargo_monto.toFixed(2), 2));

	calcular_saldo();
}



// $(".editar_detalle_producto").on("click", function(){
// 	alert("asdasd");
// 	var conte = $(this).parents("td");

// 	console.log(conte);
// });

function eliminarTr(tr, monto_delete){
	$(tr).remove(); 

	var myNumeral_monto_total = numeral($("#monto_totals").val());
	var monto                 =  myNumeral_monto_total.value();


	var myNumeral_total       = numeral(monto_delete);
	var total_producto        =  myNumeral_total.value();

	var total = total_producto - monto;

	$(".monto_totals").val(number_format(total, 2));


	calcular_saldo();
	anticipo_m();
    
}
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_banco(){
		enviarFormulario("#form_banco_registrar", 'Bancos/registrar_banco', '#cuadro2');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta del banco.
	*/
	function ver(tbody, table){
		$(tbody).on("click", "span.consultar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('cod_banco_consultar').value=data.cod_banco;
			document.getElementById('nombre_banco_consultar').value=data.nombre_banco;
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$("#form_banco_actualizar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('id_banco_editar').value=data.id_banco;
			document.getElementById('cod_banco_editar').value=data.cod_banco;
			document.getElementById('nombre_banco_editar').value=data.nombre_banco;
			cuadros('#cuadro1', '#cuadro4');
			$("#nombre_banco_editar").focus();
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_banco(){
		enviarFormulario("#form_banco_actualizar", 'Bancos/actualizar_banco', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('Bancos/eliminar_banco', data.id_banco, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('Bancos/status_banco', data.id_banco, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Bancos/status_banco', data.id_banco, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */









function number_format(amount, decimals) {   
	 amount += ''; // por si pasan un numero en vez de un string
	 amount = parseFloat(amount.replace(/[^0-9\.]/g, ''));
	 // elimino cualquier cosa que no sea numero o punto 
	  decimals = decimals || 0; // por si la variable no fue fue pasada  
	  // si no es un numero o es igual a cero retorno el mismo cero 
	  if (isNaN(amount) || amount === 0)      
	     return parseFloat(0).toFixed(decimals);     
	      // si es mayor o menor que cero retorno el valor formateado como numero   
	    amount = '' + amount.toFixed(decimals);   
	    var amount_parts = amount.split('.'),    
	    regexp = /(\d+)(\d{3})/;       
	      while (regexp.test(amount_parts[0]))  
	      amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2'); 
	       return amount_parts.join('.');  
} 


function number_format_normal(amount, decimals) {   
 amount += ''; // por si pasan un numero en vez de un string
 amount = parseFloat(amount.replace(/[^0-9\.]/g, ''));
 // elimino cualquier cosa que no sea numero o punto 
  decimals = decimals || 0; // por si la variable no fue fue pasada  
  // si no es un numero o es igual a cero retorno el mismo cero 
  if (isNaN(amount) || amount === 0)      
     return parseFloat(0).toFixed(decimals);     
      // si es mayor o menor que cero retorno el valor formateado como numero   
    amount = '' + amount.toFixed(decimals);   
    var amount_parts = amount.split('.'),    
    regexp = /(\d+)(\d{0})/;       
      while (regexp.test(amount_parts[0]))  
      //amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2'); 
       return amount_parts.join('.');  
   }



$(".monto_formato_decimales").change(function() {   
    if($(this).val() != ""){  
        $(this).val(number_format($(this).val(), 2));   
    }       
});




$("#anticipo_m").on("keyup", function(){
	calcular_anticipo_m();

});



$("#anticipo_p").on("keyup", function(){
	calcular_anticipo_p();

});


$("#anticipo_p").on("change", function(){
	calcular_anticipo_p();

	var myNumeral_valor = numeral($(this).val());
	var valor           = myNumeral_valor.value();
	$(this).val(valor+"%");

});



function calcular_anticipo_m(){
	var myNumeral_anticipo_m  = numeral($("#anticipo_m").val());
	var myNumeral_monto_total = numeral($("#monto_totals").val());

	var anticipo_m            =  myNumeral_anticipo_m.value();

	var monto_total           =  myNumeral_monto_total.value();


	var porcentaje = ((anticipo_m * 100) / monto_total); 
	$("#anticipo_p").val(porcentaje.toFixed(2)+"%");

	calcular_saldo();


}


function calcular_anticipo_p(){
	var myNumeral_anticipo_p  = numeral($("#anticipo_p").val());
	var myNumeral_monto_total = numeral($("#monto_totals").val());

	var anticipo_p            =  myNumeral_anticipo_p.value();

	var monto_total           =  myNumeral_monto_total.value();

	var monto_anticipo = ((monto_total / 100) * anticipo_p);


	$("#anticipo_m").val(number_format(monto_anticipo.toFixed(2), 2));

	calcular_saldo();
}



function anticipo_m() {
	var myNumeral_anticipo_m  = numeral($("#anticipo_m").val());
	var myNumeral_monto_total = numeral($("#monto_totals").val());

	var anticipo_m            =  myNumeral_anticipo_m.value();

	var monto_total           =  myNumeral_monto_total.value();


	var porcentaje = ((anticipo_m * 100) / monto_total); 
	$("#anticipo_p").val(porcentaje.toFixed(2)+"%");
}


function anticipo_p() {
	var myNumeral_anticipo_p  = numeral($("#anticipo_p").val());
	var myNumeral_monto_total = numeral($("#monto_totals").val());

	var anticipo_p            =  myNumeral_anticipo_p.value();

	var monto_total           =  myNumeral_monto_total.value();

	var monto_anticipo = ((monto_total / 100) * anticipo_p);


	$("#anticipo_m").val(number_format(monto_anticipo.toFixed(2), 2));
}




function calcular_saldo(){

	$("#forma_pago").val("");
	$("#cuotas").val("");
	$("#monto_cuotas").val("");

	var myNumeral_anticipo_m  = numeral($("#anticipo_m").val());
	var myNumeral_descuento   = numeral($("#descuento_select").val());
	var myNumeral_recargo     = numeral($("#recargo").val());
	
	var myNumeral_monto_total = numeral($("#monto_totals").val());


	var anticipo_m            =  myNumeral_anticipo_m.value();
	var descuento             =  myNumeral_descuento.value();
	var recargo               =  myNumeral_recargo.value();
	var monto_total           =  myNumeral_monto_total.value();


	var saldo  = ((monto_total - descuento) + recargo) - anticipo_m;
	
	$("#saldo").val(number_format(saldo.toFixed(2), 2));


	var plazo = $("#plazo_saldo option:selected").text()
	calcular_mensualidad(plazo);
	calcular_totales();
}



function calcular_mensualidad(plazo){
	var myNumeral_saldo = numeral($("#saldo").val());
	var saldo           = myNumeral_saldo.value();
	if (plazo == "CONTADO") {
		var mensualidad = saldo;
		$("#forma_pago").val("").attr("disabled", "disabled");
		$("#cuotas").val("");
		$("#monto_cuotas").val("");
	}else{
		var myNumeral_meses  = numeral(plazo);
		var meses       =  myNumeral_meses.value();
		var mensualidad = (saldo / meses);
	}



	$("#mensualidad").val(number_format(mensualidad, 2));

}



$("#tipo_cuota").on("change", function(){
	var tipo = $("#tipo_cuota option:selected").text()
	
	if (tipo == "MENSUAL") {
		$("#mes_cuota").removeAttr("disabled");
		$("#mes_cuota").attr("required", "required").css("opacity", "1");
	}else{
		$("#mes_cuota").attr("disabled", "disabled");
		$("#mes_cuota").removeAttr("required").css("opacity", ".5");
		$("#mes_cuota").val("");
	}
});




/*
		Funcion que agrega los productos  a la tabla
	*/
	var cantidad = 1;
	function agregar_cuotas(){
		var idTipo     = $("#tipo_cuota").val();
		var tipo       = $("#tipo_cuota option:selected").text();

		var idMes     = $("#mes_cuota").val();
		if (tipo == "FINAL") {
			var mes = "N/A";
		}else{
			var mes       = $("#mes_cuota option:selected").text();
		}
		

	  
	    var monto     = number_format($("#monto_cuotas_extra").val(), 2);

		var validadoTipoCuota = false;
		var validadoMesCuota  = false;
		var html = '';
		if ( idTipo != "" && monto != 0){
			if (tipo == "MENSUAL") {
				if (idMes == "") {
					warning('¡Tienes que seleccionar 1 mes.!');
					return;
				}
			}
			$("#tableCoutasRegistrar tbody tr").each(function() {
			  	if ((tipo == "FINAL") && ($(this).find(".name_cuota").text() == tipo))
			  		validadoTipoCuota = true;
			});


			$("#tableCoutasRegistrar tbody tr").each(function() {
			  	if ((tipo == "MENSUAL") && ($(this).find(".name_mes").text() == mes))
			  		validadoMesCuota = true;
			});


			
			if ((!validadoTipoCuota)) {
				if (!validadoMesCuota) {
					html += "<tr id='cuota" + cantidad + "'>";

					html += "<td> <span class='name_cuota'>" +tipo+ "</span><input type='hidden' class='type_cuota' value="+idTipo+"></td>";

					html += "<td><span class='name_mes'>" +mes+ "</span></td>";

					html += "<td>" +monto+ "</td>";

					html += "<td><span onclick='editar_producto(\"" + "#etapas_lista_" + cantidad + "\", "+cantidad+")' class='editar_detalle_producto btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span>  <button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar' onclick='eliminarTr(\"" + "#cuota" + cantidad + "\", \""+monto+"\")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button> </td></tr>";
					$("#tableCoutasRegistrar tbody").append(html);
					cantidad = cantidad + 1;


					total_cuota_extraordinaria(tipo, monto, idMes);
					re_calcular_saldo();


				}else{
					warning('¡Solo puedes agregar una cuoto por mes.!');
				}

			}else {
				warning('¡Cuota final ya esta registrada.!');
			}
		} else {
			warning('¡Recuerden llenar todos los campos!');
		}
	}




	var out = 0;
	function intp(data) {
		out = data;
	}

	function outsss() {
		return out;
	}



	var total_cuota = 0;
	function total_cuota_extraordinaria(tipo, monto, mes){
		var myNumeral_monto  = numeral(monto);
		var monto_cuota      =  myNumeral_monto.value();
		if (tipo == "FINAL") {
			total_cuota  = total_cuota + monto_cuota;
		}else{
			var plazo = $("#plazo_saldo option:selected").text();
			var myNumeral_meses  = numeral(plazo);
		    var meses            =  myNumeral_meses.value();
		   // var monto_mes = getCuoutasmes(meses, mes, monto_cuota);

		    var monto_mes = getCuoutasmes(meses, mes, monto_cuota);
		    total_cuota  = total_cuota + monto_mes;
		    
		}

		$("#total_cuota_extraordinaria").val(total_cuota);
		console.log(total_cuota);
	}

	function getCuoutasmes(plazo, mes, monto) {
		var d = new Date();

        var month  = d.getMonth()+1;
        var years  = plazo / 12;

        var monto_total = 0;
		var indicador   = 0;

		for (var i = 0; i <= years; i++) {
			indicador = indicador + 1;
			if (indicador == 1) {
				if (month < mes) {
					monto_total = monto_total + monto;
				}
				continue;
			}
			if (indicador == years) {
				if (month >= mes) {
					monto_total = monto_total + monto;
				}
				continue;
			}
			monto_total = monto_total + monto;
		
		}

        return monto_total;

		// return $.ajax({
	 //        url: document.getElementById('ruta').value + 'Corrida/getCuoutasmes/'+plazo+'/'+mes+'/'+monto,
	 //        type: 'POST',
	 //        dataType:'JSON',
	 //        beforeSend: function(){
	 //        },
	 //        error: function (repuesta) {
	 //        },
	 //        success: function(respuesta){
	 //           var monto_mes = respuesta;
	 //        }
	 //    });	
	   
	}



	function re_calcular_saldo(){

		var myNumeral_anticipo_m  = numeral($("#anticipo_m").val());
		var myNumeral_descuento   = numeral($("#descuento_select").val());
		var myNumeral_recargo     = numeral($("#recargo").val());
		
		var myNumeral_monto_total          = numeral($("#monto_totals").val());
		var myNumeral_cuota_extraordinaria = numeral($("#total_cuota_extraordinaria").val());


		var anticipo_m            =  myNumeral_anticipo_m.value();
		var descuento             =  myNumeral_descuento.value();
		var recargo               =  myNumeral_recargo.value();
		var monto_total           =  myNumeral_monto_total.value();
		var cuota_extraordinaria  =  myNumeral_cuota_extraordinaria.value();


		var saldo  = (((monto_total - descuento) + recargo) - anticipo_m) - cuota_extraordinaria;
		
		$("#saldo").val(number_format(saldo.toFixed(2), 2));


		var plazo = $("#plazo_saldo option:selected").text()
	}




	function calcular_monto_total() {
		var total_monto_total       = 0;
		$("#tableProductoRegistrar tbody tr").each(function() {
		  	monto_total = $(this).find(".monto_total_add").val();
		  	var myNumeral_monto_total  = numeral(monto_total);
			var monto_total            =  myNumeral_monto_total.value();
		  	total_monto_total          = (parseFloat(total_monto_total) + parseFloat(monto_total)).toFixed(2);
		  	
		  
		});

		$("#monto_totals").val(number_format(total_monto_total, 2))
	}



	function calcular_totales() {
		var cant       = 0;
		var superficie = 0;

		var total_superficie        = 0;
		var total_precio_superficie = 0;
		var total_monto_total       = 0;
		$("#tableProductoRegistrar tbody tr").each(function() {
		  	cant = cant + 1;

		  	if (cant == 1) {
		  		var zona_total = $(this).find(".zona_add option:selected").text() + " / " + $(this).find(".lote_add").val();
		  		$("#zona_total").text(zona_total);
		  	}else{
		  		$("#zona_total").text("Zona / Lote");
		  	}
		  	superficie = $(this).find(".superficie_add").val();
		  	total_superficie = (parseFloat(total_superficie) + parseFloat(superficie)).toFixed(2);

		  	precio_superficie = $(this).find(".precio_superficie_add").val();
		  	var myNumeral_precio_superficie  = numeral(precio_superficie);
			var precio_superficie            =  myNumeral_precio_superficie.value();
		  	total_precio_superficie          = (parseFloat(total_precio_superficie) + parseFloat(precio_superficie)).toFixed(2);


		  	monto_total = $(this).find(".monto_total_add").val();
		  	var myNumeral_monto_total  = numeral(monto_total);
			var monto_total            =  myNumeral_monto_total.value();
		  	total_monto_total          = (parseFloat(total_monto_total) + parseFloat(monto_total)).toFixed(2);
		  	
		  	//console.log(total_superficie);
		});


	  	var myNumeral_anticipo_total     =  numeral($("#anticipo_m").val());
		var anticipo_total               =  myNumeral_anticipo_total.value();

		var myNumeral_saldo_total        =  numeral($("#saldo").val());
		var saldo_total                  =  myNumeral_saldo_total.value();



		var myNumeral_mensualidad_total  =  numeral($("#mensualidad").val());
		var mensualidad_total            =  myNumeral_mensualidad_total.value();


		var myNumeral_monto_cuotas_total  =  numeral($("#monto_cuotas").val());
		var monto_cuotas_total            =  myNumeral_monto_cuotas_total.value();


		$("#cantidad").text(cant);
		//$("#m2_total").text(number_format_normal(total_superficie, 2));
		$("#m2_total").text(total_superficie);
		$("#precio_m2_total").text(number_format(total_precio_superficie, 2));
		$("#monto_total_total").text(number_format(total_monto_total, 2));
		$("#anticipo_total").text(number_format(anticipo_total, 2));
		$("#saldo_total").text(number_format(saldo_total, 2));
		$("#mensualidad_total").text(number_format(mensualidad_total, 2));


		var fp = $("#forma_pago option:selected").text();
		if (fp == "Seleccione") {
			var fp = "";
		}
		$("#fp").text(fp);

		$("#monto_cuotas_total").text(number_format(monto_cuotas_total, 2));
		
	}
