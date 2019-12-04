<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <body class="login-page">
        <div class="login-box">
            <div class="logo">
                <a class="s" href="javascript:void(0);">Sistema de Gestión de Ventas</a>
                <small></small>
            </div>
            <div class="card">
                <div class="body">
                    <form id="sign_in" method="POST" action="<?=base_url()?>auth/recu_clave">
                        <div class="msg">Recuperar Contraseña</div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="correo_usuario" placeholder="Correo Electrónico" required autofocus value="<?=$mensaje_result['correo']?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-xs-offset-4">
                                <button class="btn btn-block bg-pink waves-effect" type="submit">Enviar</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <p style="text-align: right;"><strong><a href="<?=base_url()?>auth/" style="color: #4a4a4a;">Iniciar Sesión</a></strong></p>
                        </div>
                    </form>
                </div>
            </div>

                <div class="alert alert-<?=$mensaje_result['tipo']?>" role="alert" style="display: <?=$mensaje_result['mostrar']?>;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <span><?=$mensaje_result['texto']?></span>
                </div>

        </div>
    </body>
</html>