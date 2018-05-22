<?php

class Acciones_PaginaTabuladores extends Armado_Plantilla {

    private $_tabla = Array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        if (isset($_GET['desplegable']) && ($_GET['desplegable'] == 'si')) {
            $this->_ajaxDesplegables();
            exit;
        }

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $this->_tabla = Consultas_TablaNombreIdioma::RegistroConsultaPrefijo(__FILE__, __LINE__, $_GET['id_tabla']);

        $ver = $this->_paginaMenuDatos();

        $armado_botonera = new Armado_Botonera();

        if (isset($_GET['intermedia_tb_id']) && isset($_GET['id_tabla_trd'])) {
            $parametros = array('kk_generar' => '0', 'accion' => '69', 'id_tabla' => $_GET['id_tabla'], 'intermedia_tb_id' => $_GET['intermedia_tb_id'], 'id_tabla_trd' => $_GET['id_tabla_trd']);
        } else {
            $parametros = array('kk_generar' => '0', 'accion' => '69', 'id_tabla' => $_GET['id_tabla']);
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

        // si es un alta, sino no debe mostrarse
        $plantilla['campos_relacionados'] = $this->_tablasCampos();

        return Armado_PlantillasInternas::acciones(basename(__FILE__), $plantilla);
    }

    private function _componenteAlta() {


        return $this->_armado();
    }

    // reordena los valores de los parámetro en una matriz utilizable
    private function _tablasCampos() {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tabla');
        $consulta->tablas('kirke_tabla_prefijo');
        $consulta->campos('kirke_tabla_prefijo', 'prefijo');
        $consulta->campos('kirke_tabla', 'tabla_nombre');
        $consulta->campos('kirke_tabla', 'id_tabla');
        $consulta->condiciones('', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
        $consulta->condiciones('y', 'kirke_tabla', 'tipo', 'iguales', '', '', 'registros');
        $matriz_tb_campo = $consulta->realizarConsulta();

        if ($matriz_tb_campo) {
            if ($matriz_tb_campo) {
                $tb_campo = '<select name="id_tb_rel" id="id_tb_rel" no_nulo="{TR|o_debe_seleccionar_un_dato}">';
                $tb_campo .= '<option value="" selected="selected">{TR|o_seleccionar}</option>';
                foreach ($matriz_tb_campo as $linea) {
                    $tb_campo .= '<option value="' . $linea['id_tabla'] . '">' . $linea['prefijo'] . '_' . $linea['tabla_nombre'] . '</option>';
                }
                $tb_campo .= '</select>';
                $tb_campo .= '<div class="VC_error" id="VC_id_tb_rel"></div>';
            }
        } else {
            return false;
        }

        return $tb_campo;
    }

    private function _ajaxDesplegables() {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_componente');
        $consulta->tablas('kirke_componente_parametro');
        $consulta->campos('kirke_componente_parametro', 'valor');
        $consulta->condiciones('', 'kirke_componente', 'id_tabla', 'iguales', '', '', $_GET['id_tabla']);
        $consulta->condiciones('y', 'kirke_componente_parametro', 'id_componente', 'iguales', 'kirke_componente', 'id_componente');
        $consulta->condiciones('y', 'kirke_componente_parametro', 'parametro', 'iguales', '', '', 'origen_cp_id');
        //$consulta->verConsulta();
        $componentes = $consulta->realizarConsulta();

        foreach ($componentes as $id_cp) {
            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas('kirke_tabla');
            $consulta->tablas('kirke_componente');
            $consulta->campos('kirke_tabla', 'tabla_nombre');
            $consulta->campos('kirke_componente', 'id_componente');
            $consulta->campos('kirke_componente', 'tabla_campo');
            $consulta->condiciones('', 'kirke_tabla', 'id_tabla', 'iguales', 'kirke_componente', 'id_tabla');
            $consulta->condiciones('y', 'kirke_componente', 'id_componente', 'iguales', '', '', $id_cp['valor']); // es componente
            $consulta->orden('kirke_tabla', 'tabla_nombre');
            $consulta->orden('kirke_componente', 'tabla_campo');
            //$consulta->verConsulta();
            $resultado = $consulta->realizarConsulta();

            $datos[$resultado[0]['id_componente']] = $resultado[0]['tabla_nombre'] . ' - ' . $resultado[0]['tabla_campo'];
        }

        $xml = '';
        $largo = 50;

        if (is_array($datos)) {

            Header("Content-Type:application/xhtml+xml; charset=utf-8");

            $xml .= "<?xml version=\"1.0\" standalone=\"yes\" ?>";
            $xml .= "<datos>";

            foreach ($datos as $id => $valor) {
                $puntos = '';
                if (strlen($valor) > $largo) {
                    $puntos = '... ';
                }

                $xml .= "<dato>";
                $xml .= "<id>" . $id . "</id>";
                $xml .= "<texto>" . substr($valor, 0, $largo) . $puntos . "</texto>";
                $xml .= "</dato>";
            }

            $xml .= "</datos>";
        }

        echo $xml;
    }

}
