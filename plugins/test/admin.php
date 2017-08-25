<?php

/**
 * Class plugins_test_admin
 * Fichier pour l'administration d'un plugin
 */
class plugins_test_admin{
    /**
     * @var backend_model_template
     */
    protected $controller_name,$template, $message, $plugins, $xml, $sitemap;
    /**
     * frontend_controller_home constructor.
     */
    public function __construct(){
        $this->template = new backend_model_template();
        $this->plugins = new backend_controller_plugins();
        $formClean = new form_inputEscape();
        $this->message = new component_core_message($this->template);
        $this->xml = new xml_sitemap();
        $this->sitemap = new backend_model_sitemap($this->template);
        if(http_request::isGet('controller')){
            $this->controller_name = $formClean->simpleClean($_GET['controller']);
        }
    }

    /**
     * @param $config
     */
    public function setSitemap($config){
        $dateFormat = new date_dateformat();
        //print 'lang sitemap plugins: '.$config['id_lang'];
        $url = '/' . $config['iso_lang']. '/'.$config['name'].'/';
        $this->xml->writeNode(
            array(
                'type'      =>  'child',
                'loc'       =>  $this->sitemap->url(array('domain' => $config['domain'], 'url' => $url)),
                'image'     =>  false,
                'lastmod'   =>  $dateFormat->dateDefine(),
                'changefreq'=>  'always',
                'priority'  =>  '0.7'
            )
        );
    }
    public function run(){
        $this->template->display('index.tpl');
    }
}
?>