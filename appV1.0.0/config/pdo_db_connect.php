<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function pdo_connect() {

    try {
     
        $dbdriver = 'mysql'; 
        $hostname = 'mysql.mundobijou.com.ar';
        $database = 'base_mundobijou';
        $username = 'usuario_mundobij';
        $password = 'mundoespagueti';
        //to connect
        $DB = new PDO($dbdriver . ':host=' . $hostname . '; dbname=' . $database, $username, $password);
        return $DB;
    } catch (PDOException $e) {
        echo 'Please contact Admin: ' . $e->getMessage();
    }
}
