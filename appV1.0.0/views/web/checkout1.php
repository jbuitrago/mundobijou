<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12 clearfix">
                <ul class="breadcrumb">
                    <li><a href="{#link/index}">Home</a> </li>
                    <li>Checkout - Dirección</li>
                </ul>
                <div class="box text-center">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <h1>Completa todos tus datos para finalizar tu compra</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 clearfix" id="checkout">
                <div class="box">
                    <?php echo form_open('process_login',array('onsubmit'=>'return false', 'id'=>'form_login')); ?>
                    <input type="hidden" name="come_from" value="checkout2">
                        <ul class="nav nav-pills nav-justified">
                            <li class="active"><a href="<?php echo site_url('checkout1'); ?>"><i class="fa fa-user"></i><br>
                                    Datos</a> </li>
                            <li class="disabled"><a href="<?php echo site_url('checkout2'); ?>"><i class="fa fa-truck"></i><br>
                                    Datos de envio</a> </li>
                            <li class="disabled"><a href="<?php echo site_url('checkout3'); ?>"><i class="fa fa-money"></i><br>
                                    Forma de pago</a> </li>
                            <li class="disabled"><a href="<?php echo site_url('checkout4'); ?>"><i class="fa fa-eye"></i><br>
                                    Revisión de compra</a> </li>
                        </ul>
                        <div class="content">
                            <div class="row ">
                                <div class="col-sm-12">
                                    <h4>Si sos un usuario registrado</h4>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group inline " style="width: 100%;">
                                        <label for="usuario">Mail (*)</label>
                                        <input type="text" class="form-control " name="usuario">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group inline" style="width: 80%;">
                                        <label for="password">Contraseña (*)</label>
                                        <input type="password" class="form-control" name="password">
                                    </div>

                                    <div class="form-group inline" >
                                        <button class="btn btn-primary invert btn-sm">Ingresar</button>
                                    </div>
                                    
                                </div>
                                <p class="text-muted"><a href="<?php echo site_url('olvide_pass'); ?>">Olvidé mi contraseña</a></p>
                               <div class="form_error" id="errores_login"></div>     
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>Si sos un usuario nuevo registrate</h4></div>
                            </div>
                            <!-- /.row -->

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="text-center"> <a href="<?php echo site_url('registro'); ?>" class="btn btn-primary"><i class="fa fa-user-md"></i> Registrate</a> </div>
                                </div>
                            </div>
                            <!-- /.row --> 

                            <!-- /.row --> 
                        </div>
                        <div class="box-footer">
                            <div class="pull-left"> <a href="<?php echo site_url('carro'); ?>" class="btn btn-default"><i class="fa fa-chevron-left"></i>Volver al carro</a> </div>
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

            $d = (!empty($_SESSION['descuento_aplicado']['porcentaje']))? $_SESSION['descuento_aplicado']['porcentaje'] : 0;
            
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
