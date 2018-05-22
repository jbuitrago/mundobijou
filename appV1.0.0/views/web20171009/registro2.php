<div id="all">
    <div id="content" class="clearfix">
        <div class="container">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?= base_url(); ?>/index}">Home</a> </li>
                    <li>Registro</li>
                </ul>
            </div>

            <!-- *** LEFT COLUMN ***
                           _________________________________________________________ -->

            <div class="col-md-3">
                <!-- *** CUSTOMER MENU ***
         _________________________________________________________ -->
                <div class="panel panel-default sidebar-menu">
                    <div class="panel-heading">
                        <h3 class="panel-title">Registro</h3>
                    </div>
                    <div class="panel-body">
                        <p>Registrate en Mundo Bijou y recibí descuentos para tus compras y muchísimas ofertas más. Todo el proceso no te llevará más de un minuto.</p>
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
                <div class="box clearfix">
                    <?php echo form_open('registro'); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="inline">Tipo de cuenta (*): </h4>

                            <?php $c = count($tipo_cliente);
                            for ($i = 1; $i <= $c; $i++):
                                ?>
                                <div class="checkbox checkbox-inline">
                                    <input type="checkbox" name="tipo_cliente" class="styled" value="<?php echo $i ?>" />

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

                                <input name="nombre_apellido" placeholder="nombre y apellido" class="form-control" type="text" value="<?php echo set_value('nombre_apellido'); ?>">
                                <div class="form_error"><?php echo form_error('nombre_apellido'); ?></div> 
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="cuit">CUIT / CUIL / DNI (*)</label>
                                <input type="text" class="form-control" name="numero_doc" value="<?php echo set_value('numero_doc'); ?>">
                                <div class="form_error"><?php echo form_error('numero_doc'); ?> </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="usuario">Usuario (*)</label>
                                <input name="usuario" class="form-control" type="text" value="<?php echo set_value('usuario'); ?>">
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
                                <input name="email" class="form-control" type="email" value="<?php echo set_value('email'); ?>">
                                <div class="form_error"><?php echo form_error('email'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="phone">Código de Área + Teléfono (*)</label>

                                <input name="telefono" placeholder="Ej. (011) 4632 4444" class="form-control" type="text" value="<?php echo set_value('telefono'); ?>">
                                <div class="form_error"><?php echo form_error('telefono'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="calle">Calle</label>

                                <input name="calle" class="form-control" type="text" value="<?php echo set_value('calle'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="altura">Altura</label>
                                <input name="altura" class="form-control" type="text" value="<?php echo set_value('altura'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="piso">Piso</label>

                                <input name="piso" class="form-control" type="text" value="<?php echo set_value('piso'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="dpto">Dpto.</label>
                                <input name="dpto" class="form-control" type="text" value="<?php echo set_value('dpto'); ?>">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="barrio">Barrio</label>
                                <input name="barrio" class="form-control" type="text" value="<?php echo set_value('barrio'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="apple">Manzana</label>
                                <input type="text" class="form-control" name="manzana" value="<?php echo set_value('manzana'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="lote">Lote</label>
                                <input type="text" class="form-control" name="lote" value="<?php echo set_value('lote'); ?>">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="zip">Código Postal</label>
                                <input name="codigo_postal" class="form-control" type="text" value="<?php echo set_value('codigo_postal'); ?>">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="state">Provincia</label>
                                <?php echo form_dropdown('provincia', $provincias, set_value('provincia'), 'class="form-control"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="localidad">Localidad</label>
                                <?php echo form_dropdown('localidad', array(), '', 'class="form-control"'); ?>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="iva">Tipo de IVA</label>
                                <?php echo form_dropdown('tipo_iva', $condicion_iva, set_value('tipo_iva'), 'class="form-control"'); ?>
                            </div>

                        </div>
                        <div class="col-sm-12">
                            <div class="checkbox checkbox-default">
                                <input type="checkbox" id="checkbox1" class="styled" checked name="terminos">
                                <label for="checkbox1"> Acepto  <a href="<?= base_url(); ?>terminos_y_condiciones" target="_blank" >Términos y Condiciones</a> </label>
                            </div>
                            <div class="form_error"><?php echo form_error('terminos'); ?></div>
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
        var LOCALIDAD;
        </script>
