$(document).ready(function(){
	telefonoInput('.telefono');
	listar();
	registrar_prospecto();
	actualizar_prospecto();
	verificarRadio();
    registrar_cliente();
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
				"url": url + "Prospecto/listado_prospecto",
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
		          		if(data.status == true)	
		          			botones += "<span class='traspaso btn btn-xs btn-success waves-effect' data-toggle='tooltip' title='Traspasar'><i class='fa fa-exchange' style='margin-bottom:5px'></i></span>";
		          		return botones;
		          	}
				},
				{"data":"rfc_datos_personales"},
				{"data":"tipo_persona_cliente"},
				{"data":"nombre_datos_personales"},
				{"data":"nombre_vendedor"},
				//{"data":"nombre_proyecto"},
				{"data":"fec_regins",
					render : function(data, type, row) {
						var valor = data.date;
						fecha = valor.split(" ");
						return cambiarFormatoFecha(fecha[0]);
	          		}
				},
				{"data":"correo_usuario"}
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
		trasladar("#tabla tbody", table);

		//imagen_edi("#tabla tbody", table)
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro2 para mostrar el formulario de registrar.
	*/
	function nuevoRegistro(cuadroOcultar, cuadroMostrar){
		
		$("#alertas").css("display", "none");
		cuadros("#cuadro1", "#cuadro2");
			$("#personaMoral").hide();
			$("#personaFisica").show();
			$(".moralf").removeAttr("required")	
		$("#form_prospecto_registrar")[0].reset();
		$("#form_prospecto_actualizar")[0].reset();
		$("#id_vendedor").focus();

		$("#id_vendedor").on("change", function(){
			var id_vendedor = $('#id_vendedor').val()
			if (id_vendedor != "") {
		 	$.ajax({
	            url: document.getElementById('ruta').value + 'prospecto/getProyecto/'+id_vendedor,
	            type: 'POST',
	            dataType:'JSON',
	            cache: false,
				processData: false,
				contentType: false,
	            error: function (repuesta) {
	                $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
	                var errores=repuesta.responseText;
	                if(errores!="")
	                    mensajes('danger', errores);
	                else
	                    mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
	            },
	            success: function(respuesta){
	                console.log(respuesta);
	                var proyectos = respuesta;
	                  $('#proyecto').removeAttr('disabled');
					$('#proyecto option').remove();
					if (proyectos.length == 0) {
	                	$('#proyecto').append($('<option>',
					    {
					        value: "",
					        text : "No Tiene Proyectos..."
					    }));
					    $('#proyecto').attr('disabled', 'disabled');
					    $('#proyecto').removeAttr('required');
	             }else{
	                	$('#proyecto').attr('required', 'required');
	                	$('#proyecto').append($('<option>',
					    {
					        value: "",
					        text : "Seleccione..."
					    }));
					    $.each(proyectos, function(i, item){
			           		$('#proyecto').append($('<option>',
						     {
						        value: item.id_proyecto,
						        text : item.nombre_proyecto//+" --- "+number_format(item.precio,2)
						    }));
			           	});
	                }
		           
	            }
	        });	
		 }

		});
		$("#id_vendedor_moral").on("change", function(){
			var id_vendedor = $('#id_vendedor_moral').val()
			if (id_vendedor != "") {
		 	$.ajax({
	            url: document.getElementById('ruta').value + 'prospecto/getProyecto/'+id_vendedor,
	            type: 'POST',
	            dataType:'JSON',
	            cache: false,
				processData: false,
				contentType: false,
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
	                var proyectos = respuesta;
	                  $('#proyecto_moral').removeAttr('disabled');
					$('#proyecto_moral option').remove();
					if (proyectos.length == 0) {
	                	$('#proyecto_moral').append($('<option>',
					    {
					        value: "",
					        text : "No Tiene Proyectos..."
					    }));
					    $('#proyecto_moral').attr('disabled', 'disabled');
					    $('#proyecto_moral').removeAttr('required');
	             }else{
	                	$('#proyecto_moral').attr('required', 'required');
	                	$('#proyecto_moral').append($('<option>',
					    {
					        value: "",
					        text : "Seleccione..."
					    }));
					    $.each(proyectos, function(i, item){
			           		$('#proyecto_moral').append($('<option>',
						     {
						        value: item.id_proyecto,
						        text : item.nombre_proyecto//+" --- "+number_format(item.precio,2)
						    }));
			           	});
	                }
		           
	            }
	        });	
		 }

		});
		
	}
	/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_prospecto(){
		enviarFormulario("#form_prospecto_registrar", 'Prospecto/registrar_prospecto', '#cuadro2');
	}
/* ------------------------------------------------------------------------------- */
   function verificarRadio()
   {
   	   	$("input[name=rad_tipoper]").change(function () {
		if($("#tipopersona input[id='moral']").is(':checked')){
			$("#personaMoral").show();
			$("#personaFisica").hide();
			$(".fisicaf").removeAttr("required")	
			$(".moralf").prop("required", true)
			$("#razon_social").focus()		
		}
		else{
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
		$("#form_prospecto_registrar")[0].reset();
		$("#form_prospecto_actualizar")[0].reset();
		//$('#fisica_mostrar').attr('checked', false)
		//$('#moral_mostrar').attr('checked', false)
		base_url = document.getElementById('ruta').value;
		$(tbody).on("click", "span.consultar", function(){
		$("#alertas").css("display", "none");
		var data = table.row( $(this).parents("tr") ).data();
			$("#tipo_persona_e").val(data.tipo_persona_cliente)
		
			if (data.tipo_persona_cliente == "FISICA"){ 
				$('#fisica_mostrar').prop('checked', true)
				$("#datosGeneralFisica").show();
				$("#datosGeneralMoral").hide();
				$("#vendedor_m").val(data.nombre_vendedor+' '+data.apellido_vendedor)
				$("#proyecto_m").val(data.nombre_proyecto)
				$("#rfc_m").val(data.rfc_datos_personales)
				$("#nombre_prospecto_m").val(data.nombre_datos_personales)
				$("#apellido_paterno_prospecto_m").val(data.apellido_p_datos_personales)
				$("#apellido_materno_prospecto_m").val(data.apellido_m_datos_personales)
				$("#tef_ppal_prospecto_m").val(data.telefono_principal_contacto)
				$("#tfl_movilContacto_m").val(data.telefono_movil_contacto)
				$("#tlf_casa_m").val(data.telefono_casa_contacto)
				$("#tlf_trabajo_m").val(data.telefono_trabajo_contacto)
				$("#tlf_fax_m").val(data.telefono_fax_contacto)	
				$("#correo_contacto_m").val(data.correo_contacto)
				$("#coreo_contactp_opc_m").val(data.correo_opcional_contacto)	
				$("#observaciones_fisica_view").val(data.observacion);
		}			
			if (data.tipo_persona_cliente == "MORAL"){
				$('#moral_mostrar').prop('checked', true);
				//$("#datosGeneralMoral").show();
				$("#datosGeneralMoral").css("display", "block");
				//$("#datosGeneralFisica").hide();
				$("#datosGeneralFisica").css("display", "none");
				$("#vendedor_m").val(data.nombre_vendedor+' '+data.apellido_vendedor)
				$("#proyecto_m").val(data.nombre_proyecto)
				$("#rfc_moral_m").val(data.rfc_datos_personales)
				$("#razon_social_m").val(data.nombre_datos_personales)
				//alert(data.telefono_principal_contacto);
				$("#tlf_ppalContacto_moral_m").val(data.telefono_principal_contacto)
				$("#tlf_fax_moral_m").val(data.telefono_fax_contacto)	
				$("#correo_contacto_moral_m").val(data.correo_contacto)
				$("#observaciones_moral_view").val(data.observacion);
			} 
			

			

			
			cuadros('#cuadro1', '#cuadro3');
			

		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
/* 
		Funcion que muestra el cuadro4 para editar
	*/
	function editar(tbody, table){
		//verificarRadio()
		$('#fisica_editar').attr('checked', false)
		$('#moral_editar').attr('checked', false)
		$("#form_prospecto_registrar")[0].reset();
		$("#form_prospecto_actualizar")[0].reset();
		base_url = document.getElementById('ruta').value;
		url_imagen = base_url+'assets/cpanel/ClientePagador/images/'
		$(tbody).on("click", "span.editar", function(){
		var data = table.row( $(this).parents("tr") ).data();
		console.log(data)
		$("#alertas").css("display", "none");
		$("#id_prospecto").val(data.id_prospecto);
		$("#id_cliente_e").val(data.id_cliente)
		$("#id_datos_personales_e").val(data.id_datos_personales)
		$("#id_contacto_e").val(data.id_contacto)
		$("#tipo_persona_e").val(data.tipo_persona_cliente)
		$("#id_vendedor_e").val(data.id_vendedor)
		$("#vendedor_e").val(data.nombre_vendedor+' '+data.apellido_vendedor)
		$("#proyecto_e").val(data.nombre_proyecto)

			if (data.tipo_persona_cliente == "FISICA"){ 
				$('#fisica_editar').attr('checked', true)
				$("#personaFisica_e").show();
				$("#personaMoral_e").hide();
				$(".morale").removeAttr("required");
				
				//$("#vendedor_e").val(data.nombre_vendedor+' '+data.apellido_vendedor)
				//$("#proyecto_e").val(data.nombre_proyecto)
				$("#rfc_editar ").val(data.rfc_datos_personales)
				$("#nombre_prospecto_e").prop("readonly",true).val(data.nombre_datos_personales)
				$("#apellido_paterno_editar").prop("readonly",true).val(data.apellido_p_datos_personales)
				$("#apellido_materno_editar").prop("readonly",true).val(data.apellido_m_datos_personales)
				$("#tef_ppal_prospecto_e").val(data.telefono_principal_contacto)
				$("#tfl_movilContacto_e").val(data.telefono_movil_contacto)
				$("#tlf_casa_e").val(data.telefono_casa_contacto)
				$("#tlf_trabajo_e").val(data.telefono_trabajo_contacto)
				$("#tlf_fax_e").val(data.telefono_fax_contacto)	
				$("#correo_contacto_e").val(data.correo_contacto)
				$("#coreo_contactp_opc_e").val(data.correo_opcional_contacto)
				//$("#id_vendedor_e").val(data.id_vendedor)
				agregarOptions("#proyecto_e", data.id_proyecto, data.nombre_proyecto);
				$("#proyecto_e option[value='" + data.nombre_proyecto + "']").prop("selected",true);
				$("#observaciones_fisica_editar").val(data.observacion);
		           
			$("#id_vendedor_e").on("change", function(){
			var id_vendedor = $('#id_vendedor_e').val()
			if (id_vendedor != "") {
		 	$.ajax({
	            url: document.getElementById('ruta').value + 'prospecto/getProyecto/'+id_vendedor,
	            type: 'POST',
	            dataType:'JSON',
	            cache: false,
				processData: false,
				contentType: false,
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
	                var proyectos = respuesta;
	                  $('#proyecto_e').removeAttr('disabled');
					$('#proyecto_e option').remove();
					if (proyectos.length == 0) {
	                	$('#proyecto_e').append($('<option>',
					    {
					        value: "",
					        text : "No Tiene Proyectos..."
					    }));
					    $('#proyecto_e').attr('disabled', 'disabled');
					    $('#proyecto_e').removeAttr('required');
	             }else{
	                	$('#proyecto_e').attr('required', 'required');
	                	$('#proyecto_e').append($('<option>',
					    {
					        value: "",
					        text : "Seleccione..."
					    }));
					    $.each(proyectos, function(i, item){
			           		$('#proyecto_e').append($('<option>',
						     {
						        value: item.id_proyecto,
						        text : item.nombre_proyecto//+" --- "+number_format(item.precio,2)
						    }));
			           	});
	                }
		           
	            }
	        });	
		 }

		});	



		}		
			if (data.tipo_persona_cliente == "MORAL"){
				$('#moral_editar').attr('checked', true);
				$("#personaMoral_e").show();
				$("#personaFisica_e").hide();
				$(".fisicae").removeAttr("required");
				$("#vendedor_moral_m").val(data.nombre_vendedor+' '+data.apellido_vendedor)
				$("#proyecto_moral_m").val(data.nombre_proyecto)
				$("#rfc_moral_e").val(data.rfc_datos_personales)
				$("#razon_social_e").prop("readonly",true).val(data.nombre_datos_personales)
				$("#telefono_moral_e").val(data.telefono_principal_contacto)
				$("#tlf_fax_moral_e").val(data.telefono_fax_contacto)	
				$("#correo_moral_e").val(data.correo_contacto)
				$("#observaciones_moral_editar").val(data.observacion);
				$(".morale").attr("required");
	}
		

			

			

					
			cuadros('#cuadro1', '#cuadro4');

		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_prospecto(){
		enviarFormulario("#form_prospecto_actualizar", 'Prospecto/actualizar_prospecto', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

	
/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('Prospecto/eliminar', data.id_cliente, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('Prospecto/status_prospecto', data.id_cliente, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Prospecto/status_prospecto', data.id_cliente, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}

/* ------------------------------------------------------------------------------- */
//Para traspasar cliente....
function trasladar(tbody, table){
		$(tbody).on("click", "span.traspaso", function(){
            var data=table.row($(this).parents("tr")).data();
            $("#form_clientePagador_registrar")[0].reset();
            /*--------*/
			elegirFecha_Cumple('.fecha');
            /*--------*/
            //console.log(data)
            trasladarProspecto('Prospecto/trasladarProspecto', data.id_cliente, data.id_proyecto, data.id_vendedor, "¿Esta seguro de Pasar a Cliente este Prospecto?");
            $("#id_cliente_cliente").val(data.id_cliente)
            $("#id_datos_personales_cliente").val(data.id_datos_personales)
            $("#id_contacto_cliente").val(data.id_contacto)
            $("#id_proyecto_cliente").val(data.id_proyecto)
            $("#id_vendedor_cliente").val(data.id_vendedor)
            //$("#id_prospecto_cliente").val(data.id)
            $("#id_prospecto_cliente").val(data.id_prospecto)
            //-------------------------------------------------------
            //--Limpiar imagenes de formulario...
            $('#rfc_img').fileinput('destroy');
			$('#rfc_imag_mo').fileinput('destroy');
			$('#acta_img_r').fileinput('destroy');
			$('#domicilio_fiscal_img').fileinput('destroy');
			//--Limpiar select de codigo postal
			eliminarOptions3("estado_registrar");
	        eliminarOptions3("ciudad_registrar");
	        eliminarOptions3("municipio_registrar");
	        eliminarOptions3("colonia_registrar");
            //-------------------------------------------------------
            if (data.tipo_persona_cliente == "FISICA"){
            	$('#fisica_cliente').prop('checked', true)
				$(".pestana_replegal").hide();
				$("#personaFisicaCliente").show();
				$("#personaMoralCliente").hide();
				$(".moralf").removeAttr("required"); 
            $("#nombre_cliente").val(data.nombre_datos_personales)
            $("#apellido_paterno_cliente").val(data.apellido_p_datos_personales)
            $("#apellido_materno_cliente").val(data.apellido_m_datos_personales)
            $("#rfc").val(data.rfc_datos_personales)
            $("#telefono_registrar").val(data.telefono_principal_contacto)
            $("#correo_cliente_registrar").val(data.correo_contacto)
        }
        if (data.tipo_persona_cliente == "MORAL"){
				$('#moral_cliente').prop('checked', true);
				$(".pestana_replegal").show();
				$("#personaMoralCliente").show();
				$("#personaFisicaCliente").hide();
				$(".fisicac").removeAttr("required");
				$(".moralf").attr("required");  
			$("#razon_social").val(data.nombre_datos_personales)
            $("#rfc_moral_cliente").val(data.rfc_datos_personales)
            $("#telefono_moral_m").val(data.telefono_principal_contacto)
            $("#correo_moral_m").val(data.correo_contacto)



		}
			$("[type='file']").fileinput('destroy');

            var $file;	
			var x = [];	
			$(function(){
			    $file = $("[type='file']")	
			    console.log($file);
			    $file.each(function(i,el){
			    x[i] = $(el).fileinput({
			        theme: 'fa',
			        language: 'es',	

			        uploadAsync: true,
			        showUpload: false, // hide upload button
			        showRemove: false,
			        uploadUrl: base_url+'uploads/upload/cliente',
			        uploadExtraData:{
			        	name:$(el).attr('id')
			        },
			        allowedFileExtensions: ["jpg","jpeg", "png", "gif", "pdf", "doc", "xlsx"],
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


        });
	}
	//------------------------------------------------------------------------------------
	//Bloque tomado de CrmVentas Escorfin
	/*			$("#rfc_img_file").fileinput({

		        theme: 'fa',

		        language: 'es',	



		        uploadAsync: true,

		        showUpload: false, // hide upload button

		        showRemove: false,

		        uploadUrl: base_url+'uploads/upload/cliente',

		        uploadExtraData:{

		        	name:$("#rfc_img_file").attr('id')

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

		    }).on('filedeleted', function() {

		    	$("#rfc_img").removeAttr("value");

		        setTimeout(function() {



		            swal("Eliminado!", "Archivo Eliminado Con Exito", "success");



		        }, 900);



		    }).on('filesuccessremove', function(event, id) {

			    $("#rfc_img").removeAttr("value");

			});







			$("#rfc_imag_mo_file").fileinput({

		        theme: 'fa',

		        language: 'es',	



		        uploadAsync: true,

		        showUpload: false, // hide upload button

		        showRemove: false,

		        uploadUrl: base_url+'uploads/upload/cliente',

		        uploadExtraData:{

		        	name:$("#rfc_imag_mo_file").attr('id')

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

		    }).on('filedeleted', function() {

		    	$("#rfc_imag_mo").removeAttr("value");

		        setTimeout(function() {



		            swal("Eliminado!", "Archivo Eliminado Con Exito", "success");



		        }, 900);



		    }).on('filesuccessremove', function(event, id) {

			    $("#rfc_imag_mo").removeAttr("value");

			});





			$("#acta_img_r_file").fileinput({

		        theme: 'fa',

		        language: 'es',	



		        uploadAsync: true,

		        showUpload: false, // hide upload button

		        showRemove: false,

		        uploadUrl: base_url+'uploads/upload/cliente',

		        uploadExtraData:{

		        	name:$("#acta_img_r_file").attr('id')

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

		    }).on('filedeleted', function() {

		    	$("#acta_img_r").removeAttr("value");

		        setTimeout(function() {



		            swal("Eliminado!", "Archivo Eliminado Con Exito", "success");



		        }, 900);



		    }).on('filesuccessremove', function(event, id) {

			    $("#acta_img_r").removeAttr("value");

			});







			$("#domicilio_fiscal_img_file").fileinput({

		        theme: 'fa',

		        language: 'es',	



		        uploadAsync: true,

		        showUpload: false, // hide upload button

		        showRemove: false,

		        uploadUrl: base_url+'uploads/upload/cliente',

		        uploadExtraData:{

		        	name:$("#domicilio_fiscal_img_file").attr('id')

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

		    }).on('filedeleted', function() {

		    	$("#domicilio_fiscal_img").removeAttr("value");

		        setTimeout(function() {



		            swal("Eliminado!", "Archivo Eliminado Con Exito", "success");



		        }, 900);



		    }).on('filesuccessremove', function(event, id) {

			    $("#domicilio_fiscal_img").removeAttr("value");

			});









			$("#rfc_img_rep_file").fileinput({

		        theme: 'fa',

		        language: 'es',	



		        uploadAsync: true,

		        showUpload: false, // hide upload button

		        showRemove: false,

		        uploadUrl: base_url+'uploads/upload/cliente',

		        uploadExtraData:{

		        	name:$("#rfc_img_rep_file").attr('id')

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

		    }).on('filedeleted', function() {

		    	$("#rfc_img_rep").removeAttr("value");

		        setTimeout(function() {



		            swal("Eliminado!", "Archivo Eliminado Con Exito", "success");



		        }, 900);



		    }).on('filesuccessremove', function(event, id) {

			    $("#rfc_img_rep").removeAttr("value");

			});
        });

	}*/
	///Fin de trasladar




	/*$(document).ready(function(){

        $('#rfc_img_file').change(function(e){

            var fileName = e.target.files[0].name;

            $("#rfc_img").val(fileName);

        });

    });





	$(document).ready(function(){

        $('#rfc_imag_mo_file').change(function(e){

            var fileName = e.target.files[0].name;

            $("#rfc_imag_mo").val(fileName);

        });

    });





     $(document).ready(function(){

        $('#acta_img_r_file').change(function(e){

            var fileName = e.target.files[0].name;

            $("#acta_img_r").val(fileName);

        });

    });





     $(document).ready(function(){

        $('#domicilio_fiscal_img_file').change(function(e){

            var fileName = e.target.files[0].name;

            $("#domicilio_fiscal_img").val(fileName);

        });

    });



    $(document).ready(function(){

        $('#rfc_img_rep_file').change(function(e){

            var fileName = e.target.files[0].name;

            $("#rfc_img_rep").val(fileName);

        });

    });*/
    //Fin de Escorfin....
    ///--------------------------------------------------------------------------------

        
	 function trasladarProspecto(controlador, id_cliente, id_proyecto, id_vendedor, title){
         url=document.getElementById('ruta').value;
         swal({
            title: title,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Trasladar!",
            cancelButtonText: "No, Cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                 $.ajax({
	                url:url+'Prospecto/consultarCliente',
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        id_cliente:id_cliente,
                        tipo_cliente:'CLIENTE',
                     
                    },
   
	                error: function (repuesta) {
	                   cuadros("#cuadro1", "#cuadro5");        
	                },
	                success: function(respuesta){
	                $.ajax({
                    url:url+controlador,
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        id_cliente:id_cliente,
                        id_proyecto: id_proyecto,
                        id_vendedor: id_vendedor,
                        tipo_cliente:'CLIENTE',
                     
	                    }, 
	                    
	                });
	               }
	            });
            } else {
                swal("Cancelado", "No se ha Trasladado el Prospecto", "error");
            }
        });
    }

    function registrar_cliente(){
		enviarFormulario2("#form_clientePagador_registrar", 'Prospecto/registrar_cliente', '#cuadro5');
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
            $(".img-n-identificacion .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
            	var img = [];
            	img.push($(this).attr("title"));
            	objetoNIdeintificacion.push(img);
			});


			console.log(objetoNIdeintificacion)


            var objetoDomicilio = [];
            $("#domicilio_regis .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
            	var img = [];
            	img.push($(this).attr("title"));
            	objetoDomicilio.push(img);
			});



			var objetoActa = [];
            $("#file-acta .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
            	var img = [];
            	img.push($(this).attr("title"));
            	objetoActa.push(img);
			});


			formData.append('imgProfile', objetoImgProfile[0]);
			formData.append('img_n_identificacion', objetoNIdeintificacion[0]);
			formData.append('img_domicilio', objetoDomicilio[0]);
			formData.append('acta_img_r', objetoActa[0]);

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


/*--------------------------------------------------------------------------------- */
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
                //buscarCodigos(document.getElementById('codigo_postal_registrar').value, 'create');
                buscarCodigosUs(document.getElementById('codigo_postal_registrar').value, 'create');
                var busqueda = true;
            }

        }
    });
    $("#codigo_postal_registrar").change(function(e) {
    	var busqueda = false
  
    });
    $("#codigo_postal_editar").keydown(function(e) {
    	var busqueda = false
        if(e.which == 13) {
            if (!busqueda){
                //buscarCodigos(document.getElementById('codigo_postal_editar').value, 'edit');
                buscarCodigosUs(document.getElementById('codigo_postal_editar').value, 'edit');
                var busqueda = true;
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
    function clienteExiste(e)
    {
    	rfc = e.value
    	$("#id_cliente").val()
    	$("#id_datos_personales").val()
    	$("#id_contacto").val()
    	$("#id_cliente_moral").val()
    	$("#id_datos_personales_moral").val()
    	$("#id_contacto_moral").val()
    	$.ajax({
                    url: document.getElementById('ruta').value + "Prospecto/clienteExiste",
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        'rfc' : rfc,
                    },
                    error: function (repuesta) {
                 
                    },
                    success: function(respuesta){
                    	var nombre_prospecto = respuesta[0]['nombre_datos_personales']
                       	$('.nombre_prospecto').prop('readonly', true).val(nombre_prospecto)
                       	var apellido_prospecto = respuesta[0]['apellido_p_datos_personales']
                       	$('#apellido_paterno_prospecto').prop('readonly', true).val(apellido_prospecto)
                       	var apellido_m_prospecto = respuesta[0]['apellido_m_datos_personales']
                       	$('#apellido_materno_prospecto').prop('readonly', true).val(apellido_m_prospecto)
                       	var correo = respuesta[0]['correo_contacto']
                       	$('#correo_r').val(correo)
                       	var telefono = respuesta[0]['telefono_principal_contacto']
                      	$('#tef_ppal_prospecto').val(telefono)
                      	var id_cliente = respuesta[0]['id_cliente']
                      	$('#id_cliente').val(id_cliente)
                      	var id_datos_personales = respuesta[0]['id_datos_personales']
                      	$('#id_datos_personales').val(id_datos_personales)
                      	var id_contacto = respuesta[0]['id_contacto']
                      	$('#id_contacto').val(id_contacto)
	                  
	                  	var razon_social = respuesta[0]['nombre_datos_personales']
                      	$('#razon_social_r').val(razon_social)
                      	var tlf_ppal_moral_r = respuesta[0]['telefono_principal_contacto'];
                      	$('#tlf_ppal_moral_r').val(tlf_ppal_moral_r)
                      	var correo_moral_r = respuesta[0]['correo_contacto'];
                      	$('#correo_moral_r').val(correo_moral_r)
                      	var id_cliente = respuesta[0]['id_cliente']
                      	$('#id_cliente_moral').val(id_cliente)
                      	var id_datos_personales = respuesta[0]['id_datos_personales']
                      	$('#id_datos_personales_moral').val(id_datos_personales)
                      	var id_contacto = respuesta[0]['id_contacto']
                      	$('#id_contacto_moral').val(id_contacto)
	                  
                    }
            });
    }

