<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class producto_model extends Default_model {

    var $table = 'producto';
    var $column = array('producto.id', 'titulo', 'articulo', 'categorias.nombre AS categoria', 'subcategorias.nombre AS subcategoria', 'precio_mayorista', 'precio_revendedor', 'stock', 'oferta', 'nuevo');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
    }

    public function get_ofertas() {

        $this->db->select($this->table.'.*, categorias.nombre AS categoria');

        $this->db->from($this->table);

        $this->db->join('categorias', 'categorias.id = ' . $this->table . '.categoria_id');

        $this->db->where($this->table . '.borrado', 'no');

        $this->db->where($this->table . '.oferta', 'SI');
        
        $this->db->where('stock>0');
        
        $this->db->order_by('id', 'desc');

        $this->db->limit(4);

        $query = $this->db->get();

        return $query->result();
    }

    public function last_prod() {

        $this->db->select($this->table.'.*, categorias.nombre AS categoria');

        $this->db->from($this->table);

        $this->db->join('categorias', 'categorias.id = ' . $this->table . '.categoria_id');
        
        $this->db->where($this->table . '.borrado', 'no');
        
        $this->db->where($this->table . '.nuevo', 'SI');
        
        $this->db->where('stock>0');
        
        $this->db->order_by('id', 'desc');

        $this->db->limit(4);

        $query = $this->db->get();

        return $query->result();
    }

    public function query_list_get_select() {

        $this->db->select(implode(',', $this->column));

        $this->db->from($this->table);

        $this->db->join('categorias', 'categorias.id = ' . $this->table . '.categoria_id');

        $this->db->join('subcategorias', 'subcategorias.id = ' . $this->table . '.sub_categoria_id', 'left');

        $this->db->where($this->table . '.borrado', 'no');
    }

    public function get_colores($id) {

        $this->db->select('color_id');

        $this->db->from('producto_colores');

        $this->db->where('producto_id', $id);

        $query = $this->db->get();

        $data = array();

        foreach ($query->result() as $row) {
            $data[] = $row->color_id;
        }

        return $data;
    }

    public function get_talles($id) {

        $this->db->select('talle_id');

        $this->db->from('producto_talles');

        $this->db->where('producto_id', $id);

        $query = $this->db->get();

        $data = array();

        foreach ($query->result() as $row) {

            $data[] = $row->talle_id;
        }

        return $data;
    }

    public function get_medidas($id) {

        $this->db->select('medida_id');

        $this->db->from('producto_medidas');

        $this->db->where('producto_id', $id);

        $query = $this->db->get();

        $data = array();

        foreach ($query->result() as $row) {
            $data[] = $row->medida_id;
        }

        return $data;
    }

    public function delete_all_from($table, $id) {

        $this->db->where('producto_id', $id);

        $this->db->delete($table);
    }

    public function get_by_slug($id) {

        $data_prod = explode('-', $id);
       
        $this->db->select($this->table . '.*, categorias.nombre AS categoria, subcategorias.nombre AS subcategoria', FALSE);

        $this->db->from($this->table);

        $this->db->join('categorias', 'categorias.id = ' . $this->table . '.categoria_id');

        $this->db->join('subcategorias', 'subcategorias.id = ' . $this->table . '.sub_categoria_id', 'left');

        //$this->db->where('slug', $id);

        //$this->db->where($this->table.'.titulo', $data_prod['0']);
        //
        //$this->db->where($this->table.'.articulo', $data_prod['1']);
        
        $this->db->where($this->table.'.id', $data_prod[count($data_prod)-1]);
        
        $this->db->where('stock>0');

        $this->db->where($this->table . '.borrado', 'no');

        $query = $this->db->get();
    
        return $query->row();
    }

    public function web_get_colores($id) {

        $this->db->select('nombre, color_id, codigo');

        $this->db->from('producto_colores');

        $this->db->join('colores', 'colores.id = producto_colores.color_id');

        $this->db->where('producto_colores.producto_id', $id);

        $query = $this->db->get();

        $data = array();

        foreach ($query->result() as $row) {
            $data[] = $row;
        }

        return $data;
    }

    public function web_get_talles($id) {

        $this->db->select('nombre, talle_id');

        $this->db->from('producto_talles');

        $this->db->join('talles', 'talles.id = producto_talles.talle_id');

        $this->db->where('producto_talles.producto_id', $id);

        $query = $this->db->get();

        $data = array();

        foreach ($query->result() as $row) {

            $data[] = $row;
        }

        return $data;
    }

    public function web_get_medidas($id) {

        $this->db->select('nombre, medida_id');

        $this->db->from('producto_medidas');

        $this->db->join('medidas', 'medidas.id = producto_medidas.medida_id');

        $this->db->where('producto_medidas.producto_id', $id);

        $query = $this->db->get();

        $data = array();

        foreach ($query->result() as $row) {
            $data[] = $row;
        }

        return $data;
    }

    function get_productos($limit, $start, $sort, $order, $categoria, $subcategoria) {

        $this->db->select($this->table.'.*,categorias.nombre AS categoria, (SELECT COUNT(*) FROM pedido_items WHERE tipo_producto="producto" AND producto_id=producto.id GROUP BY producto.id) AS moresell', FALSE);

        $this->db->join('categorias', 'categorias.id = ' . $this->table . '.categoria_id','left');
        
        if ($categoria != 0) {
            $this->db->where('categoria_id', $categoria);
        }
        if ($subcategoria != 0) {
            $this->db->where('sub_categoria_id', $subcategoria);
        }

        $this->db->where($this->table.'.borrado', 'no');

        $this->db->where('stock >0');
        
        /*$this->db->where('precio_por_diez_mayorista <> ""');
        
        $this->db->where('precio_por_diez_revendedor <> ""');
        
        $this->db->where('id IN(SELECT producto_id FROM producto_colores)');
        
        $this->db->where('id IN(SELECT producto_id FROM producto_talles)');
        
        $this->db->where('id IN(SELECT producto_id FROM producto_medidas)');*/
        
        if (!empty($_POST)) {

            $this->db->where("(titulo LIKE '%".$_POST['search_ter']."%' OR articulo LIKE '%".$_POST['search_ter']."%' OR descripcion LIKE '%".$_POST['search_ter']."%' OR descripcion_corta LIKE '%".$_POST['search_ter']."%')", NULL, FALSE);
        }

        $this->db->order_by($sort, $order);

        $this->db->limit($limit, $start);

        $query = $this->db->get('producto');

        return $query->result();
    }

    public function record_count($categoria, $subcategoria) {

        if ($categoria != 0) {
            $this->db->where('categoria_id', $categoria);
        }
        if ($subcategoria != 0) {
            $this->db->where('sub_categoria_id', $subcategoria);
        }

        $this->db->where('borrado', 'no');

        $this->db->where('stock >0');

        if (!empty($_POST)) {

            $this->db->where("(titulo LIKE '%".$_POST['search_ter']."%' OR articulo LIKE '%".$_POST['search_ter']."%' OR descripcion LIKE '%".$_POST['search_ter']."%' OR descripcion_corta LIKE '%".$_POST['search_ter']."%')", NULL, FALSE);
        }
        
        $this->db->from('producto');

        return $this->db->count_all_results();
    }

}
