<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Recepcion de pedido</title>
        <!-- 
        The style block is collapsed on page load to save you some scrolling.
        Postmark automatically inlines all CSS properties for maximum email client 
        compatibility. You can just update styles here, and Postmark does the rest.
        -->
        <style type="text/css" rel="stylesheet" media="all">
            /* Base ------------------------------ */

            *:not(br):not(tr):not(html) {
                font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                box-sizing: border-box;
            }

            body {
                width: 100% !important;
                height: 100%;
                margin: 0;
                line-height: 1.4;
                background-color: #F2F4F6;
                color: #74787E;
                -webkit-text-size-adjust: none;
            }

            p,
            ul,
            ol,
            blockquote {
                line-height: 1.4;
                text-align: left;
            }

            a {
                color: #3869D4;
            }

            a img {
                border: none;
            }

            td {
                word-break: break-word;
            }
            /* Layout ------------------------------ */

            .email-wrapper {
                width: 100%;
                margin: 0;
                padding: 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
                background-color: #F2F4F6;
            }

            .email-content {
                width: 100%;
                margin: 0;
                padding: 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
            }
            /* Masthead ----------------------- */

            .email-masthead {
                padding: 25px 0;
                text-align: center;
            }

            .email-masthead_logo {
                width: 94px;
            }

            .email-masthead_name {
                font-size: 16px;
                font-weight: bold;
                color: #bbbfc3;
                text-decoration: none;
                text-shadow: 0 1px 0 white;
            }
            /* Body ------------------------------ */

            .email-body {
                width: 100%;
                margin: 0;
                padding: 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
                border-top: 1px solid #EDEFF2;
                border-bottom: 1px solid #EDEFF2;
                background-color: #FFFFFF;
            }

            .email-body_inner {
                width: 100%;
                margin: 0 auto;
                padding: 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
                background-color: #FFFFFF;
            }

            .email-footer {
                width: 100%;
                margin: 0 auto;
                padding: 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
                text-align: center;
            }

            .email-footer p {
                color: #AEAEAE;
            }

            .body-action {
                width: 100%;
                margin: 30px auto;
                padding: 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
                text-align: center;
            }

            .body-sub {
                margin-top: 25px;
                padding-top: 25px;
                border-top: 1px solid #EDEFF2;
            }

            .content-cell {
                padding: 35px;
            }

            .preheader {
                display: none !important;
                visibility: hidden;
                mso-hide: all;
                font-size: 1px;
                line-height: 1px;
                max-height: 0;
                max-width: 0;
                opacity: 0;
                overflow: hidden;
            }
            /* Attribute list ------------------------------ */

            .attributes {
                margin: 0 0 21px;
            }

            .attributes_content {
                background-color: #EDEFF2;
                padding: 16px;
            }

            .attributes_item {
                padding: 0;
            }
            /* Related Items ------------------------------ */

            .related {
                width: 100%;
                margin: 0;
                padding: 25px 0 0 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
            }

            .related_item {
                padding: 10px 0;
                color: #74787E;
                font-size: 15px;
                line-height: 18px;
            }

            .related_item-title {
                display: block;
                margin: .5em 0 0;
            }

            .related_item-thumb {
                display: block;
                padding-bottom: 10px;
            }

            .related_heading {
                border-top: 1px solid #EDEFF2;
                text-align: center;
                padding: 25px 0 10px;
            }
            /* Discount Code ------------------------------ */

            .discount {
                width: 100%;
                margin: 0;
                padding: 24px;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
                background-color: #EDEFF2;
                border: 2px dashed #9BA2AB;
            }

            .discount_heading {
                text-align: center;
            }

            .discount_body {
                text-align: center;
                font-size: 15px;
            }
            /* Social Icons ------------------------------ */

            .social {
                width: auto;
            }

            .social td {
                padding: 0;
                width: auto;
            }

            .social_icon {
                height: 20px;
                margin: 0 8px 10px 8px;
                padding: 0;
            }
            /* Data table ------------------------------ */

            .purchase {
                width: 100%;
                margin: 0;
                padding: 35px 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
            }

            .purchase_content {
                width: 100%;
                margin: 0;
                padding: 25px 0 0 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
            }

            .purchase_item {
                padding: 10px 0;
                color: #74787E;
                font-size: 15px;
                line-height: 18px;
            }

            .purchase_heading {
                padding-bottom: 8px;
                border-bottom: 1px solid #EDEFF2;
            }

            .purchase_heading p {
                margin: 0;
                color: #9BA2AB;
                font-size: 12px;
            }

            .purchase_footer {
                padding-top: 15px;
                border-top: 1px solid #EDEFF2;
            }

            .purchase_total {
                margin: 0;
                text-align: right;
                font-weight: bold;
                color: #2F3133;
            }

            .purchase_total--label {
                padding: 0 15px 0 0;
            }
            /* Utilities ------------------------------ */

            .align-right {
                text-align: right;
            }

            .align-left {
                text-align: left;
            }

            .align-center {
                text-align: center;
            }
            /*Media Queries ------------------------------ */

            @media only screen and (max-width: 600px) {
                .email-body_inner,
                .email-footer {
                    width: 100% !important;
                }
            }

            @media only screen and (max-width: 500px) {
                .button {
                    width: 100% !important;
                }
            }
            /* Buttons ------------------------------ */

            .button {
                background-color: #3869D4;
                border-top: 10px solid #3869D4;
                border-right: 18px solid #3869D4;
                border-bottom: 10px solid #3869D4;
                border-left: 18px solid #3869D4;
                display: inline-block;
                color: #FFF;
                text-decoration: none;
                border-radius: 3px;
                box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
                -webkit-text-size-adjust: none;
            }

            .button--green {
                background-color: #22BC66;
                border-top: 10px solid #22BC66;
                border-right: 18px solid #22BC66;
                border-bottom: 10px solid #22BC66;
                border-left: 18px solid #22BC66;
            }

            .button--red {
                background-color: #FF6136;
                border-top: 10px solid #FF6136;
                border-right: 18px solid #FF6136;
                border-bottom: 10px solid #FF6136;
                border-left: 18px solid #FF6136;
            }
            /* Type ------------------------------ */

            h1 {
                margin-top: 0;
                color: #2F3133;
                font-size: 19px;
                font-weight: bold;
                text-align: left;
            }

            h2 {
                margin-top: 0;
                color: #2F3133;
                font-size: 16px;
                font-weight: bold;
                text-align: left;
            }

            h3 {
                margin-top: 0;
                color: #2F3133;
                font-size: 14px;
                font-weight: bold;
                text-align: left;
            }

            p {
                margin-top: 0;
                color: #74787E;
                font-size: 16px;
                line-height: 1.5em;
                text-align: left;
            }

            p.sub {
                font-size: 12px;
            }

            p.center {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <span class="preheader">Se ha realizado un nuevo pedido en Mundo  Bijou.</span>
        <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center">
                    <table class="email-content" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="email-masthead"  style="background:#222222;color: #fff">
                                <a href="<?php echo site_url('index'); ?>" class="email-masthead_name" style="color:#fff">
                                    Mundo  Bijou
                                </a>
                            </td>
                        </tr>
                        <!-- Email Body -->
                        <tr>
                            <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                                <table class="email-body_inner" align="center" width="100%" cellpadding="0" cellspacing="0">
                                    <tr><td class="content-cell" align="center"> <img src="<?php echo base_url(); ?>/assets/web/img/logo.png" alt="Mundo Bijou"></td></tr>
                                    <!-- Body content -->
                                    <tr>
                                        <td class="content-cell">
                                            <h1>Se ha realizado un nuevo pedido en Mundo  Bijou</h1>
                                            <p>Este es un resumen del pedido.</p>
                                            <table class="attributes" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td class="attributes_content">
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td class="attributes_item">#<?= str_pad($pedido->id, 4, 0, STR_PAD_LEFT) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="attributes_item">Total: $<?php echo number_format($pedido->total, 2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="attributes_item">Fecha: <?= date("d/m/Y", strtotime($pedido->fecha_insert)) ?></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="attributes_item">Forma de pago: <?= $pedido->forma_pago ?></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="attributes_item">Cliente:  <?= $cliente->nombre_apellido ?><br />

                                                                    Doc.: <?= $cliente->numero_doc ?><br />

                                                                    Tipo cuenta: <?= ($cliente->tipo_cliente == 1) ? 'Mayorista' : 'Revendedor' ?><br />

                                                                    Email: <?= $cliente->email ?><br />

                                                                    Telfono: (<?= $cliente->codigo_de_area ?>)<?= $cliente->telefono ?><br />
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="attributes_item"><strong>Direcci&oacute;n de entrega</strong><br />

                                                                    Calle: <?= $pedido->calle ?> N&deg;: <?= $pedido->altura ?> - <?= $pedido->piso ?>&nbsp;<?= $pedido->dpto ?><br />
                                                                    Barrio: <?= $pedido->barrio ?> - Manzana <?= $pedido->manzana ?> - Lote <?= $pedido->lote ?> - CP <?= $pedido->codigo_postal ?><br />
                                                                    Provincia:<?= $extra['provincias'][$pedido->provincia] ?> - Localidad: <?= $extra['localidades'][$pedido->localidad] ?><br />
                                                                    Expreso: <?= $pedido->expreso ?><br />
                                                                    Comentario: <?= $pedido->comentario ?>
                                                                </td>
                                                            </tr>

                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>

                                            <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td align="center">

                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td align="center">
                                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                                        <tr>
                                                                            <td>
                                                                                <a href="<?php echo site_url('pedidos/add/' . $pedido->id); ?>" class="button button--green" target="_blank">Ver Pedido</a>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table class="purchase" width="100%" cellpadding="0" cellspacing="0">

                                                <tr>
                                                    <td colspan="2">
                                                        <table class="purchase_content" width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td  class="purchase_heading">Imagen</td>
                                                                <td  class="purchase_heading">Producto</td>
                                                                <td class="purchase_heading">Cantidad</td>
                                                                <td class="purchase_heading">Medida</td>
                                                                <td class="purchase_heading">Talle</td>
                                                                <td class="purchase_heading">Color</td>
                                                                <td class="purchase_heading">$ Unitario</td>

                                                                <td class="purchase_heading">Subtotal</td>
                                                            </tr>
                                                            <?php foreach ($items as $item): ?>
                                                                <tr>
                                                                    <td><a href="<?php echo site_url($item->my_url); ?>"> <img src="<?= base_url(); ?>uploads/<?= $item->imagen ?>" alt="<?= $item->slug; ?>" style="width: 50px"> </a></td>

                                                                    <td><a href="<?php echo site_url($item->my_url); ?>" class="black"><?php echo $item->nombre; ?></a></td>

                                                                    <td align="left"><?php echo $item->cantidad; ?></td>

                                                                    <td><?= (!empty($item->medida_id)) ? $extra['medidas'][$item->medida_id] : ''; ?></td>

                                                                    <td><?= (!empty($item->talle_id)) ? $extra['talles'][$item->talle_id] : ''; ?></td>

                                                                    <td>

                                                                        <div style="background: <?= (!empty($item->color_id)) ? $extra['colores'][$item->color_id] : '' ?> !important;">
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        </div>
                                                                    </td>
                                                                    <td>$ <?php echo number_format($item->precio_unitario, 2); ?></td>

                                                                    <td align="center">$ <?php echo number_format($item->subtotal, 2); ?></td>

                                                                </tr>
                                                            <?php endforeach; ?>

                                                            <?php $cantidad_por_articulo = array(); ?>

                                                            <?php $cantidad_total = 0; ?>

                                                            <?php foreach ($items as $item): ?>

                                                                <?php $cantidad_por_articulo[$item->nombre] ++; ?>    

                                                                <?php $cantidad_total = $cantidad_total + $item->cantidad; ?>   

                                                            <?php endforeach; ?>

                                                            <tr>
                                                                <td class="purchase_footer" valign="middle" colspan="7">
                                                                    <p class="purchase_total purchase_total--label">Cantidad productos</p>
                                                                </td>
                                                                <td class="purchase_footer" valign="middle">
                                                                    <p class="purchase_total"><?php echo $cantidad_total; ?></p>
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td class="purchase_footer" valign="middle" colspan="7">
                                                                    <p class="purchase_total purchase_total--label">Cantidad art.</p>
                                                                </td>
                                                                <td class="purchase_footer" valign="middle">
                                                                    <p class="purchase_total"><?php echo count($cantidad_por_articulo); ?></p>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="purchase_footer" valign="middle" colspan="7">
                                                                    <p class="purchase_total purchase_total--label">Subtotal</p>
                                                                </td>
                                                                <td class="purchase_footer" valign="middle">
                                                                    <p class="purchase_total">$<?php echo number_format($pedido->subtotal, 2); ?></p>
                                                                </td>
                                                            </tr>

                                                            <?php
                                                            $d = (!empty($pedido->porcentaje)) ? $pedido->porcentaje : 0;

                                                            $descuento = ($pedido->subtotal * $d) / 100;
                                                            ?> 


                                                            <tr>
                                                                <td class="purchase_footer" valign="middle" colspan="7">
                                                                    <p class="purchase_total purchase_total--label">Descuento</p>
                                                                </td>
                                                                <td class="purchase_footer" valign="middle">
                                                                    <p class="purchase_total">$ <?php echo number_format($descuento, 2, '.', ''); ?></p>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="purchase_footer" valign="middle" colspan="7">
                                                                    <p class="purchase_total purchase_total--label">Total</p>
                                                                </td>
                                                                <td class="purchase_footer" valign="middle">
                                                                    <p class="purchase_total">$<?php echo number_format($pedido->total, 2); ?></p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <p>Si ten&eacute;s alguna pregunta sobre tu pedido por favor mandanos un mail a info@mundobijou.com.ar, llamanos al +54 011-6088-7172 / +54 011-4382-1379 o enviarnos un whatsapp al 11-2777-0109. Te pedimos que tengas a mano el n&uacute;mero de pedido as&iacute; te podemos responder mas r&aacute;pido. </p>

                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="content-cell" align="center" style="background:#222222;color: #fff">
                                            <p class="sub align-center">&copy; 2017 Mundo Bijou. All rights reserved.</p>
                                            <p class="sub align-center">
                                                MUNDO BIJOU
                                                <br>SARMIENTO 1179
                                                    <br>C.A.B.A. - Buenos Aires
                                                        </p>
                                                        </td>
                                                        </tr>
                                                        </table>
                                                        </td>
                                                        </tr>
                                                        </table>
                                                        </td>
                                                        </tr>
                                                        </table>
                                                        </body>
                                                        </html>