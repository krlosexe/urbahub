
            <div class="container" id="">
                <!--  Barra fila superior -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round round-lg align-self-center round-entradas"><i class="mdi mdi-book-open-page-variant"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0 font-light" id="total_entradas"><?php print_r($te+$tn+$to) ?></h3>
                                        <h5 class="text-muted m-b-0">Total Entradas</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round round-lg align-self-center round-noticias"><i class="mdi mdi-book-open-variant"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0 font-lgiht" id="entradas_noticia" ><?php print_r($tn) ?></h3>
                                        <h5 class="text-muted m-b-0">Total de Noticias</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round round-lg align-self-center round-eventos"><i class="mdi mdi-book-open-variant"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0 font-lgiht" id="entradas_evento"><?php print_r($te) ?></h3>
                                        <h5 class="text-muted m-b-0">Total de Eventos</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round round-lg align-self-center round-entradas-o"><i class="mdi mdi-eye-off"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0 font-lgiht" id="entradas_ocultas"><?php print_r($to) ?></h3>
                                        <h5 class="text-muted m-b-0">Entradas Ocultas</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <!--  Barra fila superior -->
                <!--  GRAFICAS  -->
                <div class="row">   
                    <!-- Column -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Estadisticas  de planes</h4>
                                <div class="table-responsive">
                                        <table class="table d-lg-none d-md-none d-block">
                                            <thead>
                                                <tr>
                                                    <th>Plan</th>
                                                    <th>Cantidad</th>
                                                
                                                </tr>
                                            </thead>
                                            <tbody id="tabla_planes">
                                  
                                            </tbody>
                                        </table>
                                    </div>
                                <div>
                                    <canvas id="chart1" class="d-none d-md-block" height="" width="" style=""></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 ">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Estadisticas de acceso</h4>
                                    <div class="table-responsive">
                                            <table class="table d-lg-none d-md-none d-block">
                                                <thead>
                                                    <tr>
                                                        <th>Seccioón</th>
                                                        <th>Cantidad</th>
                                                    
                                                    </tr>
                                                </thead>
                                                <tbody id="tabla_secciones">
                                      
                                                </tbody>
                                            </table>
                                        </div>
                                    <div>
                                        <canvas id="chart2" class="d-none d-md-block" height="" width="" style=""></canvas>
                                    </div>
                                </div>
                            </div>
                        </div> 
                </div>
                    <!-- Column -->
                <!--  GRAFICAS  -->
                <!--  Barra fila inferior -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round round-lg align-self-center round-email"><i class="mdi mdi-email"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0 font-light" id="email"></h3>
                                        <h5 class="text-muted m-b-0">Correos Enviados</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round round-lg align-self-center round-visitas"><i class="mdi mdi-login"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0 font-lgiht" id="visitas"></h3>
                                        <h5 class="text-muted m-b-0">Total de Visitas</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Column -->
                </div>
                <!--  Barra fila inferior -->
         
            </div>



            
<script>
//$( document ).ready(function() {




url = "consultar_estadisticas"
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
a = JSON.parse(response);

  var datalista = a[0];

  console.log(datalista);

  p1 = datalista.plan1;
  p2 = datalista.plan2;
  p3 = datalista.plan3;
  p4 = datalista.plan4;
  p5 = datalista.plan5;
  p6 = datalista.plan6;
  p7 = datalista.plan7;

vistas = datalista.visitas;
contacto = datalista.contacto;
galeria = datalista.galeria;
blog = datalista.blog;
beneficios = datalista.beneficios;
servicios = datalista.servicios;
planes = datalista.planes;

email = datalista.email;
ingresos = datalista.visitas;



// LLENAR GRAFICAS

    
    var ctx1 = document.getElementById("chart1").getContext("2d");
    var data1 = {
        labels: ["Nomad N", "Nomad I", "Nomad", "Stable", "Cove", "Suit"],
        datasets: [
            {
                label: "mes 1",
                fillColor: "#00263b",
                strokeColor: "#00263b",
                pointColor: "#00263b",
                pointStrokeColor: "#ffc107",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "#00263b",
                
                data: [p2, p7, p3, p4, p5, p6]
            },
            
            
        ],
        
    };
    Chart.types.Line.extend({
      name: "LineAlt",
      initialize: function () {
        Chart.types.Line.prototype.initialize.apply(this, arguments);

        var ctx = this.chart.ctx;
        var originalStroke = ctx.stroke;
        ctx1.stroke = function () {
          ctx1.save();
          ctx1.shadowColor = 'rgba(0, 0, 0, 0.9)';
          ctx1.shadowBlur = 10;
          ctx1.shadowOffsetX = 8;
          ctx1.shadowOffsetY = 8;
          originalStroke.apply(this, arguments)
          ctx1.restore();

        }
      }
    });
    var chart1 = new Chart(ctx1).LineAlt(data1, {
        scaleShowGridLines : true,
        scaleGridLineColor : "rgba(0,0,0,.005)",
        scaleGridLineWidth : 0,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        bezierCurve : true,
        bezierCurveTension : 0.4,
        pointDot : true,
        pointDotRadius : 4,
        pointDotStrokeWidth : 2,
        pointHitDetectionRadius : 2,
        datasetStroke : true,
		tooltipCornerRadius: 2,
        datasetStrokeWidth : 0,
        datasetFill : false,
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        responsive: true
    });





    var ctx2 = document.getElementById("chart2").getContext("2d");
    var data2 = {
        labels: ["Servicios", "Galeria", "Planes", "Blog","Beneficios","Contacto"],
        datasets: [
            {
                label: "My First dataset",
                fillColor: "#00263b",
                strokeColor: "#00263b",
                highlightFill: "#ffc107",
                highlightStroke: "#00263b",
                data: [servicios, galeria, planes, blog,beneficios, contacto]
            },
           /* {
                label: "My Second dataset",
                fillColor: "#55ce63",
                strokeColor: "#55ce63",
                highlightFill: "#55ce63",
                highlightStroke: "#55ce63",
                data: [28, 48, 40, 19, 86]
            }*/
        ]
    };
    
    var chart2 = new Chart(ctx2).Bar(data2, {
        scaleBeginAtZero : true,
        scaleShowGridLines : true,
        scaleGridLineColor : "#00263b6e",
        scaleGridLineWidth : 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        barShowStroke : true,
        barStrokeWidth : 0,
		tooltipCornerRadius: 2,
        barDatasetSpacing : 3,
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        responsive: true
    });
    




        // Cargar tablas 

        $("#tabla_planes").append('<tr><td>Virtual</td><td>'+p1+'</td></tr>'+
                                   '<tr><td>Nomad N</td><td>'+p2+'</td></tr>'+
                                   '<tr><td>Nomad</td><td>'+p3+'</td></tr>'+
                                   '<tr><td>Stable</td><td>'+p4+'</td></tr>'+
                                   '<tr><td>Cove</td><td>'+p5+'</td></tr>'+
                                   '<tr><td>Suit</td><td>'+p6+'</td></tr>'+
        '')

        
        $("#tabla_secciones").append('<tr><td>Servicios</td><td>'+servicios+'</td></tr>'+
                                   '<tr><td>Galeria</td><td>'+galeria+'</td></tr>'+
                                   '<tr><td>Planes</td><td>'+planes+'</td></tr>'+
                                   '<tr><td>Blog</td><td>'+blog+'</td></tr>'+
                                   '<tr><td>Contacto</td><td>'+contacto+'</td></tr>'+
                                
        '')


$("#visitas").html(ingresos)
$("#email").html(email)








})








</script>
