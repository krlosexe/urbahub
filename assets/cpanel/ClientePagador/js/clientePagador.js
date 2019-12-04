$(document).ready(function(){
	elegirFecha_Cumple('.fecha');
    telefonoInput('.telefono');
	listar();
	registrar_clientePagador();
	actualizar_clientePagador();
	verificarRadio();
	
	var busqueda = false;
	$(".moralf").removeAttr("required")	
  	
	$('#domicilio_fiscal_img').change(function(){
		$(".guardado").removeAttr("required")
	})
	/*$('#clabe_registrar').click(function(){
		$(".guardado").attr("required", true)	
	})*/
	

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
				/*"url": url + "ClientePagador/listado_clientePagador",*/
				"url": url + "ClientePagador/listado_clientePagador_performance",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_cliente",
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
							botones += "<span id='editar 'class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						if(data.status == true && actualizar == 0)
							botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == false && actualizar == 0)
							botones += "<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						if(borrar == 0)
		              		botones += "<span class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
		          		return botones;
		          	}
				},
				{"data":"nombre_datos_personales"},
				{"data":"rfc_datos_personales",
					render : function(data, type, row) {
						return data.toUpperCase()
				    }
				},
				{"data":"tipo_persona_cliente"},
				{"data":"name_empresa"},
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
		$("[type='file']").fileinput('destroy');
		$("#alertas").css("display", "none");
		cuadros("#cuadro1", "#cuadro2");
			$(".pestana_replegal").hide()
			$("#personaMoral").hide();
			$("#personaFisica").show();
			$(".moralf").removeAttr("required")	
		$("#form_clientePagador_registrar")[0].reset();
		$("#form_clientePa_actualizar")[0].reset();
		/*------------------------------------------------*/
		eliminarOptions3("estado_registrar");
        eliminarOptions3("ciudad_registrar");
        eliminarOptions3("municipio_registrar");
        eliminarOptions3("colonia_registrar");
        eliminarOptions3("estado_editar");
        eliminarOptions3("ciudad_editar");
        eliminarOptions3("municipio_editar");
        eliminarOptions3("colonia_editar");
		$("#estado_registrar,#ciudad_registrar,#municipio_registrar,#colonia_registrar,#estado_editar,#ciudad_editar,#municipio_editar,#colonia_editar").click();
		

		GetClientes("#empresa_pertenece");
		/*------------------------------------------------*/
		$("#nombre_cliente").focus();
		var $file;	
			var x = [];	
			$(function(){
				base_url = document.getElementById('ruta').value;
			    $file = $("[type='file']").not('.fileeditar')	
			    $file.each(function(i,el){
			    /*alert(el)
			    alert($(el).attr('id'))*/
			    x[i] = $(el).fileinput({
			        theme: 'fa',
			        language: 'es',	

			        uploadAsync: true,
			        showUpload: false, // hide upload button
			        showRemove: false,
			        uploadUrl: base_url+'uploads/upload/cliente',
			        uploadExtraData:{
			        	name:$(el).attr('id')
						//name:$('#cliente_img').attr('id')
			        },
			        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg"],
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
			      
			      console.log(data.response)
			    });
			    })
			    
			})



			$("#cliente_img").fileinput({
				theme: 'fa',
				language: 'es',	

				uploadAsync: true,
				showUpload: false, // hide upload button
				showRemove: false,
				uploadUrl: base_url+'uploads/upload/cliente',
				uploadExtraData:{
					name:$("#cliente_img").attr('id')
					//name:$('#cliente_img').attr('id')
				},
				allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg"],
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
			  
			  console.log(data.response)
			});

			
	}





	var ObjCliente =  [];
	function GetClientes(select){
			
		getClientesObj("ClientePagador/listado_clientePagador_performance")

		var optionsClient = "";


		$(select+" option").remove();
		$(select).append($('<option>',
		{
			value: "",
			text : "Seleccione"
		}));

		$.each(ObjCliente, function (i, item) { 
			if(item.tipo_persona_cliente == "MORAL"){
				if (item.status == 1) {
					$(select).append($('<option>',
					{
						value: item.id_cliente,
						text : item.nombre_datos_personales+" "+item.apellido_p_datos_personales
					}));
				}
			}
		});
	}


	function getClientesObj(controlador){
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		$.ajax({
			url:url+controlador,
			type:"POST",
			dataType:'JSON',
			async: false,
			success: function(respuesta){
				ObjCliente = respuesta
			}

		});
	}


	/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_clientePagador(){
		/*$imagen           = ($('#rfc_img').val()!="") ? $('#rfc_img').val() : '';
		$imagenDomFiscal  = ($('#domicilio_fiscal_img').val()!="") ? $('#domicilio_fiscal_img').val() : '';
       	$imagenActa  = ($('#acta_img_r').val()!="") ? $('#acta_img_r').val() : '';
       	$imagenMoral  = ($('#rfc_imag_mo').val()!="") ? $('#rfc_imag_mo').val() : '';
       	$imagenLegal  = ($('#rfc_img_rep').val()!="") ? $('#rfc_img_rep').val() : '';*/

		enviarFormulario2("#form_clientePagador_registrar", 'ClientePagador/registrar_clientePagador', '#cuadro2');
	}





	 function enviarFormulario2(form, controlador, cuadro){
        $(form).submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
            var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
            var method = $(this).attr('method'); //obtiene el method del formulario


            var objetoImgProfile = [];
            $(".img_profile .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
            	var img = [];
            	img.push($(this).attr("title"));
            	objetoImgProfile.push(img);
			});

			var objetoNIdeintificacion = [];
            $(".img_n_identificacion .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
            	var img = [];
            	img.push($(this).attr("title"));
            	objetoNIdeintificacion.push(img);
			});




			var objetoDomicilio = [];
            $("#domicilio_regis .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
            	var img = [];
            	img.push($(this).attr("title"));
            	objetoDomicilio.push(img);
			});



			var objetoActa = [];
            $("#img_acta_constitutiva .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
            	var img = [];
            	img.push($(this).attr("title"));
            	objetoActa.push(img);
			});






			formData.append('imgProfile', objetoImgProfile[0]);
			formData.append('img_n_identificacion', objetoNIdeintificacion[0]);
			formData.append('img_domicilio', objetoDomicilio[0]);
			formData.append('img_acta_constitutiva', objetoActa[0]);

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
                        if(cuadro!="")
                            listar(cuadro);
                    }

                }

            });
        });
    }



	function enviarFormularioCliente(form, controlador, cuadro){
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
    }
/* ------------------------------------------------------------------------------- */
   function verificarRadio()
   {
   	   	$("input[name=rad_tipoper]").change(function () {
		if($("#tipopersona input[id='moral']").is(':checked')){
			$(".pestana_replegal").show();
			$("#personaMoral").show();
			$("#personaFisica").hide();
			$(".fisicaf").removeAttr("required")	
			$(".moralf").attr("required", true)
			$("#razon_social").focus()		
		}
		else{
			$(".pestana_replegal").hide()
			$("#personaMoral").hide();
			$("#personaFisica").show();
			$(".moralf").removeAttr("required")
			$("#nombre_cliente").focus();	
		}
	});
	$("input[name=rad_tipoper_editar]").change(function () {
		if($("#tipopersona_editar input[id='moral_editar']").is(':checked')){
			$(".pestana_replegaleditar").show();
			$("#personaMoral_e").show();
			$("#personaFisica_e").hide();
			$(".fisicae").removeAttr("required")	
		}
		else{
			$(".pestana_replegaleditar").hide()
			$("#personaMoral_e").hide();
			$("#personaFisica_e").show();
			$(".morale").removeAttr("required")	
		}
	});

	   }

/* ------------------------------------------------------------------------------- */
	/*

		Funcion que muestra el cuadro3 para la consulta
	*/
	function ver(tbody, table){
		$("#form_clientePagador_registrar")[0].reset();

		GetClientes("#empresa_pertenece_view");


		$('#cliente_img_consultar').fileinput('destroy');
		$('#cliente_img_moral_consultar').fileinput('destroy');
		$('#rfc_img_consultar').fileinput('destroy');
		$('#rfc_imag_mo_c').fileinput('destroy');
		$('#acta_img_c').fileinput('destroy');
		$('#domicilio_fiscal_img_c').fileinput('destroy');
		$("#form_clientePa_actualizar")[0].reset();
		base_url = document.getElementById('ruta').value;
		url_imagen = base_url+'assets/cpanel/ClientePagador/images/'
		$(tbody).on("click", "span.consultar", function(){

			$("#alertas").css("display", "none");
			var data = table.row( $(this).parents("tr") ).data();
			//--
			/*
			*	Envio un ajax request para cosumir el servicio ag
			*/
			data_cliente = armarDataClienteAg(data,"consultar");
			console.log(data_cliente);
			//--
			/*
			*
			*/
			if(data.rfc_img != ""){

				var ext = data.rfc_img.split('.');
				if (ext[1] == "pdf") {
                	rfc = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.rfc_img+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
            	}else{
				 	rfc = '<img src="'+url_imagen+data.rfc_img+'" class="file-preview-image kv-preview-data">'
	            }

	 		// rfc += '<input name="plano_editar" value="'+data.plano+'" type="hidden">'
	 		}else{rfc = ""}

      $('#rfc_img_consultar').fileinput({
        theme: 'fa',
        language: 'es',	

        uploadAsync: true,
        showUpload: false, // hide upload button
        showRemove: false,
        uploadUrl: base_url+'uploads/upload/productos',
        uploadExtraData:{
        	name:$('#rfc_img_consultar').attr('id')
        },
        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
        overwriteInitial: false,
        maxFileSize: 5000,			
        maxFilesNum: 1,
        autoReplace:true,
        initialPreviewAsData: false,
        initialPreview: [ 
        	rfc
        ],
        initialPreviewConfig: [
            {caption: data.rfc_img,downloadUrl: url_imagen+data.rfc_img  ,url: base_url+"uploads/delete", key: data.rfc_img}
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
    if(data.rfc_img != ""){


    	    var ext = data.rfc_img.split('.');
				if (ext[1] == "pdf") {
                	rfc = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.rfc_img+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
            	}else{
			 		rfc = '<img src="'+url_imagen+data.rfc_img+'" class="file-preview-image kv-preview-data">'
            	}
	 		// rfc += '<input name="plano_editar" value="'+data.plano+'" type="hidden">'
	 		}else{rfc = ""}

      $('#rfc_imag_mo_c').fileinput({
        theme: 'fa',
        language: 'es',	

        uploadAsync: true,
        showUpload: false, // hide upload button
        showRemove: false,
        uploadUrl: base_url+'uploads/upload/productos',
        uploadExtraData:{
        	name:$('#rfc_imag_mo_c').attr('id')
        },
        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
        overwriteInitial: false,
        maxFileSize: 5000,			
        maxFilesNum: 1,
        autoReplace:true,
        initialPreviewAsData: false,
        initialPreview: [ 
        	rfc
        ],
        initialPreviewConfig: [
            {caption: data.rfc_img,downloadUrl: url_imagen+data.rfc_img  ,url: base_url+"uploads/delete", key: data.rfc_img}
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
      if(data.acta_img != ""){


      	    var ext = data.acta_img.split('.');
				if (ext[1] == "pdf") {
                	acta = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.acta_img+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
            	}else{

			 		acta = '<img src="'+url_imagen+data.acta_img+'" class="file-preview-image kv-preview-data">'
            	}


	 		// acta += '<input name="plano_editar" value="'+data.plano+'" type="hidden">'
	 		}else{acta = ""}

      $('#acta_img_c').fileinput({
        theme: 'fa',
        language: 'es',	

        uploadAsync: true,
        showUpload: false, // hide upload button
        showRemove: false,
        uploadUrl: base_url+'uploads/upload/productos',
        uploadExtraData:{
        	name:$('#acta_img_c').attr('id')
        },
        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
        overwriteInitial: false,
        maxFileSize: 5000,			
        maxFilesNum: 1,
        autoReplace:true,
        initialPreviewAsData: false,
        initialPreview: [ 
        	acta
        ],
        initialPreviewConfig: [
            {caption: data.acta_img,downloadUrl: url_imagen+data.acta_img  ,url: base_url+"uploads/delete", key: data.acta_img}
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
    if(data.dominio_fiscal_img != ""){

	    		if (ext[1] == "pdf") {
	                	domicilioimg = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.dominio_fiscal_img+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
	            	}else{

 					domicilioimg = '<img src="'+url_imagen+data.dominio_fiscal_img+'" class="file-preview-image kv-preview-data">'
            	}


	//domicilioimg += '<input name="domicilio_fiscal_img_c" value="'+data.dominio_fiscal_img+'" type="hidden">'
   }else{domicilioimg = ""}
    $('#domicilio_fiscal_img_c').fileinput({
        theme: 'fa',
        language: 'es',	

        uploadAsync: true,
        showUpload: false, // hide upload button
        showRemove: false,
        uploadUrl: base_url+'uploads/upload/cliente',
        uploadExtraData:{
        	name:$('#domicilio_fiscal_img_c').attr('id')
        },
        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
        overwriteInitial: false,
        maxFileSize: 5000,			
        maxFilesNum: 1,
        autoReplace:true,
        initialPreviewAsData: false,
        initialPreview: [ 
        	domicilioimg
        ],
        initialPreviewConfig: [
           {caption: data.dominio_fiscal_img, downloadUrl: url_imagen+data.dominio_fiscal_img, url: base_url+"uploads/delete", key: data.dominio_fiscal_img}  
            
        ],
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    }).on("filebatchselected", function(event, files) {
      $(event.target).fileinput("upload");

    }).on("filebatchuploadsuccess",function(form, data){
      
      console.log(data.response)
    });
			console.log(data)
			// *****   datos datos generales ****
			
			if (data.tipo_persona_cliente == "FISICA"){ 
				//--------------------------------------------
				//alert(data.imagenCliente)
				if(data.imagenCliente != "undefined"){
					var ext = data.imagenCliente.split('.');
					if (ext[1] == "pdf") {
	                	imagenCliente = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.imagenCliente+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
	            	}else{
					   imagenCliente = '<img src="'+url_imagen+data.imagenCliente+'" class="file-preview-image kv-preview-data">'
	            	}
		 		}else{imagenCliente = ""}

				$('#cliente_img_consultar').fileinput({
					theme: 'fa',
					language: 'es',	

					uploadAsync: true,
					showUpload: false, // hide upload button
					showRemove: false,
					uploadUrl: base_url+'uploads/upload/productos',
					uploadExtraData:{
						name:$('#cliente_img_consultar').attr('id')
					},
					allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
					overwriteInitial: false,
					maxFileSize: 5000,			
					maxFilesNum: 1,
					autoReplace:true,
					initialPreviewAsData: false,
					initialPreview: [ 
						imagenCliente
					],
					initialPreviewConfig: [
					    {caption: data.imagenCliente,downloadUrl: url_imagen+data.imagenCliente  ,url: base_url+"uploads/delete", key: data.rfc_img}
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
				//--------------------------------------------
				$('#fisica_mostrar').attr('checked', true)
				$(".pestana_replegalMostrar").hide();	
				$("#datosGeneralFisica").show();
				$("#datosGeneralMoral").hide();				
			}			
			if (data.tipo_persona_cliente == "MORAL"){
				//--------------------------------------------
				//if(data.imagenCliente != ""){


					var ext = data.imagenCliente.split('.');
					if (ext[1] == "pdf") {
                	imagenCliente = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.imagenCliente+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
            	}else{
					imagenCliente = '<img src="'+url_imagen+data.imagenCliente+'" class="file-preview-image kv-preview-data">'

            	} 


		 		//rfc += '<input name="plano_editar" value="'+data.plano+'" type="hidden">'
		 		//}else{imagenCliente = ""}
		 		//alert(imagenCliente)
				$('#cliente_img_moral_consultar').fileinput({
					theme: 'fa',
					language: 'es',	

					uploadAsync: true,
					showUpload: false, // hide upload button
					showRemove: false,
					uploadUrl: base_url+'uploads/upload/cliente',
					uploadExtraData:{
						name:$('#cliente_img_moral_consultar').attr('id')
					},
					allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
					overwriteInitial: false,
					maxFileSize: 5000,			
					maxFilesNum: 1,
					autoReplace:true,
					initialPreviewAsData: false,
					initialPreview: [ 
						imagenCliente
					],
					initialPreviewConfig: [
					    {caption: data.imagenCliente,downloadUrl: url_imagen+data.imagenCliente  ,url: base_url+"uploads/delete", key: data.imagenCliente}
					],

					//allowedFileTypes: ['image', 'video', 'flash'],
					slugCallback: function (filename) {
					    return filename.replace('(', '_').replace(']', '_');
					}
					}).on("filebatchselected", function(event, files) {
						$(event.target).fileinput("upload");

					}).on("filebatchuploadsuccess",function(form, data){

					console.log(data.response)
				});
				$('#descargarV_mostrar').attr('href',url_imagen+data.rfc_img);

				//--------------------------------------------
				$('#moral_mostrar').attr('checked', true);
				$(".pestana_replegalMostrar").show();
				$("#datosGeneralMoral").show();
				$("#datosGeneralFisica").hide();	
			} 

			//-----------------------------------------------------------------------------------
			$('#descargarV_mostrar').attr('href',url_imagen+data.rfc_img);
			$('#descargar_mostrar').attr('href',url_imagen+data.rfc_img).attr('download', data.rfc_img);
			$('#domicilioImgMostrar').attr('href',url_imagen+data.dominio_fiscal_img);
			$('#domicilioImgDMostrar').attr('href',url_imagen+data.dominio_fiscal_img).attr('download', data.dominio_fiscal_img);
			$('#actaMostrar').attr('href',url_imagen+data.acta_img);
			$('#actaDMostrar').attr('href',url_imagen+data.acta_img).attr('download', data.acta_img)
			$('#RFCMoralMostrar').attr('href',url_imagen+data.rfc_img)
			$('#RFCMoralDMostrar').attr('href',url_imagen+data.rfc_img).attr('download', data.rfc_img);
			//document.getElementById('img_rfc_mostrar').value = data.rfc_img;
			document.getElementById('nombre_cliente_mostrar').value = data.nombre_datos_personales;
			document.getElementById('apellido_paterno_mostrar').value = data.apellido_p_datos_personales;
			document.getElementById('apellido_materno_mostrar').value = data.apellido_m_datos_personales;
			document.getElementById('rfc_mostrar').value = data.rfc_datos_personales;
			document.getElementById('curp_datos_personales_mostrar').value = data.curp_datos_personales;
			document.getElementById('actividad_economica_mostrar').value = data.actividad_economica;


			$("#ficha_view span").text(data.nombre_datos_personales+" "+data.apellido_p_datos_personales+" "+data.apellido_m_datos_personales);


			if(data.imagenCliente != "undefined"){
				$("#img_profile_view").css("display", "block");
				$("#img_profile_view").attr("src", url_imagen+data.imagenCliente);
			}else{
				$("#img_profile_view").css("display", "none");
			}
			//$("#actividad_economica_mostrar option[value='" + data.actividad_e_cliente + "']").attr("selected","selected");
			
			if(data.fecha_nac_datos_personales!="")
				document.getElementById('fecha_nac_datos_mostrar').value = cambiarFormatoFecha(data.fecha_nac_datos_personales);
			else
				document.getElementById('fecha_nac_datos_mostrar').value =""

			document.getElementById('correo_cliente_mostrar').value = data.correo_contacto;
			document.getElementById('telefono_cliente_mostrar').value = data.telefono_principal_contacto;
			document.getElementById('nacionalidad_cliente_mostrar').value = data.pais_nacionalidad;
			document.getElementById('pais_origen_mostrar').value = data.pais_origen;
			
			//document.getElementById('acta_img').value = data.acta_img;
			//document.getElementById('rfc_moral_c_img').value = data.rfc_img;
			document.getElementById('razon_social_c').value = data.nombre_datos_personales;
			document.getElementById('rfc_moral_c').value = data.rfc_datos_personales;
			document.getElementById('fecha_cons_c').value = cambiarFormatoFecha(data.fecha_nac_datos_personales);
			document.getElementById('acta_constutiva_c').value = data.acta_constitutiva;
			document.getElementById('giro_mercantil_c').value = data.giro_merca_desc;
			document.getElementById('correo_moral_c').value = data.correo_contacto;
			document.getElementById('telefono_moral_c').value = data.telefono_principal_contacto;
			//document.getElementById('domicilo_mostrar_img').value = data.dominio_fiscal_img;
			//$("#nacionalidad_cliente_mostrar option[value='" + data.nacionalidad_cliente + "']").attr("selected","selected");
			//$("#pais_origen_mostrar option[value='" + data.pais_cliente + "']").attr("selected","selected");
			// ***** datos Domicilio ****	
			document.getElementById('calle_contacto_mostrar').value = data.calle_contacto;
			document.getElementById('exterior_contacto_mostrar').value = data.exterior_contacto;
			document.getElementById('interior_contacto_mostrar').value = data.interior_contacto;


			$("#empresa_pertenece_view").val(data.empresa_pertenece).attr("disabled", "disabled")
			/*document.getElementById('codigo_postal_mostrar').value=data.d_codigo;
			document.getElementById('estado_mostrar').value=data.d_estado;
			if(data.ciudad!=""){
				document.getElementById('ciudad_mostrar').value=data.d_ciudad;
			}else{
				document.getElementById('ciudad_mostrar').value='NO APLICA';
			}
			document.getElementById('municipio_mostrar').value=data.d_mnpio;
			document.getElementById('colonia_mostrar').value=data.d_asenta;*/
			
			cuadros('#cuadro1', '#cuadro3');
			
			urlcuentas = base_url+'ClientePagador/cuentas/'+ data.id_cliente
			$('#iframeCuentam').attr('src', urlcuentas);
			urlRepLegal = base_url+'ClientePagador/rep_legal/'+data.id_cliente
			$('#iframeRepLegalm').attr('src',urlRepLegal)
			urlContacto = base_url+'ClientePagador/contacto/'+data.id_cliente
			$('#iframeContactom').attr('src',urlContacto)
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
/* 
		Funcion que muestra el cuadro4 para editar: Anterior se tuvo que cambiar esto ya que al pulsar el boton de editar debe dispararse un ajax request al controlador y traerse de nuevo al info
*/
	function editar_anterior(tbody, table){
		//verificarRadio()
		//cargar_elementos_select();
		$('#fisica_editar').attr('checked', false)
		$('#moral_editar').attr('checked', false)
		$('#rfc_img_editar').fileinput('destroy');
		$('#acta_img_e').fileinput('destroy');
		$('#acta_img_e').fileinput('destroy');
		$('#domicilio_fiscal_img_e').fileinput('destroy');
		$('#rfc_imag_mo_e').fileinput('destroy');
		$('#acta_img_e').fileinput('destroy');
		$("#form_clientePagador_registrar")[0].reset();
		$("#form_clientePa_actualizar")[0].reset();
		base_url = document.getElementById('ruta').value;
		url_imagen = base_url+'assets/cpanel/ClientePagador/images/'
		$(tbody).on("click", "span.editar", function(){
		var data = table.row( $(this).parents("tr") ).data();
		//console.log(data)
		$("#alertas").css("display", "none");
		if(data.rfc_img != ""){

			var ext = data.rfc_img.split('.');

					if (ext[1] == "pdf") {
                	rfcimg = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.rfc_img+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
	  				
            	}else{
					rfcimg = '<img src="'+url_imagen+data.rfc_img+'" class="file-preview-image kv-preview-data">'
            	}
					rfcimg += '<input name="rfc_img_editar" value="'+data.rfc_img+'" type="hidden">'
   		}else{ rfcimg = ""}

    $('#rfc_img_editar').fileinput({
        theme: 'fa',
        language: 'es',	

        uploadAsync: true,
        showUpload: false, // hide upload button
        showRemove: false,
        uploadUrl: base_url+'uploads/upload/cliente',
        uploadExtraData:{
        	name:$('#rfc_img_editar').attr('id')
        },
        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
        overwriteInitial: false,
        maxFileSize: 5000,			
        maxFilesNum: 1,
        autoReplace:true,
        initialPreviewAsData: false,
        initialPreview: [ 
        	rfcimg
        ],
        initialPreviewConfig: [
            {caption: data.rfc_img,downloadUrl: url_imagen+data.rfc_img  ,url: base_url+"uploads/delete", key: data.rfc_img}
        ],

        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    }).on("filebatchselected", function(event, files) {
      $(event.target).fileinput("upload");

    }).on("filebatchuploadsuccess",function(form, data){
      
      console.log(data.response)
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
    if(data.dominio_fiscal_img != ""){
	 	domicilioimg = '<img src="'+url_imagen+data.dominio_fiscal_img+'" class="file-preview-image kv-preview-data">'
		domicilioimg += '<input name="domicilio_fiscal_img_e" value="'+data.dominio_fiscal_img+'" type="hidden">'
	}else{domicilioimg = ""}
    $('#domicilio_fiscal_img_e').fileinput({
        theme: 'fa',
        language: 'es',	

        uploadAsync: true,
        showUpload: false, // hide upload button
        showRemove: false,
        uploadUrl: base_url+'uploads/upload/cliente',
        uploadExtraData:{
        	name:$('#domicilio_fiscal_img_e').attr('id')
        },
        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
        overwriteInitial: false,
        maxFileSize: 5000,			
        maxFilesNum: 1,
        autoReplace:true,
        initialPreviewAsData: false,
        initialPreview: [ 
        	domicilioimg
        ],
        initialPreviewConfig: [
           {caption: data.dominio_fiscal_img, downloadUrl: url_imagen+data.dominio_fiscal_img, url: base_url+"uploads/delete", key: data.dominio_fiscal_img}  
            
        ],
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    }).on("filebatchselected", function(event, files) {
      $(event.target).fileinput("upload");

    }).on("filebatchuploadsuccess",function(form, data){
      
      console.log(data.response)
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
	if (data.tipo_persona_cliente == "FISICA"){ 
			$('#fisica_editar').attr('checked', true)
			$(".pestana_replegaleditar").hide();
			$("#personaFisica_e").show();
			$("#personaMoral_e").hide();
			$(".morale").removeAttr("required");

		$('#descargar').attr('href',url_imagen+data.rfc_img);
		document.getElementById('nombre_cliente_editar').value = data.nombre_datos_personales;
		document.getElementById('apellido_paterno_editar').value = data.apellido_p_datos_personales;
		document.getElementById('apellido_materno_editar').value = data.apellido_m_datos_personales;
		document.getElementById('rfc_editar').value = data.rfc_datos_personales;
		document.getElementById('rfc_moral_e').value = data.rfc_datos_personales;

		document.getElementById('curp_datos_personales_editar').value = data.curp_datos_personales;
		$("#actividad_economica_editar option[value='" + data.actividad_e_cliente + "']").prop("selected",true);
		document.getElementById('fecha_nac_datos_editar').value = cambiarFormatoFecha(data.fecha_nac_datos_personales);
				
		document.getElementById('correo_cliente_editar').value = data.correo_contacto;
		document.getElementById('telefono_cliente_editar').value = data.telefono_principal_contacto;
		$("#nacionalidad_cliente_editar option[value='" + data.nacionalidad_datos_personales + "']").prop("selected",true);
		$("#pais_origen_editar option[value='" + data.pais_cliente + "']").prop("selected",true);

	}		
	if (data.tipo_persona_cliente == "MORAL"){
		$('#moral_editar').attr('checked', true);
		$(".pestana_replegaleditar").show();
		$("#personaMoral_e").show();
		$("#personaFisica_e").hide();
		$(".fisicae").removeAttr("required");
		document.getElementById('razon_social_e').value = data.nombre_datos_personales;
		document.getElementById('rfc_moral_e').value = data.rfc_datos_personales;
		document.getElementById('rfc_editar').value = data.rfc_datos_personales;
		document.getElementById('fecha_cons_e').value = cambiarFormatoFecha(data.fecha_nac_datos_personales);
		document.getElementById('acta_constutiva_e').value = data.acta_constitutiva;
		$("#giro_mercantil_e option[value='" + data.giro_mercantil + "']").prop("selected",true);
		document.getElementById('correo_moral_e').value = data.correo_contacto;
		document.getElementById('telefono_moral_e').value = data.telefono_principal_contacto;
	}
	if(data.rfc_img != ""){
		rfcimgmoral = '<img src="'+url_imagen+data.rfc_img+'" class="file-preview-image kv-preview-data">'
		rfcimgmoral += '<input name="rfc_imag_mo_e" value="'+data.rfc_img+'" type="hidden">'
		}else{rfcimgmoral = ""}	   
	    $('#rfc_imag_mo_e').fileinput({
	        theme: 'fa',
	        language: 'es',	

	        uploadAsync: true,
	        showUpload: false, // hide upload button
	        showRemove: false,
	        uploadUrl: base_url+'uploads/upload/cliente',
	        uploadExtraData:{
	        	name:$('#rfc_imag_mo_e').attr('id')
	        },
	        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
	        overwriteInitial: false,
	        maxFileSize: 5000,			
	        maxFilesNum: 1,
	        autoReplace:true,
	        initialPreviewAsData: false,
	        initialPreview: [ 
	        	rfcimgmoral
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
	      
	      console.log(data.response)
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
   if(data.acta_img != "" && data.acta_img != null){
		actaimg = '<img src="'+url_imagen+data.acta_img+'" class="file-preview-image kv-preview-data">'
		actaimg += '<input name="acta_img_e" value="'+data.acta_img+'" type="hidden">'
		  }else {actaimg = ""} 
	    $('#acta_img_e').fileinput({
	        theme: 'fa',
	        language: 'es',	

	        uploadAsync: true,
	        showUpload: false, // hide upload button
	        showRemove: false,
	        uploadUrl: base_url+'uploads/upload/cliente',
	        uploadExtraData:{
	        	name:$('#acta_img_e').attr('id')
	        },
	        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
	        overwriteInitial: false,
	        maxFileSize: 5000,			
	        maxFilesNum: 1,
	        autoReplace:true,
	        initialPreviewAsData: false,
	        initialPreview: [ 
	        	actaimg
	        ],
	        initialPreviewConfig: [
	            {caption: data.acta_img, downloadUrl: url_imagen+data.acta_img, url: base_url+"uploads/delete", key: data.acta_img} 
	            
	        ],

	        //allowedFileTypes: ['image', 'video', 'flash'],
	        slugCallback: function (filename) {
	            return filename.replace('(', '_').replace(']', '_');
	        }
	    }).on("filebatchselected", function(event, files) {
	      $(event.target).fileinput("upload");

	    }).on("filebatchuploadsuccess",function(form, data){
	      
	      console.log(data.response)
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

		$('#domicilioImgEditar').attr('href',url_imagen+data.dominio_fiscal_img);
		document.getElementById('imagen_editar').value = data.rfc_img;
		document.getElementById('imagen_domicilio_e').value = data.dominio_fiscal_img;
		document.getElementById('imagen_acta_e').value = data.acta_img;
		document.getElementById('id_clientePagador_actualizar').value = data.id_cliente;
		document.getElementById('id_contacto').value = data.id_contacto;
		document.getElementById('id_datos_personales').value = data.id_datos_personales;
		document.getElementById('id_codigo_postal').value = data.id_codigo_postal;

		// ***** datos Domicilio ****	
		document.getElementById('calle_contacto_editar').value = data.calle_contacto;
		document.getElementById('exterior_contacto_editar').value = data.exterior_contacto;
		document.getElementById('interior_contacto_editar').value = data.interior_contacto;
		document.getElementById('codigo_postal_editar').value=data.d_codigo;
		agregarOptions("#estado_editar", data.d_estado, data.d_estado);
		$("#estado_editar option[value='"+data.d_estado+"']").prop("selected",true);
		if(data.d_ciudad!=""){
            agregarOptions('#ciudad_editar', data.d_ciudad, data.d_ciudad);
            $("#ciudad_editar").css('border-color', '#ccc');
            $("#ciudad_editar option[value='"+data.d_ciudad+"']").prop("selected",true);
        }else{
            agregarOptions('#ciudad_editar', "N/A", "NO APLICA");
            $("#ciudad_editar").css('border-color', '#a94442');
            $("#ciudad_editar option[value='N/A']").prop("selected",true);
        }
        agregarOptions("#municipio_editar", data.d_mnpio, data.d_mnpio);
		$("#municipio_editar option[value='"+data.d_mnpio+"']").prop("selected",true);
		agregarOptions('#colonia_editar', data.id_codigo_postal, data.d_asenta);
		$("#colonia_editar option[value='"+data.id_codigo_postal+"']").prop("selected",true);

			

					
		cuadros('#cuadro1', '#cuadro4');
		urlcuentas = base_url+'ClientePagador/cuentas/'+ data.id_cliente+'/1'
		$('#iframeCuenta').attr('src', urlcuentas);
		urlRepLegal = base_url+'ClientePagador/rep_legal/'+data.id_cliente+'/1'
		$('#iframeRepLegal').attr('src',urlRepLegal)
		urlContacto = base_url+'ClientePagador/contacto/'+data.id_cliente+'/1'
		$('#iframeContacto').attr('src',urlContacto)

		});
	}
/* ------------------------------------------------------------------------------- */
/*
*	Funcion editar Nueva Con Mongo Db - Modificacion 20-02-2019
*/
function editar(tbody, table){
		//verificarRadio()
		//cargar_elementos_select();


		GetClientes("#empresa_pertenece_edit")

		$('#fisica_editar').attr('checked', false)
		$('#moral_editar').attr('checked', false)
		$('#cliente_img_editar').fileinput('destroy');
		$('#cliente_img_moral_editar').fileinput('destroy');
		$('#rfc_img_editar').fileinput('destroy');
		$('#acta_img_e').fileinput('destroy');
		$('#acta_img_e').fileinput('destroy');
		$('#domicilio_fiscal_img_e').fileinput('destroy');
		$('#rfc_imag_mo_e').fileinput('destroy');
		$('#acta_img_e').fileinput('destroy');
		$("#form_clientePagador_registrar")[0].reset();
		$("#form_clientePa_actualizar")[0].reset();
		base_url = document.getElementById('ruta').value;
		url_imagen = base_url+'assets/cpanel/ClientePagador/images/'

		//--Cuepro tbody --
		$(tbody).on("click", "span.editar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			//console.log(data)
			//--
			/*
			*	Envio un ajax request para cosumir el servicio ag
			*/
			data_cliente = armarDataClienteAg(data,"actualizar");
			console.log(data_cliente);
			//--
			$("#alertas").css("display", "none");
			if(data.rfc_img != ""){
			var ext = data.rfc_img.split('.');
			
					if (ext[1] == "pdf") {
                	rfcimg = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.rfc_img+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
	  				
            	}else{
					rfcimg = '<img src="'+url_imagen+data.rfc_img+'" class="file-preview-image kv-preview-data">'
            	}
		  	rfcimg += '<input name="rfc_img_editar" value="'+data.rfc_img+'" type="hidden">'
	   		}else{ rfcimg = ""}

		    $('#rfc_img_editar').fileinput({
		        theme: 'fa',
		        language: 'es',	

		        uploadAsync: true,
		        showUpload: false, // hide upload button
		        showRemove: false,
		        uploadUrl: base_url+'uploads/upload/cliente',
		        uploadExtraData:{
		        	name:$('#rfc_img_editar').attr('id')
		        },
		        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
		        overwriteInitial: false,
		        maxFileSize: 5000,			
		        maxFilesNum: 1,
		        autoReplace:true,
		        initialPreviewAsData: false,
		        initialPreview: [ 
		        	rfcimg
		        ],
		        initialPreviewConfig: [
		            {caption: data.rfc_img,downloadUrl: url_imagen+data.rfc_img  ,url: base_url+"uploads/delete", key: data.rfc_img}
		        ],

		        //allowedFileTypes: ['image', 'video', 'flash'],
		        slugCallback: function (filename) {
		            return filename.replace('(', '_').replace(']', '_');
		        }
		    }).on("filebatchselected", function(event, files) {
		      $(event.target).fileinput("upload");

		    }).on("filebatchuploadsuccess",function(form, data){
		      
		      console.log(data.response)
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








		    if(data.dominio_fiscal_img != ""){

		    	var ext = data.dominio_fiscal_img.split('.');
			
				if (ext[1] == "pdf") {
                	domicilioimg = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.dominio_fiscal_img+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
	  				
            	}else{

			 		domicilioimg = '<img src="'+url_imagen+data.dominio_fiscal_img+'" class="file-preview-image kv-preview-data">'
            	}


				domicilioimg += '<input name="domicilio_fiscal_img_e" value="'+data.dominio_fiscal_img+'" type="hidden">'
			}else{domicilioimg = ""}
		    $('#domicilio_fiscal_img_e').fileinput({
		        theme: 'fa',
		        language: 'es',	

		        uploadAsync: true,
		        showUpload: false, // hide upload button
		        showRemove: false,
		        uploadUrl: base_url+'uploads/upload/cliente',
		        uploadExtraData:{
		        	name:$('#domicilio_fiscal_img_e').attr('id')
		        },
		        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
		        overwriteInitial: false,
		        maxFileSize: 5000,			
		        maxFilesNum: 1,
		        autoReplace:true,
		        initialPreviewAsData: false,
		        initialPreview: [ 
		        	domicilioimg
		        ],
		        initialPreviewConfig: [
		           {caption: data.dominio_fiscal_img, downloadUrl: url_imagen+data.dominio_fiscal_img, url: base_url+"uploads/delete", key: data.dominio_fiscal_img}  
		            
		        ],
		        //allowedFileTypes: ['image', 'video', 'flash'],
		        slugCallback: function (filename) {
		            return filename.replace('(', '_').replace(']', '_');
		        }
		    }).on("filebatchselected", function(event, files) {
		      $(event.target).fileinput("upload");

		    }).on("filebatchuploadsuccess",function(form, data){
		      
		      console.log(data.response)
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
			if (data.tipo_persona_cliente == "FISICA"){ 
				//------------------------------------------------
				//Imagen cliente fisica
				console.log(data.imagenCliente);
				if((data.imagenCliente != "")&&(data.imagenCliente !="default-img.png") && (data.imagenCliente != "undefined")){


					var ext = data.imagenCliente.split('.');
			
				if (ext[1] == "pdf") {
                	imagenCliente = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.imagenCliente+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
	  				
            	}else{
            		imagenCliente = '<img src="'+url_imagen+data.imagenCliente+'" class="file-preview-image kv-preview-data">'
            	}


					
			 		imagenCliente += '<input name="cliente_img_editar" value="'+data.imagenCliente+'" type="hidden">'

			 	}else{imagenCliente = ""}
				$('#cliente_img_editar').fileinput({
			        theme: 'fa',
			        language: 'es',	

			        uploadAsync: true,
			        showUpload: false, // hide upload button
			        showRemove: false,
			        uploadUrl: base_url+'uploads/upload/cliente',
			        uploadExtraData:{
			        	name:$('#cliente_img_editar').attr('id')
			        },
			        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
			        overwriteInitial: false,
			        maxFileSize: 5000,			
			        maxFilesNum: 1,
			        autoReplace:true,
			        initialPreviewAsData: false,
			        initialPreview: [ 
			        	imagenCliente
			        ],
			        initialPreviewConfig: [
			            {caption: data.imagenCliente,downloadUrl: url_imagen+data.imagenCliente  ,url: base_url+"uploads/delete", key: data.imagenCliente}
			        ],

			        //allowedFileTypes: ['image', 'video', 'flash'],
			        slugCallback: function (filename) {
			            return filename.replace('(', '_').replace(']', '_');
			        }
			    }).on("filebatchselected", function(event, files) {
			      $(event.target).fileinput("upload");

			      var data_img = $(".img_profile_edit .kv-preview-thumb .file-preview-image").attr("src");

			      $("#img_profile_edit").attr("src", data_img);
			      $("#img_profile_edit").css("display", "block");
			      
			    }).on("filebatchuploadsuccess",function(form, data){
			      
			        
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
				//------------------------------------------------	
					$('#fisica_editar').attr('checked', true)
					$(".pestana_replegaleditar").hide();
					$("#personaFisica_e").show();
					$("#personaMoral_e").hide();
					$(".morale").removeAttr("required");
					$(".fisicae").attr("required");
					$('#descargar').attr('href',url_imagen+data.rfc_img);
					document.getElementById('nombre_cliente_editar').value = data.nombre_datos_personales;
					document.getElementById('apellido_paterno_editar').value = data.apellido_p_datos_personales;
					document.getElementById('apellido_materno_editar').value = data.apellido_m_datos_personales;
					document.getElementById('rfc_editar').value = data.rfc_datos_personales;
					document.getElementById('rfc_moral_e').value = data.rfc_datos_personales;

					document.getElementById('curp_datos_personales_editar').value = data.curp_datos_personales;
					$("#actividad_economica_editar option[value='" + data.actividad_e_cliente + "']").prop("selected",true);
					if(data.fecha_nac_datos_personales!=""){
						document.getElementById('fecha_nac_datos_editar').value = cambiarFormatoFecha(data.fecha_nac_datos_personales);
					}else{
						document.getElementById('fecha_nac_datos_editar').value = "";
					}
					document.getElementById('correo_cliente_editar').value = data.correo_contacto;
					document.getElementById('telefono_cliente_editar').value = data.telefono_principal_contacto;
					$("#nacionalidad_cliente_editar option[value='" + data.nacionalidad_datos_personales + "']").prop("selected",true);
					$("#pais_origen_editar option[value='" + data.pais_cliente + "']").prop("selected",true);



					$("#ficha_edit span").text(data.nombre_datos_personales+" "+data.apellido_p_datos_personales+" "+data.apellido_m_datos_personales);
			       
			        if(data.imagenCliente != "undefined"){
						
			        	$("#img_profile_edit").css("display", "block");
			        	$("#img_profile_edit").attr("src", url_imagen+data.imagenCliente);
			        }else{
			        	$("#img_profile_edit").css("display", "none");
			        }

			}		
			if (data.tipo_persona_cliente == "MORAL"){



				if(data.imagenCliente != "undefined"){
						
					$("#img_profile_edit").css("display", "block");
					$("#img_profile_edit").attr("src", url_imagen+data.imagenCliente);
				}else{
					$("#img_profile_edit").css("display", "none");
				}



				$("#ficha_edit span").text(data.nombre_datos_personales+" "+data.apellido_p_datos_personales+" "+data.apellido_m_datos_personales);


				//------------------------------------------------
				//Imagen cliente moral
				//------------------------------------------------

				if((data.imagenCliente != "")&&(data.imagenCliente !="default-img.png")){

					var ext = data.imagenCliente.split('.');
			
				if (ext[1] == "pdf") {
                	imagenCliente = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.imagenCliente+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
	  				
            	}else{
            		imagenCliente = '<img src="'+url_imagen+data.imagenCliente+'" class="file-preview-image kv-preview-data">'
            	}

					
			 		imagenCliente += '<input name="cliente_img_moral_editar" value="'+data.imagenCliente+'" type="hidden">'
			 	}else{imagenCliente = ""}
				$('#cliente_img_moral_editar').fileinput({
			        theme: 'fa',
			        language: 'es',	

			        uploadAsync: true,
			        showUpload: false, // hide upload button
			        showRemove: false,
			        uploadUrl: base_url+'uploads/upload/cliente',
			        uploadExtraData:{
			        	name:$('#cliente_img_moral_editar').attr('id')
			        },
			        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
			        overwriteInitial: false,
			        maxFileSize: 5000,			
			        maxFilesNum: 1,
			        autoReplace:true,
			        initialPreviewAsData: false,
			        initialPreview: [ 
			        	imagenCliente
			        ],
			        initialPreviewConfig: [
			            {caption: data.imagenCliente,downloadUrl: url_imagen+data.imagenCliente  ,url: base_url+"uploads/delete", key: data.imagenCliente}
			        ],

			        //allowedFileTypes: ['image', 'video', 'flash'],
			        slugCallback: function (filename) {
			            return filename.replace('(', '_').replace(']', '_');
			        }
			    }).on("filebatchselected", function(event, files) {
			      $(event.target).fileinput("upload");

			    }).on("filebatchuploadsuccess",function(form, data){
			      
			      console.log(data.response)
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
				//------------------------------------------------	
				//------------------------------------------------
				$('#moral_editar').attr('checked', true);
				$(".pestana_replegaleditar").show();
				$("#personaMoral_e").show();
				$("#personaFisica_e").hide();
				$(".fisicae").removeAttr("required");
				$(".moralf").attr("required")	

				document.getElementById('razon_social_e').value = data.nombre_datos_personales;
				document.getElementById('rfc_moral_e').value = data.rfc_datos_personales;
				document.getElementById('rfc_editar').value = data.rfc_datos_personales;
				document.getElementById('fecha_cons_e').value = cambiarFormatoFecha(data.fecha_nac_datos_personales);
				document.getElementById('acta_constutiva_e').value = data.acta_constitutiva;
				$("#giro_mercantil_e option[value='" + data.giro_mercantil + "']").prop("selected",true);
				document.getElementById('correo_moral_e').value = data.correo_contacto;
				document.getElementById('telefono_moral_e').value = data.telefono_principal_contacto;
			}
			if(data.rfc_img != ""){

				var ext = data.rfc_img.split('.');
				if (ext[1] == "pdf") {
                	rfcimgmoral = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.rfc_img+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
	  				
            	}else{
            		rfcimgmoral = '<img src="'+url_imagen+data.rfc_img+'" class="file-preview-image kv-preview-data">'
            	}
				
				rfcimgmoral += '<input name="rfc_imag_mo_e" value="'+data.rfc_img+'" type="hidden">'
			}else{rfcimgmoral = ""}	   
		    $('#rfc_imag_mo_e').fileinput({
		        theme: 'fa',
		        language: 'es',	

		        uploadAsync: true,
		        showUpload: false, // hide upload button
		        showRemove: false,
		        uploadUrl: base_url+'uploads/upload/cliente',
		        uploadExtraData:{
		        	name:$('#rfc_imag_mo_e').attr('id')
		        },
		        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
		        overwriteInitial: false,
		        maxFileSize: 5000,			
		        maxFilesNum: 1,
		        autoReplace:true,
		        initialPreviewAsData: false,
		        initialPreview: [ 
		        	rfcimgmoral
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
		      
		      console.log(data.response)
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
		    if(data.acta_img != "" && data.acta_img != null){

		    	var ext = data.acta_img.split('.');
				if (ext[1] == "pdf") {
                	actaimg = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.acta_img+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
	  				
            	}else{

					actaimg = '<img src="'+url_imagen+data.acta_img+'" class="file-preview-image kv-preview-data">'
            	}


				actaimg += '<input name="acta_img_e" value="'+data.acta_img+'" type="hidden">'
			}else {actaimg = ""} 
		    $('#acta_img_e').fileinput({
		        theme: 'fa',
		        language: 'es',	

		        uploadAsync: true,
		        showUpload: false, // hide upload button
		        showRemove: false,
		        uploadUrl: base_url+'uploads/upload/cliente',
		        uploadExtraData:{
		        	name:$('#acta_img_e').attr('id')
		        },
		        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
		        overwriteInitial: false,
		        maxFileSize: 5000,			
		        maxFilesNum: 1,
		        autoReplace:true,
		        initialPreviewAsData: false,
		        initialPreview: [ 
		        	actaimg
		        ],
		        initialPreviewConfig: [
		            {caption: data.acta_img, downloadUrl: url_imagen+data.acta_img, url: base_url+"uploads/delete", key: data.acta_img} 
		            
		        ],

		        //allowedFileTypes: ['image', 'video', 'flash'],
		        slugCallback: function (filename) {
		            return filename.replace('(', '_').replace(']', '_');
		        }
		    }).on("filebatchselected", function(event, files) {
		      $(event.target).fileinput("upload");

		    }).on("filebatchuploadsuccess",function(form, data){
		      
		      console.log(data.response)
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

			$('#domicilioImgEditar').attr('href',url_imagen+data.dominio_fiscal_img);
			document.getElementById('imagen_editar').value = data.rfc_img;
			document.getElementById('imagen_domicilio_e').value = data.dominio_fiscal_img;
			document.getElementById('imagen_acta_e').value = data.acta_img;
			document.getElementById('id_clientePagador_actualizar').value = data.id_cliente;
			document.getElementById('id_contacto').value = data.id_contacto;
			document.getElementById('id_datos_personales').value = data.id_datos_personales;
			document.getElementById('id_codigo_postal').value = data.id_codigo_postal;

			// ***** datos Domicilio ****	
			document.getElementById('calle_contacto_editar').value = data.calle_contacto;
			document.getElementById('exterior_contacto_editar').value = data.exterior_contacto;
			document.getElementById('interior_contacto_editar').value = data.interior_contacto;



			$("#empresa_pertenece_edit").val(data.empresa_pertenece)



			/*document.getElementById('codigo_postal_editar').value=data.d_codigo;
			agregarOptions("#estado_editar", data.d_estado, data.d_estado);
			$("#estado_editar option[value='"+data.d_estado+"']").prop("selected",true);
			if(data.d_ciudad!=""){
	            agregarOptions('#ciudad_editar', data.d_ciudad, data.d_ciudad);
	            $("#ciudad_editar").css('border-color', '#ccc');
	            $("#ciudad_editar option[value='"+data.d_ciudad+"']").prop("selected",true);
	        }else{
	            agregarOptions('#ciudad_editar', "N/A", "NO APLICA");
	            $("#ciudad_editar").css('border-color', '#a94442');
	            $("#ciudad_editar option[value='N/A']").prop("selected",true);
	        }
	        agregarOptions("#municipio_editar", data.d_mnpio, data.d_mnpio);
			$("#municipio_editar option[value='"+data.d_mnpio+"']").prop("selected",true);
			agregarOptions('#colonia_editar', data.id_codigo_postal, data.d_asenta);
			$("#colonia_editar option[value='"+data.id_codigo_postal+"']").prop("selected",true);*/
			cuadros('#cuadro1', '#cuadro4');
			urlcuentas = base_url+'ClientePagador/cuentas/'+ data.id_cliente+'/1'
			$('#iframeCuenta').attr('src', urlcuentas);
			urlRepLegal = base_url+'ClientePagador/rep_legal/'+data.id_cliente+'/1'
			$('#iframeRepLegal').attr('src',urlRepLegal)
			urlContacto = base_url+'ClientePagador/contacto/'+data.id_cliente+'/1'
			$('#iframeContacto').attr('src',urlContacto)
			
		});
		//--Fin cuerpo tbody --
}
/*
*	armarDataClienteAg: Consume el servicio ag2 y arma un objeto con los valores 
*/
function armarDataClienteAg(data,tipo){
	var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>

	$.ajax({
		url: url + "ClientePagador/listado_clientePagador_servicio",
        type:"POST",
        dataType:"JSON",
        data:{
                        "data":data,
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
        	console.log(respuesta)
        	//---
			$("#alertas").css("display", "none");

        	if(tipo=="actualizar"){

        		//---Codigo postal
	        	document.getElementById('codigo_postal_editar').value=respuesta.d_codigo;
				agregarOptions("#estado_editar", respuesta.d_estado, respuesta.d_estado);
				$("#estado_editar option[value='"+respuesta.d_estado+"']").prop("selected",true);
				if(respuesta.d_ciudad!=""){
		            agregarOptions('#ciudad_editar', respuesta.d_ciudad, respuesta.d_ciudad);
		            $("#ciudad_editar").css('border-color', '#ccc');
		            $("#ciudad_editar option[value='"+respuesta.d_ciudad+"']").prop("selected",true);
		        }else{
		            agregarOptions('#ciudad_editar', "N/A", "NO APLICA");
		            $("#ciudad_editar").css('border-color', '#a94442');
		            $("#ciudad_editar option[value='N/A']").prop("selected",true);
		        }
		        agregarOptions("#municipio_editar", respuesta.d_mnpio, respuesta.d_mnpio);
				$("#municipio_editar option[value='"+respuesta.d_mnpio+"']").prop("selected",true);
				agregarOptions('#colonia_editar', respuesta.id_codigo_postal, respuesta.d_asenta);
				$("#colonia_editar option[value='"+respuesta.id_codigo_postal+"']").prop("selected",true);
				//---
				//---Actividad económica...
				//$("#actividad_economica_editar option[value='" + respuesta.actividad_e_cliente + "']").prop("selected",true);
				//---Giro mercantil
				//document.getElementById('giro_mercantil_e').value = respuesta.giro_merca_desc;
				//---Nacionalidad
				//$("#nacionalidad_cliente_editar option[value='" + respuesta.pais_nacionalidad + "']").prop("selected",true);
				//---Pais de Origen
				//$("#pais_origen_editar option[value='" + respuesta.pais_cliente + "']").prop("selected",true);

        	}else if(tipo=="consultar"){

        		//---Codigo postal
	        	document.getElementById('codigo_postal_mostrar').value=respuesta.d_codigo;
				$("#estado_mostrar").val(respuesta.d_estado)
				if(respuesta.d_ciudad!=""){
		            $("#ciudad_mostrar").css('border-color', '#ccc');
		            $("#ciudad_mostrar").val(respuesta.d_ciudad) 
		        }else{
		            $('#ciudad_mostrar').val("NO APLICA");
		            $("#ciudad_mostrar").css('border-color', '#a94442');
		        }
		        $("#municipio_mostrar").val(respuesta.d_mnpio)
				$("#colonia_mostrar").val(respuesta.id_codigo_postal)
				//---
				//---Actividad económica...
				$("#actividad_economica_mostrar").val(respuesta.actividad_economica)
				//---Giro mercantil
				document.getElementById('giro_mercantil_c').value = respuesta.giro_merca_desc;
				//---Nacionalidad
				document.getElementById('nacionalidad_cliente_mostrar').value = respuesta.pais_nacionalidad;
				//---Pais de Origen
				$("#pais_origen_mostrar").val(respuesta.pais_origen)
        	}
      		//--
		}	
	});	
}
/*
*
*/
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_clientePagador(){
		enviarFormulario3("#form_clientePa_actualizar", 'ClientePagador/actualizar_clientePagador', '#cuadro4');
	}







	function enviarFormulario3(form, controlador, cuadro){
        $(form).submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
            var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
            var method = $(this).attr('method'); //obtiene el method del formulario


            var objetoImgProfile = [];
            $(".img_profile_edit .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
            	var img = [];
            	img.push($(this).attr("title"));
            	objetoImgProfile.push(img);
			});

			var objetoNIdeintificacion = [];
            $(".img-n-identificacion-edit .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
            	var img = [];
            	img.push($(this).attr("title"));
            	objetoNIdeintificacion.push(img);
			});


			console.log(objetoNIdeintificacion)



			var objetoDomicilio = [];
            $("#domicilio_edit .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
            	var img = [];
            	img.push($(this).attr("title"));
            	objetoDomicilio.push(img);
			});



			var objetoActa = [];
            $("#img_acta_constitutiva_edit .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
            	var img = [];
            	img.push($(this).attr("title"));
            	objetoActa.push(img);
			});






			formData.append('imgProfile', objetoImgProfile[0]);
			formData.append('img_n_identificacion', objetoNIdeintificacion[0]);
			formData.append('img_domicilio', objetoDomicilio[0]);
			formData.append('img_acta_constitutiva', objetoActa[0]);

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
                        if(cuadro!="")
                            listar(cuadro);
                    }

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
            eliminarConfirmacion('ClientePagador/eliminar', data.id_cliente, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('ClientePagador/status_clientePagador', data.id_cliente, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('ClientePagador/status_clientePagador', data.id_cliente, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}

/* ------------------------------------------------------------------------------- */

/*
        Funcion que busca los codigos
    */
    /*function buscarCodigos(codigo, type){
    	if(codigo.length>4){ 
    	if (!busqueda){
    		busqueda = true;
	    	if(type == 'create'){
	    		var estado = 'estado_registrar',
		    		ciudad = 'ciudad_registrar',
		    		municipio = 'municipio_registrar',
		    		colonia = 'colonia_registrar';
	    	}else if(type == 'edit'){
	    		var estado = 'estado_editar',
		    		ciudad = 'ciudad_editar',
		    		municipio = 'municipio_editar',
		    		colonia = 'colonia_editar';
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
	    }else{
	    busqueda = false;
	    }
    }*/

/* ------------------------------------------------------------------------------- */
function buscarCodigosUs(codigo, type){

	    	if(type == 'create'){
	    		var estado = 'estado_registrar',
		    		ciudad = 'ciudad_registrar',
		    		municipio = 'municipio_registrar',
		    		colonia = 'colonia_registrar';
	    	}else if(type == 'edit'){
	    		var estado = 'estado_editar',
		    		ciudad = 'ciudad_editar',
		    		municipio = 'municipio_editar',
		    		colonia = 'colonia_editar';
	    	}

	    	buscarCodigos(codigo,estado,ciudad,municipio,colonia);
	 
    }
/* ------------------------------------------------------------------------------- */

/*
        funcion que detecta la tecla enter para la busqueda de los codigos postales.
    */
    $("#codigo_postal_registrar").keydown(function(e) {
    	var busqueda = false
        if(e.which == 13) {
            if (!busqueda){
            	setTimeout(function(){
            		//buscarCodigos(document.getElementById('codigo_postal_registrar').value, 'create');
                	buscarCodigosUs(document.getElementById('codigo_postal_registrar').value, 'create')
                	var busqueda = true;
            	},2000);
            }

        }
    });
    $("#codigo_postal_registrar").change(function(e) {
    	var busqueda = false
  
    });
    $("#codigo_postal_editar").keydown(function(e) {
    	var busqueda = false
        if(e.which == 13) {
            /*if (!busqueda){
                buscarCodigos(document.getElementById('codigo_postal_editar').value, 'edit');
                var busqueda = true;
            }*/
            if (!busqueda){
            	setTimeout(function(){
            		//buscarCodigos(document.getElementById('codigo_postal_editar').value, 'edit');
                	buscarCodigosUs(document.getElementById('codigo_postal_editar').value, 'edit')
                	var busqueda = true;
            	},2000);
            }

        }

    });
     $("#codigo_postal_editar").change(function(e) {
    	var busqueda = false
  
    });

     $("#codigo_postal_registrar").focus(function() {
        busqueda = false;
    });
    $("#codigo_postal_editar").focus(function() {
        busqueda = false;
    });

      function codigoPostal(e){
        key=e.keyCode || e.which;
        teclado=String.fromCharCode(key);
        numeros="1234567890";
        especiales="8-9-17-37-38-46";//los numeros de esta linea son especiales y es para las flechass
        teclado_escpecial=false;
        for(var i in especiales)
            if (key==especiales[i])
                teclado_escpecial=true;
        if (numeros.indexOf(teclado)==-1 && !teclado_escpecial)
            return false;
    }

	/*$('#regreso_editar').click(function(){
		$("#form_clientePagador_registrar")[0].reset();
		$("#form_clientePa_actualizar")[0].reset();
		verificarRadio();
	  //  $('#moral_editar').attr('checked', false)
			
	})*/

	/*$(".filen").fileinput({
    language: "es",
    showUpload: false,
    dropZoneEnabled: false,
    maxFileCount: 5,
    maxFilePreviewSize: 10240,
    uploadUrl: "assets/cpanel/ClientePagador/images",
    allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg"],
    previewFileIcon: '<i class="fa fa-file"></i>',
    //allowedPreviewTypes: null, // set to empty, null or false to disable preview for all types
    //preferIconicZoomPreview: true,
    previewFileIconSettings: {
        'docx': '<i class="fa fa-file-word-o text-primary"></i>',
        'xlsx': '<i class="fa fa-file-excel-o text-success"></i>',
        'pptx': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
        'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
        },
     initialPreviewDownloadUrl: "localhost/crmventas/assets/cpanel/ClientePagador/images/3dc4511d49d0779175085a0c5c94160b.pdf"
})
*/
/*function imagen_edi(tbody, table){
$(tbody).on("click", "span.editar", function(){
var data = table.row( $(this).parents("tr") ).data();
	$("#rfc_img_editar").fileinput({
		language: "es",
		 uploadUrl: "/file-upload-batch/2",
		 maxFileCount: 1,
		allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg"],
		overwriteInitial: false,
		initialPreviewAsData: true,
		initialPreview: [
		    "https://picsum.photos/1920/1080?image=101",
		    
		],
		initialPreviewConfig: [
		    {caption: "picture-1.jpg", url: "/site/file-delete", key: 101},
		    
		],
		initialPreviewDownloadUrl: 'https://picsum.photos/1920/1080?image={key}' // the key will be dynamically replaced  
		});
	});
}*/
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
/*------------------------------------------------------------------------------------------------------------------------------*/
