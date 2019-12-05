

<style>

@media (max-width: 1370px){
	.logo-blanco {
    position: absolute;
    top: 1rem;
    left: 5rem;
    width: 8rem !important;
    z-index: 100;
}
}

@media (max-width: 500px){
  .flex-container {
  height: 100%;

}

}
</style>
	<!-- logo -->
	<img src="assets/img/nuevo/logo-blanco.svg" alt="" class="logo-blanco">
	
	<!-- logo -->


<!--   ZONA INICIAL O MENU   -->
<div class="flex-container">
		<div class="spinner"><p>
			<div class="cube1"></div>
			<div class="cube2"></div>
			Loading...
			</p>
		</div>
		<div class="flex-slide text-uppercase fondo_1" id="op_1" onmouseover="focus_slider(this.id)" onmouseout="reinicio_slider()" onclick="location.replace('Servicios')">
			<div class="flex-title flex-title-home OPCION_TITULO_op_1">Servicios</div>

		</div>
		<div class="flex-slide text-uppercase fondo_2" id="op_2" onmouseover="focus_slider(this.id)" onmouseout="reinicio_slider()" onclick="location.replace('Planes')">
			<div class="flex-title OPCION_TITULO_op_2 letra_azul" >Planes</div>
		</div>
		<div class="flex-slide text-uppercase fondo_3 OPCION_TITULO_op_3" id="op_3" onmouseover="focus_slider(this.id)" onmouseout="reinicio_slider()" onclick="location.replace('Galeria')">
			<div class="flex-title OPCION_TITULO_op_3">Galeria</div>
		
		</div>
		<div class="flex-slide text-uppercase fondo_4 OPCION_TITULO_op_4" id="op_4" onmouseover="focus_slider(this.id)" onmouseout="reinicio_slider()" onclick="location.replace('Blog')">
			<div class="flex-title letra_azul OPCION_TITULO_op_4">Blog</div>
		</div>
	
		<div class="flex-slide text-uppercase fondo_5" id="op_5" onmouseover="focus_slider(this.id)" onmouseout="reinicio_slider()" onclick="location.replace('Beneficios')">
			<div class="flex-title flex-title-home OPCION_TITULO_op_5">Beneficios</div>
		</div>
	
		<div class="flex-slide text-uppercase fondo_6 " id="op_6" onmouseover="focus_slider(this.id)" onmouseout="reinicio_slider()" onclick="location.replace('Contacto')">
			<div class="flex-title flex-title-home OPCION_TITULO_op_6 letra_azul">Contacto</div>
		</div>
	
		
	</div>


	<!-- modal de apps  -->

	
<!-- modal de apps  -->
<script>
	 

$(document).ready(function () {
  $('#apps').modal('show');

});


		(function(){
			$('.flex-container').waitForImages(function() {
				$('.spinner').fadeOut();
			}, $.noop, true);
			
			$(".flex-slide").each(function(){
				$(this).hover(function(){
					$(this).find('.flex-title').css({
					/*	transform: 'rotate(0deg)',*/
						top: '50%'/*,
						left: '0%'*/
					});
					$(this).find('.flex-about').css({
						opacity: '1'
					});
				}, function(){
					$(this).find('.flex-title').css({
						transform: 'rotate(-90deg)',
						top: '69.2%',
						left: '40%'
					});
					$(this).find('.flex-about').css({
						opacity: '0'
					});
				})
			});
		})();


		
	function focus_slider(id){
		console.log(id)
		
if(window.visualViewport.width < 1370){
	console.log("pantalla menor a 1370 px")
		$(".flex-slide").css('width', '10%');
		$("#"+id).css('width', '75%');
		$(".flex-title").css('font-size', '3.3vw');
		$(".OPCION_TITULO_"+id).css('font-size', '2vw');
}

		
if(window.visualViewport.width > 1370){
		console.log("pantalla mayor a 1370 px")
		$(".flex-slide").css('width', '10%');
		$("#"+id).css('width', '75%');
		$(".flex-title").css('font-size', '3.5vw');
		$(".OPCION_TITULO_"+id).css('font-size', '3.2vw');
		
}
	
	}
	
	function reinicio_slider(){
		
		if(window.visualViewport.width < 1370){
		$(".flex-slide").css('width', '16.6%');
		$(".flex-title").css('font-size', '3.5vw');

		}
	

		
		if(window.visualViewport.width > 1370){
		
		$(".flex-slide").css('width', '16.7%');
		$(".flex-title").css('font-size', '3.3vw');
		}

	}

	re = 0
		</script>

<!--   ZONA INICIAL O MENU  -->