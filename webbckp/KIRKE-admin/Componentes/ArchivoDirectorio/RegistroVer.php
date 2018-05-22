<?php

class Componentes_ArchivoDirectorio_RegistroVer extends Armado_Plantilla {

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
        return false;
    }

    private function _registroListadoCuerpo() {
        return false;
    }

    private function _registroListadoPie() {
        return false;
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {

            if ($this->_valor != '') {
                $nombre_real = explode('_', $this->_valor, 3);
                $nombre_real = $nombre_real[2];
                $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '2', 'tipo' => 'ArchivoDirectorio', 'ver' => 'definitiva', 'id_componente' => $this->_idComponente, 'archivo_nombre' => $this->_valor, 'nombre' => $nombre_real), 's');
                $mostrar = '<a href="' . $link . '" />' . $nombre_real . '</a>';
            } else {
                $mostrar = '{TR|o_sin_archivo}';
            }
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
            if (isset($_POST[$id_campo][0])) {
                $valor1 = Generales_Post::obtener($_POST[$id_campo][0], 'h');
            } else {
                $valor1 = '';
            }
            if (isset($_POST[$id_campo][1])) {
                $valor2 = Generales_Post::obtener($_POST[$id_campo][1], 'h');
            } else {
                $valor2 = '';
            }

            $mostrar = '';
            if (isset($_POST['cp_' . $this->_dcp['cp_id']]['0']) && $_POST['cp_' . $this->_dcp['cp_id']]['0'] != '') {

                $mensaje = '{TR|o_no_subir}';
                $accion = 'no_subir';
                $nombre_real = explode('_', $valor1, 4);
                $nombre_real = $nombre_real[3];
                $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '2', 'tipo' => 'ArchivoDirectorio', 'ver' => 'definitiva', 'id_componente' => $this->_idComponente, 'archivo_nombre' => $_POST['cp_' . $this->_dcp['cp_id']]['0'], 'nombre' => $nombre_real), 's');
                $mostrar .= '<a href="' . $link . '" />' . $nombre_real . '</a><br >';

                $mostrar .= '<input type="hidden" name="' . $id_campo . '[2]" id="' . $id_campo . '_c" value="' . $valor1 . '" />';
            }

            $mostrar .= '<input type="file" name="' . $id_campo . '[0]" id="' . $id_campo . '">';

            if (!$this->_dcp['obligatorio'] || ($this->_dcp['obligatorio'] != 'no_nulo')) {
                $mostrar .= '<input type="checkbox" name="' . $id_campo . '[1]" id="' . $id_campo . '_b" value="no_subir" />{TR|o_no_subir_archivo}';
            }

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroAltaPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $mostrar = $this->_vistaPrevia();
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacion() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            // encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo][0])) {
                $valor1 = Generales_Post::obtener($_POST[$id_campo][0], 'h');
            } else {
                $valor1 = '';
            }
            if (isset($_POST[$id_campo][1])) {
                $valor2 = Generales_Post::obtener($_POST[$id_campo][1], 'h');
            } else {
                $valor2 = '';
            }

            $mostrar = '';
            if (isset($_POST[$id_campo]['0']) && ($_POST[$id_campo]['0'] != '')) {
                $valor = $valor1;
                $mostrar .= '<input type="hidden" name="' . $id_campo . '[2]" id="' . $id_campo . '" value="' . $valor1 . '" >';
                $nombre_real = explode('_', $this->_valor, 4);
                $nombre_real = $nombre_real[3];
            } else {
                $nombre_real = explode('_', $this->_valor, 3);
                if (isset($nombre_real[2])) {
                    $nombre_real = $nombre_real[2];
                } else {
                    $nombre_real = '';
                }
            }
            if ($this->_valor) {
                $mensaje = '{TR|o_eliminar_archivo}';
                $accion = 'eliminar';
                $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '2', 'tipo' => 'ArchivoDirectorio', 'ver' => 'definitiva', 'id_componente' => $this->_idComponente, 'archivo_nombre' => $this->_valor, 'nombre' => $nombre_real), 's');
                $mostrar .= '<a href="' . $link . '" />' . $nombre_real . '</a><br >';
            } else {
                $mensaje = '{TR|o_no_subir_archivo}';
                $accion = 'no_subir';
                $mostrar .= '';
            }
            $mostrar .= '<input type="file" name="' . $id_campo . '[0]" id="' . $id_campo . '" />';

            if (!$this->_dcp['obligatorio'] || ($this->_dcp['obligatorio'] != 'no_nulo')) {
                $mostrar .= '<input type="checkbox" name="' . $id_campo . '[1]" id="' . $id_campo . '_b" value="' . $accion . '" /> ' . $mensaje;
            }

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacionPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

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
        if ($this->_dcp['obligatorio'] == 'nulo') {
            $plantilla['obligatorio'] = '';
            if (Armado_DesplegableOcultos::mostrarOcultos() === true) {
                $plantilla['ocultar'] = '<div id_ocultar_cp="' . $this->_dcp['cp_id'] . '" class="ocultos_ocultar">{TR|m_ocultar}</div>';
            }
        } else {
            $plantilla['obligatorio'] = '<span class="VC_campo_requerido">&#8226;</span> ';
            $plantilla['ocultar'] = '';
        }

        $plantilla['idioma_generales_idioma'] = $this->_dcp['idioma_' . Generales_Idioma::obtener()];
        $plantilla['mostrar'] = $mostrar;
        $plantilla['tamanio'] = '<br /><span class="texto_claro">[{TR|m_tamanio_maximo_del_archivo}:' . ini_get('upload_max_filesize') . ']</span>';

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
        if (isset($_POST[$id_campo][0])) {
            $valor1 = Generales_Post::obtener($_POST[$id_campo][0], 'h');
        } else {
            $valor1 = '';
        }
        if (isset($_POST[$id_campo][1])) {
            $valor2 = Generales_Post::obtener($_POST[$id_campo][1], 'h');
        } else {
            $valor2 = '';
        }
        if (isset($_POST[$id_campo][2])) {
            $valor3 = Generales_Post::obtener($_POST[$id_campo][2], 'h');
        } else {
            $valor3 = '';
        }

        $mostrar = '';
        if ($valor2 == 'no_subir') {
            $mostrar = '{TR|o_sin_archivo}';
        } elseif ($valor2 == 'eliminar') {
            // paso el nombre del archivo
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[0]" id="' . $id_campo . '" value="t_' . $this->_dcp['cp_id'] . '_' . $this->_idRegistro . '_' . $_FILES[$id_campo]['name'][0] . '" />';
            // accion a realizar
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[1]" id="' . $id_campo . '_b" value="eliminar" />';
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[2]" id="' . $id_campo . '_c" value="' . $valor3 . '" />';
            $mostrar .= '{TR|o_archivo_eliminado}';
            // si realmente se subio un archivo
        } elseif (is_uploaded_file($_FILES[$id_campo]['tmp_name'][0])) {

            $this->_idRegistro = $this->_obtenerIdRegistroTmp();

            move_uploaded_file($_FILES[$id_campo]['tmp_name'][0], $this->_directorio . '/t_' . $this->_dcp['cp_id'] . '_' . $this->_idRegistro . '_' . $_FILES[$id_campo]['name'][0]);
            // paso el nombre del archivo
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[0]" id="' . $id_campo . '" value="t_' . $this->_dcp['cp_id'] . '_' . $this->_idRegistro . '_' . $_FILES[$id_campo]['name'][0] . '" />';
            // accion a realizar
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[1]" id="' . $id_campo . '_b" value="actualizar" />';
            // muestro la imagen de muestra
            $mostrar .= $_FILES[$id_campo]['name'][0];

            // si no se han hechos cambios y el archivo existe
        } elseif (isset($_POST[$id_campo]['2']) && ($_POST[$id_campo]['2'] != '')) {

            // paso el nombre de la imagen
            $mostrar = '<input type="hidden" name="' . $id_campo . '[0]" id="' . $id_campo . '" value="' . $valor3 . '" />';
            // accion a realizar
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[1]" id="' . $id_campo . '_b" value="actualizar" />';
            // paso el nombre de la imagen termporal para eliminar
            $mostrar .= '<input type="hidden" name="' . $id_campo . '[2]" id="' . $id_campo . '" value="' . $valor3 . '" />';
            // muestro la imagen de muestra
            $nombre_real = explode('_', $_POST['cp_' . $this->_dcp['cp_id']]['2'], 4);
            $nombre_real = $nombre_real[3];
            $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '2', 'tipo' => 'ArchivoDirectorio', 'ver' => 'definitiva', 'id_componente' => $this->_idComponente, 'archivo_nombre' => $valor3, 'nombre' => $nombre_real), 's');
            $mostrar .= '<a href="' . $link . '" />' . $nombre_real . '</a>';

            // si no llega nada muestra el archivo original
        } elseif ($this->_valor != '') {

            $nombre_real = explode('_', $this->_valor, 3);
            $nombre_real = $nombre_real[2];
            $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '2', 'tipo' => 'ArchivoDirectorio', 'ver' => 'definitiva', 'id_componente' => $this->_idComponente, 'archivo_nombre' => $this->_valor, 'nombre' => $nombre_real), 's');
            $mostrar .= '<a href="' . $link . '" />' . $nombre_real . '</a>';

            // si no se han hechos cambios y el archivo no existe
        } else {
            $mostrar .= '{TR|o_sin_archivo}';
        }

        return $mostrar;
    }

    private function _obtenerDirectorio() {

        if (isset($this->_dcp['directorio'])) {

            // guardo la imagen original en el directorio de destino con nombre t_g_[id-tabla]_[id-registro]_xxx
            $url_actual = getcwd();
            chdir(Inicio::pathPublico());
            chdir($this->_dcp['directorio']);
            $directorio = getcwd();
            chdir($url_actual);

            return $directorio;
        } else {

            return false;
        }
    }

    private function _obtenerIdRegistroTmp() {

        $id_campo = 'cp_' . $this->_dcp['cp_id'];
        $id_tmp = $this->_directorio . '/.' . $id_campo . '_id_tmp_contador.kirke';

        if (file_exists($id_tmp)) {
            $fh = fopen($id_tmp, 'r+');
            $contents = fread($fh, filesize($id_tmp));
            $id_nvo = $contents + 1;
            fclose($fh);
        } else {
            $id_nvo = 1;
        }
        if (file_exists($id_tmp)) {
            file_put_contents($id_tmp, $id_nvo, LOCK_EX);
        }

        return $id_nvo;
    }

}
