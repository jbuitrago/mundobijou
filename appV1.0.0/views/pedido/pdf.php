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


            table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                margin-bottom: 20px;
                border: 1px solid #FFFFFF;
            }

            table th,
            table td {
                padding: 5px;
                background: #EEEEEE;
                text-align: left;
                border: 1px solid #FFFFFF;
            }

            table th {
                white-space: nowrap;        
                font-weight: normal;
            }

            table td {
                text-align: left;
            }

            table td h3{
                color: #57B223;
                font-size: 0.9em;
                font-weight: normal;
                margin: 0 0 0.2em 0;
            }

            table .no {
                color: #FFFFFF;
                font-size: 1.6em;
                background: #57B223;
            }

            table .desc {
                text-align: left;
            }

            table .unit {
                background: #DDDDDD;
            }

            table .qty {
            }

            table .total {
                background: #57B223;
                color: #FFFFFF;
            }

            table td.unit,
            table td.qty,
            table td.total {
                font-size: 0.9em;
            }

            table tbody tr:last-child td {
                border: none;
            }

            table tfoot td {
                padding: 5px 5px;
                background: #FFFFFF;
                border-bottom: none;
                font-size: 0.9em;
                white-space: nowrap; 
                border-top: 1px solid #AAAAAA; 
                border: 1px solid #FFFFFF;
            }

            table tfoot tr:first-child td {
                /*border-top: none; */
            }

            table tfoot tr:last-child td {
                color: #57B223;
                font-size: 0.9em;
                border-top: 1px solid #57B223; 
                border: 1px solid #FFFFFF;

            }

            table tfoot tr td:first-child {
                /* border: none;*/
            }
        </style>
        <link href="<?php echo base_url('assets/administrador/css/invoices/style.css') ?>" rel="stylesheet"></link>
    </head>
    <body>

        <table style="width:100%">

            <tr>
                <td class="email-masthead"  style="background:#222222;color: #fff">
                    <a href="<?php echo site_url('index'); ?>" class="email-masthead_name" style="color:#fff">
                        Mundo  Bijou
                    </a>
                </td>
            </tr>
            <tr><td class="content-cell" align="center"> <img src="<?php echo base_url(); ?>/assets/web/img/logo.png" alt="Mundo Bijou"></td></tr> 
        </table>

        <table  style="width:100%">

            <tr class="form-group">
                <td class="control-label col-md-2" colspan="3"><b>PEDIDO</b></td>
                <td class="col-md-4">
                    <b><?= str_pad($pedido->id, 4, "0", STR_PAD_LEFT); ?></b>
                </td>

            </tr>

            <tr class="form-group">
                <td class="control-label col-md-2">Fecha</td>
                <td class="col-md-4">
                    <?= $pedido->fecha_insert; ?>
                </td>

                <td class="control-label col-md-2">Estado</td>
                <td class="col-md-4">

                    <?php echo $estados_pedido[$pedido->estado]; ?>
                </td>
            </tr>

            <tr class="form-group">
                <td class="control-label col-md-2">Forma pago</td>
                <td class="col-md-4" colspan="4">
                    <?= $pedido->forma_pago; ?>
                </td>
            </tr>

            <tr class="form-group">
                <td class="control-label col-md-2">Cliente</td>
                <td class="col-md-4">
                    <?= $cliente->nombre_apellido ?>
                </td>
                <td class="control-label col-md-2">Doc.</td>
                <td class="col-md-4">

                    <?= $cliente->numero_doc ?>
                </td>
            </tr>

            <tr class="form-group">
                <td class="control-label col-md-2">Tipo cliente</td>
                <td class="col-md-4">

                    <?= ($cliente->tipo_cliente == 1) ? 'Mayorista' : 'Revendedor' ?>
                </td>
                <td class="control-label col-md-2">Email</td>
                <td class="col-md-4">

                    <?= $cliente->email ?>
                </td>
            </tr>

            <tr class="form-group">
                <td class="control-label col-md-2">Cod. Area</td>
                <td class="col-md-4">

                    <?= $cliente->codigo_de_area ?>
                </td>
                <td class="control-label col-md-2">Telefono</td>
                <td class="col-md-4">

                    <?= $cliente->telefono ?>
                </td>
            </tr>

            <tr class="form-group">
                <td class="control-label col-md-2">Calle</td>
                <td class="col-md-4">
                    <?= $pedido->calle; ?>
                </td>

                <td class="control-label col-md-2">Altura</td>
                <td class="col-md-4">
                    <?= $pedido->altura; ?>
                </td>
            </tr>
            <tr class="form-group">
                <td class="control-label col-md-2">Piso</td>
                <td class="col-md-4">
                    <?= $pedido->piso; ?>
                </td>

                <td class="control-label col-md-2">Dpto</td>
                <td class="col-md-4">
                    <?= $pedido->dpto; ?>
                </td>
            </tr>
            <tr class="form-group">
                <td class="control-label col-md-2">Barrio</td>
                <td class="col-md-4">
                    <?= $pedido->barrio; ?>
                    </div>

                    <td class="control-label col-md-2">Manzana</td>
                    <td class="col-md-4">
                        <?= $pedido->manzana; ?>
                    </td>
            </tr>
            <tr class="form-group">
                <td class="control-label col-md-2">Lote</td>
                <td class="col-md-4">
                    <?= $pedido->lote; ?>
                </td>

                <td class="control-label col-md-2">Codigo postal</td>
                <td class="col-md-4">
                    <?= $pedido->codigo_postal; ?>
                </td>
            </tr>
            <tr class="form-group">
                <td class="control-label col-md-2">Provincia</td>
                <td class="col-md-4">
                    <?php echo $provincias[$pedido->provincia]; ?>
                    </div>

                    <td class="control-label col-md-2">Localidad</td>
                    <td class="col-md-4">
                        <?php echo $localidades[$pedido->localidad]; ?>
                    </td>
            </tr>
            <tr class="form-group">
                <td class="control-label col-md-2">Expreso</td>
                <td class="col-md-4">
                    <?= $pedido->expreso; ?>
                    </div>

                    <td class="control-label col-md-2">Direccion transporte</td>
                    <td class="col-md-4">
                        <?= $pedido->direccion_transporte; ?>
                    </td>
            </tr>
            <tr class="form-group">
                <td class="control-label col-md-2">Comentario</td>
                <td class="col-md-9" colspan="4">
                    <?= $pedido->comentario; ?>
                </td>
            </tr>

            <?php $cantidad_por_articulo = array(); ?>

            <?php $cantidad_total = 0; ?>

            <?php foreach ($items as $item): ?>

                <?php $cantidad_por_articulo[$item->nombre] ++; ?>    

                <?php $cantidad_total = $cantidad_total + $item->cantidad; ?>   

            <?php endforeach; ?>
            
            <tr class="form-group">
                <td class="control-label col-md-10">Cantidad articulos</td>
                <td class="col-md-2" style="text-align:right;" colspan="3">
                    <b><?= count($cantidad_por_articulo) ?></b>
                </td>
            </tr>

            <tr class="form-group">
                <td class="control-label col-md-10">Cantidad productos</td>
                <td class="col-md-2" style="text-align:right;" colspan="3">
                    <b><?= $cantidad_total ?></b>
                </td>
            </tr>

            <tr class="form-group">
                <td class="control-label col-md-10">Subtotal</td>
                <td class="col-md-2" style="text-align:right;" colspan="3">
                    <b>$<?= $pedido->subtotal; ?></b>
                </td>
            </tr>

            <tr class="form-group">
                <td class="control-label col-md-10">Descuento %</td>
                <td class="col-md-2" style="text-align:right;" colspan="3">
                    <b><?= $pedido->porcentaje; ?></b>
                </td>
            </tr>

            <tr class="form-group">
                <td class="control-label col-md-10">Valor Descuento</td>
                <td class="col-md-2" style="text-align:right;" colspan="3">
                    <b>$<?= number_format((($pedido->subtotal*$pedido->porcentaje)/100), 2, '.', ''); ?></b>
                </td>
            </tr>

            <tr class="form-group">
                <td class="control-label col-md-10">Total</td>
                <td class="col-md-2" style="text-align:right;" colspan="3">
                    <b>$<?= $pedido->total; ?></b>
                </td>
            </tr>

        </table>    

        <br/><br/>
        <table style="width:100%; font-size: 12px;" id='tableitems'>

            <thead>

                <tr>
                    <th>Producto</th>
                    <th>Descripcion</th>
                    <th>Cantidad</th>
                    <th>Medida</th>
                    <th>Talle</th>
                    <th>Color</th>
                    <th>$ Unitario</th>

                    <th>Subtotal</th>

                </tr>

            </thead>

            <tbody>
                <?php foreach ($items as $item): ?>

                    <style>
                        .radio-<?= $item->color_id; ?>.radio label::before {
                            border: 1px solid <?= $colores[$item->color_id] ?>;
                            background: <?= $colores[$item->color_id] ?> !important;
                        }
                        .radio-<?= $item->color_id; ?> input[type="radio"] + label::after {
                            background: <?= $colores[$item->color_id] ?>;
                        }
                        .radio-<?= $item->color_id; ?> input[type="radio"]:checked + label::before {
                            background-color: <?= $colores[$item->color_id] ?>;
                            border-color: <?= $colores[$item->color_id] ?>;
                        }
                        .radio-<?= $item->color_id; ?> input[type="radio"]:checked + label::after {
                            background-color: <?= $colores[$item->color_id] ?>;
                            border-color: <?= $colores[$item->color_id] ?>;
                        }
                    </style>

                    <tr >
                        <td> <img src="<?= base_url(); ?>uploads/<?= $item->imagen ?>" alt="<?= $item->slug; ?>" style="width:30px"></td>

                        <td><?php echo $item->nombre; ?></td>

                        <td align="right">
                            <?php echo $item->cantidad; ?>
                            <?php echo ($item->cantidad_a_descontar == 10) ? '(x10)' : '' ?>
                        </td>


                        <td><?= (isset($item->medida_id)) ? $medidas[$item->medida_id] : '-'; ?></td>

                        <td><?= (isset($item->talle_id)) ? $talles[$item->talle_id] : '-'; ?></td>

                        <td align="center">

                            <div style="background: <?= (!empty($item->color_id)) ? $colores[$item->color_id] : '' ?> !important;width:30%">
                                &nbsp;
                            </div>
                        </td>
                        <td>$ <?php echo number_format($item->precio_unitario, 2, '.', ''); ?></td>

                        <td align="center">$ <?php echo number_format($item->subtotal, 2, '.', ''); ?></td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </body>
</html>