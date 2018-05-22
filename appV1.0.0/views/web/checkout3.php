<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url('index'); ?>">Home</a> </li>
                    <li>Checkout - Forma de pago</li>
                </ul>
                <div class="box text-center">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <h1>Checkout - Forma de pago</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 clearfix" id="checkout">
                <div class="box">
                    <?php echo form_open('checkout3'); ?>
                    <ul class="nav nav-pills nav-justified">
                        <li><a href="<?php echo site_url('checkout1'); ?>"><i class="fa fa-user"></i><br>
                                Datos</a> </li>
                        <li><a href="<?php echo site_url('checkout2'); ?>"><i class="fa fa-truck"></i><br>
                                Datos de envio</a> </li>
                        <li class="active"><a href="<?php echo site_url('checkout3'); ?>"><i class="fa fa-money"></i><br>
                                Forma de pago</a> </li>
                        <li class="disabled"><a href="<?php echo site_url('checkout4'); ?>"><i class="fa fa-eye"></i><br>
                                Revisión de compra</a> </li>
                    </ul>
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="box payment-method">
                                    <h4>Depósito o Transferencia Bancaria</h4>
                                    <p>Podés depositar/transferir en cualquier sucursal del país del Banco BBVA Francés.</p>
                                    <div class="box-footer text-center">
                                        <input type="radio" name="payment" value="deposito_bancario">
                                        <div class="form_error"><?php echo form_error('payment'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="box payment-method">
                                    <h4>MercadoPago</h4>
                                    <p>Podes abonar con todos los medios de Mercado Pago. Recibirás la solicitud de pago con el 7% de recargo dentro de las 24hs hábiles de realizado tu pedido.</p>
                                    <div class="box-footer text-center">
                                        <input type="radio" name="payment" value="mercadopago">
                                    </div>
                                    <div class="form_error"><?php echo form_error('payment'); ?></div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="box payment-method">
                                    <h4>Datos Bancarios</h4>
                                    <p>Titular: Gabriel Hernan Slain <br>
                                        Banco: Banco Frances<br>
                                        Tipo y Número de cuenta: Cuenta Corriente, N° 302616/3<br>
                                        Sucursal: 302<br>
                                        CUIT: 23-29317677-9<br>
                                        CBU: 0170302120000030261631<br>
                                        ALIAS: JOYASACERO
                                    </p>
                                </div>
                            </div>

                        </div>
                        <!-- /.row --> 

                    </div>
                    <!-- /.content -->

                    <div class="box-footer">
                        <div class="pull-left"> <a href="<?php echo site_url('checkout2'); ?>" class="btn btn-default"><i class="fa fa-chevron-left"></i>Volver a Datos de envio</a> </div>
                        <div class="pull-right"> <button class="btn btn-primary" type="submit">Revisión de Compra<i class="fa fa-chevron-right"></i> </button> </div>
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
