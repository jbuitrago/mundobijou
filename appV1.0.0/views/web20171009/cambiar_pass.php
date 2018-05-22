<div id="all">
    <div id="content" class="clearfix">
        <div class="container">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url('index'); ?>">Home</a> </li>
                    <li>Mi cuenta</li>
                </ul>
                <div class="box text-center">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <h1>Cambiar Contraseña</h1>
                            <p class="lead">Cambia tu contraseña desde aquí.</p>
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
                            <li class="active" > <a href="<?php echo site_url('cambiar_pass'); ?>"><i class="fa fa-user-md"></i> Cambiar contrase&ntilde;a</a> </li>
                            <li > <a href="<?php echo site_url('mis_pedidos'); ?>"><i class="fa fa-list"></i> Mis Pedidos</a> </li>
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

            <div class="col-md-9 clearfix" id="customer-account">
                <div class="box clearfix noborder">
                    <h3>Cambiar Contraseña</h3>
                    <div class="form_error"><?php echo $actualizar_pass; ?></div>    
                    <?php echo form_open('cambiar_pass'); ?>
                    <input type="hidden" name="id" value="<?php echo $cliente->id ?>">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password_old">Contraseña actual</label>
                                <input type="password" class="form-control" name="password_old">
                                <div class="form_error"><?php echo form_error('password_old'); ?></div>    
                                <input type="hidden" class="form-control" name="update_pass" value="si">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password_1">Nueva contraseña</label>
                                <input type="password" class="form-control" name="password_1">
                                <div class="form_error"><?php echo form_error('password_1'); ?></div>    
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password_2">Repite nueva contraseña</label>
                                <input type="password" class="form-control" name="password_2">
                                <div class="form_error"><?php echo form_error('password_2'); ?></div>    
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
               
            </div>

            <!-- *** RIGHT COLUMN END *** --> 

        </div>
        <!-- /.container --> 
    </div>
    <!-- /#content --> 
    <script>
    var LOCALIDAD = '<?= (!empty($cliente->localidad)) ? $cliente->localidad : "" ?>';
    </script>
