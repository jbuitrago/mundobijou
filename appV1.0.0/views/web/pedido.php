<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url('index'); ?>">Home</a> </li>
                    <li><a href="<?php echo site_url('mis_pedidos'); ?>">Mis Pedidos</a> </li>
                    <li>Pedido #<?= str_pad($pedido->id, 4, 0, STR_PAD_LEFT) ?></li>
                </ul>
                <div class="box text-center">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <h1>Pedido #<?= str_pad($pedido->id, 4, 0, STR_PAD_LEFT) ?></h1>
                            <p class="lead">El Pedido #<?= str_pad($pedido->id, 4, 0, STR_PAD_LEFT) ?> fué hecho el <strong><?= date("d/m/Y", strtotime($pedido->fecha_insert)) ?></strong> y su estado es <strong><?= $estados_pedido[$pedido->estado] ?></strong>.</p>
                            <p class="text-muted">Si tenés alguna duda, por favor <a href="<?php echo site_url('contacto'); ?>">contactanos</a>, te responderemos a la brevedad.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- *** LEFT COLUMN ***
                           _________________________________________________________ -->
            <div class="col-md-3">
                <!-- *** CUSTOMER MENU ***
         _________________________________________________________ -->
                <div class="panel panel-default sidebar-menu">
                    <div class="panel-heading">
                        <h3 class="panel-title">Menú del cliente</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills nav-stacked">
                            <li> <a href="<?php echo site_url('mi_cuenta'); ?>"><i class="fa fa-user"></i> Mi Cuenta</a> </li>
                            <li class="active"> <a href="<?php echo site_url('mis_pedidos'); ?>"><i class="fa fa-list"></i> Mis Pedidos</a> </li>
                            <li> <a href="<?php echo site_url('logout'); ?>"><i class="fa fa-sign-out"></i> Cerrar Sesión</a> </li>
                        </ul>
                    </div>
                </div>
                <!-- /.col-md-3 -->

                <!-- *** CUSTOMER MENU END *** -->
            </div>

            <!-- /.col-md-9 -->

            <!-- *** LEFT COLUMN END *** -->

            <!-- *** RIGHT COLUMN ***
                           _________________________________________________________ -->

            <div class="col-md-9 clearfix" id="customer-order">

                <div class="box noborder">

                    <div class="table-responsive">

                        <?php echo form_open('pedido/' . $pedido->id, array('id' => 'form_carro')); ?>

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

                                <?php foreach ($items as $item): ?>

                                    <?php
                                    echo form_hidden('pedido_id', $pedido->id);

                                    echo form_hidden('porcentaje', $pedido->porcentaje);

                                    echo form_hidden('cart[' . $item->id . '][id]', $item->id);

                                    echo form_hidden('cart[' . $item->id . '][rowid]', $item->id);

                                    echo form_hidden('cart[' . $item->id . '][name]', $item->nombre);

                                    echo form_hidden('cart[' . $item->id . '][price]', $item->precio_unitario);

                                    echo form_hidden('cart[' . $item->id . '][qty]', $item->cantidad);

                                    echo form_hidden('cart[' . $item->id . '][producto_id]', $item->producto_id);

                                    echo form_hidden('cart[' . $item->id . '][cantidad_a_descontar]', $item->cantidad_a_descontar);

                                    echo form_hidden('cart[' . $item->id . '][color_id]', $item->color_id);

                                    echo form_hidden('cart[' . $item->id . '][medida_id]', $item->medida_id);

                                    echo form_hidden('cart[' . $item->id . '][talle_id]', $item->talle_id);

                                    echo form_hidden('cart[' . $item->id . '][imagen]', $item->imagen);
                                    
                                    echo form_hidden('cart[' . $item->id . '][tipo_producto]', $item->tipo_producto);

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

                                    <td><?= (isset($item->medida_id))?$medidas[$item->medida_id] :''; ?></td>

                                    <td><?= (isset($item->talle_id))?$talles[$item->talle_id] : ''; ?></td>

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
                                    <th colspan="7" class="text-left">Subtotal</th>
                                    <th>$<?php echo number_format($pedido->subtotal, 2, '.', ''); ?></th>
                                </tr>

                                <tr>
                                    <th colspan="7" class="text-left">Descuento</th>
                                    <th>$ <?php echo number_format($descuento, 2, '.', ''); ?></th>
                                </tr>

                                <tr>
                                    <th colspan="7" class="text-left">Total</th>
                                    <th>$<?php echo number_format($pedido->total, 2, '.', ''); ?></th>
                                </tr>

                            </tfoot>

                        </table>

                        <?php echo form_close(); ?>

                    </div>
                    <!-- /.table-responsive -->

                    <div class="row addresses" style="text-align: center;">
                        <div class="col-sm-12">
                            <h2>Dirección de entrega</h2>
                            <p>Calle: <?= $pedido->calle ?> N&deg;: <?= $pedido->altura ?> - <?= $pedido->piso ?>&nbsp;<?= $pedido->dpto ?> <br>
                                Barrio: <?= $pedido->barrio ?> - Manzana <?= $pedido->manzana ?> - Lote <?= $pedido->lote ?> - CP <?= $pedido->codigo_postal ?><br>
                                <?= $provincias[$pedido->provincia] ?> - <?= $localidades[$pedido->localidad] ?><br>
                                Expreso: <?= $pedido->expreso ?><br>
                                Comentario: <?= $pedido->comentario ?></p>

                        </div>
                    </div>
                    <!-- /.addresses -->

                </div>
                <!-- /.box -->

            </div>
            <!-- *** RIGHT COLUMN END *** -->

        </div>
        <!-- /.container -->
    </div>
    <!-- /#content -->
