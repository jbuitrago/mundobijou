<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <div class="row">
                    <div class="box text-center">
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <?php
                                $ur = explode("/", $_SERVER['REQUEST_URI']);

                                $categoria_actual = 0;

                                $subcategoria_actual = 0;

                                foreach ($categorias as $c) {
                                    if ($c->id == $categoria) {
                                        $categoria_actual = str_replace(' ', '-', $c->nombre . '-' . $c->id);
                                        echo '<h1>' . $c->nombre . '</h1>';
                                    }
                                }

                                foreach ($subcategorias as $s) {
                                    if ($s->id == $subcategoria) {
                                        $subcategoria_actual = str_replace(' ', '-', $s->nombre . '-' . $s->id);
                                        echo '<p class="text-muted">' . $s->nombre . '</p>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-sm-9 col-sm-push-3">
                    <div class="box info-bar no-border">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 products-showing"> Mostrando <strong><?= $cantidad_productos ?></strong> de <strong><?= $total_productos ?></strong> productos </div>
                            <div class="col-sm-12 col-md-8  products-number-sort">
                                <div class="row">
                                    <form class="form-inline">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="products-number"> <strong>Mostrar</strong>
                                                <a href="<?= base_url(); ?><?= str_replace(' ', '-', $categoria_actual) ?>/<?= str_replace(' ', '-', $subcategoria_actual) ?>/<?= (($sort == 'precio_mayorista')and ( $order == 'desc')) ? $sort . '_' . $order : $sort ?>/nofilter/12/<?= 1 ?>" class="btn btn-default btn-sm <?= ($per_page == 12) ? 'btn-primary' : '' ?>">12</a>
                                                <a href="<?= base_url(); ?><?= str_replace(' ', '-', $categoria_actual) ?>/<?= str_replace(' ', '-', $subcategoria_actual) ?>/<?= (($sort == 'precio_mayorista')and ( $order == 'desc')) ? $sort . '_' . $order : $sort ?>/nofilter/24/<?= 1 ?>" class="btn btn-default btn-sm <?= ($per_page == 24) ? 'btn-primary' : '' ?>">24</a>
                                                <a href="<?= base_url(); ?><?= str_replace(' ', '-', $categoria_actual) ?>/<?= str_replace(' ', '-', $subcategoria_actual) ?>/<?= (($sort == 'precio_mayorista')and ( $order == 'desc')) ? $sort . '_' . $order : $sort ?>/nofilter/99999999/<?= 1 ?>" class="btn btn-default btn-sm <?= ($per_page == 99999999) ? 'btn-primary' : '' ?>">Todos</a>
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

                    <?php $indice = 0; ?>

                    <!-- products -->
                    <div class="row products">

                        <?php foreach ($productos as $producto): ?>

                            <?php
                            $id = $producto->id;

                            $name = $producto->titulo . ' ' . $producto->articulo;

                            $description = $producto->descripcion_corta;

                            $price = $producto->precio_mayorista;

                            $imagen = $producto->file1;
                            ?>

                            <?php if ($indice == 0): ?><div class="row products"><?php endif; ?>

                                <?php if ($indice == 4): ?></div><div class="row products"><?php endif; ?>

                                <div class="col-md-3 col-sm-4">

                                    <?php $indice = $indice + 1; ?>

                                    <div class="product" id="producto_<?= $producto->id ?>">
                                        <div class="image" style="height: 180px;">
                                            <a href="<?= str_replace(' ', '-', site_url(strtolower($producto->categoria) . '/' . str_replace(' ', '-', $producto->titulo) . '-' . $producto->articulo . '-' . $producto->id)) ?>">
                                                <img src="<?= base_url() . 'uploads/' . $producto->file1; ?>" alt="<?= $producto->titulo ?> art.<?= $producto->articulo ?>" class="img-responsive image1" alt="<?=$producto->alt_file1?>" title="<?=$producto->title_file1?>">
                                            </a>
                                            <div class="quick-view-button">
                                                <a href="#" my-link="<?= str_replace(' ', '-', site_url('web/modal_detalle/' . strtolower($producto->categoria) . '/' . str_replace(' ', '-', $producto->titulo) . '-' . $producto->articulo . '-' . $producto->id)) ?>" data-toggle="modal" data-target="#product-quick-view-modal" class="btn btn-default btn-sm">
                                                    Vista rápida
                                                </a>
                                            </div>
                                        </div>
                                        <!-- /.image -->
                                        <div class="text">
                                            <h2><a href="<?= str_replace(' ', '-', site_url(strtolower($producto->categoria) . '/' . str_replace(' ', '-', $producto->titulo) . '-' . $producto->articulo . '-' . $producto->id)) ?>"><?= $producto->titulo ?> art.<?= $producto->articulo ?></a></h2>
                                            <h3 class="description"><?= $producto->descripcion_corta ?></h3>
                                            <p class="price">

                                                <?php
                                                //if ($producto->oferta == 'SI'):
                                                if ($tipo_cliente == 2) :
                                                    $precio_simple = number_format($producto->precio_revendedor * $valor_dolar, 2, '.', '');
                                                    $precio_oferta = number_format($producto->precio_oferta_revendedor * $valor_dolar, 2, '.', '');
                                                else:
                                                    $precio_simple = number_format($producto->precio_mayorista * $valor_dolar, 2, '.', '');
                                                    $precio_oferta = number_format($producto->precio_oferta_mayorista * $valor_dolar, 2, '.', '');
                                                endif;
                                                ?>

                                                <?php if ($precio_oferta > 0): ?>
                                                    Precio Unitario: <del>$<?= $precio_simple ?></del> $<?= $precio_oferta ?>
                                                <?php else: ?>
                                                    Precio Unitario: $<?= $precio_simple ?>
                                                <?php endif; ?>

                                                <?php /* else:

                                                  $precio_simple = ($tipo_cliente == 2) ?
                                                  number_format($producto->precio_revendedor * $valor_dolar, 2, '.', '') :
                                                  number_format($producto->precio_mayorista * $valor_dolar, 2, '.', '')
                                                  ?>
                                                  Precio Unitario: $<?= $precio_simple */ ?>

                                                <?php /* endif; */ ?>

                                            </p>
                                            <p class="text-center">
                                                <a href="<?= str_replace(' ', '-', site_url(strtolower(str_replace(' ', '-', $producto->categoria)) . '/' . str_replace(' ', '-', $producto->titulo) . '-' . str_replace(' ', '-', $producto->articulo) . '-' . $producto->id)) ?>" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Comprar</a>
                                                <br><br>

                                                <a href="<?= str_replace(' ', '-', site_url(strtolower(str_replace(' ', '-', $producto->categoria)) . '/' . str_replace(' ', '-', $producto->titulo) . '-' . str_replace(' ', '-', $producto->articulo) . '-' . $producto->id)) ?>"><i class="fa fa-external-link-square" aria-hidden="true"></i> VER DETALLE</a></p>
                                        </div>
                                        <!-- /.text -->
                                    </div>

                                    <!-- /.product -->
                                </div>

                            <?php endforeach; ?>

                        </div>

                        <!--</div>-->

                        <!-- /.products -->

                        <div class="pages">
                            <ul class="pagination">

                                <?= $links ?>

                            </ul>
                        </div>

                        <?php foreach ($banners_internos as $banner):?>
                          <?php if($banner->ubicacion == '4'):?>
                            <div class="banner"> <a href="<?php echo $banner->link ?>" target="<?php echo $banner->target; ?>"> <img src="<?= base_url() . 'uploads/' . $banner->file; ?>" alt="Mundo Bijou" class="img-responsive"> </a> </div>
                          <?php endif; ?>
                        <?php endforeach; ?>

                        <!-- MODAL VISTA RAPIDA -->
                        <div class="modal fade" id="product-quick-view-modal" tabindex="-1" role="dialog" aria-hidden="false"></div>
                        <!-- /.modal -->

                    </div>
                    <!-- /.col-md-9 -->

                    <!-- *** RIGHT COLUMN END *** -->

                </div>
                <!-- *** LEFT COLUMN ***
                                _________________________________________________________ -->

                <div class="col-sm-3 col-sm-pull-9">

                    <!-- *** MENUS AND FILTERS ***
           _________________________________________________________ -->
                    <div class="panel panel-default sidebar-menu">
                        <div class="panel-heading">
                            <h3 class="panel-title">Categorías</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="nav nav-pills nav-stacked category-menu">

                                <?php foreach ($categorias as $c): ?>

                                    <li class="<?php echo ($categoria == $c->id) ? 'active' : '' ?> parent">

                                        <?php if ($c->total > 0): ?>

                                            <a href="#"><?php echo $c->nombre; ?></a>

                                            <ul class="child">

                                                <?php echo '<li><a href="' . base_url() . str_replace(' ', '-', $c->nombre) . '-' . $c->id . '/">Todos </a></li>'; ?>

                                                <?php
                                                $count = 0;

                                                foreach ($subcategorias as $sub):

                                                    if ($c->id == $sub->categoria_padre):

                                                        echo '<li><a href="' . site_url(str_replace(' ', '-', $c->nombre) . '-' . $c->id . '/' . str_replace(' ', '-', $sub->nombre) . '-' . $sub->id) . '">' . $sub->nombre . '<!--(127)--></a> </li>';

                                                    endif;
                                                endforeach;
                                                ?>

                                            </ul>
                                        <?php else: ?>
                                            <a href="<?php echo site_url(str_replace(' ', '-', $c->nombre) . '-' . $c->id); ?>"><?php echo $c->nombre; ?></a>
                                        <?php endif; ?>
                                    </li>

                                <?php endforeach; ?>
                                <?php if ($mostrar_categoria_combos > 0): ?><li><a href="<?= base_url(); ?>combos">Combos</a></li><?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- *** MENUS AND FILTERS END *** -->
                    <?php foreach ($banners_internos as $banner):?>

                      <?php if($banner->ubicacion != '4'):?>
                        <div class="banner"> <a href="<?php echo $banner->link ?>" target="<?php echo $banner->target; ?>"> <img src="<?= base_url() . 'uploads/' . $banner->file; ?>" alt="Mundo Bijou" class="img-responsive"> </a> </div>
                      <?php endif; ?>
                    <?php endforeach; ?>

                    <!-- /.banner -->

                </div>
                <!-- /.col-md-3 -->

                <!-- *** LEFT COLUMN END *** -->

                <!-- *** RIGHT COLUMN ***
                                _________________________________________________________ -->

            </div>
            <!-- /.container -->
        </div>
        <!-- /#content -->
        <script>
            function ordenar(order) {

                <?php if($my_page == 0):?>

                document.location = "<?= base_url(); ?>productos/<?= $categoria_actual ?>/<?= $subcategoria_actual ?>/" + order+"/<?=$filtro?>";

                <?php else:?>
                    document.location = "<?= base_url(); ?><?= $categoria_actual ?>/<?= $subcategoria_actual ?>/" + order+"/<?=$filtro?>";
                <?php endif; ?>
            }

        </script>
