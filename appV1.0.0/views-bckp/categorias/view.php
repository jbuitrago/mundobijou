<h2><?= strtoupper($controller); ?></h2>
<?=$otros_acciones;?>
<br />
<br />
<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Destacada</th>
            <th style="width:80px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <th>Nombre</th>
            <th>Destacada</th>
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
        $('#destacada').html('<input name="destacada" class="form-control" type="checkbox" value="si">');
        $('.modal-title').text('Agregar'); // Set Title to Bootstrap modal title
        $('#errores').html('').hide();
        $('[name="file"]').val('');
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
                $('[name="destacada"]').val(data.destacada);
                if(data.destacada === "si") 
                {
                    $('#destacada').html('<input name="destacada" class="form-control" type="checkbox" checked="checked" value="si">');
                }
                else
                {
                    $('#destacada').html('<input name="destacada" class="form-control" type="checkbox" value="si">');
                }
                 if(data.foto !=  null) $('#output').html('<img src="' + base_url +'uploads/'+ data.foto + '" style="width:50px;">');
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
        }
        else
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
                if (data.status==false) {
                    $('#errores').show();    
                    $('#errores').html('');
                    $('#errores').html(data.errores);
                } else {
                    $('#errores').html('');
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

function import_excel()
    {
      
       $('#import_form').modal('show'); // show bootstrap modal
         
       // $('.modal-title').text('Importar Excel'); // Set Title to Bootstrap modal title
        $('#errores_import').html('').hide();
       
    }
    function validar()
    {
        if ($('#excelfile').val() === '') 
        {
            $('#errores_import').html('Debe seleccionar un archivo!').show();
            return false;
        }
        else
        {   
            extensiones_permitidas = new Array(".xls", ".xlsx"); 
            archivo = $('#excelfile').val();
            extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase(); 
            permitida = false; 
            for (var i = 0; i < extensiones_permitidas.length; i++) 
            { 
                if (extensiones_permitidas[i] == extension)
                { 
                    permitida = true; 
                    break; 
                 } 
            } 
             if (!permitida) 
            { 
                $('#errores_import').html('Extensión de archivo incorrecta!').show();
                return false;
            }
            else
            { 
                $('#btnSave').html('Espere...!').show();
                return true;                
            } 
        }
    }
  
</script>
<div class="modal fade" id="import_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" onClick="javascript:window.location.href='/<?= $controller ?>'" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Importar <?= $controller ?> desde Excel</h3>
            </div>
            <div class="modal-body form">
                <?php  //echo form_open_multipart(site_url($controller . '/ExcelDataAdd')); ?>
                <form action="<?= $controller ?>/ExcelDataAdd" onsubmit="return validar()" enctype="multipart/form-data" method="post" accept-charset="utf-8" >   
                <div class="form-body">
                         <div class="form-group">
                            <label class="control-label col-md-3">Archivo Excel</label>
                            <div class="col-md-9">
                                <input id="excelfile" name="excelfile" class="form-control" type="file">
                            </div>
                            </div> 
                         
                    </div>
                    <div class="alert alert-danger" id="errores_import" style="display:none;"></div>
                    <div class="modal-footer">
                        
                        <button type="submit"  onClick="validar()" id="btnSave" class="btn btn-primary">Importar</button>
                        <button type="button" onClick="javascript:window.location.href='/<?= $controller ?>'" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
                <button type="submit"  ONCLICK="document.location='uploads/modelos/categoria.xlsx'" id="btnSave" class="btn btn-primary">Bajar formato</button>
            </div>
        </div>
    </div>
</div>
								

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
                     <input type="hidden" value="" name="file" id="file"/> 
                    <div class="form-body">
                         <div class="form-group">
                            <label class="control-label col-md-3">Nombre</label>
                            <div class="col-md-9">
                                <input name="nombre" placeholder="nombre" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Destacada</label>
                            <div class="col-md-9">
                                <div id="destacada"></div>
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
                               <input name="FileInput" class="form-control" id="FileInput" type="file" maxlength="45" required="required" onchange="save_file('#FileInput', '#MyUploadForm', '#file', '#output', '#statustxt', '#progressbar')">
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