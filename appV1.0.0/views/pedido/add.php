<style>
    .form-horizontal .control-label{ text-align: left; padding: 0;}
    @media screen and (min-width: 768px) {
        .custom-class {
            width: 70%;
            /* either % (e.g. 60%) or px (400px) */
        }
    }
</style>

<!-- Bootstrap modal -->
<div class="modalx" id="modal_formx" role="">
    <div class="modal-dialogx custom-classx">
        <div class="modal-content" style="width: 100%">
            <div class="modal-header">

                <h3 class="modal-title">Formulario <?= $controller ?></h3>
            </div>

            <form action="#" id="form" class="form-horizontal">
                <input type="hidden" value="" name="id"/>
                <div class="form-body">

                    <div class="form-group">
                        <label class="control-label col-md-2">Fecha</label>
                        <div class="col-md-4">
                            <input name="fecha" placeholder="fecha" class="form-control" type="text" readonly="">
                        </div>

                        <label class="control-label col-md-2">Estado</label>
                        <div class="col-md-4">

                            <?php echo form_dropdown('estado', $estados_pedido, '', 'class="form-control"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Forma pago</label>
                        <div class="col-md-4">
                            <input name="forma_pago" placeholder="forma_pago" class="form-control" type="text">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Cliente</label>
                        <div class="col-md-4">
                            <input name="cliente_id" placeholder="cliente_id" class="form-control" type="hidden">
                            <input name="cliente" class="form-control" type="text" value="<?= $cliente->nombre_apellido ?>" readonly="">
                        </div>
                        <label class="control-label col-md-2">Doc.</label>
                        <div class="col-md-4">

                            <input name="numero_doc" class="form-control" type="text" value="<?= $cliente->numero_doc ?>" readonly="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Tipo cliente</label>
                        <div class="col-md-4">

                            <input name="tipo_cliente" class="form-control" type="text" value="<?= ($cliente->tipo_cliente == 1) ? 'Mayorista' : 'Revendedor' ?>" readonly="">
                        </div>
                        <label class="control-label col-md-2">Email</label>
                        <div class="col-md-4">

                            <input name="email" class="form-control" type="text" value="<?= $cliente->email ?>" readonly="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Cod. Area</label>
                        <div class="col-md-4">

                            <input name="telefono" class="form-control" type="text" value="<?= $cliente->codigo_de_area ?>" readonly="">
                        </div>
                        <label class="control-label col-md-2">Telefono</label>
                        <div class="col-md-4">

                            <input name="numero_doc" class="form-control" type="text" value="<?= $cliente->telefono ?>" readonly="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Calle</label>
                        <div class="col-md-4">
                            <input name="calle" placeholder="calle" class="form-control" type="text">
                        </div>

                        <label class="control-label col-md-2">Altura</label>
                        <div class="col-md-4">
                            <input name="altura" placeholder="altura" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Piso</label>
                        <div class="col-md-4">
                            <input name="piso" placeholder="piso" class="form-control" type="text">
                        </div>

                        <label class="control-label col-md-2">Dpto</label>
                        <div class="col-md-4">
                            <input name="dpto" placeholder="dpto" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Barrio</label>
                        <div class="col-md-4">
                            <input name="barrio" placeholder="barrio" class="form-control" type="text">
                        </div>

                        <label class="control-label col-md-2">Manzana</label>
                        <div class="col-md-4">
                            <input name="manzana" placeholder="manzana" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Lote</label>
                        <div class="col-md-4">
                            <input name="lote" placeholder="lote" class="form-control" type="text">
                        </div>

                        <label class="control-label col-md-2">Codigo postal</label>
                        <div class="col-md-4">
                            <input name="codigo_postal" placeholder="codigo_postal" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Provincia</label>
                        <div class="col-md-4">
                            <?php echo form_dropdown('provincia', $provincias, '', 'class="form-control"'); ?>
                        </div>

                        <label class="control-label col-md-2">Localidad</label>
                        <div class="col-md-4">
                            <?php echo form_dropdown('localidad', array(), '', 'class="form-control"'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Expreso</label>
                        <div class="col-md-4">
                            <input name="expreso" placeholder="expreso" class="form-control" type="text">
                        </div>

                        <label class="control-label col-md-2">Direccion transporte</label>
                        <div class="col-md-4">
                            <input name="direccion_transporte" placeholder="direccion_transporte" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Comentario</label>
                        <div class="col-md-9">
                            <textarea name="comentario" placeholder="comentario" class="form-control"></textarea>
                        </div>
                    </div>

                    <?php $cantidad_por_articulo = array(); ?>

                    <?php $cantidad_total = 0; ?>

                    <?php foreach ($items as $item): ?>

                        <?php $cantidad_por_articulo[$item->nombre] ++; ?>    

                        <?php $cantidad_total = $cantidad_total + $item->cantidad; ?>   

                    <?php endforeach; ?>

                    <div class="form-group">
                        <label class="control-label col-md-10">Cantidad articulos</label>
                        <div class="col-md-2" style="text-align:right;">
                            <input name="cantidadp" value="<?= count($cantidad_por_articulo) ?>" class="form-control text-right" type="text" readonly="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-10">Cantidad productos</label>
                        <div class="col-md-2" style="text-align:right;">
                            <input name="cantidadp" value="<?= ($cantidad_total) ?>" class="form-control text-right" type="text" readonly="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-10">Subtotal</label>
                        <div class="col-md-2" style="text-align:right;">
                            <input name="subtotal" placeholder="subtotal" class="form-control text-right" type="text" readonly="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-10">Descuento %</label>
                        <div class="col-md-2" style="text-align:right;">
                            <input name="porcentaje" placeholder="0" class="form-control text-right" type="text" value="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-10">Valor Descuento</label>
                        <div class="col-md-2" style="text-align:right;">
                            <input name="valor_descuento" placeholder="0" class="form-control text-right" type="text" readonly="" value="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-10">Total</label>
                        <div class="col-md-2" style="text-align:right;">
                            <input name="total" placeholder="total" class="form-control text-right" type="text" readonly="">
                        </div>
                    </div>

                    <div class="alert alert-danger" id="errores" style="display:none;"></div>
                </div>


                <a href="#tableitems" onclick="agregar_producto()" class="btn btn-warning">Agregar producto</a>

                <table class="table table-striped table-bordered" cellspacing="0" style="width:90%; font-size: 12px;" id='tableitems'>

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
                            <th>Acciones</th>
                        </tr>

                    </thead>

                    <tbody>
                        <?php foreach ($items as $item): ?>

                            <?php
                            echo form_hidden('cart[' . $item->id . '][id]', $item->id);

                            echo form_hidden('cart[' . $item->id . '][name]', $item->nombre);

                            echo form_hidden('cart[' . $item->id . '][price]', $item->precio_unitario);

                            echo form_hidden('cart[' . $item->id . '][qty]', $item->cantidad);

                            echo form_hidden('cart[' . $item->id . '][color_id]', $item->color_id);

                            echo form_hidden('cart[' . $item->id . '][medida_id]', $item->medida_id);

                            echo form_hidden('cart[' . $item->id . '][talle_id]', $item->talle_id);

                            echo form_hidden('cart[' . $item->id . '][imagen]', $item->imagen);

                            echo form_hidden('cart[' . $item->id . '][tipo_producto]', $item->tipo_producto);

                            echo form_hidden('cart[' . $item->id . '][producto_id]', $item->producto_id);

                            echo form_hidden('cart[' . $item->id . '][cantidad_a_descontar]', $item->cantidad_a_descontar);

                            echo form_hidden('cart[' . $item->id . '][subtotal]', $item->subtotal);

                            echo form_hidden('cart[' . $item->id . '][hay]', $item->hay);
                            ?>

                        <div id='productos_cargados'></div>
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

                        <tr id="item_<?= $item->id ?>">
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

                            <td align="center">
                                <a class="btn btn-sm btn-primary" href="javascript:void()" title="Editar" onclick="edit_item('<?= $item->id ?>', '<?= $item->tipo_producto ?>')">
                                    <i class="glyphicon glyphicon-pencil"></i>
                                </a>
                                <a class="btn btn-sm btn-danger" href="javascript:void()" title="Eliminar" onclick="delete_item('<?= $item->id ?>', true)">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </form>

        </div>
        <div class="modal-footer">
            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="document.location = '<?php echo site_url($controller . '/index') ?>'">Cancelar</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<!-- End Bootstrap modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario <?= $controller ?></h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="formi" class="form-horizontal">
                    <input type="hidden" value="" name="iditem"/>
                    <input type="hidden" value="" name="imagen"/>
                    <input type="hidden" value="" name="articulo"/>
                    <input type="hidden" value="" name="nombre_prod"/>
                    <input type="hidden" value="" name="oferta"/>
                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-3">Producto</label>
                            <div class="col-md-9">
                                <select name="tipo_producto" class="form-control">
                                    <option value=""></option>
                                    <option value="combo">Combo</option>
                                    <option value="producto">Producto</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" id="div_producto" style="display:none;">
                            <label class="control-label col-md-3">Producto</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('producto_id', $productos, '', 'class="form-control"'); ?>
                            </div>
                        </div>

                        <div class="form-group" id="div_combo" style="display:none;">
                            <label class="control-label col-md-3">Combos</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('combo_id', $combos, '', 'class="form-control"'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Colores</label>
                            <div class="col-md-9">

                                <?php echo form_dropdown('colores', array(), '', 'class="form-control"'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Medidas</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('medidas', array(), '', 'class="form-control"'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Talles</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('talles', array(), '', 'class="form-control"'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Precio</label>
                            <div class="col-md-3">
                                <input name="precio" value="0" class="form-control text-right" type="text" readonly="">
                            </div>

                            <label class="control-label col-md-3">Precio x 10</label>
                            <div class="col-md-3">
                                <input name="precio_por_diez" value="0" class="form-control text-right" type="text" readonly="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Precio oferta</label>
                            <div class="col-md-3">
                                <input name="precio_oferta" value="0" class="form-control text-right" type="text" readonly="">
                            </div>

                            <label class="control-label col-md-3">Precio oferta x 10</label>
                            <div class="col-md-3">
                                <input name="precio_oferta_por_diez" value="0" class="form-control text-right" type="text" readonly="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Hay/No Hay</label>
                            <div class="col-md-4">
                                <input type="radio" name="hay" value="si" checked id="si"> HAY&nbsp;
                                <input type="radio" name="hay" value="no" id="no"> NO HAY<br>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Cantidad</label>
                            <div class="col-md-9">
                                <input name="cantidad" class="form-control text-right" type="text" value="0">
                            </div>
                        </div>

                        <div class="alert alert-danger" id="errores" style="display:none;"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save_item(1)" class="btn btn-primary">Agregar</button>
                <button type="button" id="btnSave" onclick="save_item(10)" class="btn btn-primary">Agregar combo x 10</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="reset_form()">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<script>

    var LOCALIDAD;

    var COTIZACION_DOLAR = <?= $cotizacion; ?>

    var COLORES = [];

    var MEDIDAS = [];

    var TALLES = [];

<?php foreach ($colores as $value => $key): ?>
        COLORES[<?= $value ?>] = '<?= $key ?>';
<?php endforeach; ?>

<?php foreach ($medidas as $value => $key): ?>
        MEDIDAS[<?= $value ?>] = '<?= $key ?>';
<?php endforeach; ?>

<?php foreach ($talles as $value => $key): ?>
        TALLES[<?= $value ?>] = '<?= $key ?>';
<?php endforeach; ?>

    function edit_obj(id)
    {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url($controller . '/ajax_edit/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data)
            {
                $('[name="id"]').val(data.id);
                $('[name="fecha"]').val(data.fecha_insert);

                if (data.porcentaje != null) {
                    $('[name="porcentaje"]').val(data.porcentaje);
                } else {

                    $('[name="porcentaje"]').val(0);
                }

                $('[name="descuento_id"]').val(data.descuento_id);

                if (data.porcentaje) {

                    $('[name="valor_descuento"]').val(((parseFloat(data.subtotal) * parseFloat(data.porcentaje)) / 100).toFixed(2));

                    $('[name="subtotal"]').val(data.subtotal);

                    $('[name="total"]').val((parseFloat(data.subtotal) - ((parseFloat(data.subtotal) * parseFloat(data.porcentaje)) / 100)).toFixed(2));
                }else{
                    $('[name="valor_descuento"]').val(data.valor_descuento);

                    $('[name="subtotal"]').val(data.subtotal);

                    $('[name="total"]').val(data.total);
                }
                $('[name="estado"]').val(data.estado);

                $('[name="forma_pago"]').val(data.forma_pago);

                $('[name="calle"]').val(data.calle);

                $('[name="altura"]').val(data.altura);
                $('[name="piso"]').val(data.piso);
                $('[name="dpto"]').val(data.dpto);
                $('[name="barrio"]').val(data.barrio);
                $('[name="manzana"]').val(data.manzana);
                $('[name="lote"]').val(data.lote);
                $('[name="codigo_postal"]').val(data.codigo_postal);
                $('[name="provincia"]').val(data.provincia);
                $('[name="localidad"]').val(data.localidad);
                $('[name="expreso"]').val(data.expreso);
                $('[name="direccion_transporte"]').val(data.direccion_transporte);
                $('[name="comentario"]').val(data.comentario);
                $('[name="cliente_id"]').val(data.cliente_id);

                LOCALIDAD = data.localidad;

                $('[name="provincia"]').trigger('change');

                if (data.estado == 3) {
                    $('[name="estado"]').prop('disabled', 'disabled');
                    $('#btnSave').hide();
                }

                $('.modal-title').text('Editar'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }
    function save()
    {
        var url;
        if (save_method == 'add')
        {
            url = "<?php echo site_url($controller . '/ajax_add') ?>";
        } else
        {
            url = "<?php echo site_url($controller . '/ajax_update') ?>";
        }
        $('[name="estado"]').prop('disabled', false);
        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status == false) {
                    $('#errores').show();
                    $('#errores').html('');
                    $('#errores').html(data.errores);
                    $('[name="estado"]').prop('disabled', 'disabled');
                } else {
                    $('#errores').hide();
                    $('#modal_form').modal('hide');
                    document.location = "<?php echo site_url($controller . '/index') ?>";
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al guardar');
            }
        });
    }

    function agregar_producto() {
        $('#modal_form').modal('show'); // show bootstrap modal
    }

    $(document).ready(function () {

        edit_obj(<?= $id ?>);

        $('[name="provincia"]').change(function () {

            var provincia_id = $('[name="provincia"]').val();

            $('[name="localidad"]')
                    .find('option')
                    .remove();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('cliente/get_localidades') ?>/" + provincia_id,

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

        $('[name="tipo_producto"]').change(function (t) {

            if ($(this).val() == 'combo') {

                $("#div_combo").show();

                $("#div_producto").hide();

            }

            if ($(this).val() == 'producto') {

                $("#div_combo").hide();

                $("#div_producto").show();

            }

            if ($(this).val() == '') {

                $("#div_combo").hide();

                $("#div_producto").hide();

            }

        });

        $('[name="producto_id"]').change(function (t) {

            if ($(this).val() != '') {

                get_informacion_producto($(this).val());

            }

        });

        $('[name="combo_id"]').change(function (t) {

            if ($(this).val() != '') {

                get_informacion_combo($(this).val());

            }

        });

    });

    function get_informacion_producto(productoid) {

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url($controller . '/get_informacion_producto/') ?>/" + productoid,
            type: "GET",
            dataType: "JSON",
            async: false,
            success: function (data)
            {
<?php if ($cliente->tipo_cliente == 2): ?>

                    $('[name="precio"]').val((parseFloat(data.precio_revendedor) * COTIZACION_DOLAR).toFixed(2));

                    $('[name="precio_oferta"]').val((parseFloat(data.precio_oferta_revendedor) * COTIZACION_DOLAR).toFixed(2));

                    $('[name="precio_por_diez"]').val((parseFloat(data.precio_por_diez_revendedor) * COTIZACION_DOLAR).toFixed(2));

                    $('[name="precio_oferta_por_diez"]').val((parseFloat(data.precio_por_diez_oferta_revendedor) * COTIZACION_DOLAR).toFixed(2));

<?php else: ?>

                    $('[name="precio"]').val((parseFloat(data.precio_mayorista) * COTIZACION_DOLAR).toFixed(2));

                    $('[name="precio_oferta"]').val((parseFloat(data.precio_oferta_mayorista) * COTIZACION_DOLAR).toFixed(2));

                    $('[name="precio_por_diez"]').val((parseFloat(data.precio_por_diez_mayorista) * COTIZACION_DOLAR).toFixed(2));

                    $('[name="precio_oferta_por_diez"]').val((parseFloat(data.precio_por_diez_oferta_mayorista) * COTIZACION_DOLAR).toFixed(2));

<?php endif; ?>

                $('[name="imagen"]').val(data.file1);

                $('[name="articulo"]').val(data.articulo);

                $('[name="oferta"]').val(data.oferta);

                $('[name="nombre_prod"]').val(data.titulo);

                set_colores(data.colores_selected);

                set_talles(data.talles_selected);

                set_medidas(data.medidas_selected);

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function get_informacion_combo(productoid) {

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url($controller . '/get_informacion_combo/') ?>/" + productoid,
            type: "GET",
            dataType: "JSON",
            async: false,
            success: function (data)
            {
                $('[name="precio"]').val(data.precio_mayorista);

                $('[name="precio_oferta"]').val(data.precio_oferta_mayorista);

                $('[name="precio_por_diez"]').val(data.precio_por_diez_mayorista);

                $('[name="precio_oferta_por_diez"]').val(data.precio_por_diez_oferta_mayorista);

                $('[name="imagen"]').val(data.file1);

                $('[name="articulo"]').val(data.articulo);

                $('[name="oferta"]').val(data.oferta);

                $('[name="nombre_prod"]').val(data.titulo);

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function save_item(c) {

        var precio = 0;

        var cantidad = parseInt($('[name="cantidad"]').val());

        var color = $('[name="colores"]').val();

        var medida = $('[name="medidas"]').val();

        var talle = $('[name="talles"]').val();

        var oferta = $('[name="oferta"]').val();

        var imagen = $('[name="imagen"]').val();

        var articulo = $('[name="articulo"]').val();

        var nombre = $('[name="nombre_prod"]').val();

        var tipo_producto = $('[name="tipo_producto"]').val();

        var manageradiorel = $("input:radio[name ='hay']:checked").val();

        var producto;

        if (tipo_producto == 'producto') {
            producto = $('[name="producto_id"]').val();
        } else {
            producto = $('[name="combo_id"]').val();
        }

        var cantidad_a_descontar = 1;

        var item_cargado = $('[name="iditem"]').val();

        var indice = 0;

        if (!$.isNumeric(cantidad)) {
            alert('El valor ingresado en cantidad no tiene un formato valido.');
            return false;
        }

        if (cantidad < 0) {
            alert('El valor ingresado en cantidad debe ser mayor a cero.');
            return false;
        }

        if (c == 1) {

            cantidad_a_descontar = 1;

            if (oferta == '1') {

                precio = parseFloat($('[name="precio_oferta"]').val());

            } else {
                precio = parseFloat($('[name="precio"]').val());
            }

        }

        if (c == 10) {

            cantidad_a_descontar = 10;

            if (oferta == '1') {

                precio = parseFloat($('[name="precio_oferta_por_diez"]').val());

            } else {
                precio = parseFloat($('[name="precio_por_diez"]').val());
            }

        }

        if (item_cargado != '') {

            indice = item_cargado;

        } else {

            indice = producto;

        }

        var subtotal = (precio * cantidad).toFixed(2);

        var inputs = '<input name="cart[' + indice + '][id]" value="' + indice + '" type="hidden">' +
                '<input name="cart[' + indice + '][name]" value="' + nombre + ' ' + articulo + '" type="hidden">' +
                '<input name="cart[' + indice + '][price]" value="' + precio + '" type="hidden">' +
                '<input name="cart[' + indice + '][qty]" value="' + cantidad + '" type="hidden">' +
                '<input name="cart[' + indice + '][color_id]" value="' + color + '" type="hidden">' +
                '<input name="cart[' + indice + '][medida_id]" value="' + medida + '" type="hidden">' +
                '<input name="cart[' + indice + '][talle_id]" value="' + talle + '" type="hidden">' +
                '<input name="cart[' + indice + '][imagen]" value="' + imagen + '" type="hidden">' +
                '<input name="cart[' + indice + '][tipo_producto]" value="' + tipo_producto + '" type="hidden">' +
                '<input name="cart[' + indice + '][producto_id]" value="' + producto + '" type="hidden">' +
                '<input name="cart[' + indice + '][cantidad_a_descontar]" value="' + cantidad_a_descontar + '" type="hidden">' +
                '<input name="cart[' + indice + '][subtotal]" value="' + subtotal + '" type="hidden">' +
                '<input name="cart[' + indice + '][hay]" value="' + manageradiorel + '" type="hidden">';

        var x10 = '';

        if (cantidad_a_descontar == 10) {
            x10 = '(x10)';
        }

        var tr = '<tr id="item_' + indice + '">' +
                '<td>' +
                '<img src="<?= base_url(); ?>uploads/' + imagen + '" style="width:30px">' +
                '</td>' +
                '<td>' + nombre + ' ' + articulo + '</td>' +
                '<td align="right">' + cantidad + x10 + '</td>' +
                '<td>' + MEDIDAS[medida] + '</td>' +
                '<td>' + TALLES[talle] + '</td>' +
                '<td align="center">' +
                '<div style="background: ' + COLORES[color] + ' !important;width:30%">&nbsp;</div>' +
                '</td>' +
                '<td>$ ' + precio.toFixed(2) + '</td>' +
                '<td align="center">$ ' + subtotal + '</td>' +
                '<td align="center"><a class="btn btn-sm btn-primary" href="javascript:void()" title="Editar" onclick="edit_item(' + indice + ', \'' + tipo_producto + '\')"><i class="glyphicon glyphicon-pencil"></i></a>' +
                '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Eliminar" onclick="delete_item(+indice+, true)"><i class="glyphicon glyphicon-trash"></i></a>' +
                ' </td>' +
                ' </tr>';

        $.when(delete_item(indice, false)).then(set_item(inputs, tr, indice));
    }

    function set_item(inputs, tr, indice) {

        $('#productos_cargados').append(inputs);

        $("#tableitems tbody").append(tr);

        $('#formi')[0].reset();

        $('#modal_form').modal('hide'); // show bootstrap modal

        incrementar_totales(indice);

    }

    function reset_form() {
        $('#formi')[0].reset();
    }

    function set_colores(data) {

        $('[name="colores"]')
                .find('option')
                .remove()
                .end();

        $.each(data, function (i, d) {

            $('[name="colores"]').append('<option value="' + d.color_id + '">' + d.nombre + '</option>');

        });
    }

    function set_talles(data) {

        $('[name="talles"]')
                .find('option')
                .remove()
                .end();

        $.each(data, function (i, d) {

            $('[name="talles"]').append('<option value="' + d.talle_id + '">' + d.nombre + '</option>');

        });
    }

    function set_medidas(data) {

        $('[name="medidas"]')
                .find('option')
                .remove()
                .end();

        $.each(data, function (i, d) {

            $('[name="medidas"]').append('<option value="' + d.medida_id + '">' + d.nombre + '</option>');

        });
    }

    function delete_item(itemid, preguntar) {

        if (preguntar) {
            if (confirm('Esta seguro que desea eliminar el producto?')) {

                $.when(decrementar_totales(itemid)).then(delete_html(itemid));
            }
        } else {
            $.when(decrementar_totales(itemid)).then(delete_html(itemid));
        }

    }

    function delete_html(itemid) {

        $("#item_" + itemid).remove();

        $("input[name^='cart[" + itemid + "]']").each(function () {
            $(this).remove();
        });
    }

    function edit_item(itemid, tipo_producto) {

        agregar_producto();

        $('[name="iditem').val(itemid);

        $('[name="tipo_producto"]').val(tipo_producto).trigger('change');

        if (tipo_producto == 'producto') {

            $('[name="producto_id"]').val($('[name="cart[' + itemid + '][producto_id]"]').val());

            $.when(get_informacion_producto($('[name="cart[' + itemid + '][producto_id]"]').val())).then(set_datos(itemid));
        }
        if (tipo_producto == 'combo') {

            $('[name="combo_id"]').val($('[name="cart[' + itemid + '][producto_id]"]').val());

            $.when(get_informacion_combo($('[name="cart[' + itemid + '][producto_id]"]').val())).then(set_datos(itemid));
        }

        $('[name="cantidad"]').val($('[name="cart[' + itemid + '][qty]"]').val());
    }

    function set_datos(itemid) {

        $('[name="colores"]').val($('[name="cart[' + itemid + '][color_id]"]').val());

        $('[name="medidas"]').val($('[name="cart[' + itemid + '][medida_id]"]').val());

        $('[name="talles"]').val($('[name="cart[' + itemid + '][talle_id]"]').val());

        $('#' + $('[name="cart[' + itemid + '][hay]"]').val()).prop("checked", true);
    }

    function decrementar_totales(itemid) {

        var cantidad;

        var precio;

        if ($('[name="cart[' + itemid + '][qty]"]').length) {

            cantidad = parseFloat($('[name="cart[' + itemid + '][qty]"]').val()); //

        } else {
            cantidad = 0;
        }

        if ($('[name="cart[' + itemid + '][price]"]').length) {
            precio = parseFloat($('[name="cart[' + itemid + '][price]"]').val());
        } else {
            precio = 0;
        }

        var precio_final = cantidad * precio;

        var descuento = parseFloat($('[name="porcentaje"]').val());

        var subtotal = parseFloat($('[name="subtotal"]').val());

        var total = parseFloat($('[name="total"]').val());

        subtotal = subtotal - precio_final;

        var tmp_subtotal = subtotal - ((subtotal * descuento) / 100);

        total = tmp_subtotal;

        $('[name="total"]').val(total);

        $('[name="subtotal"]').val(subtotal);

    }

    function incrementar_totales(itemid) {

        var cantidad = parseFloat($('[name="cart[' + itemid + '][qty]"]').val()); //

        var precio = parseFloat($('[name="cart[' + itemid + '][price]"]').val());

        var precio_final = cantidad * precio;

        var descuento = parseFloat($('[name="porcentaje"]').val());

        var subtotal = parseFloat($('[name="subtotal"]').val());

        var total = parseFloat($('[name="total"]').val());

        subtotal = subtotal + precio_final;

        var tmp_subtotal = subtotal - ((subtotal * descuento) / 100);

        total = tmp_subtotal;

        $('[name="total"]').val(total);

        $('[name="subtotal"]').val(subtotal);

    }

    $('[name="porcentaje"]').change(function (t) {

        var descuento = parseFloat($('[name="porcentaje"]').val());

        var subtotal = parseFloat($('[name="subtotal"]').val());

        var total = parseFloat($('[name="total"]').val());

        if (!$.isNumeric(descuento)) {
            alert('Debe ingresar un formato valido en descuento');
            return false;
        }

        var descuento_valor = ((subtotal * descuento) / 100)

        var tmp_subtotal = subtotal - descuento_valor;

        total = tmp_subtotal;

        $('[name="total"]').val(total);

        $('[name="subtotal"]').val(subtotal);

        $('[name="valor_descuento"]').val(descuento_valor);

    });

</script>