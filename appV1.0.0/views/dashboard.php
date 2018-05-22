<!DOCTYPE HTML>
<html>
    <head>
        <title><?= $nombre_sistema ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo base_url(); ?>images/MTKMAIL.ico" type="image/x-icon" rel="shortcut icon">
        <meta name="keywords" content="" />
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
        <!--webfonts-->
        <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
        <!--//webfonts--> 
        <!--animate-->
        <link href="<?php echo base_url(); ?>assets/administrador/css/animate.css" rel="stylesheet" type="text/css" media="all">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/administrador/css/dhtmlxscheduler_flat.css" type="text/css" media="screen" title="no title" charset="utf-8">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/administrador/css/clndr.css" type="text/css" />
        <link href="<?php echo base_url('assets/datatables/dataTables.bootstrap.css') ?>" rel="stylesheet">

        <link href="<?php echo base_url('assets/administrador/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/administrador/css/bootstrap-datepicker.standalone.min.css') ?>" rel="stylesheet">

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/administrador/css/multiple-select.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/administrador/css/bootstrap3-wysihtml5.min.css" type="text/css" />

        <script src="<?php echo base_url('assets/administrador/js/jquery/jquery-2.1.4.min.js') ?>"></script>
        <script src="<?php echo base_url(); ?>assets/administrador/js/modernizr.custom.js"></script>

        <script src="<?php echo base_url(); ?>assets/administrador/js/wow.min.js"></script>
        <script>
            new WOW().init();
        </script>
        <!--//end-animate-->
        <!-- chart -->
        <script src="<?php echo base_url(); ?>assets/administrador/js/Chart.js"></script>
        <!-- //chart -->
        <!--Calender-->

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

        <script type="text/javascript" src="<?php echo base_url(); ?>assets/administrador/js/jquery.form.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/administrador/js/uploads_images.js" type="text/javascript"></script>

        <script src="<?php echo base_url(); ?>assets/administrador/js/multiple-select.js" type="text/javascript"></script>

        <script src="<?php echo base_url(); ?>assets/administrador/editorhtml/bootstrap-wysiwyg" type="text/javascript"></script>

        <!--//Metis Menu -->
    </head> 
    <body class="cbp-spmenu-push">

        <div class="main-content">

            <!--left-fixed -navigation-->
            <div class=" sidebar" role="navigation" style="/*width: 18%*/">
                <div class="navbar-collapse">
                    <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1" style="/*width:17.5%;*/background:#5cb85c">
                        <ul class="nav" id="side-menu">
                            <?php echo $Mymenu; ?>
                        </ul>
                        <!-- //sidebar-collapse -->
                    </nav>
                </div>
            </div>
            <!--left-fixed -navigation-->

            <!-- header-starts -->
            <div class="sticky-header header-section ">
                <div class="header-left">
                    <!--toggle button start-->
                    <button id="showLeftPush"><i class="fa fa-bars"></i></button>
                    <!--toggle button end-->
                    <!--logo -->
                    <div class="logo" style="background: #5cb85c">
                        <a href="#">
                            <h1><?= $nombre_sistema ?></h1>
                            <span>administrador</span>
                        </a>
                    </div>
                    <!--//logo-->
                    <!--search-box-->
                    <div class="search-box">
                        <img src="<?= base_url() ?>assets/administrador/images/ring-alt.gif" style="display: none;width: 50px" id="loader2"/> 
                    </div><!--//end-search-box-->
                    <div class="clearfix"> </div>
                </div>
                <div class="header-right">
                    <div class="profile_details_left"><!--notifications of menu start -->
                        <ul class="nofitications-dropdown">

                            <div class="clearfix"> </div>
                    </div>
                    <!--notification menu end -->
                    <div class="profile_details">		
                        <ul>
                            <li class="dropdown profile_details_drop">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <div class="profile_img">	
                                        <span class="prfil-img"><img src="<?= base_url() . $imagen_user ?>" alt="" style="width: 50px;height: 50px"> </span> 
                                        <div class="user-name">
                                            <p><?= $usuarioactual ?></p>
                                            <span><?= $rol_usuarioactual ?></span>
                                        </div>
                                        <i class="fa fa-angle-down lnr"></i>
                                        <i class="fa fa-angle-up lnr"></i>
                                        <div class="clearfix"></div>	
                                    </div>	
                                </a>
                                <ul class="dropdown-menu drp-mnu">
                                    <!--<li> <a href="#"><i class="fa fa-cog"></i> Settings</a> </li> 
                                    <li> <a href="#"><i class="fa fa-user"></i> Profile</a> </li> -->
                                    <li> <a href="<?php echo site_url('verifylogin/logout') ?>"><i class="fa fa-sign-out"></i> Logout</a> </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"> </div>				
                </div>
                <div class="clearfix"> </div>	
            </div>
            <!-- //header-ends -->
            <div id="page-wrapper" style="/*margin-left: 18%*/">
                <div class="main-page">
                    <?php if ($this->uri->segment(1) == "turnos"): ?> <div class="row-one" style="height: 1200px"> <?php else: ?><div class="row-one"><?php endif; ?>

                            <?php echo $output; ?>

                        </div>
                    </div>
                </div>

                <!--footer-->
                <div class="footer">
                    <p>&copy; <?= date("Y"); ?> <?= $nombre_sistema ?>. </p>
                </div>
                <!--//footer-->
            </div>
            <!-- Classie -->


            <div id="loader" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content"> 
                        <div class="modal-header">
                            <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>-->
                            <h4 class="modal-titlex" id="mySmallModalLabel">Trabajando por favor espere...</h4> 
                        </div> 
                        <div class="modal-body" style="text-align: center"> 

                            <img src="<?= base_url() ?>assets/administrador/images/ring-alt.gif"/> 

                        </div>
                    </div>
                </div>
            </div>
            <script>
            (function () {
                var proxied = window.XMLHttpRequest.prototype.send;
                window.XMLHttpRequest.prototype.send = function () {
                    console.log(arguments);
                    $('#loader2').show();
                    ajaxindicatorstart('Cargando datos. Por favor espere..');
                    //Here is where you can add any code to process the request. 
                    //If you want to pass the Ajax request object, pass the 'pointer' below
                    var pointer = this
                    var intervalId = window.setInterval(function () {
                        if (pointer.readyState != 4) {
                            return;
                        }
                        console.log(pointer.responseText);
                        //Here is where you can add any code to process the response.
                        //If you want to pass the Ajax request object, pass the 'pointer' below
                        ajaxindicatorstop();
                        $('#loader2').hide();
                        clearInterval(intervalId);

                    }, 1);//I found a delay of 1 to be sufficient, modify it as you need.
                    return proxied.apply(this, [].slice.call(arguments));
                };

            })();
            </script>

            <script src="<?php echo base_url(); ?>assets/administrador/js/classie.js"></script>
            <script>
            var menuLeft = document.getElementById('cbp-spmenu-s1'),
                    showLeftPush = document.getElementById('showLeftPush'),
                    body = document.body;

            showLeftPush.onclick = function () {
                //alert(this.toSource())
                classie.toggle(this, 'active');
                classie.toggle(body, 'cbp-spmenu-push-toright');
                classie.toggle(menuLeft, 'cbp-spmenu-open');
                disableOther('showLeftPush');
            };


            function disableOther(button) {
                alert(1)
                if (button !== 'showLeftPush') {
                    classie.toggle(showLeftPush, 'disabled');
                }
            }
            var base_url = '<?= base_url() ?>';
            var base_url_upload = '<?= $url_images ?>';



            </script>
            <!--scrolling js-->
            <script src="<?php echo base_url(); ?>assets/administrador/js/jquery.nicescroll.js"></script>
            <script src="<?php echo base_url(); ?>assets/administrador/js/scripts.js"></script>
            <script src="<?php echo base_url(); ?>assets/administrador/js/bootstrap-datepicker.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/administrador/js/bootstrap-datetimepicker.min.js"></script>
            <!--//scrolling js-->
            <!-- include summernote css/js-->
            <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.css" rel="stylesheet">
            <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.js"></script>

            <script>

            $(document).ready(function () {
                $('#descripcion').summernote({
                    height: 300, // set editor height
                    minHeight: null, // set minimum height of editor
                    maxHeight: null, // set maximum height of editor
                    focus: true, // set focus to editable area after initializing summernote
                    toolbar: [
                        //[groupname, [button list]]

                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['view', ['codeview']],
                        ['table', ['table']],
                        ['picture', ['picture']]
                    ],
                    callbacks: {
                        onImageUpload: function (image) {
                            uploadImage(image[0]);
                        }
                    }
                });
            });

            function ajaxindicatorstart(text)
            {
                if (jQuery('body').find('#resultLoading').attr('id') != 'resultLoading') {
                    jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="<?= base_url() ?>assets/administrador/images/ajax-loader_1.gif"><div>' + text + '</div></div><div class="bg"></div></div>');
                }

                jQuery('#resultLoading').css({
                    'width': '100%',
                    'height': '100%',
                    'position': 'fixed',
                    'z-index': '10000000',
                    'top': '0',
                    'left': '0',
                    'right': '0',
                    'bottom': '0',
                    'margin': 'auto'
                });

                jQuery('#resultLoading .bg').css({
                    'background': '#000000',
                    'opacity': '0.7',
                    'width': '100%',
                    'height': '100%',
                    'position': 'absolute',
                    'top': '0'
                });

                jQuery('#resultLoading>div:first').css({
                    'width': '250px',
                    'height': '75px',
                    'text-align': 'center',
                    'position': 'fixed',
                    'top': '0',
                    'left': '0',
                    'right': '0',
                    'bottom': '0',
                    'margin': 'auto',
                    'font-size': '16px',
                    'z-index': '10',
                    'color': '#ffffff'

                });

                jQuery('#resultLoading .bg').height('100%');
                jQuery('#resultLoading').fadeIn(300);
                jQuery('body').css('cursor', 'wait');
            }
            function ajaxindicatorstop()
            {
                jQuery('#resultLoading .bg').height('100%');
                jQuery('#resultLoading').fadeOut(300);
                jQuery('body').css('cursor', 'default');
            }

            </script>

    </body>
</html>