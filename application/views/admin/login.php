<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <body class="login-page">
        <div class="login-box">
            <div class="logo">
                <a class="s" href="javascript:void(0);"></a>
                <small></small>
            </div>
            <div class="card" style="margin-top: 220px;">
                <div class="body">
                    <form id="sign_in" method="POST" action="<?php echo base_url();?>auth/login">
                        <div class="msg">Iniciar Sesión</div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="correo_usuario" placeholder="Correo" required autofocus>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">lock</i>
                            </span>
                            <div class="form-line">
                                <input type="password" class="form-control" name="clave_usuario" placeholder="Contraseña" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-xs-offset-4">
                                <button class="btn btn-block bg-pink waves-effect" type="submit">Entrar</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <p style="text-align: right;"><strong><a href="<?=base_url()?>auth/recu_clave" style="color: #4a4a4a;">¿Olvidó su contraseña?</a></strong></p>
                        </div>
                    </form>
                </div>
            </div>
            <?php if($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <span><?= $this->session->flashdata('error') ?></span>
                </div>
            <?php endif ?>
        </div>
    </body>
</html>