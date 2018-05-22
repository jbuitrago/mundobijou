<?php

class Componentes_ImagenDirectorio_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    private $_directorio;

    function __construct() {
        $this->_nombreComponente = Generales_ObtenerNombreComponente::get(__FILE__);
    }

    public function set($datos) {
        $this->_valor = $datos[0];
        $this->_metodo = $datos[1];
        $this->_dcp = $datos[2];
        $this->_idComponente = $datos[3];
        $this->_directorio = $this->_obtenerDirectorio();
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
        } else {
            $ocultar_celulares = '';
        }

        return '<td class="columna' . $ocultar_celulares . '">&nbsp;<span class="titulo_tabla">' . $this->_dcp['idioma_' . Generales_Idioma::obtener()] . '</span></td>';
    }

    private function _registroListadoCuerpo() {
        if ($this->_valor != '') {
            $nombre_imagen = $this->_nombreLimpio($this->_valor);
            if ($nombre_imagen != '') {
                $link = $this->_linkImagen('definitiva', '.' . $this->_dcp['cp_nombre'] . '.kirke/' . $this->_valor, 'tabla');
                $mostrar = '<span class="cp_ImagenDirectorio" href="#cp_ImagenDirectorio"><img src="' . $link . '" width="18" height="18" /><div><img src="' . $link . '" width="50" height="50" />' . $nombre_imagen . '</div></span>';
            } else {
                $mostrar = '<span class="texto_claro">&lt; {TR|o_sin_foto} &gt;</span>';
            }
        } else {
            $mostrar = '<span class="texto_claro">&lt; {TR|o_sin_foto} &gt;</span>';
        }

        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' class="kk_resp_hidden"';
        } else {
            $ocultar_celulares = '';
        }

        return '<td ' . $ocultar_celulares . '>' . $mostrar . '</td>';
    }

    private function _registroListadoPie() {
        if (!isset($this->_dcp['ocultar_celulares'])) {
            return '<td class="columna kk_resp_hidden">&nbsp;</td>';
        }
        return false;
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {
            if ($this->_valor != '') {
                $nombre_imagen = $this->_nombreLimpio($this->_valor);
                $link = $this->_linkImagen('definitiva', $this->_valor);
                $mostrar = '<img src="' . $link . '" width="200" /> ' . $nombre_imagen;
            } else {
                $mostrar = '{TR|o_sin_foto}';
            }
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroAlta() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo]['0'])) {
                $valor = Generales_Post::obtener($_POST[$id_campo]['0'], 'h');
            } else {
                $valor = '';
            }

            $mostrar = '';
            if ($valor != '') {
                $mensaje = '{TR|o_no_subir}';
                $accion = 'no_subir';

                $imagen = $this->_directorio . '/' . $valor;
                $tamanio = $this->_tamanioMostrar($imagen, 200);

                $link = $this->_linkImagen('definitiva', $valor);
                $mostrar .= '<img src="' . $link . '" width="' . $tamanio['ancho'] . '" height="' . $tamanio['alto'] . '" /><br />';
                $mostrar .= '<input type="hidden" name="' . $id_campo . '[2]" id="' . $id_campo . '_c" value="' . $valor . '" />';
            }

            $mostrar .= '<input type="file" name="' . $id_campo . '[0]" id="' . $id_campo . '">';

            if (!$this->_dcp['obligatorio'] || ($this->_dcp['obligatorio'] != 'no_nulo')) {
                $mostrar .= '<input type="checkbox" name="' . $id_campo . '[1]" id="' . $id_campo . '_b" value="no_subir" />{TR|o_no_subir}';
            }
            if (isset($_POST[$id_campo . '_x'])) {
                $_x = $_POST[$id_campo . '_x'];
            } else {
                $_x = '';
            }
            if (isset($_POST[$id_campo . '_y'])) {
                $_y = $_POST[$id_campo . '_y'];
            } else {
                $_y = '';
            }
            if (isset($_POST[$id_campo . '_x2'])) {
                $_x2 = $_POST[$id_campo . '_x2'];
            } else {
                $_x2 = '';
            }
            if (isset($_POST[$id_campo . '_y2'])) {
                $_y2 = $_POST[$id_campo . '_y2'];
            } else {
                $_y2 = '';
            }

            $mostrar .= '
        <input type="hidden" id="' . $id_campo . '_x\" name="' . $id_campo . '_x" value="' . $_x . '" />
        <input type="hidden" id="' . $id_campo . '_y\" name="' . $id_campo . '_y" value="' . $_y . '" />
        <input type="hidden" id="' . $id_campo . '_x2\" name="' . $id_campo . '_x2" value="' . $_x2 . '" />
        <input type="hidden" id="' . $id_campo . '_y2\" name="' . $id_campo . '_y2" value="' . $_y2 . '" />';

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroAltaPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            // encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

            $mostrar = $this->_vistaPrevia();
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacion() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo]['0'])) {
                $valor = Generales_Post::obtener($_POST[$id_campo]['0'], 'h');
            } else {
                $valor = '';
            }

            $mostrar = '';
            if ($this->_valor) {
                $mensaje = '{TR|o_eliminar_foto}';
                $accion = 'eliminar';

                $imagen = $this->_directorio . '/' . $this->_valor;
                if (($this->_valor != '') && (substr($this->_valor, -1, 1) != '/')) {
                    $tamanio = $this->_tamanioMostrar($imagen, 200);
                    $nombre_imagen = $this->_nombreLimpio($this->_valor);
                    $link = $this->_linkImagen('definitiva', $this->_valor);
                    $mostrar .= '<img src="' . $link . '" width="' . $tamanio['ancho'] . '" height="' . $tamanio['alto'] . '" />' . $nombre_imagen . '<br />';
                }
            } else {
                $mensaje = '{TR|o_no_subir}';
                $accion = 'no_subir';
            }

            $mostrar .= '<input type="file" name="' . $id_campo . '[0]" id="' . $id_campo . '">';
            if (!$this->_dcp['obligatorio'] || ($this->_dcp['obligatorio'] != 'no_nulo')) {
                $mostrar .= '<input type="checkbox" name="' . $id_campo . '[1]" id="' . $id_campo . '_b" value="' . $accion . '" />' . $mensaje;
            }

            if (isset($_POST[$id_campo . '_x'])) {
                $_x = $_POST[$id_campo . '_x'];
            } else {
                $_x = '';
            }
            if (isset($_POST[$id_campo . '_y'])) {
                $_y = $_POST[$id_campo . '_y'];
            } else {
                $_y = '';
            }
            if (isset($_POST[$id_campo . '_x2'])) {
                $_x2 = $_POST[$id_campo . '_x2'];
            } else {
                $_x2 = '';
            }
            if (isset($_POST[$id_campo . '_y2'])) {
                $_y2 = $_POST[$id_campo . '_y2'];
            } else {
                $_y2 = '';
            }

            $mostrar .= '
        <input type=hidden id="' . $id_campo . '_x\" name="' . $id_campo . '_x" value="' . $_x . '" />
        <input type=hidden id="' . $id_campo . '_y\" name="' . $id_campo . '_y" value="' . $_y . '" />
        <input type=hidden id="' . $id_campo . '_x2\" name="' . $id_campo . '_x2" value="' . $_x2 . '" />
        <input type=hidden id="' . $id_campo . '_y2\" name="' . $id_campo . '_y2" value="' . $_y2 . '" />';

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacionPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            // encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

            $mostrar = $this->_vistaPrevia();
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

        $descripciones[0] = '{TR|o_igual_a}';
        $descripciones[1] = '{TR|o_contiene}';
        $descripciones[2] = '{TR|o_no_contiene}';
        $descripciones[3] = '{TR|o_nulo}';
        $descripciones[4] = '{TR|o_no_nulo}';

        $valores[0] = 'semejante';
        $valores[1] = 'coincide';
        $valores[2] = 'no_coincide';
        $valores[3] = 'nulo';
        $valores[4] = 'no_nulo';

        return Armado_SelectFiltros::armado($this->_dcp['cp_id'], $valores, $descripciones, $condicion);
    }

// metodos especiales
    private function _tituloYComponente($mostrar) {

        $id_campo = 'cp_' . $this->_dcp['cp_id'];

        $plantilla['mostrar'] = $mostrar;
        // identifica si tiene mascara o filtro
        if ($this->_dcp['obligatorio'] == 'nulo') {
            $plantilla['obligatorio'] = '';
            if (Armado_DesplegableOcultos::mostrarOcultos() === true) {
                $plantilla['ocultar'] = '<div id_ocultar_cp="' . $this->_dcp['cp_id'] . '" class="ocultos_ocultar">{TR|m_ocultar}</div>';
            }
        } else {
            $plantilla['obligatorio'] = '<span class="VC_campo_requerido">&#8226;</span> ';
            $plantilla['ocultar'] = '';
            if (
                    ($this->_metodo != 'registro_alta_previa') &&
                    ($this->_metodo != 'registro_modificacion_previa') &&
                    ($this->_metodo != 'registro_modificacion')
            ) {
                $plantilla['div_mensaje'] = '<div id="VC_' . $id_campo . '"></div>';
            }
        }

        $plantilla['idioma_generales_idioma'] = $this->_dcp['idioma_' . Generales_Idioma::obtener()];

        if (($this->_dcp['alto_final'] != '') && ($this->_dcp['ancho_final'] != '')) {
            $plantilla['tamanio'] = '<br /><span class="texto_claro">[{TR|m_recorta}: {TR|m_ancho}: ' . $this->_dcp['ancho_final'] . ' x {TR|m_alto}:' . $this->_dcp['alto_final'] . ' {TR|m_px}]</span>';
        } elseif ($this->_dcp['alto_final'] != '') {
            $plantilla['tamanio'] = '<br /><span class="texto_claro">[{TR|m_recorta_alto}: ' . $this->_dcp['alto_final'] . ' {TR|m_px}]</span>';
        } elseif ($this->_dcp['ancho_final'] != '') {
            $plantilla['tamanio'] = '<br /><span class="texto_claro">[{TR|m_recorta_ancho}: ' . $this->_dcp['ancho_final'] . ' {TR|m_px}]</span>';
        } else {
            $plantilla['tamanio'] = '<br /><span class="texto_claro">[{TR|m_tamanio_original}]</span>';
        }
        if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] == 'administrador general') {
            $plantilla['campo_nombre'] = '<br /><span class="texto_nombre_campos">( ' . $this->_dcp['tb_campo'] . ' ) </span><span class="texto_id_campos"> ( ' . $this->_dcp['cp_id'] . ' )</span>';
        } else {
            $plantilla['campo_nombre'] = '';
        }

        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

    private function _vistaPrevia() {

        $id_campo = 'cp_' . $this->_dcp['cp_id'];

        if (isset($_POST[$id_campo]['1']) && ($_POST[$id_campo]['1'] == 'no_subir')) {

            $mostrar = "{TR|o_sin_foto}";
        } elseif (isset($_POST[$id_campo]['1']) && ($_POST[$id_campo]['1'] == 'eliminar')) {

            $value = '';
            if (isset($_POST[$id_campo]['2'])) {
                $value = $_POST[$id_campo]['2'];
            }

            // paso el nombre de la imagen
            $mostrar = '<input type="hidden" name="' . $id_campo . '[0]" id="' . $id_campo . '" value="t_' . $this->_dcp['cp_id'] . '_' . $this->_idRegistro . '_' . $_FILES[$id_campo]['name'][0] . '" />';
            // accion a realizar
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[1]" id="' . $id_campo . '_b" value="eliminar" />';
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[2]" id="' . $id_campo . '_c" value="' . $value . '" />';
            $mostrar .= '{TR|o_foto_eliminada}';

            // si realmente se subio un archivo
        } elseif (is_uploaded_file($_FILES[$id_campo]['tmp_name'][0])) {

            $this->_idRegistro = $this->_obtenerIdRegistroTmp();
            $imagen = $_FILES[$id_campo]['tmp_name'][0];

            move_uploaded_file($imagen, $this->_directorio . '/t_' . $this->_dcp['cp_id'] . '_' . $this->_idRegistro . '_' . $_FILES[$id_campo]['name'][0]);
            $tamanio = $this->_tamanioMostrar($this->_directorio . '/t_' . $this->_dcp['cp_id'] . '_' . $this->_idRegistro . '_' . $_FILES[$id_campo]['name'][0], $this->_dcp['img_ancho_ver']);

            // paso el nombre de la imagen
            $mostrar = '<input type="hidden" name="' . $id_campo . '[0]" id="' . $id_campo . '" value="t_' . $this->_dcp['cp_id'] . '_' . $this->_idRegistro . '_' . $_FILES[$id_campo]['name'][0] . '" />';
            // accion a realizar
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[1]" id="' . $id_campo . '_b" value="actualizar" />';
            // muestro la imagen de muestra
            $link = $this->_linkImagen('temporal', $this->_dcp['cp_id'] . '_' . $this->_idRegistro . '_' . $_FILES['cp_' . $this->_dcp['cp_id']]['name'][0]);
            $mostrar .= $this->_opcionesRecorte($id_campo, $link, $tamanio['ancho'], $tamanio['alto']);

            // si no se han hechos cambios y la imagen $_POST existe
        } elseif (isset($_POST[$id_campo]['2']) && ($_POST[$id_campo]['2'] != '')) {

            $imagen = $this->_directorio . '/' . $_POST[$id_campo]['2'];
            $tamanio = $this->_tamanioMostrar($imagen, $this->_dcp['img_ancho_ver']);

            // paso el nombre de la imagen
            $mostrar = '<input type="hidden" name="' . $id_campo . '[0]" id="' . $id_campo . '" value="' . $_POST[$id_campo]['2'] . '" />';
            // accion a realizar
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[1]" id="' . $id_campo . '_b" value="actualizar" />';
            // paso el nombre de la imagen termporal para eliminar
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[2]" id="' . $id_campo . '" value="' . $_POST[$id_campo]['2'] . '" />';
            // muestro la imagen de muestra
            $link = $this->_linkImagen('definitiva', $_POST[$id_campo]['2']);
            $mostrar .= $this->_opcionesRecorte($id_campo, $link, $tamanio['ancho'], $tamanio['alto']);

            // si no llega nada muestra la imagen original
        } elseif ($this->_valor != '') {

            $imagen = $this->_directorio . '/' . $this->_valor;
            $tamanio = $this->_tamanioMostrar($imagen, $this->_dcp['img_ancho_ver']);

            // paso el nombre de la imagen
            $mostrar = '<input type="hidden" name="' . $id_campo . '[0]" id="' . $id_campo . '" value="' . $this->_valor . '" />';
            // accion a realizar
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[1]" id="' . $id_campo . '_b" value="actualizar" />';
            // muestro la imagen de muestra
            $link = $this->_linkImagen('definitiva', $this->_valor);
            $mostrar .= $this->_opcionesRecorte($id_campo, $link, $tamanio['ancho'], $tamanio['alto']);

            // si no se han hechos cambios y la imagen no existe
        } else {

            $mostrar = '{TR|o_sin_foto}';
        }

        return $mostrar;
    }

    private function _obtenerDirectorio() {
        // guardo la imagen original en el directorio de destino con nombre t_g_[id-tabla]_[id-registro]_xxx
        $url_actual = getcwd();
        chdir(Inicio::pathPublico());
        chdir($this->_dcp['directorio']);
        $directorio = getcwd();
        chdir($url_actual);

        return $directorio;
    }

    private function _obtenerIdRegistroTmp() {

        $id_tmp = $this->_directorio . '/.' . $this->_dcp['cp_id'] . '_id_tmp_contador.kirke';

        if (file_exists($id_tmp)) {
            $fh = fopen($id_tmp, 'r+');
            $contents = fread($fh, filesize($id_tmp));
            $id_nvo = $contents + 1;
            fclose($fp);
        } else {
            $id_nvo = 1;
        }
        if (file_exists($id_tmp)) {
            file_put_contents($id_tmp, $id_nvo);
        }

        return $id_nvo;
    }

    private function _javascript($id_campo) {

        if (($this->_dcp['ancho_final'] != '') && ($this->_dcp['alto_final'] != '')) {
            $ratio = "aspectRatio: '" . $this->_dcp['ancho_final'] . ":" . $this->_dcp['alto_final'] . "',";
        } else {
            $ratio = '';
        }

        return "
        <script type=\"text/javascript\">
            $(function () {
                $('#canvas_" . $id_campo . "').imgAreaSelect({
                    " . $ratio . "
                    handles: true
                });
            });
            $(document).ready(function () {
                $('#canvas_" . $id_campo . "').imgAreaSelect({
                    onSelectEnd: function (img, selection) {
                        $('input[name=\"" . $id_campo . "_x\"]').val(selection.x1);
                        $('input[name=\"" . $id_campo . "_y\"]').val(selection.y1);
                        $('input[name=\"" . $id_campo . "_x2\"]').val(selection.x2);
                        $('input[name=\"" . $id_campo . "_y2\"]').val(selection.y2);
                        $('#" . $id_campo . "_corte_recorte').prop('checked', true); 
                    }
                });
                $('#" . $id_campo . "_corte_color').click(function() {
                    var img_area_" . $id_campo . " = $('#canvas_" . $id_campo . "').imgAreaSelect({ instance: true });
                    img_area_" . $id_campo . ".cancelSelection();
                });
                $('#divp_" . $id_campo . "').click(function() {
                    $('#" . $id_campo . "_corte_color').prop('checked', true);
                    var img_area_" . $id_campo . " = $('#canvas_" . $id_campo . "').imgAreaSelect({ instance: true });
                    img_area_" . $id_campo . ".cancelSelection();
                });
            });
        </script>
        <input type=\"hidden\" name=\"" . $id_campo . "_x\" value=\"\" />
        <input type=\"hidden\" name=\"" . $id_campo . "_y\" value=\"\" />
        <input type=\"hidden\" name=\"" . $id_campo . "_x2\" value=\"\" />
        <input type=\"hidden\" name=\"" . $id_campo . "_y2\" value=\"\" />
        ";
    }

    private function _opcionesRecorte($id_campo, $link, $ancho, $alto) {

        $ajuste_imagen = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '3', 'componente' => 'ImagenDirectorio', 'archivo' => 'ajuste_imagen.gif', 'traducir' => 'n'));

        $mostrar = '<div style="float:left"><canvas width="' . $ancho . '" height="' . $alto . '" id="canvas_' . $id_campo . '"><img src="' . $link . '" width="' . $ancho . '" height="' . $alto . '" id="crop_' . $id_campo . '" /></canvas></div>';
        // para recortar al guardar la imagen
        $mostrar .= '<input type="hidden" name="' . $id_campo . '_ancho" value="' . $ancho . '" />';
        $mostrar .= '<input type="hidden" name="' . $id_campo . '_alto" value="' . $alto . '" />';
        $mostrar .= "\n";
        $mostrar .= '<div style="float:left">';
        $mostrar .= '<input type="radio" name="' . $id_campo . '_corte" id="' . $id_campo . '_corte_recorte" value="recorte" checked="checked"> {TR|o_recortar_imagen}<br /><br />';
        $mostrar .= '<img src="' . $ajuste_imagen . '" hspace="10" /><br /><br />';
        $mostrar .= $this->_javascript($id_campo);

        if (($this->_dcp['ancho_final'] != '') && ($this->_dcp['alto_final'] != '')) {
            $mostrar .= '<input type="radio" name="' . $id_campo . '_corte" id="' . $id_campo . '_corte_color" value="color"> {TR|o_color_de_fondo}<br /><br />';
            $mostrar .= '<button id="divp_' . $id_campo . '" class="jscolor {valueElement:\'' . $id_campo . '_color_fondo\', onFineChange:\'setTextColor(this)\'}" style="height: 30px; width: 30px; border-style:solid; border-width: thin; border-color:#CCC; float:left; margin-left:10px;"></button>';
            $mostrar .= '<input type="text" name="' . $id_campo . '_color_fondo" id="' . $id_campo . '_color_fondo" value="' . $this->_dcp['color_fondo'] . '" style="float:left; margin-left:10px;width:50px;" control="CampoTexto" maxlength="6" filtro="ABCDEFabcdef0123456789">';
        }

        $mostrar .= '</div>';
        $mostrar .= '
        <script type="text/javascript">
            $(document).ready(function () {
                var canvas = document.getElementById("canvas_' . $id_campo . '").getContext(\'2d\');
                var img = document.getElementById("crop_' . $id_campo . '");
                canvas.drawImage(img,0,0,' . $ancho . ',' . $alto . ');
                ';

        if (($this->_dcp['ancho_final'] != '') && ($this->_dcp['alto_final'] != '')) {
            $mostrar .= '
                function rgbToHex(R,G,B) {return toHex(R)+toHex(G)+toHex(B)}
                function toHex(n) {
                  n = parseInt(n,10);
                  if (isNaN(n)) return "00";
                  n = Math.max(0,Math.min(n,255));
                  return "0123456789ABCDEF".charAt((n-n%16)/16)  + "0123456789ABCDEF".charAt(n%16);
                }
                $(\'#canvas_' . $id_campo . '\').click(function(event){
                  // getting user coordinates
                  var x = event.pageX - this.offsetLeft;
                  var y = event.pageY - this.offsetTop;
                  // getting image data and RGB values
                  var img_data = canvas.getImageData(x, y, 1, 1).data;
                  var R = img_data[0];
                  var G = img_data[1];
                  var B = img_data[2];  var rgb = R + \',\' + G + \',\' + B;
                  // convert RGB to HEX
                  var hex = rgbToHex(R,G,B);
                  // making the color the value of the input
                  $(\'#' . $id_campo . '_color_fondo\').val(hex);
                  $("#divp_' . $id_campo . '").css("background-color", "#"+hex);
                  $("#' . $id_campo . '_corte_color").prop("checked", true); 
                });
        ';
        }

        $mostrar .= '
            });
        </script>
        ';

        return $mostrar;
    }

    private function _tamanioMostrar($imagen, $limite) {

        if (file_exists($imagen)) {
            list($ancho, $alto, $tipo) = getimagesize($imagen);

            if (($ancho > $limite) && ($alto <= $limite)) {
                $ancho_nvo = $limite;
                $alto_nvo = $alto * $limite / $ancho;
            } elseif (($ancho <= $limite) && ($alto > $limite)) {
                $alto_nvo = $limite;
                $ancho_nvo = $ancho * $limite / $alto;
            } elseif (($ancho > $limite) && ($alto > $limite)) {
                if ($ancho > $limite) {
                    $indice_ancho = $ancho / $limite;
                }
                if ($alto > $limite) {
                    $indice_alto = $alto / $limite;
                }
                if ($indice_ancho > $indice_alto) {
                    $ancho_nvo = $limite;
                    $alto_nvo = $alto * $limite / $ancho;
                } else {
                    $alto_nvo = $limite;
                    $ancho_nvo = $ancho * $limite / $alto;
                }
            } else {
                $ancho_nvo = $ancho;
                $alto_nvo = $alto;
            }
        } else {
            $ancho_nvo = 0;
            $alto_nvo = 0;
        }

        $tamanio['ancho'] = round($ancho_nvo, 0);
        $tamanio['alto'] = round($alto_nvo, 0);

        return $tamanio;
    }

    private function _linkImagen($ver, $foto_nombre, $tipo = '') {
        if (($tipo == 'tabla') && strpos($this->_directorio, $_SERVER['DOCUMENT_ROOT']) !== false) {
            $path_imagentes = str_replace($_SERVER['DOCUMENT_ROOT'], '', $this->_directorio);
            if (file_exists($this->_directorio . '/.' . $this->_dcp['cp_nombre'] . '.kirke/' . $this->_nombreParaTabla($foto_nombre, 'kk_'))) {
                return $path_imagentes . '/.' . $this->_dcp['cp_nombre'] . '.kirke/' . $this->_nombreParaTabla($foto_nombre, 'kk_');
            } elseif (file_exists($this->_directorio . '/.' . $this->_dcp['cp_nombre'] . '.kirke/' . $this->_nombreParaTabla($foto_nombre))) {
                return $path_imagentes . '/.' . $this->_dcp['cp_nombre'] . '.kirke/' . $this->_nombreParaTabla($foto_nombre);
            } else {
                return false;
            }
        } else {
            if ($tipo == 'tabla') {
                $foto_nombre = '.ImagenDirectorio.kirke/kk_' . $this->_nombreParaTabla($foto_nombre);
            }
            return './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '2', 'tipo' => 'ImagenDirectorio', 'ver' => $ver, 'id_componente' => $this->_idComponente, 'foto_nombre' => $foto_nombre));
        }
    }

    private function _nombreLimpio($nombre) {
        if (!is_array($nombre) && ($nombre != '')) {
            $nombre_array = explode('/', $nombre);
            if (count($nombre_array) > 1) {
                return preg_replace("/^([0-9]+)[_]([0-9]*)[_]/", '', $nombre_array[1]);
            }
            return $nombre;
        } else {
            return false;
        }
    }

    private function _nombreParaTabla($nombre, $prefijo = '') {
        if (!is_array($nombre) && ($nombre != '')) {
            $nombre_array = explode('/', $nombre);
            if (isset($nombre_array[2])) {
                return $prefijo . $nombre_array[2];
            }
            return $prefijo . $nombre_array[1];
        } else {
            return false;
        }
    }

}
