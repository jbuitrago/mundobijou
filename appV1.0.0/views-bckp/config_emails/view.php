<h2><?= strtoupper($controller); ?></h2>
<?= $otros_acciones; ?>
<br />
<br />
<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Smtp host</th>
            <th>Smtp email from</th>
            <th>Smtp email name</th>
           
            <th>Email envio quiebre</th>
            <th>Email envio pedido</th>

            <th style="width:80px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>

    <tfoot>
        <tr>
            <th>Smtp host</th>
            <th>Smtp email from</th>
            <th>Smtp email name</th>
           
            <th>Email envio quiebre</th>
            <th>Email envio pedido</th>

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
                $('[name="smtp_email_from"]').val(data.smtp_email_from);
                $('[name="smtp_email_name"]').val(data.smtp_email_name);
                $('[name="smtp_email_pass"]').val(data.smtp_email_pass);
                $('[name="email_envio_quiebre"]').val(data.email_envio_quiebre);
                $('[name="email_envio_pedido"]').val(data.email_envio_pedido);
                $('[name="smtp_host"]').val(data.smtp_host);

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
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-4">Smtp host</label>
                            <div class="col-md-8">
                                <input name="smtp_host" placeholder="smtp_host" class="form-control" type="text">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4">Smtp email from</label>
                            <div class="col-md-8">
                                <input name="smtp_email_from" placeholder="smtp_email_from" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Smtp email name</label>
                            <div class="col-md-8">
                                <input name="smtp_email_name" placeholder="smtp_email_name" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Smtp email pass</label>
                            <div class="col-md-8">
                                <input name="smtp_email_pass" placeholder="smtp_email_pass" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Email envio quiebre</label>
                            <div class="col-md-8">
                                <input name="email_envio_quiebre" placeholder="email_envio_quiebre" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Email envio pedido</label>
                            <div class="col-md-8">
                                <input name="email_envio_pedido" placeholder="email_envio_pedido" class="form-control" type="text">
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