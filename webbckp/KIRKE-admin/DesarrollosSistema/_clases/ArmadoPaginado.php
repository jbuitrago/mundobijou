<?php

class ArmadoPaginado {

    private $_variableGet;
    private $_variableGetPosicion;
    private $_cantidadTotal;
    private $_cantidadPorPagina = 10;
    private $_linksPorLado = 3;
    private $_paginaActual;
    private $_paginaActualForzada;
    private $_textoAnterior = '&lt;';
    private $_textoSiguiente = '&gt;';
    private $_textoInicio = '&lt;&lt;';
    private $_textoFin = '&gt;&gt;';
    private $_textoSigue = '...';
    private $_textoEstilo;
    private $_textoEstiloActual;
    private $_cantidadPaginas;
    private $_separacion = ' - ';

    public function cantidadTotal($cantidad_total) {
        $this->_cantidadTotal = $cantidad_total;
    }

    public function cantidadPorPagina($cantidad_por_pagina) {
        $this->_cantidadPorPagina = $cantidad_por_pagina;
    }

    public function paginaActual($pagina_actual_forzada = NULL) {
        if ($pagina_actual_forzada != NULL) {
            $this->_paginaActualForzada = $pagina_actual_forzada;
        } else {
            $this->_paginaActualForzada = 1;
        }
    }

    public function textoAnterior($texto_anterior) {
        $this->_textoAnterior = $texto_anterior;
    }

    public function textoSiguiente($texto_siguiente) {
        $this->_textoSiguiente = $texto_siguiente;
    }

    public function textoInicio($texto_inicio) {
        $this->_textoInicio = $texto_inicio;
    }

    public function textoFin($texto_fin) {
        $this->_textoFin = $texto_fin;
    }

    public function textoSigue($texto_sigue) {
        $this->_textoSigue = $texto_sigue;
    }

    public function textoEstilo($texto_estilo) {
        $this->_textoEstilo = $texto_estilo;
    }

    public function textoEstiloActual($texto_estilo_actual) {
        $this->_textoEstiloActual = $texto_estilo_actual;
    }

    public function separacion($separacion) {
        $this->_separacion = $separacion;
    }

    public function linksPorLado($links_por_lado) {
        $this->_linksPorLado = $links_por_lado;
    }

    public function limiteInicialConsulta() {
        $this->_paginaActualCalculo();
        return ( ($this->_paginaActual - 1) * $this->_cantidadPorPagina);
    }

    public function obtCantidadPaginas() {
        return $this->_cantidadPaginas - 1;
    }

    //public function paginado_imp(&$paginado){
    public function obtenerPaginado() {

        $this->_paginaActualCalculo();

        // cantidad de paginas
        if (($this->_cantidadTotal / $this->_cantidadPorPagina) > (ceil($this->_cantidadTotal / $this->_cantidadPorPagina))) {
            $paginas = ceil($this->_cantidadTotal / $this->_cantidadPorPagina);
        } else {
            $paginas = ceil($this->_cantidadTotal / $this->_cantidadPorPagina);
        }

        $this->_cantidadPaginas = $paginas + 1;

        if (isset($this->_linksPorLado)) {
            if (($this->_paginaActual - $this->_linksPorLado) > 1) {
                $links_inicio = $this->_paginaActual - $this->_linksPorLado;
                $links_faltantes_inicio = $this->_textoSigue . $this->_separacion;
            } else {
                $links_inicio = 1;
                $links_faltantes_inicio = '';
            }
            if (($this->_paginaActual + $this->_linksPorLado) < $paginas) {
                $links_fin = $this->_paginaActual + $this->_linksPorLado;
                $links_faltantes_fin = $this->_textoSigue . $this->_separacion;
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

        // armado de indice
        if ($paginas > 1) {

            $paginado_armar = '';

            if ($this->_paginaActual > 1) {

                // link a inicio
                $paginado_armar .= '<a href="' . $this->_links(1) . '" target="_self" class="' . $this->_textoEstilo . '">' . $this->_textoInicio . '</a>' . $this->_separacion;

                // link anterior
                $paginado_armar .= '<a href="' . $this->_links($this->_paginaActual - 1) . '" target="_self" class="' . $this->_textoEstilo . '">' . $this->_textoAnterior . '</a>' . $this->_separacion;
            }

            $paginado_armar .= $links_faltantes_inicio;

            for ($i = $links_inicio; $i <= $links_fin; $i++) {

                if ($i != $this->_paginaActual) {

                    // link activado					
                    $paginado_armar .= '<a href="' . $this->_links($i) . '" target="_self" class="' . $this->_textoEstilo . '">' . $i . '</a>';
                } else {

                    // link no activado pagina actual
                    $paginado_armar .= '<span class="' . $this->_textoEstiloActual . '">' . $i . '</span>';
                }
                if (!(($this->_paginaActual == $paginas) && (($i) == $paginas))) {
                    $paginado_armar .= $this->_separacion;
                }
            }

            $paginado_armar .= $links_faltantes_fin;

            if ($this->_paginaActual < $paginas) {

                // link siguiente
                $paginado_armar .= '<a href="' . $this->_links($this->_paginaActual + 1) . '" target="_self" class="' . $this->_textoEstilo . '">' . $this->_textoSiguiente . '</a>' . $this->_separacion;

                // link a fin
                $paginado_armar .= '<a href="' . $this->_links($paginas) . '" target="_self" class="' . $this->_textoEstilo . '">' . $this->_textoFin . '</a>';
            }
        } else {

            $paginado_armar = false;
        }

        return $paginado_armar;
    }

    private function _links($paginas_link) {

        $url = $_SERVER['REQUEST_URI'];

        // se hacen todos los calculos para que sea compatible con el administrador

        if (strstr($url, '&') !== false) {
            $conector = '\&';
        } else {
            $conector = '\?';
        }
        $url = preg_replace('/' . $conector . 'p=[0-9]+/', '', $url);
        $url_partes = parse_url($url);

        if (isset($url_partes['query']) && (strlen($url_partes['query']) > 0)) {
            $conector = '&';
        } else {
            $conector = '?';
        }

        return $url . $conector . "p=" . $paginas_link;
    }

    private function _paginaActualCalculo() {

        // se calcula con $_GET['p'] para que sea compatible con el administrador
        if ($this->_paginaActualForzada != '') {
            $this->_paginaActual = $this->_paginaActualForzada;
        } elseif (!VariableControl::getParam('p') && !isset($_GET['p'])) {
            $this->_paginaActual = 1;
        } else {
            if (VariableControl::getParam('p')) {
                $this->_paginaActual = VariableControl::getParam('p');
            } elseif (isset($_GET['p'])) {
                $this->_paginaActual = $_GET['p'];
            }
        }
    }

}
