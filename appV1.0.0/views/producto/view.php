<h2><?= strtoupper($controller); ?></h2>
<?= $otros_acciones; ?>
<br />
<br />
<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Titulo</th>

            <th>Articulo</th>

            <th>Categoria</th>

            <th>Sub-Categoria</th>

            <th>Precio mayorista</th>

            <th>Precio revendedor</th>

            <th>Stock</th>

            <th>Oferta</th>

            <th>Nuevo</th>

            <th style="width:80px;">Acciones</th>
        </tr>
    </thead>

    <tbody>
    </tbody>

    <tfoot>
        <tr>
            <th>Titulo</th>

            <th>Articulo</th>

            <th>Categoria</th>

            <th>Sub-Categoria</th>

            <th>Precio mayorista</th>

            <th>Precio revendedor</th>

            <th>Stock</th>

            <th>Oferta</th>

            <th>Nuevo</th>

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
                }
            ]<?php if(isset($page_view) && !empty($page_view)): ?>,
                    initComplete: function () {
                        setTimeout( function () {
                          table.page(<?=$page_view-1?>).draw(false);
                        }, 10 );
      
                    }
            <?php endif; ?>
        });

        
    });

    function add_obj()
    {
        document.location = "<?php echo site_url($controller . '/add/') ?>/";
        return false;
    }

    function edit_obj(id)
    {
        var info = table.page.info();

        document.location = "<?php echo site_url($controller . '/add/') ?>/" + id+"/"+(info.page + 1);
        
        return false;
    }

    function reload_table()
    {
        table.ajax.reload(null, false); //reload datatable ajax
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
    function import_excel()
    {
        document.location = "<?php echo site_url($controller . '/import_excel') ?>";
        return false;
    }
</script>
