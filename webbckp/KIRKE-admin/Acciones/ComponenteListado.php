<?php

class Acciones_ComponenteListado extends Armado_Plantilla {

    static private $_parametros_valores;

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('tabla'));

        $armado_botonera = new Armado_Botonera();
        $parametros = array('kk_generar' => '0', 'accion' => '30');
        $armado_botonera->armar('volver', $parametros);

        // modificacion de orden de los elementos de la tabla
        if (isset($_GET['orden_act']) && $_GET['orden_act'] != '' && $_GET['id_orden_act'] != ''
        ) {
            if (isset($_GET['orden_ant']) && $_GET['orden_ant'] != '' && $_GET['id_orden_ant'] != ''
            ) {
                Consultas_CambiarOrden::armado('kirke_componente', $_GET['id_orden_ant'], 'orden', $_GET['orden_act'], 'id_componente');
                Consultas_CambiarOrden::armado('kirke_componente', $_GET['id_orden_act'], 'orden', $_GET['orden_ant'], 'id_componente');
            } elseif ($_GET['orden_sig'] != '' && $_GET['id_orden_sig'] != ''
            ) {
                Consultas_CambiarOrden::armado('kirke_componente', $_GET['id_orden_sig'], 'orden', $_GET['orden_act'], 'id_componente');
                Consultas_CambiarOrden::armado('kirke_componente', $_GET['id_orden_act'], 'orden', $_GET['orden_sig'], 'id_componente');
            }
        }

        // datos necesarios para armar la tabla:
        $id_columna = 0;

        // id del componente
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '5';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_id}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'cp_id';
        $id_columna++;

        // nombre asignado al componente
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '20';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_nombre}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'nombre';
        $id_columna++;

        // nombre del campo en la base
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '20';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_campo_nombre}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'tb_campo';
        $id_columna++;

        // nombre del componente
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_componente}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'cp_nombre';
        $id_columna++;

        // muestra si su carga es obligatoria
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'opcion';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_obligatorio}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'obligatorio';
        $id_columna++;

        // muestra si se muestra en el listado
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'opcion';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_listado_mostrar}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'listado_mostrar';
        $id_columna++;

        // muestra si tiene el filtro activado
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'opcion';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_filtrar}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'filtrar';
        $id_columna++;

        // orden
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'orden';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_orden}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'cp_orden';
        $tabla_columnas[$id_columna]['tb_campo_id'] = 'cp_id';
        $tabla_columnas[$id_columna]['accion'] = '5';
        $id_columna++;

        // editar
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'editar';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_editar}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'cp_id';
        $tabla_columnas[$id_columna]['accion'] = '6';
        $id_columna++;

        // eliminar
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'eliminar';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_eliminar}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'cp_id';
        $tabla_columnas[$id_columna]['accion'] = '4';
        $id_columna++;

        // obtengo los datos del componente y sus parametros
        $tabla = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos', $_GET['id_tabla']);

        $tabla_armada = '';

        if (is_array($tabla)) {

            foreach ($tabla as $id => $value) {

                $tabla_valores[$id]['cp_id'] = $value['cp_id'];
                $tabla_valores[$id]['nombre'] = $value['idioma_' . Generales_Idioma::obtener()];
                $tabla_valores[$id]['tb_campo'] = $value['tb_campo'];
                $tabla_valores[$id]['cp_nombre'] = $value['cp_nombre'];
                $tabla_valores[$id]['cp_orden'] = $value['cp_orden'];

                if (!isset(self::$_parametros_valores[$value['cp_nombre']])) {
                    $parametros_predefinidos = Componentes_Componente::componente($value['cp_nombre'], 'ParametrosValores');
                } else {
                    $parametros_predefinidos = self::$_parametros_valores[$value['cp_nombre']];
                }
                
                // columna obligatorio
                if (!isset($value['obligatorio']) && !isset($parametros_predefinidos['obligatorio'])) {
                    $tabla_valores[$id]['obligatorio'] = '';
                } elseif (
                        (
                        isset($value['obligatorio']) && ($value['obligatorio'] == 'no_nulo')
                        ) || (
                        isset($parametros_predefinidos['obligatorio']) && ($parametros_predefinidos['obligatorio'] == 'no_nulo')
                        )
                ) {
                    $tabla_valores[$id]['obligatorio'] = 1;
                } else {
                    $tabla_valores[$id]['obligatorio'] = 0;
                }
                // columna listado_mostrar
                if (!isset($value['listado_mostrar']) && !isset($parametros_predefinidos['listado_mostrar'])) {
                    $tabla_valores[$id]['listado_mostrar'] = '';
                } elseif (
                        (
                        isset($value['listado_mostrar']) && ($value['listado_mostrar'] == 's')
                        ) || (
                        isset($parametros_predefinidos['listado_mostrar']) && ($parametros_predefinidos['listado_mostrar'] == 's')
                        )
                ) {
                    $tabla_valores[$id]['listado_mostrar'] = 1;
                } else {
                    $tabla_valores[$id]['listado_mostrar'] = 0;
                }
                // columna filtrar
                if (!isset($value['filtrar']) && !isset($parametros_predefinidos['filtrar'])) {
                    $tabla_valores[$id]['filtrar'] = '';
                } elseif (
                        (
                        isset($value['filtrar']) && ($value['filtrar'] == 's')
                        ) || (
                        !isset($value['filtrar']) && isset($parametros_predefinidos['filtrar']) && ($parametros_predefinidos['filtrar'] == 's')
                        )
                ) {
                    $tabla_valores[$id]['filtrar'] = 1;
                } else {
                    $tabla_valores[$id]['filtrar'] = 0;
                }
            }
            
            // armado de la tabla
            $armar_tabla = new Armado_Tabla();
            $armar_tabla->sinDatosPie();
            $tabla_armada = $armar_tabla->armar($tabla_columnas, $tabla_valores);
        }

        // seleccion de los componentes
        $armado_select = new Armado_ComponenteSelect();
        $componente_select = $armado_select->armar('componente_select');

        // envio de datos al cuerpo de la pagina
        return $tabla_armada . $componente_select . '<div class="contenido_separador"></div>';
    }

}

