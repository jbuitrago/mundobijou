<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
    <head>
        <title><?= $nombre_sistema ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="Novus Admin Panel Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
              SmartPhone Compatible web template, free WebDesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <!-- Bootstrap Core CSS -->
        <link href="<?php echo base_url(); ?>assets/administrador/css/bootstrap.css" rel='stylesheet' type='text/css' />
        <!-- Custom CSS -->
        <link href="<?php echo base_url(); ?>assets/administrador/css/style.css" rel='stylesheet' type='text/css' />
        <!-- font CSS -->
        <!-- font-awesome icons -->
        <link href="<?php echo base_url(); ?>assets/administrador/css/font-awesome.css" rel="stylesheet"> 
        <!-- //font-awesome icons -->
        <!-- js-->
        <script src="<?php echo base_url('assets/administrador/js/jquery/jquery-2.1.4.min.js') ?>"></script>
        <script src="<?php echo base_url(); ?>assets/administrador/js/modernizr.custom.js"></script>
        <!--webfonts-->
        <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
        <!--//webfonts--> 
        <!--animate-->
        <link href="<?php echo base_url(); ?>assets/administrador/css/animate.css" rel="stylesheet" type="text/css" media="all">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/administrador/css/dhtmlxscheduler_flat.css" type="text/css" media="screen" title="no title" charset="utf-8">
        <script src="<?php echo base_url(); ?>assets/administrador/js/wow.min.js"></script>
        <script>
            new WOW().init();
        </script>
        <!--//end-animate-->
        <!-- chart -->
        <script src="<?php echo base_url(); ?>assets/administrador/js/Chart.js"></script>
        <!-- //chart -->
        <!--Calender-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/administrador/css/clndr.css" type="text/css" />
        <link href="<?php echo base_url('assets/datatables/dataTables.bootstrap.css') ?>" rel="stylesheet">
        <script src="<?php echo base_url(); ?>assets/administrador/js/underscore-min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/administrador/js/moment-2.2.1.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/administrador/js/clndr.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/administrador/js/site.js" type="text/javascript"></script>
        <!--End Calender-->
        <!-- Metis Menu -->
        <script src="<?php echo base_url(); ?>assets/administrador/js/metisMenu.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/administrador/js/custom.js"></script>
        <link href="<?php echo base_url(); ?>assets/administrador/css/custom.css" rel="stylesheet">



        <script src="<?php echo base_url('assets/administrador/js/bootstrap.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.js') ?>"></script>

        <script src="<?php echo base_url('assets/administrador/js/js_mtk.js') ?>"></script>
        <!--//Metis Menu -->
    </head> 
    <body class="cbp-spmenu-push">

        <div class="main-content">

            <div id="page-wrapperx">
                <div class="main-page">
                    <div class="row-one" style="height: 800px">

                        <div id="page-wrapperx">
                            <div class="main-page login-page ">
                                <h3 class="title1">Login</h3>
                                <div class="widget-shadow">
                                    <div class="login-top">
                                        <h4>Bienvenido a <?= $nombre_sistema ?> »</a> </h4>
                                    </div>
                                    <div class="login-body">
                                        <?php echo form_open('verifylogin'); ?>
                                            <input type="text" class="user" name="username" placeholder="Ingrese su usuario" required="">
                                            <input type="password" name="password" class="lock" placeholder="password"  required="">
                                            <input type="submit" name="Sign In" value="Ingresar">
                                            <!--<div class="forgot-grid">
                                                <label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i>Remember me</label>
                                                <div class="forgot">
                                                    <a href="#">forgot password?</a>
                                                </div>
                                                <div class="clearfix"> </div>
                                            </div>-->
                                            <?php echo validation_errors(); ?>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!--footer-->
            <div class="footer">
                <p>&copy; <?= date("Y"); ?>. All Rights Reserved </p>
            </div>
            <!--//footer-->
        </div>
        <!-- Classie -->
        <script src="<?php echo base_url(); ?>assets/administrador/js/classie.js"></script>
        <script>
            var menuLeft = document.getElementById('cbp-spmenu-s1'),
                    showLeftPush = document.getElementById('showLeftPush'),
                    body = document.body;

            showLeftPush.onclick = function () {
                classie.toggle(this, 'active');
                classie.toggle(body, 'cbp-spmenu-push-toright');
                classie.toggle(menuLeft, 'cbp-spmenu-open');
                disableOther('showLeftPush');
            };


            function disableOther(button) {
                if (button !== 'showLeftPush') {
                    classie.toggle(showLeftPush, 'disabled');
                }
            }
        </script>
        <!--scrolling js-->
        <script src="<?php echo base_url(); ?>assets/administrador/js/jquery.nicescroll.js"></script>
        <script src="<?php echo base_url(); ?>assets/administrador/js/scripts.js"></script>
        <!--//scrolling js-->

    </body>
</html>