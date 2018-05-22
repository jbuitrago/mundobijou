<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url('index'); ?>">Home</a> </li>
                    <li>Mis Pedidos</li>
                </ul>
                <div class="box text-center">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <h1>Mis Pedidos</h1>
                            <p class="lead">Todo tu historial de compra aquí</p>
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
                            <li > <a href="<?php echo site_url('cambiar_pass'); ?>"><i class="fa fa-user-md"></i> Cambiar contrase&ntilde;a</a> </li>
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

            <div class="col-md-9" id="customer-orders">
                <div class="box">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#Pedido</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th colspan="2">Estado</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($pedidos as $pedido): ?>

                                    <?php
                                    $color_estado = '';

                                    switch ($pedido->estado) {
                                        case 1:
                                            $color_estado = 'info';
                                            break;
                                        case 2:
                                            $color_estado = 'success';
                                            break;

                                        case 3:
                                            $color_estado = 'danger';
                                            break;

                                        case 4:
                                            $color_estado = 'warning';
                                            break;

                                        default:
                                            break;
                                    }
                                    ?>

                                    <tr>
                                        <th># <?= str_pad($pedido->id, 4, 0, STR_PAD_LEFT) ?></th>
                                        <td><?= date("d/m/Y", strtotime($pedido->fecha_insert)) ?></td>
                                        <td>$ <?= $pedido->total ?></td>
                                        <td><span class="label label-<?=$color_estado?>"><?= $estados_pedido[$pedido->estado] ?></span></td>
                                        <td><a href="<?php echo site_url('pedido/'.$pedido->id); ?>" class="btn btn-primary btn-sm">ver</a></td>
                                    </tr>

                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive --> 

                </div>
                <!-- /.box --> 

            </div>

            <!-- *** RIGHT COLUMN END *** --> 

        </div>
        <!-- /.container --> 
    </div>
    <!-- /#content --> 
