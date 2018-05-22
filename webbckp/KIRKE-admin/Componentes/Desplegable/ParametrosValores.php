<?php

class Componentes_Desplegable_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'numero';
        $parametro['tb_columna_tipo'] = 'nombre_link';
        $parametro['seleccionar_alta'] = 's';
        $parametro['link_a_grupo'] = 'n'; // si es 's', debe guardarse el valor igualmente, para que funciones
        $parametro['listado_mostrar'] = 'n';
        $parametro['obligatorio'] = 'nulo';
        $parametro['motrar_id'] = 'n';
        $parametro['filtrar'] = 's';
        $parametro['sufijo'] = '';
        $parametro['autofiltro'] = 'n';
        $parametro['autofiltro_elementos'] = '0';
        $parametro['filtrar_antiguedad'] = '';
        $parametro['campo'] = '';
        $parametro['separador_del_campo_1'] = '';
        $parametro['nombre_del_campo_1'] = '';
        $parametro['separador_del_campo_2'] = '';
        $parametro['nombre_del_campo_2'] = '';
        $parametro['campo_filtro'] = '';
        $parametro['condicion'] = 'no_filtrar';
        $parametro['valor'] = '';
        $parametro['ocultar_edicion'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['predefinir_ultimo_val_cargado'] = 'n';
        $parametro['tb_campo'] = '';
        $parametro['orden'] = 'alfanumerico';
        $parametro['agregar_registro'] = 'n';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
