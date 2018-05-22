<?php

class Componentes_DesplegableTablasVarias_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $valor;
    private $_metodo;                // metodo a llamar
    private $_dcp = array(); // parametros del componente
    private $dcp_origen = array(); // datos del componente que contiene los datos de origen
    private $_idComponente;         // id del componente
    private $_idRegistro;           // id del registro
    private $id_relaciones_param; // parametros de los componentes relacionados
    private $_despelgableLink; // todos los componentes que conformar los desplegables
    private $_matriz_links;

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

            $mostrar = $this->_mostrarValorPrevia($this->_valor) . '<br>';
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroAlta() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            // encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);
            // encabezados para el autocomplete
            if ($this->_dcp['autofiltro'] == 's') {
                Armado_Cabeceras::autocomplete();
            }

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $this->_valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } elseif (($this->_dcp['predefinir_ultimo_val_cargado'] == 's') && !isset($_POST[$id_campo]) && isset($_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)])) {
                $this->_valor = $_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)];
            }

            if ($this->_dcp['autofiltro'] == 's') {
                $mostrar = $this->_despelgableLinkAutocomplete();
            } else {
                $mostrar = $this->_despelgableLink();
            }

            return $this->_tituloYComponente($mostrar);
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
            $mostrar .= $this->_mostrarValorPrevia($valor) . '<br>';
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacion() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            // encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);
            // encabezados para el autocomplete
            if ($this->_dcp['autofiltro'] == 's') {
                Armado_Cabeceras::autocomplete();
            }

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $this->_valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            }

            if ($this->_dcp['autofiltro'] == 's') {
                $mostrar = $this->_despelgableLinkAutocomplete();
            } else {
                $mostrar = $this->_despelgableLink();
            }

            return $this->_tituloYComponente($mostrar);
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
            $mostrar .= $this->_mostrarValorPrevia($valor) . '<br>';
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

        if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] == 'administrador general') {
            $plantilla['campo_nombre'] = '<br /><span class="texto_nombre_campos">( ' . $this->_dcp['tb_campo'] . ' ) </span><span class="texto_id_campos"> ( ' . $this->_dcp['cp_id'] . ' )</span>';
        } else {
            $plantilla['campo_nombre'] = '';
        }

        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

    private function _despelgableLink() {

        $id_campo = 'cp_' . $this->_dcp['cp_id'];

        $campo_error = '';

        if (($this->_dcp['obligatorio'] == 'no_nulo') && ($this->_metodo != 'registroFiltroMenu')) {
            $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';
            $campo_error = '<div class="VC_error" id="VC_' . $id_campo . '"></div>';
        } else {
            $obligatorio = '';
        }

        if ($this->_matrizLinks()) {

            $select_link = '<select name="' . $id_campo . '" id="' . $id_campo . '" ' . $obligatorio . '>';
            $select_link .= '<option value="" >{TR|m_seleccione_una_opcion}</option>';
            if (is_array($this->matriz_links)) {
                foreach ($this->matriz_links as $linea) {

                    $valor = $linea['id_registro'] . ';' . $linea['tabla'];
                    $texto = $linea['valor'];

                    if ($this->_dcp['motrar_nombre_tabla'] == 's') {
                        $texto .= ' [' . $linea['tabla_nombre'] . ']';
                    }
                    if ($this->_dcp['motrar_id'] == 's') {
                        $texto .= ' (' . $linea['id_registro'] . ')';
                    }

                    $seleccionado = '';
                    // selecciona el valor anterior en la edicion
                    if ($valor == $this->_valor) {
                        $seleccionado = 'selected';
                    }
                    $select_link .= '<option value="' . $valor . '" ' . $seleccionado . '>' . $texto . '</option>';
                }
            }
            $select_link .= '</select>' . $campo_error;
            return $select_link;
        } else {
            return '{TR|o_las_tablas_origen_no_contiene_datos_o_no_existen}';
        }
    }

    private function _despelgableLinkAutocomplete() {

        if ($this->_matrizLinks()) {

            // Se agrego el parametro "cellSeparator" en autocomplete.js

            $id_campo = 'cp_' . $this->_dcp['cp_id'];

            $select_link = '
            <script type="text/javascript">
            $(document).ready(function(){
                $("#' . $id_campo . '_ajax").autocomplete("index.php?kk_generar=3&componente=DesplegableTablasVarias&archivo=RegistroVer.php&id_cp=' . $this->_dcp['cp_id'] . '", { 
                    matchContains: true, 
                    minChars: 1, 
                    mustMatch: false,
                    selectFirst: true,
                    cacheLength: 0,
                    cellSeparator: "<|>",
                    max: 1000,
                } );
                $("#' . $id_campo . '_ajax").result(function(event, data, formatted) {
                    $("#' . $id_campo . '").val(data[1]);
                });
                $("#' . $id_campo . '_ajax").focusout(function() {
                    if( $("#' . $id_campo . '_ajax").val()=="" ){
                        $("#' . $id_campo . '").val("");
                    }
                });
            });
            </script>
            ';

            $campo_error = '';

            if (($this->_dcp['obligatorio'] == 'no_nulo') && ($this->_metodo != 'registroFiltroMenu')) {
                $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';
                $campo_error = '<div class="VC_error" id="VC_' . $id_campo . '"></div>';
            } else {
                $obligatorio = '';
            }

            $select_link .= '<input type="text" name="' . $id_campo . '_ajax" id="' . $id_campo . '_ajax" value="' . $this->_mostrarValorPrevia($this->_valor) . '" ><br>';
            $select_link .= '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $this->_valor . '" ' . $obligatorio . ' >';

            return $select_link;
        } else {
            return '{TR|o_la_tabla_origen_no_contiene_datos_o_no_existe}';
        }
    }

    private function _mostrarValorPrevia($id_tabla_valor) {
        if ($id_tabla_valor != '') {

            $id_tabla_valor = explode(';', $id_tabla_valor);
            $tb_nombre = $id_tabla_valor[1];
            $id_origen = $id_tabla_valor[0];

            $matriz_links_cp = explode(',', $this->_dcp['origen_cp_id_otros']);
            foreach ($matriz_links_cp as $linea) {
                $matriz_componentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($linea);
                if (is_array($matriz_componentes)) {
                    if ($matriz_componentes['tb_prefijo'] . '_' . $matriz_componentes['tb_nombre'] == $tb_nombre) {
                        $tb_id = $matriz_componentes['tb_id'];
                        $tb_nombre_idioma = $matriz_componentes['idioma_' . Generales_Idioma::obtener()];
                        $tb_campo = $matriz_componentes['tb_campo'];
                    }
                }
            }

            if (isset($tb_campo)) {

                $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
                $consulta->tablas($tb_nombre);
                $consulta->campos($tb_nombre, $tb_campo);
                $consulta->condiciones('', $tb_nombre, 'id_' . $tb_nombre, 'iguales', '', '', $id_origen);
                $valor_campo = $consulta->realizarConsulta();

                $texto = $valor_campo[0][$tb_campo];

                if (($texto != '') && ($this->_dcp['motrar_nombre_tabla'] == 's')) {
                    $datos_tabla = Consultas_TablaNombreIdioma::RegistroConsulta(__FILE__, __LINE__, Generales_Idioma::obtener(), $tb_id);
                    $texto .= ' [' . $datos_tabla[0]['tabla_nombre_idioma'] . ']';
                }
                if (($texto != '') && ($this->_dcp['motrar_id'] == 's')) {
                    $texto .= ' (' . $id_origen . ')';
                }

                $texto = strtr($texto, '"', "'");
            } else {
                $texto = '';
            }

            return $texto;
        } else {

            return false;
        }
    }

    private function _matrizLinks() {

        $cp_ids = explode(',', $this->_dcp['origen_cp_id_otros']);

        foreach ($cp_ids as $clave => $cp_id) {
            $array_consulta = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($cp_ids[$clave]);
            if (is_array($array_consulta)) {
                $this->id_relaciones_param[] = $array_consulta;
            }
        }

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tabla');
        $consulta->tablas('kirke_tabla_nombre_idioma');
        $consulta->campos('kirke_tabla_nombre_idioma', 'tabla_nombre_idioma');
        $consulta->campos('kirke_tabla', 'id_tabla');
        $consulta->condiciones('y', 'kirke_tabla', 'tipo', 'distintos', '', '', 'componente');
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', 'kirke_tabla_nombre_idioma', 'id_tabla');
        $consulta->condiciones('y', 'kirke_tabla_nombre_idioma', 'idioma_codigo', 'iguales', '', '', Generales_Idioma::obtener());
        $consulta->orden('kirke_tabla', 'tabla_nombre');
        $tablas = $consulta->realizarConsulta();

        foreach ($tablas as $datos) {
            $tablas_id[$datos['id_tabla']] = $datos['tabla_nombre_idioma'];
        }

        if (($this->_dcp['filtrar_antiguedad'] != '') && ($this->_dcp['campo'] != '')) {
            $ant = explode(',', $this->_dcp['filtrar_antiguedad']);
            if (!isset($ant[0]))
                $ant[0] = 0;
            if (!isset($ant[1]))
                $ant[1] = 0;
            if (!isset($ant[2]))
                $ant[2] = 0;
            if (!isset($ant[3]))
                $ant[3] = 0;
            if (!isset($ant[4]))
                $ant[4] = 0;
            if (!isset($ant[5]))
                $ant[5] = 0;
            $fecha_limite = mktime(date('G') - $ant[3], date('i') - $ant[4], date('s') - $ant[5], date('m') - $ant[1], date('d') - $ant[0], date('Y') - $ant[2]);
        }

        $consulta_union = new Bases_RegistroConsulta(__FILE__, __LINE__);

        foreach ($this->id_relaciones_param as $ident => $valor) {

            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas($valor['tb_prefijo'] . '_' . $valor['tb_nombre']);
            $consulta->campos($valor['tb_prefijo'] . '_' . $valor['tb_nombre'], 'id_' . $valor['tb_prefijo'] . '_' . $valor['tb_nombre'], 'id_registro');
            $consulta->cadena($valor['tb_prefijo'] . '_' . $valor['tb_nombre'], 'tabla');
            $consulta->cadena($tablas_id[$valor['tb_id']], 'tabla_nombre');
            $consulta->campos($valor['tb_prefijo'] . '_' . $valor['tb_nombre'], $valor['tb_campo'], 'valor');

            $condiciones_ant = false;
            if (($this->_dcp['autofiltro'] == 's') && isset($_GET['q'])) {
                $consulta->condiciones('', $valor['tb_prefijo'] . '_' . $valor['tb_nombre'], $valor['tb_campo'], 'coincide', '', '', $_GET['q'], 'inicio');
                $consulta->condiciones('o', $valor['tb_prefijo'] . '_' . $valor['tb_nombre'], 'id_' . $valor['tb_prefijo'] . '_' . $valor['tb_nombre'], 'coincide', '', '', $_GET['q'], 'fin');
                $condiciones_ant = true;
            }

            if (($this->_dcp['filtrar_antiguedad'] != '') && ($this->_dcp['campo'] != '')) {
                if ($condiciones_ant === false) {
                    $consulta->condiciones('', $valor['tb_prefijo'] . '_' . $valor['tb_nombre'], $this->_dcp['campo'], 'mayor', '', '', $fecha_limite);
                } else {
                    $consulta->condiciones('y', $valor['tb_prefijo'] . '_' . $valor['tb_nombre'], $this->_dcp['campo'], 'mayor', '', '', $fecha_limite);
                }
            }
            $consulta_union->unionTabla($consulta->obtenerConsulta());
        }

        if ($this->_dcp['autofiltro'] == 's') {
            if ($this->_dcp['autofiltro_elementos'] != 0) {
                $consulta_union->limite(0, $this->_dcp['autofiltro_elementos']);
            }
        }

        $nombre_tapla_union = 'resultados_union';
        $consulta_union->orden($nombre_tapla_union, 'valor', 'ascendente');

        //echo $consulta_union->verConsulta();
        $this->matriz_links = $consulta_union->obtenerConsultaUnionTodos($nombre_tapla_union);
        return true;
    }

    private function _consultaAjax() {

        if (($this->_matrizLinks() === true) && is_array($this->matriz_links)) {
            foreach ($this->matriz_links as $linea) {
                $texto = preg_replace("[\n|\r|\n\r]", ' ', $linea['valor']);

                if ($this->_dcp['motrar_nombre_tabla'] == 's') {
                    $texto .= ' [' . $linea['tabla_nombre'] . ']';
                }
                if ($this->_dcp['motrar_id'] == 's') {
                    $texto .= ' (' . $linea['id_registro'] . ')';
                }

                echo $texto . '<|>' . $linea['id_registro'] . ';' . $linea['tabla'] . "\n";
            }
        } else {
            echo '';
        }
    }

}
