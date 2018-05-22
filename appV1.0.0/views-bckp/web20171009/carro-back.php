<?php $cart_check = $this->cart->contents(); ?> 
<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url('index'); ?>">Home</a> </li>
                    <li>Carro de compras</li>
                </ul>
                <div class="box text-center">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <h1>Carro de Compras</h1>

                            <p class="text-muted">Tienes <?= count($cart_check) ?> item(s) en tu carro de compras</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 clearfix" id="basket">

                <?php if (!empty($cart_check)): ?>

                    <div class="box">
                        <?php
                        // Create form and send all values in "shopping/update_cart" function.
                        echo form_open('web/cart_update', array('id' => 'form_carro'));
                        ?>
                        <div class = "table-responsive">
                            <table class = "table">
                                <thead>
                                    <tr>
                                        <th colspan = "2">Producto</th>
                                        <th>Cantidad</th>
                                        <th>$ Unitario</th>
                                        <th></th>
                                        <th style = "text-align: center;">Subtotal</th>
                                        <th>&nbsp;
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $grand_total = 0;

                                    $i = 1;

                                    foreach ($cart_check as $item):

                                        echo form_hidden('cart[' . $item['rowid'] . '][id]', $item['id']);
                                        echo form_hidden('cart[' . $item['rowid'] . '][rowid]', $item['rowid']);
                                        echo form_hidden('cart[' . $item['rowid'] . '][name]', $item['name']);
                                        echo form_hidden('cart[' . $item['rowid'] . '][price]', $item['price']);
                                        echo form_hidden('cart[' . $item['rowid'] . '][qty]', $item['qty']);

                                        $link = ($this->cart->product_options($item['rowid'])['tipo_producto'] == 'producto') ? 'detalle' : 'detalle_combo';
                                        ?>

                                        <tr>

                                            <td><a href="<?php echo site_url($link . '/' . $this->cart->product_options($item['rowid'])['slug']); ?>"> <img src="<?= base_url(); ?>uploads/<?= $this->cart->product_options($item['rowid'])['imagen'] ?>" alt="White Blouse Armani"> </a></td>
                                            <td><a href="<?php echo site_url($link . '/' . $this->cart->product_options($item['rowid'])['slug']); ?>" class="black"><?php echo $item['name']; ?></a></td>
                                            <td>
                                                <?php echo form_input('cart[' . $item['rowid'] . '][qty]', $item['qty'], 'maxlength="4" size="1" style="text-align: right;float:left;" class="form-control" onchange="$(\'#form_carro\').submit()"'); ?>
                                                <?php /*echo ($this->cart->product_options($item['rowid'])['cantidad_a_descontar'] == 10) ? '<span style="float:left">(x10)</span>' : ''*/ ?>
                                            </td>
                                            <td>$ <?php echo number_format($item['price'], 2); ?></td>
                                            <td><?php $grand_total = $grand_total + $item['subtotal']; ?></td>
                                            <td align="center">$ <?php echo number_format($item['price'] * $item['qty'], 2); ?></td>
                                            <td>
                                                <?php
                                                $path = '<i class="fa fa-trash-o"></i>';
                                                echo anchor('web/cart_remove/' . $item['rowid'], $path);
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php
                                    $d = (!empty($_SESSION['descuento_aplicado']['porcentaje'])) ? $_SESSION['descuento_aplicado']['porcentaje'] : 0;

                                    $descuento = ($grand_total * $d) / 100;

                                    $total = $grand_total - $descuento;
                                    ?>    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5">Descuento</th>
                                        <th style="text-align: center;">$<?= number_format($descuento, 2, '.', '') ?></th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <th colspan="5">Total</th>
                                        <th style="text-align: center;">$<?php echo number_format($total, 2, '.', ''); ?></th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </tfoot>
                                <tfoot>
                                    <tr>
                                        <th colspan="5"><h4>Cupón de descuento<br>
                                                <small>Si posee un cupón de descuento, ingrese el número aquí.</small></h4></th>

                                        <th colspan="2" style="text-align: center;">
                                            <div class="input-group" style="max-width: 140px; margin-top: 12px;">
                                                <input type="text" class="form-control" name="descuento">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" onclick="$('#form_carro').submit();"><i class="fa fa-gift"></i></button>
                                                </span> </div>
                                            <!-- /input-group -->
                                        </th>

                                    </tr>
                                </tfoot>
                            </table>
                            <div class="alert alert-dismissible alert-info" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Pedido y stock a verificar en base a disponibilidad de productos </div>
                        </div>
                        <!-- /.table-responsive -->

                        <div class="box-footer">
                            <div class="pull-left"> <a href="<?php echo site_url('productos'); ?>" class="btn btn-default"><i class="fa fa-chevron-left"></i> Seguir comprando</a> </div>
                            <div class="pull-right">
                                <button class="btn btn-default" type="submit"><i class="fa fa-refresh"></i> Actualizar carro</button>
                                <?php //submit button.    ?>

                                <?php echo form_close(); ?>
                                <a  href="<?php echo site_url('checkout1'); ?>" class="btn btn-primary invert">Confirmar compra<i class="fa fa-chevron-right"></i> </a> </div>
                        </div>

                    </div>

                <?php endif; ?>
                <!-- /.box --> 

            </div>
            <!-- /.col-md-9 --> 

            <!-- /.col-md-3 --> 

        </div>
        <!-- /.container --> 
    </div>
    <!-- /#content --> 
</div>
