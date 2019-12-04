<div class="container">
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Gestor de Etiquetas</h4>
                                <div class="row">
                                    <div class="col-lg-4">
                                    <form class="row" id="form_nueva_etiqueta">
                                    <div class="form-group col-12 form-material">
                                        <div class="form-group">
                                            <label>Nueva etiqueta   ( No agregar # )</label>
                                            <input type="text" class="form-control form-control-line" name="etiqueta" onkeyup="$('#prevista').text('#'+this.value)" value="" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Pre-visualización</label>
                                            <h3 class="text-center"><i class="mdi mdi-tag-plus" id="prevista">EJEMPLO</i></h3>
                                           
                                        </div>

                                            
                                    <div class="mr-auto ml-auto mt-4">
                                        <button type="submit" id="" class="btn waves-effect waves-light btn-block btn-info">Guardar</button>
                                    </div>
                                    </div>
                               
                              
                                    </form>


                                    </div>
                                    <div class="col-lg-6 ml-5">
                                        <h4 class="card-title text-center">Lista de Etiquetas</h4>
                                        <div class="col-lg-12">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="buscador"  name="buscador" onkeyup="buscar(this.value)" placeholder="Etiqueta">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-info" onclick="listar_etiquetas(); $('#buscador').val('')" type="button">Reiniciar!</button>
                                                        </span>
                                                    </div>
                                                </div>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                
                                                        <th colspan=1>Etiqueta</th>
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
                        </div>
                      
                    </div>
</div>
</div>



<div class="modal fade bs-example-modal-lg" id="modal_editar" tabindex="-1" role="dialog" aria-labelledby="Titulo" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="">Editar Etiquetas</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--  FORM  -->
                <form class="row" id="form_editar_etiqueta">
                    <div class="form-group col-12 form-material">
                        <div class="form-group">
                            <label>Editar etiqueta   ( No agregar # )</label>
                            <input type="text" class="form-control form-control-line" id="etiqueta_editar" name="etiqueta_editar" onkeyup="$('#prevista_editar').text('#'+this.value)" value="" required>
                            <input type="hidden" class="form-control form-control-line" id="id_etiqueta" name="id_etiqueta"  >
                        </div>
                        
                        <div class="form-group">
                            <label>Pre-visualización</label>
                            <h3 class="text-center"><i class="mdi mdi-tag-plus" id="prevista_editar">EJEMPLO</i></h3>
                           
                        </div>

                            
                    <div class="mr-auto ml-auto mt-4 col-6">
                        <button type="submit" id="" class="btn waves-effect waves-light btn-block btn-info">Guardar</button>
                    </div>
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

console.log("llego")
});


    // Date Picker
        jQuery('.mydatepicker, #datepicker').datepicker();
//cantidad_parrafos(0);








$('#form_nueva_etiqueta').on('submit', function (e) {

        console.log("form_nueva_etiqueta");
        e.preventDefault();
        url = "../Etiquetas/crear_etiqueta"
            var settings = {
       "async": true,
       "crossDomain": true,
       "url": url,
       "method": "POST",
       "headers": {
           "cache-control": "no-cache"
       },
       "data": $('#form_nueva_etiqueta').serialize(),
       "beforeSend": function () {
           //showLoader()
       }
   };

   // llena el select
   $.ajax(settings).done(function (response) {
       console.log(response);
         if(response == 1){
            swal({
           title: "Felicidades,",
           text: "Contendo cargado con exito",
           type: "success",
           confirmButtonText: "Aceptar",
           closeOnConfirm: false
       })
       listar_etiquetas();
         } else{
            swal({
           title: "Error,",
           text: "Por favor intente hacerlo nuevamente.",
           type: "warrning",
           confirmButtonText: "Aceptar",
           closeOnConfirm: false
       })
         }
   })

        
})


$('#form_editar_etiqueta').on('submit', function (e) {

console.log("form_nueva_etiqueta");
e.preventDefault();
url = "../Etiquetas/actualizar_etiqueta"
    var settings = {
"async": true,
"crossDomain": true,
"url": url,
"method": "POST",
"headers": {
   "cache-control": "no-cache"
},
"data": $('#form_editar_etiqueta').serialize(),
"beforeSend": function () {
   //showLoader()
}
};

// llena el select
$.ajax(settings).done(function (response) {
console.log(response);
 if(response == 1){
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
 } else{
    swal({
   title: "Error,",
   text: "Por favor intente hacerlo nuevamente.",
   type: "warrning",
   confirmButtonText: "Aceptar",
   closeOnConfirm: false
})
 }
})


})


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
            
        $("#tabla-entradas").append('<tr>'+
                  '<td><i class="mdi mdi-tag" id="prevista">#'+ valor.etiqueta + '</i></td>'+
                  '<td><button type="button" title="Editar" class="btn btn-secondary" data-toggle="modal"  data-object="'+valor._id.$id+'"  data-target="#modal_editar" onclick="editar_etiqueta($(this).data(\'object\'))"><i class="mdi mdi-tooltip-edit"></i></button>'+
                  '<button type="button" title="Eliminar" class="btn btn-secondary ml-3" data-toggle="modal" data-object="'+valor._id.$id+'"  onclick="eliminar_etiqueta($(this).data(\'object\'))"><i class="mdi mdi-delete"></i></button></td>'+'</tr>');
            
     })
       

   })
}


 function buscar(){
    $("#tabla-entradas").html("");
    url = "../Etiquetas/buscar_etiquetas"
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

            
            
var datalista = a;
console.log(datalista);

if(a == 0){
    $("#tabla-entradas").append('<tr><td>No hay resultados</td></tr>');
}else{
    datalista.forEach(function (valor, indice, array) {     
                  
        $("#tabla-entradas").append('<tr>'+
                  '<td><i class="mdi mdi-tag" id="prevista">#'+ valor.etiqueta + '</i></td>'+
                  '<td><button type="button" title="Editar" class="btn btn-secondary" data-toggle="modal"  data-object="'+valor._id.$id+'"  data-target="#modal_editar" onclick="editar_etiqueta($(this).data(\'object\'))"><i class="mdi mdi-tooltip-edit"></i></button>'+
                  '<button type="button" title="Eliminar" class="btn btn-secondary ml-3" data-toggle="modal" data-object="'+valor._id.$id+'"  onclick="eliminar_etiqueta($(this).data(\'object\'))"><i class="mdi mdi-delete"></i></button></td>'+'</tr>');
            
     })
}})
}


function editar_etiqueta(id){
                $.ajax({
                type: 'GET',
                url: '../Etiquetas/buscar_etiqueta/'+id,
                success: function (data) {
                    
                    //location.reload(true);
                    a = JSON.parse(data);
                    datalista = a;
                    console.log(datalista);

                    $("#prevista_editar").text(datalista.etiqueta);
                    $("#etiqueta_editar").val(datalista.etiqueta);
                    $("#id_etiqueta").val(datalista._id.$id);
                }
                })
}


function eliminar_etiqueta(id){
                $.ajax({
                type: 'GET',
                url: '../Etiquetas/eliminar_etiqueta/'+id,
                success: function (data) {
                    if(data == 1){
                        swal({   
                    title: "Cambios guardados con exito!",   
                    text: "",   
                    type: "success",   
                    confirmButtonText: "Continuar",   
                    closeOnConfirm: false,
                            })
                            
                    listar_etiquetas()
                    }else{
                        swal({   
                    title: "Error al eliminar!",   
                    text: "Esta etiqueta esta siendo usada en una entrada, por favor retirela del articulo y proceda de nuevo.",   
                    type: "error",   
                    confirmButtonText: "Continuar",   
                    closeOnConfirm: false,
                            })
                    }
                }

                })

}
 




</script>