
<!-- *** NAVBAR ***
_________________________________________________________ -->

<div class="navbar navbar-default navbar-fixed-top yamm" role="navigation" id="navbar" > 

    <!-- TOP BAR -->
    <div class="header-top-bar">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-xs-2 ">
                    <div class="call-us"> <a href="tel:01143821379" class="hidden-xs blanco" ><i class="fa fa-phone" aria-hidden="true"></i> <span class="hidden-sm hidden-xs">(011) 4382.1379 / 6088.7172</span></a> <a href="tel:0112777-0109" class="verde" ><i class="fa fa-whatsapp" aria-hidden="true"></i> <span class="hidden-sm hidden-xs">(011) 2777-0109</span></a> <a href="<?= base_url(); ?>contacto" class="blanco" ><i class="fa fa-envelope" aria-hidden="true"></i> <span class="hidden-sm hidden-xs">Contacto</span></a> 
                    <a href="<?= base_url(); ?>index" class="blanco" ><i class="fa fa-home" aria-hidden="true"></i> <span class="hidden-sm hidden-xs">Inicio</span></a>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-10 ">
                    <div class="top-link clearfix">
                        <ul class="link f-right" style="list-style-type: none;">
                            <?php if (empty($cliente_logged)): ?><li> <a href="#" data-toggle="modal" data-target="#login-modal"> <i class="fa fa-lock" aria-hidden="true"></i> Ingresar </a> </li> <?php endif; ?>
                            
                            <?php if (!empty($cliente_logged)): ?><li> <a href="<?php echo site_url('logout'); ?>"> <i class="fa fa-sign-out" aria-hidden="true"></i> Cerrar Sesion </a> </li> <?php endif; ?>
                            <li> <a href="<?= base_url(); ?>mi_cuenta"> <i class="fa fa-user" aria-hidden="true"></i> Mi cuenta </a> </li>
                            <li> <a href="<?= base_url(); ?>carro"> <i class="fa fa-shopping-cart"> &nbsp;<?php echo count($this->cart->contents()); ?> &nbsp;</i> Carro </a> </li>
                            <li> <a href="#" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i> Buscar </a> </li>
                        </ul>
                    </div>
                </div>
                <div class="collapse clearfix" id="search" style="text-align: center;">

                    <?php echo form_open('busqueda'); ?>
                   <!-- <form action="web/busqueda" method="get">-->
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Buscá en Mundo Bijou" name='search_ter'>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </span> </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN TOP BAR -->
    <div class="container">
        <div class="navbar-header"> <a class="navbar-brand home" href="<?= base_url(); ?>index"> <img src="<?= base_url(); ?>assets/web/img/logo.png" alt="Mundo Bijou"> <span class="sr-only">Mundo Bijou Home</span> </a> 

            <!-- MENU XS MOBILE -->
            <div class="navbar-buttons">
                <button type="button" class="navbar-toggle btn-primary" data-toggle="collapse" data-target="#navigation"> <span class="sr-only">Toggle navigation</span> <i class="fa fa-align-justify"></i> </button>
            </div>
        </div>
        <!--/.navbar-header -->

        <div class="navbar-collapse collapse" id="navigation">


            <ul class="nav navbar-nav ">

                <?php
                foreach ($categorias as $c):

                    if ($c->total == 0):
                        ?>
                            <li > <a href="<?= site_url(str_replace(' ', '-', $c->nombre) . '-' . $c->id); ?>" ><?php echo $c->nombre; ?><b class="caret"></b></a>

                        <?php else: ?>   
                        <li class="dropdown yamm-fw"> <a href="<?= site_url(str_replace(' ', '-', $c->nombre) . '-' . $c->id); ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $c->nombre; ?><b class="caret"></b></a>
                        <?php endif; ?>       

                        <ul class="dropdown-menu">
                            <li>
                                <div class="yamm-content">
                                    <div class="row">
                                        <div class="col-sm-3 hidden-xs">
                                            <div id="carousel-a" class="carousel slide carousel-sync" data-ride="carousel" data-pause="false">
                                                <div class="carousel-inner">

                                                    <?php
                                                    $count = 0;
                                                    foreach ($subcategorias as $sub):
                                                        if ($c->id == $sub->categoria_padre):
                                                            if ($sub->foto != null):
                                                                if ($count > 0):
                                                                    ?>
                                                                    <div class="item"> <img src="<?= base_url(); ?>uploads/<?php echo $sub->foto; ?> "> </div>
                                                                    <?php
                                                                else:
                                                                    $count++;
                                                                    ?>
                                                                    <div class="item active"> <img src="<?= base_url(); ?>uploads/<?php echo $c->foto; ?> "> </div>
                                                                <?php
                                                                endif;
                                                            endif;
                                                        endif;
                                                    endforeach;
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <ul>
                                                <?php echo '<li><a href="' . site_url(str_replace(' ', '-', $c->nombre) . '-' . $c->id) . '/">Todos </a></li>'; ?>
                                                <?php
                                                $cant = 0;
                                                foreach ($subcategorias as $sub):
                                                    if ($c->id == $sub->categoria_padre) :
                                                        if ($cant < 7):
                                                            ?>

                                                            <li><a href="<?= site_url(str_replace(' ', '-', $c->nombre) . '-' . $c->id . '/' . str_replace(' ', '-', $sub->nombre) . '-' . $sub->id) ?>"><?php echo $sub->nombre; ?></a> </li>
                                                            <?php
                                                            $cant++;
                                                        else:
                                                            ?>
                                                        </ul>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <ul> 
                                                            <li><a href="<?= site_url(str_replace(' ', '-', $c->nombre) . '-' . $c->id . '/' . str_replace(' ', '-', $sub->nombre) . '-' . $sub->id) ?>"><?php echo $sub->nombre; ?></a> </li>
                                                            <?php
                                                            $cant = 0;
                                                        endif;
                                                    endif;
                                                endforeach;
                                                ?>  

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer clearfix hidden-xs"> <a href="<?= site_url(str_replace(' ', '-', $c->nombre) . '-' . $c->id); ?>">
                                        <h4 class="pull-right"><?php echo $c->nombre; ?></h4>
                                    </a> </div>
                            </li>
                        </ul>

                    </li>
                <?php endforeach; ?>    

                <!--
                <li class="dropdown yamm-fw"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Anillos<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="yamm-content">
                                <div class="row">
                                    <div class="col-sm-3 hidden-xs">
                                        <div id="carousel-a" class="carousel slide carousel-sync" data-ride="carousel" data-pause="false">
                                            <div class="carousel-inner">
                                                <div class="item active"> <img src="<?= base_url(); ?>assets/web/img/productos/anillos/6533.jpg"> </div>
                                                <div class="item"> <img src="<?= base_url(); ?>assets/web/img/productos/anillos/10028.jpg"> </div>
                                                <div class="item"> <img src="<?= base_url(); ?>assets/web/img/productos/anillos/12136.jpg"> </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <ul>
                                            <li><a href="{#link/productos}">Anillos Ionizados AQ (128)</a> </li>
                                            <li><a href="{#link/productos}">Anillos AQ (127)</a> </li>
                                            <li><a href="{#link/productos}">Anillos PyO (119)</a> </li>
                                            <li><a href="{#link/productos}">Anillos PyO (111)</a> </li>
                                            <li><a href="{#link/productos}">Anillos Hombre AQ (102)</a> </li>
                                            <li><a href="{#link/productos}">Anillos AQ (99)</a> </li>
                                            <li><a href="{#link/productos}">Anillos PyO (98)</a> </li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-3">
                                        <ul>
                                            <li><a href="{#link/productos}">Anillos AQ (93)</a> </li>
                                            <li><a href="{#link/productos}">Anillos PyO (88)</a> </li>
                                            <li><a href="{#link/productos}">Anillos Oferta AQ (87))</a> </li>
                                            <li><a href="{#link/productos}">Anillos Hombre AQ (77)</a> </li>
                                            <li><a href="{#link/productos}">Anillos AQ (76)</a> </li>
                                            <li><a href="{#link/productos}">Anillos PyO (75)</a> </li>
                                            <li><a href="{#link/productos}">Anillos PyO (71)</a> </li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-3">
                                        <ul>
                                            <li><a href="{#link/productos}">Anillos AQ (70)</a> </li>
                                            <li><a href="{#link/productos}">Anillos AQ (66)</a> </li>
                                            <li><a href="{#link/productos}">Anillos PyOyP (62)</a> </li>
                                            <li><a href="{#link/productos}">Anillos AQ (55)</a> </li>
                                            <li><a href="{#link/productos}">Anillos AQ (51)</a> </li>
                                            <li><a href="{#link/productos}">Anillos PyO (50)</a> </li>
                                            <li><a href="{#link/productos}">Anillos PyO (925)</a> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="footer clearfix hidden-xs"> <a href="{#link/productos}">
                                    <h4 class="pull-right">Anillos</h4>
                                </a> </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown yamm-fw"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Aros <b class="caret"></b></a> </li>
                <li class="dropdown yamm-fw"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="200">Cadenas <b class="caret"></b></a> </li>
                <li><a href="{#link/productos}">Conjuntos <b class="caret"></b></a></li>
                <li><a href="{#link/productos}">Dijes <b class="caret"></b></a></li>
                <li><a href="{#link/productos}">Estuches <b class="caret"></b></a></li>
                <li><a href="{#link/productos}">Pulseras <b class="caret"></b></a></li>
                <li><a href="{#link/productos}">Rosarios <b class="caret"></b></a></li>
                <li><a href="{#link/productos}">Ofertas <b class="caret"></b></a></li>
                <li><a href="{#link/productos}">Accesorios <b class="caret"></b></a></li>
                -->
                <?php if($mostrar_categoria_combos>0):?><li><a href="<?= base_url(); ?>combos">Combos <b class="caret"></b></a></li><?php endif;?>
            </ul>
        </div>
        <!--/.nav-collapse --> 

        <!--/.nav-collapse --> 

    </div>
</div>
<!-- /#navbar --> 

<!-- *** NAVBAR END *** -->