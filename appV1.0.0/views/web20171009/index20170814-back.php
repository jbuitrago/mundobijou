<?= $slider_home ?>

<?= $slider_categorias ?>

<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-sm-12">
                <div class="box text-center">
                    <h3 class="text-uppercase">Nuevos Modelos</h3>
                    <a href="productos" class="btn btn-default btn-sm"><small>VER TODOS</small></a> </div>
                <div class="row products">

                    <?php foreach ($last_prod as $l_p): ?>
                        <div class="col-md-3 col-sm-4">
                            <div class="product">
                                <div class="image"> <a href="<?= base_url(); ?>detalle/<?php echo $l_p->slug; ?>"> <img src="<?= base_url(); ?>uploads/<?php echo $l_p->file1; ?>" alt="" class="img-responsive image1"> </a>
                                    <div class="quick-view-button"> <a href="#" my-link="<?php echo site_url('web/modal_detalle/' . $l_p->slug); ?>" data-toggle="modal" data-target="#product-quick-view-modal" class="btn btn-default btn-sm">Vista rápida</a> </div>
                                </div>
                                <!-- /.image -->
                                <div class="text">
                                    <h3><a href="<?= base_url(); ?>detalle/<?php echo $l_p->slug; ?>"><?php echo $l_p->titulo; ?>  <?php echo $l_p->articulo; ?></a></h3>
                                    <p class="description"><?php echo $l_p->descripcion_corta; ?></p>
                                    <p class="price">$<?php echo $l_p->precio_mayorista; ?></p>
                                </div>
                                <!-- /.text -->
                            </div>
                        </div>   
                    <?php endforeach; ?>
                     <?php foreach ($last_combos as $l_c): ?>
                        <div class="col-md-3 col-sm-4">
                            <div class="product">
                                <div class="image"> <a href="<?= base_url(); ?>detalle/<?php echo $l_c->slug; ?>"> <img src="<?= base_url(); ?>uploads/<?php echo $l_c->file1; ?>" alt="" class="img-responsive image1"> </a>
                                    <div class="quick-view-button"> <a href="#" my-link="<?php echo site_url('web/modal_detalle/' . $l_c->slug); ?>" data-toggle="modal" data-target="#product-quick-view-modal" class="btn btn-default btn-sm">Vista rápida</a> </div>
                                </div>
                                <!-- /.image -->
                                <div class="text">
                                    <h3><a href="<?= base_url(); ?>detalle/<?php echo $l_p->slug; ?>"><?php echo $l_c->titulo; ?>  <?php echo $l_c->articulo; ?></a></h3>
                                    <p class="description"><?php echo $l_c->descripcion_corta; ?></p>
                                    <p class="price">$<?php echo $l_c->precio_mayorista; ?></p>
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
                    <a href="#" class="btn btn-default btn-sm"><small>VER TODAS</small></a> </div>
                <div class="row products">
                    <?php foreach ($ofertas as $o): ?>
                        <div class="col-md-3 col-sm-4">
                            <div class="product">
                                <div class="image"> <a href="<?= base_url(); ?>detalle/<?php echo $l_p->slug; ?>"> <img src="<?= base_url(); ?>uploads/<?php echo $o->file1; ?>" alt="" class="img-responsive image1"> </a>
                                    <div class="quick-view-button"> <a href="#" my-link="<?php echo site_url('web/modal_detalle/' . $o->slug); ?>" data-toggle="modal" data-target="#product-quick-view-modal" class="btn btn-default btn-sm">Vista rápida</a> </div>
                                </div>
                                <!-- /.image -->
                                <div class="text">
                                    <h3><a href="<?= base_url(); ?>detalle/<?php echo $l_p->slug; ?>"><?php echo $o->titulo; ?>  <?php echo $o->articulo; ?></a></h3>
                                    <p class="description"><?php echo $o->descripcion_corta; ?></p>
                                    <p class="price"><del>$280</del> $143.00</p>
                                </div>
                                <!-- /.text --> 
                            </div>

                            <!-- /.product --> 
                        </div>
                    <?php endforeach; ?>
                      <?php foreach ($ofertas_combos as $o): ?>
                        <div class="col-md-3 col-sm-4">
                            <div class="product">
                                <div class="image"> <a href="<?= base_url(); ?>detalle/<?php echo $l_p->slug; ?>"> <img src="<?= base_url(); ?>uploads/<?php echo $o->file1; ?>" alt="" class="img-responsive image1"> </a>
                                    <div class="quick-view-button"> <a href="#" my-link="<?php echo site_url('web/modal_detalle/' . $o->slug); ?>" data-toggle="modal" data-target="#product-quick-view-modal" class="btn btn-default btn-sm">Vista rápida</a> </div>
                                </div>
                                <!-- /.image -->
                                <div class="text">
                                    <h3><a href="<?= base_url(); ?>detalle/<?php echo $l_p->slug; ?>"><?php echo $o->titulo; ?>  <?php echo $o->articulo; ?></a></h3>
                                    <p class="description"><?php echo $o->descripcion_corta; ?></p>
                                    <p class="price"><del>$280</del> $143.00</p>
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
