<?php

class Acciones_ComponenteModificacion extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '7', 'id_tabla' => $_GET['id_tabla'], 'cp_id' => $_GET['cp_id']);
        $armado_botonera->armar('guardar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '5', 'id_tabla' => $_GET['id_tabla']);
        $armado_botonera->armar('volver', $parametros);

        // creo una matriz con los campos de los componentes de la pagina
        $matriz_componentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($_GET['cp_id']);

        return Generales_LlamadoAComponentesYTraduccion::armar('ComponenteVer', 'componenteModificacion', '', $matriz_componentes, $matriz_componentes['cp_nombre']);
    }

}

