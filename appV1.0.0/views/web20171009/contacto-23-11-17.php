<div id="all">
    <div id="content">
        <div class="container">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?= base_url(); ?>index">Home</a> </li>
                    <li>Contacto</li>
                </ul>
                <div class="box text-center">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <h1>Contacto</h1>

                            <p>Tenés alguna duda? No dudes en ponerte en contacto con nosotros.</p>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    function send_email()
                    {
                         $('#errores').hide();
                        $('#btnSave').html('Enviando consulta').show();
                        $.ajax({
                            url: "web/send",
                            type: "POST",
                            data: $('#form_contact').serialize(),
                            dataType: "JSON",
                           
                            success: function (data)
                            {
                                if (data.status == false) {
                                    $('#errores').show();
                                    $('#errores').html('');
                                    $('#errores').html(data.errores);
                                    $('#btnSave').html('Enviar').show();
                                } else {
                                    location.href="<?php echo site_url('web/contacto_gracias') ?>";
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown)
                            {
                                alert('Error al enviar!');
                                alert(jqXHR);
                                alert(textStatus);
                                alert(errorThrown);
                            }

                        });
                    }
                </script>
                <div class="box noborder" id="contact">
                    <h2 class='text-center'>Formulario de contacto</h2>
                    <div id="formulario">

                        <form id="form_contact" onsubmit="return false">

                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="firstname">Nombre (*)</label>
                                            <input type="text" class="form-control" name="firstname" id="firstname">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="lastname">Apellido (*)</label>
                                            <input type="text" class="form-control" name="lastname" id="lastname">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Email (*)</label>
                                            <input type="text" class="form-control" name="email" id="email">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label for="codarea">Cod. Área (*)</label>
                                                <input type="text" class="form-control" name="codarea" id="codarea" placeholder="ej. 011">
                                            </div>
                                            <div class="col-sm-9">
                                                <label for="tel">Teléfono / Celular (*)</label>
                                                <input type="text" class="form-control" name="tel" id="tel" placeholder="ej. 4666 2222">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="message">Consulta (*)</label>
                                            <textarea name="message"  id="message" class="form-control"></textarea>
                                        </div>
                                    </div>
                                   
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" id="btnSave" onclick="send_email()" class="btn btn-primary"><i ></i> Enviar</button>
                                    </div>
                                </div>
                            </div>
                             <div class="alert alert-danger" id="errores" style="display:none;"></div>
                            <!-- /.row -->
                            </form>
                            
                            <div class="row">
                                <hr>
                                <div class="col-sm-4">
                                    <h3><i class="fa fa-map-marker"></i> Dirección</h3>
                                    <p>SARMIENTO 1179,<br>
                                        C.A.B.A. - Buenos Aires<br>
                                        Lunes a Viernes de 9:30 a 18 hrs. </p>
                                </div>
                                <!-- /.col-sm-4 -->
                                <div class="col-sm-4">
                                    <h3><i class="fa fa-phone"></i>Contacto</h3>
                                    <p class="text-muted">Llamanos ahora</p>
                                    <p><strong>(011) 4382-1379 / (011) 6088-7172 <br>
                                            Líneas rotativas</strong><br>
                                        <i class="fa fa-whatsapp" aria-hidden="true"></i>(011) 2777-0109 </p>
                                </div>
                                <!-- /.col-sm-4 -->
                                <div class="col-sm-4">
                                    <h3><i class="fa fa-envelope"></i> Email</h3>
                                    <p class="text-muted">Escribinos, te responderemos a la brevedad.</p>
                                    <ul>
                                        <li><strong><a href="mailto: info@mundobijou.com.ar">info@mundobijou.com.ar</a></strong> </li>
                                    </ul>
                                </div>
                                <!-- /.col-sm-4 --> 
                            </div>
                            <!-- /.row -->

                            <hr>
                            <div id="map">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3283.9685457451997!2d-58.385511785090735!3d-34.60495688045917!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bccac58659e18b%3A0x6d854566ab7406ad!2sSarmiento+1179%2C+C1041AAW+CABA!5e0!3m2!1ses-419!2sar!4v1492125395334" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
                                <br>
                                                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3283.958160662552!2d-58.38580788439207!3d-34.60521948045907!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bccac578e2da45%3A0x6392e9f79a7e1757!2sLibertad+288%2C+C1012AAF+CABA%2C+Argentina!5e0!3m2!1ses!2sar!4v1508793903296" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
                            </div>
                    </div>
                    <!-- /#contact --> 
                </div>
            </div>
            <!-- /.container --> 
        </div>
        <!-- /#content --> 
