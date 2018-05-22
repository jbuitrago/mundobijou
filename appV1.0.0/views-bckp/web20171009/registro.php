<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?= base_url(); ?>/index">Home</a> </li>
                    <li>Registro / Ingresar</li>
                </ul>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <h1>Registro</h1>
                    <p class="lead">Todavía no estás registrado?</p>
                    <p>Registrate en Mundo Bijou y recibí descuentos para tus compras y muchísimas ofertas más. Todo el proceso no te llevará más de un minuto.</p>
                    <p class="text-muted">Si tenés alguna duda, por favor <a href="<?= base_url(); ?>/contacto">contactanos</a>, te responderemos a la brevedad.</p>
                    <hr>
                    <form >
                        <!-- <div class="form-group">
                           <label for="user">Usuario</label>
                           <input type="text" class="form-control" id="user">
                         </div>
                         <div class="form-group">
                           <label for="pass">Contraseña</label>
                           <input type="text" class="form-control" id="pass">
                         </div>-->

                        <div class="text-center"> <a href="<?= base_url(); ?>/registro" class="btn btn-primary"><i class="fa fa-user-md"></i> Registrate</a> </div>
                        <br>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <h1>Ingresar</h1>
                    <p class="lead">Ya sos cliente registrado?</p>
                    <p class="text-muted">Ingresá tu Email y contraseña y luego hacé click en el botón Ingresar</p>
                    <hr>
                     <?php echo form_open('process_login',array('onsubmit'=>'return false', 'id'=>'form_login')); ?>
                        <div class="form-group">
                            <label for="email">Usuario o Email</label>
                            <input type="text" class="form-control" name="usuario" placeholder="usuario o email">
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" name="password" placeholder="password">
                        </div>
                     <div class="form_error" id="errores_login"></div>
                        <div class="text-center">
                            
                            <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i>Ingresar</button>

                        </div>
                        <div class="text-center">
                            <br>

                            <p class="text-center text-muted"><a href="<?= base_url(); ?>/olvide_pass">Olvidé mi contraseña</a></p>
                        </div>
                        <br>
                   
                <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <!-- /.container --> 
    </div>
    <!-- /#content --> 

