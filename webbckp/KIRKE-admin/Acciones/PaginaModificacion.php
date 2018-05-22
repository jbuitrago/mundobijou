<?php

class Acciones_PaginaModificacion extends Armado_Plantilla {

    private $_tabla = Array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '32', 'id_tabla' => $_GET['id_tabla']);
        $armado_botonera->armar('guardar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '30');
        $armado_botonera->armar('volver', $parametros);

        $this->_tabla = Consultas_TablaNombreIdioma::RegistroConsultaPrefijo(__FILE__, __LINE__, $_GET['id_tabla']);

        $pagina_alta_cuerpo = $this->_paginaNueva();

        return $pagina_alta_cuerpo;
    }

    private function _paginaNueva() {

        $plantilla['tabla_nombre'] = $this->_tabla[0]['tabla_nombre'];
        $id_tabla_prefijo = $this->_tabla[0]['id_tabla_prefijo'];
        $habilitado = $this->_tabla[0]['habilitado'];

        foreach ($this->_tabla as $id => $valor) {
            $datos_idiomas_cargados[$this->_tabla[$id]['idioma_codigo']] = $this->_tabla[$id]['tabla_nombre_idioma'];
        }

        if ($habilitado == 's') {
            $plantilla['habilitado'] = " checked";
            $plantilla['no_habilitado'] = '';
        } elseif ($habilitado == 'n') {
            $plantilla['habilitado'] = '';
            $plantilla['no_habilitado'] = " checked";
        }

        $plantilla['prefijo_select'] = Armado_PrefijoSelect::armado($id_tabla_prefijo);
        $plantilla['etiqueta_idiomas'] = Armado_EtiquetaIdiomas::armar('etiqueta', $datos_idiomas_cargados);

        return Armado_PlantillasInternas::acciones(basename(__FILE__), $plantilla);
    }

}

