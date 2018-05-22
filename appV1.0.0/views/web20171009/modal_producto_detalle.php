
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="row quick-view product-main">
                <div class="col-sm-6">
                    <div class="quick-view-main-image"> <img id="zoom_01" src="<?= base_url(); ?>uploads/<?= $producto->file1 ?>" alt="" class="img-responsive" data-zoom-image="<?= base_url(); ?>assets/web/img/productos/anillos/6533.jpg"> </div>

                    <?php if ($producto->oferta == 'SI'): ?>

                        <div class="ribbon sale">
                            <div class="theribbon">OFERTA</div>
                            <div class="ribbon-background"></div>
                        </div>
                        <!-- /.ribbon -->

                    <?php endif; ?>

                    <?php if ($producto->nuevo == 'SI'): ?>    
                        <div class="ribbon new">
                            <div class="theribbon">NUEVO</div>
                            <div class="ribbon-background"></div>
                        </div>
                    <?php endif; ?>
                    <!-- /.ribbon -->
                    <div class="row" id="thumbs">
                        <?php if (!empty($producto->file1)): ?> <div class="col-xs-4"> <a href="<?= base_url(); ?>uploads/<?= $producto->file1 ?>" class="thumb"> <img src="<?= base_url(); ?>uploads/<?= $producto->file1 ?>" alt="" class="img-responsive"> </a> </div><?php endif; ?>
                        <?php if (!empty($producto->file2)): ?> <div class="col-xs-4"> <a href="<?= base_url(); ?>uploads/<?= $producto->file2 ?>" class="thumb"> <img src="<?= base_url(); ?>uploads/<?= $producto->file2 ?>" alt="" class="img-responsive"> </a> </div><?php endif; ?>
                        <?php if (!empty($producto->file3)): ?> <div class="col-xs-4"> <a href="<?= base_url(); ?>uploads/<?= $producto->file3 ?>" class="thumb"> <img src="<?= base_url(); ?>uploads/<?= $producto->file3 ?>" alt="" class="img-responsive"> </a> </div><?php endif; ?>

                        <?php if (!empty($producto->video)): ?>    
                            <div class="col-xs-12 text-center">
                                <p class="text-center"><a href="#" data-toggle="modal" data-target="#ModalVideo" class="btn btn-primary" style="border-color: #ac9632; margin: 15px auto; width: 140px;"><i class="fa fa-youtube-play"></i>video</a></p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
                <div class="col-sm-6">

                    <h2><?= $producto->titulo . ' ' . $producto->articulo ?></h2>

                    <p><strong><?= $producto->descripcion_corta ?></strong></p>

                    <div class="box" >
                        <?php
                        $id = $producto->id;

                        $name = $producto->titulo . ' ' . $producto->articulo;

                        $description = $producto->descripcion_corta;

                        $price = $producto->precio_mayorista;

                        $imagen = $producto->file1;

                        $attributes = array('id' => 'form_producto', 'onsubmit' => 'return false');

                        // Create form and send values in 'shopping/add' function.
                        echo form_open('web/cart_add', $attributes);

                        echo form_hidden('id', $id);

                        echo form_hidden('name', $name);

                        echo form_hidden('tipo_producto', 'producto');

                        echo form_hidden('price', $price * $valor_dolar);

                        echo form_hidden('quantity', 1);

                        echo form_hidden('imagen', $imagen);

                        echo form_hidden('slug', $producto->slug);

                        echo form_hidden('cantidad_a_descontar', 1);
                        ?>

                        <p class="price" >

                            <?php
                            $precio_oferta = 0;

                            if ($tipo_cliente == 2) :
                                $precio_simple = number_format($productos->precio_revendedor * $valor_dolar, 2, '.', '');
                                $precio_oferta = number_format($producto->precio_oferta_revendedor * $valor_dolar, 2, '.', '');
                            else:
                                $precio_simple = number_format($producto->precio_mayorista * $valor_dolar, 2, '.', '');
                                $precio_oferta = number_format($producto->precio_oferta_mayorista * $valor_dolar, 2, '.', '');
                            endif;
                            ?>

                            <?php if ($precio_oferta > 0): ?>
                                Precio Unitario: <small><del>$<?= $precio_simple ?></del></small> $<?= $precio_oferta ?>
                            <?php else: ?>
                                Precio Unitario: $<?= $precio_simple ?>
                            <?php endif; ?>

                        <div>
                            <input required="" name="quantity_a" type="number" value="1" class="form-control" style="width: 70px; display: inline-block; margin-right: 10px;">

                            <button type="submit" class="btn btn-primary" onclick="agregar_al_carrito('<?= number_format(($precio_oferta == 0) ? $precio_simple : $precio_oferta, 2, '.', '') ?>', 1)"><i class="fa fa-shopping-cart"></i> Comprar</button>
                           <!-- <a href="carro.html" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Comprar</a>-->
                        </div>

                        </p>

                        <?php if (!empty($producto->precio_por_diez_revendedor) && !empty($producto->precio_por_diez_mayorista)): ?>

                            <p class="price">

                                <?php
                                $precio_oferta_combo = 0;

                                if ($tipo_cliente == 2) :
                                    $precio_simple_combo = number_format($producto->precio_por_diez_revendedor * $valor_dolar, 2, '.', '');
                                    $precio_oferta_combo = number_format($producto->precio_por_diez_oferta_revendedor * $valor_dolar, 2, '.', '');
                                else:
                                    $precio_simple_combo = number_format($producto->precio_por_diez_mayorista * $valor_dolar, 2, '.', '');
                                    $precio_oferta_combo = number_format($producto->precio_por_diez_oferta_mayorista * $valor_dolar, 2, '.', '');
                                endif;
                                ?>
                                <?php if ($precio_oferta_combo > 0): ?>
                                    <small>Precio x 10: <del>$<?= $precio_simple_combo ?>  </del></small>  $<?= $precio_oferta_combo ?>   
                                <?php else: ?>
                                    Precio x 10: $<?= $precio_simple_combo ?>
                                <?php endif; ?> 

                            <div>
                                <input name="quantity_b" type="number" value="1" class="form-control" style="width: 70px; display: inline-block; margin-right: 10px;">
                                <button type="submit" class="btn btn-primary" onclick="agregar_al_carrito('<?= number_format(($precio_oferta_combo == 0) ? $precio_simple_combo : $precio_oferta_combo, 2, '.', '') ?>', 10)"><i class="fa fa-shopping-cart"></i> Comprar combo x 10</button>
                            </div>
                            </p>

                        <?php endif; ?>

                        <hr>

                        <?php if (count($talles) > 0): ?>

                            <div class="sizes">
                                <h3>Talles</h3>

                                <?php foreach ($talles as $talle): ?>

                                    <label for="size_s"> <a href="#"><?= $talle->nombre; ?></a>
                                        <input type="radio" id="size_s" name="size" value="<?= $talle->talle_id ?>" class="size-input">
                                    </label>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                        <?php if (count($colores) > 0): ?>

                            <div class="colours">
                                <h3>Colores</h3>

                                <?php foreach ($colores as $color): ?>

                                    <style>
                                        .radio-<?= $color->color_id ?>.radio label::before {
                                            border: 1px solid <?= $color->codigo ?>;
                                            background: <?= $color->codigo ?> !important;
                                        }
                                        .radio-<?= $color->color_id ?> input[type="radio"] + label::after {
                                            background: rgba(255,7,11,1);
                                        }
                                        .radio-<?= $color->color_id ?> input[type="radio"]:checked + label::before {
                                            background-color: rgba(211,211,211,1);
                                            border-color: rgba(211,211,211,1);
                                        }
                                        .radio-<?= $color->color_id ?> input[type="radio"]:checked + label::after {
                                            background-color: rgba(211,211,211,1);
                                            border-color: rgba(211,211,211,1);
                                        }
                                    </style>

                                    <div class="radio radio-<?= $color->color_id ?> radio-inline">
                                        <input type="radio" id="inlineRadio<?= $color->color_id ?>" value="<?= $color->color_id ?>" name="color_id">
                                        <label for="inlineRadio<?= $color->color_id ?>"></label>
                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                        <?php if (count($medidas) > 0): ?>

                            <div class="medidas">

                                <h3>Medidas</h3>

                                <select name="medida_id">

                                    <?php foreach ($medidas as $medida): ?>

                                        <option value="<?= $medida->medida_id ?>"><?= $medida->nombre; ?></option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        <?php endif; ?>

                        <br style="clear: both;">

                        <div class="presentation">
                            <h3>Presentación</h3>
                            <p><?= $producto->descripcion ?></p>
                        </div>

                        <!-- <hr>
                         <p class="text-center"> <a href="carro.html" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Comprar</a> <a href="#" class="btn btn-default disabled"><i class="fa fa-shopping-cart"></i> Artículo sin Stock</a> </p>-->
                        <?php echo form_close(); ?>
                        <div class="form_error" id="errores_carro"></div>
                    </div>
                    <!-- /.box -->

                    <div class="quick-view-social">
                        <h4>Compartir</h4>
                        <p> 

                            <a href="<?php echo site_url('detalle/' . $producto->slug); ?>" class="external facebook"
                               onclick="
                                       window.open(
                                               'https://www.facebook.com/sharer/sharer.php?u=' + (location.href),
                                               'facebook-share-dialog',
                                               'width=626,height=436');
                                       return false;">
                                <i class="fa fa-facebook"></i>
                            </a>

                    <!--<a href="#" class="external facebook" data-animate-hover="pulse"><i class="fa fa-facebook"></i></a>-->

                            <a class="external gplus" href="https://plus.google.com/share?url=<?php echo site_url('detalle/' . $producto->slug); ?>" onclick="javascript:window.open(encodeURI(this.href),
                                            '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
                                    return false;"><i class="fa fa-google-plus"></i></a>

<!--<a href="#" class="external gplus" data-animate-hover="pulse"><i class="fa fa-google-plus"></i></a> -->
<!--<a href="#" class="external twitter" data-animate-hover="pulse"><i class="fa fa-twitter"></i></a> -->

                            <a class="twitter-share-button external twitter"
                               href="https://twitter.com/intent/tweet?url=<?php echo site_url('detalle/' . $producto->slug); ?>"
                               data-size="large"><i class="fa fa-twitter"></i>
                            </a>

                            <a href="mailto:?subject=Quiero que veas este sitio web&amp;body=<?php echo site_url('detalle/' . $producto->slug); ?>."
                               title="Compartir por Email"  class="email" data-animate-hover="pulse">
                                <i class="fa fa-envelope"></i>
                            </a>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.modal-dialog-->

<!--Modal video-->
<div class="modal" id="ModalVideo" tabindex="-1" role="dialog" aria-labelledby="video-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnCloseVideo" aria-hidden="true" onclick="$('#ModalVideo').modal('hide')">×</button>
                <h4><?= $producto->titulo . ' ' . $producto->articulo ?></h4>

            </div>
            <div class="modal-body">
                <iframe  src="https://www.youtube.com/embed/<?= $producto->video ?>?rel=0&amp;showinfo=0" frameborder="0"  style="width: 100%;height: 320px;"></iframe>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

</div>
