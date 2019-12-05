
<!--  SEGUNDA PARTE DE PAGINA ss   -->



<style>

.banner_1_fondo{
  background: linear-gradient(rgba(0, 0, 0, 0.79), rgba(0, 0, 0, 0.79)),url('assets/img/biblioteca_imagenes/banner_2.jpg');
  background-size: cover; height: -webkit-fill-available;
 
}


.banner_2_fondo{
  background: linear-gradient(rgba(245, 7, 7, 0.7), rgba(245, 7, 7, 0.7)),url('assets/img/biblioteca_imagenes/banner_1.jpg');
  background-size: cover; height: -webkit-fill-available;
 
}

.derechos{
  font-size: 0.65rem;
  margin-top: 1.5rem
}


.owl-next, .carousel-control-next {
    right: 24px !important;
}



</style>




<!-- ZONA  BANNER -->
<div class="owl-carousel owl-reponsive banner_1 d-lg-block d-none" style="overflow-y: hidden">
              <?php 


foreach($banners as $banner){
  /*
  $banner['transparencia'];
  $banner['colortexto'];
  $banner['imgfondo'];
  $banner['imglogo'];
*/

$año  = date("Y");
  print_r('<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12  banner_2_fondo p-0 m-0" style="background: linear-gradient('.$banner['transparencia'].', '.$banner['transparencia'].')
                                                                                              ,url(assets/img/img-banner/'.$banner['imgfondo'].');
                                                                                              background-size: cover;height: -webkit-fill-available;">
              <div class="row justify-content-center text-light p-0 m-0 pt-4">
                        <div class="col-lg-4 col-md-6 col-sm-5 col-xs-6">
                            <img class="mr-auto ml-auto d-block" style="width: 40%;" src="assets/img/img-banner/'.$banner['imglogo'].'" alt="urbanhub">
                        </div>
                            <div class="col-lg-6 col-md-6 pt-4 pt-md-1">
                              <p class="p_banner pt-2 text-justify" style="color: '.$banner['colortexto'].' ! important">
                              '.$banner['parrafo'].'
                              </p>
                              <p class="text-left derechos pb-4">
                                © '.$año.'  UrbanHub. Todos los derechos reservados. Diseñado y Desarrollado por <img class="w-25" style="display: -webkit-inline-box"  src="assets/img/nuevo/LOGO-AG2-PIE DE PAGINA.svg" alt="">
                              </p>
							              </div>
						</div>
			    </div>
                    ');

};          

              ?>
									
		
  </div>
  
  
	<!-- ZONA  BANNER -->


	<div class="modal fade" id="modal_ampliar_imagen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <img class="img-fluid" id="imagen_ampliada" src="" alt="">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary mr-auto ml-auto d-block" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>




      <div class="contenedor_redes">
<p>
                <a href="https://www.linkedin.com/in/urbanhub-the-coworking-space-789bb516b/" target="_blank"><i class="fab fa-linkedin pl-2 fa-2x"></i></a>
                <a href="https://www.facebook.com/urbanhubmx/" target="_blank"><i class="fab fa-facebook  pl-2 fa-2x"></i></a>
                <!--<i class="fab fa-youtube text-light pl-2 fa-2x"></i>-->
                <a href="https://www.instagram.com/urbanhub_mx/" target="_blank"><i class="fab fa-instagram pl-2 fa-2x"></i></a>
                <a  id="btm_logg" onclick='$("#container_log").fadeIn(500)'  ><i class="far fa-user pl-2 fa-2x"></i></a>
        </p>
</div>






<div id="container_log" class="" style="" onclick=''>
<div class="contenedor_login">
  <div class="btn_salir" onclick='$("#container_log").fadeOut(200)'>
    <i class="far fa-times-circle text-light"></i>
  </div>

  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-lg-4">
       
        <div class="botones" style="">
          <img src="assets/img/nuevo/logo-blanco.svg" alt="" class="logo-blanco_login">
            <button id="oplog1" class="btn btn-warning mr-auto d-block  text-uppercase menu_opciones" id="" onclick="mostrarformulario()" style="width: 90%;    font-size: 0.75rem;"><strong>Registrar</strong></button>
            <button id="oplog2" class="btn btn-warning  mr-auto d-block  text-uppercase menu_opciones" id="" onclick="mostrarformulario()" style="width: 90%;     font-size: 0.75rem;"><strong>Olvide mi Contraseña</strong></button>
            <button id="url_reporte_1" class="btn btn-warning  mr-auto text-uppercase menu_opciones" id="" onclick="location.replace('<?=base_url()?>r_saldos')" style="width: 90%;     font-size: 0.75rem;"><strong>Reporte de Saldos</strong></button>
      
          </div>

        <div class="text text-light text-uppercase">
          <p>
            <img src="assets/img/login/TEXT.png" alt="">
          </p>
        </div>


        <div class="foto_form" style="background: #fff">
            <img class="img-fluid rounded-circle" src="" alt="">
        </div>
        <div class="form">
          <form  id="login_form">
              <h5 id="mensaje_s" class="text-center text-light pt-2"></h5>
            <input type="email" class="form-control" id="usuario" name="usuario"  placeholder="Cuenta de Usuario"  onkeyup="$('#pass').val('')" required autocomplete="off">
            <input type="password" class="form-control" id="pass" name="pass"  placeholder="Contraseña"  autocomplete="off">
            <button id="btn_log" class="btn btn-warning btn-op-planes d-block mr-auto ml-auto w-50 text-uppercase menu_opciones mt-3" tipo="submit" onclick="" style="font-size: 0.75rem"><strong>Continuar</strong></button>
            <button id="salir_mem" class="btn btn-warning btn-op-planes mr-auto ml-auto w-50 text-uppercase menu_opciones mt-3"  tipo="button" onclick="localStorage.clear();location.reload()" style="font-size: 0.75rem"><strong>Salir</strong></button>
          
          </form>
        </div>


        <div class="redes">
                <a href="https://www.facebook.com/urbanhubmx/" target="_blank"><i class="fab fa-facebook-f text-light fa-1x"></i></a>
                <a href="https://www.linkedin.com/in/urbanhub-the-coworking-space-789bb516b/" target="_blank"><i class="fab fa-linkedin text-light pl-2 fa-1x"></i></a>
                <a href="https://www.instagram.com/urbanhub_mx/" target="_blank"><i class="fab fa-instagram text-light pl-2 fa-1x"></i></a>
        </div>

            <img style="height: 25rem;width: 14rem;position: absolute;left: -9.5rem;z-index: 1;" src="assets/img/login/b.png" alt="">

            <img class="" src="assets/img/login/h.png" alt="" style="height: 25rem;width: 31rem;">

      </div>
    </div>
  </div>

</div>

</div>




<div class="modal fade" id="modal-gracias" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm" style="background: #00000000; color: #fff; border: 1px solid rgba(0, 0, 0, 0)">
            <div class="modal-header" style="width: 80px;">
                <h5 class="modal-title" id="exampleModalLongTitle">¡Gracias!</h5>
                <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>-->
            </div>
            <div class="modal-body">
                Estamos muy agradecidos por tu interés y confianza, muy pronto personal de nuestro staff se pondrá en contacto.
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="formulario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm" style="background: #00000000; color: #fff; border: 1px solid rgba(0, 0, 0, 0)">
            <div class="modal-header" style="width: 80px;">
                <h5 class="modal-title" id="exampleModalLongTitle">¡hola!</h5>
              <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>-->
            </div>
            <div class="modal-body">
                Facilitanos tu información para poder contactarte, te prometemos hacer buen uso de ella.
                    <form id="formulario2" class="pt-1">
                        <div class="form-group">
                            <input type="text" class="form-control" id="nombre-2" name="nombre" placeholder="Nombre Completo" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="email-2" name="email" placeholder="Correo Electronico" required>
                        </div>
                        <input type="text" class="form-control" id="telefono-2" name="telefono" placeholder="Telefono" required>
                        <div class="form-group">
                            <textarea type="text" rows="4" class="form-control mt-3" id="mensaje-2" name="mensaje" placeholder="Mensaje" required></textarea>
                        </div>
                    
                    
                        <button type="submit" class="btn mr-auto btn-urban-2">
                            ENVIAR
                        </button>
                    </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="apps" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content " style="background: #00000000; color: #fff; border: 1px solid rgba(0, 0, 0, 0)">
          <div class="modal-header" style="width: 80px;">
              <h5 class="modal-title" id="exampleModalLongTitle">¡Hola!</h5>
            <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>-->
          </div>
          <div class="modal-body">
              ¡También puedes encontrarnos en!
        
                     <div class="row">
                       <div class="col-lg-6 d-none">

                        <button id="app" class="btn btn-warning btn-op-planes mr-auto ml-auto w-75 text-uppercase menu_opciones mt-3"  tipo="button" onclick="location.replace('#')" style="font-size: 0.75rem"><strong><i class="fab fa-app-store"></i> App Store</strong></button>
          

                       </div>

                       <div class="col-lg-6">

                        <button id="play" class="btn btn-warning btn-op-planes mr-auto ml-auto w-75 text-uppercase menu_opciones mt-3"  tipo="button" onclick="location.replace('#')" style="font-size: 0.75rem"><strong><i class="fab fa-google-play"></i><a style="text-decoration: none" href="https://play.google.com/store/apps/details?id=com.ag2.urbanhub" target="_blank" rel="noopener noreferrer">Play Store</a></strong></button>
         

                       </div>
                     </div>
                  
                  
                
          </div>
      </div>
  </div>
</div>

	<script>



$('#formulario2').submit(function (e) { 
            e.preventDefault()//evitas hacer el submit
            console.log("paso")
            $("#formulario").modal('hide')
            $.ajax({
                type: 'POST',
                url: 'panel/envio_correo_2',
                data: {
                    "nombre": $("#nombre-2").val(),
                    "ciudad": $("#ciudad-2").val(),
                    "email": $("#email-2").val(),
                    "telefono": $("#telefono-2").val(),
                    "mensaje": $("#mensaje-2").val()
                },
                success: function (data) {
                    //location.reload(true);
                    console.log(data);
                    if (data == 1) {
                        //alert("Mensaje enviado con exito.")
                          $("#modal-gracias").modal('show')
                        $("input").val('')
                        $("textarea").val('')
                        captar_data(5)
                    } else {
                        alert("error al enviar el mensaje, por favor intente nuevamente.")
                    }
                    //acciones a hacer cuando se recibe la info
                }
            });
        });



function mostrarformulario() {
    $("#formulario").modal('show')
}

veri_log()
function veri_log(){
  if(localStorage.getItem("membrecia") == 1){
            


    $("#url_reporte_1").show();  
$("#salir_mem").show();
$("#btn_log").hide();
$("#btm_logg").css("text-shadow","0px 3px 7px #ffeb00");

  }else{
$("#container_log").hide();
$("#url_reporte_1").hide();
$("#salir_mem").hide();
$("#mensaje_s").hide();
$(".foto_form").hide();
$("#pass").hide();
  }
}




$("#container_log").hide();
$("#url_reporte_1").hide();
$("#salir_mem").hide();
if(re == 1){
    $(".banner_1").hide()
}
$("#mensaje_s").hide();
$(".foto_form").hide();
$("#pass").hide();
$('#login_form').on('submit', function (e) {
        e.preventDefault();
  
        console.log($("#login_form").serialize());

        url = "login_web"
        var settings = {
        "async": true,
        "crossDomain": true,
        "url": url,
        "method": "POST",
        "data":$("#login_form").serialize(),
        "headers": {
          "cache-control": "no-cache"
        },
        "beforeSend":   function(xhr){
          $("#btn_log").attr("disabled",true);
        }}


        $.ajax(settings).done(function (response) {
          $("#btn_log").attr("disabled",false);

          data_login = JSON.parse(response);

          if(data_login.estado_login == 3){
            $("#mensaje_s").text("Serial Incorrecto").show(500);
       
          }


          if(data_login.estado_login == 1){
            $("#mensaje_s").text("Correo Invalido").hide();
            $(".foto_form").fadeIn(400)
            $("#mensaje_s").text(data_login.nombre);
            $("#mensaje_s").fadeIn(500);
            console.log(data_login.ruta+"/assets/cpanel/ClientePagador/images/"+data_login.imagen);
            $(".foto_form img").attr("src", data_login.ruta+"/assets/cpanel/ClientePagador/images/"+data_login.imagen)
            $("#usuario").hide()
            $("#pass").show()
            

          }
          
          if(data_login.estado_login == 0){

              $(".foto_form").hide();
              $("#mensaje_s").text("Correo Invalido").show(500);
             // $("#mensaje_s").fadeOut(5000);
              
          }

          if(data_login.estado_login == 4){
            localStorage.setItem("membrecia", 1);
            
            $("#url_reporte_1").show().addClass('d-block');  
            $("#salir_mem").show().addClass('d-block');
            $("#btn_log").hide();
            $("#oplog1").hide().removeClass('d-block');
            $("#oplog2").hide().removeClass('d-block');
            $("#btn_log").removeClass("d-block");
            $("#pass").hide();

            $("#btm_logg").css("text-shadow","0px 3px 7px #ffeb00");
          }
            
            



          
        })



})




function report_saldos(){

    


            data =  JSON.parse(sessionStorage.getItem('reporte_saldos'));






console.log(data);
  url = "r_saldos"
        var settings = {
        "async": true,
        "crossDomain": true,
        "url": url,
        "method": "POST",
        "data": {"data": data},
        "headers": {
          "cache-control": "no-cache"
        }}


        $.ajax(settings).done(function (response) {
        })
}

function formta_fecha(fecha){

  año = fecha.substr(0, 4);
  mes = fecha.substr(8, 9);
  dia = fecha.substr(5, 2);

return dia+"-"+mes+"-"+año; 
}

console.clear();

function ampliar(ruta){
        $("#imagen_ampliada").attr('src', ruta)
        $("#modal_ampliar_imagen").modal('show');
    }



	var owl = $('.banner_1');
owl.owlCarousel({
    items:4,
    loop:true,
    margin:10,
    autoplay:true,
    autoplayTimeout:10000,
    autoplayHoverPause:true,
    responsiveClass:true,
    autoHeight:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:1,
            nav:false
        },
        1000:{
            items:1,
            nav:true,
            loop:true
        }
    }
});
$(".owl-prev").html('<span class="carousel-control-prev-icon" aria-hidden="true"></span>');
$(".owl-next").html('<span class="carousel-control-next-icon" aria-hidden="true"></span>');

	
$(document).ready(function(){
/*
$('.owl-prev').on('click', function (e) {
				e.preventDefault();
				$('audio')[0].play()            
					});

$('.owl-next').on('click', function (e) {
e.preventDefault();
$('audio')[0].play()            
	});

$('button').on('click', function (e) {

$('audio')[0].play()            
	});
*/
})



	</script>

	
<audio>
        <source src="assets/sond/boton.wav" volumen="-5000" > 
	</audio>
	

</body>
</html>


