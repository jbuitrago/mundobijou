<?php

class DesarrollosSistema_Paginado {

    private $_variableGet = 'kk_pagina_n';
    private $_cantidadTotal;
    private $_cantidadPorPagina = 50;
    private $_linksPorLado = 5;
    private $_paginaActual;
    private $_textoAnterior;
    private $_textoSiguiente;
    private $_destino;
    private $_pagina;
    private $_cantidadPaginas;
    private $_otrasVariablesNombre = Array();
    private $_otraVariableValor = Array();
    private $_tabla;

    public function __construct($tabla = null) {
        $this->_tabla = $tabla;
        $this->obtenerPaginado();
    }

    public function cantidadTotal($cantidad_total) {
        $this->_cantidadTotal = $cantidad_total;
    }

    public function cantidadPorPagina($cantidad) {
        $this->_cantidadPorPagina = $cantidad;
    }

    public function linksPorLado($links_por_lado) {
        $this->_linksPorLado = $links_por_lado;
    }

    public function limiteInicialConsulta() {
        return ($this->_paginaActual * $this->_cantidadPorPagina);
    }

    public function paginadoObtener() {

        // cantidad de paginas
        $paginas_dec = $this->_cantidadTotal / $this->_cantidadPorPagina;

        if ($paginas_dec > floor($this->_cantidadTotal / $this->_cantidadPorPagina)) {
            $paginas = floor($this->_cantidadTotal / $this->_cantidadPorPagina);
        } else {
            $paginas = floor($this->_cantidadTotal / $this->_cantidadPorPagina) - 1;
        }

        $this->_cantidadPaginas = $paginas + 1;

        if (isset($this->_linksPorLado)) {

            if (($this->_paginaActual - $this->_linksPorLado) > 0) {
                $links_inicio = $this->_paginaActual - $this->_linksPorLado;
                $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET[0], 'kk_tabla' => $this->_tabla, $this->_variableGet => ($this->_paginaActual - 1)), 's');
                $links_faltantes_inicio = '<a href="' . $link . '" target="_self" class="paginado">...</a>&nbsp;&nbsp';
            } else {
                $links_inicio = 0;
                $links_faltantes_inicio = '';
            }
            if (($this->_paginaActual + $this->_linksPorLado) < $paginas) {
                $links_fin = $this->_paginaActual + $this->_linksPorLado;
                $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET[0], 'kk_tabla' => $this->_tabla, $this->_variableGet => ($this->_paginaActual + 1)), 's');
                $links_faltantes_fin = '<a href="' . $link . '" target="_self" class="paginado">...</a>&nbsp;&nbsp';
            } else {
                $links_fin = $paginas;
                $links_faltantes_fin = '';
            }
        } else {

            $links_inicio = 0;
            $links_faltantes_inicio = '';
            $links_fin = $paginas;
            $links_faltantes_fin = '';
        }

        $paginado_armar = '';

        // armado de indice
        if ($paginas > 0) {

            // link a inicio
            $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET[0], 'kk_tabla' => $this->_tabla, $this->_variableGet => 0), 's');
            $paginado_armar .= '<a href="' . $link . '" target="_self" class="paginado"><<</a>&nbsp;&nbsp';

            // link anterior
            if ($this->_paginaActual > 0) {
                $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET[0], 'kk_tabla' => $this->_tabla, $this->_variableGet => ($this->_paginaActual - 1)), 's');
                $paginado_armar .= '<a href="' . $link . '" target="_self" class="paginado">{TR|m_anterior}</a>&nbsp;&nbsp';
            }

            $paginado_armar .= $links_faltantes_inicio;

            for ($i = $links_inicio; $i <= $links_fin; $i++) {
                $i_ver = $i + 1;
                // link activado
                if ($i != $this->_paginaActual) {
                    $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET[0], 'kk_tabla' => $this->_tabla, $this->_variableGet => ($i_ver - 1)), 's');
                    $paginado_armar .= '<a href="' . $link . '" target="_self" class="paginado">' . $i_ver . '</a>&nbsp;&nbsp';
                    // link no activado pagina actual
                } else {
                    $paginado_armar .= '<span class="paginado_actual">' . $i_ver . '</span>&nbsp;&nbsp';
                }
            }

            $paginado_armar .= $links_faltantes_fin;

            // link sigueinte
            if ($this->_paginaActual < $paginas) {
                $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET[0], 'kk_tabla' => $this->_tabla, $this->_variableGet => ($this->_paginaActual + 1)), 's');
                $paginado_armar .= '<a href="' . $link . '" target="_self" class="paginado">{TR|m_siguiente}</a>&nbsp;&nbsp';
            }

            // link a fin
            $paginas_link = $paginas;
            $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET[0], 'kk_tabla' => $this->_tabla, $this->_variableGet => $paginas_link), 's');
            $paginado_armar .= '<a href="' . $link . '" target="_self" class="paginado">>></a>&nbsp;&nbsp';
        }

        if ($paginado_armar != '') {
            $paginado_armar = '<div class="botonera2"><span class="paginado_actual_total">{TR|o_cantidad_de_paginas}: ' . $this->_cantidadPaginas . '</span> &nbsp; &nbsp; &nbsp; ' . $paginado_armar . '</div>';
        }

        return $paginado_armar;
    }
    
    public function obtenerPaginado(){
        $this->_paginaActual = DesarrollosSistema_FiltrosOrden::obtenerPaginado($this->_tabla);
    }

}
