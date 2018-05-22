<?php

class Componentes_ImagenDirectorio_ComponenteInsercion {

    private $_nombreComponente;
    private $_tbTituloIdioma = array(); // textos en idiomas a cargar
    private $_idioma = array(); // pasa el idioma ej: 'es'
    private $_tbCampo; // nombre del campo
    private $_pv;

    function __construct() {
        $this->_nombreComponente = Generales_ObtenerNombreComponente::get(__FILE__);
    }

    public function set() {

        // obtiencion de etiquetas de idiomas
        if (is_array(Inicio::confVars('idiomas'))) {
            $contador = 0;
            foreach (Inicio::confVars('idiomas') as $key => $value) {
                $idioma_s_ext = substr($value, 0, 2);
                $this->_idioma[$contador] = $value;
                $this->_tbTituloIdioma[$contador] = Bases_InyeccionSql::consulta($_POST['etiqueta_' . $idioma_s_ext]);
                $contador++;
            }
        }
        $this->_tbCampo = Generales_LimpiarTextos::alfanumericoGuiones(Bases_InyeccionSql::consulta($_POST['nombre_campo']));
        $this->_pv = Componentes_Componente::componente($this->_nombreComponente, 'ParametrosValores');
        if (isset($_POST['directorio']) && ($_POST['directorio'] == '')) {
            $this->_pv['directorio'] = Bases_InyeccionSql::consulta($_POST['directorio']);
        }
    }

    public function get() {
        $this->_insertarConfiguracion();
    }

    private function _insertarConfiguracion() {

        // obtengo nombre y tipo de tabla actual
        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();
        $tb_nombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
        $tb_tipo = $datos_tabla['tipo'];

        if ($_GET['accion'] == 'ComponenteAltaInsercion') {

            // creacion del directorio
            $directorio = $this->_obtenerDirectorio();
            if (!file_exists($directorio)) {
                mkdir($directorio, 0775);
            }

            // directorio de imagenes de muestra
            $dir_muestra = $directorio . '/.' . $this->_nombreComponente . '.kirke';
            if (!file_exists($dir_muestra)) {
                mkdir($dir_muestra, 0775);
            }

            // creo el objeto 'componente_carga' para el alta
            $componente_carga = new Acciones_ComponenteAltaInsercion();
            // crear componente y obtener id de insercion
            $id_componente = $componente_carga->crearComponente($_GET['id_tabla'], $this->_nombreComponente, $this->_tbCampo);
            $tb_campo_anterior = '';
            
            $componente_carga->consultaParametro($id_componente, 'directorio', $this->_pv['directorio']);
            
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {

            // creo el objeto 'componente_carga' para la modificacion
            $componente_carga = new Acciones_ComponenteModificacionInsercion();
            // se obtiene nombre de campo en tabla anterior
            $tb_campo_anterior = $componente_carga->tbCampoAnterior();
            // se le pasa el id para poder hacer las modificaciones
            $id_componente = $_GET['cp_id'];
            // modificar 'tabla_campo'
            $componente_carga->modificarTablaCampo($this->_tbCampo);
        }

        // agregar o modificar columna en tabla 'tipo registro' o 'tipo variable'
        if ($tb_tipo == 'registros') {
            if ($_POST['obligatorio'] == 'no_nulo') {
                $es_nulo = false;
            } else {
                $es_nulo = true;
            }
            $componente_carga->crearModificarColumna($tb_nombre, $this->_tbCampo, $tb_campo_anterior, $this->_pv['tipo_dato'], '', $es_nulo);
        } elseif ($tb_tipo == 'variables') {
            $componente_carga->consultaRegistro($tb_nombre, $this->_tbCampo, $tb_campo_anterior);
        }

        // agregar parametros al compoenente

        foreach ($this->_tbTituloIdioma as $key => $value) {
            $componente_carga->consultaParametro($id_componente, 'idioma_' . $this->_idioma[$key], $this->_tbTituloIdioma[$key]);
        }

        if ($_POST['alto_final'] != $this->_pv['alto_final']) {
            $componente_carga->consultaParametro($id_componente, 'alto_final', Bases_InyeccionSql::consulta($_POST['alto_final']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'alto_final');
        }

        if ($_POST['ancho_final'] != $this->_pv['ancho_final']) {
            $componente_carga->consultaParametro($id_componente, 'ancho_final', Bases_InyeccionSql::consulta($_POST['ancho_final']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'ancho_final');
        }

        if ($_POST['listado_mostrar'] != $this->_pv['listado_mostrar']) {
            $componente_carga->consultaParametro($id_componente, 'listado_mostrar', Bases_InyeccionSql::consulta($_POST['listado_mostrar']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'listado_mostrar');
        }

        if ($_POST['obligatorio'] != $this->_pv['obligatorio']) {
            $componente_carga->consultaParametro($id_componente, 'obligatorio', Bases_InyeccionSql::consulta($_POST['obligatorio']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'obligatorio');
        }

        if ($_POST['eliminar_imagenes'] != $this->_pv['eliminar_imagenes']) {
            $componente_carga->consultaParametro($id_componente, 'eliminar_imagenes', Bases_InyeccionSql::consulta($_POST['eliminar_imagenes']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'eliminar_imagenes');
        }

        if ($_POST['filtrar'] != $this->_pv['filtrar']) {
            $componente_carga->consultaParametro($id_componente, 'filtrar', Bases_InyeccionSql::consulta($_POST['filtrar']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'filtrar');
        }

        if ($_POST['separar_en_directorios'] != $this->_pv['separar_en_directorios']) {
            $componente_carga->consultaParametro($id_componente, 'separar_en_directorios', Bases_InyeccionSql::consulta($_POST['separar_en_directorios']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'separar_en_directorios');
        }

        for ($i = 1; $i <= 10; $i++) {
            if ($_POST['prefijo_' . $i] != $this->_pv['prefijo_' . $i]) {
                $componente_carga->consultaParametro($id_componente, 'prefijo_' . $i, Bases_InyeccionSql::consulta($_POST['prefijo_' . $i]));
                if ($_POST['ancho_' . $i] != $this->_pv['ancho_' . $i]) {
                    $componente_carga->consultaParametro($id_componente, 'ancho_' . $i, Bases_InyeccionSql::consulta($_POST['ancho_' . $i]));
                } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
                    $componente_carga->consultaParametroEliminar($id_componente, 'ancho_' . $i);
                }
                if ($_POST['alto_' . $i] != $this->_pv['alto_' . $i]) {
                    $componente_carga->consultaParametro($id_componente, 'alto_' . $i, Bases_InyeccionSql::consulta($_POST['alto_' . $i]));
                } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
                    $componente_carga->consultaParametroEliminar($id_componente, 'alto_' . $i);
                }
            } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
                $componente_carga->consultaParametroEliminar($id_componente, 'prefijo_' . $i);
                $componente_carga->consultaParametroEliminar($id_componente, 'ancho_' . $i);
                $componente_carga->consultaParametroEliminar($id_componente, 'alto_' . $i);
            }
        }
        
        if ($_POST['ocultar_edicion'] != $this->_pv['ocultar_edicion']) {
            $componente_carga->consultaParametro($id_componente, 'ocultar_edicion', Bases_InyeccionSql::consulta($_POST['ocultar_edicion']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'ocultar_edicion');
        }
        if ($_POST['ocultar_vista'] != $this->_pv['ocultar_vista']) {
            $componente_carga->consultaParametro($id_componente, 'ocultar_vista', Bases_InyeccionSql::consulta($_POST['ocultar_vista']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'ocultar_vista');
        }
  
        // para que llame al archivo RegistroBaja.php y realice tareas especiales. No sirve agregarlo como valor predefinido.
        $componente_carga->consultaParametro($id_componente, 'eliminacion_especial', 's');
    }

    private function _obtenerDirectorio() {

        // obtengo el directirio donde se guardaran ala imaganes
        $url_actual = getcwd();
        chdir(Inicio::pathPublico());
        $directorio_crear = getcwd() . '/' . $this->_pv['directorio'];
        chdir($url_actual);
        return $directorio_crear;
    }

}
