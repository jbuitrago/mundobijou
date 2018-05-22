<?php

class Acciones_PaginaTabuladoresInsercion {

    private $_id_componente;

    public function armado() {
        
        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        $parametros_tabla = Consultas_TablaParametros::RegistroConsultaTodos(__FILE__, __LINE__, $_GET['id_tabla']);

        if (!is_array($parametros_tabla)) {

            if (isset($_POST['id_cp_rel']) && ($_POST['id_cp_rel'] != '')) {
                $tabla_tabuladores = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $_GET['id_tabla']);
                $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
                $consulta->tablas('kirke_tabla');
                $consulta->tablas('kirke_tabla_prefijo');
                $consulta->tablas('kirke_componente');
                $consulta->campos('kirke_tabla', 'tabla_nombre');
                $consulta->campos('kirke_tabla_prefijo', 'prefijo');
                $consulta->condiciones('', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
                $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', 'kirke_componente', 'id_tabla');
                $consulta->condiciones('y', 'kirke_componente', 'id_componente', 'iguales', '', '', $_POST['id_cp_rel']);
                $id_tabla_cp_rel = $consulta->realizarConsulta();
                Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_tabuladores, 'id_' . $id_tabla_cp_rel[0]['prefijo'] . '_' . $id_tabla_cp_rel[0]['tabla_nombre'], 'numero', '12', false);
            }

            $tabla_rel_datos = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $_POST['id_tb_rel']);
            $tabla_int_datos = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $_GET['intermedia_tb_id']);
            Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_int_datos, 'id_' . $tabla_rel_datos, 'numero', '12', false);

            $this->_agregarComponenteTabuladores();
            $this->_pasarNombresComponente();

            if (isset($_POST['id_cp_rel']) && ($_POST['id_cp_rel'] != '')) {
                Consultas_TablaParametros::RegistroCrearCompleto(__FILE__, __LINE__, $_GET['id_tabla'], 'tabuladores', 'id_cp_rel', $_POST['id_cp_rel']);
            }

            Consultas_TablaParametros::RegistroCrearCompleto(__FILE__, __LINE__, $_GET['id_tabla'], 'tabuladores', 'tabla_relacionada', $_POST['id_tb_rel']);
            Consultas_TablaParametros::RegistroCrearCompleto(__FILE__, __LINE__, $_GET['id_tabla'], 'tabuladores', 'cp_id', $this->_id_componente);
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();
        $parametros = array('kk_generar' => '0', 'accion' => '30');
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _agregarComponenteTabuladores() {

        // creo el objeto 'componente_carga' para el alta
        $componente_carga = new Acciones_ComponenteAltaInsercion();

        $this->_id_componente = $componente_carga->crearComponente($_POST['id_tb_rel'], 'PaginaTabuladores', '');

        // crear componente y obtener id de insercion
        $componente_carga->consultaParametro($this->_id_componente, 'id_tabla', $_GET['id_tabla']);
        $componente_carga->consultaParametro($this->_id_componente, 'intermedia_tb_id', $_GET['intermedia_tb_id']);
        $componente_carga->consultaParametro($this->_id_componente, 'id_tabla_trd', $_GET['id_tabla_trd']);
        if (isset($_POST['id_cp_rel']) && ($_POST['id_cp_rel'] != '')) {
            $componente_carga->consultaParametro($this->_id_componente, 'id_cp_rel', $_POST['id_cp_rel']);
        }
        $componente_carga->consultaParametro($this->_id_componente, 'tabla_relacionada', $_POST['id_tb_rel']);
    }

    private function _pasarNombresComponente() {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tabla_nombre_idioma');
        $consulta->campos('kirke_tabla_nombre_idioma', 'idioma_codigo');
        $consulta->campos('kirke_tabla_nombre_idioma', 'tabla_nombre_idioma');
        $consulta->condiciones('', 'kirke_tabla_nombre_idioma', 'id_tabla', 'iguales', '', '', $_GET['id_tabla']);
        //$consulta->verConsulta();
        $idiomas_tabla = $consulta->realizarConsulta();

        if (is_array($idiomas_tabla)) {
            $componente_carga = new Acciones_ComponenteAltaInsercion();
            foreach ($idiomas_tabla as $valor) {
                $componente_carga->consultaParametro($this->_id_componente, 'idioma_' . $valor['idioma_codigo'], $valor['tabla_nombre_idioma']);
            }
        }
    }

}
