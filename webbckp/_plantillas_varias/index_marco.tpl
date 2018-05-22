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
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme stylesheet -->
    <link href="css/estilos.css" rel="stylesheet" id="theme-stylesheet">

    <!-- Custom stylesheet - for your changes -->
    <link href="css/custom.css" rel="stylesheet">

    <!-- Responsivity for older IE -->
    <script src="js/respond.min.js"></script>

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.png">
    <!-- owl carousel css -->
    <link href="css/animate.css" rel="stylesheet" type="text/css">
    <link href="css/owl.carousel.css" rel="stylesheet">
    <link href="css/owl.theme.css" rel="stylesheet">
    
   <!-- CHECKBOX -->
   <link href="css/checkbox.css" rel="stylesheet" type="text/css">
</head>

<body>
  
{template tpl="header"}
{template section}
{template tpl="modal_login"}
{template tpl="modal_vistarapida"}
{template tpl="footer"}






<!-- #### JAVASCRIPT FILES ### -->

    
    <script type="text/javascript" src="js/jquery-latest.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>

    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/front.js"></script>

    

    <!-- owl carousel -->
    <script src="js/owl.carousel.min.js"></script>

    
	<script>
	$('.carousel-sync').on('slide.bs.carousel', function(ev) {
  	var dir = ev.direction == 'right' ? 'prev' : 'next';
	$('.carousel-sync').not('.sliding').addClass('sliding').carousel(dir);
});
$('.carousel-sync').on('slid.bs.carousel', function(ev) {
	$('.carousel-sync').removeClass('sliding');
});
	</script>
	<script>
	$('#btnCloseVideo').click(function(){
	$('#ModalVideo').modal('toggle');
});
	</script>
</body>
</html>
