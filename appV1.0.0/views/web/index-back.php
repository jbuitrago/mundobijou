<?= $slider_home ?>

<?= $slider_categorias ?>

<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-sm-12">
                <div class="box text-center">
                    <h3 class="text-uppercase">Nuevos Modelos</h3>
                    <a href="<?php echo site_url('productos'); ?>" class="btn btn-default btn-sm"><small>VER TODOS</small></a> </div>
                <div class="row products">

                    <?php foreach ($last_prod as $l_p): ?>

                        <div class="col-md-3 col-sm-4">
                            <div class="product">
                                <div class="image" style="height: 200px; width: 200px;"> <a href="<?php echo site_url(strtolower($l_p->categoria) . '/' . $l_p->titulo . '-' . $l_p->articulo . '-' . $l_p->id); ?>"> <img src="<?= base_url(); ?>uploads/<?php echo $l_p->file1; ?>" alt="" class="img-responsive image1"> </a>
                                    <div class="quick-view-button"> <a href="#" my-link="<?php echo site_url('web/modal_detalle/' . $l_p->slug); ?>" data-toggle="modal" data-target="#product-quick-view-modal" class="btn btn-default btn-sm">Vista rápida</a> </div>
                                </div>
                                <!-- /.image -->
                                <div class="text">
                                    <h3><a href="<?php echo site_url(strtolower($l_p->categoria) . '/' . $l_p->titulo . '-' . $l_p->articulo . '-' . $l_p->id); ?>"><?php echo $l_p->titulo; ?>  <?php echo $l_p->articulo; ?></a></h3>
                                    <p class="description"><?php echo $l_p->descripcion_corta; ?></p>
                                    <p class="price">
                                        <?php
                                        $precio_oferta = 0;

                                        if ($tipo_cliente == 2) :
                                            $precio_simple = number_format($l_p->precio_revendedor * $valor_dolar, 2, '.', '');
                                            $precio_oferta = number_format($l_p->precio_oferta_revendedor * $valor_dolar, 2, '.', '');
                                        else:
                                            $precio_simple = number_format($l_p->precio_mayorista * $valor_dolar, 2, '.', '');
                                            $precio_oferta = number_format($l_p->precio_oferta_mayorista * $valor_dolar, 2, '.', '');
                                        endif;
                                        ?>

                                        <?php if ($precio_oferta > 0): ?>
                                            <del>$<?= $precio_simple ?></del> $<?= $precio_oferta ?>
                                        <?php else: ?>
                                            $<?= $precio_simple ?>
                                        <?php endif; ?>    
                                    </p>
                                </div>
                                <!-- /.text -->
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php foreach ($last_combos as $l_c): ?>
                        <div class="col-md-3 col-sm-4">
                            <div class="product">
                                <div class="image"  style="height: 200px; width: 200px;"> <a href="<?= base_url(); ?>detalle_combo/<?php echo $l_c->slug; ?>"> <img src="<?= base_url(); ?>uploads/<?php echo $l_c->file1; ?>" alt="" class="img-responsive image1"> </a>
                                    <div class="quick-view-button"> <a href="#" my-link="<?php echo site_url('web/modal_detalle_combo/' . $l_c->slug); ?>" data-toggle="modal" data-target="#product-quick-view-modal" class="btn btn-default btn-sm">Vista rápida</a> </div>
                                </div>
                                <!-- /.image -->
                                <div class="text">
                                    <h3><a href="<?= base_url(); ?>detalle_combo/<?php echo $l_c->slug; ?>"><?php echo $l_c->titulo; ?>  <?php echo $l_c->articulo; ?></a></h3>
                                    <p class="description"><?php echo $l_c->descripcion_corta; ?></p>
                                    <p class="price">

                                        <?php
                                        $precio_oferta = 0;

                                        if ($tipo_cliente == 2) :
                                            $precio_simple = number_format($l_c->precio_revendedor * $valor_dolar, 2, '.', '');
                                            $precio_oferta = number_format($l_c->precio_oferta_revendedor * $valor_dolar, 2, '.', '');
                                        else:
                                            $precio_simple = number_format($l_c->precio_mayorista * $valor_dolar, 2, '.', '');
                                            $precio_oferta = number_format($l_c->precio_oferta_mayorista * $valor_dolar, 2, '.', '');
                                        endif;
                                        ?>

                                        <?php if ($precio_oferta > 0): ?>
                                            <del>$<?= $precio_simple ?></del> $<?= $precio_oferta ?>
                                        <?php else: ?>
                                            $<?= $precio_simple ?>
                                        <?php endif; ?>   
                                    </p>
                                </div>
                                <!-- /.text -->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- /.products -->

            </div>
            <!-- /.col-sm-12 -->
            <div class="col-sm-12">
                <div class="box text-center">
                    <h3 class="text-uppercase">Ofertas</h3>
                    <a href="<?php echo site_url('productos'); ?>" class="btn btn-default btn-sm"><small>VER TODAS</small></a> </div>
                <div class="row products">
                    <?php foreach ($ofertas as $oferta): ?>
                        <div class="col-md-3 col-sm-4">
                            <div class="product">
                                <div class="image"  style="height: 200px; width: 200px;"> <a href="<?php echo site_url(strtolower($oferta->categoria) . '/' . $oferta->titulo . '-' . $oferta->articulo . '-' . $oferta->id); ?>"> <img src="<?= base_url(); ?>uploads/<?php echo $oferta->file1; ?>" alt="" class="img-responsive image1"> </a>
                                    <div class="quick-view-button"> <a href="#" my-link="<?php echo site_url('web/modal_detalle/' .strtolower($oferta->categoria) . '/' . $oferta->titulo . '-' . $oferta->articulo . '-' . $oferta->id); ?>" data-toggle="modal" data-target="#product-quick-view-modal" class="btn btn-default btn-sm">Vista rápida</a> </div>
                                </div>
                                <!-- /.image -->
                                <div class="text">
                                    <h3><a href="<?php echo site_url(strtolower($oferta->categoria) . '/' . $oferta->titulo . '-' . $oferta->articulo . '-' . $oferta->id); ?>"><?php echo $oferta->titulo; ?>  <?php echo $oferta->articulo; ?></a></h3>
                                    <p class="description"><?php echo $oferta->descripcion_corta; ?></p>
                                    <p class="price">

                                        <?php
                                        $precio_oferta = 0;

                                        if ($tipo_cliente == 2) :
                                            $precio_simple = number_format($oferta->precio_revendedor * $valor_dolar, 2, '.', '');
                                            $precio_oferta = number_format($oferta->precio_oferta_revendedor * $valor_dolar, 2, '.', '');
                                        else:
                                            $precio_simple = number_format($oferta->precio_mayorista * $valor_dolar, 2, '.', '');
                                            $precio_oferta = number_format($oferta->precio_oferta_mayorista * $valor_dolar, 2, '.', '');
                                        endif;
                                        ?>

                                        <?php if ($precio_oferta > 0): ?>
                                            <del>$<?= $precio_simple ?></del> $<?= $precio_oferta ?>
                                        <?php else: ?>
                                            $<?= $precio_simple ?>
                                        <?php endif; ?>   

                                    </p>
                                </div>
                                <!-- /.text -->
                            </div>

                            <!-- /.product -->
                        </div>
                    <?php endforeach; ?>
                    <?php foreach ($ofertas_combos as $combo): ?>
                        <div class="col-md-3 col-sm-4">
                            <div class="product">
                                <div class="image"  style="height: 200px; width: 200px;"> <a href="<?= base_url(); ?>detalle_combo/<?php echo $combo->slug; ?>"> <img src="<?= base_url(); ?>uploads/<?php echo $combo->file1; ?>" alt="" class="img-responsive image1"> </a>
                                    <div class="quick-view-button"> <a href="#" my-link="<?php echo site_url('web/modal_detalle_combo/' . $combo->slug); ?>" data-toggle="modal" data-target="#product-quick-view-modal" class="btn btn-default btn-sm">Vista rápida</a> </div>
                                </div>
                                <!-- /.image -->
                                <div class="text">
                                    <h3><a href="<?= base_url(); ?>detalle_combo/<?php echo $combo->slug; ?>"><?php echo $combo->titulo; ?>  <?php echo $combo->articulo; ?></a></h3>
                                    <p class="description"><?php echo $combo->descripcion_corta; ?></p>
                                    <p class="price">

                                        <?php
                                        $precio_oferta = 0;

                                        if ($tipo_cliente == 2) :
                                            $precio_simple = number_format($combo->precio_revendedor * $valor_dolar, 2, '.', '');
                                            $precio_oferta = number_format($combo->precio_oferta_revendedor * $valor_dolar, 2, '.', '');
                                        else:
                                            $precio_simple = number_format($combo->precio_mayorista * $valor_dolar, 2, '.', '');
                                            $precio_oferta = number_format($combo->precio_oferta_mayorista * $valor_dolar, 2, '.', '');
                                        endif;
                                        ?>

                                        <?php if ($precio_oferta > 0): ?>
                                            <del>$<?= $precio_simple ?></del> $<?= $precio_oferta ?>
                                        <?php else: ?>
                                            $<?= $precio_simple ?>
                                        <?php endif; ?>   
                                    </p>
                                </div>
                                <!-- /.text -->
                            </div>

                            <!-- /.product -->
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- /.products -->

            </div>
            <!-- /.col-sm-12 -->

        </div>
        <!-- /.container -->

        <div class="bar background-image-fixed-2 no-mb color-white text-center">
            <div class="dark-mask"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="icon icon-lg"><i class="fa fa-pencil" aria-hidden="true"></i> </div>
                        <h1>Registrate en Mundo Bijou para ver todo nuestro catálogo</h1>
                        <p class="lead">Además por tu primer compra mayorista llevate el bono de descuento especial para clientes.</p>
                        <p class="text-center"> <a href="<?= base_url(); ?>/registrate" class="btn btn-primary btn-lg">Registrate YA!</a> </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="col-sm-12">
                <div class="box text-center">
                    <h3 class="text-uppercase">Donde estamos?</h3>
                </div>
                <div id="blog-homepage" class="row">
                    <div class="col-sm-6">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3283.9685457452056!2d-58.385511784363196!3d-34.604956880459206!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bccac58659e18b%3A0x6d854566ab7406ad!2sSarmiento+1179%2C+C1041AAW+CABA!5e0!3m2!1ses-419!2sar!4v1490657485413" height="300" frameborder="0" style="border:0; width:100%" allowfullscreen></iframe>
                    </div>
                    <div class="col-sm-6">
                        <h4>Información de Contacto</h4>
                        <p class="author-category">Horario de atención: Lunes a Viernes de 9 a 18 hrs. </p>
                        <hr>
                        <ul style="list-style: none; padding: 0">
                            <li>
                                <h4><i class="fa fa-map-marker" aria-hidden="true"></i> Sarmiento 1179 - Libertad 288 - CABA - Argentina</h4>
                            </li>
                            <li>
                                <h4><i class="fa fa-phone" aria-hidden="true"> </i>(011) 4382.1379</h4>
                            </li>
                            <li>
                                <h4><i class="fa fa-phone" aria-hidden="true"> </i>(011) 6088.7172 Líneas Rotativas</h4>
                            </li>
                            <li>
                                <h4><i class="fa fa-whatsapp" aria-hidden="true"> </i>(011) 6088.7172</h4>
                            </li>
                            <li>
                                <h4><a href="#"><i class="fa fa-envelope" aria-hidden="true"> </i>info@mundobijou.com.ar</a></h4>
                            </li>
                        </ul>
                    </div>
                </div>
                <br>
                <br>
            </div>
        </div>
