<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <div class="row">
                    <div class="box text-center">
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <h1>Combos</h1>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <!-- *** LEFT COLUMN ***
                                _________________________________________________________ -->

                <!--<div class="col-sm-3"> -->

                <!-- *** MENUS AND FILTERS ***
       _________________________________________________________
                <div class="panel panel-default sidebar-menu">
                    <div class="panel-heading">
                        <h3 class="panel-title">Categorías</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills nav-stacked category-menu">
                            <li> <a href="{#link/productos}">Anillos <span class="badge pull-right">142</span></a>
                                <ul>
                                    <li><a href="{#link/productos}">Anillos AQ (127)</a> </li>
                                    <li><a href="{#link/productos}">Anillos AQ (51)</a> </li>
                                    <li><a href="{#link/productos}">Anillos AQ (55)</a> </li>
                                    <li><a href="{#link/productos}">Anillos AQ (66)</a> </li>
                                </ul>
                            </li>
                            <li> <a href="{#link/productos}">Aros <span class="badge pull-right">123</span></a>
                                <ul>
                                    <li><a href="{#link/productos}">Aros AQ (123)</a> </li>
                                    <li><a href="{#link/productos}">Aros AQ (129)</a> </li>
                                    <li><a href="{#link/productos}">Aros AQ (53)</a> </li>
                                    <li><a href="{#link/productos}">Aros AQ (100)</a> </li>
                                </ul>
                            </li>
                            <li> <a href="{#link/productos}">Cadenas <span class="badge pull-right">11</span></a>
                                <ul>
                                    <li><a href="{#link/productos}">Cadenas AQ (67)</a> </li>
                                    <li><a href="{#link/productos}">Cadenas Gruesas AQ (125)</a> </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- *** MENUS AND FILTERS END

                    <div class="banner"> <a href="<?php echo site_url('index'); ?>"> <img src="<?= base_url(); ?>assets/web/img/banner.jpg" alt="Mundo Bijou" class="img-responsive"> </a> </div>

                                        <div class="banner"> <a href="<?php echo site_url('index'); ?>"> <img src="<?= base_url(); ?>assets/web/img/banner3.jpg" alt="Mundo Bijou" class="img-responsive"> </a> </div>

                                                            <div class="banner"> <a href="<?php echo site_url('index'); ?>"> <img src="<?= base_url(); ?>assets/web/img/banner4.jpg" alt="Mundo Bijou" class="img-responsive"> </a> </div>
                <!-- /.banner -->

                <!--</div>-->
                <!-- /.col-md-3 -->

                <!-- *** LEFT COLUMN END *** -->

                <!-- *** RIGHT COLUMN ***
                                _________________________________________________________ -->

                <div class="col-sm-12">
                    <div class="box info-bar no-border">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 products-showing"> Mostrando <strong><?= $cantidad_productos ?></strong> de <strong><?= $total_productos ?></strong> productos </div>
                            <div class="col-sm-12 col-md-8  products-number-sort">
                                <div class="row">
                                    <form class="form-inline">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="products-number"> <strong>Mostrar</strong>
                                                <a href="<?= base_url(); ?>combos/<?= $categoria ?>/<?= $subcategoria ?>/<?= (($sort == 'precio_mayorista')and ( $order == 'desc')) ? $sort . '_' . $order : $sort ?>/12/<?= $page ?>" class="btn btn-default btn-sm <?= ($per_page == 12) ? 'btn-primary' : '' ?>">12</a>
                                                <a href="<?= base_url(); ?>combos/<?= $categoria ?>/<?= $subcategoria ?>/<?= (($sort == 'precio_mayorista')and ( $order == 'desc')) ? $sort . '_' . $order : $sort ?>/24/<?= $page ?>" class="btn btn-default btn-sm <?= ($per_page == 24) ? 'btn-primary' : '' ?>">24</a>
                                                <a href="<?= base_url(); ?>combos/<?= $categoria ?>/<?= $subcategoria ?>/<?= (($sort == 'precio_mayorista')and ( $order == 'desc')) ? $sort . '_' . $order : $sort ?>/99999999/<?= $page ?>" class="btn btn-default btn-sm <?= ($per_page == 99999999) ? 'btn-primary' : '' ?>">Todos</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="products-sort-by"> <strong>Ordenar</strong>
                                                <select name="sort-by" class="form-control" onchange="ordenar(this.value)">
                                                    <option value="precio_mayorista" <?= ($sort == 'precio_mayorista') ? 'selected' : '' ?>>Menor Precio</option>
                                                    <option value="precio_mayorista_desc" <?= (($sort == 'precio_mayorista')and ( $order == 'desc')) ? 'selected' : '' ?>>Mayor Precio</option>
                                                    <option value="titulo" <?= ($sort == 'titulo') ? 'selected' : '' ?>>Nombre</option>
                                                    <option value="moresell" <?= ($sort == 'moresell') ? 'selected' : '' ?>>Más Vendidos</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- products -->
                    <div class="row products">
                        <div class="col-xs-12">

                            <?php foreach ($combos as $combo): ?>

                                <div class="product combo">
                                    <div class="image col-md-5 col-sm-12"> <a href="<?php echo site_url('detalle_combo/' . $combo->slug); ?>"> <img src="<?= base_url() . 'uploads/' . $combo->file1; ?>" alt="<?= $combo->titulo ?> art.<?= $combo->articulo ?>" class="img-responsive image1"> </a>
                                        <!--<div class="quick-view-button"> <a href="#" data-toggle="modal" data-target="#combo-quick-view-modal" class="btn btn-default btn-sm">Vista rápida</a> </div>-->
                                        <div class="quick-view-button"> <a href="#" my-link="<?php echo site_url('web/modal_detalle_combo/' . $combo->slug); ?>" data-toggle="modal" data-target="#combo-quick-view-modal" class="btn btn-default btn-sm">Vista rápida</a> </div>
                                    </div>
                                    <!-- /.image -->
                                    <div class="text col-md-7 col-sm-12 text-left">
                                        <h3><a href="<?php echo site_url('detalle_combo/' . $combo->slug); ?>"><?= $combo->titulo ?> art.<?= $combo->articulo ?></a></h3>

                                        <?php foreach ($combo->composicion as $cpm): ?>

                                            <p class="description"><img src="<?= base_url() . 'uploads/' . $cpm->imagen; ?>" alt=""> <?= $cpm->cantidad; ?> <?= $cpm->nombre; ?> - <a href="<?php echo site_url('detalle_combo/' . $combo->slug); ?>">art. <?= $cpm->numero_articulo; ?></a></p>

                                        <?php endforeach; ?>

                                        <p class="price" >

                                            <?php
                                            if ($tipo_cliente == 2) :
                                                $precio_simple = number_format($combo->precio_revendedor * $valor_dolar, 2, '.', '');
                                                $precio_oferta = number_format($combo->precio_oferta_revendedor * $valor_dolar, 2, '.', '');
                                            else:
                                                $precio_simple = number_format($combo->precio_mayorista * $valor_dolar, 2, '.', '');
                                                $precio_oferta = number_format($combo->precio_oferta_mayorista * $valor_dolar, 2, '.', '');
                                            endif;
                                            ?>
                                            <?php if ($precio_oferta > 0): ?>
                                                Precio Unitario: <del>$<?= $precio_simple ?></del> $<?= $precio_oferta ?>
                                            <?php else: ?>
                                                Precio Unitario: $<?= $precio_simple ?>
                                            <?php endif; ?>


                                        </p>
                                        <p class="price" >
                                            <?php
                                            if ($combo->precio_por_diez_revendedor > 0) {

                                                if ($tipo_cliente == 2) :
                                                    $precio_simple_combo = number_format($combo->precio_por_diez_revendedor * $valor_dolar, 2, '.', '');
                                                    $precio_oferta_combo = number_format($combo->precio_por_diez_oferta_revendedor * $valor_dolar, 2, '.', '');
                                                else:
                                                    $precio_simple_combo = number_format($combo->precio_por_diez_mayorista * $valor_dolar, 2, '.', '');
                                                    $precio_oferta_combo = number_format($combo->precio_por_diez_oferta_mayorista * $valor_dolar, 2, '.', '');
                                                endif;
                                                ?>

                                                <?php if ($precio_oferta_combo > 0): ?>
                                                    Precio x 10: <del>$<?= $precio_simple_combo ?>  </del>  $<?= $precio_oferta_combo ?>
                                                <?php else: ?>
                                                    Precio x 10: $<?= $precio_simple_combo ?>
                                                <?php endif; ?>

                                            <?php } ?>

                                        </p>

                                        <p><a href="<?php echo site_url('detalle_combo/' . $combo->slug); ?>" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Comprar</a> <a href="<?php echo site_url('detalle_combo/' . $combo->slug); ?>"><i class="fa fa-external-link-square" aria-hidden="true"></i> VER DETALLE</a></p>
                                    </div>
                                    <!-- /.text -->
                                </div>

                            <?php endforeach; ?>

                        </div>

                        <!-- /.col-md-4 -->

                    </div>

                    <!-- /.products -->

                    <div class="pages">
                        <ul class="pagination">
                            <?= $links ?>
                        </ul>
                    </div>

                    <?php foreach ($banners_internos as $banner): ?>

                        <?php if ($banner->ubicacion == '4'): ?>
                            <div class="banner"> <a href="<?php echo $banner->link ?>" target="<?php echo $banner->target; ?>"> <img src="<?= base_url() . 'uploads/' . $banner->file; ?>" alt="Mundo Bijou" class="img-responsive"> </a> </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <!-- MODAL VISTA RAPIDA -->
                    <div class="modal fade" id="combo-quick-view-modal" tabindex="-1" role="dialog" aria-hidden="false">

                    </div>
                    <!-- /.modal -->

                </div>
                <!-- /.col-md-9 -->

                <!-- *** RIGHT COLUMN END *** -->

            </div>
        </div>
        <!-- /.container -->
    </div>
    <!-- /#content -->
</div>
<script>
    function ordenar(order) {

        document.location = "<?= base_url(); ?>combos/<?= $categoria ?>/<?= $subcategoria ?>/" + order;
    }

</script>
