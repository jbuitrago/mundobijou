<h2><?= strtoupper($controller); ?></h2>
<?= $otros_acciones; ?>
<br />
<style>
    .form-horizontal .control-label{ text-align: left; padding: 0;}
    @media screen and (min-width: 768px) {
        .custom-class {
            width: 70%;
            /* either % (e.g. 60%) or px (400px) */
        }
    }
</style>
<script type="text/javascript">

    var save_method; //for save method string
    var table;

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
                $('[name="valor"]').val(data.valor);

                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
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

        url = "<?php echo site_url($controller . '/ajax_add') ?>";
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
                    $('#form')[0].reset();
                    $('#errores').html('');
                    alert('Modificacion realizada con exito');
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al guardar');
            }
        });
    }
    $(document).ready(function () {
        //$('[name="categorias[]"]').multipleSelect();

        $('[name="productos[]"]').multipleSelect();

        $('[name="combos[]"]').multipleSelect();

        $('[name="modificacion"]').change(function () {

            var a_modificar = $('[name="modificacion"]').val();

            switch (a_modificar) {
                case 'todos':
                    $('#select_producto').hide();
                    $('#select_combo').hide();
                    $('#select_categoria').hide();
                    //alert(1);
                    break;
                case 'categorias':
                    $('#select_producto').hide();
                    $('#select_combo').hide();
                    $('#select_categoria').show();
                    //alert(2);
                    break;
                case 'productos':
                    $('#select_producto').show();
                    $('#select_combo').hide();
                    $('#select_categoria').hide();
                    //alert(3);
                    break;
                case 'combos':
                    $('#select_producto').hide();
                    $('#select_combo').show();
                    $('#select_categoria').hide();
                    //alert(4);
                    break;
            }
        });

        $('[name="categoria_id"]').change(function () {

            var categoria_id = $('[name="categoria_id"]').val();

            $('[name="sub_categoria_id[]"]')
                    .find('option')
                    .remove();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('subcategorias/get_subcategorias/') ?>/" + categoria_id,
                success: function (data) {
                    // Parse the returned json data
                    var opts = $.parseJSON(data);
                    // Use jQuery's each to iterate over the opts value
                    $.each(opts, function (i, d) {

                        // You will need to alter the below to get the right values from your json object.  Guessing that d.id / d.modelName are columns in your carModels data
                        $('[name="sub_categoria_id[]"]').append('<option value="' + d.id + '">' + d.nombre + '</option>');

                    });
                    
                    $('[name="sub_categoria_id[]"]').multipleSelect();
                }
            });

        });

    });

</script>

<!-- Bootstrap modal -->
<div class="modalx fadex" id="modal_formx" role="dialogx">
    <div class="modal-dialogx">
        <div class="modal-contentx">
            <div class="modal-header">

                <h3 class="modal-title">Modificacion masiva de precios</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-2">Modificar por</label>
                            <div class="col-md-8">
                                <?php
                                $options = array(
                                    'todos' => 'Todos',
                                    'categorias' => 'Categorias',
                                    'productos' => 'Productos',
                                    'combos' => 'Combos',
                                );

                                echo form_dropdown('modificacion', $options, 'large', 'class="form-control"');
                                ?>
                            </div>

                        </div>

                        <div class="form-group" id="select_producto" style="display:none">
                            <label class="control-label col-md-2">Productos</label>
                            <div class="col-md-8">
                                <?php echo form_multiselect('productos[]', $productos, array(), 'class="form-controlx" style="width:90%;padding: 0;"'); ?>
                            </div>

                        </div>

                        <div class="form-group" id="select_combo" style="display:none">
                            <label class="control-label col-md-2">Combos</label>
                            <div class="col-md-8">
                                <?php echo form_multiselect('combos[]', $combos, array(), 'class="form-controlx" style="width:90%;padding: 0;"'); ?>
                            </div>
                        </div>

                        <!--<div class="form-group">
                            <label class="control-label col-md-2">Categorias</label>
                            <div class="col-md-6">
                        <?php echo form_multiselect('categorias[]', $categorias, array(), 'class="form-controlx" style="width:90%;padding: 0;"'); ?>
                            </div>

                        </div>-->

                        <div class="form-group" id="select_categoria" style="display:none">

                            <label class="control-label col-md-2">Categoria</label>
                            <div class="col-md-4">
                                <?php echo form_dropdown('categoria_id', $categorias, '', 'class="form-control"'); ?>
                            </div>

                            <label class="control-label col-md-2">Sub-Categoria</label>
                            <div class="col-md-4">
                                <?php echo form_dropdown('sub_categoria_id[]', array(), '', 'class="form-control"'); ?>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Valor</label>
                            <div class="col-md-8">
                                <input name="valor" placeholder="valor" class="form-control" type="number">
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