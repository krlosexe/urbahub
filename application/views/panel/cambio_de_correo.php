<div class="container">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cambio de Correo</h4>
                <form class="row" id="cambio_email">
                    <div class="form-group col-md-4 form-material">
                        <div class="form-group">
                            <label>Contraseña Actual</label>
                            <input type="password" class="form-control form-control-line" name="password_old" value="" required> </div>
                    </div>
                    <div class="form-group col-md-4 form-material">
                        <div class="form-group">
                            <label>Nuevo Correo</label>
                            <input type="email" class="form-control form-control-line" name="email" id="email" value="" required> </div>
                    </div>
                    <div class="col-12"></div>
                    <div class="mr-auto ml-auto mt-4">
                        <button type="submit" id="" class="btn waves-effect waves-light btn-block btn-info">Cambiar</button>
                    </div>
              
                </form>
            </div>
        </div>
      
    </div>
</div>
</div>



<script>

$(document).ready(function () {


console.log("llego")
});





$('#cambio_email').on('submit', function (e) {
    e.preventDefault();

    
console.log("paso")
url = "cambio_email"
var settings = {
"async": true,
"crossDomain": true,
"url": url,
"method": "POST",
"headers": {
"cache-control": "no-cache"
},
"data": $('#cambio_email').serialize(),
"beforeSend": function () {
//showLoader()
}
};

// llena el select
$.ajax(settings).done(function (response) {
console.log(response);

if(response == 1){
    swal({
    title: "El correo ha sido cambiado con exito",
    text: "",
    type: "success",
    confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
    closeOnConfirm: false
})  
}

if(response == 0){
    swal({
    title: "Error al actualizar correo",
    text: "La contraseña es incorrecta",
    type: "warning",
    confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
    closeOnConfirm: false
})
}





});



})

</script>