<?php

class Acciones_UsuarioListado extends Armado_Plantilla {

    public function armado() {
        
        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('administrador_usuarios');

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('tabla'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '54');
        $armado_botonera->armar('nuevo', $parametros);

        // modificacion de orden de los elementos de la tabla
        if (isset($_GET['orden_act']) && $_GET['orden_act'] != '' && $_GET['id_orden_act'] != ''
        ) {
            if ($_GET['orden_ant'] != '' && $_GET['id_orden_ant'] != ''
            ) {
                Consultas_CambiarOrden::armado('kirke_usuario', $_GET['id_orden_ant'], 'orden', $_GET['orden_act'], 'id_usuario');
                Consultas_CambiarOrden::armado('kirke_usuario', $_GET['id_orden_act'], 'orden', $_GET['orden_ant'], 'id_usuario');
            } elseif ($_GET['orden_sig'] != '' && $_GET['id_orden_sig'] != ''
            ) {
                Consultas_CambiarOrden::armado('kirke_usuario', $_GET['id_orden_sig'], 'orden', $_GET['orden_act'], 'id_usuario');
                Consultas_CambiarOrden::armado('kirke_usuario', $_GET['id_orden_act'], 'orden', $_GET['orden_sig'], 'id_usuario');
            }
        }

        // datos necesarios para armar la tabla:
        // columna 0
        $tabla_columnas[0]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[0]['tb_titulo_idioma'] = '{TR|o_apellido}';
        $tabla_columnas[0]['tb_columna_ancho'] = '30';
        $tabla_columnas[0]['tb_campo'] = 'apellido';
        // columna 1
        $tabla_columnas[1]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[1]['tb_titulo_idioma'] = '{TR|o_nombre}';
        $tabla_columnas[1]['tb_columna_ancho'] = '';
        $tabla_columnas[1]['tb_campo'] = 'nombre';
        // columna 2
        $tabla_columnas[2]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[2]['tb_titulo_idioma'] = '{TR|o_usuario}';
        $tabla_columnas[2]['tb_columna_ancho'] = '';
        $tabla_columnas[2]['tb_campo'] = 'usuario';
        // columna 3
        $tabla_columnas[3]['tb_columna_tipo'] = 'orden';
        $tabla_columnas[3]['tb_titulo_idioma'] = '{TR|o_orden}';
        $tabla_columnas[3]['tb_campo'] = 'orden';
        $tabla_columnas[3]['tb_campo_id'] = 'id_usuario';
        $tabla_columnas[3]['accion'] = '57';
        // columna 4
        $tabla_columnas[4]['tb_columna_tipo'] = 'ver';
        $tabla_columnas[4]['tb_titulo_idioma'] = '{TR|o_ver}';
        $tabla_columnas[4]['tb_campo'] = 'id_usuario';
        $tabla_columnas[4]['variable_link'] = 'id_usuario';
        $tabla_columnas[4]['accion'] = '60';
        // columna 5
        $tabla_columnas[5]['tb_columna_tipo'] = 'editar';
        $tabla_columnas[5]['tb_titulo_idioma'] = '{TR|o_editar}';
        $tabla_columnas[5]['tb_campo'] = 'id_usuario';
        $tabla_columnas[5]['accion'] = '58';
        // columna 6
        $tabla_columnas[6]['tb_columna_tipo'] = 'eliminar';
        $tabla_columnas[6]['tb_titulo_idioma'] = '{TR|o_eliminar}';
        $tabla_columnas[6]['tb_campo'] = 'id_usuario';
        $tabla_columnas[6]['accion'] = '56';
        
        if (Inicio::usuario('tipo') == 'administrador general') {
            $es_administrador = true;
        }else{
            $es_administrador = false;
        }

        $tabla = Consultas_MatrizUsuarios::armado($es_administrador);

        // armado de la tabla
        $armar_tabla = new Armado_Tabla();
        $armar_tabla->sinDatosPie();
        return $armar_tabla->armar($tabla_columnas, $tabla);
    }

}

