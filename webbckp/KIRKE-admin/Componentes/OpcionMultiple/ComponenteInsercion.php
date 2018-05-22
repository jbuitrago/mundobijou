<?php

class Componentes_OpcionMultiple_ComponenteInsercion {

    private $_nombreComponente;
    private $_tbTituloIdioma = array(); // textos en idiomas a cargar
    private $_idioma = array(); // pasa el idioma ej: 'es'
    private $_tbCampo; // nombre del campo
    private $_dcp_origen; // datos del componente de origen
    private $_intermedia_tb_nombre; // tabla creada especialmente para la relacion
    private $_intermedia_tb_prefijo; // prefijo de la tabla creada especialmente para la relacion
    private $_intermedia_tb_prefijo_texto; // prefijo de la tabla creada especialmente para la relacion
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

        // datos del componente de origen
        if (isset($_POST['origen_cp_id'])) {
            $this->_dcp_origen = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado(Bases_InyeccionSql::consulta($_POST['origen_cp_id']));
        }

        // tabla intermedia
        if (isset($_POST['nombre_tabla_relacion'])) {
            $this->_intermedia_tb_nombre = Bases_InyeccionSql::consulta($_POST['nombre_tabla_relacion']);
        }
        
        $this->id_intermedia_tb_prefijo = Bases_InyeccionSql::consulta($_POST['tabla_prefijo']);
        
        // controla si la tabla ya existe
        $tabla_existente = Consultas_Tabla::RegistroConsulta(__FILE__, __LINE__, $this->_intermedia_tb_nombre, $this->id_intermedia_tb_prefijo);
        if ($tabla_existente != '') {
            // la redireccion va al final
            $armado_botonera = new Armado_Botonera();
            $parametros = array('kk_generar' => '0', 'accion' => '5', 'id_tabla' => $_GET['id_tabla']);
            $armado_botonera->armar('redirigir', $parametros);
        }
        
        $id_prefijo_array = Consultas_MatrizPrefijos::armado($this->id_intermedia_tb_prefijo);
        $this->_intermedia_tb_prefijo_texto = $id_prefijo_array[0]['prefijo'];

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

        // obtengo nombre y tipo de tabla origen
        $tb_origen_nombre = $this->_dcp_origen['tb_prefijo'] . '_' . $this->_dcp_origen['tb_nombre'];

        if ($_GET['accion'] == 'ComponenteAltaInsercion') {

            // creo el objeto 'componente_carga' para el alta
            $componente_carga = new Acciones_ComponenteAltaInsercion();
            // crear componente y obtener id de insercion
            $id_componente = $componente_carga->crearComponente($_GET['id_tabla'], $this->_nombreComponente, '');
            // se inserta los id del componente linkeado
            $componente_carga->consultaParametro($id_componente, 'origen_cp_id', $this->_dcp_origen['cp_id']);

            // el siguiente codigo es valido tanto para la tabla en formato registros como variables
            // crear tabla de relacion
            Consultas_TablaCrear::armado(__FILE__, __LINE__, $this->_intermedia_tb_nombre, $this->_intermedia_tb_prefijo_texto);

            $tabla_intermedia = $this->_intermedia_tb_prefijo_texto . '_' . $this->_intermedia_tb_nombre;
            // agregar modificar columna id en tabla de relacion (referencia a campo tabla destino)
            $componente_carga->crearModificarColumna($tabla_intermedia, 'id_' . $tb_nombre, '', $this->_pv['tipo_dato'], 12, false, true);
            // agregar modificar columna id en tabla de relacion (tabla origen)
            $componente_carga->crearModificarColumna($tabla_intermedia, 'id_' . $tb_origen_nombre, '', $this->_pv['tipo_dato'], 12, false, true);

            // crear registro de pagina
            $orden = Consultas_ObtenerRegistroMaximo::armado('kirke_tabla', 'orden');

            $id_insertado = Consultas_Tabla::RegistroCrear(__FILE__, __LINE__, $this->id_intermedia_tb_prefijo, $orden[0]['orden'] + 1, $this->_intermedia_tb_nombre, 's', 'componente');
            $id_tabla_crear = $id_insertado['id'];

            // parametro del id de tabla de referencia
            $componente_carga->consultaParametro($id_componente, 'intermedia_tb_id', $id_tabla_crear);
            
            // agrega o elimina el link en 'id_tabla_parametro' para poder
            // acceder desde la tabla de origen al registro que lo utiliza, siempre lo carga
            $this->insertar_link_a_grupo($id_componente);
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {

            // creo el objeto 'componente_carga' para la modificacion
            $componente_carga = new Acciones_ComponenteModificacionInsercion();

            // se le pasa el id para poder hacer las modificaciones
            $id_componente = $_GET['cp_id'];
        }

        // agregar parametros al compoenente

        foreach ($this->_tbTituloIdioma as $key => $value) {
            $componente_carga->consultaParametro($id_componente, 'idioma_' . $this->_idioma[$key], $this->_tbTituloIdioma[$key]);
        }

        if ($_POST['link_a_grupo'] != $this->_pv['link_a_grupo']) {
            $componente_carga->consultaParametro($id_componente, 'link_a_grupo', Bases_InyeccionSql::consulta($_POST['link_a_grupo']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'link_a_grupo');
        }
        if ($_POST['obligatorio'] != $this->_pv['obligatorio']) {
            $componente_carga->consultaParametro($id_componente, 'obligatorio', Bases_InyeccionSql::consulta($_POST['obligatorio']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'obligatorio');
        }
        if ($_POST['eliminar_tb_relacionada'] != $this->_pv['eliminar_tb_relacionada']) {
            $componente_carga->consultaParametro($id_componente, 'eliminar_tb_relacionada', Bases_InyeccionSql::consulta($_POST['eliminar_tb_relacionada']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'eliminar_tb_relacionada');
        }
        if ($_POST['filtrar'] != $this->_pv['filtrar']) {
            $componente_carga->consultaParametro($id_componente, 'filtrar', Bases_InyeccionSql::consulta($_POST['filtrar']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'filtrar');
        }
        if ($_POST['filtrar_texto'] != $this->_pv['filtrar_texto']) {
            $componente_carga->consultaParametro($id_componente, 'filtrar_texto', Bases_InyeccionSql::consulta($_POST['filtrar_texto']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'filtrar_texto');
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
        
        // para que llame al archivo RegistroBaja.php y realice tareas especiales. No sirve agregarlo como valor predefinido.
        $componente_carga->consultaParametro($id_componente, 'eliminacion_especial', 's');
        
        // para que no muestre las cantidades en el link de la tabla relacionada
        $componente_carga->consultaParametro($id_componente, 'link_a_grupo_cantidad', 'n');
    }

    private function insertar_link_a_grupo($id_componente) {

        $matriz = Consultas_TablaParametros::RegistroConsulta(__FILE__, __LINE__, $this->_dcp_origen['tb_id'], $id_componente);

        if (!is_array($matriz)) {

            Consultas_TablaParametros::RegistroCrear(__FILE__, __LINE__, $this->_dcp_origen['tb_id'], $id_componente);
        }
    }
    
}
