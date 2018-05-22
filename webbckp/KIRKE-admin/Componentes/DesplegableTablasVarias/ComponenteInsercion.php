<?php

class Componentes_DesplegableTablasVarias_ComponenteInsercion {

    private $_nombreComponente;
    private $_tbTituloIdioma = array(); // textos en idiomas a cargar
    private $_idioma = array(); // pasa el idioma ej: 'es'
    private $_tbCampo; // nombre del campo
    private $_campo; // es obligatorio su carga    
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
        $this->_tbCampo = Bases_InyeccionSql::consulta($_POST['nombre_campo']);
        $this->_pv = Componentes_Componente::componente($this->_nombreComponente, 'ParametrosValores');
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

            // creo el objeto 'componente_carga' para el alta
            $componente_carga = new Acciones_ComponenteAltaInsercion();
            // crear componente y obtener id de insercion
            $id_componente = $componente_carga->crearComponente($_GET['id_tabla'], $this->_nombreComponente, $this->_tbCampo);

            $valore_relacionados = '';

            if ($_POST['origen_cp_id'] != '') {
                $valore_relacionados = $_POST['origen_cp_id'];
            }

            for ($i = 0; $i <= 9; $i++) {
                if (isset($_POST['desplegable_' . $i]) && ($_POST['desplegable_' . $i] != '')) {
                    $valore_relacionados .= ',' . $_POST['desplegable_' . $i];
                }
            }

            $componente_carga->consultaParametro($id_componente, 'origen_cp_id_otros', $valore_relacionados);
            $tb_campo_anterior = '';
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
            $componente_carga->crearModificarColumna($tb_nombre, $this->_tbCampo, $tb_campo_anterior, $this->_pv['tipo_dato'], 200, $es_nulo);
        } elseif ($tb_tipo == 'variables') {
            $componente_carga->consultaRegistro($tb_nombre, $this->_tbCampo, $tb_campo_anterior);
        }

        // agregar parametros al componente

        $componente_carga->consultaParametro($id_componente, 'tb_columna_tipo', $this->_pv['tb_columna_tipo']);

        foreach ($this->_tbTituloIdioma as $key => $value) {
            $componente_carga->consultaParametro($id_componente, 'idioma_' . $this->_idioma[$key], $this->_tbTituloIdioma[$key]);
        }

        $componente_carga->consultaParametro($id_componente, 'campo', Bases_InyeccionSql::consulta($_POST['campo']));

        if ($_POST['motrar_nombre_tabla'] != $this->_pv['motrar_nombre_tabla']) {
            $componente_carga->consultaParametro($id_componente, 'motrar_nombre_tabla', Bases_InyeccionSql::consulta($_POST['motrar_nombre_tabla']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'motrar_nombre_tabla');
        }
        if ($_POST['motrar_id'] != $this->_pv['motrar_id']) {
            $componente_carga->consultaParametro($id_componente, 'motrar_id', Bases_InyeccionSql::consulta($_POST['motrar_id']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'motrar_id');
        }
        if ($_POST['obligatorio'] != $this->_pv['obligatorio']) {
            $componente_carga->consultaParametro($id_componente, 'obligatorio', Bases_InyeccionSql::consulta($_POST['obligatorio']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'obligatorio');
        }
        if ($_POST['autofiltro'] != $this->_pv['autofiltro']) {
            $componente_carga->consultaParametro($id_componente, 'autofiltro', Bases_InyeccionSql::consulta($_POST['autofiltro']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'autofiltro');
        }
        if ($_POST['autofiltro_elementos'] != $this->_pv['autofiltro_elementos']) {
            $componente_carga->consultaParametro($id_componente, 'autofiltro_elementos', Bases_InyeccionSql::consulta($_POST['autofiltro_elementos']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'autofiltro_elementos');
        }
        if ($_POST['filtrar_antiguedad'] != $this->_pv['filtrar_antiguedad']) {
            $componente_carga->consultaParametro($id_componente, 'filtrar_antiguedad', Bases_InyeccionSql::consulta($_POST['filtrar_antiguedad']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'filtrar_antiguedad');
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
        if ($_POST['predefinir_ultimo_val_cargado'] != $this->_pv['predefinir_ultimo_val_cargado']) {
            $componente_carga->consultaParametro($id_componente, 'predefinir_ultimo_val_cargado', Bases_InyeccionSql::consulta($_POST['predefinir_ultimo_val_cargado']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'predefinir_ultimo_val_cargado');
        }
    }

}
