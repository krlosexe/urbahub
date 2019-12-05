<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/libs/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/libs/font-awesome/css/font-awesome.css?v=4.7" />
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/libs/magnific-popup/magnific-popup.css" />
		<!-- <link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/libs/bootstrap-datepicker/css/datepicker3.css" />-->
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/libs/pnotify/pnotify.custom.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/js/comprobantes/jquery-ui/jquery-ui.min.css" />
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/libs/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/libs/bootstrap-multiselect/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/libs/morris/morris.css" />
		<!-- Theme CSS -->
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/css/stylesheets/theme.css?v=11" />
		<!-- Skin CSS -->
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/css/stylesheets/skins/default.css?v=1" />
		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/css/stylesheets/theme-custom.css">
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/css/stylesheets/theme-custom.css">
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/css/employee.css">
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/css/angucomplete-alt.css">
		<!-- <link rel="stylesheet" href="assets/css/structure.css"> -->
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/fonts/bariol/bariol.css">
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/libs/dropzone/css/basic.css" />
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/libs/dropzone/css/dropzone.css" />
		<link rel="stylesheet" href="http://192.168.2.113/nube360/assets/cssgestion/css/styles.css?v=96">

		<!-- Head Libs -->
		<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/modernizr/modernizr.js"></script>

		<link type="text/css" rel="stylesheet" href="http://192.168.2.113/nube360/assets/grocery_crud/themes/bootstrap2/css/list.css">
		<link rel="stylesheet" type="text/css" href="http://192.168.2.113/nube360/assets/datatables/media/css/jquery.dataTables.css"/>

		<!-- Selects combos-->
		<link rel="stylesheet" type="text/css" href="http://192.168.2.113/nube360/assets/css/select/select2/select2.css"/>
		<link rel="stylesheet" type="text/css" href="http://192.168.2.113/nube360/assets/css/select/multi-select/multi-select.css"/>

		
		<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>-->
		<script src="http://192.168.2.113/nube360/assets/js/jquery.min.js"></script>

		<script type="text/javascript">
			var baseurl = "http://192.168.2.113/nube360/";
		</script>

<section role="main" class="content-body caja">
	<header class="page-header">
		<h2>Database Status</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>cabecera">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li>
					<a>Database Status</a>
				</li>
			</ol>
		</div>
	</header>

	<?php if (!empty($success)) {
        echo '<div class="alert alert-success">' . $success . '</div>';
    }
    if (!empty($error)) {
        echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
		<form action="" method="post">
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label>
							Ingrese las bases de datos en las que correrá el script
							<textarea name="databases" class="form-control" name="bases"></textarea>
						</label>
					</div>
				</div>
				<div class="col-sm-6">
					<?php if (!empty($files)) { ?>
						<p>Scripts generales:</p>
						<?php foreach ($files as $file) { ?>
							<div class="form-group checkbox">
								<label>
									<?php echo '<input name="run[]" type="checkbox" value="' . $file . '" checked>' . $file; ?>
								</label>
							</div>
						<?php }
					}
					if (!empty($specfiles)) { ?>
						<p>Scripts específicos:</p>
						<?php foreach ($specfiles as $file) { ?>
							<div class="form-group checkbox">
								<label>
									<?php echo '<input name="run[]" type="checkbox" value="' . $file . '" checked>' . $file; ?>
								</label>
							</div>
						<?php }
					} ?>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<input class="btn btn-success" type="submit" value="Obtener consultas">
					</div>
				</div>
			</div>
		</form>
		<?php if (!empty($queries)) {
			echo '<pre>' . implode(PHP_EOL, $queries) . '</pre>';
		} ?>
    <?php } ?>
</section>

<!-- Vendor -->
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jquery-browser-mobile/jquery.browser.mobile.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/bootstrap/js/bootstrap.js"></script>
		<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/magnific-popup/magnific-popup.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jquery-placeholder/jquery.placeholder.js"></script>
	<!-- Specific Page Vendor -->
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jquery-ui/js/jquery-ui-1.12.1.custom.min.js"></script>
		<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jquery-appear/jquery.appear.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/bootstrap-multiselect/bootstrap-multiselect.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jquery-easypiechart/jquery.easypiechart.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/flot/jquery.flot.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/flot-tooltip/jquery.flot.tooltip.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/flot/jquery.flot.pie.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/flot/jquery.flot.categories.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/flot/jquery.flot.resize.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jquery-sparkline/jquery.sparkline.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/raphael/raphael.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/morris/morris.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/gauge/gauge.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/snap-svg/snap.svg.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/liquid-meter/liquid.meter.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jqvmap/jquery.vmap.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jqvmap/data/jquery.vmap.sampledata.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jqvmap/maps/jquery.vmap.world.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jqvmap/maps/continents/jquery.vmap.africa.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jqvmap/maps/continents/jquery.vmap.asia.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jqvmap/maps/continents/jquery.vmap.australia.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jqvmap/maps/continents/jquery.vmap.europe.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/pnotify/pnotify.custom.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/ios7-switch/ios7-switch.js"></script>
	<script src="http://192.168.2.113/nube360/assets/js/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js"></script>
	<script src="http://192.168.2.113/nube360/assets/js/plugins/bootstrap-confirmation/bootstrap-confirmation.config.js"></script>
	<script src="http://192.168.2.113/nube360/assets/js/main.js?v=3"></script>

	<!-- Theme Base, Components and Settings -->
	<script src="http://192.168.2.113/nube360/assets/cssgestion/js/theme/theme.js"></script>

	<!-- Theme Custom -->
	<script src="http://192.168.2.113/nube360/assets/cssgestion/js/theme/theme.custom.js"></script>
	<!-- Theme Initialization Files -->
	<script src="http://192.168.2.113/nube360/assets/cssgestion/js/theme/theme.init.js"></script>

	<script src="http://192.168.2.113/nube360/assets/js/jquery.maskedinput.js" type="text/javascript"></script>

	<script src="http://192.168.2.113/nube360/assets/js/comprobantes/ini.js"></script>
	<script src="http://192.168.2.113/nube360/assets/js/comprobantes/jquery.anexsoft-validator.js"></script>
	<script src="http://192.168.2.113/nube360/assets/js/comprobantes/js-render.js"></script>
	<script src="http://192.168.2.113/nube360/assets/js/comprobantes/jquery.anexgrid.min.js"></script>

	<script src="http://192.168.2.113/nube360/assets/grocery_crud/themes/bootstrap2/js/jquery-plugins/bootstrap-growl.min.js"></script>
	<script src="http://192.168.2.113/nube360/assets/grocery_crud/themes/bootstrap2/js/datagrid/gcrud.datagrid.js?v=2"></script>
	<script src="http://192.168.2.113/nube360/assets/grocery_crud/themes/bootstrap2/js/datagrid/list.js"></script>
	<script type="text/javascript" src="http://192.168.2.113/nube360/assets/datatables/media/js/jquery.dataTables.js"></script>

		
	<script src="http://192.168.2.113/nube360/assets/cssgestion/libs/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="http://192.168.2.113/nube360/assets/cssgestion/js/script/script.js?v=3"></script>
	<script src="http://192.168.2.113/nube360/assets/scripts-nahuen/comprobantegral.js?v=140"></script>


	<!-- select combo-->
	<script type="text/javascript" src="http://192.168.2.113/nube360/assets/css/select/select2/select2.min.js"></script>
	<script type="text/javascript" src="http://192.168.2.113/nube360/assets/css/select/boot-select/bootstrap-select.min.js"></script>
	<script type="text/javascript" src="http://192.168.2.113/nube360/assets/css/select/multi-select/jquery.multi-select.js"></script>
	<script src="http://192.168.2.113/nube360/assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="http://192.168.2.113/nube360/assets/js/page/selectbox.js"></script>

	<script type="text/javascript" charset="utf-8">
		$(function() {
			cargaralertas();

			function cargaralertas() {
				setTimeout(cargaralertas, 60000);
				$.ajax({
					url: "http://192.168.2.113/nube360/cabecera/alertas",
					type:'POST',
					success: function(output_string){
						$("#alertas").html(output_string);
					}
				});
			}
		});
		$(document).keydown(function(e) {
			if (e.keyCode == 112) {
				// F1
				e.preventDefault();
				modalProductos();
			}
		});
		$('.keyboard-shortcuts .f1').click(function() {
			modalProductos();
		});
		function modalProductos() {
			$('#modal-productos').modal('show');
			$('#modal-productos-inner').addClass('loading').html('<p class="loader"></p>');
			$.get('http://192.168.2.113/nube360/productos/modal', function(data) {
				$('#modal-productos-inner').removeClass('loading').html(data);
				$('.gc-container').datagrid();
				$('#modal-productos .search-button').trigger('click');
			});
		}
	</script>
		<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-104076333-1', 'auto');
	  ga('send', 'pageview');
	
	</script>

