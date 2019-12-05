<div class="container-fluid">
    <div class="row">
        <!-- Column -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Lista de Eventos</h4>
                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="buscador"  name="buscador" onkeyup="buscar(this.value)" placeholder="Titulo de Evento O Autor!">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-info" onclick="cargar_eventos(); $('#buscador').val('')" type="button">Reiniciar!</button>
                                                        </span>
                                                    </div>
                                                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="20%" colspan=1>Titulo</th>
                                <th colspan=1>Autor</th>
                                <th colspan=1>Descripción</th>
                                <th colspan=1>Fecha</th>
                                <th colspan=1>Acciones</th>
                                
                            </tr>
                        </thead>
                        <tbody id="tabla-entradas">
                            <tr>
                                <th></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        <!-- Column -->
    </div>
</div>


<div class="modal fade bs-example-modal-lg" id="modal_ver" tabindex="-1" role="dialog" aria-labelledby="Titulo" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="Titulo"></h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <img class="img-fluid mr-auto ml-auto d-block" src="" id="img" alt="">
                <div id="contenido" class="pt-2 pb-2 pr-3 pl-3 text-justify">
              
                </div>
           </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade bs-example-modal-lg" id="modal_editar" tabindex="-1" role="dialog" aria-labelledby="Titulo" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="">Editar entrada</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--  FORM  -->
                <form class="form-material  row pl-2 pr-2" id="cargar_contenido">
                    <div class="form-group col-md-4 ">
                        <div class="form-group">
                            <label>Titulo</label>
                            <input type="text" class="form-control form-control-line" name="titulo_editar" id="titulo_editar" value="" required> </div>
                    </div>
               

                        <div class="form-group col-md-4 ">
                            <div class="form-group">
                                <label>Tipo de contenido</label>
                                <select class="form-control" name="tipo_editar" id="tipo_contenido">
                                    <option>Evento</option>
                                    <option>Noticia</option>
                                </select>
                            </div>
                        </div>

                            <div class="form-group col-md-4 ">
                                <div class="form-group">
                                    <label>Fecha del contenido</label>
                                    <input type="text" name="fecha_editar" id="fecha_editar" class="form-control form-control-line mydatepicker" placeholder="Mes/Dia/Año"
                                        autocomplete="off">
                                </div>
                            </div>
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label>Autor</label>
                            <textarea class="form-control" maxlength="100" placeholder="max. 100 caracteres" required rows="1" name="autor_editar" id="autor_editar"></textarea>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label>Descripción</label>
                            <textarea class="form-control" maxlength="100" placeholder="max. 100 caracteres" required rows="1" name="descripcion_editar" id="descripcion_editar"></textarea>
                            </div>
                        </div>

                    <div class="col-lg-6">
                            <div class="form-group col-md-12 parrafos" id="parrafo_1">
                                <div class="form-group">
                                    <label>Contenido</label>
                                        <textarea class="textarea_editor form-control" placeholder="carapteres ilimitados" spellcheck="true" required rows="6"
                                            id="p1" name="contenido_editar"></textarea>
                                </div>
                            </div>
                     
                    </div>
                    <div class="col-lg-6 imagen-carga">
                        <label>Cargar Imagen</label>
                                    <div class="dropzone" id="contenido_imagen"></div>
                                    <input type="text" id="entrada" name="entrada" hidden>
                    </div>
                    <div class="col-lg-6">
                                        
                                        <h5 class="m-t-20">Lista de Etiquetas</h5>
                                            <select class="select2 m-b-10 select2-multiple" id="lista"  name="lista_etiquetas[]" style="width: 100%" multiple="multiple" data-placeholder="#ejemplo">
                                                <optgroup label="Lista de etiquetas" id="lista-etiquetas">
                                                </optgroup>
                                            
                                            </select>
                    </div>


                    <div class="mr-auto ml-auto mt-4">
                        <button type="submit" id="" class="btn waves-effect waves-light btn-block btn-info">Guardar</button>
                    </div>
              
                </form>
                <!--  FORM  -->
           </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<script>
// cargar lista

function buscar(){
    $("#tabla-entradas").html("");
    url = "../Blog/buscar_evento"
            var settings = {
       "async": true,
       "crossDomain": true,
       "url": url,
       "method": "POST",
       "headers": {
           "cache-control": "no-cache"
       },
       "data": {"buscador": $("#buscador").val()},
       "beforeSend": function () {
           //showLoader()
       }
   };

   // llena el select
   $.ajax(settings).done(function (response) {
    a = JSON.parse(response);

    if(a == 0){
    $("#tabla-entradas").append('<tr><td>No hay resultados</td></tr>');
}else{
     
     
  var datalista = a;
  console.log(datalista);

            //$("#tabla-entradas").html("No hay contenido de este tipo.")
            datalista.forEach(function (valor, indice, array) {
                
                var estado = valor.estado_visible;

if(estado == 1){
  boton_estado = "checked";
  accion = 0
}


if(estado == 0){
    boton_estado = "";
    accion = 1
}


                    
    if (valor.tipo == "Evento") {
                        $("#tabla-entradas").append('<tr>'+
                        '<td>' + valor.titulo + '</td>'+
                        '<td>' + valor.autor + '</td>'+
                        '<td>' + valor.descripcion + '</td>'+
                        '<td>' + valor.fecha + '</td>'+
                        '<td><button type="button" title="Editar" class="btn btn-secondary" data-toggle="modal" data-target="#modal_editar" data-object="'+valor._id.$id+'" onclick="editar_entrada($(this).data(\'object\'))"><i class="mdi mdi-tooltip-edit"></i></button>'+
                        '<button type="button" title="Ver" class="btn btn-secondary" data-toggle="modal" data-target="#modal_ver"  data-object="'+valor._id.$id+'" onclick="mostrar_entrada($(this).data(\'object\'))"><i class="mdi mdi-eye"></i></button>'+
                        '<div class="switch" title="Contenido activo" style="display:inline-block;"><label><input type="checkbox" data-object="'+valor._id.$id+'" '+boton_estado+' onclick="cambiar_estado_entrada($(this).data(\'object\'), '+accion+')" ><span class="lever switch-col-light-green"></span></label></td>'+'</tr>');
                    }
       })
}
        })
}



    cargar_eventos()
    function cargar_eventos() {
    $("#tabla-entradas").html("");
        url = "../Blog/listar_eventos"
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": url,
            "method": "GET",
            "headers": {
                "cache-control": "no-cache"
            },
            //"data": $('#cargar_contenido').serialize(),
            "beforeSend": function () {
                //showLoader()
            }
        };

        $.ajax(settings).done(function (response) {
            a = JSON.parse(response);

      
            
        var datalista = a;
        console.log(datalista);
        


            datalista.forEach(function (valor, indice, array) {
                var estado = valor.estado_visible;
                 var id_objeto = ('"'+valor._id.$id+'"')

            if(estado == 1){
            boton_estado = "checked";
            accion = 0
            }


            if(estado == 0){
                boton_estado = "";
                accion = 1
            }


                if (valor.tipo == "Evento") {
                    $("#tabla-entradas").append('<tr>'+
                    '<td>' + valor.titulo + '</td>'+
                    '<td>' + valor.autor + '</td>'+
                    '<td>' + valor.descripcion + '</td>'+
                    '<td>' + valor.fecha + '</td>'+
                    '<td><button type="button" title="Editar" class="btn btn-secondary" data-toggle="modal" data-target="#modal_editar" data-object="'+valor._id.$id+'" onclick="editar_entrada($(this).data(\'object\'))"><i class="mdi mdi-tooltip-edit"></i></button>'+
                    '<button type="button" title="Ver" class="btn btn-secondary" data-toggle="modal" data-target="#modal_ver"  data-object="'+valor._id.$id+'" onclick="mostrar_entrada($(this).data(\'object\'))"><i class="mdi mdi-eye"></i></button>'+
                    '<div class="switch" title="Contenido activo" style="display:inline-block;"><label><input type="checkbox" data-object="'+valor._id.$id+'" '+boton_estado+' onclick="cambiar_estado_entrada($(this).data(\'object\'), '+accion+')" ><span class="lever switch-col-light-green"></span></label></td>'+'</tr>');
                }
       })

        })
    }
// mostrar entrda
function mostrar_entrada(id){


            $.ajax({
                type: 'GET',
                url: 'cargar_entrada/'+id,
                success: function (data) {
                    //location.reload(true);
                    a = JSON.parse(data);
                    datalista = a;
                    console.log(datalista);
                    $("#contenido").html("")
                    $("#cantidad_vistas").text(datalista.visitas)
                    $("#fecha").text(datalista.fecha)
                    $("#Titulo").text(datalista.titulo)
                    $("#contenido").append("<p>"+datalista.contenido+"</p>")
                    $("#img").attr("src", "../assets/img/img-blog/"+datalista.imagen)
                    $(".fb-comments").attr("data-href", "https://stfranksanchez.com.ve/blog"+datalista.code_facebook)

                    //acciones a hacer cuando se recibe la info
                }
            });


}


        $(document).ready(function () {

            $('.textarea_editor').wysihtml5({
                "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
                "emphasis": true, //Italics, bold, etc. Default true
                "lists": false, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                "html": false, //Button which allows you to edit the generated HTML. Default false
                "link": false, //Button to insert a link. Default true
                "image": false, //Button to insert an image. Default true,
                "color": false //Button to change color of font  
            });

            
        });


// editar entrada
function editar_entrada(id){

                $.ajax({
                type: 'GET',
                url: 'cargar_entrada/'+id,
                success: function (data) {
                    //location.reload(true);
                    a = JSON.parse(data);
                    datalista = a;
                    console.log(datalista._id.$id);
                    var lista_etiquetas = [];

                    if(a.etiquetas_id == undefined){
                        console.log("no tiene etiquetas")
                    }else{
                        console.log(a.etiquetas_id)
                        a.etiquetas_id.forEach(function (valor, indice, array) {
                         lista_etiquetas.push(valor);  
                    });
                    }
                    
                    $('#lista').val(lista_etiquetas);
                    $('#lista').trigger('change');
                    $(".select2").select2();
       
                    $("#contenido").html("")
                    $("#fecha_editar").val(datalista.fecha)
                    $("#titulo_editar").val(datalista.titulo)
                     $('#p1').data("wysihtml5").editor.setValue(datalista.contenido);             
                    $("#tipo_contenido").val(datalista.tipo);
                    $("#descripcion_editar").val(datalista.descripcion);
                    $("#autor_editar").val(datalista.autor);
                    $("#etiquetas_editar").val(datalista.etiquetas_id);
                    $("#entrada").val(datalista._id.$id);
                    $(".modal-dialog").css("max-width","70%");
                    nombre_imagen = datalista.imagen;

                       
                }
            });


}




        jQuery('.mydatepicker, #datepicker').datepicker();


 
        listar_etiquetas()
function listar_etiquetas(){
    $("#tabla-entradas").html("");
    url = "../Etiquetas/listar_etiquetas"
            var settings = {
       "async": true,
       "crossDomain": true,
       "url": url,
       "method": "POST",
       "headers": {
           "cache-control": "no-cache"
       },
       "beforeSend": function () {
           //showLoader()
       }
   };

   // llena el select
   $.ajax(settings).done(function (response) {
    a = JSON.parse(response);

            
            
var datalista = a;
console.log(datalista);

          //$("#tabla-entradas").html("No hay contenido de este tipo.")
          datalista.forEach(function (valor, indice, array) {
            
        $("#lista-etiquetas").append('<option value="'+valor._id.$id+'">'+valor.etiqueta+'</option>');
            
     })
     $(".select2").select2();
       

   })

}



    Dropzone.autoDiscover = false;



    var dz = new Dropzone(".dropzone", {
        //url: "http://localhost/pruebas/lux/test.php", /*url de prueba*/
        url: "../Blog/actualizar_imagen", /*define url*/
        method: "post", /*define metodo*/
        maxFiles: 1, /*solo un archivo por formulario*/
        dictDefaultMessage: "Arrastre <STRONG>aquí</STRONG> la imagen del contenido.", /*mensaje del formulario*/
        dictMaxFilesExceeded: "Solo se puede subir una imagen", /*mensaje si intenta subir varios*/
        renameFile: function (file) {
    let newName = nombre_imagen;
    return newName;},
        paramName: "filenames", //nombre del elemento en $_FILES
        acceptedFiles: "image/jpg, image/png, image/jpeg", /*formatos aceptados*/
        dictInvalidFileType: "No puedes subir este tipo de archivos", /*mensaje de formato erroneo*/
        autoProcessQueue: false, /*evita que se suba automaticamente*/
        previewsContainer: ".dropzone",
        /* headers: {
             "accept": "application/json",
             "authorization": end_token,
             "cache-control": "no-cache"
             },*/
        init: function () {
            /*sustituye la imagen anterior para cargar una sola*/
            this.on('addedfile', function (file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            });

            //llamada cuando finaliza la subida
            this.on('success', function (file, response) {
                //$("#entrada").val(response)
                console.log(response)
               cambiar_entrada();
            });

            //muestra un error
            this.on('error', function (file, response) {
                //console.log(response);
                //.dz-error-message lo agregue yo manualmente
                $(file.previewElement).find('.dz-error-message').text(response);
            });
        }
    });




    $('#cargar_contenido').on('submit', function (e) {

            console.log("cargar_contenido");
            e.preventDefault();
            //$("#modal_editar").modal('hide');
            if(dz.files[0] == undefined){
                console.log("NO existe")
                swal({   
            title: "Desea mantener la imagen actual?",   
            text: "",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Si!",   
            cancelButtonText: "No,",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) {     
                cambiar_entrada()
            } else {     
                swal("Actualizacion cancelada", "Estimado usuario por favor seleccione una imagen", "error");  

            } 
        });
               return;
            }else{
                console.log("existe");
                 dz.processQueue()
            }
    })


   
   
   function cambiar_entrada() {
       
       $("#modal_editar").modal('hide');
       //return;
        url = "../Blog/actualizar_entrada"
       var settings = {
           "async": true,
           "crossDomain": true,
           "url": url,
           "method": "POST",
           "headers": {
               "cache-control": "no-cache"
           },
           "data": $('#cargar_contenido').serialize(),
           "beforeSend": function () {
               //showLoader()
           }
       };

       // llena el select
       $.ajax(settings).done(function (response) {
          
             
           
              swal({   
            title: "Cambios guardados con exito!",   
            text: "",   
            type: "success",   
            confirmButtonText: "Continuar",   
            closeOnConfirm: false,
        }, function(isConfirm){   
            if (isConfirm) {     
                location.reload()
            }
          
           
            })

   })

}





   function cambiar_estado_entrada(id, accion) {
    
    url = "cambiar_estado_entrada"
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": url,
        "method": "POST",
        "headers": {
        "cache-control": "no-cache"
                    },
        "data": {"id": id,
                "accion": accion},
        "beforeSend": function () {
        }
       };

    $.ajax(settings).done(function (response) {
        swal("Cambios guardados con exito!", "", "success");
       })

   }












</script>