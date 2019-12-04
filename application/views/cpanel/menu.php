<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Top Bar -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="<?=base_url();?>">CRM-UrbanHub.</a>
        </div>
    </div>
</nav>
<!-- #Top Bar -->
<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <img src="<?php echo base_url();?>assets/cpanel/Usuarios/images/<?php echo $this->session->userdata('avatar_usuario');?>" width="48" height="48" alt="User" />
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $this->session->userdata('nombre') ?></div>
                <div class="email"><?php echo $this->session->userdata('correo_usuario') ?></div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="<?=base_url()?>perfil"><i class="material-icons">person</i>Perfil</a></li>
                        <li><a href="javascript:void(0);"><i class="material-icons">info</i>Manual Operativo</a></li>
                        <li role="seperator" class="divider"></li>
                        <li><a href="<?php echo base_url();?>auth/logout"><i class="material-icons">input</i>Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">Barra de Navegación</li>
                <li id="inicio">
                    <a href="<?=base_url()."inicio";?>">
                        <span>Inicio</span>
                    </a>
                </li>
                <?php if($modulos_vistas){ foreach($modulos_vistas as $modulo): ?>

                    <!--<li id="mv<?php //echo $modulo->id_modulo_vista; ?>">-->
                    <!--Cambio a Mongo db -->
                    <li id="mv<?php echo $modulo["_id"]->{'$id'}; ?>">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <span><?php echo $modulo["nombre_modulo_vista"]; ?></span>
                        </a>
                        <ul class="ml-menu">
                            <?php foreach($vistas as $vista): ?>
                                <?php 
                                //var_dump($vista->_id->{'$id'});die('');
                                //echo($vista->id_modulo_vista."<br>");
                                // if($modulo["id_modulo_vista"] == $vista->id_modulo_vista): 
                                if($modulo["_id"]->{'$id'} == $vista->id_modulo_vista): 
                                ?>                               
                                    <!--<li id="lv<?php //echo $vista["id_lista_vista"]; ?>">-->
                                    <li id="lv<?php echo $vista->_id->{'$id'}; ?>">
                                        <a href="<?=base_url();?><?php echo $vista->url_lista_vista; ?>"><?php echo $vista->nombre_lista_vista; ?></a>
                                    </li>
                                <?php endif ?>
                            <?php endforeach ?>
                        </ul>
                    </li>
                <?php endforeach; }else{ echo "No tiene permisos para acceder al modulo!";}?>
            </ul>
        </div>
        <!-- #Menu -->
        <!-- Footer -->
        <div class="legal">
            <div class="copyright">
                <span class="text-info">&copy; <?=date("Y")?>.</span> CRM-Gestión de Ventas.
            </div>
            <div class="version">
                <span class="text-info">Todos los derechos reservados by</span> <img src="<?=base_url()?>assets/cpanel/images/LOGO(AF)-AG2-01.svg" style="width: 25%;">
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
</section>
