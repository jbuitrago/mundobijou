<?php

class Componentes_Desplegable_ComponenteInsercion {

    private $_nombreComponente;
    private $_tbTituloIdioma = array(); // textos en idiomas a cargar
    private $_idioma = array(); // pasa el idioma ej: 'es'
    private $_dcp_origen; // datos del componente de origen
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
        $this->_pv = Componentes_Componente::componente($this->_nombreComponente, 'ParametrosValores');
        $this->_sufijo = Bases_InyeccionSql::consulta($_POST['sufijo']);
    }

    public function get() {
        $this->_insertarConfiguracion();
    }

    private function _insertarConfiguracion() {

        // obtengo nombre y tipo de tabla actual
        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();
        $tb_nombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
        $tb_tipo = $datos_tabla['tipo'];

        // obtencion del campo de origen, es el id de la tabla de origen
        // Por si se carga un prefijo
        if ($this->_sufijo == '') {
            $sufijo = '';
        } else {
            if (preg_match("/[a-zA-Z0-9]+/", $this->_sufijo)) {
                $sufijo = '_' . $this->_sufijo;
            } else {
                $sufijo = '';
            }
        }

        if ($_GET['accion'] == 'ComponenteAltaInsercion') {

            // datos del componente de origen
            $this->_dcp_origen = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado(Bases_InyeccionSql::consulta($_POST['origen_cp_id']));

            $tb_campo = 'id_' . $this->_dcp_origen['tb_prefijo'] . '_' . $this->_dcp_origen['tb_nombre'] . $sufijo;

            // creo el objeto 'componente_carga' para el alta
            $componente_carga = new Acciones_ComponenteAltaInsercion();
            // crear componente y obtener id de insercion
            $id_componente = $componente_carga->crearComponente($_GET['id_tabla'], $this->_nombreComponente, $tb_campo);

            // se inserta los id del componente linkeado
            $componente_carga->consultaParametro($id_componente, 'origen_cp_id', $this->_dcp_origen['cp_id']);

            // agregar o modificar columna en tabla 'tipo registro' o 'tipo variable'
            if ($tb_tipo == 'registros') {
                    if ($_POST['obligatorio'] == 'no_nulo') {
                        $es_nulo = false;
                    } else {
                        $es_nulo = true;
                    }
                $componente_carga->crearModificarColumna($tb_nombre, $tb_campo, '', $this->_pv['tipo_dato'], 12, $es_nulo, true);
            } elseif ($tb_tipo == 'variables') {
                $componente_carga->consultaRegistro($tb_nombre, $tb_campo);
            }

            // agrega o elimina el link en 'id_tabla_parametro' para poder
            // acceder desde la tabla de origen al registro que lo utiliza, siempre lo carga
            $this->insertar_link_a_grupo($id_componente);
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {

            // se le pasa el id para poder hacer las modificaciones
            $id_componente = $_GET['cp_id'];

            $_dcp_param = Consultas_ComponenteParametro::RegistroConsulta(__FILE__, __LINE__, $id_componente, 'sufijo');

            // creo el objeto 'componente_carga' para la modificacion
            $componente_carga = new Acciones_ComponenteModificacionInsercion();

            if ($sufijo != '_' . $_dcp_param[0]['valor']) {

                $_dcp = Consultas_Componente::RegistroConsulta(__FILE__, __LINE__, $id_componente);

                if ($sufijo == '') {
                    if ($_dcp_param[0]['valor'] != '') {
                        $tb_campo_nvo = substr($_dcp[0]['tabla_campo'], 0, -(strlen($_dcp_param[0]['valor']) + 1));
                    } else {
                        $tb_campo_nvo = $_dcp[0]['tabla_campo'];
                    }
                } elseif (($sufijo != '') && ($_dcp_param[0]['valor'] == '')) {
                    $tb_campo_nvo = $_dcp[0]['tabla_campo'] . $sufijo;
                } elseif (($sufijo != '') && ($_dcp_param[0]['valor'] != '')) {
                    $tb_campo_nvo = $this->reemplazar_ultimo('_' . $_dcp_param[0]['valor'], $sufijo, $_dcp[0]['tabla_campo']);
                }

                $componente_carga->modificarTablaCampo($tb_campo_nvo);

                // agregar o modificar columna en tabla 'tipo registro' o 'tipo variable'
                if ($tb_tipo == 'registros') {
                    if ($_POST['obligatorio'] == 'no_nulo') {
                        $es_nulo = false;
                    } else {
                        $es_nulo = true;
                    }
                    $componente_carga->crearModificarColumna($tb_nombre, $tb_campo_nvo, $_dcp[0]['tabla_campo'], $this->_pv['tipo_dato'], 12, $es_nulo, true);
                } elseif ($tb_tipo == 'variables') {
                    $componente_carga->consultaRegistro($tb_nombre, $tb_campo_nvo, $_dcp[0]['tabla_campo']);
                }
            }
        }

        // agregar parametros al componente

        foreach ($this->_tbTituloIdioma as $key => $value) {
            $componente_carga->consultaParametro($id_componente, 'idioma_' . $this->_idioma[$key], $this->_tbTituloIdioma[$key]);
        }
        if ($_POST['seleccionar_alta'] != $this->_pv['seleccionar_alta']) {
            $componente_carga->consultaParametro($id_componente, 'seleccionar_alta', Bases_InyeccionSql::consulta($_POST['seleccionar_alta']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'seleccionar_alta');
        }
        if ($_POST['link_a_grupo'] != $this->_pv['link_a_grupo']) {
            $componente_carga->consultaParametro($id_componente, 'link_a_grupo', Bases_InyeccionSql::consulta($_POST['link_a_grupo']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'link_a_grupo');
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
        if ($_POST['motrar_id'] != $this->_pv['motrar_id']) {
            $componente_carga->consultaParametro($id_componente, 'motrar_id', Bases_InyeccionSql::consulta($_POST['motrar_id']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'motrar_id');
        }
        if ($_POST['sufijo'] != $this->_pv['sufijo']) {
            $componente_carga->consultaParametro($id_componente, 'sufijo', Bases_InyeccionSql::consulta($_POST['sufijo']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'sufijo');
        }
        if ($_POST['filtrar'] != $this->_pv['filtrar']) {
            $componente_carga->consultaParametro($id_componente, 'filtrar', Bases_InyeccionSql::consulta($_POST['filtrar']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'filtrar');
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
        if ($_POST['campo'] != $this->_pv['campo']) {
            $componente_carga->consultaParametro($id_componente, 'campo', Bases_InyeccionSql::consulta($_POST['campo']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'campo');
        }
        if ($_POST['campo_filtro'] != $this->_pv['campo_filtro']) {
            $componente_carga->consultaParametro($id_componente, 'campo_filtro', Bases_InyeccionSql::consulta($_POST['campo_filtro']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'campo_filtro');
        }
        if ($_POST['condicion'] != $this->_pv['condicion']) {
            $componente_carga->consultaParametro($id_componente, 'condicion', Bases_InyeccionSql::consulta($_POST['condicion']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'condicion');
        }
        if ($_POST['valor'] != $this->_pv['valor']) {
            $componente_carga->consultaParametro($id_componente, 'valor', Bases_InyeccionSql::consulta($_POST['valor']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'valor');
        }
        if ($_POST['separador_del_campo_1'] != $this->_pv['separador_del_campo_1']) {
            $componente_carga->consultaParametro($id_componente, 'separador_del_campo_1', Bases_InyeccionSql::consulta($_POST['separador_del_campo_1']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'separador_del_campo_1');
        }
        if ($_POST['nombre_del_campo_1'] != $this->_pv['nombre_del_campo_1']) {
            $componente_carga->consultaParametro($id_componente, 'nombre_del_campo_1', Bases_InyeccionSql::consulta($_POST['nombre_del_campo_1']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'nombre_del_campo_1');
        }        
        if ($_POST['separador_del_campo_2'] != $this->_pv['separador_del_campo_2']) {
            $componente_carga->consultaParametro($id_componente, 'separador_del_campo_2', Bases_InyeccionSql::consulta($_POST['separador_del_campo_2']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'separador_del_campo_2');
        }
        if ($_POST['nombre_del_campo_2'] != $this->_pv['nombre_del_campo_2']) {
            $componente_carga->consultaParametro($id_componente, 'nombre_del_campo_2', Bases_InyeccionSql::consulta($_POST['nombre_del_campo_2']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'nombre_del_campo_2');
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
        if ($_POST['orden'] != $this->_pv['orden']) {
            $componente_carga->consultaParametro($id_componente, 'orden', Bases_InyeccionSql::consulta($_POST['orden']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'orden');
        }
        if ($_POST['predefinir_ultimo_val_cargado'] != $this->_pv['predefinir_ultimo_val_cargado']) {
            $componente_carga->consultaParametro($id_componente, 'predefinir_ultimo_val_cargado', Bases_InyeccionSql::consulta($_POST['predefinir_ultimo_val_cargado']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'predefinir_ultimo_val_cargado');
        }
        if ($_POST['agregar_registro'] != $this->_pv['agregar_registro']) {
            $componente_carga->consultaParametro($id_componente, 'agregar_registro', Bases_InyeccionSql::consulta($_POST['agregar_registro']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'agregar_registro');
        }
    }

    private function insertar_link_a_grupo($id_componente) {

        $matriz = Consultas_TablaParametros::RegistroConsulta(__FILE__, __LINE__, $this->_dcp_origen['tb_id'], $id_componente);

        if (!is_array($matriz)) {

            Consultas_TablaParametros::RegistroCrear(__FILE__, __LINE__, $this->_dcp_origen['tb_id'], $id_componente);
        }
    }

    private function reemplazar_ultimo($buscar, $remplazar, $texto) {
        $pos = strrpos($texto, $buscar);
        if ($pos !== false) {
            $texto = substr_replace($texto, $remplazar, $pos, strlen($buscar));
        }
        return $texto;
    }

}
