<script>
     function validar()
    {
        $('#button_send').html('Espere...').show();             
        if ($('#usuario_email').val() === '') 
        {
            $('#errores_pass').html('Debe ingresar un usuario o un email!').show();
            $('#button_send').html('Enviar').show();
            return false;
        }
        else
        {   
            $.ajax({
                url: "web/check_user_email ",
                type: "POST",
                data: $('#form_pass').serialize(),
                dataType: "JSON",

                success: function (data)
                {
                    if (data.status == false) {
                        $('#errores_pass').show();
                        $('#errores_pass').html('');
                        $('#errores_pass').html(data.errores);
                        $('#button_send').html('Enviar').show();
                    } 
                    else 
                    {
                        location.href="<?php echo site_url('web/olvide_pass_gracias') ?>";
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error al enviar!');
                }
            });
        }
    }
    </script>

<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?= base_url(); ?>/index">Home</a> </li>
                    <li>Registro / Ingresar</li>
                </ul>
            </div>

            <div class="col-md-12">
                <div class="box ">
                    <h1>Ingresar</h1>
                    <p class="lead">Ingresá tu mail de usuario</p>
                    <p class="text-muted"></p>
                    <hr>
                    <form  id="form_pass" onsubmit="return false" > 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usuario_email">Usuario o Email</label>
                                <input type="text" class="form-control" name="usuario_email" id="usuario_email">
                            </div>

                            <div class="text-center">
                                <button onclick="validar()" id="button_send"class="btn btn-primary"><i class="fa fa-sign-in"></i>Enviar</button>

                            </div>
                             <div class="alert alert-danger" id="errores_pass"  style="display:none;"></div>
                        </div><br style="clear: both;">
                      </form>
                </div>
            </div>
        </div>
        <!-- /.container --> 
    </div>
    <!-- /#content --> 
</div>
