<!-- *** LOGIN MODAL ***
_________________________________________________________ -->

<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center" id="Login">
                   <!-- <img src="img/logo.png" height="60" alt="" style="margin:0 auto;"/><br> -->
                    <img src="<?= base_url(); ?>assets/web/img/logo.png" height="60" alt="" style="margin:0 auto;"/><br>
                    <hr>
                    Ingresar</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('web/process_login',array('onsubmit'=>'return false', 'id'=>'form_login')); ?>
                <input type="hidden" name="come_from" value="mi_cuenta">
                    <div class="form-group">
                        <input type="text" class="form-control" name="usuario" placeholder="usuario">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="password">
                    </div>
                    <div class="checkbox checkbox-default">
                        <input type="checkbox" id="checkbox1" class="styled" checked>
                        <label for="checkbox1"> Recordarme </label>
                    </div>
                    <p class="text-center">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-sign-in"></i> Log in</button>
                    </p>
                    <div class="form_error" id="errores_login"></div>
                <?php echo form_close(); ?>
                <p class="text-center text-muted"><a href="<?php echo site_url('olvide_pass'); ?>">Olvidé mi contraseña</a></p>
                <p class="text-center text-muted">Todavía no estás registrado?</p>
                <p class="text-center text-muted"><a href="<?php echo site_url('registro'); ?>"><strong>Registrate ahora !</strong></a></p>
            </div>
        </div>
    </div>
</div>

<!-- *** LOGIN MODAL END *** -->