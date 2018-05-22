<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <div class="row">
                    <div class="box text-center">
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <?php
                                $ur=explode("/", $_SERVER['REQUEST_URI']);
                                if(array_key_exists(2, $ur)==1){
                                    foreach ($categorias as $c){
                                        if($c->id==$ur[2]){
                                            echo '<h1>'.$c->nombre.'</h1>';
                                        }
                                    }
                                }
                                if(array_key_exists(3, $ur)==1){
                                    foreach ($subcategorias as $s){
                                        if($s->id==$ur[3]){
                                            echo '<p class="text-muted">'.$s->nombre.'</p>';
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <!-- *** LEFT COLUMN ***
                                _________________________________________________________ -->

                <div class="col-sm-3">

                    <!-- *** MENUS AND FILTERS ***
           _________________________________________________________ -->
                    <div class="panel panel-default sidebar-menu">
                        <div class="panel-heading">
                            <h3 class="panel-title">Categorías</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="nav nav-pills nav-stacked category-menu">

                                <?php foreach ($categorias as $c): ?>

                                    <li class="<?php echo ($categoria == $c->id)? 'active' : '' ?>"> <a href="<?= base_url(); ?>productos/<?php echo $c->id; ?>"><?php echo $c->nombre; ?> <!--<span class="badge pull-right">142</span>--></a>

                                        <?php if ($c->total > 0): ?>          
                                            <ul>
                                                <?php
                                                $count = 0;
                                                foreach ($subcategorias as $sub):

                                                    if ($c->id == $sub->categoria_padre):

                                                        echo '<li><a href="' . base_url() . 'productos/' . $c->id .'/'.$sub->id. '">' . $sub->nombre . '<!--(127)--></a> </li>';

                                                    endif;
                                                endforeach;
                                                ?>

                                            </ul>
                                        <?php endif; ?>
                                    </li>

                                <?php endforeach; ?>
                                <li><a href="<?= base_url(); ?>combos">Combos <b class="caret"></b></a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- *** MENUS AND FILTERS END *** -->

                    <div class="banner"> <a href="<?php echo site_url('index'); ?>"> <img src="<?= base_url(); ?>assets/web/img/banner.jpg" alt="Mundo Bijou" class="img-responsive"> </a> </div>
                    
                                        <div class="banner"> <a href="<?php echo site_url('index'); ?>"> <img src="<?= base_url(); ?>assets/web/img/banner3.jpg" alt="Mundo Bijou" class="img-responsive"> </a> </div>
                                        
                                                            <div class="banner"> <a href="<?php echo site_url('index'); ?>"> <img src="<?= base_url(); ?>assets/web/img/banner4.jpg" alt="Mundo Bijou" class="img-responsive"> </a> </div>
                    <!-- /.banner -->

                </div>
                <!-- /.col-md-3 -->

                <!-- *** LEFT COLUMN END *** -->

                <!-- *** RIGHT COLUMN ***
                                _________________________________________________________ -->

                <div class="col-sm-9">
                    <div class="box info-bar no-border">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 products-showing"> Mostrando <strong><?=$cantidad_productos?></strong> de <strong><?=$total_productos?></strong> productos </div>
                            <div class="col-sm-12 col-md-8  products-number-sort">
                                <div class="row">
                                    <form class="form-inline">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="products-number"> <strong>Mostrar</strong> 
                                                <a href="<?= base_url(); ?>productos/<?= $categoria ?>/<?= $subcategoria ?>/<?= (($sort == 'precio_mayorista')and ($order == 'desc'))? $sort.'_'.$order : $sort ?>/12/<?= $page ?>" class="btn btn-default btn-sm <?= ($per_page == 12) ? 'btn-primary' : '' ?>">12</a> 
                                                <a href="<?= base_url(); ?>productos/<?= $categoria ?>/<?= $subcategoria ?>/<?= (($sort == 'precio_mayorista')and ($order == 'desc'))? $sort.'_'.$order : $sort ?>/24/<?= $page ?>" class="btn btn-default btn-sm <?= ($per_page == 24) ? 'btn-primary' : '' ?>">24</a> 
                                                <a href="<?= base_url(); ?>productos/<?= $categoria ?>/<?= $subcategoria ?>/<?= (($sort == 'precio_mayorista')and ($order == 'desc'))? $sort.'_'.$order : $sort ?>/99999999/<?= $page ?>" class="btn btn-default btn-sm <?= ($per_page == 99999999) ? 'btn-primary' : '' ?>">Todos</a> 
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="products-sort-by"> <strong>Ordenar</strong>
                                                <select name="sort-by" class="form-control" onchange="ordenar(this.value)">
                                                    <option value="precio_mayorista" <?= ($sort == 'precio_mayorista') ? 'selected' : '' ?>>Mayor Precio</option>
                                                    <option value="precio_mayorista_desc" <?= (($sort == 'precio_mayorista')and ($order == 'desc'))? 'selected' : '' ?>>Menor Precio</option>
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
                                        <div class="image" style="height: 180px;"> <a href="<?php echo site_url('detalle/' . $producto->slug); ?>"> <img src="<?= base_url() . 'uploads/' . $producto->file1; ?>" alt="<?= $producto->titulo ?> art.<?= $producto->articulo ?>" class="img-responsive image1"> </a>
                                            <div class="quick-view-button"> <a href="#" my-link="<?php echo site_url('web/modal_detalle/' . $producto->slug); ?>" data-toggle="modal" data-target="#product-quick-view-modal" class="btn btn-default btn-sm">Vista rápida</a> </div>
                                        </div>
                                        <!-- /.image -->
                                        <div class="text">
                                            <h3><a href="<?php echo site_url('detalle/' . $producto->slug); ?>"><?= $producto->titulo ?> art.<?= $producto->articulo ?></a></h3>
                                            <p class="description"><?= $producto->descripcion_corta ?></p>
                                            <p class="price">
                                                $<?PHP // ($tipo_cliente == 2) ?  number_format($producto->precio_revendedor*$valor_dolar, 2, '.', '') : number_format($producto->precio_mayorista*$valor_dolar*$valor_dolar, 2, '.', '')  ?>
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
                                                Precio Unitario: <del>$<?= $precio_simple ?>  </del>  $<?= $precio_oferta ?>   

                                                <?php
                                            /*else:

                                                $precio_simple = ($tipo_cliente == 2) ?
                                                        number_format($producto->precio_revendedor * $valor_dolar, 2, '.', '') :
                                                        number_format($producto->precio_mayorista * $valor_dolar, 2, '.', '')
                                                ?>
                                                Precio Unitario: $<?= $precio_simple */?>

                                            <?php /*endif;*/ ?>
                                            
                                            </p>
                                            <p class="text-center">
                                                <a href="<?php echo site_url('detalle/' . $producto->slug); ?>" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Comprar</a>
                                                <br><br>

                                                <a href="<?php echo site_url('detalle/' . $producto->slug); ?>"><i class="fa fa-external-link-square" aria-hidden="true"></i> VER DETALLE</a></p>
                                        </div>
                                        <!-- /.text -->
                                    </div>

                                    <!-- /.product -->
                                </div>

                            <?php endforeach; ?>

                        </div>

                        <!--</div>-->

                        <!-- /.products -->

                        <div class="row">
                            <div class="col-md-12 banner"> <a href="#"> <img src="<?= base_url(); ?>assets/web/img/banner2.jpg" alt="" class="img-responsive"> </a> </div>
                        </div>
                        <div class="pages">
                            <ul class="pagination">

                                <?= $links ?>

                            </ul>
                        </div>

                        <!-- MODAL VISTA RAPIDA -->
                        <div class="modal fade" id="product-quick-view-modal" tabindex="-1" role="dialog" aria-hidden="false"></div>
                        <!-- /.modal -->

                    </div>
                    <!-- /.col-md-9 -->

                    <!-- *** RIGHT COLUMN END *** -->

                </div>
            </div>
            <!-- /.container -->
        </div>
        <!-- /#content -->
        <script>
            function ordenar(order) {

                document.location = "<?= base_url(); ?>productos/<?= $categoria ?>/<?= $subcategoria ?>/" + order;
            }

        </script>