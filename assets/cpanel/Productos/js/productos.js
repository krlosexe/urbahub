$(document).ready(function(){
    listar();
	registrar_producto();
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
				"url": url + "productos/listado_productos",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_producto",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data":"id_producto"},
				{"data":"descripcion"},
				{"data":"name_proyecto"},
				{"data":"nombre_clasificacion",
					render : function(data, type, row) {
						if (!data) {
							return "No Aplica";
						}else{
							return data;
						}
						
						
	          		}},
				{"data":"lote_anterior"},
				{"data":"lote_nuevo"},
				{"data":"superficie",
					render : function(data, type, row) {
						return number_format_normal(data, 2);
						
	          		}
			    },
				{"data":"precio_m2",
					render : function(data, type, row) {
						return number_format(data, 2);
						
	          		}
				},
				{"data":"sts_producto"},
				{"data":"correo_usuario"},
				{"data":"fec_regins",
					render : function(data, type, row) {
						return cambiarFormatoFecha(data);
						//number_format(total, 2)
	          		}
				},
				{"data": null,
					render : function(data, type, row) {
						var botones = "";
						if(consultar == 0)
							botones += "<span class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						if(actualizar == 0)
							if (data.sts_producto == "PENDIENTE") {
								botones += "<span class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
							}
							
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
	function nuevoVendedor(cuadroOcultar, cuadroMostrar){
		$("#alertas").css("display", "none");
		$('#plano_registrar').fileinput('destroy')
		cuadros("#cuadro1", "#cuadro2");
		$("#alertas").css("display", "none");
		$("#form_productos_registrar").trigger("reset");
		$("#tableInmobiliariaRegistrar tbody tr").remove();
		$("#tableClasificacionRegistrar tbody tr").remove();

		$("#etapas_proyecto").attr("disabled", "disabled");
		$("#clasificacion_proyecto").attr("disabled", "disabled");
		$('#plano_registrar').fileinput({
        theme: 'fa',
        language: 'es',	

        uploadAsync: true,
        showUpload: false, // hide upload button
        showRemove: false,
        uploadUrl: base_url+'uploads/upload/productos',
        uploadExtraData:{
        	name:$('#plano_registrar').attr('id')
        },
        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
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
      
      //console.log(data.response)
    }); 
	}


/* ------------------------------------------------------------------------------- */
	/*
		ACCION PARA FILTRA LA CLASIFICACION DEL PROYECTO SEGUN EL PROYECTO SELECCIONADO
	*/

	$("#proyecto").on("change", function(){
		 var proyecto = $("#proyecto").val();
		 	if (proyecto != "") {
		 	$("#precio_m2").val("");
			 $.ajax({
	            url: document.getElementById('ruta').value + 'proyectos/getclasificaciones/'+proyecto,
	            type: 'POST',
	            dataType:'JSON',
	            cache: false,
				processData: false,
				contentType: false,
	            beforeSend: function(){
	            	$('.etapa_pp option').remove();
	                $('.etapa_pp').append($('<option>',
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
	                $('#etapas_proyecto').removeAttr('disabled');
					$('#etapas_proyecto option').remove();
	                if (clasificaciones.length == 0) {
	                	$('#etapas_proyecto').append($('<option>',
					    {
					        value: "",
					        text : "No Aplica..."
					    }));
					    $('#etapas_proyecto').attr('disabled', 'disabled');
					    $('#etapas_proyecto').removeAttr('required');
					    //$("#precio_m2").removeAttr("disabled");
					    //$("#superficie_m2").removeAttr("disabled");
	                }else{
	                	$('#etapas_proyecto').attr('required', 'required');
	                	//$("#precio_m2").attr("disabled", "disabled");
					    //$("#superficie_m2").attr("disabled", "disabled");
	                	$('#etapas_proyecto').append($('<option>',
					    {
					        value: "",
					        text : "Seleccione..."
					    }));
					    $.each(clasificaciones, function(i, item){
			           		$('#etapas_proyecto').append($('<option>',
						     {
						        value: item.etapa,
						        text : item.etapa_nomb//+" --- "+number_format(item.precio,2)
						    }));
			           	});
	                }
		           
	            }
	        });	
		 }else{
		 	$('#etapas_proyecto option').remove();
		 	$('#etapas_proyecto').append($('<option>',
		    {
		        value: "",
		        text : "Seleccione..."
		    }));
		 }
		 
	})
	$("#etapas_proyecto").on("change", function(){
		 var proyecto = $("#proyecto").val();
		 var etapa = $("#etapas_proyecto").val();

		 if (etapa != "") {
		 	$("#precio_m2").val("");
			 //$("#superficie_m2").val("");
			 sumar();
			 $.ajax({
	            url: document.getElementById('ruta').value + 'proyectos/getclasificacionesEtapas/'+proyecto+'/'+etapa,
	            type: 'POST',
	            dataType:'JSON',
	            cache: false,
				processData: false,
				contentType: false,
	            beforeSend: function(){
	            	$('#clasificacion_proyecto option').remove();
	                $('#clasificacion_proyecto').append($('<option>',
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
	                $('#clasificacion_proyecto').removeAttr('disabled');
					$('#clasificacion_proyecto option').remove();
	                if (clasificaciones.length == 0) {
	                	$('#etapas_proyecto').append($('<option>',
					    {
					        value: "",
					        text : "No Aplica..."
					    }));
					    $('#clasificacion_proyecto').attr('disabled', 'disabled');
					    $('#clasificacion_proyecto').removeAttr('required');
					    $("#precio_m2").removeAttr("disabled");
					    $("#superficie_m2").removeAttr("disabled");
	                }else{
	                	$('#clasificacion_proyecto').attr('required', 'required');
	                	$("#precio_m2").attr("disabled", "disabled");
					    $("#superficie_m2").attr("disabled", "disabled");
	                	$('#clasificacion_proyecto').append($('<option>',
					    {
					        value: "",
					        text : "Seleccione..."
					    }));
					    $.each(clasificaciones, function(i, item){
			           		$('#clasificacion_proyecto').append($('<option>',
						     {
						        value: item.id_proyecto_clasificacion,
						        text : item.nombre_lista_valor+" --- "+number_format(item.precio,2)
						    }));
			           	});
	                }
		           
	            }
	        });	
		 }else{
		 	$('#clasificacion_proyecto option').remove();
		 	$('#clasificacion_proyecto').append($('<option>',
		    {
		        value: "",
		        text : "Seleccione..."
		    }));
		 }
		 
	})



	$("#clasificacion_proyecto").on("change", function(){

		var val = $("#clasificacion_proyecto option:selected").text();
		var res = val.split(" --- ");

		var precio_m2 = res[1];

		$("#superficie_m2").removeAttr("disabled");
		$("#precio_m2").attr("disabled", "disabled");
		$("#precio_m2").val(number_format(precio_m2,2));
		sumar();
	})


	$("#precio_m2").on("keyup", function(){

		var myNumeral_prec = numeral($("#precio_m2").val());
		var value_prec     = myNumeral_prec.value();
		if (value_prec>99999.99) {
        	console.log("HEEEYYY");
        	//var borra = superficie.toString().substr(-1);

        	var cant = $("#precio_m2").val().toString().length;

        	//console.log($("#superficie_m2").val().toString().substring(0,5));

        	$("#precio_m2").val($("#precio_m2").val().toString().substring(0,5));
        }

		sumar();
	});

	$("#superficie_m2").on("keyup", function(){
		var myNumeral_super = numeral($("#superficie_m2").val());
		var value           = myNumeral_super.value();
		if (value>99999.99) {
        	console.log("HEEEYYY");
        	//var borra = superficie.toString().substr(-1);

        	var cant = $("#superficie_m2").val().toString().length;

        	//console.log($("#superficie_m2").val().toString().substring(0,5));

        	$("#superficie_m2").val($("#superficie_m2").val().toString().substring(0,5));
        }

		sumar();
	});


	 $(".monto_formato_decimales").change(function() {   
	       if($(this).val() != ""){  
	        $(this).val(number_format($(this).val(), 2));   

	        sumar();
	        sumar_edit();
	    }       
	    });


	 $(".monto_formato_decimales_normal").change(function() {   
	       if($(this).val() != ""){  
	        $(this).val(number_format_normal($(this).val(), 2));   

	        sumar();
	        sumar_edit();
	    }       
	    });


	function sumar(){
       // var precio     = Number($("#precio_m2").val());
        myNumeral_precio = numeral($("#precio_m2").val());
        var value_precio = myNumeral_precio.value();
        var precio = value_precio;

        myNumeral = numeral($("#superficie_m2").val());
        var value = myNumeral.value();
        var superficie = value;


       
      

		total = precio * superficie;
       // $("input[name=precio_venta]").val(total.toFixed(2));
       $("input[name=precio_venta]").val(number_format(total, 2));

        
	}
	

/* ------------------------------------------------------------------------------- */	



/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_producto(){
		$("#form_productos_registrar").submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
           	

           	 myNumeralSuperficie = numeral($("#superficie_m2").val());
             var valuesuperficie = myNumeralSuperficie.value();


             myNumeralprecio = numeral($("#precio_m2").val());
             var valueprecio = myNumeralprecio.value();


            var descripcion             = $("#descripcion").val();
            var proyecto                = $("#proyecto").val();
            var clasificacion_proyecto  = $("#clasificacion_proyecto").val();
            var etapas_proyecto  		= $("#etapas_proyecto").val();
            var precio_m2               = valueprecio;
            var superficie_m2           = valuesuperficie;
            var precio_venta            = $("#precio_venta").val();
            var lote_anterior           = $("#lote_anterior").val();
            var lote_nuevo              = $("#lote_nuevo").val();

            var observacion = $("#observaciones_registrar").val();
            var plano       = $("[name='plano_registrar']").val();
           

			var data = new FormData();
			data.append('descripcion', descripcion);
			data.append('cod_proyecto', proyecto);
			data.append('cod_proyecto_clasificacion', clasificacion_proyecto);
			data.append('etapas', etapas_proyecto);
			data.append('lote_anterior', lote_anterior);
			data.append('lote_nuevo', lote_nuevo);
			data.append('superficie', superficie_m2);
			data.append('precio_m2', precio_venta);
			data.append('precio', precio_m2);

			data.append('observacion', observacion);
			data.append('plano', plano);



            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url: document.getElementById('ruta').value + 'productos/registrar_producto',
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

                	if (respuesta.success == false) {
                   		 mensajes('danger', respuesta.message);
                   		 $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                	}else{
	                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
	                    mensajes('success', respuesta);
	                    listar('#cuadro2');
                	}
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
		
		$(tbody).on("click", "span.consultar", function(){
			
			var data = table.row( $(this).parents("tr") ).data(); 
			//console.log(data)
			$("#alertas").css("display", "none");
			$('#plano_consultar').fileinput('destroy')
            $("#descripcion_view").val(data.descripcion);
            $("#superficie_m2_view").val(number_format_normal(data.superficie,2));
            $("#precio_venta_view").val(number_format(data.precio_m2, 2));
            $("#precio_m2_view").val(number_format((data.precio_m2 / data.superficie), 2));
            
            $("#lote_anterior_view").val(data.lote_anterior);
            $("#lote_nuevo_view").val(data.lote_nuevo);
            $("#status").val(data.sts_producto);


            $("#observaciones_view").val(data.observacion);
            if ($('#proyecto_view option:selected').val() != data.id_proyecto) {
			    $("#proyecto_view option[value='" + data.id_proyecto + "']").prop("selected",true);
			    agregarOptions("#etapas_proyecto_c", data.nom_etapa, data.nom_etapa);
			    $("#etapas_proyecto_c option[value='" + data.nom_etapa + "']").prop("selected",true);

			    agregarOptions("#clasificacion_proyecto_view", data.cod_proyecto_clasificacion, data.nombre_clasificacion);
			    $("#clasificacion_proyecto_view option[value='" + data.cod_proyecto_clasificacion + "']").prop("selected",true);
            }
            url_imagen = base_url+'assets/cpanel/Productos/planos/'
      if(data.plano != "undefined"){
			 plano = '<img src="'+url_imagen+data.plano+'" class="file-preview-image kv-preview-data">'
	 		// plano += '<input name="plano_editar" value="'+data.plano+'" type="hidden">'
	 		}else{plano = ""}

      $('#plano_consultar').fileinput({
        theme: 'fa',
        language: 'es',	

        uploadAsync: true,
        showUpload: false, // hide upload button
        showRemove: false,
        uploadUrl: base_url+'uploads/upload/productos',
        uploadExtraData:{
        	name:$('#plano_consultar').attr('id')
        },
        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
        overwriteInitial: false,
        maxFileSize: 5000,			
        maxFilesNum: 1,
        autoReplace:true,
        initialPreviewAsData: false,
        initialPreview: [ 
        	plano
        ],
        initialPreviewConfig: [
            {caption: data.plano,downloadUrl: url_imagen+data.plano  ,url: base_url+"uploads/delete", key: data.plano}
        ],

        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    }).on("filebatchselected", function(event, files) {
      $(event.target).fileinput("upload");

    }).on("filebatchuploadsuccess",function(form, data){
      
      //console.log(data.response)
    });
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		
			$('#plano_editar').fileinput('destroy');
		$(tbody).on("click", "span.editar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			url_imagen = base_url+'assets/cpanel/Productos/planos/'
			 if(data.plano != "undefined"){
			 plano = '<img src="'+url_imagen+data.plano+'" class="file-preview-image kv-preview-data">'
	 		 plano += '<input name="plano_editar" value="'+data.plano+'" type="hidden">'
	 		}else{plano = ""}
		$('#plano_editar').fileinput({
        theme: 'fa',
        language: 'es',	

        uploadAsync: true,
        showUpload: false, // hide upload button
        showRemove: false,
        uploadUrl: base_url+'uploads/upload/productos',
        uploadExtraData:{
        	name:$('#plano_editar').attr('id')
        },
        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
        overwriteInitial: false,
        maxFileSize: 5000,			
        maxFilesNum: 1,
        autoReplace:true,
        initialPreviewAsData: false,
        initialPreview: [ 
        	plano
        ],
        initialPreviewConfig: [
            {caption: data.plano,downloadUrl: url_imagen+data.plano  ,url: base_url+"uploads/delete", key: data.plano}
        ],

        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    }).on("filebatchselected", function(event, files) {
      $(event.target).fileinput("upload");

    }).on("filebatchuploadsuccess",function(form, data){
      
      //console.log(data.response)
    }).on('filebeforedelete', function() {
        return new Promise(function(resolve, reject) {
            swal({
            title: '¿Esta seguro de eliminar este Archivo?',
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
               	 resolve();
                
            } else {
                swal("Cancelado", "No se ha eliminado el archivo", "error");
            }
        });
        });
    }).on('filedeleted', function() {
        setTimeout(function() {
            swal("Eliminado!", "Archivo Eliminado Con Exito", "success");
        }, 900);
    });


            $("#descripcion_edit").val(data.descripcion);
            $("#superficie_m2_edit").val(number_format_normal(data.superficie,2));
            $("#precio_venta_edit").val(number_format(data.precio_m2, 2));

            $("#precio_m2_edit").val(number_format((data.precio_m2 / data.superficie), 2));
            //$("#precio_venta_edit").val(data.precio_m2);
            $("#zona_edit").val(data.zona);
            $("#lote_anterior_edit").val(data.lote_anterior);
            $("#lote_nuevo_edit").val(data.lote_nuevo);


            $("#observaciones_editar").val(data.observacion);
            if ($('#proyecto_edit option:selected').val() != data.id_proyecto) {
			    $("#proyecto_edit option[value='" + data.id_proyecto + "']").prop("selected",true); 
			    // agregarOptions("#etapas_proyecto_e", data.etapas, data.nom_etapa);
			    //  $("#etapas_proyecto_e option[value='" + data.nom_etapa + "']").prop("selected",true);
           }
                   

            if ($('#clasificacion_proyecto_view option:selected').val() != data.cod_proyecto_clasificacion) {

            	
            	$.ajax({
		            url: document.getElementById('ruta').value + 'proyectos/getclasificaciones/'+data.id_proyecto,
		            type: 'POST',
		            dataType:'JSON',
		            cache: false,
					processData: false,
					contentType: false,
		            beforeSend: function(){
		            	$('#clasificacion_proyecto_edit option').remove();
		                $('#clasificacion_proyecto_edit').append($('<option>',
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
		                console.log(clasificaciones);
		                $('#etapas_proyecto_e').removeAttr('disabled');
						$('#etapas_proyecto_e option').remove();
		                if (clasificaciones.length == 0) {
		                	$('#etapas_proyecto_e').append($('<option>',
						    {
						        value: "",
						        text : "No Aplica..."
						    }));
						    $('#etapas_proyecto_e').removeAttr('required');
						    $("#precio_m2").removeAttr("disabled");
						    $("#superficie_m2").removeAttr("disabled");
		                }else{
		                	$('#etapas_proyecto_e').attr('required', 'required');
		                	$("#precio_m2_edit").attr("disabled", "disabled");
						   // $("#superficie_m2_edit").attr("disabled", "disabled");
		                	$('#etapas_proyecto_e').append($('<option>',
						    {
						        value: "",
						        text : "Seleccione..."
						    }));
						    $.each(clasificaciones, function(i, item){
				           		$('#etapas_proyecto_e').append($('<option>',
							     {
							        value: item.etapa,
							        text : item.etapa_nomb
							    }));
				           	});
		                }

		                if (data.etapas == null) {
		                	$('#etapas_proyecto_e').append($('<option>',
						    {
						        value: "",
						        text : "No Aplica...",
						        selected: true
						    }));
						    $('#etapas_proyecto_e').attr('disabled', 'disabled');
						    $('#etapas_proyecto_e').removeAttr('required');
						    $("#precio_m2_edit").removeAttr("disabled");
						    $("#superficie_m2_edit").removeAttr("disabled");

						    var precio_m2_edit = data.precio_m2 / data.superficie;

						    $("#precio_m2_edit").val(number_format(precio_m2_edit,2));
		                }else{
		                	$("#etapas_proyecto_e option[value='" + data.etapas + "']").prop("selected",true);

				   // 			var val = $("#etapas_proyecto_e option:selected").text();
							// var res = val.split(" --- ");

							// var precio_m2 = res[1];

							// $("#precio_m2_edit").val(number_format(precio_m2,2));
		                }
			   			
		            }
		        });

            }



            $.ajax({
	            url  : document.getElementById('ruta').value + 'proyectos/getclasificacionesEtapas/'+data.id_proyecto+'/'+data.etapas,
	            type : 'POST',
	            dataType:'JSON',
	            cache: false,
				processData: false,
				contentType: false,
	            beforeSend: function(){
	            	$('#clasificacion_proyecto_edit option').remove();
	                $('#clasificacion_proyecto_edit').append($('<option>',
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
	                $('#clasificacion_proyecto_edit').removeAttr('disabled');
					$('#clasificacion_proyecto_edit option').remove();
	                if (clasificaciones.length == 0) {
	                	$('#etapas_proyecto').append($('<option>',
					    {
					        value: "",
					        text : "No Aplica..."
					    }));
					    $('#clasificacion_proyecto_edit').attr('disabled', 'disabled');
					    $('#clasificacion_proyecto_edit').removeAttr('required');
					    $("#precio_m2").removeAttr("disabled");
					    $("#superficie_m2").removeAttr("disabled");
	                }else{
	                	$('#clasificacion_proyecto_edit').attr('required', 'required');
	                	$("#precio_m2").attr("disabled", "disabled");
					    $("#superficie_m2").attr("disabled", "disabled");
	                	$('#clasificacion_proyecto_edit').append($('<option>',
					    {
					        value: "",
					        text : "Seleccione..."
					    }));
					    $.each(clasificaciones, function(i, item){
			           		$('#clasificacion_proyecto_edit').append($('<option>',
						     {
						        value: item.id_proyecto_clasificacion,
						        text : item.nombre_lista_valor+" --- "+number_format(item.precio,2)
						    }));
			           	});

			           	$("#clasificacion_proyecto_edit option[value='" + data.cod_proyecto_clasificacion + "']").prop("selected",true);
	                }
		   			
	            }
	        });

		    document.getElementById('id_producto_editar').value=data.id_producto;
			cuadros('#cuadro1', '#cuadro4');
		});
	}
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
	/*
		ACCION PARA FILTRA LA CLASIFICACION DEL PROYECTO SEGUN EL PROYECTO SELECCIONADO
	*/

	$("#proyecto_edit").on("change", function(){
		 var proyecto = $("#proyecto_edit").val();
		 	if (proyecto != "") {
		 	$("#precio_m2_edit").val("");
			 $.ajax({
	            url: document.getElementById('ruta').value + 'proyectos/getclasificaciones/'+proyecto,
	            type: 'POST',
	            dataType:'JSON',
	            cache: false,
				processData: false,
				contentType: false,
	            beforeSend: function(){
	            	$('#etapas_proyecto_e option').remove();
	                $('#etapas_proyecto_e').append($('<option>',
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
	                $('#etapas_proyecto_e').removeAttr('disabled');
					$('#etapas_proyecto_e option').remove();
	                if (clasificaciones.length == 0) {
	                	$('#etapas_proyecto_e').append($('<option>',
					    {
					        value: "",
					        text : "No Aplica..."
					    }));
					    $('#etapas_proyecto_e').attr('disabled', 'disabled');
					    $('#etapas_proyecto_e').removeAttr('required');
					    //$("#precio_m2").removeAttr("disabled");
					    //$("#superficie_m2").removeAttr("disabled");
	                }else{
	                	$('#etapas_proyecto_e').attr('required', 'required');
	                	//$("#precio_m2").attr("disabled", "disabled");
					    //$("#superficie_m2").attr("disabled", "disabled");
	                	$('#etapas_proyecto_e').append($('<option>',
					    {
					        value: "",
					        text : "Seleccione..."
					    }));
					    $.each(clasificaciones, function(i, item){
			           		$('#etapas_proyecto_e').append($('<option>',
						     {
						        value: item.etapa,
						        text : item.etapa_nomb//+" --- "+number_format(item.precio,2)
						    }));
			           	});
	                }
		           
	            }
	        });	
		 }else{
		 	$('#etapas_proyecto_e option').remove();
		 	$('#etapas_proyecto_e').append($('<option>',
		    {
		        value: "",
		        text : "Seleccione..."
		    }));
		 }
		 
	})

	$("#etapas_proyecto_e").on("change", function(){
		 var proyecto = $("#proyecto_edit").val();
		 var etapa = $("#etapas_proyecto_e").val();

		 if (etapa != "") {
		 	$("#precio_m2_edit").val("");
			 //$("#superficie_m2").val("");
			 sumar();
			 $.ajax({
	            url: document.getElementById('ruta').value + 'proyectos/getclasificacionesEtapas/'+proyecto+'/'+etapa,
	            type: 'POST',
	            dataType:'JSON',
	            cache: false,
				processData: false,
				contentType: false,
	            beforeSend: function(){
	            	$('#clasificacion_proyecto_edit option').remove();
	                $('#clasificacion_proyecto_edit').append($('<option>',
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
	                $('#clasificacion_proyecto_edit').removeAttr('disabled');
					$('#clasificacion_proyecto_edit option').remove();
	                if (clasificaciones.length == 0) {
	                	$('#clasificacion_proyecto_edit').append($('<option>',
					    {
					        value: "",
					        text : "No Aplica..."
					    }));
					    $('#clasificacion_proyecto_edit').attr('disabled', 'disabled');
					    $('#clasificacion_proyecto_edit').removeAttr('required');
					    $("#precio_m2_edit").removeAttr("disabled");
					    $("#superficie_m2_edit").removeAttr("disabled");
	                }else{
	                	$('#clasificacion_proyecto_edit').attr('required', 'required');
	                	$("#precio_m2_edit").attr("disabled", "disabled");
					    $("#superficie_m2_edit").attr("disabled", "disabled");
	                	$('#clasificacion_proyecto_edit').append($('<option>',
					    {
					        value: "",
					        text : "Seleccione..."
					    }));
					    $.each(clasificaciones, function(i, item){
			           		$('#clasificacion_proyecto_edit').append($('<option>',
						     {
						       value: item.id_proyecto_clasificacion,
						        text : item.nombre_lista_valor+" --- "+number_format(item.precio,2)
						    }));
			           	});
	                }
		           
	            }
	        });	
		 }else{
		 	$('#clasificacion_proyecto_edit option').remove();
		 	$('#clasificacion_proyecto_edit').append($('<option>',
		    {
		        value: "",
		        text : "Seleccione..."
		    }));
		 }
		 
	})

	$("#clasificacion_proyecto_edit").on("change", function(){

		var val = $("#clasificacion_proyecto_edit option:selected").text();
		var res = val.split(" --- ");

		var precio_m2 = res[1];

		$("#superficie_m2_edit").removeAttr("disabled");
		$("#precio_m2_edit").attr("disabled", "disabled");
		$("#precio_m2_edit").val(precio_m2);
		sumar_edit();
	})


	$("#precio_m2_edit").on("keyup", function(){

		var myNumeral_prec_edit = numeral($("#precio_m2_edit").val());
		var value_prec_edit     = myNumeral_prec_edit.value();
		if (value_prec_edit>99999.99) {
        	console.log("HEEEYYY");
        	//var borra = superficie.toString().substr(-1);

        	var cant = $("#precio_m2_edit").val().toString().length;

        	//console.log($("#superficie_m2").val().toString().substring(0,5));

        	$("#precio_m2_edit").val($("#precio_m2_edit").val().toString().substring(0,5));
        }


		sumar_edit();
	});

	$("#superficie_m2_edit").on("keyup", function(){
		var myNumeral_super_edit = numeral($("#superficie_m2_edit").val());
		var value_super_edit     = myNumeral_super_edit.value();
		if (value_super_edit>99999.99) {
        	console.log("HEEEYYY");
        	//var borra = superficie.toString().substr(-1);

        	var cant = $("#superficie_m2_edit").val().toString().length;

        	//console.log($("#superficie_m2").val().toString().substring(0,5));

        	$("#superficie_m2_edit").val($("#superficie_m2_edit").val().toString().substring(0,5));
        }

		sumar_edit();
	});


	function sumar_edit(){
        // var precio     = Number($("#precio_m2_edit").val());
        // var superficie = Number($("#superficie_m2_edit").val());

        myNumeral      = numeral($("#superficie_m2_edit").val());
        var value      = myNumeral.value();
        var superficie = value;

        myNumeral_precio = numeral($("#precio_m2_edit").val());
        var value_precio = myNumeral_precio.value();
        var precio = value_precio;


		total = precio * superficie;
       // $("input[name=precio_venta_edit]").val(total.toFixed(2));
       $("input[name=precio_venta_edit]").val(number_format(total, 2));
	}
	

/* ------------------------------------------------------------------------------- */	


/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_vendedor(){
		$("#form_producto_editar").submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
          	
            myNumeralSuperficie = numeral($("#superficie_m2_edit").val());
            var valuesuperficie_edit = myNumeralSuperficie.value();


            myNumeralprecio = numeral($("#precio_m2_edit").val());
            var valueprecio_edit = myNumeralprecio.value();

          	var id_producto             = $("#id_producto_editar").val();
            var descripcion             = $("#descripcion_edit").val();
            var proyecto                = $("#proyecto_edit").val();
            var clasificacion_proyecto  = $("#clasificacion_proyecto_edit").val();
            var superficie_m2           = valuesuperficie_edit;
            var precio_venta            = $("#precio_venta_edit").val();
            var lote_anterior           = $("#lote_anterior_edit").val();
            var lote_nuevo              = $("#lote_nuevo_edit").val();
            var precio_m2               = valueprecio_edit;
            var etapas 					= $("#etapas_proyecto_e").val();


            var observaciones           = $("#observaciones_editar").val();
            var plano                   = $("[name='plano_editar']").val();
           

			var data = new FormData();
			data.append('id_producto', id_producto);
			data.append('descripcion', descripcion);
			data.append('cod_proyecto', proyecto);
			data.append('cod_proyecto_clasificacion', clasificacion_proyecto);
			data.append('etapas', etapas);
			data.append('lote_anterior', lote_anterior);
			data.append('lote_nuevo', lote_nuevo);
			data.append('superficie', superficie_m2);
			data.append('precio_m2', precio_venta);
			data.append('precio', precio_m2);

			data.append('plano', plano);
			data.append('observacion', observaciones);
			
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url: document.getElementById('ruta').value + 'productos/actualizar_productos',
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
            eliminarConfirmacion('productos/eliminar_producto', data.id_producto, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('productos/status_producto', data.id_producto, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('productos/status_producto', data.id_producto, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}



/* ------------------------------------------------------------------------------- */
	/*
		Funcion que agrega las inombiliaria a la tabla
	*/
	function agregarInmobiliaria(select, tabla){
		var idInmobiliaria = $(select).val();
		var nombreInmobiliaria = $(select + " option:selected").html();
		var validadoInmobiliaria = false;
		var html = '';
		if ( idInmobiliaria != "" ){
			$(tabla + " tbody tr").each(function() {
			  	if (idInmobiliaria == $(this).find(".id_inmobiliaria").val())
			  		validadoInmobiliaria = true;
			});
			if (!validadoInmobiliaria) {
				html += "<tr id='i" + idInmobiliaria + "'><td>" + nombreInmobiliaria + " <input type='hidden' class='id_inmobiliaria' name='id_inmobiliaria' value='" + idInmobiliaria + "'></td>";
				html += "<td><button type='button' class='btn btn-danger waves-effect' onclick='eliminarTr(\"" + "#i" + idInmobiliaria + "\")'>Eliminar</button></td></tr>";
				$(tabla + " tbody").append(html);
			} else {
				warning('¡La opción seleccionada ya se encuentra agregada!');
			}
			$(select + " option[value='']").attr("selected","selected");
		} else {
			warning('¡Debe seleccionar una opción!');
		}
		
	}


	/* ------------------------------------------------------------------------------- */
	/*
		Funcion que busca las inmobiliarias asociadas al proyecto.
	*/
	function buscarInmobiliarias(tabla, vendedor){

		$.ajax({
	        url:document.getElementById('ruta').value + 'productos/buscarInmobiliarias',
	        type:'POST',
	        dataType:'JSON',
	        data: {'vendedor' : vendedor},
	        error: function() {
                buscarInmobiliarias(tabla, vendedor);
	        },
	        success: function(respuesta){
	            respuesta.forEach(function(inmobiliaria, index){
	            	if ( tabla == "#tableInmobiliariaConsultar") {
						table = '<tr><td>' + inmobiliaria.codigo + ' - ' + inmobiliaria.nombre + ' - Coordinador: ' + inmobiliaria.nombres + ' ' + inmobiliaria.paterno + ' ' + inmobiliaria.materno + '</td><tr>';
	            	} else if( tabla == "#tableInmobiliariaEditar") {
						table = "<tr id='i" + inmobiliaria.id_inmobiliaria + "'><td>" + inmobiliaria.codigo + " - " + inmobiliaria.nombre + "<input type='hidden' class='id_inmobiliaria' name='id_inmobiliaria' value='" + inmobiliaria.id_inmobiliaria + "'></td><td><button type='button' class='btn btn-danger waves-effect'onclick='eliminarConfirmarInmobiliaria(" + inmobiliaria.id + ", " + inmobiliaria.id_inmobiliaria + ")'>Eliminar</button></td><tr>";
	            	}
					$(tabla + " tbody").append(table);
	            });
	        }
	    });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que elimina la inmobiliaria de la tabla
	*/
	function eliminarTr(tr){
		$(tr).remove(); 
	}


	/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que hace una busqueda de las operaciones que tiene el rol por cada
		lista vista y mostrar los resultados para su edicion
	*/
	function eliminarConfirmarInmobiliaria(id, inmobiliaria){
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
                    url: document.getElementById('ruta').value + "productos/eliminar_inmobiliaria_vendedor",
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
                        $("#tableInmobiliariaEditar").find("tbody tr#i" + inmobiliaria).remove();
                    }
                });
            } else {
                swal("Cancelado", "No se ha eliminado el registro", "error");
            }
        });
	}



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


	 function valida(e){
	    tecla = (document.all) ? e.keyCode : e.which;      

	    console.log(tecla); 
	    if(tecla == 8){
	        return;
	    }else{
	        patron =/^([0-9])*[.]?[0-9]*$/;
	        tecla_final = String.fromCharCode(tecla);
	        return patron.test(tecla_final);
	    }
	}


		function sin_coma(e){
			tecla = (document.all) ? e.keyCode : e.which;          
			patron =/[^,]/;
			tecla_final = String.fromCharCode(tecla);
			return patron.test(tecla_final);
		}
		
