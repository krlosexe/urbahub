<div class="container">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cambio de Contraseña</h4>
                <form class="row" id="cambio_pass">
                    <div class="form-group col-md-4 form-material">
                        <div class="form-group">
                            <label>Contraseña Actual</label>
                            <input type="password" class="form-control form-control-line" name="password_old" value="" required> </div>
                    </div>
                    <div class="form-group col-md-4 form-material">
                        <div class="form-group">
                            <label>Nueva contraseña</label>
                            <input type="password" class="form-control form-control-line" name="password_1" id="PASS_1" value="" required> </div>
                    </div>
                    <div class="form-group col-md-4 form-material">
                        <div class="form-group">
                            <label>Repetir contraseña</label>
                            <input type="password" class="form-control form-control-line" name="password_2" id="PASS_2"  value="" required> </div>
                    </div>
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





$('#cambio_pass').on('submit', function (e) {
    e.preventDefault();
pass1 = $("#pass_1").val()
pass2 = $("#pass_2").val()
    
console.log("paso")
url = "cambio_contra_bd"
var settings = {
"async": true,
"crossDomain": true,
"url": url,
"method": "POST",
"headers": {
"cache-control": "no-cache"
},
"data": $('#cambio_pass').serialize(),
"beforeSend": function () {
//showLoader()
}
};

// llena el select
$.ajax(settings).done(function (response) {
console.log(response);

if(response == 1){
    swal({
    title: "La contraseña ha sido cambiada",
    text: "",
    type: "success",
    confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
    closeOnConfirm: false
})  
}


if(response == 2){
    swal({
    title: "Error al actualizar contraseña",
    text: "Por favor intentelo mas tarde.",
    type: "warning",
    confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
    closeOnConfirm: false
})
}


if(response == 3){
    swal({
    title: "Error al actualizar contraseña",
    text: "La contraseña es incorrecta",
    type: "warning",
    confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
    closeOnConfirm: false
})
}



if(response == 4){  
    swal({
    title: "Las contraseñas no son iguales",
    text: "",
    type: "warning",
    confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
    closeOnConfirm: false
})
}


});



})

</script>