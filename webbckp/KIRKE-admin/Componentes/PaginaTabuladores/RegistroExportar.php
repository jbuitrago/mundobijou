<?php

class Componentes_PaginaTabuladores_RegistroExportar extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;                // metodo a llamar
    private $_dcp = array();         // parametros del componente
    private $dcp_origen = array(); // datos del componente que contiene los datos de origen
    private $_idComponente;         // id del componente
    private $_idRegistro;           // id del registro
    private $_tabla_tabuladores;
    private $_titulo;

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
        $this->_tabla_tabuladores = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $this->_dcp['id_tabla']);
        $tabla_tabuladores_nombre_idioma = Consultas_TablaNombreIdioma::RegistroConsulta(__FILE__, __LINE__, Generales_Idioma::obtener(), $this->_dcp['id_tabla']);
        $this->_tabla_tabuladores_nombre_idioma = $tabla_tabuladores_nombre_idioma[0]['tabla_nombre_idioma'];
        $this->_id_cp_rel_destino = $this->_idCpRelDestino();
    }

    public function get() {
        $valores = $this->_mostrarValor();
        if (is_array($valores)) {
            foreach ($valores as $valor) {
                $this->_valor[] = $valor;
            }
        } else {
            $this->_valor = '-';
        }
        $metodo = '_' . $this->_metodo;
        return $this->$metodo();
    }

    private function _excel() {
        $this->_valores = '';
        $separador = '';
        if (is_array($this->_valor)) {
            foreach ($this->_valor as $datos) {
                $titulo = str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($datos['titulo'], '<a>'));
                $valor = str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($datos['valor'], '<a>'));
                $this->_valores .= $separador . $titulo . ':' . $valor;
                $separador = ' ; ';
            }
        }
        $valores['titulo'] = 'tab';
        $valores['valor'] = '<td bgcolor="#eeeeec">' . $this->_valores . '</td>';
        return $valores;
    }

    private function _pdf() {
        $this->_valores = '';
        $separador = '';
        if (is_array($this->_valor)) {
            foreach ($this->_valor as $datos) {
                $this->_valores .= $separador . $datos['titulo'] . ':' . $datos['valor'];
                $separador = ' ; ';
            }
        }
        $valores['titulo'] = 'tab';
        $valores['valor'] = $this->_valores;
        return $valores;
    }

    private function _cvs() {
        $this->_valores = '';
        $separador = '';
        if (is_array($this->_valor)) {
            foreach ($this->_valor as $datos) {
                $titulo = str_replace(';', ',', str_replace(array("\r\n", "\n", "\r"), ' ', $datos['titulo']));
                $valor = str_replace(';', ',', str_replace(array("\r\n", "\n", "\r"), ' ', $datos['valor']));
                $this->_valores .= $separador . $titulo . ':' . $valor;
                $separador = ' | ';
            }
        }
        $valores['titulo'] = 'tab';
        $valores['valor'] = $this->_valores;
        return $valores;
    }

    private function _xml() {
        $dato = '';
        if (is_array($this->_valor)) {
            foreach ($this->_valor as $valores) {
                $dato .= '      <dato><titulo><![CDATA[' . $valores['titulo'] . ']]></titulo><valor><![CDATA[' . $valores['valor'] . ']]></valor></dato>' . "\n";
            }
        }
        $titulo = 'tab';
        return '    <' . $titulo . '>' . $dato . '</' . $titulo . '>' . "\n";
    }

    private function _html() {
        $this->_valores = '';
        $separador = '';
        if (is_array($this->_valor)) {
            foreach ($this->_valor as $datos) {
                $titulo = str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($datos['titulo'], '<a>'));
                $valor = str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($datos['valor'], '<a>'));
                $this->_valores .= $separador . $titulo . ':' . $valor;
                $separador = ' ; ';
            }
        }
        $valores['titulo'] = 'tab';
        $valores['valor'] = '<td>' . $this->_valores . '</td>';
        return $valores;
    }

    private function _sql() {
        
        $this->_valores = '';
        $separador = '';
        if (is_array($this->_valor)) {
            foreach ($this->_valor as $datos) {
                $this->_valores .= $separador . $datos['titulo'] . ':' . $datos['valor'];
                $separador = ' | ';
            }
        }
        $valores['titulo'] = '';
        $valores['valor'] = "'" . str_replace("'", '\\\'', $this->_valores) . "', ";
        return $valores;
    }

    private function _sqlEstructura() {
        $titulo[0] = '  `tab` longtext,' . "\n";
        $titulo[1] = '`tab`, ';
        return $titulo;
    }

    private function _mostrarValor() {

        $id_campo = 'cp_' . $this->_dcp['cp_id'];

        $matriz_tabuladores = $this->_obtenerMatrizTabuladoresTitulos($this->_idGrupoTabuladoresBD());

        $matriz_tabuladores_valores = $this->_obtenerMatrizTabuladoresValores();

        $etiqueta = array();

        if (is_array($matriz_tabuladores)) {
            $cont = 0;

            foreach ($matriz_tabuladores as $datos) {
                foreach (Inicio::confVars('idiomas') as $value) {
                    if (isset($matriz_tabuladores_valores[$datos['tab_id']][$value])) {
                        $etiqueta[$cont]['valor'] = $matriz_tabuladores_valores[$datos['tab_id']][$value];
                    } else {
                        $etiqueta[$cont]['valor'] = '';
                    }
                }

                $etiqueta[$cont]['titulo'] = $datos['tab_nombre'];
                $cont++;
            }
        }

        return $etiqueta;
    }

    private function _obtenerMatrizTabuladoresTitulos($id_grupo_tabuladores = null) {

        if (isset($this->_dcp['id_cp_rel'])) {
            $this->_obtenerTablaOrigen();
        }

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_tabla_tabuladores);
        $consulta->tablas($this->_tabla_tabuladores . '_trd');
        $consulta->campos($this->_tabla_tabuladores . '_trd', 'id_' . $this->_tabla_tabuladores, 'tab_id');
        $consulta->campos($this->_tabla_tabuladores . '_trd', 'tabulador_nombre', 'tab_nombre');
        $consulta->condiciones('', $this->_tabla_tabuladores, 'id_' . $this->_tabla_tabuladores, 'iguales', $this->_tabla_tabuladores . '_trd', 'id_' . $this->_tabla_tabuladores);
        $consulta->condiciones('y', $this->_tabla_tabuladores . '_trd', 'idioma', 'iguales', '', '', Generales_Idioma::obtener());
        if ($id_grupo_tabuladores !== null) {
            // controlo si recibo el valor post del campo relacionado con el origen.
            $consulta->condiciones('y', $this->_tabla_tabuladores, 'id_' . $this->_tb_origen, 'iguales', '', '', $id_grupo_tabuladores);
        }
        // no hacer falta 'orden', porque se ordena mas abajo en '$matriz_tabuladores'
        $consulta->orden($this->_tabla_tabuladores, 'orden');
        $consulta->orden($this->_tabla_tabuladores . '_trd', 'idioma');
        //$consulta->verConsulta();
        $matriz = $consulta->realizarConsulta();

        $matriz_tabuladores = array();
        if (is_array($matriz)) {
            $orden = 0;
            foreach ($matriz as $datos) {
                $matriz_tabuladores[$orden]['tab_id'] = $datos['tab_id'];
                $matriz_tabuladores[$orden]['tab_nombre'] = $datos['tab_nombre'];
                $orden++;
            }
        }
        return $matriz_tabuladores;
    }

    private function _obtenerTablaOrigen() {
        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tabla');
        $consulta->tablas('kirke_tabla_prefijo');
        $consulta->tablas('kirke_componente');
        $consulta->campos('kirke_tabla', 'tabla_nombre');
        $consulta->campos('kirke_tabla_prefijo', 'prefijo');
        $consulta->condiciones('', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', 'kirke_componente', 'id_tabla');
        $consulta->condiciones('y', 'kirke_componente', 'id_componente', 'iguales', '', '', $this->_dcp['id_cp_rel']);
        $id_tabla_cp_rel = $consulta->realizarConsulta();
        $this->_tb_origen = $id_tabla_cp_rel[0]['prefijo'] . '_' . $id_tabla_cp_rel[0]['tabla_nombre'];
    }

    private function _idCpRelDestino() {

        if (isset($this->_dcp['id_cp_rel'])) {
            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas('kirke_componente_parametro');
            $consulta->tablas('kirke_componente');
            $consulta->campos('kirke_componente_parametro', 'id_componente');
            $consulta->condiciones('', 'kirke_componente_parametro', 'parametro', 'iguales', '', '', 'origen_cp_id');
            $consulta->condiciones('y', 'kirke_componente_parametro', 'valor', 'iguales', '', '', $this->_dcp['id_cp_rel']);
            $consulta->condiciones('y', 'kirke_componente_parametro', 'id_componente', 'iguales', 'kirke_componente', 'id_componente');
            $consulta->condiciones('y', 'kirke_componente', 'id_tabla', 'iguales', '', '', $this->_dcp['tb_id']);
            //$consulta->verConsulta();
            $id_cp_rel_destino = $consulta->realizarConsulta();
            return $id_cp_rel_destino[0]['id_componente'];
        } else {
            return '';
        }
    }

    private function _obtenerMatrizTabuladoresValores() {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_tabla_tabuladores);
        $consulta->tablas($this->_tabla_tabuladores . '_rel');
        $consulta->campos($this->_tabla_tabuladores . '_rel', 'id_' . $this->_tabla_tabuladores, 'tab_id');
        $consulta->campos($this->_tabla_tabuladores . '_rel', 'tabulador', 'tab_valor');
        $consulta->campos($this->_tabla_tabuladores . '_rel', 'idioma', 'tab_idioma');
        $consulta->condiciones('', $this->_tabla_tabuladores, 'id_' . $this->_tabla_tabuladores, 'iguales', $this->_tabla_tabuladores . '_rel', 'id_' . $this->_tabla_tabuladores);
        $consulta->condiciones('y', $this->_tabla_tabuladores . '_rel', 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $this->_idRegistro);
        //$consulta->verConsulta();
        $matriz = $consulta->realizarConsulta();

        $matriz_tabuladores = array();
        if (is_array($matriz)) {
            foreach ($matriz as $datos) {
                $matriz_tabuladores[$datos['tab_id']][$datos['tab_idioma']] = $datos['tab_valor'];
            }
        }
        return $matriz_tabuladores;
    }

    private function _idGrupoTabuladoresBD() {

        $cp_rel_destino = Consultas_Componente::RegistroConsulta(__FILE__, __LINE__, $this->_id_cp_rel_destino);

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre']);
        $consulta->campos($this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], $cp_rel_destino[0]['tabla_campo'], 'id_cp_rel_destino');
        $consulta->condiciones('', $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $this->_idRegistro);
        //$consulta->verConsulta();
        $id_cp_rel_destino = $consulta->realizarConsulta();

        if (isset($id_cp_rel_destino[0]['id_cp_rel_destino'])) {
            return $id_cp_rel_destino[0]['id_cp_rel_destino'];
        } else {
            return false;
        }
    }

}
