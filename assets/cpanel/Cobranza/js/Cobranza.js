$(document).ready(function(){
    listar('', '');
    elegirFecha('.fecha');
    guardarCobranza();
    //registrar_pago();
    editar_pago();

});

/* ------------------------------------------------------------------------------- */
    /* 
        Funcion para cargar los datos de la base de datos en la tabla.
    */
    function listar(cuadro, id){
        $('#tabla tbody').off('click');
        cuadros(cuadro, "#cuadro1");
        var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
        var table=$("#tabla").DataTable({
            "destroy":true,
            "stateSave": true,
            "serverSide":false,
            "ajax":{
                "method":"POST",
                "url":url+"Cobranza/getCotizaciones/",
                "dataSrc":""
            },
            "columns":[
                {"data": "numero_cobranza",
                    render : function(data, type, row) {
                        return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
                    }
                },
                {"data": null,
                       render : function(data, type, row) {
                            var botones = "";

                            if (registrar == 0) {
                                var icon = "<i class='fa fa-dollar' style='margin-bottom:5px'></i>";
                            }else{
                                var icon = "<i class='fa fa-eye' style='margin-bottom:5px'></i>";
                            }
                            if(consultar == 0)
                                botones += "<span class='consultar btn btn-xs btn-success waves-effect admin' data-toggle='tooltip' title='Cobranza'>"+icon+"</span> ";                            
                            return botones;
                        }
                    },

                {"data":"numero_cotizacion"},
                {"data":"datos_clientes",
                    render : function(data, type, row) {
                        return data;
                     }
                },

                {"data":"datos_facturar",
                    render : function(data, type, row) {
                        return data;
                     }
                },


                {"data":"datos_vendores",
                    render : function(data, type, row) {
                        return data;
                     }
                },
                {"data":"productos",
                    render : function(data, type, row) {
                        return data;
                    }
                },
                {"data":"saldo",
                    render : function(data, type, row) {
                        return number_format(data, 2);
                    },
                },
                {"data":"condicion",
                    render : function(data, type, row) {
                        return data;
                    },
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
            "buttons":[
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        admin("#tabla tbody", table);
        viewProductos("#tabla tbody", table);
    }
    /* ------------------------------------------------------------------------------- */
    /* 
        Funcion que muestra el cuadro3 para la consulta de la plaza.
    */
    function admin(tbody, table){
        $(tbody).on("click", "span.admin", function(){
            //----------------------------------------------
            $("[type='file']").fileinput('destroy');
            $("#alertas").css("display", "none");
            
            var data = table.row( $(this).parents("tr") ).data();
           
            var fecha_vect = data.fec_regins.date;

            var fecha = fecha_vect.split(" ");
            
            var fecha_cobranza = cambiarFormatoFecha(fecha[0]);
         
            $("#form_cobranzas_registrar")[0].reset();

            cuadros('#cuadro1', '#cuadro2');
            
            //----------------------------------------------
            if (registrar == 1) {
                $("#btn-save").attr("disabled", "disabled");
            }else{
                $("#btn-save").removeAttr("disabled");
            }
            $("#id_cliente").val(data.id_clientes);
            $("#id_cotizacion").val(data.id_cotizacion);
            $("#id_cobranza").val(data.id_cobranza);
            $("#rfc_cliente_cobranza_registrar").val(data.datos_clientes);
            $("#vendedor_cobranza_registrar").val(data.datos_vendores);
            $("#cotizacion_cobranza_registrar").val(data.numero_cotizacion);
            $("#status_cobranza_registrar").val(data.condicion);
            $("#fecha_cobranza_registrar").val(fecha_cobranza);
            $("#monto_cobranza_registrar").val(number_format((inNum(data.mensualidad) + inNum(data.inscripcion)), 2));
            $("#saldo_cobranza_registrar").val(number_format((inNum(data.mensualidad) + inNum(data.inscripcion)), 2));
            $("#plan_cobranza_registrar").val(data.planes);
            $("#paquete_cobranza_registrar").val(data.paquetes);
            $("#vigencia_cobranza_registrar").val(data.vigencia);
            if(data.imagenCliente=="")
                $("#imagen_registrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/default-img.png');
            else
                $("#imagen_registrar").attr('src', document.getElementById('ruta').value+'assets/cpanel/ClientePagador/images/'+data.imagenCliente);
            $("#monto_inscripcion_cobranza_registrar").html(number_format(data.inscripcion, 2));
            $("#monto_mensualidad_cobranza_registrar").html(number_format(data.mensualidad, 2));
            //--------------------------------------------------------------------
            eliminarOptions("banco");

            $.each(data.bancos, function(key, item){
                agregarOptions('#banco', item.id_banco, item.nombre_banco);
            });

            $("#banco option[value='0']").prop("selected",true);

            //--------------------------------------------------------------------
            var c = 0;
            var tr = "";
            //consultarTablaRecibos();
            getrecibo(data.id_cotizacion);
            verTablaRecibos(data.id_cotizacion, "#tableCobranza");


            GetBancos("#banco")
            //getSaldoTotalPendiente(data.id_cotizacion);
            //---------------------------------------------------------------------------
            //Datos para el modal:
            $("#id_cotizacionModalEc").val(data.id_cotizacion);
            $("#id_clienteModalEc").val(data.id_clientes);
            //----------------------------------------------------------------------------
            $('#comprobante_pago').fileinput('destroy');

            var base_url =  document.getElementById('ruta').value;
            $('#comprobante_pago').fileinput({
                theme: 'fa',
                language: 'es', 

                uploadAsync: true,
                showUpload: false, // hide upload button
                showRemove: false,
                uploadUrl: base_url+'uploads/upload/productos',
                uploadExtraData:{
                    name:$('#comprobante_pago').attr('id')
                },
                allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
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
            }).on('filedeleted', function() {
                console.log ('id =');
            });
        });
    }
    /*
    *   Obtener cuentas segun el banco seleccionado
    */
    function consultarCuentas(){
        var banco = $("#banco").val()
        var url = document.getElementById('ruta').value;
        var cobranza = document.getElementById('id_cobranza').value;
        $.ajax({
            "url":url+"MisCuentas/GetCuentasByBanco/",
            "type":'GET',
            "dataType":'JSON',
            "data": { 
                'id_banco' : banco
            },
            success: function(respuesta){
                $("#cuenta option").remove();
                $("#cuenta").append($('<option>',
                {
                    value: "",
                    text : "Seleccione"
                }));
                $.each(respuesta, function(i, item){
                    if (item.status) {
                        $("#cuenta").append($('<option>',{
                            value: item._id.$id,
                            text : item.clabe_cuenta+" / "+ item.numero_cuenta
                        }));
                    }
                });
            }
        });    
    }
    /***/
    /*
    *   Ver tabla de recibos
    */
    function verTablaRecibos(id_venta, tabla) {
        var url = document.getElementById('ruta').value;
        var cobranza = document.getElementById('id_cobranza').value;
        var datos = {'cobranza' : cobranza, 'id_cotizacion':id_venta}

        $(tabla+" tbody").html("");
                var url = document.getElementById('ruta').value;
                var table=$(tabla).DataTable({
                "destroy":true,
                "stateSave": true,
                "serverSide":false,
           
                "ajax":{
                    "type":"POST",
                    //"url": url + "Cobranza/getcobranzaventa",
                    "url": url + "Cobranza/tablaRecibos",
                    "data": datos,
                    "dataSrc":""
                },
                "columns":[
                    
                    {"data": 'tipo_operacion',
                         render : function(data, type, row) {
                            var btn = "";
                            if (data == "A") {
                                btn += "<span class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span><br><br>  ";

                                if (actualizar == 0) {
                                    btn += "<span class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";                                
                                }

                            }

                            if (tabla == "#tableCobranzaModal") {
                                return "";
                            }else{
                                return btn;
                            }

                        }
                    },

                    {"data": 'operacion',
                    },

                    {"data":"numero_recibo",
                    },

                    {"data":"mes",
                    },

                    {"data":"tipo_operacion",
                    },

                    {"data":"concepto",
                        render : function(data, type, row) {

                            if (row.saldo == 0) {
                                var chek = "<span class='btn btn-success btn-xs'><i class='fa fa-check' style='margin-bottom:5px'></i></span>"
                                return chek+" "+data;
                            }else{
                                 return data;
                            }

                        }
                    },


                    {"data":"fecha",
                        render : function(data, type, row) {
                            var valor = data.date;
                            fecha = valor.split(" ");
                            return cambiarFormatoFecha(fecha[0]);
                        }
                    },


                    {"data":"cargo",
                        render : function(data, type, row) {
                            return number_format(data, 2);
                        }
                    },

                    {"data":"abono",
                        render : function(data, type, row) {

                            if (row.tipo_operacion == "D") {
                                return "<span style='color: red'>-"+number_format(data, 2)+"</span>";
                            }else{
                                 return number_format(data, 2);
                            }
                        }
                    },

                    {"data":"saldo",
                        render : function(data, type, row) {
                            return number_format(data, 2);
                        }
                    },
                ],
                "searching": false,
                "ordering": false,
                "language": idioma_espanol,
                "dom": 'Bfrtip',
                "responsive": true,
                "buttons":[
                    
                ]
            });

            $(tabla+" tbody").off('click');
            //Modificar al realizar consultar/editar...    
            ver_recibos("#tableCobranza tbody", table);
            editar_recibos("#tableCobranza tbody", table);
    }
    /*
    *   consultarTablaRecibos
    */
    function consultarTablaRecibos(){
        var url = document.getElementById('ruta').value;
        var cobranza = document.getElementById('id_cobranza').value;
        $.ajax({
            "url":url+"Cobranza/tablaRecibos/",
            "type":'POST',
            "dataType":'JSON',
            "data": {'cobranza' : cobranza},
            success: function(respuesta){
            //-------------------------------
                var recibos = respuesta
                var tr = "";
                var c = 0;
                $.each(recibos, function(key, item){
                    var fecha_vect = item.fecha.date;
                    var fecha = fecha_vect.split(" ");
                    tr+= "<tr><th id='accion"+c+"'></th>"
                    tr+= "<th id='operacion"+c+"'>"+item.operacion+"</th>"
                    tr+= "<th id='numero_recibo"+c+"'>"+item.numero_recibo+"</th>"
                    tr+= "<th id='mes"+c+"'>"+item.mes+"</th>"
                    tr+= "<th id='tipo"+c+"'>"+item.tipo_operacion+"</th>"
                    tr+= "<th id='concepto"+c+"'>"+item.concepto+"</th>"
                    tr+= "<th id='fecha"+c+"'>"+fecha[0]+"</th>"
                    tr+= "<th id='cargo"+c+"' style='text-align:right'>"+item.cargo+"</th>"
                    tr+= "<th id='abono"+c+"' style='text-align:right'>"+item.abono+"</th>"
                    tr+= "<th id='saldo"+c+"' style='text-align:right'>"+item.saldo+"</th></tr>"
                    $("#tbody_cobranzas_detalle").html(tr);
                    c++;
                });
            //-------------------------------  
            }
        });
    }
    /*
    *   get Recibos: Obtiene los datos del recibo a cancelar
    */
    function getrecibo(id_cotizacion) {
        var url = document.getElementById('ruta').value;
        $.ajax({
            url:url+"Cobranza/getrecibopendiente",
            type:'GET',
            dataType:'JSON',
            data:{'id_cotizacion' : id_cotizacion},
            beforeSend: function(){
               // mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
            },
            error: function (repuesta) {
                $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                var errores=repuesta.responseText;
                if(errores!="")
                    mensajes('danger', errores);
                    
            },
            success: function(respuesta){
                console.log(respuesta)
                if(respuesta!=""){
                    //---
                    $("#id_recibo").val(respuesta.numero_recibo);
                    $("#id_recibo_pendiente").val(respuesta.numero_recibo);
                    $("#recibo").val(respuesta.numero_recibo);
                    //$("#fecha_cuota").val(respuesta.fecha);
                    $("#mes").val(respuesta.mes);
                    $("#monto").val(number_format(respuesta.saldo,2));
                    $("#monto_pago").val(number_format(respuesta.saldo,2));
                    $("#saldo_pendiente_total").val("0.00");
                    $("#saldo_pendiente_total_hidden").val("0");
                    $('input[type="submit"]').removeAttr('disabled'); //desactiva el input submit
                    $("#numero_secuencia").val(respuesta._id.$oid)
                    //---
                  //  $("#saldo_cobranza_registrar").val(number_format(respuesta.saldo_acumulado,2));
                    //---
                    //$("#saldo_pendiente_total").val(number_format(respuesta.saldo,2));
                    //$("#saldo_pendiente_total_hidden").val(respuesta.saldo);

                    /*if (respuesta == 1) {
                        $("#btn-save").attr("disabled", "disabled");

                        var status_ventas = $("#status_venta").val();
                        if (status_ventas != 4) {
                            devolution(id_venta);
                        }
                    }*/
                    //---
                }else{
                    $("#id_recibo").val();
                    $("#recibo").val();
                    //$("#fecha_cuota").val(respuesta.fecha);
                    $("#mes").val();
                    $("#monto").val();
                    $("#monto_pago").val();
                    $("#saldo_pendiente_total").val("0.00");
                    $("#saldo_pendiente_total_hidden").val("0");
                    $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
                    //----
                    $("#saldo_cobranza_registrar").val("0.00");
                }
            }
        });
    }
    /*
    *   getSaldoTotalPendiente
    */
    /*function getSaldoTotalPendiente(id_cotizacion) {
        var url = document.getElementById('ruta').value;
        $.ajax({
            url:url+"Cobranza/getSaldoTotalPendiente",
            type:'GET',
            dataType:'JSON',
            data:{'id_cotizacion' : id_cotizacion},
            
            success: function(respuesta){
               
               $("#saldo_pendiente_total").val(number_format(respuesta, 2));

               $("#saldo_pendiente_total_hidden").val(inNum(respuesta));
            }
        });
    }*/
    /*
    *   Al hacer change a las formas de pago
    */
    $("#fp").change(function() {

        if ($("#fp option:selected").text() == "EFECTIVO") {
            $("#banco").attr("disabled", "disabled");
            $("#tarjeta").css("display", "none");
            $("#numero_tarjeta").removeAttr("required");
            $("#cuenta").removeAttr("required");

        }else if ($("#fp option:selected").text() == "TRAJETA DE CRÉDITO") {
            //$("#banco").attr("disabled", "disabled");
            $("#tarjeta").css("display", "block");
            $("#banco").removeAttr("required");
            $("#cuenta").removeAttr("required");
            $("#numero_tarjeta").attr("required", "required");
            //------------------------------------------------
            $("#banco").attr("disabled", "disabled");
            $("#cuenta").attr("disabled", "disabled");
            //-----------------------------------------------
            //$("#cuenta").attr("disabled", "disabled");
        }else{
            $("#banco").removeAttr("disabled");
            $("#tarjeta").css("display", "none");
            $("#numero_tarjeta").removeAttr("required");

            $("#cuenta").removeAttr("disabled");

            $("#banco").attr("required", true);

            $("#cuenta").attr("required", true);
        }

        /*var id_proyecto = $("#proyecto").val();
        getBancosByProyecto(id_proyecto);*/        
    })
    /*
    *   Proceso de guardar
    */
    function guardarCobranza(){
        savepago("#form_cobranzas_registrar", 'Cobranza/registrar_cobranza', '#cuadro2');
    }
    /*
    *   Registro del pago
    */
    function savepago(form, controlador, cuadro){
        $(form).submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit

            var comprobantes = [];
             $("#form_cobranzas_registrar .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
                var file = [];
                file.push($(this).attr("title"));
                comprobantes.push(file);
            });



            var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
            var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
            

            for (var i = 0; i < comprobantes.length; i++) {
                formData.append('comprobantes[]', comprobantes[i]);
            }


            var method = $(this).attr('method'); //obtiene el method del formulario
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url:url+controlador,
                type:method,
                dataType:'text',
                data:formData,
                cache:false,
                contentType:false,
                processData:false,
                beforeSend: function(){
                    mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                    $("#btn-save").attr("disabled", "disabled");
                },
                error: function (repuesta) {
                    $('#btn-save').removeAttr('disabled'); //activa el input submit
                    var errores=repuesta.responseText;
                    if(errores!="")
                        mensajes('danger', errores);
                    else
                        mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");     

                },
                 success: function(respuesta){
                    console.log(respuesta);
                    if ($("#mes").val() != 0) {
                        //--Esta parte será modificada una vez se incluyan otros recibos
                        //var new_saldo = ((inNum($("#saldo_total_editar").text()) - inNum($("#monto_pago").val())) + monto_mora);

                        /*if (inNum($("#monto_pago").val()) > inNum($("#saldo_total_editar").text())) {
                            new_saldo = 0;
                        }
                        $("#saldo_total_editar").text(number_format(new_saldo, 2));
                        
                        var id_venta = $("#corrida").val();
                        updateSaldo(id_venta, inNum($("#monto_pago").val()));

                        getSaldoTotalPendiente(id_venta);*/
                    }

                    //cheksaldo();
                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    //$("#btn-save").removeAttr('disabled'); //activa el input submit
                    $("#recibo").val("");
                    $("#mes").val("");
                    $("#monto").val("");

                    $("#fp").val("");
                    $("#banco").val("");
                    $("#cuenta").val("");

                    if ($("#plazo").val() == "CONTADO") {
                        $("#fecha_pago").attr("readonly", "readonly");
                    }else{
                        $("#fecha_pago").val("");
                    }
                    $("#monto_pago").val("");

                    $("#numero_tarjeta").val("");

                    getrecibo($("#id_cotizacion").val());
                    verTablaRecibos($("#id_cotizacion").val(), "#tableCobranza");
                    //$("#tableCobranza").ajax.reload();

                    console.log(respuesta);
                    
                    if (respuesta == "") {
                        mensajes('success', "Operacion Exitosa");
                    }else{
                        mensajes('danger', respuesta);
                    }


                    //getAbonosVenta(id_venta);
                    $('#comprobante_pago').fileinput('destroy');
                    var base_url =  document.getElementById('ruta').value;
                    $('#comprobante_pago').fileinput({
                        theme: 'fa',
                        language: 'es', 

                        uploadAsync: true,
                        showUpload: false, // hide upload button
                        showRemove: false,
                        uploadUrl: base_url+'uploads/upload/productos',
                        uploadExtraData:{
                            name:$('#comprobante_pago').attr('id')
                        },
                        allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
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
                    }).on('filedeleted', function() {
                        console.log ('id =');
                    });
                }

            });
        });
    }
    /***/
    $("#monto_pago").keyup(function(){
        //saldo_pendiente();
        saldo_pendiente_total();
    });

    /*function saldo_pendiente() {
        var saldo      = inNum($("#monto").val());
        var monto_pago = inNum($("#monto_pago").val());
        if (monto_pago >= saldo) {
            var saldo_pendiente = 0;    
        }else{
            var saldo_pendiente = saldo - monto_pago;    
        }
        
        $("#saldo_pendiente").val(number_format(saldo_pendiente, 2));
    }*/


    function saldo_pendiente_total() {
        var saldo      = inNum($("#saldo_pendiente_total_hidden").val());
        var monto_pago = inNum($("#monto_pago").val());
        
        if (monto_pago >= saldo) {
            var saldo_pendiente = 0;    
        }else{
            var saldo_pendiente = saldo - monto_pago;    
        }
        
        $("#saldo_pendiente_total").val(number_format(saldo_pendiente, 2));
    }
    /*
    *   ver_recibos
    */
    function ver_recibos(tbody, table){
        //$(tbody).off('click');
        $(tbody).on("click", "span.consultar", function(){
            $("#alertas").css("display", "none");
            table.ajax.reload();
            var data = table.row( $(this).parents("tr") ).data();
            //--
            //Fecha:
            var valorfechaContable = data.fecha_contable.date;
            fechaCon = valorfechaContable.split(" ");
            fechaContable = cambiarFormatoFecha(fechaCon[0]);
            //
            var valorfechaMovimiento = data.fecha_movimiento.date;
            fechaMov = valorfechaMovimiento.split(" ");
            fechaMovimiento = cambiarFormatoFecha(fechaMov[0]);
            //--
            $("#modal_recibo").modal("show");
            
            $("#operacion_view").val(data.operacion);
            $("#recibo_view").val(data.numero_secuencia);
            $("#mes_view").val(data.mes);

            $("#monto_pago_view").val(number_format(data.monto_pago, 2));
            $("#fp_pago_view").val(data.fp);
            $("#banco_pago_view").val(data.nombre_banco);
            $("#cuenta_view").val(data.cuenta);
            $("#fecha_pago_view").val(fechaMovimiento);
            $("#fecha_contable_view").val(fechaContable);
            
            var base_url = document.getElementById('ruta').value;

              var files  = [];
              var config = [];
              url_imagen = base_url+'assets/cpanel/Cobranza/comprobantes/'

              if(data.comprobantes.length > 0){
                $(data.comprobantes).each(function(i, comprobante) {

                  
                    var ext = comprobante.file.split('.');
                    if (ext[1] == "pdf") {
                        comprobante_file = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+comprobante.file+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
                    }else{
                        comprobante_file = '<img src="'+url_imagen+comprobante.file+'" class="file-preview-image kv-preview-data">'
                    }

                    files.push(comprobante_file); 

                    var caption_file = {
                        caption: comprobante.file,downloadUrl: url_imagen+comprobante.file  ,url: base_url+"uploads/delete", key: comprobante.file
                    };

                    config.push(caption_file); 

                });
            }else{plano = ""}

            
              $('#comprobante_pago_view').fileinput("destroy");
              $('#comprobante_pago_view').fileinput({
                theme: 'fa',
                language: 'es', 

                uploadAsync: true,
                showUpload: false, // hide upload button
                showRemove: false,
                uploadUrl: base_url+'uploads/upload/productos',
                uploadExtraData:{
                    name:$('#comprobante_pago_view').attr('id')
                },
                allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
                overwriteInitial: false,
                maxFileSize: 5000,          
                maxFilesNum: 1,
                autoReplace:true,
                initialPreviewAsData: false,
                initialPreview: files,
                initialPreviewConfig: config,

                    //allowedFileTypes: ['image', 'video', 'flash'],
                    slugCallback: function (filename) {
                        return filename.replace('(', '_').replace(']', '_');
                    }
                }).on("filebatchselected", function(event, files) {
                  $(event.target).fileinput("upload");

                }).on("filebatchuploadsuccess",function(form, data){
                });
        });
    }
    /*
    *   editar recibos
    */
    function editar_recibos(tbody, table){
        $(tbody).on("click", "span.editar", function(){
            
            $("#alertas").css("display", "none");

            table.ajax.reload();

            var data = table.row( $(this).parents("tr") ).data();
            //Fecha:
            var valorfechaContable = data.fecha_contable.date;
            fechaCon = valorfechaContable.split(" ");
            fechaContable = cambiarFormatoFecha(fechaCon[0]);
            //
            var valorfechaMovimiento = data.fecha_movimiento.date;
            fechaMov = valorfechaMovimiento.split(" ");
            fechaMovimiento = cambiarFormatoFecha(fechaMov[0]);
            //fechMovi = fechaMovimiento.replace("-","/");
            //fechMovi2 = fechMovi.replace("-","/");
            //---
            var superFecha = new Date(fechaMovimiento);
            //---
            $("#modal_recibo_edit").modal("show");
            //---
            $("#id_recibo_edit").val(data.numero_recibo);
            $("#id_cobranza_edit").val(data.id_cobranza);
            $("#id_cotizacion_edit").val(data.id_cotizacion);
            //---
            $("#operacion_edit").val(data.operacion);
            $("#recibo_edit").val(data.numero_secuencia);
            $("#mes_edit").val(data.mes);
            $("#monto_pago_edit").val(number_format(data.monto_pago, 2));
            $("#fp_pago_edit").val(data.fp);
            $("#banco_pago_edit").val(data.nombre_banco);
            $("#cuenta_edit").val(data.cuenta);
            $("#fecha_pago_edit").val(fechaMovimiento);
            //$("#fecha_contable_edit").val(superFecha);
           
            $("#fecha_contable_edit").attr('value',fechaMovimiento);

            var base_url = document.getElementById('ruta').value;

              var files  = [];
              var config = [];
              url_imagen = base_url+'assets/cpanel/Cobranza/comprobantes/'

              if(data.comprobantes.length > 0){
                $(data.comprobantes).each(function(i, comprobante) {
                    
                    var ext = comprobante.file.split('.');
                    if (ext[1] == "pdf") {
                        comprobante_file = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+comprobante.file+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
                    }else{
                        comprobante_file = '<img src="'+url_imagen+comprobante.file+'" class="file-preview-image kv-preview-data">'
                    }

                    files.push(comprobante_file); 

                    var caption_file = {
                        caption: comprobante.file,downloadUrl: url_imagen+comprobante.file  ,url: base_url+"Cobranza/deleteComprobante/"+comprobante.id_recibos_cobranza_comprobantes, key: comprobante.file
                    };

                    config.push(caption_file); 

                });

                // plano += '<input name="plano_editar" value="'+data.plano+'" type="hidden">'
            }else{plano = ""}

            //-------------------------------------------------------
            $('#comprobante_pago_edit').fileinput("destroy");
            $('#comprobante_pago_edit').fileinput({
                theme: 'fa',
                language: 'es', 

                uploadAsync: true,
                showUpload: false, // hide upload button
                showRemove: false,
                uploadUrl: base_url+'uploads/upload/productos',
                uploadExtraData:{
                    name:$('#comprobante_pago_edit').attr('id')
                },
                allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
                overwriteInitial: false,
                maxFileSize: 5000,          
                maxFilesNum: 1,
                autoReplace:true,
                initialPreviewAsData: false,
                initialPreview:files,
                initialPreviewConfig:config,

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
                swal("Eliminado!", "Archivo Eliminado Con Exito", "success");
                $("#modal_recibo_edit").modal("hide");
                verTablaRecibos($("#id_cotizacion").val(), "#tableCobranza");
            });
            //-------------------------------------------------------
             
        });
    }
     function editar_pago(){
        editar_pagos("#form_edit_recibo", 'Cobranza/editPago', '#cuadro7');
    }

    function editar_pagos(form, controlador, cuadro){
        $(form).submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit

            var comprobantes = [];
             $("#form_edit_recibo .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
                var file = [];
                file.push($(this).attr("title"));
                comprobantes.push(file);
            });



            var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
            var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
        

            for (var i = 0; i < comprobantes.length; i++) {
                formData.append('comprobantes[]', comprobantes[i]);
            }

            console.log(formData);
            
            var method = $(this).attr('method'); //obtiene el method del formulario
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url:url+controlador,
                type:method,
                dataType:'text',
                data:formData,
                cache:false,
                contentType:false,
                processData:false,
                beforeSend: function(){
                    mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                    $("#btn-edit-recibo").attr("disabled", "disabled");
                },
                error: function (repuesta) {
                    $('#btn-edit-recibo').removeAttr('disabled'); //activa el input submit
                    var errores=repuesta.responseText;
                    if(errores!="")
                        mensajes('danger', errores);
                    else
                        mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");     

                },
                 success: function(respuesta){
                    $('#btn-edit-recibo').removeAttr('disabled');

                    mensajes('success', "Operacion Exitosa");
                    $("#modal_recibo_edit").modal("hide");
                    swal("Exito!", "El recibo fue actualizado", "success");

                    verTablaRecibos($("#id_cotizacion").val(), "#tableCobranza");

                }

            });
        });
    }

    /*
    *   Envio de email
    */
    $("#send-mail").click(function(){
        var id_venta      = $("#id_cotizacionModalEc").val();
        var id_cliente    = $("#id_clienteModalEc").val();
        var url=document.getElementById('ruta').value;
        $.ajax({
            url:url+"Cobranza/sendEmail/"+id_venta+"/"+id_cliente,
            type:'GET',
            dataType:'JSON',
            beforeSend: function(){
                $("#send-mail").attr("disabled", "disabled");
               // mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
            },
            error: function (repuesta) {
                $("#send-mail").removeAttr('disabled');
               /// $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                warning("A ocurrido un Error");
                    
            },
            success: function(respuesta){
                $("#send-mail").removeAttr('disabled');
                warning("Se ha enviado un correo al email del cliente");
               
            }
        });
    });

    /*
    *   Fin de metodos modificados por Gianni Santucci
    */
      
    /* ------------------------------------------------------------------------------- */
    /* 
        Funcion para cargar los datos de la base de datos en la tabla.
    */
    function listar_detalle(cuadro, id){
        $('#tabla tbody').off('click');
        cuadros(cuadro, "#cuadro1");
        var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
        var table=$("#tabla").DataTable({
            "destroy":true,
            "stateSave": true,
            "serverSide":false,
            "ajax":{
                "method":"POST",
                "url":url+"Corrida/getventasbyproyectodetalle/"+id,
                "dataSrc":""
            },
            "columns":[
                {"data": "id_venta",
                    render : function(data, type, row) {
                        return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
                    }
                },


                 {"data": null,
                        render : function(data, type, row) {
                            var botones = "";

                            if (registrar == 0) {
                                var icon = "<i class='fa fa-dollar' style='margin-bottom:5px'></i>";
                            }else{
                                var icon = "<i class='fa fa-eye' style='margin-bottom:5px'></i>";
                            }
                            if(consultar == 0)
                                botones += "<span class='consultar btn btn-xs btn-success waves-effect admin' data-toggle='tooltip' title='Cobranza'>"+icon+"</span> ";
                            
                            // if(data.status == 0)
                            //     botones += "<span class='aprobar btn btn-xs btn-success waves-effect' data-toggle='tooltip' title='Aprobar'><i class='fa fa-check' style='margin-bottom:5px'></i></span> ";


                            // if(data.status == 0)
                            //     botones += "<span class='cancelar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Cancelar'><i class='fa fa-remove' style='margin-bottom:5px'></i></span>";


                            // if(data.status == 1)
                            //     botones += "<span class='docs btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Ver Documentos'><i class='fa fa-file-word-o' style='margin-bottom:5px'></i></span>";
                            if (row.indicador != 1) {
                                return botones;
                            }else{
                                return "";
                            }
                        }
                    },

               {"data":"id_venta"},


                {"data":"nombre_cliente",
                    render : function(data, type, row) {
                        return data;
                     }
                },
                {"data":"nombre_vendedor",
                    render : function(data, type, row) {
                        return data+" "+row.apellido_p_vendedor+" "+row.apellido_m_vendedor;
                     }
                },

                {"data":"nombre_producto",
                    render : function(data, type, row) {
                        if (row.cantidad_producto > 1) {
                            var add = "";
                        }else{
                            var add = "";
                        }
                        return data+add;
                     },
                },


                {"data":"nombre_inmobiliaria", 
                    render : function(data, type, row) {
                        if (row.indicador != 1) {
                           return data;
                        }else{
                            return "";
                        }
                        
                     },
                },
                {"data":"saldo",
                    render : function(data, type, row) {
                        return number_format(data, 2);
                     },
                },

                {"data":"status",
                    render : function(data, type, row) {
                        if (row.indicador == 1){
                            return "";
                        }
                        
                        if (data == 0) {
                            return "COTIZACION";
                        }

                        if (data == 1) {
                            return "VENTA";
                        }

                        if (data == 2) {
                            return "FINALIZADA";
                        }
                        if (data == 3) {
                            return "CANCELADA";
                        }

                        if (data == 4) {
                            return "Aprobada";
                        }

                        
                     },
                },
                {"data":"fecha_regsitro",
                    render : function(data, type, row) {
                        if (row.indicador != 1) {
                            return cambiarFormatoFecha(data);
                        }else{
                            return "";
                        }

                    }
                },
                {"data":"user_regis"},
                  
            ],
            "language": idioma_espanol,
            "dom": 'Bfrtip',
            "buttons":[
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        admin("#tabla tbody", table);
        viewProductos("#tabla tbody", table);

    }

   $("#btn-detalle").click(function(){
        var id = $("#filter").val();
        listar_detalle("", id);
    });



    function regresar_lval(cuadroOcultar){


        if (($("#plazo").val() == "CONTADO") && ($("#recibo").val() != "")) {
            alertaPagoContado(cuadroOcultar);
        }else{
            var lval = $("#filter").val();
            listar(cuadroOcultar, lval);
        }
    }



    function alertaPagoContado(cuadroOcultar){
        swal({
            title: "Aun no ha completado de pagar el saldo, ¿realmente desea salir? se borraran todos los pagos registrados",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Salir",
            cancelButtonText: "Quedarme Aqui!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                var lval = $("#filter").val();
                borarpagos();
                listar(cuadroOcultar, lval);
            } else {

                swal("Cancelado", "Continue", "error");
                return false;
            }
        });
    }


    function borarpagos() {
      var id_venta = $("#id_venta").val();

      var url=document.getElementById('ruta').value;

      $.ajax({
        url:url+"Cobranza/deleteabonos",
        type:'GET',
        dataType:'JSON',
        data:{'id_venta' : id_venta},
        async: false,
        beforeSend: function(){
           // mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
        },
        error: function (repuesta) {
           // $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
            var errores=repuesta.responseText;
            if(errores!="")
                mensajes('danger', errores);
            else
                mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
        },
        success: function(respuesta){
           
        }
     });
    }

    function viewProductos(tbody, table){
        $(tbody).on("click", "button.view_productos", function(){
            $("#alertas").css("display", "none");
            var data = table.row( $(this).parents("tr") ).data();

            buscarProductos('#tableCantidadProductos', data.id_venta);
            
        });
    }




    function buscarProductos(tabla, venta){
       $(tabla + " tbody").html("");
            var url = document.getElementById('ruta').value;
            var table=$(tabla).DataTable({
            "destroy":true,
            "stateSave": true,
            "serverSide":false,
            "ajax":{
                "method":"GET",
                "url": url + "Corrida/getproductoventa",
                "data": {'id_venta' : venta},
                "dataSrc":"",
                "async": false
            },
            "columns":[
                
                {"data":"name_producto",
                    render : function(data, type, row) {
                        return row.name_producto+" <input type='hidden' class='id_producto_v' value='"+row.id_producto+"'>";
                    }
                },
                {"data":"nom_etapa"},

                {"data":"nom_zona"},

                {"data":"lote_anterior"},

                {"data":"lote_nuevo"},

                {"data":"superficie",
                    render : function(data, type, row) {
                        return number_format(row.superficie, 2);
                    }   
                },

                {"data":"precio_m2",
                    render : function(data, type, row) {
                        return number_format(inNum(row.precio_m2) / inNum(row.superficie), 2);
                    }   
                },

                {"data":"precio_m2",
                    render : function(data, type, row) {
                        return number_format(row.precio_m2, 2);
                    }   
                }
            ],
            "language": idioma_espanol,
            "dom": 'Bfrtip',
            "responsive": true,
            "buttons":[
                
            ]
        });
    }



    function getAbonosVenta(id_venta) {
       var url = document.getElementById('ruta').value;
        $.ajax({
            url:url+"Cobranza/getabonos/"+id_venta,
            type:'GET',
            dataType:'JSON',
            
            success: function(respuesta){
               var total = 0;
               $.each(respuesta, function(key, item){
                    total = number_format(inNum(total) + inNum(item.abono), 2);
               });
               
               $("#monto_pagado").text(total);
               
            }
        });
    }

    


    $("#estado_cuenta").click(function(){
        verTablaRecibos($("#id_venta").val(), "#tableCobranzaModal");
        $("#pdf").attr("href", "Cobranza/pdfcobranza/"+$("#id_cotizacion").val());

    });



    

    function devolution(id_venta) {
       var url = document.getElementById('ruta').value;
        $.ajax({
            url:url+"Cobranza/devolution",
            type:'GET',
            dataType:'JSON',
            data:{'id_venta' : id_venta},
            async: false,
            beforeSend: function(){
               // mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
            },
            error: function (repuesta) {
                $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                var errores=repuesta.responseText;
                if(errores!="")
                    mensajes('danger', errores);
                    
            },
            success: function(respuesta){
                if (respuesta == 1){
                    verCorrida(id_venta, "#tableCobranza");
                }
               
            }
        });
    }

    function buscar_credito(id_venta) {
        var url = document.getElementById('ruta').value;
        $.ajax({
            url:url+"Cobranza/buscar_credito",
            type:'GET',
            dataType:'JSON',
            data:{'id_venta' : id_venta},
            async: false,
            beforeSend: function(){
               // mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
            },
            error: function (repuesta) {
                $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                var errores=repuesta.responseText;
                if(errores!="")
                    mensajes('danger', errores);
                    
            },
            success: function(respuesta){
                $("#saldo_total_editar").text(number_format(respuesta, 2));

                getSaldoTotalPendiente(id_venta);

               // $("#saldo_pendiente_total").val(number_format(respuesta, 2));
               
            }
        });
    }



    



    /*function ver_recibos(tbody, table){
        $(tbody).on("click", "span.consultar", function(){
            $("#alertas").css("display", "none");

            var data = table.row( $(this).parents("tr") ).data();
            
            $("#modal_recibo").modal("show");

            $("#operacion_view").val(data.operacion);
            $("#recibo_view").val(data.recibo);
            $("#mes_view").val(data.mes);

            $("#monto_pago_view").val(number_format(data.monto_pago, 2));
            $("#fp_pago_view").val(data.fp);
            $("#banco_pago_view").val(data.nombre_banco);
            $("#cuenta_view").val(data.numero_cuenta);
            $("#fecha_pago_view").val(data.fecha_movimiento);
            $("#fecha_contable_view").val(data.fecha_contable);
            
            var base_url = document.getElementById('ruta').value;

              var files  = [];
              var config = [];
              url_imagen = base_url+'assets/cpanel/Cobranza/comprobantes/'

              if(data.comprobantes.length > 0){
                $(data.comprobantes).each(function(i, comprobante) {


                    
                    var ext = comprobante.file.split('.');
                    if (ext[1] == "pdf") {
                        comprobante_file = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+comprobante.file+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
                    }else{
                        comprobante_file = '<img src="'+url_imagen+comprobante.file+'" class="file-preview-image kv-preview-data">'
                    }

                    files.push(comprobante_file); 

                    var caption_file = {
                        caption: comprobante.file,downloadUrl: url_imagen+comprobante.file  ,url: base_url+"uploads/delete", key: comprobante.file
                    };

                    config.push(caption_file); 

                });

                // plano += '<input name="plano_editar" value="'+data.plano+'" type="hidden">'
            }else{plano = ""}


              $('#comprobante_pago_view').fileinput("destroy");
              $('#comprobante_pago_view').fileinput({
                theme: 'fa',
                language: 'es', 

                uploadAsync: true,
                showUpload: false, // hide upload button
                showRemove: false,
                uploadUrl: base_url+'uploads/upload/productos',
                uploadExtraData:{
                    name:$('#comprobante_pago_view').attr('id')
                },
                allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
                overwriteInitial: false,
                maxFileSize: 5000,          
                maxFilesNum: 1,
                autoReplace:true,
                initialPreviewAsData: false,
                initialPreview: files,
                initialPreviewConfig: config,

                //allowedFileTypes: ['image', 'video', 'flash'],
                slugCallback: function (filename) {
                    return filename.replace('(', '_').replace(']', '_');
                }
            }).on("filebatchselected", function(event, files) {
              $(event.target).fileinput("upload");

            }).on("filebatchuploadsuccess",function(form, data){
              
              //console.log(data.response)
            });

        });
    }*/

    /*function editar_recibos(tbody, table){
        $(tbody).on("click", "span.editar", function(){
            $("#alertas").css("display", "none");
            var data = table.row( $(this).parents("tr") ).data();
            $("#modal_recibo_edit").modal("show");

            $("#id_recibo_edit").val(data.id);
            $("#id_venta_edit").val(data.id_venta);

            $("#operacion_edit").val(data.operacion);
            $("#recibo_edit").val(data.recibo);
            $("#mes_edit").val(data.mes);
            $("#monto_pago_edit").val(number_format(data.monto_pago, 2));
            $("#fp_pago_edit").val(data.fp);
            $("#banco_pago_edit").val(data.nombre_banco);
            $("#cuenta_edit").val(data.numero_cuenta);
            $("#fecha_pago_edit").val(data.fecha_movimiento);
            $("#fecha_contable_edit").val(data.fecha_contable);
            
            var base_url = document.getElementById('ruta').value;

              var files  = [];
              var config = [];
              url_imagen = base_url+'assets/cpanel/Cobranza/comprobantes/'

              if(data.comprobantes.length > 0){
                $(data.comprobantes).each(function(i, comprobante) {
                    
                    var ext = comprobante.file.split('.');
                    if (ext[1] == "pdf") {
                        comprobante_file = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+comprobante.file+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
                    }else{
                        comprobante_file = '<img src="'+url_imagen+comprobante.file+'" class="file-preview-image kv-preview-data">'
                    }

                    files.push(comprobante_file); 

                    var caption_file = {
                        caption: comprobante.file,downloadUrl: url_imagen+comprobante.file  ,url: base_url+"Cobranza/deleteComprobante/"+comprobante.id_recibos_cobranza_comprobantes, key: comprobante.file
                    };

                    config.push(caption_file); 

                });

                // plano += '<input name="plano_editar" value="'+data.plano+'" type="hidden">'
            }else{plano = ""}


              $('#comprobante_pago_edit').fileinput("destroy");
              $('#comprobante_pago_edit').fileinput({
                theme: 'fa',
                language: 'es', 

                uploadAsync: true,
                showUpload: false, // hide upload button
                showRemove: false,
                uploadUrl: base_url+'uploads/upload/productos',
                uploadExtraData:{
                    name:$('#comprobante_pago_edit').attr('id')
                },
                allowedFileExtensions: ["jpg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
                overwriteInitial: false,
                maxFileSize: 5000,          
                maxFilesNum: 1,
                autoReplace:true,
                initialPreviewAsData: false,
                initialPreview: files,
                initialPreviewConfig: config,

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
                swal("Eliminado!", "Archivo Eliminado Con Exito", "success");
                $("#modal_recibo_edit").modal("hide");
                verCorrida($("#id_venta").val(), "#tableCobranza");



            });

        });
    }*/



    


    function getBancosByProyecto(id_proyecto) {
       var url           = document.getElementById('ruta').value;

        $.ajax({
            url:url+"MisCuentas/getBancosByProyecto",
            type:'GET',
            dataType:'JSON',
            data:{'id_proyecto' : id_proyecto},
            beforeSend: function(){
               // mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
            },
            error: function (repuesta) {
               /// $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                var errores=repuesta.responseText;
                if(errores!="")
                    mensajes('danger', errores);
                    
            },
            success: function(respuesta){
                $("#banco option").remove();
                $("#banco").append($('<option>',
                {
                    value: "",
                    text : "Seleccione"
                }));
                $.each(respuesta, function(i, item){
                    //if (item.status == 1) {
                        $("#banco").append($('<option>',
                         {
                            value: item.id_banco,
                            text : item.nombre_banco
                        }));
                    //}
                });
            }
        });
    }


    $("#fecha_pago").change(function(){
        //saldo_pendiente();
        saldo_pendiente_total()

        //calcular_mora();

        $("#fecha_contable").val($(this).val());
    });


    function calcular_mora(){
        var url           = document.getElementById('ruta').value;

        var id_venta      = $("#id_venta").val();
        var recibo        = $("#recibo").val();
        var fecha_pago    = $("#fecha_pago").val();
        var fecha_cuota   = $("#fecha_cuota").val();
        var monto         = $("#monto").val();

        $.ajax({
            url:url+"Cobranza/calcularmora",
            type:'GET',
            dataType:'JSON',
            data:{'id_venta' : id_venta, 'recibo' : recibo, 'fecha_pago' : fecha_pago, "fecha_cuota": fecha_cuota, "monto" : monto},
            beforeSend: function(){
               // mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
            },
            error: function (repuesta) {
               /// $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                var errores=repuesta.responseText;
                if(errores!="")
                    mensajes('danger', errores);
                    
            },
            success: function(respuesta){
                if (respuesta != "N") {
                    $("#dias_mora").val(respuesta.dias);
                    $("#porcentaje").val(respuesta.porcentaje);
                    $("#monto_mora").val(respuesta.total_mora);
                }else{
                    $("#dias_mora").val("");
                    $("#porcentaje").val("");
                    $("#monto_mora").val("");
                }
            }
        });
    }

    





    function inNum(monto) {
        var cantidad           = monto;
        var myNumeral          = numeral(cantidad);
        return myNumeral.value();
    }




   



    /*function registrar_pago(){
        savepago("#form_save_pago", 'Cobranza/savepago', '#cuadro7');
    }


    function savepago(form, controlador, cuadro){
        $(form).submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit

            if (inNum($("#monto_pago").val()) > inNum($("#monto").val())) {
                alertaPago();
                return false;
            }


            var comprobantes = [];
             $("#form_save_pago .kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
                var file = [];
                file.push($(this).attr("title"));
                comprobantes.push(file);
            });



            var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
            var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
            

            for (var i = 0; i < comprobantes.length; i++) {
                formData.append('comprobantes[]', comprobantes[i]);
            }


            var method = $(this).attr('method'); //obtiene el method del formulario
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url:url+controlador,
                type:method,
                dataType:'text',
                data:formData,
                cache:false,
                contentType:false,
                processData:false,
                beforeSend: function(){
                    mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                    $("#btn-save").attr("disabled", "disabled");
                },
                error: function (repuesta) {
                    $('#btn-save').removeAttr('disabled'); //activa el input submit
                    var errores=repuesta.responseText;
                    if(errores!="")
                        mensajes('danger', errores);
                    else
                        mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");     

                },
                 success: function(respuesta){

                    if (inNum($("#monto_mora").val()) > 0) {
                        var monto_mora = inNum($("#monto_mora").val());
                    }else{
                         var monto_mora = 0;
                    }
                    if ($("#mes").val() != 0) {
                        var new_saldo = ((inNum($("#saldo_total_editar").text()) - inNum($("#monto_pago").val())) + monto_mora);

                        if (inNum($("#monto_pago").val()) > inNum($("#saldo_total_editar").text())) {
                            new_saldo = 0;
                        }
                        $("#saldo_total_editar").text(number_format(new_saldo, 2));

                       
                        

                        var id_venta = $("#corrida").val();
                        updateSaldo(id_venta, inNum($("#monto_pago").val()));

                        getSaldoTotalPendiente(id_venta);
                    }

                    cheksaldo();
                    $("#btn-save").removeAttr('disabled'); //activa el input submit
                    $("#recibo").val("");
                    $("#mes").val("");
                    $("#monto").val("");

                    $("#fp").val("");
                    $("#banco").val("");
                    $("#cuenta").val("");

                    if ($("#plazo").val() == "CONTADO") {
                        $("#fecha_pago").attr("readonly", "readonly");
                    }else{
                        $("#fecha_pago").val("");
                    }
                    $("#monto_pago").val("");
                    $("#saldo_pendiente").val("");

                    $("#dias_mora").val("");
                    $("#porcentaje").val("");
                    $("#monto_mora").val("");


                    $("#numero_tarjeta").val("");


                    getrecibo($("#id_venta").val());
                    verCorrida($("#id_venta").val(), "#tableCobranza");

                    if (respuesta == "") {
                        mensajes('success', "Operacion Exitosa");
                    }else{
                        mensajes('danger', respuesta);
                    }



                    // var monto_pagado = inNum($("#monto_total_editar").text()) - inNum($("#saldo_total_editar").text());
                    // $("#monto_pagado").text(number_format(monto_pagado, 2));

                    getAbonosVenta(id_venta);




                    $('#comprobante_pago').fileinput('clear');
                    var base_url =  document.getElementById('ruta').value;
                    $('#comprobante_pago').fileinput({
                        theme: 'fa',
                        language: 'es', 

                        uploadAsync: true,
                        showUpload: false, // hide upload button
                        showRemove: false,
                        uploadUrl: base_url+'uploads/upload/productos',
                        uploadExtraData:{
                            name:$('#comprobante_pago').attr('id')
                        },
                        allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
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
                    }).on('filedeleted', function() {
                        console.log ('id =');
                    });



                }

            });
        });
    }*/



    function savepago2(form, controlador, cuadro){
        
        var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>


        var comprobantes = [];
             $(".kv-preview-thumb .file-thumbnail-footer .file-footer-caption").each(function() {
                var file = [];
                file.push($(this).attr("title"));
                comprobantes.push(file);
            });




        var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros

         for (var i = 0; i < comprobantes.length; i++) {
                formData.append('comprobantes[]', comprobantes[i]);
            }
       
        $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
        $.ajax({
            url:url+controlador,
            type:"POST",
            dataType:'text',
            data:formData,
            cache:false,
            contentType:false,
            processData:false,
            beforeSend: function(){
                mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                $("#btn-save").attr("disabled", "disabled");
            },
            error: function (repuesta) {
                $('#btn-save').removeAttr('disabled'); //activa el input submit
                var errores=repuesta.responseText;
                if(errores!="")
                    mensajes('danger', errores);
                else
                    mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");     

            },
             success: function(respuesta){


                if (inNum($("#monto_mora").val()) > 0) {
                    var monto_mora = inNum($("#monto_mora").val());
                }else{
                     var monto_mora = 0;
                }

                if ($("#mes").val() != 0) {
                     var new_saldo = ((inNum($("#saldo_total_editar").text()) - inNum($("#monto_pago").val())) + monto_mora);

                    if (inNum($("#monto_pago").val()) > inNum($("#saldo_total_editar").text())) {
                        new_saldo = 0;
                    }
                    $("#saldo_total_editar").text(number_format(new_saldo, 2));
                    //$("#saldo_pendiente_total").val(number_format(new_saldo, 2));

                    var id_venta = $("#corrida").val();

                    updateSaldo(id_venta, inNum($("#monto_pago").val()));

                    getSaldoTotalPendiente(id_venta);
                }


                cheksaldo();


                $("#btn-save").removeAttr('disabled'); //activa el input submit
                $("#recibo").val("");
                $("#mes").val("");
                $("#monto").val("");

                $("#fp").val("");
                $("#banco").val("");
                $("#cuenta").val("");
                
                if ($("#plazo").val() == "CONTADO") {
                    $("#fecha_pago").attr("readonly", "readonly");
                }else{
                    $("#fecha_pago").val("");
                }

                $("#monto_pago").val("");
                $("#saldo_pendiente").val("");

                $("#dias_mora").val("");
                $("#porcentaje").val("");
                $("#monto_mora").val("");


                getrecibo($("#id_venta").val());
                verCorrida($("#id_venta").val(), "#tableCobranza");

                if (respuesta == "") {
                    mensajes('success', "Operacion Exitosa");
                }else{
                    mensajes('danger', respuesta);
                }



                // var monto_pagado = inNum($("#monto_total_editar").text()) - inNum($("#saldo_total_editar").text());
                // $("#monto_pagado").text(number_format(monto_pagado, 2));
                getAbonosVenta(id_venta);



                $('#comprobante_pago').fileinput('destroy');
                var base_url =  document.getElementById('ruta').value;
                $('#comprobante_pago').fileinput({
                    theme: 'fa',
                    language: 'es', 

                    uploadAsync: true,
                    showUpload: false, // hide upload button
                    showRemove: false,
                    uploadUrl: base_url+'uploads/upload/productos',
                    uploadExtraData:{
                        name:$('#comprobante_pago').attr('id')
                    },
                    allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf", "doc", "xlsx", "jpeg","docx"],
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
                }).on('filedeleted', function() {
                    console.log ('id =');
                });

                

            }

        });
    }




    function alertaPago(){
        swal({
            title: "¿El monto del pago es mayor al Saldo, está seguro de aplicar el pago?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Continuar",
            cancelButtonText: "No, Cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                console.log("ENVIANDO FORM")
                // $("#form_save_pago").get(0).submit();
                 savepago2("#form_save_pago", 'Cobranza/savepago', '#cuadro7');

            } else {
                swal("Cancelado", "Proceso cancelado", "error");
                return false;
            }
        });
    }


    function updateSaldo(id_venta, monto_pago) {
        var url=document.getElementById('ruta').value; 

    
        var data = {
            "id_venta"   : id_venta,
            "monto_pago" : monto_pago 
        }

       $.ajax({
            url:url+"Cobranza/updateSaldo",
            type:'GET',
            dataType:'JSON',
            data:data,
            async: false,
            beforeSend: function(){
               // mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
            },
            error: function (repuesta) {
               /// $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                var errores=repuesta.responseText;
                if(errores!="")
                    mensajes('danger', errores);
                    
            },
            success: function(respuesta){
                
            }
        })
    }


    function cheksaldo() {

       var url=document.getElementById('ruta').value; 

       var id_venta      = $("#id_venta").val();

       $.ajax({
            url:url+"Cobranza/checksaldoventa",
            type:'GET',
            dataType:'JSON',
            data:{'id_venta' : id_venta},
            async: false,
            beforeSend: function(){
               // mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
            },
            error: function (repuesta) {
               /// $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                var errores=repuesta.responseText;
                if(errores!="")
                    mensajes('danger', errores);
                    
            },
            success: function(respuesta){
                
            }
        });
    }



    /*$("#banco").change(function(){
        var url=document.getElementById('ruta').value;

        var id_banco = $(this).val();

        var id_proyecto = $("#proyecto").val();

        $.ajax({
            url:url+"MisCuentas/getcuentabancoByProyecto/"+id_banco+"/"+id_proyecto,
            type:'GET',
            dataType:'JSON',
            beforeSend: function(){
                $("#cuenta").attr("disabled", "disabled");
                $("#cuenta option").remove();   
                $("#cuenta").append($('<option>',
                {
                    value: "",
                    text : "Espere por favor...",
                }));
               // mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
            },
            error: function (repuesta) {
                $("#cuenta").removeAttr('disabled');
                $("#cuenta option").remove();   
                $("#cuenta").append($('<option>',
                {
                    value: "",
                    text : "Seleccione",
                }));
               /// $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                warning("A ocurrido un Error");
                    
            },
            success: function(respuesta){
                $("#cuenta").removeAttr('disabled');
                $("#cuenta option").remove();   

                $("#cuenta").append($('<option>',
                {
                    value: "",
                    text : "Seleccione",
                }));

                $.each(respuesta, function(i, item){
                    $("#cuenta").append($('<option>',
                     {
                        value: item.id_cuenta,
                        text : item.clabe_cuenta+" / "+item.numero_cuenta,//+" --- "+number_format(item.precio,2)
                    }));
                });
        

               
            }
        });
    });*/




    
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que filtra por tipo de valor en la tabla
    */
    function filtrar(value){
        listar("#cuadro33", value);
        $('#tabla').DataTable().search("").draw();
    }
/* ------------------------------------------------------------------------------- */




$(".monto_formato_decimales").change(function() {   
       if($(this).val() != ""){  
        $(this).val(number_format($(this).val(), 2));   
        
    }       
});




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

function valida(e){
    tecla = (document.all) ? e.keyCode : e.which;          
    patron =/^([0-9])*[.]?[0-9]*$/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}






function GetBancos(select){

  var url=document.getElementById('ruta').value;
  $.ajax({
     url:url+"MisCuentas/GetBancosCobranza/",
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
        
        $(select).append($('<option>',
        {
          value: item.id_banco,
          text : item.name_banco
        }));
        
      });

    }
  });
}