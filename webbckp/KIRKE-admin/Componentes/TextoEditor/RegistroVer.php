<?php

class Componentes_TextoEditor_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;

    function __construct() {
        $this->_nombreComponente = Generales_ObtenerNombreComponente::get(__FILE__);
    }

    public function set($datos) {
        $this->_valor = $datos[0];
        $this->_metodo = $datos[1];
        $this->_dcp = $datos[2];
        $this->_idComponente = $datos[3];
        $this->_idRegistro = $datos[4];
        $_pv = Componentes_Componente::componente($this->_nombreComponente, 'ParametrosValores');
        $this->_dcp = array_merge($_pv, $this->_dcp);
    }

    public function get() {
        $metodo = '_' . $this->_metodo;
        return $this->$metodo();
    }

    private function _registroValor() {
        return $this->_valor;
    }

    private function _registroListadoCabezal() {
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        }else{
            $ocultar_celulares = '';
        }
        
        return Armado_RegistroListadoCabezal::armado($this->_dcp['cp_id'], $this->_dcp['tb_campo'], $this->_dcp['idioma_' . Generales_Idioma::obtener()], $ocultar_celulares);
    }

    private function _registroListadoCuerpo() {
        $this->_valor = strip_tags($this->_valor);
        if ($this->_valor == '') {
            $mostrar = '<span class="texto_claro">&lt; {TR|m_sin_datos} &gt;</span>';
            $title = '';
        } elseif (strlen($this->_valor) > 100) {
            $mostrar = substr($this->_valor, 0, 100) . '...';
            if (strlen($this->_valor) > 400) {
                $title = 'title="' . substr($this->_valor, 0, 400) . '..."';
            } else {
                $title = 'title="' . $this->_valor . '"';
            }
        } else {
            $mostrar = $this->_valor;
            $title = '';
        }
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden"';
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td class="columna'.$ocultar_celulares.'" ' . $title . '><div class="tabla_ocultar_sobrante">' . $mostrar . '</div></td>';
    }

    private function _registroListadoPie() {
        if (!isset($this->_dcp['ocultar_celulares'])) {
            return '<td class="columna kk_resp_hidden">&nbsp;</td>';
        }
        return false;
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {

            $mostrar = $this->_valor;
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroAlta() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            // encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }
            return $this->_tituloYComponente($this->_codigo_editor($valor));
        } else {

            return '';
        }
    }

    private function _registroAltaPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }
            $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" />';
            $mostrar .= utf8_encode($this->_vistaPrevia());
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacion() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }

            // recupero de los valores obtenidos al volver de la vista previa
            if ($valor == '') {
                $valor = Generales_Post::obtener($this->_valor, 'h');
            }

            return $this->_tituloYComponente($this->_codigo_editor($valor));
        } else {

            return '';
        }
    }

    private function _registroModificacionPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }

            $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" />';
            $mostrar .= utf8_encode($this->_vistaPrevia());
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroFiltroCampo() {

        if (isset($this->_dcp['filtrar']) && ($this->_dcp['filtrar'] == 's')) {

            if (isset($this->_valor['valor'])) {
                $valor = $this->_valor['valor'];
            } else {
                $valor = '';
            }
            if (isset($this->_valor['condicion'])) {
                $condicion = $this->_valor['condicion'];
            } else {
                $condicion = '';
            }

            if (($condicion == '') || ($condicion == 'nulo') || ($condicion == 'no_nulo')) {
                $style = ' style="display:none"';
            } else {
                $style = '';
            }

            $template = '
                <td><div class="filtros_texto">' . $this->_dcp['idioma_' . Generales_Idioma::obtener()] . '</div>
                <input name="parametro_' . $this->_dcp['cp_id'] . '" id="parametro_' . $this->_dcp['cp_id'] . '" type="hidden" />
                </td>
                <td>' . $this->_registroFiltroCampoOpciones($condicion) . '</td>
                <td><input ' . $style . ' type="text" name="valor_' . $this->_dcp['cp_id'] . '" id="valor_' . $this->_dcp['cp_id'] . '" value="' . $valor . '" class="filtro_Texto" /></td>
                <td><div class="bt_tb_eliminar_filtro" filtro_eliminar_id="' . $this->_dcp['cp_id'] . '"></div></td>
            ';

            return $template;
        } else {

            return false;
        }
    }

    private function _registroFiltroCampoOpciones($condicion) {

        $descripciones[0] = '{TR|o_contiene}';
        $descripciones[1] = '{TR|o_no_contiene}';
        $descripciones[2] = '{TR|o_nulo}';
        $descripciones[3] = '{TR|o_no_nulo}';

        $valores[0] = 'coincide';
        $valores[1] = 'no_coincide';
        $valores[2] = 'nulo';
        $valores[3] = 'no_nulo';

        return Armado_SelectFiltros::armado($this->_dcp['cp_id'], $valores, $descripciones, $condicion);
    }

// metodos especiales
    private function _tituloYComponente($mostrar) {

        if (isset($this->_dcp['obligatorio']) && ($this->_dcp['obligatorio'] == 'no_nulo')) {
            $obligatorio = '<span class="VC_campo_requerido">&#8226;</span> ';
            $plantilla['ocultar'] = '';
        } else {
            $obligatorio = '';
            if (Armado_DesplegableOcultos::mostrarOcultos() === true) {
                $plantilla['ocultar'] = '<div id_ocultar_cp="' . $this->_dcp['cp_id'] . '" class="ocultos_ocultar">{TR|m_ocultar}</div>';
            }
        }

        $plantilla['idioma_generales_idioma'] = $obligatorio . ' ' . $this->_dcp['idioma_' . Generales_Idioma::obtener()];
        $plantilla['mostrar'] = $mostrar;

        if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] == 'administrador general') {
            $plantilla['campo_nombre'] = '<br /><span class="texto_nombre_campos">( ' . $this->_dcp['tb_campo'] . ' ) </span><span class="texto_id_campos"> ( ' . $this->_dcp['cp_id'] . ' )</span>';
        } else {
            $plantilla['campo_nombre'] = '';
        }

        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

    private function _vistaPrevia() {

        $valor_post_ver = html_entity_decode($_POST['cp_' . $this->_dcp['cp_id']], ENT_QUOTES);
        $mostrar = '<div style="width:90%;height:' . $this->_dcp['alto'] . '">' . $valor_post_ver . '</div>';
        return $mostrar;
    }

    private function _codigo_editor($valor) {

        $url_original = getcwd();
        chdir(Inicio::pathPublico() . '/' . ucwords(Inicio::confVars('archivos_externos')) . '/' . $this->_dcp['cp_nombre']);
        $dir_fck = getcwd();
        chdir($url_original);

        if (file_exists($dir_fck . '/ckeditor/ckeditor_php5.php')) {
            include_once($dir_fck . '/ckeditor/ckeditor_php5.php');
        }

        $url = './' . ucwords(Inicio::confVars('archivos_externos')) . '/' . $this->_dcp['cp_nombre'];

        $CKEditor = new CKEditor();
        $CKEditor->returnOutput = true;
        $CKEditor->basePath = $url . '/ckeditor/';

        if (file_exists($dir_fck . "/ckeditor/ckeditor.php")) {

            // cambiar en publico "_administrador/Archivos/TextoEditor/ckfinder/config.php" a $baseUrl = '/upload_editor/';
            // Si esta configurado el CKFinder
            if (file_exists($dir_fck . '/ckfinder')) {
                $CKEditor->config['filebrowserBrowseUrl'] = $url . '/ckfinder/ckfinder.html';
                $CKEditor->config['filebrowserImageBrowseUrl'] = $url . '/ckfinder/ckfinder.html?type=Images';
                $CKEditor->config['filebrowserFlashBrowseUrl'] = $url . '/ckfinder/ckfinder.html?type=Flash';
                $CKEditor->config['filebrowserUploadUrl'] = $url . '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
                $CKEditor->config['filebrowserImageUploadUrl'] = $url . '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
                $CKEditor->config['filebrowserFlashUploadUrl'] = $url . '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
                $CKEditor->config['filebrowserWindowWidth'] = '800';
                $CKEditor->config['filebrowserWindowHeight'] = '500';
            }
        }

        $CKEditor->config['height'] = $this->_dcp['alto'];
        $CKEditor->config['width'] = '90%';

        if ($this->_dcp['menu'] == 'b') {
            $CKEditor->config['toolbar'] = array(
                array('Source', '-', 'Bold', 'Italic', 'Underline', 'Strike'),
                array('Image', 'Link', 'Unlink', 'Anchor')
            );
        } elseif ($this->_dcp['menu'] == 'n') {

            $CKEditor->config['toolbar'] = array(
                array('Source', '-', 'Save', 'NewPage', 'Preview', '-', 'Templates'),
                array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Print', 'SpellChecker', 'Scayt'),
                array('Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'),
                array('/'),
                array('Bold', 'Italic', 'Underline', 'Strike'),
                array('/'),
                array('Styles', 'Format', 'Font', 'FontSize'),
                array('TextColor', 'BGColor'),
                array('Maximize', 'ShowBlocks', '-', 'About')
            );
        } elseif ($this->_dcp['menu'] == 'c') {

            $CKEditor->config['toolbar'] = array(
                array('Source', '-', 'Save', 'NewPage', 'Preview', '-', 'Templates'),
                array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Print', 'SpellChecker', 'Scayt'),
                array('Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'),
                array('Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'),
                array('BidiLtr', 'BidiRtl'),
                array('/'),
                array('Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'),
                array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote', 'CreateDiv'),
                array('JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
                array('Link', 'Unlink', 'Anchor'),
                array('Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak'),
                array('/'),
                array('Styles', 'Format', 'Font', 'FontSize'),
                array('TextColor', 'BGColor'),
                array('Maximize', 'ShowBlocks', '-', 'About')
            );
        }

        $CKEditor->config['uiColor'] = '#EAC4C3';
        $CKEditor->config['skin'] = 'kama';
        $CKEditor->textareaAttributes = array('cols' => 80, 'rows' => 10);
        $initialValue = html_entity_decode($valor);

        $id_campo = 'cp_' . $this->_dcp['cp_id'];

        return $CKEditor->editor($id_campo, $initialValue);
    }

}
