<h2><?= strtoupper($controller); ?> - Ventas por categoria</h2>
<?= $otros_acciones; ?>
<br />
<br />
<?php echo form_open('informes/ventas_por_categoria'); ?>

<div id="sandbox-container" class="span5 col-md-5">

    <div class="input-daterange input-group" id="datepicker" style=' float: left;width: 80%;'>  
        
        <span class="input-group-addon">Desde</span>
        
        <input type="text" class="input-sm form-control" name="start" value="<?= (!empty($_POST['start']))? $_POST['start'] : ''  ?>"/>

        <span class="input-group-addon">Hasta</span>

        <input type="text" class="input-sm form-control" name="end"  value="<?= (!empty($_POST['end']))? $_POST['end'] : ''  ?>"/>
    </div>

    <button type="submit" id="btnSave" class="btn btn-primary" style=' float: left;margin-left: 5px'>Filtrar</button>

</div>

<?php echo form_close(); ?>

<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
   
            <th>Categoria</th>
          
            <th>Cantidad unidades</th>
            
            <th>Monto total</th>

        </tr>
    </thead>
    <tbody>

        <?php foreach ($ventas as $venta): ?>

            <tr>

                <td><?= $venta->categoria ?></td>
  
                <td class="text-right"><?= $venta->cantidad ?></td>

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