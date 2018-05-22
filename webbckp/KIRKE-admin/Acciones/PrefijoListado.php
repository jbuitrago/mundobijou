<?php

class Acciones_PrefijoListado extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('tabla'));

        $armado_botonera = new Armado_Botonera();

        // botones de navegacion
        $parametros = array('kk_generar' => '0', 'accion' => '33');
        $armado_botonera->armar('nuevo', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '30');
        $armado_botonera->armar('volver', $parametros);

        // modificacion de orden de los elementos de la tabla
        if (isset($_GET['orden_act']) && $_GET['orden_act'] != '' && $_GET['id_orden_act'] != ''
        ) {
            if ($_GET['orden_ant'] != '' && $_GET['id_orden_ant'] != ''
            ) {
                Consultas_CambiarOrden::armado('kirke_tabla_prefijo', $_GET['id_orden_ant'], 'orden', $_GET['orden_act'], 'id_tabla_prefijo');
                Consultas_CambiarOrden::armado('kirke_tabla_prefijo', $_GET['id_orden_act'], 'orden', $_GET['orden_ant'], 'id_tabla_prefijo');
            } elseif ($_GET['orden_sig'] != '' && $_GET['id_orden_sig'] != ''
            ) {
                Consultas_CambiarOrden::armado('kirke_tabla_prefijo', $_GET['id_orden_sig'], 'orden', $_GET['orden_act'], 'id_tabla_prefijo');
                Consultas_CambiarOrden::armado('kirke_tabla_prefijo', $_GET['id_orden_act'], 'orden', $_GET['orden_sig'], 'id_tabla_prefijo');
            }
        }

        // datos necesarios para armar la tabla:
        // columna 0
        $tabla_columnas[0]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[0]['tb_titulo_idioma'] = '{TR|o_prefijos}';
        $tabla_columnas[0]['tb_columna_ancho'] = '';
        $tabla_columnas[0]['tb_campo'] = 'prefijo';
        // columna 1
        $tabla_columnas[1]['tb_columna_tipo'] = 'orden';
        $tabla_columnas[1]['tb_titulo_idioma'] = '{TR|o_orden}';
        $tabla_columnas[1]['tb_campo'] = 'orden';
        $tabla_columnas[1]['tb_campo_id'] = 'id_tabla_prefijo';
        $tabla_columnas[1]['accion'] = '35';
        // columna 2
        $tabla_columnas[2]['tb_columna_tipo'] = 'ver';
        $tabla_columnas[2]['tb_titulo_idioma'] = '{TR|o_ver}';
        $tabla_columnas[2]['tb_campo'] = 'id_tabla_prefijo';
        $tabla_columnas[2]['variable_link'] = 'id_tabla_prefijo';
        $tabla_columnas[2]['accion'] = '36';

        // query para armar la consulta
        $tabla = Consultas_MatrizPrefijos::armado();

        // armado de la tabla
        $armar_tabla = new Armado_Tabla();
        $armar_tabla->sinDatosPie();
        return $armar_tabla->armar($tabla_columnas, $tabla);
    }

}

