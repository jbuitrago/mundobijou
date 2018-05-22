<?php $cart_check = $this->cart->contents(); ?>

<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url('index'); ?>">Home</a> </li>
                    <li>Checkout - Revisión de Compra</li>
                </ul>
                <div class="box text-center">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <h1>Checkout - Revisión de compra</h1>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_open('checkout4', array('id' => 'form_carro')); ?>
            <div class="col-md-9 clearfix" id="checkout">
                <div class="box">

                    <ul class="nav nav-pills nav-justified">
                        <li><a href="<?php echo site_url('checkout1'); ?>"><i class="fa fa-map-marker"></i><br>
                                Dirección</a> </li>
                        <li><a href="<?php echo site_url('checkout2'); ?>"><i class="fa fa-truck"></i><br>
                                Datos de envio</a> </li>
                        <li><a href="<?php echo site_url('checkout3'); ?>"><i class="fa fa-money"></i><br>
                                Forma de pago</a> </li>
                        <li class="active"><a href="<?php echo site_url('checkout4'); ?>"><i class="fa fa-eye"></i><br>
                                Revisión de compra</a> </li>
                    </ul>
                    <div class="content">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Producto</th>
                                        <th>Cantidad</th>
                                        <th>Medida</th>
                                        <th>Talle</th>
                                        <th>Color</th>
                                        <th>$ Unitario</th>

                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $grand_total = 0;

                                    $i = 1;

                                    foreach ($cart_check as $item):

                                        // echo form_hidden('cart[' . $item['id'] . '][id]', $item['id']);
                                        // Will produce the following output.
                                        // <input type="hidden" name="cart[1][id]" value="1" />

                                        echo form_hidden('cart[' . $item['rowid'] . '][id]', $item['id']);

                                        echo form_hidden('cart[' . $item['rowid'] . '][rowid]', $item['rowid']);

                                        echo form_hidden('cart[' . $item['rowid'] . '][name]', $item['name']);

                                        echo form_hidden('cart[' . $item['rowid'] . '][price]', $item['price']);

                                        echo form_hidden('cart[' . $item['rowid'] . '][qty]', $item['qty']);

                                        $grand_total = $grand_total + $item['subtotal'];

                                        $link = ($this->cart->product_options($item['rowid'])['tipo_producto'] == 'producto') ? 'detalle' : 'detalle_combo';
                                        ?>
                                    <style>
                                        .radio-<?= $this->cart->product_options($item['rowid'])['color_id']; ?>.radio label::before {
                                            border: 1px solid <?= $colores[$this->cart->product_options($item['rowid'])['color_id']] ?>;
                                            background: <?= $colores[$this->cart->product_options($item['rowid'])['color_id']] ?> !important;
                                        }
                                        .radio-<?= $this->cart->product_options($item['rowid'])['color_id']; ?> input[type="radio"] + label::after {
                                            background: <?= $colores[$this->cart->product_options($item['rowid'])['color_id']] ?>;
                                        }
                                        .radio-<?= $this->cart->product_options($item['rowid'])['color_id']; ?> input[type="radio"]:checked + label::before {
                                            background-color: <?= $colores[$this->cart->product_options($item['rowid'])['color_id']] ?>;
                                            border-color: <?= $colores[$this->cart->product_options($item['rowid'])['color_id']] ?>;
                                        }
                                        .radio-<?= $this->cart->product_options($item['rowid'])['color_id']; ?> input[type="radio"]:checked + label::after {
                                            background-color: <?= $colores[$this->cart->product_options($item['rowid'])['color_id']] ?>;
                                            border-color: <?= $colores[$this->cart->product_options($item['rowid'])['color_id']] ?>;
                                        }
                                    </style>
                                    <tr>
                                        <td><a href="<?php echo site_url($link . '/' . $this->cart->product_options($item['rowid'])['slug']); ?>"> <img src="<?= base_url(); ?>uploads/<?= $this->cart->product_options($item['rowid'])['imagen'] ?>" alt="<?= $this->cart->product_options($item['rowid'])['slug']; ?>"> </a></td>
                                        <td><a href="<?php echo site_url($link . '/' . $this->cart->product_options($item['rowid'])['slug']); ?>" class="black"><?php echo $item['name']; ?></a></td>
                                        <!--<td align="left"><input type="number" value="1" class="form-control" style="width: 70px; display: inline-block; margin-right: 10px; text-align: left"></td>-->
                                        <td align="left">
                                            <?php echo form_input('cart[' . $item['rowid'] . '][qty]', $item['qty'], 'maxlength="4" size="1" style="width: 70px; display: inline-block; margin-right: 10px; text-align: left;float:left;" class="form-control" onchange="$(\'#form_carro\').submit()"'); ?>
                                            <?php /* echo ($this->cart->product_options($item['rowid'])['cantidad_a_descontar'] == 10) ? '<span style="float:left">(x10)</span>' : '' */ ?>
                                        </td>
                                        <td><?= (!empty($this->cart->product_options($item['rowid'])['medida_id'])) ? $medidas[$this->cart->product_options($item['rowid'])['medida_id']] : ''; ?></td>
                                        <td><?= (!empty($this->cart->product_options($item['rowid'])['talle_id'])) ? $talles[$this->cart->product_options($item['rowid'])['talle_id']] : ''; ?></td>
                                        <td align="center">

                                            <div class="radio radio-<?= $this->cart->product_options($item['rowid'])['color_id']; ?> radio-inline">
                                                <input type="radio" id="inlineRadi<?= $this->cart->product_options($item['rowid'])['color_id']; ?>" value="<?= $this->cart->product_options($item['rowid'])['color_id']; ?>" name="color_id" checked>
                                                <label for="inlineRadio<?= $this->cart->product_options($item['rowid'])['color_id']; ?>"></label>
                                            </div>
                                        </td>
                                        <td>$ <?php echo number_format($item['price'], 2); ?></td>

                                        <td align="center">$ <?php echo number_format($item['price'] * $item['qty'], 2, '.', ''); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                                <?php
                                $d = (!empty($_SESSION['descuento_aplicado']['porcentaje'])) ? $_SESSION['descuento_aplicado']['porcentaje'] : 0;

                                $descuento = ($grand_total * $d) / 100;

                                $subtotal = $grand_total;

                                $grand_total = $grand_total - $descuento;
                                ?>
                                <tfoot>
                                    <tr>
                                        <th colspan="7">Descuento</th>
                                        <th style="text-align: center;">$<?= number_format($descuento, 2, '.', '') ?></th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <th colspan="7">Total</th>
                                        <th style="text-align: center;">$<?php echo number_format($grand_total, 2, '.', ''); ?></th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.content -->

                    <div class="box-footer">
                        <div class="pull-left"> <a href="<?php echo site_url('checkout3'); ?>" class="btn btn-default"><i class="fa fa-chevron-left"></i>Volver a Forma de Pago</a> </div>
                        <div class="pull-right"> <a href="<?php echo site_url('checkout5'); ?>" class="btn btn-primary">Finalizar compra<i class="fa fa-chevron-right"></i> </a> </div>
                    </div>

                </div>
                <!-- /.box -->

            </div>
            <!-- /.col-md-9 -->

            <div class="col-md-3">
                <div class="box" id="order-summary">
                    <div class="box-header">
                        <h3>Resumen de compra</h3>
                    </div>
                    <p class="text-muted">Los precios incluyen IVA. La entrega y sus costos adicionales son calculados en base a los datos ingresados.</p>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Subtotal</td>
                                    <th>$<?php echo number_format($subtotal, 2, '.', ''); ?></th>
                                </tr>
                                <tr>
                                    <td>Descuento</td>
                                    <th>

                                        <div class="form-group">
                                            <label for="user">Ingresá tu código</label>
                                            <input type="text" class="form-control" name="descuento" onchange="$('#form_carro').submit();">
                                        </div>

                                    </th>
                                </tr>
                                <tr>
                                    <td>Descuento</td>
                                    <th>$<?php echo number_format($descuento, 2, '.', ''); ?></th>
                                </tr>
                                <tr class="total">
                                    <td>Total</td>
                                    <th>$<?php echo number_format($grand_total, 2, '.', ''); ?></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.col-md-3 -->
            <?php echo form_close(); ?>

        </div>
        <!-- /.container -->
    </div>
