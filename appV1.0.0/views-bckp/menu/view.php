<h2><?= strtoupper($controller); ?></h2>
<?= $otros_acciones; ?>
<br />
<br />
<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Orden</th>
            <th style="width:100px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>

    <tfoot>
        <tr>
            <th>Nombre</th>
            <th>Orden</th>
            <th>Acciones</th>
        </tr>
    </tfoot>
</table>


<script type="text/javascript">

    var save_method; //for save method string
    var save_method_p; //for save method string
    var table;

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

        $("#macciones").multipleSelect("uncheckAll");

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
                $('[name="nombre"]').val(data.nombre);
                $('[name="add"]').val(data.add);
                $('[name="list"]').val(data.list);
                $('[name="orden"]').val(data.orden);
                $('[name="icono"]').val(data.icono);
                $('[name="controller"]').val(data.controller);
                $('[name="padre_id"]').val(data.padre_id);
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Editar'); // Set title to Bootstrap modal title

                $.each(data.grupos.split(","), function (i, e) {
                    $("[name='grupos_id[]'] option[value='" + e + "']").prop("selected", true);
                });

                acciones = [];

                $.each(data.acciones.split(","), function (i, e) {

                    if (e != '')
                        acciones.push(e);

                });
                $("#macciones").multipleSelect("setSelects", acciones);

                /*$.each(data.acciones.split(","), function (i, e) {
                 $("[name='acciones_id[]'] option[value='" + e + "']").prop("selected", true);
                 });*/

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
    function cargar_permisos(id) {
        save_method_p = 'add';
        $('#form_permisos')[0].reset(); // reset form on modals
        $('#modal_form_permisos').modal('show'); // show bootstrap modal
        $('.modal-title').text('Agregar'); // Set Title to Bootstrap modal title
        $('#menu_id').val(id);
    }

    function save_permisos()
    {
        var url;
        if (save_method_p == 'add')
        {
            url = "<?php echo site_url($controller . '/ajax_add_permisos') ?>";
        } else
        {
            url = "<?php echo site_url($controller . '/ajax_update_permisos') ?>";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form_permisos').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status == false) {
                    $('#errores2').show();
                    $('#errores2').html('');
                    $('#errores2').html(data.errores);
                } else {
                    $('#errores2').hide();
                    $('#modal_form_permisos').modal('hide');
                    //reload_table();
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al guardar');
            }
        });
    }

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
                            <label class="control-label col-md-3">Nombre</label>
                            <div class="col-md-9">
                                <input name="nombre" placeholder="nombre" class="form-control" type="text" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Add</label>
                            <div class="col-md-9">
                                <input name="add" placeholder="add" class="form-control" type="text" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">List</label>
                            <div class="col-md-9">
                                <input name="list" placeholder="list" class="form-control" type="text" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Orden</label>
                            <div class="col-md-9">
                                <input name="orden" placeholder="orden" class="form-control" type="number" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Icono</label>
                            <div class="col-md-9">
                                <input name="icono" placeholder="icono" class="form-control" type="text" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Controller</label>
                            <div class="col-md-9">
                                <input name="controller" placeholder="controller" class="form-control" type="text" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Padre</label>
                            <div class="col-md-9">
<?php echo form_dropdown('padre_id', $menus, array(), 'class="form-control"'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Campos<span style="color:red;">*</span></label>
                            <div class="col-md-10">
                                <textarea name="campos" placeholder="Campos" class="form-control" ></textarea>
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
<div class="modal fade" id="modal_form_permisos" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario <?= $controller ?></h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_permisos" class="form-horizontal">
                    <input type="hidden" value="" name="menu_id" id="menu_id"/>
                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-3">Rol</label>
                            <div class="col-md-9">
<?php echo form_dropdown('rol_id', $roles, array(), 'class="form-control"'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Acciones</label>
                            <div class="col-md-9">
                                <?php //echo form_multiselect('acciones_id[]', $acciones, array(), 'class="form-control"');  ?>
<?php echo form_multiselect('acciones_id[]', $acciones, array(), 'class="form-controlx" style="width: 150px;" id=macciones'); ?>
                            </div>
                        </div>

                        <div class="alert alert-danger" id="errores2" style="display:none;"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save_permisos()" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<script>
    $('#macciones').multipleSelect();

</script>