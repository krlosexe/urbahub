<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<body class="theme-blue">
		<section class="content">
	        <div class="container-fluid">
				<div class="block-header">
	                <ol class="breadcrumb breadcrumb-col-cyan">
                        <li class="active">INICIO</li>
                    </ol>
	            </div>
	            <div class="col-xs-12">
                    <img src="<?=base_url();?>/assets/cpanel/images/login/fondo2.png" style="width: 100%;">
                </div>
			</div>
		</section>
	</body>
	<script>
		$(document).ready(function(){
			$("#inicio").attr('class', 'active');
		});
	</script>
</html>
