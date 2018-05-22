<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url('index'); ?>">Home</a> </li>
                    <li>Pedido #<?= str_pad($pedido->id, 4, 0, STR_PAD_LEFT) ?></li>
                </ul>
                <div class="box text-center">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <h1>Pedido #<?= str_pad($pedido->id, 4, 0, STR_PAD_LEFT) ?></h1>
                            <p class="text-muted">Estos son los datos de su pedido</p>
                        </div>
                    </div>
                </div>
                 <div class="text-left seguir-comprando"> <a href="<?php echo site_url((!empty($_SESSION['last_url_view'])) ? $_SESSION['last_url_view'] : 'productos'); ?>" class="btn btn-primary invert"><i class="fa fa-chevron-left"></i> Seguir comprando</a> </div>
            </div>
            <div class="col-md-12 clearfix" id="customer-order">
                <div class="alert alert-dismissible alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Felicitaciones!</strong> Su compra ha sido realizada con éxito.<br>
                    <small>En breve recibirá un email con los datos de su compra.</small> </div>
                <div class="box noborder">
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

                                <?php $grand_total = 0; ?>

                                <?php foreach ($pedido_items as $item): ?>

                                    <?php
                                    $grand_total = $grand_total + $item->subtotal;
                                    ?>

                                <style>
                                    .radio-<?= $item->color_id; ?>.radio label::before {
                                        border: 1px solid <?= $colores[$item->color_id] ?>;
                                        background: <?= $colores[$item->color_id] ?> !important;
                                    }
                                    .radio-<?= $item->color_id; ?> input[type="radio"] + label::after {
                                        background: <?= $colores[$item->color_id] ?>;
                                    }
                                    .radio-<?= $item->color_id; ?> input[type="radio"]:checked + label::before {
                                        background-color: <?= $colores[$item->color_id] ?>;
                                        border-color: <?= $colores[$item->color_id] ?>;
                                    }
                                    .radio-<?= $item->color_id; ?> input[type="radio"]:checked + label::after {
                                        background-color: <?= $colores[$item->color_id] ?>;
                                        border-color: <?= $colores[$item->color_id] ?>;
                                    }
                                </style>

                                <tr>
                                    <td><a href="<?php echo site_url($item->my_url); ?>"> <img src="<?= base_url(); ?>uploads/<?= $item->imagen ?>" alt="<?= $item->slug; ?>"> </a></td>

                                    <td><a href="<?php echo site_url($item->my_url); ?>" class="black"><?php echo $item->nombre; ?></a></td>

                                    <?php if ($pedido->estado == 1 || $pedido->estado == 4): ?>
                                        <td align="left"><?php echo form_input('cart[' . $item->id . '][cantidad]', $item->cantidad, 'maxlength="4" size="1" style="width: 70px; display: inline-block; margin-right: 10px; text-align: left" class="form-control" onchange="$(\'#form_carro\').submit()"'); ?>
                                        <?php else: ?>
                                        <td align="left"><?php echo $item->cantidad; ?>
                                        <?php endif; ?> 

                                    <td><?= (!empty($item->medida_id)) ? $medidas[$item->medida_id] : ''; ?></td>

                                    <td><?= (!empty($item->talle_id)) ? $talles[$item->talle_id] : ''; ?></td>

                                    <td align="center">

                                        <div class="radio radio-<?= $item->color_id; ?> radio-inline">
                                            <input type="radio" id="inlineRadi<?= $item->color_id; ?>" value="<?= $item->color_id; ?>" name="color_id" checked>
                                            <label for="inlineRadio<?= $item->color_id; ?>"></label>
                                        </div>
                                    </td>
                                    <td>$ <?php echo number_format($item->precio_unitario, 2, '.', ''); ?></td>

                                    <td align="center">$ <?php echo number_format($item->subtotal, 2, '.', ''); ?></td>

                                </tr>

                            <?php endforeach; ?>
                            </tbody>

                            <?php
                            $d = (!empty($pedido->porcentaje)) ? $pedido->porcentaje : 0;

                            $descuento = ($pedido->subtotal * $d) / 100;
                            ?>  

                            <tfoot>
                                <tr>
                                    <th colspan="7" class="text-right">Subtotal</th>
                                    <th>$<?php echo number_format($pedido->subtotal, 2, '.', ''); ?></th>
                                </tr>

                                <tr>
                                    <th colspan="7" class="text-right">Descuento</th>
                                    <th>$ <?php echo number_format($descuento, 2, '.', ''); ?></th>
                                </tr>

                                <tr>
                                    <th colspan="7" class="text-right">Total</th>
                                    <th>$<?php echo number_format($pedido->total, 2, '.', ''); ?></th>
                                </tr>

                            </tfoot>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

                    <div class="row addresses" style="text-align: center;">

                        <?php if ($pedido->forma_pago == 'deposito_bancario'): ?>

                            <div class="col-sm-12">
                                <h2>Depósito o Transferencia Bancaria</h2>
                                <p>Titular: Gabriel Hernan Slain <br>
                                    Banco: Banco Frances<br>
                                    Tipo y Nro. de Cuenta: Cuenta Corriente, N° 302616/3<br>
                                    Sucursal: 302<br>
                                    CUIT: 23-29317677-9<br>
                                    CBU: 0170302120000030261631<br>
                                    ALIAS: JOYASACERO<br>
                            </div>

                        <?php else: ?>

                            <div class="col-sm-12">
                                <h2>MercadoPago</h2>
                                <p>Podes abonar con todos los medios de Mercado Pago. Recibirás la solicitud de pago dentro de las 24 hs hábiles de realizado tu pedido.</p>
                            </div>

                        <?php endif; ?>
                    </div>
                    <!-- /.addresses -->
                    <div class="box-footer">
                        <div class="pull-left"> <a href="<?php echo site_url('productos'); ?>" class="btn btn-primary invert"><i class="fa fa-chevron-left"></i>Seguir comprando</a> </div>
                        <div class="pull-right"> <a href="<?php echo site_url('mis_pedidos'); ?>" class="btn btn-primary">Ver resumen de compras<i class="fa fa-chevron-right"></i> </a> </div>
                    </div>
                </div>

                <!-- /.box --> 

            </div>
            <!-- /.col-md-9 --> 

            <!-- /.col-md-3 --> 

        </div>
        <!-- /.container --> 
    </div>
    <!-- /#content --> 
