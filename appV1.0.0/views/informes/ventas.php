<h2><?= strtoupper($controller); ?> - Ventas</h2>
<?= $otros_acciones; ?>
<br />
<br />

<?php echo form_open('informes/ventas'); ?>

<div id="sandbox-container" class="span5 col-md-5">

    <div class="input-daterange input-group" id="datepicker">      

        <input type="text" class="input-sm form-control" name="start" />

        <span class="input-group-addon">to</span>

        <input type="text" class="input-sm form-control" name="end" />
    </div>

    <button type="submit" id="btnSave" onclick="save_producto()" class="btn btn-primary">Filtrar</button>

</div>

<?php echo form_close(); ?>

<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Mes</th>

            <th class="text-right">Monto</th>

        </tr>
    </thead>
    <tbody>

        <?php foreach ($ventas as $venta): ?>

            <tr>
                <td><?= $venta->mes ?></td>
                <td class="text-right">$<?= number_format($venta->monto, 2, '.', '') ?></td>
            </tr>

        <?php endforeach; ?>
    </tbody>

    <tfoot>
        <tr>
            <th>Mes</th>

            <th class="text-right">Monto</th>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">

    var save_method; //for save method string
    var table;

    $(document).ready(function () {
        $('#table').DataTable({
            "searching": false,
            "lengthChange": false,
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por pagina",
                "zeroRecords": "No se encontraron registros",
                "info": "Mostrando pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No se encontraron registros",
                "infoFiltered": "(filtrado de _MAX_ total records)"
            }
        });
    });


    $(document).ready(function () {
        $('#sandbox-container .input-daterange').datepicker({
            format: "dd-mm-yyyy",
            language: "es",
            autoclose: true
        });
    });
</script>
