<!DOCTYPE html>
<html lang="en">

<head>

  <script>
  
if(location.href == "https://urbanhub.mx/panel"){
    location.replace('https://urbanhub.mx/panel/')
}

 
if(location.href == "http://urbanhub.mx/panel"){
    location.replace('http://urbanhub.mx/panel/')
}


if(location.href == "http://localhost/public/panel"){
    location.replace('"http://localhost/public/panel/')
}

if(location.href == "http://www.siteag.ag2-group.com/urban/panel"){
    location.replace('http://www.siteag.ag2-group.com/urban/panel/')
}


if(location.href == "https://www.siteag.ag2-group.com/urban/panel"){
    location.replace('https://www.siteag.ag2-group.com/urban/panel/')
}

  </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Panel UrbanHub</title>
    <!-- Bootstrap Core CSS -->
    <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../assets/panel/css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="../assets/panel/css/colors/blue.css" id="theme" rel="stylesheet">

        <!--alerts CSS -->
        <link href="../assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper" class="login-register login-sidebar"  style="background-image:url(../assets/images/background/fondo-login.jpg);">
  <div class="login-box card">
    <div class="card-body pt-5">
      <form class="form-horizontal form-material" id="loginform" action="#">
        <a href="javascript:void(0)" class="text-center db"><img src="../assets/images/logo.png" alt="Home" /></a>  
        
        <div class="form-group m-t-40">
          <div class="col-xs-12">
            <input class="form-control" name="user" type="text" id="user" required="" placeholder="Usuario">
          </div>
        </div>
        <div class="form-group">
          <div class="col-xs-12">
            <input class="form-control" name="pass" type="password" id="pass" required="" placeholder="Contraseña">
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
           <!-- <div class="checkbox checkbox-primary pull-left p-t-0">
              <input id="checkbox-signup" type="checkbox">
              <label for="checkbox-signup"> Recordarme </label>
            </div>-->
           <!-- <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a>--> </div>
        </div>
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Ingresar</button>
          </div>

          <footer class="footer">
              © 2018, Panel administrativo  <a href="https://urbanhub.mx/" target="_blank">UrbanHub.</a> <br>Todos los derechos reservados de <br> <a href="https://ag2.com.mx/" target="_blank">AG2 IT CONSULTING</a></footer>
        
        </div>
      
        <div class="form-group m-b-0">
          <div class="col-sm-12 text-center">
            <p>¿No recuerdad la contraseña? <a onclick="recuperar_pass()" href="#" class="text-primary m-l-5"><b>Enviar</b></a></p>
          </div>
        </div>
      </form>
      <form class="form-horizontal" id="recoverform" action="index.html">
        <div class="form-group ">
          <div class="col-xs-12">
            <h3>Recover Password</h3>
            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
          </div>
        </div>
        <div class="form-group ">
          <div class="col-xs-12">
            <input class="form-control" type="text" required="" placeholder="Email">
          </div>
        </div>
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../assets/panel/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="../assets/panel/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../assets/panel/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="../assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!--Custom JavaScript -->
    <script src="../assets/panel/js/custom.min.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="../assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>

            <!-- Sweet-Alert  -->
            <script src="../assets/plugins/sweetalert/sweetalert.min.js"></script>
            <script src="../assets/plugins/sweetalert/jquery.sweet-alert.custom.js"></script>


<script>

    $('#loginform').on('submit', function (e) {

e.preventDefault()

    
    url = "login_ws"
var settings = {
"async": true,
"crossDomain": true,
"url": url,
"method": "POST",
"headers": {
  "cache-control": "no-cache"
},
"data": $('#loginform').serialize()
};

$.ajax(settings).done(function (response) {
if(response == 1){
  sessionStorage.setItem("acceso", "0");
      location.replace("inicio")
}else{
  swal({
          title: "Datos incorrectos",
          text: "",
          type: "warning",
          confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
          closeOnConfirm: false
        })
}
});


    })


    function recuperar_pass(){
      
url = "reinicio_contra_bd"
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


if(response == 1){
  swal({
          title: "Estimado Usurio",
          text: "Ha sido enviado a su correo la informacion solicitada.",
          type: "success",
          confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
          closeOnConfirm: false
})
}else{
  swal({
          title: "No se ha podido enviar la informacion",
          text: "",
          type: "warning",
          confirmButtonColor: "#DD6B55", confirmButtonText: "Aceptar",
          closeOnConfirm: false
        })
}
    })

    }




</script>

</body>

</html>