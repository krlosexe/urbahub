<div class="container">
<div class="row">
    <div class="col-lg-9 col-xlg-10 col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title" id="1">Uso del modulo Blog</h4>
                <p>En este módulo, podrás carga nuevo contenido en tu sitio web, también podrás editar los contenidos previamente
                    cargados.</p>
                <p> Requisitos para cargar nuevo contenido:</p>
                <ul>
                    <li>Debes de tener una imagen</li>
                        <img class="img-fluid" src="../assets/img/document/blog1.png" alt="">
                    <li>La fecha</li>
                    <li>La Descripción, que es solo visible en el panel y es para que sirva de referencia.</li>
                    <li>El Titulo</li>
                    <li>Especificar el tipo de contenido.</li>
                    <img class="img-fluid" src="../assets/img/document/blog2.png" alt="">
                    <li>Los parrafos, se puede cargar todo el contenido que se quiera, para dejar un salto de linea se deben dejar dos para que se vea mejor al nivel de diseño.</li>
                    <img class="img-fluid" src="../assets/img/document/blog3.png" alt="">
                </ul>
                <h4 class="card-title m-t-40" id="2">Lista de Noticias y Eventos. </h4>
                <p>En las Listas de Eventos y nociticas podras ver una tabla con los contenidos de ese tipo que esten 
                previamente cargados, al lado derecho estan las acciones para cada contenido, las acciones son las siguientes:
                    <ul>
                        <li>Ver, podras ver el contenido seleccionado. </li>
                        <li>Podras editar todo el contenido, es decir, cambiar la imagen, fecha, parrafos, titulo y tipo de contenido. </li>
                        <li>Mostrar o Ocultar contenido, podras elegir si quieres que un contenido sea visible o no en la pagina web.</li>
                        <img class="img-fluid" src="../assets/img/document/blog4.png" alt="">
                    </ul>
            </p>
            <p>
                Si tienes problemas para identificar las acciones, solo debes dejar el puntero del mouse sobre el boton para ver que acción es. 
            </p>
                <h4 class="card-title m-t-40" id="3">Carrusel Principal</h4>
                <p>En este modulo, podras ver cada imagen carga en el menu principal, ademas tambien podras cambiar las imagenes, para cambiar las imagenes estas deben cumplir con las siguientes caracteristicas.</p>
                <ul>
                    <li>Deben tener un ancho de 1920px</li>
                    <li>Deben tener un alto de 552px</li>
                    <li>pesar menos de 2Mb de peso</li>
                </ul>
                <p>Si las imagenes no cumplen con estas caracteristicas no se podran subir ya que puden dañar el diseño del sitio web.</p>
                <p>
                        Si tienes problemas para identificar las acciones, solo debes dejar el puntero del mouse sobre el boton para ver que acción es. 
                    </p>
                <img class="img-fluid" src="../assets/img/document/carruselp.png" alt="">

                <h4 class="card-title m-t-40" id="4">Galeria</h4>
                <p>
                    En este modulo se pude administrar las imagenes que estan en la seccion de galeria en el sitio web, es decir que se pueden actualizar las imagenes.  
                </p>
                <img class="img-fluid" src="../assets/img/document/galeria.png" alt="">
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xlg-2 col-md-4">
        <div class="stickyside" style="">
            <div class="list-group" id="top-menu">
                <a href="#1" class="list-group-item active">Uso del modulo Blog</a>
                <a href="#2" class="list-group-item">Lista de Noticias y Eventos</a>
                <a href="#3" class="list-group-item">Carrusel Principal</a>
                <a href="#4" class="list-group-item">Galeria</a>                
            </div>
        </div>
    </div>
</div>

</div>



<script>
    // This is for the sticky sidebar    
    $(".stickyside").stick_in_parent({
        offset_top: 100
    });
    $('.stickyside a').click(function () {
        $('html, body').animate({
            scrollTop: $($(this).attr('href')).offset().top - 100
        }, 500);
        return false;
    });
    // This is auto select left sidebar
    // Cache selectors
    // Cache selectors
    var lastId,
        topMenu = $(".stickyside"),
        topMenuHeight = topMenu.outerHeight(),
        // All list items
        menuItems = topMenu.find("a"),
        // Anchors corresponding to menu items
        scrollItems = menuItems.map(function () {
            var item = $($(this).attr("href"));
            if (item.length) {
                return item;
            }
        });

    // Bind click handler to menu items


    // Bind to scroll
    $(window).scroll(function () {
        // Get container scroll position
        var fromTop = $(this).scrollTop() + topMenuHeight - 250;

        // Get id of current scroll item
        var cur = scrollItems.map(function () {
            if ($(this).offset().top < fromTop)
                return this;
        });
        // Get the id of the current element
        cur = cur[cur.length - 1];
        var id = cur && cur.length ? cur[0].id : "";

        if (lastId !== id) {
            lastId = id;
            // Set/remove active class
            menuItems
                .removeClass("active")
                .filter("[href='#" + id + "']").addClass("active");
        }
    });
</script>