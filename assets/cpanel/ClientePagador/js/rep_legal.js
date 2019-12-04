$(document).ready(function(){
	elegirFecha('.fecha');
	telefonoInput('.telefono');
	listar();
	registrar_repLegalCliente();
	actualizar_clientePagador();
	var busqueda = false;
	

	$("input[name=rad_tipoper]").change(function () {
		if($("#tipopersona input[id='moral']").is(':checked')){
			$(".pestana_replegal").show();
		}
		else{
			$(".pestana_replegal").hide()	
		}
	});
	$("input[name=rad_tipoper_editar]").change(function () {
		if($("#tipopersona_editar input[id='moral_editar']").is(':checked')){
			$(".pestana_replegaleditar").show();
		}
		else{
			$(".pestana_replegaleditar").hide()	
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
		var id_cliente = document.getElementById('id_cliente').value;
		var table= $("#tabla").DataTable({
			"destroy":true,
			"stateSave": true,
			"serverSide":false,
			"ajax":{
				"method":"POST",
				"url": url + "ClientePagador/listado_repLegal/"+id_cliente,
				"dataSrc":""
			},
			"columns":[
				{"data": "id_repLegal_cliente_pagador",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data": null,
					render : function(data, type, row) {
						var botones = "";
						if(consultar == 0 )
							botones += "<span class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						if(actualizar == 0 && editable == 1)
							botones += "<span class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						if(data.status == true && actualizar == 0 && editable == 1)
							botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == false && actualizar == 0 && editable == 1)
							botones += "<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						if(borrar == 0  && editable == 1)
		              		botones += "<span class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
		          		return botones;
		          	}
				},
				{"data":"nombre_datos_personales"},
				{"data":"apellido_p_datos_personales"},
				{"data":"apellido_m_datos_personales"},
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
	function nuevoRegistro(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		$('#rfc_img_rep').fileinput('destroy') 
		$("#form_repLegal_registrar")[0].reset();
		$("#tipo_registrar").focus();
		$('#rfc_img_rep').fileinput({
        theme: 'fa',
        language: 'es',	

        uploadAsync: true,
        showUpload: false, // hide upload button
        showRemove: false,
        uploadUrl: base_url+'uploads/upload/proyecto',
        uploadExtraData:{
        	name:$('#rfc_img_rep').attr('id')
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

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_repLegalCliente(){
		enviarFormulario("#form_repLegal_registrar", 'ClientePagador/registrar_repLegalCliente', '#cuadro2');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta
	*/
	function ver(tbody, table){
		base_url = document.getElementById('ruta').value;
		$('#rfc_img_rep_c').fileinput('destroy');
		url_imagen = base_url+'assets/cpanel/ClientePagador/images/'
		$(tbody).on("click", "span.consultar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			if (data.rfc_img != ""){
			rfc_rep = '<img src="'+url_imagen+data.rfc_img+'" class="file-preview-image kv-preview-data">'
			//rfc_rep += '<input name="rfc_img_rep_e" value="'+data.rfc_img+'" type="hidden">'
			}else{rfc_rep = ""}
			$('#rfc_img_rep_c').fileinput({
			        theme: 'fa',
			        language: 'es',	

			        uploadAsync: true,
			        showUpload: false, // hide upload button
			        showRemove: false,
			        uploadUrl: base_url+'uploads/upload/cliente',
			        uploadExtraData:{
			        	name:$('#rfc_img_rep_c').attr('id')
			        },
			        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
			        overwriteInitial: false,
			        maxFileSize: 5000,			
			        maxFilesNum: 1,
			        autoReplace:true,
			        initialPreviewAsData: false,
			        initialPreview: [ 
			        	rfc_rep
			        ],
			        initialPreviewConfig: [
			             {caption: data.rfc_img, downloadUrl: url_imagen+data.rfc_img, url: base_url+"uploads/delete", key: data.rfc_img}
			        ],

			        //allowedFileTypes: ['image', 'video', 'flash'],
			        slugCallback: function (filename) {
			            return filename.replace('(', '_').replace(']', '_');
			        }
			    }).on("filebatchselected", function(event, files) {
			      $(event.target).fileinput("upload");

			    }).on("filebatchuploadsuccess",function(form, data){
			      
			      //console.log(data.response)
			    })

			$('#descargar_mostrar').attr('href',url_imagen+data.rfc_img);
			$('#descargarD_mostrar').attr('href',url_imagen+data.rfc_img).attr('download', data.rfc_img);
			//document.getElementById('rfc_img_rep_c').value = data.rfc_img;

			document.getElementById('nombre_respresentante_m').value = data.nombre_datos_personales;
			document.getElementById('apellido_paterno_rep_m').value = data.apellido_p_datos_personales;
			document.getElementById('apellido_materno_rep_m').value = data.apellido_m_datos_personales;
			document.getElementById('rfc_representante_m').value = data.rfc_datos_personales;
			document.getElementById('curp_rep_legal_mostrar').value = data.curp_datos_personales;
			document.getElementById('correo_rep_legal_m').value = data.correo_rep_legal;
			document.getElementById('telf_rep_legal_m').value = data.telf_rep_legal;
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar
	*/
	function editar(tbody, table){
		base_url = document.getElementById('ruta').value;
		$('#rfc_img_rep_e').fileinput('destroy');
		url_imagen = base_url+'assets/cpanel/ClientePagador/images/'
		base_url = document.getElementById('ruta').value;
		url_imagen = base_url+'assets/cpanel/ClientePagador/images/'
		$("#form_repLegal_editar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			//rfc_rep$('#descargar').attr('href',url_imagen+data.rfc_img);
			document.getElementById('imagen_editar').value = data.rfc_img;
			document.getElementById('id_repLegal_clientePagador_actualizar').value = data.id_repLegal_cliente_pagador;
			document.getElementById('id_datos_personales').value = data.id_datos_personales;
			document.getElementById('nombre_respresentante_e').value = data.nombre_datos_personales;
			document.getElementById('apellido_paterno_rep_e').value = data.apellido_p_datos_personales;
			document.getElementById('apellido_materno_rep_e').value = data.apellido_m_datos_personales;
			document.getElementById('rfc_representante_e').value = data.rfc_datos_personales;
			document.getElementById('curp_rep_legal_e').value = data.curp_datos_personales;
			document.getElementById('correo_rep_legal_e').value = data.correo_rep_legal;
			document.getElementById('telf_rep_legal_e').value = data.telf_rep_legal;
			if (data.rfc_img != ""){
			rfc_rep = '<img src="'+url_imagen+data.rfc_img+'" class="file-preview-image kv-preview-data">'
			rfc_rep += '<input name="rfc_img_rep_e" value="'+data.rfc_img+'" type="hidden">'
			}else{rfc_rep = ""}
			$('#rfc_img_rep_e').fileinput({
			        theme: 'fa',
			        language: 'es',	

			        uploadAsync: true,
			        showUpload: false, // hide upload button
			        showRemove: false,
			        uploadUrl: base_url+'uploads/upload/cliente',
			        uploadExtraData:{
			        	name:$('#rfc_img_rep_e').attr('id')
			        },
			        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
			        overwriteInitial: false,
			        maxFileSize: 5000,			
			        maxFilesNum: 1,
			        autoReplace:true,
			        initialPreviewAsData: false,
			        initialPreview: [ 
			        	rfc_rep
			        ],
			        initialPreviewConfig: [
			             {caption: data.rfc_img, downloadUrl: url_imagen+data.rfc_img, url: base_url+"uploads/delete", key: data.rfc_img}
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
			cuadros('#cuadro1', '#cuadro4');
			
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_clientePagador(){
		enviarFormulario("#form_repLegal_editar",'ClientePagador/actualizar_repLegalCliente', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('ClientePagador/eliminar_repLegal', data.id_repLegal_cliente_pagador, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('ClientePagador/statusRepLegal', data.id_repLegal_cliente_pagador, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('ClientePagador/statusRepLegal', data.id_repLegal_cliente_pagador, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */

/*
        Funcion que busca los codigos
    */
    function buscarCodigos(codigo, type){
    	if (!busqueda){
    		busqueda = true;
	    	if(type == 'create'){
	    		var estado = 'estado_registrar',
		    		ciudad = 'ciudad_registrar',
		    		municipio = 'municipio_registrar',
		    		colonia = 'colonia_registrar';
	    	}else if(type == 'edit'){
	    		var estado = 'estado_actualizar',
		    		ciudad = 'ciudad_actualizar',
		    		municipio = 'municipio_actualizar',
		    		colonia = 'colonia_actualizar';
	    	}
	        eliminarOptions(estado);
	        eliminarOptions(ciudad);
	        eliminarOptions(municipio);
	        eliminarOptions(colonia);
	        if(codigo.length>4){
	            var url=document.getElementById('ruta').value;
	            $.ajax({
	                url:url+'Usuarios/buscar_codigos',
	                type:'POST',
	                dataType:'JSON',
	                data:{'codigo':codigo},
	                beforeSend: function(){
	                    mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
	                },
	                error: function (repuesta) {
	                    mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');         
	                },
	                success: function(respuesta){
	                    $("#alertas").html('');
	                    respuesta.estados.result_object.forEach(function(campo, index){
	                        agregarOptions("#" + estado, campo.d_estado, campo.d_estado);
	                    });
	                    respuesta.ciudades.result_object.forEach(function(campo, index){
	                        if(campo.d_ciudad!=""){
	                            agregarOptions('#' + ciudad, campo.d_ciudad, campo.d_ciudad);
	                            $("#" + ciudad).css('border-color', '#ccc');
	                        }else{
	                            agregarOptions('#' + ciudad, "N/A", "NO APLICA");
	                            $("#" + ciudad).css('border-color', '#a94442');
	                        }
	                    });
	                    respuesta.municipios.result_object.forEach(function(campo, index){
	                        agregarOptions("#"+ municipio, campo.d_mnpio, campo.d_mnpio);
	                    });
	                    respuesta.colonias.result_object.forEach(function(campo, index){
	                        agregarOptions("#"+ colonia, campo.id_codigo_postal, campo.d_asenta);
	                    });
	                }
	            });
	        }else{
	            warning('Debe colocar al menos 5 caracteres para continuar.');
	        }
	    }
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */

/*
        funcion que detecta la tecla enter para la busqueda de los codigos postales.
    */
    $("#codigo_postal_registrar").keydown(function(e) {
    	var busqueda = false
        if(e.which == 13) {
            if (!busqueda){
                buscarCodigos(document.getElementById('codigo_postal_registrar').value, 'create');
                busqueda = true;
            }

        }
    });
    $("#codigo_postal_editar").keydown(function(e) {
    	var busqueda = false
        if(e.which == 13) {
            if (!busqueda){
                buscarCodigos(document.getElementById('codigo_postal_editar').value, 'edit');
                busqueda = true;
            }

        }

    });

