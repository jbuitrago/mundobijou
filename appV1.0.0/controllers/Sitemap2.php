<?php

require_once APPPATH . 'libraries/Sitemap.php';

use SitemapPHP\Sitemap;

class Sitemap2 extends CI_Controller {

    function __construct() {

        parent::__construct();
    }

    /**
     *  Updates Sitemap.xml when called from the command line. Not available via URL
     */
    public function index() {
        // your website url
        $sitemap = new Sitemap('http://localhost/kirke');

        // This will also need to be set by you. 
        // the full server path to the sitemap folder 
        $sitemap->setPath('/srv/www/htdocs/kirke');

        // the name of the file that is being written to
        $sitemap->setFilename('mysitemap');
        
        $sitemap->addItem('/', '1.0', 'daily', 'Today');
        
        $sitemap->createSitemapIndex('http://localhost/kirke/', 'Today');
    }

}
