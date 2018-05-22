<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url('index'); ?>">Home</a> </li>
                    <li>Link</li>
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
                        <ul class="cliente">
                            <li><span>Cliente:</span> Gabriel Pujol</li>
                            <li><span>Email:</span> gabrielpujol@gmail.com</li>
                        </ul>
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
                        <?php echo form_open('link/' . $pedido->id, array('id' => 'form_carro')); ?>
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
                                    <th>Editar</th>
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
                                    echo form_hidden('cart[' . $item->id . '][tipo_producto]', $item->tipo_producto);
                                    echo form_hidden('cart[' . $item->id . '][delete]', 0);

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
                                    <td><a href="<?php echo site_url('detalle/' . $item->slug); ?>"> <img src="<?= base_url(); ?>uploads/<?= $item->imagen ?>" alt="<?= $item->slug; ?>"> </a></td>

                                    <td><a href="<?php echo site_url('detalle/' . $item->slug); ?>" class="black"><?php echo $item->nombre; ?></a></td>

                                    <?php if ($pedido->estado == 1 || $pedido->estado == 4): ?>
                                        <td align="left"><?php echo form_input('cart[' . $item->id . '][cantidad]', $item->cantidad, 'maxlength="4" size="1" style="width: 70px; display: inline-block; margin-right: 10px; text-align: left" class="form-control" onchange="$(\'#form_carro\').submit()"'); ?>
                                        <?php else: ?>
                                        <td align="left"><?php echo $item->cantidad; ?>
                                        <?php endif; ?> 

                                    <td><?= $medidas[$item->medida_id]; ?></td>

                                    <td><?= $talles[$item->talle_id]; ?></td>

                                    <td align="center">

                                        <div class="radio radio-<?= $item->color_id; ?> radio-inline">
                                            <input type="radio" id="inlineRadi<?= $item->color_id; ?>" value="<?= $item->color_id; ?>" name="color_id" checked>
                                            <label for="inlineRadio<?= $item->color_id; ?>"></label>
                                        </div>
                                    </td>
                                    <td>$ <?php echo number_format($item->precio_unitario, 2, '.', ''); ?></td>

                                    <td align="center">$ <?php echo number_format($item->precio_unitario * $item->cantidad, 2, '.', ''); ?></td>

                                    <td>
                                        <!--<a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a> -->
                                        <a href="#"><i class="fa fa-trash" aria-hidden="true" onclick="delete_item(<?= $item->id ?>)"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="7" class="text-right">Subtotal</th>
                                    <th>$<?php echo number_format($pedido->subtotal, 2, '.', ''); ?></th>
                                </tr>

                                <tr>
                                    <th colspan="7" class="text-right">Descuento</th>
                                    <th>$ <?php echo number_format($pedido->porcentaje, 2, '.', ''); ?></th>
                                </tr>

                                <tr>
                                    <th colspan="7" class="text-right">Total</th>
                                    <th>$<?php echo number_format($pedido->total, 2, '.', ''); ?></th>
                                </tr>

                            </tfoot>

                        </table>
                        <?php echo form_close(); ?>
                    </div>
                    <!-- /.table-responsive -->
                    <?php echo form_open('link/' . $pedido->id); ?>
                    <?php echo form_hidden('id', $pedido->id); ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Datos de entrega</h4>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="calle">Calle</label>
                                <input name="calle" class="form-control" type="text" value="<?php echo (!empty(set_value('calle'))) ? set_value('calle') : $pedido->calle; ?>">
                                <div class="form_error"><?php echo form_error('calle'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="altura">Altura</label>
                                <input name="altura" class="form-control" type="text" value="<?php echo (!empty(set_value('altura'))) ? set_value('altura') : $pedido->altura; ?>">
                                <div class="form_error"><?php echo form_error('altura'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="piso">Piso</label>
                                <input name="piso" class="form-control" type="text" value="<?php echo (!empty(set_value('piso'))) ? set_value('piso') : $pedido->piso; ?>">

                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="dpto">Dpto.</label>
                                <input name="dpto" class="form-control" type="text" value="<?php echo (!empty(set_value('dpto'))) ? set_value('dpto') : $pedido->dpto; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="barrio">Barrio</label>
                                <input name="barrio" class="form-control" type="text" value="<?php echo (!empty(set_value('barrio'))) ? set_value('barrio') : $pedido->barrio; ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="apple">Manzana</label>
                                <input type="text" class="form-control" name="manzana" value="<?php echo (!empty(set_value('manzana'))) ? set_value('manzana') : $pedido->manzana; ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="lote">Lote</label>
                                <input type="text" class="form-control" name="lote" value="<?php echo (!empty(set_value('lote'))) ? set_value('lote') : $pedido->lote; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="codigo_postal">Código Postal</label>
                                <input name="codigo_postal" class="form-control" type="text" value="<?php echo (!empty(set_value('codigo_postal'))) ? set_value('codigo_postal') : $pedido->codigo_postal; ?>">
                                <div class="form_error"><?php echo form_error('codigo_postal'); ?></div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="provincia">Provincia</label>
                                <?php echo form_dropdown('provincia', $provincias, (!empty(set_value('provincia'))) ? set_value('provincia') : $pedido->provincia, 'class="form-control"'); ?>
                                <div class="form_error"><?php echo form_error('provincia'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="localidad">Localidad</label>
                                <?php echo form_dropdown('localidad', array(), (!empty(set_value('localidad'))) ? set_value('localidad') : $pedido->localidad, 'class="form-control"'); ?>
                                <div class="form_error"><?php echo form_error('localidad'); ?></div>
                            </div>
                        </div>

                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="expreso">Expreso</label>
                                <input type="text" class="form-control" name="expreso" value="<?php echo (!empty(set_value('expreso'))) ? set_value('expreso') : $pedido->expreso; ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="direccion_transporte">Dirección del transporte</label>
                                <input type="text" class="form-control" name="direccion_transporte" value="<?php echo (!empty(set_value('direccion_transporte'))) ? set_value('direccion_transporte') : $pedido->direccion_transporte; ?>">
                                </select>
                            </div>
                        </div>

                        </br>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="comentario">Comentario</label>
                                <textarea name="comentario" class="form-control"><?php echo (!empty(set_value('comentario'))) ? set_value('comentario') : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                            <br>
                            <br>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <!--
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="address">Calle</label>
                            <input type="text" class="form-control" id="address">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="altura">Altura</label>
                            <input type="text" class="form-control" id="altura">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="piso">Piso</label>
                            <input type="text" class="form-control" id="piso">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="dpto">Dpto.</label>
                            <input type="text" class="form-control" id="dpto">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="barrio">Barrio</label>
                            <input type="text" class="form-control" id="barrio">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="apple">Manzana</label>
                            <input type="text" class="form-control" id="apple">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="lote">Lote</label>
                            <input type="text" class="form-control" id="lote">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="zip">Código Postal</label>
                            <input type="text" class="form-control" id="zip">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="state">Provincia</label>
                            <select class="form-control" id="state">
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="localidad">Localidad</label>
                            <select class="form-control" id="localidad">
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="expreso">Expreso</label>
                            <input type="text" class="form-control" id="expreso">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="quien">Dirección del expreso</label>
                            <input type="text" class="form-control" id="quien">
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="cuit">CUIT/CUIL/DNI</label>
                            <input type="text" class="form-control" id="cuit">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="message">Comentario</label>
                            <textarea id="message" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                        <br>
                        <br>
                    </div>
                </div>
                    <!-- /.row --> 
                    <!-- /.addresses --> 

                </div>
                <!-- /.box --> 

            </div>
            <!-- *** RIGHT COLUMN END *** --> 

        </div>
        <!-- /.container --> 
    </div>
    <!-- /#content --> 
</div>
<script>
    var LOCALIDAD = '<?php (!empty($pedido->localidad))? $pedido->localidad : "" ?>';
    function delete_item(item_id) {
        if (confirm('Esta seguro que desea borrar el item')) {
            $('input[name="cart[' + item_id + '][delete]"').val(1);
            $('#form_carro').submit();
        }
    }
</script>