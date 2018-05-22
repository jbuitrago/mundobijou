<?php

class Componentes_ImagenBase_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    private $_directorio = 'tmp';

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
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td class="columna'.$ocultar_celulares.'">&nbsp;<span class="titulo_tabla">' . $this->_dcp['idioma_' . Generales_Idioma::obtener()] . '</span></td>';
    }

    private function _registroListadoCuerpo() {
        if ($this->_valor != '') {

            $contenido_registro = explode(";", $this->_valor);

            if ($contenido_registro[1] != '') {

                preg_match("/[0-9]_([0-9]+)/", $contenido_registro[1], $id_tabla_registro);
                $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '2', 'tipo' => 'ImagenBase', 'tabla_tipo' => 'especifica', 'id_componente' => $this->_dcp['cp_id'], 'id_registro' => $id_tabla_registro[1], 'variables' => $this->_dcp['tb_campo'], 'tipo_imagen' => 'muestra'));
                $mostrar = '<span class="cp_ImagenDirectorio" href="#cp_ImagenDirectorio"><img src="' . $link . '" width="18" height="18" /><div><img src="' . $link . '" width="50" height="50" /></div></span>';
            } else {

                $mostrar = '';
            }
        } else {
            $mostrar = '';
        }
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' class="kk_resp_hidden"';
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td '.$ocultar_celulares.'>' . $mostrar . '</td>';
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
                if (isset($_GET['id_tabla_registro'])) {
                    $id_tabla_registro = $_GET['id_tabla_registro'];
                } else {
                    $id_tabla_registro = '';
                }
                $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '2', 'tipo' => 'ImagenBase', 'tabla_tipo' => 'especifica', 'id_componente' => $this->_dcp['cp_id'], 'id_registro' => $id_tabla_registro, 'variables' => $this->_dcp['tb_campo'], 'tipo_imagen' => 'original'));
                $mostrar = '<img src="' . $link . '" width="200" />';
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

                $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '2', 'tipo' => 'ImagenDirectorioTemporal', 'ver' => 'definitiva', 'id_componente' => $this->_idComponente, 'foto_nombre' => $valor));
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
            if (isset($_POST[$id_campo . '_w'])) {
                $_w = $_POST[$id_campo . '_w'];
            } else {
                $_w = '';
            }
            if (isset($_POST[$id_campo . '_h'])) {
                $_h = $_POST[$id_campo . '_h'];
            } else {
                $_h = '';
            }

            $mostrar .= '
        <input type=hidden id="' . $id_campo . '_x" name="' . $id_campo . '_x" value="' . $_x . '" />
        <input type=hidden id="' . $id_campo . '_y" name="' . $id_campo . '_y" value="' . $_y . '" />
        <input type=hidden id="' . $id_campo . '_w" name="' . $id_campo . '_w" value="' . $_w . '" />
        <input type=hidden id="' . $id_campo . '_h" name="' . $id_campo . '_h" value="' . $_h . '" />';

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
            $nombre_imagen = '';
            if ($this->_valor != '') {

                $contenido_registro = explode(";", $this->_valor);

                $nombre_imagen = $contenido_registro[1];
                $imagen = $this->_directorio . '/' . $contenido_registro[1];

                if (!file_exists($this->_directorio . '/' . $nombre_imagen)) {

                    file_put_contents($this->_directorio . '/' . $contenido_registro[1], base64_decode($contenido_registro[3]));
                }

                $mensaje = '{TR|o_eliminar_foto}';
                $accion = 'eliminar';

                if (isset($contenido_registro[1]) && ($contenido_registro[1] != '')) {

                    $tamanio = $this->_tamanioMostrar($imagen, 200);

                    $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '2', 'tipo' => 'ImagenDirectorioTemporal', 'ver' => 'definitiva', 'id_componente' => $this->_idComponente, 'foto_nombre' => $nombre_imagen));

                    $mostrar .= '<img src="' . $link . '" width="' . $tamanio['ancho'] . '" height="' . $tamanio['alto'] . '" /><br />';
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
            if (isset($_POST[$id_campo . '_w'])) {
                $_w = $_POST[$id_campo . '_w'];
            } else {
                $_w = '';
            }
            if (isset($_POST[$id_campo . '_h'])) {
                $_h = $_POST[$id_campo . '_h'];
            } else {
                $_h = '';
            }

            $mostrar .= '
        <input type=hidden id="' . $id_campo . '_x" name="' . $id_campo . '_x" value="' . $_x . '" />
        <input type=hidden id="' . $id_campo . '_y" name="' . $id_campo . '_y" value="' . $_y . '" />
        <input type=hidden id="' . $id_campo . '_w" name="' . $id_campo . '_w" value="' . $_w . '" />
        <input type=hidden id="' . $id_campo . '_h" name="' . $id_campo . '_h" value="' . $_h . '" />
        <input type=hidden id="' . $id_campo . '_nombre" name="' . $id_campo . '_nombre" value="' . preg_replace("/[(0-9+_0-9+)]/", '', $nombre_imagen) . '" />';

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
        return false;
    }

// metodos especiales
    private function _tituloYComponente($mostrar) {

        $id_campo = 'cp_' . $this->_dcp['cp_id'];

        if (isset($_POST[$id_campo . '_nombre'])) {
            $mostrar .= '<input type="hidden" id="' . $id_campo . '_nombre" name="' . $id_campo . '_nombre" value="' . $_POST[$id_campo . '_nombre'] . '" />';
        }

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
            $plantilla['tamanio'] = '<br /><span class="texto_claro">[{TR|m_recorta}: {TR|m_ancho}: ' . $this->_dcp['ancho_final'] . ' x {TR|m_alto}: ' . $this->_dcp['alto_final'] . ' {TR|m_px}]</span>';
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
            $tamanio = $this->_tamanioMostrar($imagen, 500);

            move_uploaded_file($imagen, $this->_directorio . '/t_' . $this->_dcp['cp_id'] . '_' . $this->_idRegistro . '_' . $_FILES[$id_campo]['name'][0]);

            // paso el nombre de la imagen
            $mostrar = '<input type="hidden" name="' . $id_campo . '[0]" id="' . $id_campo . '" value="t_' . $this->_dcp['cp_id'] . '_' . $this->_idRegistro . '_' . $_FILES[$id_campo]['name'][0] . '" />';
            // accion a realizar
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[1]" id="' . $id_campo . '_b" value="actualizar" />';
            // muestro la imagen de muestra

            $ajuste_imagen = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '3', 'componente' => 'ImagenBase', 'archivo' => 'ajuste_imagen.gif', 'traducir' => 'n'));

            $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '2', 'tipo' => 'ImagenDirectorioTemporal', 'ver' => 'temporal', 'id_componente' => $this->_idComponente, 'foto_nombre' => $this->_dcp['cp_id'] . '_' . $this->_idRegistro . '_' . $_FILES['cp_' . $this->_dcp['cp_id']]['name'][0]));
            $mostrar .= '<div style="float:left"><img src="' . $link . '" width="' . $tamanio['ancho'] . '" height="' . $tamanio['alto'] . '" id="crop_' . $id_campo . '" /></div><div style=""><img src="' . $ajuste_imagen . '" hspace="10" /></div><br>' . $this->_javascript($id_campo);
            // para recortar al guardar la imagen
            $mostrar .= '<input type="hidden" name="' . $id_campo . '_ancho" value="' . $tamanio['ancho'] . '" />';
            $mostrar .= '<input type="hidden" name="' . $id_campo . '_alto" value="' . $tamanio['alto'] . '" />';
            // si no se han hechos cambios y la imagen existe
        } elseif (isset($_POST[$id_campo]['2']) && ($_POST[$id_campo]['2'] != '')) {

            $imagen = $this->_directorio . '/' . $_POST[$id_campo]['2'];
            $tamanio = $this->_tamanioMostrar($imagen, 500);

            // paso el nombre de la imagen
            $mostrar = '<input type="hidden" name="' . $id_campo . '[0]" id="' . $id_campo . '" value="' . $_POST[$id_campo]['2'] . '" />';
            // accion a realizar
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[1]" id="' . $id_campo . '_b" value="actualizar" />';
            // paso el nombre de la imagen termporal para eliminar
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[2]" id="' . $id_campo . '" value="' . $_POST[$id_campo]['2'] . '" />';
            // muestro la imagen de muestra
            $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '2', 'tipo' => 'ImagenDirectorioTemporal', 'ver' => 'definitiva', 'id_componente' => $this->_idComponente, 'foto_nombre' => $_POST[$id_campo]['2']));
            $mostrar .= '<img src="' . $link . '" width="' . $tamanio['ancho'] . '" height="' . $tamanio['alto'] . '" id="crop_' . $id_campo . '"/>' . $this->_javascript($id_campo);
            // para recortar al guardar la imagen
            $mostrar .= '<input type="hidden" name="' . $id_campo . '_ancho" value="' . $tamanio['ancho'] . '" />';
            $mostrar .= '<input type="hidden" name="' . $id_campo . '_alto" value="' . $tamanio['alto'] . '" />';

            // si no llega nada muestra la imagen original
        } elseif ($this->_valor != '') {

            $contenido_registro = explode(";", $this->_valor);

            $nombre_imagen = $contenido_registro[1];
            $imagen = $this->_directorio . '/' . $contenido_registro[1];

            $tamanio = $this->_tamanioMostrar($imagen, 500);

            $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '2', 'tipo' => 'ImagenDirectorioTemporal', 'ver' => 'definitiva', 'id_componente' => $this->_idComponente, 'foto_nombre' => $nombre_imagen));
            $mostrar = '<img src="' . $link . '" width="' . $tamanio['ancho'] . '" height="' . $tamanio['alto'] . '" id="crop_' . $id_campo . '" />';

            // si no se han hechos cambios y la imagen no existe
        } else {

            $mostrar = '{TR|o_sin_foto}';
        }

        return $mostrar;
    }

    private function _obtenerDirectorio() {
        // guardo la imagen original en el directorio de destino con nombre t_g_[id-tabla]_[id-registro]_xxx
        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('tmp');
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

        if (isset($this->_dcp['ancho_final']) && isset($this->_dcp['alto_final'])) {
            $ratio = 'aspectRatio: ' . $this->_dcp['ancho_final'] . ' / ' . $this->_dcp['alto_final'];
        } else {
            $ratio = '';
        }
        if (
                ($_POST[$id_campo . '_x'] != '') && ($_POST[$id_campo . '_y'] != '') && ($_POST[$id_campo . '_w'] != '') && ($_POST[$id_campo . '_h'] != '')
        ) {
            $_w = $_POST[$id_campo . '_x'] + $_POST[$id_campo . '_w'];
            $_h = $_POST[$id_campo . '_y'] + $_POST[$id_campo . '_h'];
            $seleccion = 'setSelect:   [ ' . $_POST[$id_campo . '_x'] . ', ' . $_POST[$id_campo . '_y'] . ', ' . $_w . ', ' . $_h . ' ],';
        } else {
            $seleccion = '';
        }

        return "
        <script language=\"Javascript\">
            jQuery(document).ready(function(){
                jQuery('#crop_" . $id_campo . "').Jcrop({
                    onChange:    showCoords,
                    onSelect:    showCoords,
                    bgColor:     '#ccc',
                    bgOpacity:   .4,
                    " . $ratio . "
                    " . $seleccion . "
                });
            });
            function showCoords(c){
                    jQuery('#" . $id_campo . "_x').val(c.x);
                    jQuery('#" . $id_campo . "_y').val(c.y);
                    jQuery('#" . $id_campo . "_w').val(c.w);
                    jQuery('#" . $id_campo . "_h').val(c.h);
            };
        </script>
        <input type=hidden id=\"" . $id_campo . "_x\" name=\"" . $id_campo . "_x\" value=\"" . $_POST[$id_campo . '_x'] . "\" />
        <input type=hidden id=\"" . $id_campo . "_y\" name=\"" . $id_campo . "_y\" value=\"" . $_POST[$id_campo . '_y'] . "\" />
        <input type=hidden id=\"" . $id_campo . "_w\" name=\"" . $id_campo . "_w\" value=\"" . $_POST[$id_campo . '_w'] . "\" />
        <input type=hidden id=\"" . $id_campo . "_h\" name=\"" . $id_campo . "_h\" value=\"" . $_POST[$id_campo . '_h'] . "\" />
        ";
    }

    private function _tamanioMostrar($imagen, $limite) {

        list($ancho, $alto, $tipo) = getimagesize($imagen);
        // tipos de archivos 1=GIF, 2=JPG, 3=PNG

        if (
                ($ancho > $limite) || ($alto > $limite)
        ) {

            if ($ancho == $limite) {
                $indice = 0;
            } elseif ($alto == $limite) {
                $indice = 2;
            } else {
                $indice = abs(($ancho - $limite) / ($alto - $limite));
            }

            if ($indice > 1) {
                $ancho_nvo = $limite;
                $alto_nvo = $limite * $alto / $ancho;
            } else {
                $ancho_nvo = $limite * $ancho / $alto;
                $alto_nvo = $limite;
            }
        } else {
            $ancho_nvo = $ancho;
            $alto_nvo = $alto;
        }

        $tamanio['ancho'] = $ancho_nvo;
        $tamanio['alto'] = $alto_nvo;

        return $tamanio;
    }

}
