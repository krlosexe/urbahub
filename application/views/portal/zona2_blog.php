
<!--  SEGUNDA PARTE DE PAGINA   -->



<style>

.banner_1_fondo{
  background: linear-gradient(rgba(0, 0, 0, 0.79), rgba(0, 0, 0, 0.79)),url('../../assets/img/nuevo/banner_1.jpg');
  background-size: cover; height: -webkit-fill-available;
}


.banner_2_fondo{
  background: linear-gradient(rgba(245, 7, 7, 0.7), rgba(245, 7, 7, 0.7)),url('../../assets/img/nuevo/banner_2.jpg');
  background-size: cover; height: -webkit-fill-available;
}

.derechos{
  font-size: 0.65rem;
  margin-top: 1.5rem
}

</style>



<!-- ZONA  BANNER -->
<div class="owl-carousel owl-reponsive banner_1">
			

      <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12  banner_2_fondo p-0 m-0" style="">		
					<div class="row justify-content-center text-light p-0 m-0 pt-4">
							<div class="col-lg-4 col-md-6 col-sm-5 col-xs-6">
									<img class="mr-auto ml-auto d-block" style="width: 40%;" src="../../assets/img/nuevo/logo_2_banner.svg" alt="">
							</div>
							<div class="col-lg-6 col-md-6 pt-4 pt-md-1">
								<p class="p_banner pt-3 text-justify">
										La forma de trabajar ha cambiado… Todo el mobiliario de UrbanHub es de Herman Miller, estas empresas se unen para diseñar espacios de trabajo de alta productividad que brinda a los usuarios una experiencia de trabajo sublime y ayuda a los individuos y organizaciones a alcanzar el éxito. Herman Miller le devuelve a los lugares de trabajo ese toque humano para que podamos ayudar a su gente y a su empresa a lograr sus objetivos

                </p>
                <p class="text-left derechos">
                                © 2018 UrbanHub. Todos los derechos reservados. Diseñado y Desarrollado por <img class="w-25" style="display: -webkit-inline-box"  src="../../assets/img/nuevo/LOGO-AG2-PIE DE PAGINA.svg" alt="">
                        </p>
							</div>
						</div>
			</div>

      <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 banner_1_fondo p-0 m-0" style="">		
					<div class="row justify-content-center text-light p-0 m-0 pt-4">
							<div class="col-lg-4 col-md-6 col-sm-5 col-xs-6 ">
									<img class="w-75 mr-auto ml-auto d-block " src="../../assets/img/nuevo/logo_1_banner.svg" alt="">
							</div>
							<div class="col-lg-6 col-md-6 pt-4 pt-md-1">
								<p class="p_banner pt-3 text-justify">
										La forma de trabajar ha cambiado… Todo el mobiliario de UrbanHub es de Herman Miller, estas empresas se unen para diseñar espacios de trabajo de alta productividad que brinda a los usuarios una experiencia de trabajo sublime y ayuda a los individuos y organizaciones a alcanzar el éxito. Herman Miller le devuelve a los lugares de trabajo ese toque humano para que podamos ayudar a su gente y a su empresa a lograr sus objetivos

                </p>
               
                <p class="text-left derechos">
                  © 2018 UrbanHub. Todos los derechos reservados. Diseñado y Desarrollado por <img class="w-25" style="display: -webkit-inline-box"  src="../../assets/img/nuevo/LOGO-AG2-PIE DE PAGINA.svg" alt="">
          </p>
							</div>
						</div>
			</div>
      
      

      

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
        </p>
</div>



	<script>




if(re == 1){
    $(".banner_1").hide()
}


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
    autoplayTimeout:6000,
    autoplayHoverPause:true,
    responsiveClass:true,
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
            loop:false
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
        <source src="../../assets/sond/boton.wav" volumen="-5000" > 
	</audio>
	

</body>
</html>


