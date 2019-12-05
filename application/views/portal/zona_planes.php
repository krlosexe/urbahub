<style>



.flex-container {
    position: absolute;
    height: 100%;
    width: 100%;
    display: -webkit-flex;
    display: flex;
    overflow: hidden;
    font-family: "montserrat" !important;
    @media screen and (max-width: 768px) {;
    flex-direction: column;
    }: ;
}

.btn-op-planes {
    font-weight: bold;
  }

@media (max-width: 1500px){
  .btn-op-planes {
    width: 90%;
    -webkit-transition: 0.5s;
    transition: 0.5s;
    font-size: 0.75rem;
    font-weight: bold;
  }

  .contenedor_opciones_planes {
    height: 60%;
    position: absolute;
    top: 20%;
    width: 90%;
    left: 0%;
}

}



@media (max-width: 500px){
.contenido-servicios {
    position: absolute;
    top: 3rem;
    left: -2rem; 
    z-index: 1;
    transform: scale(0.7);
    background: #f8ed38;
}
.logo-blanco {
    position: absolute;
    top: 1rem;
    left: 4.5rem !important;
    width: 6rem;
    z-index: 100;
}


.titulo-seccion {
    position: absolute;
    color: #00263b;
    bottom: 80%;
    right: -32%;
    transform: rotate(-90deg);
    letter-spacing: 0.35rem;
    font-size: 3rem;
}


.banner_1 {
	background: linear-gradient(rgba(0, 0, 0, 0.79), rgba(0, 0, 0, 0.79)),url('assets/img/nuevo/banner_1.jpg');
	position: absolute;
	height: 60%;
	bottom: -110%;
	background-size: cover;
	background-size: 100% 297%;
	background-position: 100% 56%;
	@media screen and (
	min-width: 768px) {
		@include animation-properties((animation: aboutFlexSlide, animation-duration: $defaultSeconds, animation-iteration-count: 1, animation-delay: 0s));
	}
}


.flex-container {
    position: absolute;
    height: 80rem;
    width: 100%;
    display: -webkit-flex;
    display: flex;
    overflow: hidden;
    font-family: "montserrat" !important;
    @media screen and (max-width: 768px) {;
    flex-direction: column;
    }: ;
}

.contenedor_opciones_planes {
    height: 60%;
    position: absolute;
    top: 7%;
    width: 90%;
    left: 0%;
}


}

.contenedor_redes a i {
  color: #00263b;
}

#mas_info {
    position: absolute;
    z-index: 5;
    height: 100%;
    background: #2f2f2feb;
    display: none;
}



#opciones_panel {
}

::-webkit-scrollbar {
    width: 12px;
}
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
    border-radius: 10px;
}
::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
}

</style>




	<!-- logo -->
  <a href="./"><img src="assets/img/nuevo/logo-oscuro.svg" alt="" class="logo-blanco"></a>
	
	<!-- logo -->




<div class="flex-container fondo_2">





        
<div class="contenedor_opciones_planes">

  <div class="row justify-content-between" id="contenido_planes">
    <div class="col-lg-4 col-md-4">
    <!-- OPCIONES DE PLANES -->  
    <div class="row" id="opciones_panel">
     <!-- <div class="col-lg-10 pt-4">
      <button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 pb-2 text-uppercase" id="OP-1" onclick="mostrar_plan(this.id);captar_data(9)" ><strong>NOMAD NOCTURNO</strong><br>Co-Work Área</button>
      </div>


      <div class="col-lg-10 pt-2">
      <button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 pb-2 text-uppercase" id="OP-2a" onclick="mostrar_plan(this.id);captar_data(15)"><strong>NOMAD ILIMITADO</strong><br>Co-Work Área</button>
      </div>

      <div class="col-lg-10 pt-2">
      <button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 pb-2 text-uppercase" id="OP-2" onclick="mostrar_plan(this.id);captar_data(10)"><strong>NOMAD</strong><br>Co-Work Área</button>
      </div>

      <div class="col-lg-10 pt-2">
      <button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 pb-2 text-uppercase" id="OP-3" onclick="mostrar_plan(this.id);captar_data(11)"><strong>STABLE</strong><br>Lugar Fijo</button>
      </div>

      <div class="col-lg-10 pt-2">
      <button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 pb-2 text-uppercase" id="OP-4" onclick="mostrar_plan(this.id);captar_data(12)"><strong>COVE</strong><br>Semi-Privado</button>
      </div>

      <div class="col-lg-10 pt-2">
      <button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 pb-2 text-uppercase" id="OP-5" onclick="mostrar_plan(this.id);captar_data(13)"><strong>SUIT</strong><br>Oficina Privada</button>
      </div>

      <div class="col-lg-10 pt-2">
      <button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 pb-2 text-uppercase pt-3 pb-3" id="OP-6" onclick="mostrar_plan(this.id)"><strong>ARMA TU PLAN</strong><br></button>
      </div>-->
      </div>
      <!-- OPCIONES DE PLANES -->
    </div>

    <!-- CONTENIDO DE OPCION -->
    
    <!--  <div class="col-lg-8 col-md-8 pt-3  opcion-OP-1-contenido contenedor-plan">
        <div class="row">
          <div class="col-lg-5 col-md-5">
            <div class="contenedor-titulo">
              <h1 class="titulo-plan">
                NOMAD NOCTURNO
              </h1>
              <h1 class="descripcion-plan">
                Co-Work Área    
              </h1>

              <h1 class="costo-plan">
                $2.000
              </h1>

              
            <button class="btn btn-warning  pl-5 pr-5 mr-auto d-block pb-2 pb-2 text-uppercase pt-3 pb-3" onclick="mostrarformulario()"><strong>CONTRATAR</strong><br></button>

            </div>
          </div>
          <div class="col-lg-6 col-md-6">

            <ul class="lista_bondades_plan ml-auto">
              <li>
                  <span>   6:00 pm a 10:00 pm
              </li>
              <li>
                  <span>   80 horas al mes
              </li>
              <li>
                  <span>    6 hrs. de sala de juntas
              </li>
              <li>
                  <span>       Paquete Print Center
              </li>
              <li>
                  <span>       Uso de Locker diario 
              </li>
              <li>
                  <span>      Refreshment Center
              </li>
              <li>
                  <span>      5 Cafés Gourmet
              </li>
              <li>
                  <span>   Servicio Recepción y paquetería
              </li>
              <li>
                  <span>    Y mucho más...
              </li>
            </ul>

          </div>

          <div class="col-lg-12 col-md-12 letra-pequeña-planes text-light mt-1  ">
            <p>
              - Start Up Fee y Welcome kit, aplicable a todos los planes.
            </p>
            <p>
                - Aplica condiciones.
              </p>

              <p>
                  - Precios con IVA incluido.
                </p>
          </div>
        </div>
      </div>

      <div class="col-lg-8 col-md-8 pt-3 opcion-OP-2-contenido contenedor-plan">
          <div class="row">
            <div class="col-lg-4 col-md-5">
              <div class="contenedor-titulo">
                <h1 class="titulo-plan">
                  NOMAD
                </h1>
                <h1 class="descripcion-plan">
                  Co-Work Área    
                </h1>
  
                <h1 class="costo-plan">
                  $3.000
                </h1>
  
                
              <button class="btn btn-warning  pl-5 pr-5 mr-auto d-block pb-2 pb-2 text-uppercase pt-3 pb-3" onclick="mostrarformulario()"><strong>CONTRATAR</strong><br></button>
  
              </div>
            </div>
            <div class="col-lg-6 col-md-6">
  
              <ul class="lista_bondades_plan ml-auto">
                <li>
                    <span>    80 horas al mes
                </li>
                <li>
                    <span>    6 hrs. de sala de juntas
                </li>
                <li>
                    <span>       Paquete Print Center
                </li>
                <li>
                    <span>       Uso de Locker diario 
                </li>
                <li>
                    <span>      Refreshment Center
                </li>
                <li>
                    <span>      5 Cafés Gourmet
                </li>
                <li>
                    <span>          Recepción y paquetería
                </li>
                <li>
                    <span>    Y mucho más...
                </li>
              </ul>
  
            </div>
  
            <div class="col-lg-12 col-md-12 letra-pequeña-planes text-light mt-2  ">
              <p>
                - Start Up Fee y Welcome kit, aplicable a todos los planes.
              </p>
              <p>
                  - Aplica condiciones.
                </p>
  
                <p>
                    - Precios con IVA incluido.
                  </p>
            </div>
          </div>
      </div>
      
      <div class="col-lg-8 col-md-8 pt-3 opcion-OP-2a-contenido contenedor-plan">
          <div class="row">
            <div class="col-lg-4 col-md-5">
              <div class="contenedor-titulo">
                <h1 class="titulo-plan">
                  NOMAD ILIMITADO
                </h1>
                <h1 class="descripcion-plan">
                  Co-Work Área    
                </h1>
  
                <h1 class="costo-plan">
                  $6.000
                </h1>
  
                
              <button class="btn btn-warning  pl-5 pr-5 mr-auto d-block pb-2 pb-2 text-uppercase pt-3 pb-3" onclick="mostrarformulario()"><strong>CONTRATAR</strong><br></button>
  
              </div>
            </div>
            <div class="col-lg-6 col-md-6">
  
              <ul class="lista_bondades_plan ml-auto">
                <li>
                    <span>    acceso ilimitado
                </li>
                <li>
                    <span>    4 hrs. de sala de juntas
                </li>
                <li>
                    <span>       Paquete Print Center
                </li>
                <li>
                    <span>       Uso de Locker diario 
                </li>
                <li>
                    <span>      Refreshment Center
                </li>
                <li>
                    <span>      5 Cafés Gourmet
                </li>
                <li>
                    <span>          Recepción y paquetería
                </li>
                <li>
                    <span>    Y mucho más...
                </li>
              </ul>
  
            </div>
  
            <div class="col-lg-12 col-md-12 letra-pequeña-planes text-light mt-2  ">
              <p>
                - Start Up Fee y Welcome kit, aplicable a todos los planes.
              </p>
              <p>
                  - Aplica condiciones.
                </p>
  
                <p>
                    - Precios con IVA incluido.
                  </p>
            </div>
          </div>
      </div>


      <div class="col-lg-8 col-md-8 pt-3  opcion-OP-3-contenido contenedor-plan">
          <div class="row">
            <div class="col-lg-4 col-md-5">
              <div class="contenedor-titulo">
                <h1 class="titulo-plan">
                  STABLE
                </h1>
                <h1 class="descripcion-plan">
                  LUGAR FIJO    
                </h1>
  
                <h1 class="costo-plan">
                  $5.000
                </h1>
  
                
              <button class="btn btn-warning  pl-5 pr-5 mr-auto d-block pb-2 pb-2 text-uppercase pt-3 pb-3" onclick="mostrarformulario()"><strong>CONTRATAR</strong><br></button>
  
              </div>
            </div>
            <div class="col-lg-6 col-md-6">
  
              <ul class="lista_bondades_plan ml-auto">
                <li>
                    <span>    Acceso Ilimitado
                </li>
                <li>
                    <span>    6 hrs. de sala de juntas
                </li>
                <li>
                    <span>       Paquete Print Center
                </li>
                <li>
                    <span>       Archivero Personal
                </li>
                <li>
                    <span>      Refreshment Center
                </li>
                <li>
                    <span>       Estacionamiento 
                </li>
                <li>
                    <span>      10 Cafés Gourmet
                </li>
                <li>
                    <span>       Servicio Recepción y paquetería
                </li>
                <li>
                    <span>    Y mucho más...
                </li>
              </ul>
  
            </div>
  
            <div class="col-lg-12 col-md-12 letra-pequeña-planes text-light mt-1  ">
              <p>
                - Start Up Fee y Welcome kit, aplicable a todos los planes.
              </p>
              <p>
                  - Aplica condiciones.
                </p>
  
                <p>
                    - Precios con IVA incluido.
                  </p>
            </div>
          </div>
      </div>

      <div class="col-lg-8 col-md-8 pt-3  opcion-OP-4-contenido contenedor-plan">
          <div class="row">
            <div class="col-lg-5 col-md-5">
              <div class="contenedor-titulo">
                <h1 class="titulo-plan">
                  COVE
                </h1>
                <h1 class="descripcion-plan">
                  SEMI-PRIVADO    
                </h1>
  
                <h1 class="costo-plan">
                  $7.000
                </h1>
  
                
              <button class="btn btn-warning  pl-5 pr-5 mr-auto d-block pb-2 pb-2 text-uppercase pt-3 pb-3" onclick="mostrarformulario()"><strong>CONTRATAR</strong><br></button>
  
              </div>
            </div>
            <div class="col-lg-6 col-md-6">
  
            <ul class="lista_bondades_plan ml-auto">
                <li>
                    <span>    Acceso Ilimitado
                </li>
                <li>
                    <span>    8 hrs. de sala de juntas
                </li>
                <li>
                    <span>       Paquete Print Center
                </li>
                <li>
                    <span>       Archivero Personal
                </li>
                <li>
                    <span>      Refreshment Center
                </li>
                <li>
                    <span>       Estacionamiento 
                </li>
                <li>
                    <span>      10 Cafés Gourmet
                </li>
                <li>
                    <span>       Servicio Recepción y paquetería
                </li>
                <li>
                    <span>    Y mucho más...
                </li>
              </ul>
  
            </div>
  
            <div class="col-lg-12 col-md-12 letra-pequeña-planes text-light mt-1  ">
              <p>
                - Start Up Fee y Welcome kit, aplicable a todos los planes.
              </p>
              <p>
                  - Aplica condiciones.
                </p>
  
                <p>
                    - Precios con IVA incluido.
                  </p>
            </div>
          </div>
      </div>

      <div class="col-lg-8 col-md-8 pt-3  opcion-OP-5-contenido contenedor-plan">
          <div class="row">
            <div class="col-lg-5 col-md-5">
              <div class="contenedor-titulo">
                <h1 class="titulo-plan">
                  SUIT
                </h1>
                <h1 class="descripcion-plan">
                  OFICINA PRIVADA
                </h1>
  
                <h1 class="costo-plan">
                  $9.000
                </h1>
  
                
              <button class="btn btn-warning  pl-5 pr-5 mr-auto d-block pb-2 pb-2 text-uppercase pt-3 pb-3" onclick="mostrarformulario()"><strong>CONTRATAR</strong><br></button>
  
              </div>
            </div>
            <div class="col-lg-6 col-md-6">
  
            <ul class="lista_bondades_plan ml-auto">
                <li>
                    <span>    Acceso Ilimitado
                </li>
                <li>
                    <span>    8 hrs. de sala de juntas
                </li>
                <li>
                    <span>       Paquete Print Center
                </li>
                <li>
                    <span>       Archivero Personal
                </li>
                <li>
                    <span>      Refreshment Center
                </li>
                <li>
                    <span>       Estacionamiento 
                </li>
                <li>
                    <span>      20 Cafés Gourmet
                </li>
                <li>
                    <span>       Servicio Recepción y paquetería
                </li>
                <li>
                    <span>    Y mucho más...
                </li>
              </ul>
  
  
            </div>
  
            <div class="col-lg-12 col-md-12 letra-pequeña-planes text-light mt-1  ">
              <p>
                - Start Up Fee y Welcome kit, aplicable a todos los planes.
              </p>
              <p>
                  - Aplica condiciones.
                </p>
  
                <p>
                    - Precios con IVA incluido.
                  </p>
            </div>
          </div>
      </div>

      <div class="col-lg-8 col-md-8 pt-3  opcion-OP-6-contenido contenedor-plan">
          <div class="row">
            <div class="col-lg-7 col-md-7">
              <div class="contenedor-titulo">
                <h1 class="titulo-plan">
                  ARMA TU PLAN
                </h1>
                <h1 class="descripcion-plan">
                  A TU GUSTO    
                </h1>
  
                <h1 class="costo-plan">
                  
                </h1>
  
                
              <button class="btn btn-warning  pl-5 pr-5 mr-auto d-block pb-2 pb-2 text-uppercase pt-3 pb-3" onclick="mostrarformulario()"><strong>CONTRATAR</strong><br></button>
  
              </div>
            </div>
            <div class="col-lg-6 col-md-6">
  
            </div>
  
            <div class="col-lg-12 col-md-12 letra-pequeña-planes text-light mt-1  ">
              <p>
                - Start Up Fee y Welcome kit, aplicable a todos los planes.
              </p>
              <p>
                  - Aplica condiciones.
                </p>
  
                <p>
                    - Precios con IVA incluido.
                  </p>
            </div>
          </div>
      </div>-->
    <!-- CONTENIDO DE OPCION -->
  </div>




</div>



<H1 class="titulo-seccion text-uppercase" >PLANES&nbsp&nbsp&nbsp&nbsp<H1>

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



<div class="container-fluid" id="mas_info" onclick="ocultar_info()" style="display: block;">
  <div class="contenedor_opciones_menu">
    <div class="row">
    <div class="col-lg-7 mr-auto ml-auto d-block">
    <H1 class="text-center" style="color: #ffeb3b;">Este plan tambien puede tener</H1>
      <div  id="lista_paquetes">
        <!-- OTRO PAQUETES -->

      <!-- OTRO PAQUETES -->
      </div>
      <div class="row">
          <div class="col-lg-12">
              <button class="btn btn-warning  pl-5 pr-5 mr-auto ml-auto d-block pb-2 pb-2 text-uppercase pt-3 pb-3"   onclick="mostrarformulario()" style="font-weight: bold;">CONTRATAR</button>
          </div>
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


<script>
$("#mas_info").hide();
// variable de menu  
seccion_activa = 1

if(seccion_activa == 0 ){
      // ocultar boton menu
    $(".navbar").hide();
    console.log("No mostrar menu");
    $("#opciones_menu_cotainer").hide();
    }else{
      $(".navbar").show();
      $(".navbar").removeClass('navbar-dark')
    $(".navbar").addClass('navbar-light')
    }


charge_view_plains()


re = 0

//document.getElementById("OP-2").click();

function mostrarformulario() {
    $("#formulario").modal('show')
}


$(function(){
            $(".player-item").on("click", function(){
                var url = $(this).find(".img-polaroid").attr("src");
                ampliar_img_eq(url);
            });
        });


        $(function(){
           $(".banner_1").removeClass('d-lg-block');
           $(".banner_1").hide();
        });



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



  

  // CARGA DE PLANES DESDE EL BACK


  $.ajax({
                type: 'GET',
                url: 'consumir_planes',
                
                success: function (data) {

                  plan_fefecto_estatus = false;
                  planes_validos = 0;

                  for (var clave in data){
        if(data[clave].contenido.length === 0 ){
            //  console.log("No tiene contenido");
              }else{
              precio_base = data[clave].contenido[0].precio_paquete
              contenido_planes = data[clave].contenido[0].servicios
            // CARGA DE BOTONES PARA MOSTRAR
            // PAN POR DEFECTO
            if(plan_fefecto_estatus == false){
              paquete_defecto = data[clave].cod_planes;
         //     console.log(data[clave].cod_planes);
              plan_fefecto_estatus = true;
            }
                  planes_validos++
                  if(planes_validos > 6){                  
                  $("#opciones_panel").css("max-height","26.5rem");
                  $("#opciones_panel").css("overflow-y","scroll");
                  $("#opciones_panel").css("width","90%");
                }
                // PAN POR DEFECTO
                                $("#opciones_panel").append('<div class="col-lg-10 pt-2">'+
                  '<button class="btn btn-warning btn-op-planes mr-auto d-block pb-2 text-uppercase" id="'+data[clave].cod_planes+'" onclick="mostrar_plan(this.id);captar_data(9)" ><strong>'+data[clave].titulo+'</strong><br>'+data[clave].descripcion+'</button>'+
                  '</div>');
                  // variable de contenido final 
                  pq = "paquetes_"+data[clave].cod_planes;
                  //console.log(pq);
                   $("#contenido_planes").append('<div class="col-lg-8 col-md-8 pt-3  opcion-'+data[clave].cod_planes+'-contenido contenedor-plan">'+
                   '<div class="row"><div class="col-lg-5 col-md-5"><div class="contenedor-titulo"><h1 class="titulo-plan">'+data[clave].titulo+'</h1><h1 class="descripcion-plan">'+data[clave].descripcion+'</h1>'+
                   '<h1 class="costo-plan">$'+precio_base+'</h1><button class="btn btn-warning  pl-5 pr-5 mr-auto d-block pb-2 pb-2 text-uppercase pt-3 pb-3" data-pq="'+pq+'"  id="boton_'+data[clave].cod_planes+'"  onclick="mostrar_paquetes($(this).data(\'pq\'))"><strong>Ver Más</strong><br></button></div></div>'+
                   '<div class="col-lg-6 col-md-6"><ul class="lista_bondades_plan ml-auto" id="contenido_inicial_'+data[clave].cod_planes+'"></ul></div>'+
                   '<div class="col-lg-12 col-md-12 letra-pequeña-planes text-light mt-1"><p>- Start Up Fee y Welcome kit, aplicable a todos los planes.</p><p>- Aplica condiciones.</p><p>- Precios con IVA incluido.</p></div></div></div>')
              // AGREGAMOS EL PRIMER CONTENIDO
        for (var contenido in contenido_planes){
          $("#contenido_inicial_"+data[clave].cod_planes).append('<li><span>'+contenido_planes[contenido].descripcion_servicio+'</li>');
          //console.log(contenido_planes[contenido].descripcion_servicio)
        }
        // LLENAR LA LISTA DE PAQUETES  mostrarformulario()
        lista_paquetes = data[clave].contenido
        for (var contenido in lista_paquetes){
          if(contenido > 0){
           var codigo_paquetes = lista_paquetes[contenido].codigo_paquete;
          codigo_fianl = codigo_paquetes.replace(/\s+/g, "").trim();
            $("#lista_paquetes").append('<div class="row  paquetes pt-3 paquetes_'+data[clave].cod_planes+'" >'+
        '<div class="col-lg-4 text-light text-center"><h2>$'+lista_paquetes[contenido].precio_paquete+'</h2></div>'+
        '<div class="col-lg-6"><ul class="text-light" id="lista_paquete_'+codigo_fianl+'" ></ul></div>'+
        '</div>');
        lista_servicios_paquetes  =  lista_paquetes[contenido].servicios
              for (var servicio in lista_servicios_paquetes){
              //  console.log(lista_servicios_paquetes[servicio].descripcion_servicio);                        
                $("#lista_paquete_"+codigo_fianl).append('<li>'+lista_servicios_paquetes[servicio].descripcion_servicio+'</li>')  
              }
          }
        }
       if(data[clave].contenido.length > 1 ){
        //  console.log("posee varios paquetes");
          }else{
      //      console.log("solo tiene un paquete");
            $("#boton_"+data[clave].cod_planes).text("CONTRATAR").css("font-weight", "bold").attr("onclick", "mostrarformulario()");
          }
        }
      // CARGA DE MAS CONTENIDO QUE SOLO SE MUESTRA AL VER MAS
                }
                mostrar_plan(paquete_defecto)
                $(".paquetes").hide();
                }

                
                
  })


function mostrar_paquetes(paquetes){
 // console.log(paquetes);
  $("#mas_info").fadeIn(500);
  $("."+paquetes).fadeIn(800);

 /* $(".paquetes_P180116").show();
      paquetes_P180116
 */
}
  
function mostrar_plan(opcion){

  $(".contenedor-plan").hide();
  $(".contenedor-plan").hide()
  $(".opcion-"+opcion+"-contenido").show(500);
}


function ocultar_info(){
  $(".paquetes").fadeOut(300);
  $("#mas_info").fadeOut(500);

}

        </script>
    