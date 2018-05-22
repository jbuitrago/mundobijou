<?php

// CONFIGURATION
// Set a password to limit access to generating XML's
$token = "";
// Set your MySQL details 
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "kirke";
// Set your custom table name to generate 
$table_name = "noticias";
// Set your custom column name to generate 
$column_name = "id";
// Your site Base URL
$base_url = "http://localhost/kirke/web/";
$frequency = "weekly";
// These are the static/page/subdirectories you need to include in the site map
$static_pages = array(
    "index",
    "contacto",
    "quienes_somos",
    "servicios_contables",
    "noticias_contables/",
    "asesoramiento_servicios_contables",
    "asesoramiento_servicios_impositivos",
    "asesoramiento_servicios_laborales_previsionales",
    "asesoramiento_servicios_societarios",
    "auditoria_contable",
    "blanqueo_de_capitales_y_moratoria_fiscal_y_previsional_2016",
    "busqueda_y_seleccion_de_personal");

// END CONFIGURATION
if ($_GET["token"] != $token) {
    die("Not Authorized");
}
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT $column_name FROM $table_name WHERE borrado='no'";
$result = mysqli_query($conn, $query);
$dom = new DomDocument('1.0', 'UTF-8');
$root = $dom->appendChild($dom->createElement('urlset'));
$attr = $dom->createAttribute('xmlns');
$attr->appendChild($dom->createTextNode('http://www.sitemaps.org/schemas/sitemap/0.9'));
$root->appendChild($attr);
$count = 0;
foreach ($static_pages as $page) {
    generate_elements($page);
}
while ($row = mysqli_fetch_assoc($result)) {
    $curr = $row[$column_name];
    generate_elements('noticia_interna/' . $curr);
}
echo "<h2>Total Pages Added to sitemap :  $count</h2>";
$dom->formatOutput = true;
$test1 = $dom->saveXML();
$dom->save('sitemap.xml');

function generate_elements($curr) {
    global $base_url, $frequency, $root, $dom, $count;
    $url = $dom->createElement('url');
    $root->appendChild($url);
    $loc = $dom->createElement('loc', $base_url . $curr);
    $url->appendChild($loc);
    $lastmod = $dom->createElement('lastmod', date("Y-m-d"));
    $url->appendChild($lastmod);
    $changefreq = $dom->createElement('changefreq', $frequency);
    $url->appendChild($changefreq);
    $priority = $dom->createElement('priority', 1);
    $url->appendChild($priority);
    $count++;
}

?> 