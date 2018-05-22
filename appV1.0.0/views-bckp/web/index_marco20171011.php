<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="robots" content="all,follow">
        <meta name="googlebot" content="index,follow,snippet,archive">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Mundo Bijou</title>

        <meta name="keywords" content="">

        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:400,500,700" rel="stylesheet">
        <!-- Bootstrap and Font Awesome css -->
        <link href="<?= base_url(); ?>assets/web/css/font-awesome.css" rel="stylesheet">
        <link href="<?= base_url(); ?>assets/web/css/bootstrap.min.css" rel="stylesheet">

        <!-- Theme stylesheet -->
        <link href="<?= base_url(); ?>assets/web/css/estilos.css" rel="stylesheet" id="theme-stylesheet">

        <!-- Custom stylesheet - for your changes -->
        <link href="<?= base_url(); ?>assets/web/css/custom.css" rel="stylesheet">

        <!-- Responsivity for older IE -->
        <script src="<?= base_url(); ?>assets/web/js/respond.min.js"></script>

        <!-- Favicon -->
        <link rel="shortcut icon" href="favicon.png">
        <!-- owl carousel css -->
        <link href="<?= base_url(); ?>assets/web/css/animate.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url(); ?>assets/web/css/owl.carousel.css" rel="stylesheet">
        <link href="<?= base_url(); ?>assets/web/css/owl.theme.css" rel="stylesheet">

        <!-- CHECKBOX -->
        <link href="<?= base_url(); ?>assets/web/css/checkbox.css" rel="stylesheet" type="text/css">
        <style>
            /*.field_title{font-size: 13px;font-family:Arial;width: 300px;margin-top: 10px}*/
            .form_error{font-size: 13px;font-family:Arial;color:red;font-style:italic}

        </style>
    </head>

    <body>

        <div id='loading-indicator' style="position: fixed; left: 50%; top: 50%; display: none;">
            <img src="<?= base_url(); ?>assets/web/img/loader.gif" id="loading-indicator"/>
        </div>

        <?= $header ?>

        <?= $section ?>

        <?= $modal_login ?>

        <?= $modal_vistarapida ?>

        <?= $footer ?>

        <!-- #### JAVASCRIPT FILES ### -->

        <script type="text/javascript" src="<?= base_url(); ?>assets/web/js/jquery-latest.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/web/js/jquery-ui.js"></script>

        <script src="<?= base_url(); ?>assets/web/js/bootstrap.min.js"></script>
        <script src="<?= base_url(); ?>assets/web/js/jquery.cookie.js"></script>
        <script src="<?= base_url(); ?>assets/web/js/front.js"></script>

        <!-- owl carousel -->
        <script src="<?= base_url(); ?>assets/web/js/owl.carousel.min.js"></script>

        <script>
            $('.carousel-sync').on('slide.bs.carousel', function (ev) {
                var dir = ev.direction == 'right' ? 'prev' : 'next';
                $('.carousel-sync').not('.sliding').addClass('sliding').carousel(dir);
            });
            $('.carousel-sync').on('slid.bs.carousel', function (ev) {
                $('.carousel-sync').removeClass('sliding');
            });
        </script>
        <script>
            $('#btnCloseVideo').click(function () {
                $('#ModalVideo').modal('toggle');
            });
        </script>
        <script>

            // Fill modal with content from link href
            $("#product-quick-view-modal").on("show.bs.modal", function (e) {

                var link = $(e.relatedTarget);

                var url = link.attr("my-link");

                $('#loading-indicator').show();

                $("#product-quick-view-modal").load(url, function () {
                    productDetailSizes();
                    productDetailGallery(4000);
                    productQuickViewGallery();
                    //$('#loading-indicator').hide();
                });
            });

            // Fill modal with content from link href
            $("#combo-quick-view-modal").on("show.bs.modal", function (e) {

                var link = $(e.relatedTarget);

                var url = link.attr("my-link");

                $('#loading-indicator').show();

                $("#combo-quick-view-modal").load(url, function () {
                    productDetailSizes();
                    productDetailGallery(4000);
                    productQuickViewGallery();

                    //$('#loading-indicator').hide();
                });
            });

        </script>
        <script>

            $(document).ready(function () {

                $('[name="provincia"]').change(function () {

                    var provincia_id = $('[name="provincia"]').val();

                    $('[name="localidad"]')
                            .find('option')
                            .remove();

                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url('web/get_localidades/') ?>/" + provincia_id,
                        success: function (data) {
                            // Parse the returned json data
                            var opts = $.parseJSON(data);
                            // Use jQuery's each to iterate over the opts value
                            $.each(opts, function (i, d) {

                                if (LOCALIDAD != '') {

                                    if (LOCALIDAD == d.id) {

                                        $('[name="localidad"]').append('<option selected value="' + d.id + '">' + d.nombre + '</option>');
                                    } else {
                                        $('[name="localidad"]').append('<option value="' + d.id + '">' + d.nombre + '</option>');
                                    }
                                } else {
                                    // You will need to alter the below to get the right values from your json object.  Guessing that d.id / d.modelName are columns in your carModels data
                                    $('[name="localidad"]').append('<option value="' + d.id + '">' + d.nombre + '</option>');
                                }
                            });
                        }
                    });

                });

                $('input[type="checkbox"]').on('change', function () {
                    $('input[name="' + this.name + '"]').not(this).prop('checked', false);
                });

                $('[name="provincia"]').trigger('change');

                // process the form
                $('#form_login').submit(function (event) {

                    var formData = {
                        'usuario': $('[name=usuario]').val(),
                        'password': $('[name=password]').val(),
                        'come_from': $('[name=come_from]').val(),
                    };

                    //alert(formData.toSource());
                    // process the form
                    $.ajax({
                        type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                        url: 'process_login', // the url where we want to POST
                        data: formData, // our data object
                        dataType: 'json', // what type of data do we expect back from the server
                        encode: true
                    }).done(function (data) {
                        if (data.status == true) {
                            document.location = "<?php echo base_url(); ?>/" + $('input[name=come_from]').val();
                        } else {
                            $("#errores_login").html(data.errores);
                        }
                        // here we will handle errors and validation messages
                    });
                });
            });

        </script>
        <script>

            function agregar_al_carrito(precio, cantidad_a_descontar) {

                $('[name="price"]').val(precio);

                $('[name="cantidad_a_descontar"]').val(cantidad_a_descontar);

                if (cantidad_a_descontar == 1) {

                    $('[name="quantity"]').val($('[name="quantity_a"]').val());
                }

                if (cantidad_a_descontar == 10) {

                    $('[name="quantity"]').val($('[name="quantity_b"]').val());
                }

                // process the form
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: "<?php echo site_url('cart_add'); ?>", // the url where we want to POST
                    data: $("#form_producto").serialize(), // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true
                }).done(function (data) {
                    if (data.status == true) {
                        document.location = "<?php echo site_url('carro'); ?>";
                    } else {
                        $("#errores_carro").html(data.errores);
                    }
                    // here we will handle errors and validation messages
                });

            }

            function stopVideo() {

                // $('.YVideo').contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');

            }

            $(document).ajaxSend(function (event, request, settings) {
                $('#loading-indicator').show();
            });

            $(document).ajaxComplete(function (event, request, settings) {
                $('#loading-indicator').hide();
            });

            // Execute this after the site is loaded.
            $(function () {

                $('.child').hide();
                $('.parent').click(function () {
                    $(this).siblings('.parent').find('ul').slideUp();
                    $(this).find('ul').slideToggle();
                });

                $('.active').find('.child').show();

            });

        </script>
    </body>
</html>
