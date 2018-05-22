<h2><?= strtoupper($controller); ?></h2>
<?= $otros_acciones; ?>
<br />
<br />
<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Nombre</th>

            <th>Usuario</th>

            <th>Email</th>

            <th>Provincia</th>

            <th>Localidad</th>

            <th>Tipo cliente</th>

            <th style="width:100px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>

    <tfoot>
        <tr>
            <th>Nombre</th>

            <th>Usuario</th>

            <th>Email</th>

            <th>Provincia</th>

            <th>Localidad</th>

            <th>Tipo cliente</th>

            <th style="width:100px;">Acciones</th>
        </tr>
    </tfoot>
</table>

<style>

    .form-horizontal .control-label {
        margin-bottom: 0;
        padding-top: 7px;
        text-align: left;
    }

</style>

<script type="text/javascript">

    var save_method; //for save method string

    var table;

    var LOCALIDAD;

    $(document).ready(function () {
        table = $('#table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url($controller . '/ajax_list') ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                },
            ],
        });
    });

    function add_obj()
    {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Agregar'); // Set Title to Bootstrap modal title
        $('#output').html('');
        $('#errores').html('').hide();
    }

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
                $('[name="nombre_apellido"]').val(data.nombre_apellido);
                $('[name="tipo_doc"]').val(data.tipo_doc);
                $('[name="numero_doc"]').val(data.numero_doc);
                $('[name="usuario"]').val(data.usuario);
                //$('[name="password"]').val(data.password);
                $('[name="email"]').val(data.email);
                $('[name="codigo_de_area"]').val(data.codigo_de_area);
                $('[name="telefono"]').val(data.telefono);
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
                $('[name="tipo_iva"]').val(data.tipo_iva);
                $('[name="tipo_cliente"]').val(data.tipo_cliente);

                LOCALIDAD = data.localidad;

                $('[name="provincia"]').trigger('change');

                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Editar'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function reload_table()
    {
        table.ajax.reload(null, false); //reload datatable ajax
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
                } else {
                    $('#errores').hide();
                    $('#modal_form').modal('hide');
                    reload_table();
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al guardar');
            }
        });
    }

    function delete_obj(id)
    {
        if (confirm('Esta seguro que desea eliminar?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url($controller . '/ajax_delete') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data)
                {
                    $('#modal_form').modal('hide');
                    reload_table();

                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error al eliminar');
                }
            });

        }
    }

    function ver_pedidos(cliente_id) {

        $('#modal_pedidos').modal('show'); // show bootstrap modal
        
        $('#output').html('');
        $('#errores').html('').hide();

        $("#listado_pedidos").load("<?php echo site_url($controller . '/get_pedidos_cliente') ?>/" + cliente_id, function () {
       
        });
    }

    $(document).ready(function () {
        $('[name="provincia"]').change(function () {

            var provincia_id = $('[name="provincia"]').val();

            $('[name="localidad"]')
                    .find('option')
                    .remove();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('cliente/get_localidades/') ?>/" + provincia_id,

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
    });

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario <?= $controller ?></h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-4">Nombre y apellido</label>
                            <div class="col-md-8">
                                <input name="nombre_apellido" placeholder="nombre y apellido" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Tipo doc</label>
                            <div class="col-md-4">

<?php echo form_dropdown('tipo_doc', $tipo_doc, '', 'class="form-control"'); ?>
                            </div>

                            <label class="control-label col-md-2">N&deg; doc</label>
                            <div class="col-md-4">
                                <input name="numero_doc" placeholder="numero doc" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Usuario</label>
                            <div class="col-md-4">
                                <input name="usuario" placeholder="usuario" class="form-control" type="text">
                            </div>

                            <label class="control-label col-md-2">Contraseña</label>
                            <div class="col-md-4">
                                <input name="password" placeholder="password" class="form-control" type="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Email</label>
                            <div class="col-md-9">
                                <input name="email" placeholder="email" class="form-control" type="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Cod. de area</label>
                            <div class="col-md-4">
                                <input name="codigo_de_area" placeholder="codigo de area" class="form-control" type="number">
                            </div>

                            <label class="control-label col-md-2">Telefono</label>
                            <div class="col-md-4">
                                <input name="telefono" placeholder="telefono" class="form-control" type="text">
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

                            <label class="control-label col-md-2">Provincia</label>
                            <div class="col-md-4">
<?php echo form_dropdown('provincia', $provincias, '', 'class="form-control"'); ?>
                            </div>
                        </div>

                        <div class="form-group">    
                            <label class="control-label col-md-2">Localidad</label>
                            <div class="col-md-4">
<?php echo form_dropdown('localidad', array(), '', 'class="form-control"'); ?>
                            </div>

                            <label class="control-label col-md-2">CP</label>
                            <div class="col-md-4">
                                <input name="codigo_postal" placeholder="CP" class="form-control" type="number">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Tipo iva</label>
                            <div class="col-md-4">
<?php echo form_dropdown('tipo_iva', $condicion_iva, '', 'class="form-control"'); ?>
                            </div>

                            <label class="control-label col-md-2">Tipo cliente</label>
                            <div class="col-md-4">
<?php echo form_dropdown('tipo_cliente', $tipo_cliente, '', 'class="form-control"'); ?>

                            </div>
                        </div>

                        <div class="alert alert-danger" id="errores" style="display:none;"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_pedidos" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title2">Pedidos cliente</h3>
            </div>
            <div class="modal-body" id='listado_pedidos'>

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->