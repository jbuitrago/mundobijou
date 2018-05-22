<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	https://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |	$route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |		my-controller/my-method	-> my_controller/my_method
 */
include('appV1.0.0/config/pdo_db_connect.php');

class dynamic_route {

    public $pdo_db;

    public function __construct() {
        $this->pdo_db = pdo_connect();   
    }

    private function query_routes() {
        try {

            $routes_query = $this->pdo_db->query('SELECT * FROM categorias WHERE borrado="no" ORDER BY id ASC');

            if ($routes_query) {
                $return_data = array();
                foreach ($routes_query as $row) {
                    $return_data[] = $row;
                }
                return $return_data;
            }
        } catch (PDOException $e) {
            echo 'Please contact Admin: ' . $e->getMessage();
        }
    }

    private function filter_route_data($data) {

        $r_data = array();
        foreach ($data as $row) {
            $return_data = new stdClass;

            if (empty($row['Url_Variable'])) {
                $return_data->url = $row['Url'];
            } else {
                $return_data->url = $row['Url'] . '/' . $row['Url_Variable'];
            }

            if (empty($row['Method']) && empty($row['Variable'])) {
                $return_data->route = $row['Class'];
            } elseif (!empty($row['Method']) && empty($row['Variable'])) {
                $return_data->route = $row['Class'] . '/' . $row['Method'];
            } elseif (!empty($row['Method']) && !empty($row['Variable'])) {
                $return_data->route = $row['Class'] . '/' . $row['Method'] . '/' . $row['Variable'];
            }

            $r_data[] = $return_data;
        }
        return $r_data;
    }

    public function get_routes() {
        $route_data = $this->query_routes();
        //$return_data = $this->filter_route_data($route_data);
        return $route_data;
    }

}

$route['default_controller'] = 'web';
$route['404_override'] = 'my404';
$route['translate_uri_dashes'] = FALSE;
$route['^(index|carro|link|checkout1|checkout2|checkout3|checkout4|checkout5|combos|contacto_gracias|detalle|detalle_combo|mi_cuenta|cambiar_pass|mis_pedidos|olvide_pass|olvide_pass_gracias|pedido|contacto|registro|registrar_cliente|terminos_y_condiciones|index|productos|logout|process_login|cart_add|test_mail|registrate|modal_detalle|busqueda|show_404)(/:any)?$'] = "web/$0";
$route["^(productos)/(.+)"] = 'web/productos/$1';
$route["^(combos)/(.+)"] = 'web/combos/$1';

$dynamic_route = new dynamic_route;
// Give dynamic route database connection
$dynamic_route->pdo_db = pdo_connect();
// Get the route data
$route_data = $dynamic_route->get_routes();
//Iterate over the routes
foreach ($route_data as $row) {
   
    $route[strtolower(str_replace(' ', '-', $row['nombre'])).'/(:any)'] = 'web/detalle/$1';
    
    $route[strtolower(str_replace(' ', '-', $row['nombre']))] = 'web';
}

foreach ($route_data as $row) {
   
    $route[(str_replace(' ', '-', $row['nombre'])).'-'.$row['id'].'/(.+)'] = 'web/productos/$0/$1';
    $route[(str_replace(' ', '-', $row['nombre'])).'-'.$row['id'].''] = 'web/productos/$0/$1';
}