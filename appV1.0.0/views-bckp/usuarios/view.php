<h2><?= strtoupper($controller); ?></h2>
<?=$otros_acciones;?>
<br />
<br />
<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Usuario</th>
            <th>Email</th>
            <th>Rol</th>
            <!--<th>Sistema</th>
            <th>Plan</th>-->
            <th style="width:80px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>

    <tfoot>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Usuario</th>
            <th>Email</th>
            <th>Rol</th>
            <!--<th>Sistema</th>
            <th>Plan</th>-->
            <th>Acciones</th>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">

    var save_method; //for save method string
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
                    "targets": [0,1,2,3,4], //last column
                    "orderable": true, //set not orderable
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
                $('[name="apellido"]').val(data.apellido);
                $('[name="usuario"]').val(data.usuario);
                $('[name="email"]').val(data.email);
                $('[name="roles_id"]').val(data.roles_id);
                $('[name="sistema_id"]').val(data.sistema_id);
                $('[name="plan_id"]').val(data.plan_id);
                if(data.file !=  null) $('#output').html('<img src="' + base_url + data.file + '" style="width:50px;">');
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
                    <input type="hidden" value="" name="file" id="file"/>
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nombre</label>
                            <div class="col-md-9">
                                <input name="nombre" placeholder="Nombre" class="form-control" type="text" maxlength="45" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Apellido</label>
                            <div class="col-md-9">
                                <input name="apellido" placeholder="Apellido" class="form-control" type="text" maxlength="45" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Usuario</label>
                            <div class="col-md-9">
                                <input name="usuario" placeholder="Usuario" class="form-control" type="text" maxlength="20" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Contraseña</label>
                            <div class="col-md-9">
                                <input name="password" placeholder="Password" class="form-control" type="password" maxlength="20" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Email</label>
                            <div class="col-md-9">
                                <input name="email" placeholder="Email" class="form-control" type="email" maxlength="45" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Rol</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('roles_id', $roles, '', 'class="form-control1"'); ?>
                            </div>
                        </div>

                        <div class="form-group" style="display:none;">
                            <label class="control-label col-md-3">Sistema</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('sistema_id', $sistemas, '1', 'class="form-control1"'); ?>
                            </div>
                        </div>
                        
                        <div class="form-group"style="display:none;">
                            <label class="control-label col-md-3">Plan</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('plan_id', $planes, '1', 'class="form-control1"'); ?>
                            </div>
                        </div>
                        
                        <div class="alert alert-danger" id="errores" style="display:none;"></div>
                    </div>
                </form>

                <form action="<?= site_url("upload_file/upload_image") ?>" method="post" enctype="multipart/form-data" id="MyUploadForm">
                    <input type="hidden" value="80" name="width" id="width"/>
                    <input type="hidden" value="80" name="height" id="height"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Imagen</label>
                            <div class="col-md-4">
                                <input name="FileInput" class="form-control" id="FileInput" type="file" maxlength="45" required onchange="$('#MyUploadForm').submit()">
                            </div>
                            <input type="submit"  id="submit-btn" value="Subir"  style="float: left"/>
                            <img src="<?php echo base_url(); ?>images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
                            <div id="progressbox"  style="float: left"><div id="progressbar"></div ><div id="statustxt">0%</div></div>
                            <div id="output" style="float: right;"></div>
                        </div>
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