
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="row quick-view product-main">
                <div class="col-sm-6">
                    <div class="quick-view-main-image"> <img id="zoom_01" src="<?= base_url(); ?>uploads/<?= $combo->file1 ?>" alt="" class="img-responsive" data-zoom-image="<?= base_url(); ?>assets/web/img/productos/anillos/6533.jpg"> </div>

                    <?php if ($combo->oferta == 'SI'): ?>

                        <div class="ribbon sale">
                            <div class="theribbon">OFERTA</div>
                            <div class="ribbon-background"></div>
                        </div>
                        <!-- /.ribbon -->

                    <?php endif; ?>

                    <?php if ($combo->nuevo == 'SI'): ?>    
                        <div class="ribbon new">
                            <div class="theribbon">NUEVO</div>
                            <div class="ribbon-background"></div>
                        </div>
                    <?php endif; ?>


                    <!-- /.ribbon -->
                    <div class="row" id="thumbs">
                        <?php if (!empty($combo->file1)): ?> <div class="col-xs-4"> <a href="<?= base_url(); ?>uploads/<?= $combo->file1 ?>" class="thumb"> <img src="<?= base_url(); ?>uploads/<?= $combo->file1 ?>" alt="" class="img-responsive"> </a> </div><?php endif; ?>
                        <?php if (!empty($combo->file2)): ?> <div class="col-xs-4"> <a href="<?= base_url(); ?>uploads/<?= $combo->file2 ?>" class="thumb"> <img src="<?= base_url(); ?>uploads/<?= $combo->file2 ?>" alt="" class="img-responsive"> </a> </div><?php endif; ?>
                        <?php if (!empty($combo->file3)): ?> <div class="col-xs-4"> <a href="<?= base_url(); ?>uploads/<?= $combo->file3 ?>" class="thumb"> <img src="<?= base_url(); ?>uploads/<?= $combo->file3 ?>" alt="" class="img-responsive"> </a> </div><?php endif; ?>
                        <?php if (!empty($combo->file4)): ?> <div class="col-xs-4"> <a href="<?= base_url(); ?>uploads/<?= $combo->file4 ?>" class="thumb"> <img src="<?= base_url(); ?>uploads/<?= $combo->file3 ?>" alt="" class="img-responsive"> </a> </div><?php endif; ?>
                        <?php if (!empty($combo->file5)): ?> <div class="col-xs-4"> <a href="<?= base_url(); ?>uploads/<?= $combo->file5 ?>" class="thumb"> <img src="<?= base_url(); ?>uploads/<?= $combo->file3 ?>" alt="" class="img-responsive"> </a> </div><?php endif; ?>

                        <?php if (!empty($combo->video)): ?>    
                            <div class="col-xs-12 text-center">
                                <p class="text-center"><a href="#" data-toggle="modal" data-target="#ModalVideo" class="btn btn-primary" style="border-color: #ac9632; margin: 15px auto; width: 140px;"><i class="fa fa-youtube-play"></i>video</a></p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
                <div class="col-sm-6">

                    <h2><?= $combo->titulo . ' ' . $combo->articulo ?></h2>

                    <p><strong><?= $combo->descripcion_corta ?></strong></p>

                    <div class="box" >
                        <?php
                        $id = $combo->id;

                        $name = $combo->titulo . ' ' . $combo->articulo;

                        $description = $combo->descripcion_corta;

                        $price = $combo->precio_mayorista;

                        $imagen = $combo->file1;

                        $attributes = array('id' => 'form_producto', 'onsubmit' => 'return false');

                        // Create form and send values in 'shopping/add' function.
                        echo form_open('web/cart_add', $attributes);

                        echo form_hidden('id', $id);

                        echo form_hidden('name', $name);

                        echo form_hidden('tipo_producto', 'combo');

                        echo form_hidden('price', $price * $valor_dolar);

                        echo form_hidden('quantity', 1);

                        echo form_hidden('imagen', $imagen);

                        echo form_hidden('slug', $combo->slug);

                        echo form_hidden('cantidad_a_descontar', 1);

                        echo form_hidden('size', 0);

                        echo form_hidden('color_id', 0);

                        echo form_hidden('medida_id', 0);
                        ?>
                        <p class="price" >

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

                        <p class="price" >

                            <?php
                            $precio_oferta_combo = 0;

                            if ($tipo_cliente == 2) :
                                $precio_simple_combo = number_format($combo->precio_por_diez_revendedor * $valor_dolar, 2, '.', '');
                                $precio_oferta_combo = number_format($combo->precio_por_diez_oferta_revendedor * $valor_dolar, 2, '.', '');
                            else:
                                $precio_simple_combo = number_format($combo->precio_por_diez_mayorista * $valor_dolar, 2, '.', '');
                                $precio_oferta_combo = number_format($combo->precio_por_diez_oferta_mayorista * $valor_dolar, 2, '.', '');
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

                        <hr>

                        <?php foreach ($composicion as $cpm): ?>

                            <p class="description"><img style="width: 50px" src="<?= base_url() . 'uploads/' . $cpm->imagen; ?>" alt=""> <?= $cpm->cantidad; ?> <?= $cpm->nombre; ?> - <a href="<?php echo site_url('detalle_combo/' . $combo->slug); ?>">art. <?= $cpm->numero_articulo; ?></a></p>

                        <?php endforeach; ?>

                        <br style="clear: both;">
                        <div class="form_error" id="errores_carro"></div>
                        <div class="presentation">
                            <h3>Presentación</h3>
                            <p><?= $combo->descripcion ?></p>
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

                            <a href="<?php echo site_url('detalle_combo/' . $combo->slug); ?>" class="external facebook"
                               onclick="
                                       window.open(
                                               'https://www.facebook.com/sharer/sharer.php?u=' + (location.href),
                                               'facebook-share-dialog',
                                               'width=626,height=436');
                                       return false;">
                                <i class="fa fa-facebook"></i>
                            </a>

                    <!--<a href="#" class="external facebook" data-animate-hover="pulse"><i class="fa fa-facebook"></i></a>-->

                            <a class="external gplus" href="https://plus.google.com/share?url=<?php echo site_url('detalle_combo/' . $combo->slug); ?>" onclick="javascript:window.open(encodeURI(this.href),
                                            '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
                                    return false;"><i class="fa fa-google-plus"></i></a>

<!--<a href="#" class="external gplus" data-animate-hover="pulse"><i class="fa fa-google-plus"></i></a> -->
<!--<a href="#" class="external twitter" data-animate-hover="pulse"><i class="fa fa-twitter"></i></a> -->

                            <a class="twitter-share-button external twitter"
                               href="https://twitter.com/intent/tweet?url=<?php echo site_url('detalle_combo/' . $combo->slug); ?>"
                               data-size="large"><i class="fa fa-twitter"></i>
                            </a>

                            <a href="mailto:?subject=Quiero que veas este sitio web&amp;body=<?php echo site_url('detalle_combo/' . $combo->slug); ?>."
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
                <button type="button" class="close" id="btnCloseVideo" aria-hidden="true" " onclick="$('#ModalVideo').modal('hide')">×</button>
                <h4><?= $combo->titulo . ' ' . $combo->articulo ?></h4>
            </div>
            <div class="modal-body">
                <iframe class='YVideo' src="https://www.youtube.com/embed/<?= $combo->video ?>?rel=0&amp;showinfo=0" frameborder="0"  style="width: 100%;height: 320px;"></iframe>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

</div>
