<?php

class Acciones_PaginaMenuInsercion {

    private $_id_componente;

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        $numero_niveles = $_POST['numero_niveles'];
        $niveles_protegidos = $_POST['niveles_protegidos'];
        $niveles_habilitados = $_POST['niveles_habilitados'];

        $parametros_tabla = Consultas_TablaParametros::RegistroConsultaTodos(__FILE__, __LINE__, $_GET['id_tabla']);

        if (is_array($parametros_tabla)) {
            $parametros = array();
            foreach ($parametros_tabla as $valor) {
                $parametros[$valor['parametro']] = $valor['valor'];
            }

            if (isset($parametros['niveles_habilitados'])) {
                Consultas_TablaParametros::RegistroModificar(__FILE__, __LINE__, $_GET['id_tabla'], 'menu', 'niveles_habilitados', $niveles_habilitados);
            } else {
                Consultas_TablaParametros::RegistroCrearCompleto(__FILE__, __LINE__, $_GET['id_tabla'], 'menu', 'niveles_habilitados', $niveles_habilitados);
            }

            if (isset($parametros['numero_niveles'])) {
                Consultas_TablaParametros::RegistroModificar(__FILE__, __LINE__, $_GET['id_tabla'], 'menu', 'numero_niveles', $numero_niveles);
            } else {
                Consultas_TablaParametros::RegistroCrearCompleto(__FILE__, __LINE__, $_GET['id_tabla'], 'menu', 'numero_niveles', $numero_niveles);
            }
            
            if (isset($parametros['niveles_protegidos'])) {
                Consultas_TablaParametros::RegistroModificar(__FILE__, __LINE__, $_GET['id_tabla'], 'menu', 'niveles_protegidos', $niveles_protegidos);
            } else {
                Consultas_TablaParametros::RegistroCrearCompleto(__FILE__, __LINE__, $_GET['id_tabla'], 'menu', 'niveles_protegidos', $niveles_protegidos);
            }
            
        } else {
            Consultas_TablaParametros::RegistroCrearCompleto(__FILE__, __LINE__, $_GET['id_tabla'], 'menu', 'niveles_habilitados', $niveles_habilitados);
            Consultas_TablaParametros::RegistroCrearCompleto(__FILE__, __LINE__, $_GET['id_tabla'], 'menu', 'numero_niveles', $numero_niveles);
            Consultas_TablaParametros::RegistroCrearCompleto(__FILE__, __LINE__, $_GET['id_tabla'], 'menu', 'niveles_protegidos', $niveles_protegidos);
            Consultas_TablaParametros::RegistroCrearCompleto(__FILE__, __LINE__, $_GET['id_tabla'], 'menu', 'tabla_relacionada', $this->_agregarComponenteMenu());
            Consultas_TablaParametros::RegistroCrearCompleto(__FILE__, __LINE__, $_GET['id_tabla'], 'link', 'destino_id_cp', $this->_id_componente);
        }

        $this->_pasarNombresComponente();

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();
        $parametros = array('kk_generar' => '0', 'accion' => '30');
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _agregarComponenteMenu() {

        // creo el objeto 'componente_carga' para el alta
        $componente_carga = new Acciones_ComponenteAltaInsercion();

        $id_tabla_origen = Consultas_Componente::RegistroConsulta(__FILE__, __LINE__, $_POST['id_cp_rel']);
        $id_tabla_origen = $id_tabla_origen[0]['id_tabla'];
        $this->_id_componente = $componente_carga->crearComponente($id_tabla_origen, 'PaginaMenu', '', '0');

        // crear componente y obtener id de insercion
        $componente_carga->consultaParametro($this->_id_componente, 'id_tabla', $_GET['id_tabla']);
        $componente_carga->consultaParametro($this->_id_componente, 'intermedia_tb_id', $_GET['intermedia_tb_id']);
        $componente_carga->consultaParametro($this->_id_componente, 'id_tabla_trd', $_GET['id_tabla_trd']);

        // Se crea la columna ID de relacion con la tabla de origen
        $tabla_rel_datos = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $id_tabla_origen);
        $tabla_int_datos = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $_GET['intermedia_tb_id']);
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_int_datos, 'id_' . $tabla_rel_datos, 'numero', '12', false);

        return $id_tabla_origen;
    }

    private function _pasarNombresComponente() {

        if (!isset($this->_id_componente)) {
            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas('kirke_tabla_parametro');
            $consulta->campos('kirke_tabla_parametro', 'valor');
            $consulta->condiciones('', 'kirke_tabla_parametro', 'id_tabla', 'iguales', '', '', $_GET['id_tabla']);
            $consulta->condiciones('y', 'kirke_tabla_parametro', 'parametro', 'iguales', '', '', 'tabla_relacionada');
            //$consulta->verConsulta();
            $id_tabla = $consulta->realizarConsulta();
            $id_tabla_relacionada = $id_tabla[0]['valor'];

            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas('kirke_componente');
            $consulta->campos('kirke_componente', 'id_componente');
            $consulta->condiciones('', 'kirke_componente', 'id_tabla', 'iguales', '', '', $id_tabla_relacionada);
            $consulta->condiciones('y', 'kirke_componente', 'componente', 'iguales', '', '', 'PaginaMenu');
            //$consulta->verConsulta();
            $id_componente = $consulta->realizarConsulta();
            $this->_id_componente = $id_componente[0]['id_componente'];
        }

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
