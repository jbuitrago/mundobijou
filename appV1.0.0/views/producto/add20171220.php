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
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-2">Titulo</label>
                            <div class="col-md-10">
                                <input name="titulo" placeholder="titulo" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Articulo</label>
                            <div class="col-md-10">
                                <input name="articulo" placeholder="articulo" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Descripcion corta</label>
                            <div class="col-md-10">
                                <textarea name="descripcion_corta" placeholder="descripcion_corta" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group">

                            <label class="control-label col-md-2">Categoria</label>
                            <div class="col-md-4">
                                <?php echo form_dropdown('categoria_id', $categorias, '', 'class="form-control"'); ?>
                            </div>

                            <label class="control-label col-md-2">Sub-Categoria</label>
                            <div class="col-md-4">
                                <?php echo form_dropdown('sub_categoria_id', $subcategorias, '', 'class="form-control"'); ?>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Oferta</label>
                            <div class="col-md-4">
                                <!--<input name="oferta" placeholder="oferta" class="form-control" type="number">-->
                                <?php echo form_checkbox('oferta', 'accept', FALSE); ?>
                            </div>

                            <label class="control-label col-md-2">Nuevo</label>
                            <div class="col-md-4">
                               <!-- <input name="nuevo" placeholder="nuevo" class="form-control" type="number">-->
                                <?php echo form_checkbox('nuevo', 'accept', FALSE); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Video</label>
                            <div class="col-md-4">
                                <input name="video" placeholder="video" class="form-control" type="text">
                            </div>

                            <label class="control-label col-md-2">Medidas</label>
                            <div class="col-md-4">
                                <?php echo form_multiselect('medidas[]', $medidas, $medidas_selected, 'class="form-controlx" style="width:90%;padding: 0;"'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Colores</label>
                            <div class="col-md-4">
                                <?php echo form_multiselect('colores[]', $colores, $colores_selected, 'class="form-controlx" style="width:90%;padding: 0;"'); ?>
                            </div>

                            <label class="control-label col-md-2">Talles</label>
                            <div class="col-md-4">
                                <?php echo form_multiselect('talles[]', $talles, $talles_selected, 'class="form-controlx" style="width:90%;padding: 0;"'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Precio mayorista</label>
                            <div class="col-md-4">
                                <input name="precio_mayorista" placeholder="0" class="form-control" type="text">
                            </div>

                            <label class="control-label col-md-2">Precio oferta mayorista</label>
                            <div class="col-md-4">
                                <input name="precio_oferta_mayorista" placeholder="0" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Precio revendedor</label>
                            <div class="col-md-4">
                                <input name="precio_revendedor" placeholder="0" class="form-control" type="text">
                            </div>

                            <label class="control-label col-md-2">Precio oferta revendedor</label>
                            <div class="col-md-4">
                                <input name="precio_oferta_revendedor" placeholder="0" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Precio X 10 mayorista</label>
                            <div class="col-md-4">
                                <input name="precio_por_diez_mayorista" placeholder="0" class="form-control" type="text">
                            </div>

                            <label class="control-label col-md-2">Precio X 10 revendedor</label>
                            <div class="col-md-4">
                                <input name="precio_por_diez_revendedor" placeholder="0" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Precio X 10 oferta mayorista</label>
                            <div class="col-md-4">
                                <input name="precio_por_diez_oferta_mayorista" placeholder="0" class="form-control" type="text">
                            </div>

                            <label class="control-label col-md-2">Precio X 10 oferta revendedor</label>
                            <div class="col-md-4">
                                <input name="precio_por_diez_oferta_revendedor" placeholder="0" class="form-control" type="text">
                            </div>

                        </div>

                        <div class="form-group">

                            <label class="control-label col-md-2">Hay/No Hay</label>
                            <div class="col-md-4">
                                <input type="radio" name="hay" value="si" checked id="si"> HAY&nbsp;
                                <input type="radio" name="hay" value="no" id="no"> NO HAY<br>
                            </div>

                            <label class="control-label col-md-2">Stock</label>
                            <div class="col-md-4">
                                <input name="stock" placeholder="stock" class="form-control" type="number">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Descripcion</label>
                            <div class="col-md-10">
                                <textarea name="descripcion" placeholder="descripcion" class="form-control"></textarea>
                            </div>
                        </div>

                        <input name="file1" id="file1" class="form-control" type="hidden">

                        <input name="file2" id="file2" class="form-control" type="hidden">

                        <input name="file3" id="file3" type="hidden">

                    </div>
                </form>

                <form action="<?= site_url("upload_file/upload_image") ?>" method="post" enctype="multipart/form-data" id="MyUploadForm1">
                    <input type="hidden" value="1024" name="width" id="width"/>
                    <input type="hidden" value="400" name="height" id="height"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Imagen</label>
                            <div class="col-md-4">
                                <input name="FileInput" class="form-control" id="FileInput1" type="file" maxlength="45" required="required" onchange="save_file('#FileInput1', '#MyUploadForm1', '#file1', '#output1', '#statustxt1', '#progressbar1')">
                            </div>
                            <input type="submit"  id="submit-btn" value="Subir"  style="float: left"/>
                            <img src="<?php echo base_url(); ?>images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
                            <div id="progressbox" style="float: left"><div id="progressbar1"></div ><div id="statustxt1">0%</div></div>
                            <div id="output1" style="float: right;"></div>
                        </div>
                    </div>
                </form>

                <form action="<?= site_url("upload_file/upload_image") ?>" method="post" enctype="multipart/form-data" id="MyUploadForm2">
                    <input type="hidden" value="1024" name="width" id="width"/>
                    <input type="hidden" value="400" name="height" id="height"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Imagen</label>
                            <div class="col-md-4">
                                <input name="FileInput" class="form-control" id="FileInput2" type="file" maxlength="45" required="required" onchange="save_file('#FileInput2', '#MyUploadForm2', '#file2', '#output2', '#statustxt2', '#progressbar2')">
                            </div>
                            <input type="submit"  id="submit-btn" value="Subir"  style="float: left"/>
                            <img src="<?php echo base_url(); ?>images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
                            <div id="progressbox"  style="float: left"><div id="progressbar2"></div ><div id="statustxt2">0%</div></div>
                            <div id="output2" style="float: right;"></div>
                        </div>
                    </div>
                </form>

                <form action="<?= site_url("upload_file/upload_image") ?>" method="post" enctype="multipart/form-data" id="MyUploadForm3">
                    <input type="hidden" value="1024" name="width" id="width"/>
                    <input type="hidden" value="400" name="height" id="height"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Imagen</label>
                            <div class="col-md-4">
                                <input name="FileInput" class="form-control" id="FileInput3" type="file" maxlength="45" required="required" onchange="save_file('#FileInput3', '#MyUploadForm3', '#file3', '#output3', '#statustxt3', '#progressbar3')">
                            </div>
                            <input type="submit"  id="submit-btn" value="Subir"  style="float: left"/>
                            <img src="<?php echo base_url(); ?>images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
                            <div id="progressbox"  style="float: left"><div id="progressbar3"></div ><div id="statustxt3">0%</div></div>
                            <div id="output3" style="float: right;"></div>
                        </div>
                    </div>
                </form>
                <div class="alert alert-danger" id="errores" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="volver()">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<script>

    var INPUT_FILE;

    var INPUT;

    var OUTPUT_FILE;

    var STATUS_UPLOAD;

    var PROGRESS_UPLOAD;

    var save_method = 'add';

    SUB_CATEGORIA = '';

    var PAGE_VIEW = '<?=$page_view?>';

    function save_file(inputf, form, input_save, output_show, status, progress) {

        INPUT = inputf;

        INPUT_FILE = input_save;

        OUTPUT_FILE = output_show;

        STATUS_UPLOAD = status;

        PROGRESS_UPLOAD = progress;

        $(form).submit(function () {

            $(this).ajaxSubmit(options);
            // always return false to prevent standard browser submit and page navigation
            return false;
        });
        $(form).submit();
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
                    document.location = "<?php echo site_url($controller . '/index') ?>/"+PAGE_VIEW;
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al guardar. Contacte al administrador');
            }
        });
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
                $('[name="titulo"]').val(data.titulo);
                $('[name="articulo"]').val(data.articulo);
                $('[name="descripcion_corta"]').val(data.descripcion_corta);

                if (data.oferta == 'SI') {
                    $('[name="oferta"]').prop('checked', true);
                }

                if (data.nuevo == 'SI') {
                    $('[name="nuevo"]').prop('checked', true);
                }

                $('[name="video"]').val(data.video);
                $('[name="precio_mayorista"]').val(data.precio_mayorista);
                $('[name="precio_oferta_mayorista"]').val(data.precio_oferta_mayorista);
                $('[name="precio_revendedor"]').val(data.precio_revendedor);
                $('[name="precio_oferta_revendedor"]').val(data.precio_oferta_revendedor);
                $('[name="precio_por_diez_mayorista"]').val(data.precio_por_diez_mayorista);
                $('[name="precio_por_diez_revendedor"]').val(data.precio_por_diez_revendedor);
                $('[name="precio_por_diez_oferta_mayorista"]').val(data.precio_por_diez_oferta_mayorista);
                $('[name="precio_por_diez_oferta_revendedor"]').val(data.precio_por_diez_oferta_revendedor);
                $('[name="descripcion"]').val(data.descripcion);
                $('[name="stock"]').val(data.stock);
                $('[name="file1"]').val(data.file1);
                $('[name="file2"]').val(data.file2);
                $('[name="file3"]').val(data.file3);
                
                $("#"+data.hay).prop("checked", true);

                SUB_CATEGORIA = data.sub_categoria_id;

                $('[name="categoria_id"]').val(data.categoria_id).trigger('change');

                if (data.file1 != '')
                    $("#output1").html("<img style='width:50px;' src='" + base_url_upload + data.file1 + "'>");

                if (data.file2 != '')
                    $("#output2").html("<img style='width:50px;' src='" + base_url_upload + data.file2 + "'>");

                if (data.file3 != '')
                    $("#output3").html("<img style='width:50px;' src='" + base_url_upload + data.file3 + "'>");


                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Editar'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    $('[name="categoria_id"]').change(function () {

        var categoria_id = $('[name="categoria_id"]').val();

        $('[name="sub_categoria_id"]')
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

                    if (SUB_CATEGORIA != '') {

                        if (SUB_CATEGORIA == d.id) {

                            $('[name="sub_categoria_id"]').append('<option selected value="' + d.id + '">' + d.nombre + '</option>');
                        } else {
                            $('[name="sub_categoria_id"]').append('<option value="' + d.id + '">' + d.nombre + '</option>');
                        }
                    } else {
                        // You will need to alter the below to get the right values from your json object.  Guessing that d.id / d.modelName are columns in your carModels data
                        $('[name="sub_categoria_id"]').append('<option value="' + d.id + '">' + d.nombre + '</option>');
                    }
                });
            }
        });

    });

    function volver() {
        if (confirm('Esta seguro que desea cancelar la accion?')) {
            document.location = "<?php echo site_url($controller . '/index') ?>";
        }
    }

    $('[name="colores[]"]').multipleSelect();

    $('[name="medidas[]"]').multipleSelect();

    $('[name="talles[]"]').multipleSelect();

<?php if ($accion == 'update'): ?> edit_obj(<?= $id ?>);
<?php endif; ?>
</script>