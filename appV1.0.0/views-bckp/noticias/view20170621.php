<h2><?= strtoupper($controller); ?></h2>
<?= $otros_acciones; ?>
<br />
<br />
<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Titulo</th>
            <th>Descripcion</th>

            <th style="width:80px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>

    <tfoot>
        <tr>
            <th>Titulo</th>
            <th>Descripcion</th>

            <th>Acciones</th>
        </tr>
    </tfoot>
</table>

<link href="<?php echo base_url('css/dropzone.css') ?>" rel="stylesheet">

<script src="<?php echo base_url(); ?>js/dropzone.js" type="text/javascript"></script>


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
        $('[name="file"]').val('');
        $('#output').html('');
		$('#descripcion').summernote('code', '');
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
                $('[name="descripcion"]').val(data.descripcion);
                $('[name="video"]').val(data.video);
                $('[name="image"]').val(data.image);
               
                $('#descripcion').summernote('code', data.descripcion);

                if(data.file!='')
                    $("#output").html("<img style='width:80px;' src='" + base_url_upload + data.file + "'>");

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

		 var markupStr = $('#descripcion').summernote('code');
        $('#descripcion').val(markupStr);
		
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
    function cargar_imagenes(id)
    {
        $('.dz-preview').remove();
        save_method = 'add';
        $("#noticia_id").val(id);
        $('#modal_images').modal('show'); // show bootstrap modal
    }

    function ver_imagenes(id) {
        $("#timgs").html("");
        $.ajax({
            url: "<?php echo site_url($controller . '/get_images/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data)
            {
                i = 0;

                var h;

                data.forEach(function (entry) {

                    if ((i % 4) == 0) {

                        if (i == 0) {

                            h += '<tr><td>'
                                    + '<input id="foo' + i + '" value="' + base_url + entry.file + '" type="text" style="border: medium none;color: #fff;display: block;height: 0;width:0;">'
                                    + '<button class="btn copys" style="border:1px solid #000;background:none;" data-clipboard-target="#foo' + i + '">'
                                    + '<img src="' + base_url + entry.file + '" style="width:50px;cursor:pointer;" title="' + entry.nombre + '">'
                                    + '</button>'
                                    + '<button type="button" class="close" aria-label="Close" onclick="delete_image(' + entry.id + ', this)"><span aria-hidden="true">&times;</span></button>'
                                    + '</td>';

                        } else {

                            h += '</tr><tr><td>'
                                    + '<input id="foo' + i + '" value="' + base_url + entry.file + '" type="text" style="border: medium none;color: #fff;display: block;height: 0;width:0;">'
                                    + '<button class="btn copys" style="border:1px solid #000;background:none;" data-clipboard-target="#foo' + i + '">'
                                    + '<img src="' + base_url + entry.file + '" style="width:50px;cursor:pointer;" title="' + entry.nombre + '">'
                                    + '</button>'
                                    + '<button type="button" class="close" aria-label="Close" onclick="delete_image(' + entry.id + ', this)"><span aria-hidden="true">&times;</span></button>'
                                    + '</td>';

                        }
                    } else {

                        h += '<td>'
                                + '<input id="foo' + i + '" value="' + base_url + entry.file + '" type="text" style="border: medium none;color: #fff;display: block;height: 0;width:0;">'
                                + '<button class="btn copys" style="border:1px solid #000;background:none;" data-clipboard-target="#foo' + i + '">'
                                + '<img src="' + base_url + entry.file + '" style="width:50px;cursor:pointer;" title="' + entry.nombre + '">'
                                + '</button>'
                                + '<button type="button" class="close" aria-label="Close" onclick="delete_image(' + entry.id + ', this)"><span aria-hidden="true">&times;</span></button>'
                                + '</td>';

                    }

                    i++;
                });
                $("#timgs").append(h);

                $('#images').modal('show'); // show bootstrap modal

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function delete_image(id, t) {

        if (confirm("Esta seguro que desea borrar la imagen?")) {
            $(t).parents('td').remove();
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url($controller . '/ajax_delete_image') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data)
                {
                    //$('#images').modal('hide');
                    //ver_imagenes(id);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error al eliminar');
                }
            });
        }
        return false;
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
                    <input name="file" id="file" type="hidden">
                    <input name="video" id="video" type="hidden">
                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-3">Titulo</label>
                            <div class="col-md-9">
                                <input name="titulo" placeholder="titulo" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Descripcion</label>
                            <div class="col-md-9">
                                <textarea name="descripcion" id="descripcion" placeholder="descripcion" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="alert alert-danger" id="errores" style="display:none;"></div>
                    </div>
                </form>

                <form action="<?= site_url("upload_file/upload_image") ?>" method="post" enctype="multipart/form-data" id="MyUploadForm">
                    <input type="hidden" value="270" name="width" id="width"/>
                    <input type="hidden" value="180" name="height" id="height"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Imagen</label>
                            <div class="col-md-4">
                                <input name="FileInput" class="form-control" id="FileInput" type="file" maxlength="45" required="required"  onchange="$('#MyUploadForm').submit()">
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


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_images" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Imagenes</h3>

            </div>
            <div class="modal-body form" id="div_images">

                <div class="image_upload_div">
                    <form action="<?php echo site_url($controller . '/upload_image') ?>" class="dropzone">
                        <input type="hidden" id="noticia_id" name="noticia_id">
                        <input type="hidden" value="270" name="width" id="width"/>
                        <input type="hidden" value="180" name="height" id="height"/>
                    </form>
                </div> 

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- End Bootstrap modal -->

<div class="modal fade" role="dialog" id="images">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Imagenes cargadas</h4>
            </div>
            <div class="modal-body" id="imagenes" style="overflow-y: scroll; height: 450px">
                <h5>Imagenes cargadas</h5>
                <br>
                <table id="timgs" class="table table-bordered" style="text-align: center">
                    <!--<tr><td>Imagen</td><td>Titulo</td></tr>-->
                </table>
            </div>
            <div class="modal-footer">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->