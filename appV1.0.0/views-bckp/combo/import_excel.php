<style>
    .form-horizontal .control-label{ text-align: left; padding: 0;}
    @media screen and (min-width: 768px) {
        .custom-class {
            width: 70%;
            /* either % (e.g. 60%) or px (400px) */
        }
    }
</style>
<script>
  
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
<div class="modalx fadex" id="import_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" onClick="javascript:window.location.href='/<?= $controller ?>'" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Importar <?= $controller ?> desde Excel</h3>
            </div>
            <div class="modal-body form">
                <?php //echo form_open_multipart(site_url($controller . '/ExcelDataAdd')); ?>
                <form action="ExcelDataAdd" onsubmit="return validar()" enctype="multipart/form-data" method="post" accept-charset="utf-8" >    
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
                        <button type="submit"  onclick="validar()" id="btnSave" class="btn btn-primary">Importar</button>
                        <button type="button" onClick="javascript:window.location.href='/<?= $controller ?>'" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
                 <button type="submit"  ONCLICK="document.location='../uploads/modelos/combo.xlsx'" id="btnSave" class="btn btn-primary">Bajar formato</button>
            </div>
        </div>
    </div>
</div>