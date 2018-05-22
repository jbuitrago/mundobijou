<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url('index'); ?>">Home</a> </li>
                    <li>Checkout - Datos de envio</li>
                </ul>
                <div class="box text-center">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <h1>Checkout - Datos de envio</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 clearfix" id="checkout">

                <div class="box">

                    <?php echo form_open('checkout2'); ?>

                    <ul class="nav nav-pills nav-justified">
                        <li><a href="<?php echo site_url('checkout1'); ?>"><i class="fa fa-user"></i><br>
                                Datos</a> </li>
                        <li class="active"><a href="<?php echo site_url('checkout2'); ?>"><i class="fa fa-truck"></i><br>
                                Datos de envio</a> </li>
                        <li class="disabled"><a href="<?php echo site_url('checkout3'); ?>"><i class="fa fa-money"></i><br>
                                Forma de pago</a> </li>
                        <li class="disabled"><a href="<?php echo site_url('checkout4'); ?>"><i class="fa fa-eye"></i><br>
                                Revisión de compra</a> </li>
                    </ul>
                    <div class="content">

                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Datos del cliente</h4>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="calle">Calle  <span style="color:red">(*)</span></label>
                                    <input name="calle" class="form-control" type="text" value="<?php echo (!empty(set_value('calle'))) ? set_value('calle') : $cliente->calle; ?>">
                                    <div class="form_error"><?php echo form_error('calle'); ?></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="altura">Altura  <span style="color:red">(*)</span></label>
                                    <input name="altura" class="form-control" type="text" value="<?php echo (!empty(set_value('altura'))) ? set_value('altura') : $cliente->altura; ?>">
                                    <div class="form_error"><?php echo form_error('altura'); ?></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="piso">Piso</label>
                                    <input name="piso" class="form-control" type="text" value="<?php echo (!empty(set_value('piso'))) ? set_value('piso') : $cliente->piso; ?>">

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="dpto">Dpto.</label>
                                    <input name="dpto" class="form-control" type="text" value="<?php echo (!empty(set_value('dpto'))) ? set_value('dpto') : $cliente->dpto; ?>">
                                </div>
                            </div>
                            <p></p>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="barrio">Barrio</label>
                                    <input name="barrio" class="form-control" type="text" value="<?php echo (!empty(set_value('barrio'))) ? set_value('barrio') : $cliente->barrio; ?>">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="apple">Manzana</label>
                                    <input type="text" class="form-control" name="manzana" value="<?php echo (!empty(set_value('manzana'))) ? set_value('manzana') : $cliente->manzana; ?>">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="lote">Lote</label>
                                    <input type="text" class="form-control" name="lote" value="<?php echo (!empty(set_value('lote'))) ? set_value('lote') : $cliente->lote; ?>">
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="codigo_postal">Código Postal  <span style="color:red">(*)</span></label>
                                    <input name="codigo_postal" class="form-control" type="text" value="<?php echo (!empty(set_value('codigo_postal'))) ? set_value('codigo_postal') : ($cliente->codigo_postal == 0)? '': $cliente->codigo_postal; ?>">
                                    <div class="form_error"><?php echo form_error('codigo_postal'); ?></div>
                                </div>
                            </div>
                           <p></p>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="provincia">Provincia  <span style="color:red">(*)</span></label>
                                    <?php echo form_dropdown('provincia', $provincias, (!empty(set_value('provincia'))) ? set_value('provincia') : $cliente->provincia, 'class="form-control"'); ?>
                                    <div class="form_error"><?php echo form_error('provincia'); ?></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="localidad">Localidad  <span style="color:red">(*)</span></label>
                                    <?php echo form_dropdown('localidad', array(), (!empty(set_value('localidad'))) ? set_value('localidad') : $cliente->localidad, 'class="form-control"'); ?>
                                    <div class="form_error"><?php echo form_error('localidad'); ?></div>
                                </div>
                            </div>

                        </div>
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="expreso">Expreso</label>
                                    <input type="text" class="form-control" name="expreso" value="<?php echo (!empty(set_value('expreso'))) ? set_value('expreso') : (!empty($cliente->expreso)) ? $cliente->expreso : ''; ?>">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="direccion_transporte">Dirección del transporte</label>
                                    <input type="text" class="form-control" name="direccion_transporte" value="<?php echo (!empty(set_value('direccion_transporte'))) ? set_value('direccion_transporte') : (!empty($cliente->direccion_transporte)) ? $cliente->direccion_transporte : ''; ?>">
                                    
                                </div>
                            </div>
                            </br>
                            <p><strong>EL PEDIDO SERÁ ENVIADO A LA TERMINAL DE SU LOCALIDAD</strong></p>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="comentario">Comentario</label>
                                    <textarea name="comentario" class="form-control"><?php echo (!empty(set_value('comentario'))) ? set_value('comentario') : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- /.row --> 
                    </div>
                    <!-- /.content -->

                    <div class="box-footer">
                        <div class="pull-left"> <a href="<?php echo site_url('checkout1'); ?>" class="btn btn-default"><i class="fa fa-chevron-left"></i>Volver a Dirección</a> </div>
                        <div class="pull-right"> <button href="<?php echo site_url('checkout3'); ?>" class="btn btn-primary">Forma de Pago<i class="fa fa-chevron-right"></i> </button> </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
                <!-- /.box --> 

            </div>
            <!-- /.col-md-9 -->

            <?php
            $cart_check = $this->cart->contents();

            $grand_total = 0;

            $i = 1;

            foreach ($cart_check as $item) {
                $grand_total = $grand_total + $item['subtotal'];
            }

            $d = (!empty($_SESSION['descuento_aplicado']['porcentaje'])) ? $_SESSION['descuento_aplicado']['porcentaje'] : 0;

            $descuento = ($grand_total * $d) / 100;

            $total = $grand_total - $descuento;
            ?> 

            <div class="col-md-3">
                <div class="box" id="order-summary">
                    <div class="box-header">
                        <h3>Resumen de compra</h3>
                    </div>
                    <p class="text-muted">Los precios incluyen IVA. El costo de envío corre por cuenta del cliente.</p>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Subtotal</td>
                                    <th>$<?php echo number_format($grand_total, 2, '.', ''); ?></th>
                                </tr>
                                <tr>
                                    <td>Descuento</td>
                                    <th>$<?php echo number_format($descuento, 2, '.', ''); ?></th>
                                </tr>

                                <tr class="total">
                                    <td>Total</td>
                                    <th>$<?php echo number_format($total, 2, '.', ''); ?></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.col-md-3 --> 

        </div>
        <!-- /.container --> 
    </div>
    <!-- /#content --> 
    <script>
        var LOCALIDAD = '<?= (!empty($cliente->localidad)) ? $cliente->localidad : "" ?>';
    </script>