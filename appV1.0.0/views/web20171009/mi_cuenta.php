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
                            <h1>Mi Cuenta</h1>
                            <p class="lead">Editá tus datos de logueo y personales desde aquí.</p>
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
                            <li class="active"> <a href="<?php echo site_url('mi_cuenta'); ?>"><i class="fa fa-user"></i> Mi Cuenta</a> </li>
                            <li > <a href="<?php echo site_url('cambiar_pass'); ?>"><i class="fa fa-user-md"></i> Cambiar contrase&ntilde;a</a> </li>
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
                <!--
                <div class="box clearfix noborder">
                    <h3>Cambiar Contraseña</h3>
                    <div class="form_error"><?php //echo $actualizar_pass; ?></div>    
                    <?php //echo form_open('mi_cuenta'); ?>
                    <input type="hidden" name="id" value="<?php //echo $cliente->id ?>">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password_old">Contraseña actual</label>
                                <input type="password" class="form-control" name="password_old">
                                <div class="form_error"><?php //echo form_error('password_old'); ?></div>    
                                <input type="hidden" class="form-control" name="update_pass" value="si">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password_1">Nueva contraseña</label>
                                <input type="password" class="form-control" name="password_1">
                                <div class="form_error"><?php //echo form_error('password_1'); ?></div>    
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password_2">Repite nueva contraseña</label>
                                <input type="password" class="form-control" name="password_2">
                                <div class="form_error"><?php //echo form_error('password_2'); ?></div>    
                            </div>
                        </div>
                    </div>
                    <!-- /.row --

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                    <?php //echo form_close(); ?>
                </div>
                -->
                <div class="box clearfix">
                    <h3>Mi Cuenta</h3>
                     <div class="form_error"><?php echo $actualizar_info; ?></div>  
                    <?php echo form_open('mi_cuenta'); ?>
                    <input type="hidden" name="id" value="<?php echo $cliente->id ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="inline">Tipo de cuenta (*): </h4>

                            <?php
                            $c = count($tipo_cliente);
                            for ($i = 1; $i <= $c; $i++):
                                ?>
                                <div class="checkbox checkbox-inline">
                                    <input type="checkbox" name="tipo_cliente" class="styled" value="<?php echo $i ?>" <?= ($i == $cliente->tipo_cliente) ? 'checked' : '' ?> />

                                    <label for="inlineCheckbox1"> <?php echo $tipo_cliente[$i] ?> </label>
                                </div>

                            <?php endfor; ?>
                            <div class="form_error"><?php echo form_error('tipo_cliente'); ?></div>    
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="rs">Nombre y Apellido / Razón Social (*)</label>

                                <input name="nombre_apellido" placeholder="nombre y apellido" class="form-control" type="text" value="<?php echo (!empty(set_value('nombre_apellido'))) ? set_value('nombre_apellido') : $cliente->nombre_apellido; ?>">
                                <div class="form_error"><?php echo form_error('nombre_apellido'); ?></div> 
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="cuit">CUIT / CUIL / DNI (*)</label>
                                <input type="text" class="form-control" name="numero_doc" value="<?php echo (!empty(set_value('numero_doc'))) ? set_value('numero_doc') : $cliente->numero_doc; ?>">
                                <div class="form_error"><?php echo form_error('numero_doc'); ?> </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="usuario">Usuario (*)</label>
                                <input name="usuario" class="form-control" type="text" value="<?php echo (!empty(set_value('usuario'))) ? set_value('usuario') : $cliente->usuario; ?>">
                                <div class="form_error"><?php echo form_error('usuario'); ?>  </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password">Contraseña (*)</label>
                                <input name="password" class="form-control" type="password">
                                <div class="form_error"><?php echo form_error('password'); ?></div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">Email (*)</label>
                                <input name="email" class="form-control" type="email" value="<?php echo (!empty(set_value('email'))) ? set_value('email') : $cliente->email; ?>">
                                <div class="form_error"><?php echo form_error('email'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="phone">Código de Área + Teléfono (*)</label>

                                <input name="telefono" placeholder="Ej. (011) 4632 4444" class="form-control" type="text" value="<?php echo (!empty(set_value('telefono'))) ? set_value('telefono') : $cliente->telefono; ?>">
                                <div class="form_error"><?php echo form_error('telefono'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="calle">Calle</label>

                                <input name="calle" class="form-control" type="text" value="<?php echo (!empty(set_value('calle'))) ? set_value('calle') : $cliente->calle; ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="altura">Altura</label>
                                <input name="altura" class="form-control" type="text" value="<?php echo (!empty(set_value('altura'))) ? set_value('altura') : $cliente->altura; ?>">
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
                                <label for="zip">Código Postal</label>
                                <input name="codigo_postal" class="form-control" type="text" value="<?php echo (!empty(set_value('codigo_postal'))) ? set_value('codigo_postal') : $cliente->codigo_postal; ?>">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="state">Provincia</label>
                                <?php echo form_dropdown('provincia', $provincias, (!empty(set_value('provincia'))) ? set_value('provincia') : $cliente->provincia, 'class="form-control"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="localidad">Localidad</label>
                                <?php echo form_dropdown('localidad', array(), (!empty(set_value('localidad'))) ? set_value('localidad') : $cliente->localidad, 'class="form-control"'); ?>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="iva">Tipo de IVA</label>
                                <?php echo form_dropdown('tipo_iva', $condicion_iva, (!empty(set_value('tipo_iva'))) ? set_value('tipo_iva') : $cliente->tipo_iva, 'class="form-control"'); ?>
                            </div>

                        </div>
                     
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                            <br>
                            <br>
                        </div>
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