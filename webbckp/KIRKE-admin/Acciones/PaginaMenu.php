<?php

class Acciones_PaginaMenu extends Armado_Plantilla {

    private $_tabla = Array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $this->_tabla = Consultas_TablaNombreIdioma::RegistroConsultaPrefijo(__FILE__, __LINE__, $_GET['id_tabla']);

        $ver = $this->_paginaMenuDatos();

        $armado_botonera = new Armado_Botonera();

        if (isset($_GET['intermedia_tb_id']) && isset($_GET['id_tabla_trd'])) {
            $parametros = array('kk_generar' => '0', 'accion' => '66', 'id_tabla' => $_GET['id_tabla'], 'intermedia_tb_id' => $_GET['intermedia_tb_id'], 'id_tabla_trd' => $_GET['id_tabla_trd']);
        } else {
            $parametros = array('kk_generar' => '0', 'accion' => '66', 'id_tabla' => $_GET['id_tabla']);
        }
        $armado_botonera->armar('guardar', $parametros);

        return $ver;
    }

    private function _paginaMenuDatos() {

        $parametros_tabla = Consultas_TablaParametros::RegistroConsultaTodos(__FILE__, __LINE__, $_GET['id_tabla']);

        if (is_array($parametros_tabla)) {
            $parametros = array();
            foreach ($parametros_tabla as $valor) {
                $parametros[$valor['parametro']] = $valor['valor'];
            }
        }

        if (isset($parametros['numero_niveles'])) {
            $numero_niveles = $parametros['numero_niveles'];
        } else {
            $numero_niveles = 3;
        }
        $plantilla['numero_niveles'] = Armado_SelectNumeros::armado('numero_niveles', $numero_niveles, 1, 10);
        
        if (isset($parametros['niveles_protegidos'])) {
            $niveles_protegidos = $parametros['niveles_protegidos'];
        } else {
            $niveles_protegidos = 0;
        }
        $plantilla['niveles_protegidos'] = Armado_SelectNumeros::armado('niveles_protegidos', $niveles_protegidos, 0, 10);
        
        if (isset($parametros['niveles_habilitados'])) {
            $niveles_habilitados = $parametros['niveles_habilitados'];
        } else {
            $niveles_habilitados = 10;
        }
        $plantilla['niveles_habilitados'] = Armado_SelectNumeros::armado('niveles_habilitados', $niveles_habilitados, 1, 10);

        // si es un alta, sino no debe mostrarse
        if (!isset($parametros['tabla_relacionada'])) {
            $plantilla['campos_relacionados'] = '
        <div class="contenido_separador"></div>
        <div class="contenido_margen"></div>
        <div class="contenido_titulo"><span class="VC_campo_requerido">&#8226;</span> {TR|o_tabla_relacionada}</div>
        <div class="contenido_7">' . $this->_tablasCampos() . '</div>';
        } else {
            $plantilla['campos_relacionados'] = '';
        }

        return Armado_PlantillasInternas::acciones(basename(__FILE__), $plantilla);
    }

    private function _componenteAlta() {


        return $this->_armado();
    }

    // reordena los valores de los parámetro en una matriz utilizable
    private function _tablasCampos() {

        $matriz_tb_campo = Consultas_Componente::RegistroConsultaTabla(__FILE__, __LINE__, '', true);

        if ($matriz_tb_campo) {
            if ($matriz_tb_campo) {
                $tb_campo = '<select name="id_cp_rel" id="id_cp_rel">';
                foreach ($matriz_tb_campo as $linea) {
                    $tb_campo .= '<option value="' . $linea['id_componente'] . '">' . $linea['tabla_nombre'] . ' - ' . $linea['tabla_campo'] . '</option>';
                }
                $tb_campo .= '</select>';
            }
        } else {
            return false;
        }

        return $tb_campo;
    }

}
