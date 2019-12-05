$(document).ready(function(){
	listar();
	registrar_proyecto();
	actualizar_proyecto();
	porcentajeInput('.precio');
	//$('#plano_editar').fileinput('destroy');
	//	$('#plano_registrar').fileinput('destroy');
	//$('.precio').inputmask('99,999.99', {reverse: true})
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
				"url": url + "Proyectos/listado_proyectos",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_proyecto",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data":"codigo"},
				{"data":"nombre",
					render : function(data, type, row) {
						var descripcion = data;
						if (descripcion.length > 15)
							descripcion = data.substr(0,14) + "..."
						return descripcion;
	          		}
				},
				{"data":"descripcion",
					render : function(data, type, row) {
						var descripcion = data;
						if (descripcion.length > 15)
							descripcion = data.substr(0,14) + "..."
						return descripcion;
	          		}
				},
				{"data": null,
					render : function(data, type, row) {
						var nombres = data.nombres + " " + data.paterno + " " + data.materno;
						if (nombres.length > 15)
							descripcion = nombres.substr(0,14) + "..."
						return descripcion;
	          		}
				},
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

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro2 para mostrar el formulario de registrar.
	*/
	function nuevoProyecto(cuadroOcultar, cuadroMostrar){
		$("#alertas").css("display", "none");
		$('#plano_registrar').fileinput('destroy');
		$('#plano_registrar').fileinput({
        theme: 'fa',
        language: 'es',	

        uploadAsync: true,
        showUpload: false, // hide upload button
        showRemove: false,
        uploadUrl: base_url+'uploads/upload/proyecto',
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
		cuadros("#cuadro1", "#cuadro2");
		$("#form_proyecto_registrar")[0].reset();
		$("#tableInmobiliariaRegistrar tbody tr").remove();
		$("#tableClasificacionRegistrar tbody tr").remove();
		$("#tableEsquemaRegistrar tbody tr").remove();  
		$("#codigo_registrar").focus();

		$("#dias_vencidos_registrar, #porcentaje_mora_registrar").attr("disabled", "disabled").removeAttr("required");
		$("#indicador_mora_registrar").val("N");


	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_proyecto(){
		$("#form_proyecto_registrar").submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            var codigo = $("#codigo_registrar").val();
            var nombre = $("#nombre_registrar").val();
            var descripcion = $("#descripcion_registrar").val();
            var director = $("#director_registrar").val();
            var plano = $("[name='plano_registrar']").val();
            var indicador_mora  = $("#indicador_mora_registrar").val();
            var dias_vencidos   = $("#dias_vencidos_registrar").val();
            var porcentaje_mora = $("#porcentaje_mora_registrar").val();
            var objetoInmobiliaria = [];
            $("#tableInmobiliariaRegistrar tbody tr").each(function() {
            	var inmobiliaria = [];
            	var id = $(this).find(".id_inmobiliaria").val();
            	inmobiliaria.push(id);
				objetoInmobiliaria.push(inmobiliaria);
			});
			var objetoClasificacion = [];
            $("#tableClasificacionRegistrar tbody tr").each(function() {
            	var clasificacion = [];
            	var id = $(this).find(".proyecto_clasificacion").val();
            	clasificacion.push(id);
				objetoClasificacion.push(clasificacion);
			});
			var objetoEsquema = []
			$("#tableEsquemaRegistrar tbody tr").each(function() {
            	var esquema = [];
            	var id = $(this).find(".id_esquema").val();
            	esquema.push(id);
				objetoEsquema.push(esquema);
			});
			var data = new FormData();
			data.append('codigo', codigo);
			data.append('nombre', nombre);
			data.append('descripcion', descripcion);
			data.append('director', director);
			data.append('plano', plano);

			data.append('indicador_mora', indicador_mora);
			data.append('dias_vencidos', dias_vencidos);
			data.append('porcentaje_mora', porcentaje_mora);

			for (var i = 0; i < objetoInmobiliaria.length; i++) {
				data.append('inmobiliarias[]', objetoInmobiliaria[i]);
			}
			for (var i = 0; i < objetoClasificacion.length; i++) {
				data.append('clasificaciones[]', objetoClasificacion[i]);
			}
			for (var i = 0; i < objetoEsquema.length; i++) {
				data.append('esquemas[]', objetoEsquema[i]);
			}
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url: document.getElementById('ruta').value + 'Proyectos/registrar_proyecto',
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
                    listar('#cuadro2');
                }
            });
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 
	*/
	function ver(tbody, table){
		$("#tableInmobiliariaConsultar tbody tr").remove(); 
		$("#tableClasificacionConsultar tbody tr").remove();
		$("#tableEsquemaConsultar tbody tr").remove(); 
		$('#plano_consultar').fileinput('destroy');   
		$(tbody).on("click", "span.consultar", function(){

			$("#alertas").css("display", "none");

			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('codigo_consultar').value=data.codigo;
			document.getElementById('nombre_consultar').value=data.nombre;
			document.getElementById('descripcion_consultar').value=data.descripcion;
			$("#director_consultar option[value='" + data.director + "']").attr("selected","selected");
				$("#plano_Vconsultar").attr('href', document.getElementById('ruta').value + "assets/cpanel/Proyectos/planos/"  + data.plano)
				$("#plano_consultar").attr('href', document.getElementById('ruta').value + "assets/cpanel/Proyectos/planos/"  + data.plano).attr('download', "PLANOS_PROYECTO_" + data.codigo);

			$("#dias_vencidos_view").val(data.can_dias_vencidos);
            $("#porcentaje_mora_view").val(data.porcentaje_mora);
			buscarInmobiliarias('#tableInmobiliariaConsultar', data.id_proyecto);
			buscarClasificaciones('#tableClasificacionConsultar', data.id_proyecto);
			buscarEsquemas('#tableEsquemaConsultar', data.id_proyecto);
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
		        uploadUrl: base_url+'uploads/upload/proyectos',
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
		$("#form_proyecto_actualizar")[0].reset();
		$("#tableInmobiliariaEditar tbody tr").remove(); 
		$("#tableClasificacionEditar tbody tr").remove();
		$("#tableEsquemaEditar tbody tr").remove(); 
		$('#plano_editar').fileinput('destroy');
		base_url = document.getElementById('ruta').value
		url_imagen = base_url+'assets/cpanel/Proyectos/planos/'
		$(tbody).on("click", "span.editar", function(){
			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			console.log(data)
			document.getElementById('codigo_editar').value=data.codigo;
			document.getElementById('nombre_editar').value=data.nombre;
			document.getElementById('descripcion_editar').value=data.descripcion;
			document.getElementById('id_proyecto_editar').value=data.id_proyecto;
			$("#director_editar option[value='" + data.director + "']").attr("selected","selected");
			buscarInmobiliarias('#tableInmobiliariaEditar', data.id_proyecto);
			buscarClasificaciones('#tableClasificacionEditar', data.id_proyecto);
			buscarEsquemas('#tableEsquemaEditar', data.id_proyecto);
			$("#dias_vencidos_editar").val(data.can_dias_vencidos);
            $("#porcentaje_mora_editar").val(data.porcentaje_mora);


			$("#dias_vencidos_editar, #porcentaje_mora_editar").attr("disabled", "disabled").removeAttr("required");
			$("#indicador_mora_actualizar").val(data.indicador_mora);

			if (data.indicador_mora == "S") {
				$("#indicador_actualizar").attr("checked","checked");
				$("#dias_vencidos_editar").removeAttr("disabled");
				$("#porcentaje_mora_editar").removeAttr("disabled");
			}else{
				$("#indicador_actualizar").removeAttr("checked");
			}
			
			if (data.plano!= "undefined"){ 
			planos = '<img src="'+url_imagen+data.plano+'" class="file-preview-image kv-preview-data">'
			planos += '<input name="plano_editar" value="'+data.plano+'" type="hidden">'
		}else {
			planos = "";
		}
			$('#plano_editar').fileinput({
		        theme: 'fa',
		        language: 'es',	

		        uploadAsync: true,
		        showUpload: false, // hide upload button
		        showRemove: false,
		        uploadUrl: base_url+'uploads/upload/proyecto',
		        uploadExtraData:{
		        	name:$('#plano_editar').attr('id')
		        },
		        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg", "docx"],
		        overwriteInitial: false,
		        maxFileSize: 5000,			
		        maxFilesNum: 1,
		        autoReplace:true,
		        initialPreviewAsData: false,
		        
		        initialPreview: [ 
		        	planos
		        ],
		        initialPreviewConfig: [
		            {caption: data.plano, downloadUrl: url_imagen+data.plano, url: base_url+"uploads/delete", key: data.plano}
		        ],

		        //allowedFileTypes: ['image', 'video', 'flash'],
		        slugCallback: function (filename) {
		            return filename.replace('(', '_').replace(']', '_');
		        }
		    }).on("filebatchselected", function(event, files) {
		      $(event.target).fileinput("upload");

		    }).on("filebatchuploadsuccess",function(form, data){
		      
		     // console.log(data.response)
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
					cuadros('#cuadro1', '#cuadro4');
					$("#codigo_editar").focus();
				});
			}
/* ------------------------------------------------------------------------------- */


/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_proyecto(){
		$("#form_proyecto_actualizar").submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            var id_proyecto = $("#id_proyecto_editar").val();
            var codigo = $("#codigo_editar").val();
            var nombre = $("#nombre_editar").val();
            var descripcion = $("#descripcion_editar").val();
            var director = $("#director_editar").val();
            var plano = $("[name='plano_editar']").val();
            var indicador_mora  = $("#indicador_mora_actualizar").val();
            var dias_vencidos   = $("#dias_vencidos_editar").val();
            var porcentaje_mora = $("#porcentaje_mora_editar").val();
            var objetoInmobiliaria = [];
            $("#tableInmobiliariaEditar tbody tr").each(function() {
            	var inmobiliaria = [];
            	var id = $(this).find(".id_inmobiliaria").val();
            	if ( id != undefined){
            		inmobiliaria.push(id);
					objetoInmobiliaria.push(inmobiliaria);
            	}
			});
            var objetoClasificacion = [];
            $("#tableClasificacionEditar tbody tr").each(function() {
            	var clasificacion = [];
            	var id = $(this).find(".proyecto_clasificacion").val();
            	if ( id != undefined){
	            	clasificacion.push(id);
					objetoClasificacion.push(clasificacion);
				}
			});
			var objetoEsquema = [];
            $("#tableEsquemaEditar tbody tr").each(function() {
            	var esquema = [];
            	var id = $(this).find(".id_esquema").val();
            	if ( id != undefined){
            		esquema.push(id);
					objetoEsquema.push(esquema);
            	}
			});
			var data = new FormData();
			data.append('id_proyecto', id_proyecto);
			data.append('codigo', codigo);
			data.append('nombre', nombre);
			data.append('descripcion', descripcion);
			data.append('director', director);
			data.append('plano', plano);

			data.append('indicador_mora', indicador_mora);
			data.append('dias_vencidos', dias_vencidos);
			data.append('porcentaje_mora', porcentaje_mora);


			for (var i = 0; i < objetoInmobiliaria.length; i++) {
				data.append('inmobiliarias[]', objetoInmobiliaria[i]);
			}
			for (var i = 0; i < objetoClasificacion.length; i++) {
				data.append('clasificaciones[]', objetoClasificacion[i]);
			}
			for (var i = 0; i < objetoEsquema.length; i++) {
				data.append('esquemas[]', objetoEsquema[i]);
			}
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url: document.getElementById('ruta').value + 'Proyectos/actualizar_proyecto',
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
            eliminarConfirmacion('Proyectos/eliminar_proyecto', data.id_proyecto, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('Proyectos/status_proyecto', data.id_proyecto, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Proyectos/status_proyecto', data.id_proyecto, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */

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
				html += "<td><button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar' onclick='eliminarTr(\"" + "#i" + idInmobiliaria + "\")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button></td></tr>";
				
				
				$(tabla + " tbody").append(html);
			} else {
				warning('¡La opción seleccionada ya se encuentra agregada!');
			}
			$(select + " option[value='']").attr("selected","selected");
		} else {
			warning('¡Debe seleccionar una opción!');
		}
		
	}
	function agregarEsquemas(select, tabla){
		var idEsquema = $(select).val();
		var nombreesquema = $(select + " option:selected").html();
		var validoEsquema = false;
		var html = '';
		if ( idEsquema != "" ){
			$(tabla + " tbody tr").each(function() {
			  	if (idEsquema == $(this).find(".id_esquema").val())
			  		validoEsquema = true;
			});
			if (!validoEsquema) {
				html += "<tr id='e" + idEsquema + "'><td>" + nombreesquema + " <input type='hidden' class='id_esquema' name='id_esquema' value='" + idEsquema + "'></td>";
				html += "<td><button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar' onclick='eliminarTr(\"" + "#e" + idEsquema + "\")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button></td></tr>";
				
				
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

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que elimina la inmobiliaria de la tabla
	*/
	function eliminarTr(tr){
		$(tr).remove(); 
	}

	function editarClasificacion(tr)
	{
	var id = tr.replace("#p", "")
	$.ajax({
                    url: document.getElementById('ruta').value + "Proyectos/consulta_clasificacion_existe",
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        'id' : id,
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                        //mensajes('danger', errores);
                        warning('La Clasificación NO se puede Editar ya que tiene un producto asociado')
                    },
                    success: function(respuesta){
	                    $(tr).removeAttr('disabled').focus()
	                    $(tr).change(function(){
							precio = $(tr).val()
							$(tr).attr('disabled',true)
							$.ajax({
					        url:document.getElementById('ruta').value + 'Proyectos/editarClasificacionAjax',
					        type:'POST',
					        dataType:'JSON',
					        data: {'precio' : precio, 'id_proyecto_clasificacion': id},
					        success: function(respuesta){
					        
					        }
					        })

						})
                    }
            });

	}
	function activar_desctivar_imb(id, status, id_proyecto){

		if (status == 1){
		statusConfir('Proyectos/status_inmobilaria', id, 2, "¿Esta seguro de desactivar el registro?", 'desactivar', id_proyecto, "inmobilaria");
    }else{
		statusConfir('Proyectos/status_inmobilaria', id, 1, "¿Esta seguro de activar el registro?", 'activar', id_proyecto, "inmobilaria");

        }
    }
    function activar_desctivar_clasi(id, status, id_proyecto){
		if (status == 1){
		statusConfir('Proyectos/status_clasificacion', id, 2, "¿Esta seguro de desactivar el registro?", 'desactivar', id_proyecto, "clasificacion");
    }else{
		statusConfir('Proyectos/status_clasificacion', id, 1, "¿Esta seguro de activar el registro?", 'activar', id_proyecto, "clasificacion");

        }
    }
    function activar_desctivar_esq(id, status, id_proyecto){

		if (status == 1){
		statusConfir('Proyectos/status_esquema', id, 2, "¿Esta seguro de desactivar el registro?", 'desactivar', id_proyecto, "esquema");
    }else{
		statusConfir('Proyectos/status_esquema', id, 1, "¿Esta seguro de activar el registro?", 'activar', id_proyecto, "esquema");

        }
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que busca las inmobiliarias asociadas al proyecto.
	*/
	function buscarInmobiliarias(tabla, proyecto){
		$.ajax({
	        url:document.getElementById('ruta').value + 'Proyectos/buscarInmobiliarias',
	        type:'POST',
	        dataType:'JSON',
	        data: {'proyecto' : proyecto},
	        error: function() {
                buscarInmobiliarias(tabla, proyecto);
	        },
	        success: function(respuesta){
	            respuesta.forEach(function(inmobiliaria, index){
	            	//console.log(inmobiliaria)
	            	if ( tabla == "#tableInmobiliariaConsultar") {
						table = '<tr><td>' + inmobiliaria.codigo + ' - ' + inmobiliaria.nombre + ' - Coordinador: ' + inmobiliaria.nombres + ' ' + inmobiliaria.paterno + ' ' + inmobiliaria.materno + '</td><tr>';
	            	} else if( tabla == "#tableInmobiliariaEditar") {
						table = "<tr id='i" + inmobiliaria.id_inmobiliaria_proyecto + "'><td>" + inmobiliaria.codigo + " - " + inmobiliaria.nombre + "<input type='hidden' class='id_inmobiliaria' name='id_inmobiliaria' value='" + inmobiliaria.id_inmobiliaria + "'></td>"
						table += "<td>"
					if(inmobiliaria.status == 1){
						table += "<button type='button' class='btn btn-xs btn-warning waves-effect' title='Desactivar' onclick='activar_desctivar_imb(" + inmobiliaria.id_inmobiliaria_proyecto + ", " + inmobiliaria.status + ", " + inmobiliaria.id_proyecto + ")'><i class='block fa fa-unlock' style='margin-bottom:5px'></i></button>";
					}
					if(inmobiliaria.status == 2){
					table += "<button type='button' class='btn btn-xs btn-warning waves-effect' title='Activar' onclick='activar_desctivar_imb(" + inmobiliaria.id_inmobiliaria_proyecto + ", " + inmobiliaria.status + ", " + inmobiliaria.id_proyecto + ")'><i class='desblock fa fa-lock' style='margin-bottom:5px'></i></button>";	
					}	
						table +="<button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar' onclick='eliminarConfirmarInmobiliaria(" + inmobiliaria.id_inmobiliaria_proyecto + ", " + inmobiliaria.id_inmobiliaria + ")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button>";
						table += "</td><tr>"	          
	          }
					$(tabla + " tbody").append(table);
	            });
	        }
	    });
	}
	function buscarInmobiliariasID(tabla, proyecto, id){
		$.ajax({
	        url:document.getElementById('ruta').value + 'Proyectos/buscarInmobiliariasporId',
	        type:'POST',
	        dataType:'JSON',
	        data: {'proyecto' : proyecto, 'id_inmobiliaria_proyecto': id},
	        success: function(respuesta){
	            respuesta.forEach(function(inmobiliaria, index){
	            		table = "<tr id='i" + inmobiliaria.id_inmobiliaria_proyecto + "'><td>" + inmobiliaria.codigo + " - " + inmobiliaria.nombre + "<input type='hidden' class='id_inmobiliaria' name='id_inmobiliaria' value='" + inmobiliaria.id_inmobiliaria + "'></td>"
						table += "<td>"
					if(inmobiliaria.status == 1){
						table += "<button type='button' class='btn btn-xs btn-warning waves-effect' title='Desactivar' onclick='activar_desctivar_imb(" + inmobiliaria.id_inmobiliaria_proyecto + ", " + inmobiliaria.status + ", " + inmobiliaria.id_proyecto + ")'><i class='block fa fa-unlock' style='margin-bottom:5px'></i></button>";
					}
					if(inmobiliaria.status == 2){
					table += "<button type='button' class='btn btn-xs btn-warning waves-effect' title='Activar' onclick='activar_desctivar_imb(" + inmobiliaria.id_inmobiliaria_proyecto + ", " + inmobiliaria.status + ", " + inmobiliaria.id_proyecto + ")'><i class='desblock fa fa-lock' style='margin-bottom:5px'></i></button>";	
					}	
						table +="<button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar' onclick='eliminarConfirmarInmobiliaria(" + inmobiliaria.id_inmobiliaria_proyecto + ", " + inmobiliaria.id_inmobiliaria + ")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button>";
						table += "</td><tr>"	          
	  
					$(tabla + " tbody").append(table);
	            });
	        }
	    });
	}
	function buscarEsquemas(tabla, proyecto){
		$.ajax({
	        url:document.getElementById('ruta').value + 'Proyectos/buscarEsquemas',
	        type:'POST',
	        dataType:'JSON',
	        data: {'proyecto' : proyecto},
	        error: function() {
                buscarEsquemas(tabla, proyecto);
	        },
	        success: function(respuesta){
	            respuesta.forEach(function(esquema, index){
	            	//console.log(inmobiliaria)
	            	if ( tabla == "#tableEsquemaConsultar") {
						table = '<tr><td>'+ esquema.descripcion+'</td><tr>';
	            	} else if( tabla == "#tableEsquemaEditar") {
						table = "<tr id='e" + esquema.id_proyectos_esquemas + "'><td>" + esquema.descripcion + "<input type='hidden' class='id_esquema' name='id_esquema' value='" + esquema.id_esquema + "'></td>"
						table += "<td>"
					if(esquema.status == 1){
						table += "<button type='button' class='btn btn-xs btn-warning waves-effect' title='Desactivar' onclick='activar_desctivar_esq(" + esquema.id_proyectos_esquemas + ", " + esquema.status + ", " + esquema.id_proyecto + ")'><i class='block fa fa-unlock' style='margin-bottom:5px'></i></button>";
					}
					if(esquema.status == 2){
					table += "<button type='button' class='btn btn-xs btn-warning waves-effect' title='Activar' onclick='activar_desctivar_esq(" + esquema.id_proyectos_esquemas + ", " + esquema.status + ", " + esquema.id_proyecto + ")'><i class='desblock fa fa-lock' style='margin-bottom:5px'></i></button>";	
					}	
						table +="<button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar' onclick='eliminarConfirmarEsquema(" + esquema.id_proyectos_esquemas + ", " + esquema.id_esquema + ")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button>";
						table += "</td><tr>"	          
	          }
					$(tabla + " tbody").append(table);
	            });
	        }
	    });
	}
	function buscarEsquemasID(tabla, proyecto, id){
		$.ajax({
	        url:document.getElementById('ruta').value + 'Proyectos/buscarEsquemasID',
	        type:'POST',
	        dataType:'JSON',
	        data: {'proyecto' : proyecto, 'id_proyectos_esquemas': id},
	        success: function(respuesta){
	            respuesta.forEach(function(esquema, index){
	            		table = "<tr id='e" + esquema.id_proyectos_esquemas + "'><td>" + esquema.descripcion + "<input type='hidden' class='id_esquema' name='id_esquema' value='" + esquema.id_esquema + "'></td>"
						table += "<td>"
					if(esquema.status == 1){
						table += "<button type='button' class='btn btn-xs btn-warning waves-effect' title='Desactivar' onclick='activar_desctivar_esq(" + esquema.id_proyectos_esquemas + ", " + esquema.status + ", " + esquema.id_proyecto + ")'><i class='block fa fa-unlock' style='margin-bottom:5px'></i></button>";
					}
					if(esquema.status == 2){
					table += "<button type='button' class='btn btn-xs btn-warning waves-effect' title='Activar' onclick='activar_desctivar_esq(" + esquema.id_proyectos_esquemas + ", " + esquema.status + ", " + esquema.id_proyecto + ")'><i class='desblock fa fa-lock' style='margin-bottom:5px'></i></button>";	
					}	
						table +="<button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar' onclick='eliminarConfirmarEsquema(" + esquema.id_proyectos_esquemas + ", " + esquema.id_esquema + ")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button>";
						table += "</td><tr>"        
	  
					$(tabla + " tbody").append(table);
	            });
	        }
	    });
	        
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
                    url: document.getElementById('ruta').value + "Proyectos/eliminar_inmobiliaria_proyecto",
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        'id' : inmobiliaria,
                        'id_inmobiliaria_proyecto' : id
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                       // mensajes('danger', errores);
                      warning(errores)
                  },
                    success: function(respuesta){
                        mensajes('success', respuesta);
                        $("#tableInmobiliariaEditar").find("tbody tr#i" + id).remove();
                    }
                });
            } else {
                swal("Cancelado", "No se ha eliminado el registro", "error");
            }
        });
	}
	function eliminarConfirmarEsquema(id, esquema){
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
                    url: document.getElementById('ruta').value + "Proyectos/eliminar_proyectos_esquemas",
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        'id' : esquema,
                        'id_proyectos_esquemas' : id
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                       // mensajes('danger', errores);
                      warning(errores)
                  },
                    success: function(respuesta){
                        mensajes('success', respuesta);
                        $("#tableEsquemaEditar").find("tbody tr#e" + id).remove();
                    }
                });
            } else {
                swal("Cancelado", "No se ha eliminado el registro", "error");
            }
        });
	}

	    function statusConfir(controlador, id, status, title, confirmButton, id_proyecto, tipo){
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
                      mensajes('success', respuesta);
                      if (tipo == "inmobilaria"){
                      	eliminarTr("#i" + id)
                      	buscarInmobiliariasID('#tableInmobiliariaEditar', id_proyecto, id);

		                   	
					}else if(tipo == 'clasificacion'){
                      	eliminarTr("#cl" + id)
						buscarClasificacionesPorID('#tableClasificacionEditar', id_proyecto, id)

					}else if (tipo == 'esquema'){
						eliminarTr("#e" + id)
						buscarEsquemasID('#tableEsquemaEditar', id_proyecto, id)
					}
                      //$("#tableInmobiliariaEditar").find("tbody tr#i" + inmobiliaria).remove();
                      //buscarInmobiliarias('#tableInmobiliariaConsultar', id_proyecto);
                      //listar('#cuadro4')
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
		Funcion que agregar la clasificaion a la tabla
	*/
	function agregarClasificacion(etapas, select, input, tabla){
		var idClasificacion = $(select).val();
		var nombreClasificacion = $(select + " option:selected").html();
		var id_etapa = $(etapas).val();
		var nombreEtapas = $(etapas + " option:selected").html();
		var precio = $(input).val();
		var validadoClasificacion = false;
		var html = '';
		if ( id_etapa != "" && idClasificacion != "" && precio != "" ){
			$(tabla + " tbody tr").each(function() {
			  	if (idClasificacion == $(this).find(".id_clasificacion").val() && id_etapa == $(this).find(".id_etapa").val() )
			  		validadoClasificacion = true;
			});
			if (!validadoClasificacion) {
				var array = [id_etapa, idClasificacion, precio];
				html += "<tr id='c" + idClasificacion + "'><td>" + nombreEtapas + " <input type='hidden' class='id_etapa' value='" + id_etapa + "'> <td>" + nombreClasificacion + " <input type='hidden' class='id_clasificacion' value='" + idClasificacion + "'> <input type='hidden' class='proyecto_clasificacion' value='" + array + "'></td>";
				html += "<td class='text-right'> $<input type='text'id='p"+idClasificacion+"' style='text-align:right' disabled value= ' "+precio+"' </td>";
				html += "<td><button type='button' class='btn btn-xs btn-danger waves-effect' title= 'Eliminar' onclick='eliminarTr(\"" + "#c" + idClasificacion + "\")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button>";
				html += "</td></tr>"
						$(tabla + " tbody").append(html);
			} else {
				warning('¡La opción seleccionada ya se encuentra agregada!');
			}
			$(select + " option[value='']").attr("selected","selected");
			$(input).val('');
		} else {
			warning('¡Debe llenar los campos!');
		}
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que busca las clasificaciones asociadas al proyecto.
	*/
	function buscarClasificaciones(tabla, proyecto){
		$.ajax({
	        url:document.getElementById('ruta').value + 'Proyectos/buscarClasificaciones',
	        type:'POST',
	        dataType:'JSON',
	        data: {'proyecto' : proyecto},
	        error: function() {
                buscarClasificaciones(tabla, proyecto);
	        },
	        success: function(respuesta){
	            respuesta.forEach(function(clasificacion, index){
	            	if ( tabla == "#tableClasificacionConsultar") {
						table = '<tr><td>' + clasificacion.etapa_nom + '</td><td>' + clasificacion.nombre_lista_valor + '</td><td style="text-align:right">$ ' + number_format(clasificacion.precio, 2) + '</td><tr>';
	            	} else if( tabla == "#tableClasificacionEditar") {
	            		var array = [clasificacion.etapa, clasificacion.clasificacion, clasificacion.precio];
						clasificacion.status == ""
						table = "<tr id='cl" + clasificacion.id_proyecto_clasificacion + "'><td>" + clasificacion.etapa_nom + "<input type='hidden' class='id_estapa' value='" + clasificacion.etapa + "'></td><td>" + clasificacion.nombre_lista_valor + "<input type='hidden' class='id_clasificacion' value='" + clasificacion.clasificacion + "'><input type='hidden' class='proyecto_clasificacion' value='" + array + "'></td><td text-align: right class='text-right'>$ " 
						table += "<input type='text' style='text-align: right' class='precio' id='p"+clasificacion.id_proyecto_clasificacion+"'<td style='text-align: right' disabled value= ' "+ number_format(clasificacion.precio, 2) + "'</td>"
     					table += "<td>"
     					table += "<button type='button' class='btn btn-xs btn-primary waves-effect' title='Editar' onclick='editarClasificacion(\"" + "#p" + clasificacion.id_proyecto_clasificacion + "\")'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></button>";

	            		if(clasificacion.status == 1){
							table += "<button type='button' class='btn btn-xs btn-warning waves-effect' title='Desactivar' onclick='activar_desctivar_clasi(" + clasificacion.id_proyecto_clasificacion + ", " + clasificacion.status + ", " + clasificacion.id_proyecto + ")'><i class='block2 fa fa-unlock' style='margin-bottom:5px'></i></button>";
						}
						if(clasificacion.status == 2){
							table += "<button type='button' class='btn btn-xs btn-warning waves-effect' title='Activar' onclick='activar_desctivar_clasi(" + clasificacion.id_proyecto_clasificacion + ", " + clasificacion.status + ", " + clasificacion.id_proyecto + ")'><i class='fa fa-lock' style='margin-bottom:5px'></i></button>";	
						}	
						table += "<button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar' onclick='eliminarConfirmarClasificacion(" + clasificacion.id_proyecto_clasificacion + ", " + clasificacion.clasificacion + ")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button>";
						table += "</td><tr>"
	            	}
					$(tabla + " tbody").append(table);
	            });
	        }
	    });
	}
	function buscarClasificacionesPorID(tabla, proyecto, id){
		$.ajax({
	        url:document.getElementById('ruta').value + 'Proyectos/buscarClasificacionesID',
	        type:'POST',
	        dataType:'JSON',
	        data: {'proyecto' : proyecto, 'id_proyecto_clasificacion': id},
	        error: function() {
                buscarClasificaciones(tabla, proyecto);
	        },
	        success: function(respuesta){
	            respuesta.forEach(function(clasificacion, index){
	            		var array = [clasificacion.clasificacion, clasificacion.precio];
						clasificacion.status == ""
						table = "<tr id='cl" + clasificacion.id_proyecto_clasificacion + "'><td>" + clasificacion.etapa_nom + "<input type='hidden' class='id_estapa' value='" + clasificacion.etapa + "'></td><td>" + clasificacion.nombre_lista_valor + "<input type='hidden' class='id_clasificacion' value='" + clasificacion.clasificacion + "'><input type='hidden' class='proyecto_clasificacion' value='" + array + "'></td><td text-align: right class='text-right'>$ " 
						table += "<input type='text' style='text-align: right' class='precio' id='p"+clasificacion.id_proyecto_clasificacion+"'<td style='text-align: right' disabled value= ' "+ Intl.NumberFormat("en-IN").format(clasificacion.precio) + "'</td>"
     					table += "<td>"
     					table += "<button type='button' class='btn btn-xs btn-primary waves-effect' title='Editar' onclick='editarClasificacion(\"" + "#p" + clasificacion.id_proyecto_clasificacion + "\")'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></button>";
	            		if(clasificacion.status == 1){
						table += "<button type='button' class='btn btn-xs btn-warning waves-effect' title='Desactivar' onclick='activar_desctivar_clasi(" + clasificacion.id_proyecto_clasificacion + ", " + clasificacion.status + ", " + clasificacion.id_proyecto + ")'><i class='block2 fa fa-unlock' style='margin-bottom:5px'></i></button>";
					}
					if(clasificacion.status == 2){
					table += "<button type='button' class='btn btn-xs btn-warning waves-effect' title='Activar' onclick='activar_desctivar_clasi(" + clasificacion.id_proyecto_clasificacion + ", " + clasificacion.status + ", " + clasificacion.id_proyecto + ")'><i class='fa fa-lock' style='margin-bottom:5px'></i></button>";	
					}	
						table += "<button type='button' class='btn btn-xs btn-danger waves-effect' title='Eliminar' onclick='eliminarConfirmarClasificacion(" + clasificacion.id_proyecto_clasificacion + ", " + clasificacion.clasificacion + ")'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></button>";
						table += "</td><tr>"
					$(tabla + " tbody").append(table);
	            });
	        }
	    });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que hace una busqueda de las operaciones que tiene el rol por cada
		lista vista y mostrar los resultados para su edicion
	*/
	function eliminarConfirmarClasificacion(id, clasificacion){
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
                    url: document.getElementById('ruta').value + "Proyectos/eliminar_clasificacion_proyecto",
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        'id' : clasificacion,
                        'id_proyecto_clasificacion' : id
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                        //mensajes('danger', errores);
                        warning('La Clasificación NO se puede eliminar ya que tiene Registros asociados')
                    },
                    success: function(respuesta){
                        mensajes('success', respuesta);
                        $("#tableClasificacionEditar").find("tbody tr#cl" + id).remove();
                    }
                });
            } else {
                swal("Cancelado", "No se ha eliminado el registro", "error");
            }
        });
	}
	$('#plano_registrar').fileinput({
        theme: 'fa',
        language: 'es',	

        uploadAsync: true,
        showUpload: false, // hide upload button
        showRemove: false,
        uploadUrl: base_url+'uploads/upload/proyecto',//pasar el parametro el tipo para indicar en el controlador la ruta donde guardar
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
/* ------------------------------------------------------------------------------- */




$("#indicador_registrar").on("change", function(){
	//alert($(this).val());

	if ($("#indicador_registrar").is(':checked')) {
		//console.log("si");

		$("#dias_vencidos_registrar, #porcentaje_mora_registrar").removeAttr("disabled").attr("required", "required");
		$("#dias_vencidos_registrar").focus();
		$("#indicador_mora_registrar").val("S");
	}else{
		//console.log("no");
		$("#dias_vencidos_registrar, #porcentaje_mora_registrar").attr("disabled", "disabled").removeAttr("required");
		$("#dias_vencidos_registrar, #porcentaje_mora_registrar").val("");
		$("#indicador_mora_registrar").val("N");
	}
});



$("#indicador_actualizar").on("change", function(){
	//alert($(this).val());
	if ($("#indicador_actualizar").is(':checked')) {
		//console.log("si");

		$("#dias_vencidos_editar, #porcentaje_mora_editar").removeAttr("disabled").attr("required", "required");
		$("#dias_vencidos_editar").focus();
		$("#indicador_mora_actualizar").val("S");
	}else{
		//console.log("no");
		$("#dias_vencidos_editar, #porcentaje_mora_editar").attr("disabled", "disabled").removeAttr("required");
		$("#dias_vencidos_editar, #porcentaje_mora_editar").val("");
		$("#indicador_mora_actualizar").val("N");
	}
});

/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
