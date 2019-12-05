<section role="main" class="content-body caja">
	<header class="page-header">
		<h2>Database Reset</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo base_url(); ?>cabecera">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li>
					<a>Database Reset</a>
				</li>
			</ol>
		</div>
	</header>

	<form id="database-reset-form" action="<?php echo base_url() ?>database/reset/ajax" method="post">
		<div class="form-group">
			<input type="submit" value="Go!" class="btn btn-danger" <?php echo confirmation('¡Está a punto de DESTRUIR la base de datos! ¿Desea continuar?') ?>>
		</div>
	</form>
	<div id="dbreset-feedback"></div>
</section>

<script>
	(function() {
		$('#database-reset-form').submit(function() {
			$('#dbreset-feedback').empty();
			dbReset({ reset: true, current: null });
			return false;
		});

		function dbReset(postData) {
			if (postData.last) {
				$('#dbreset-feedback').append('<p><span class="fa fa-check text-success"></span> ' + postData.last + '</p>');
			}
			$.post($('#database-reset-form').attr('action'), postData, function(data) {
				if (data.error) {
					$('#dbreset-feedback').prepend('<div class="alert alert-error">' + data.error + '</div>');
				}
				if (data.done) {
					$('#dbreset-feedback').prepend('<div class="alert alert-success">Todo listo!</div>');
				} else {
					postData.last = data.current;
					if (data.confirm) {
						if (confirm(data.confirm)) {
							dbReset(postData);
						} else {
							$('#dbreset-feedback').prepend('<div class="alert alert-success">Todo listo!</div>');
						}
					} else {
						dbReset(postData);
					}
				}
			}, 'json');
		}
	})();
</script>