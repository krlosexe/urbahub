<div class="container">
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Nuevo contenido</h4>
                                <form class="row" id="cargar_contenido">
                                    <div class="form-group col-md-4 form-material">
                                        <div class="form-group">
                                            <label>Titulo</label>
                                            <input type="text" class="form-control form-control-line" name="titulo" value="" required> </div>
                                    </div>
                               

                                        <div class="form-group col-md-4 form-material">
                                            <div class="form-group">
                                                <label>Tipo de contenido</label>
                                                <select class="form-control" name="tipo_contenido">
                                                    <option>Evento</option>
                                                    <option>Noticia</option>
                                                </select>
                                            </div>
                                        </div>

                                            <div class="form-group col-md-4 form-material">
                                                <div class="form-group">
                                                    <label>Fecha del contenido</label>
                                                    <input type="text" name="fecha" class="form-control form-control-line mydatepicker" placeholder="Mes/Dia/Año"
                                                        autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6 form-material">
                                            <div class="form-group">
                                                <label>Autor</label>
                                            <textarea class="form-control" maxlength="100" placeholder="max. 100 caracteres" required rows="1" name="autor"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 form-material">
                                            <div class="form-group">
                                                <label>Descripción</label>
                                            <textarea class="form-control" maxlength="100" placeholder="max. 100 caracteres" required rows="1" name="descripcion"></textarea>
                                            </div>
                                        </div>

                                    <div class="col-lg-6">
                                        
                                            <div class="form-group col-md-12 parrafos" id="parrafo_1">
                                                <div class="form-group">
                                                    <label>Contenido</label>
                                                    <textarea class="textarea_editor form-control" placeholder="carapteres ilimitados" spellcheck="true" required rows="6" id="contenido" name="contenido"></textarea>  
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
                            </div>
                        </div>

                      
                    </div>
</div>
</div>



<script>

$(document).ready(function () {

    
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





    $('.textarea_editor').wysihtml5({
        "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
        "emphasis": true, //Italics, bold, etc. Default true
        "lists": false, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
        "html": false, //Button which allows you to edit the generated HTML. Default false
        "link": false, //Button to insert a link. Default true
        "image": false, //Button to insert an image. Default true,
        "color": false //Button to change color of font  
    });

console.log("llego")
});


    // Date Picker
        jQuery('.mydatepicker, #datepicker').datepicker();
//cantidad_parrafos(0);



Dropzone.autoDiscover = false;



var dz = new Dropzone(".dropzone", {
    //url: "http://localhost/pruebas/lux/test.php", /*url de prueba*/
    url: "../Blog/guardar_imagen", /*define url*/
    method: "post", /*define metodo*/
    maxFiles: 1, /*solo un archivo por formulario*/
    dictDefaultMessage: "Arrastre <STRONG>aquí</STRONG> la imagen del contenido.", /*mensaje del formulario*/
    dictMaxFilesExceeded: "Solo se puede subir una imagen", /*mensaje si intenta subir varios*/
    paramName: "filenames", //nombre del elemento en $_FILES
    acceptedFiles: "image/jpg, image/png, image/jpeg", /*formatos aceptados*/
    dictInvalidFileType: "No puedes subir este tipo de archivos", /*mensaje de formato erroneo*/
    autoProcessQueue: false, /*evita que se suba automaticamente*/
    previewsContainer: ".dropzone",
    init: function () {
        /*sustituye la imagen anterior para cargar una sola*/
        this.on('addedfile', function (file) {
            if (this.files.length > 1) {
                this.removeFile(this.files[0]);
            }
        });

        //llamada cuando finaliza la subida
        this.on('success', function (file, response) {
            $("#entrada").val(response)
           crear_entrada();
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

        if(dz.files[0] == undefined){
            console.log("NO existe")
              swal({
                title: "No se ha encontrado la imagen",
                text: "Todo contenido debe tener una imagen",
                type: "warning",
                confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
                closeOnConfirm: false
            })
           return;
        }else{
            console.log("existe");
             dz.processQueue()
        }
        
})




function crear_entrada() {
    url = "../Blog/crear_entrada"
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
       console.log(response);
         swal({
           title: "Felicidades,",
           text: "Contendo cargado con exito",
           type: "success",
           confirmButtonText: "Aceptar",
           closeOnConfirm: false
        }, function(isConfirm){   
            if (isConfirm) {     
                location.reload();
            } else {     
            } 
        
       
   })

})
}

</script>