<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Db_query_log {

    function __construct() {
        //nothing special    
    }

    function log_db_queries() {
        $CI = & get_instance();
        $queries = $CI->db->queries;

        foreach ($queries as $key => $query) {
            //echo $query . "<br>";
           // die;
            // all statements displayed are SELECT statements even for UPDATE and INSERT operations performed by controllers where data is actually changed 
        }
    }

}
