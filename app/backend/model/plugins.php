<?php
class backend_model_plugins{
    protected $template, $controller_name, $dbPlugins,$plugin;

    /**
     * backend_model_plugins constructor.
     */
    public function __construct()
    {
        $this->template = new backend_model_template();
        $formClean = new form_inputEscape();
        if(http_request::isGet('controller')){
            $this->controller_name = $formClean->simpleClean($_GET['controller']);
        }
        if(http_request::isGet('plugin')){
            $this->plugin = $formClean->simpleClean($_GET['plugin']);
        }
        $this->dbPlugins = new backend_db_plugins();
        //$this->data = new backend_model_data($this);
    }

    /**
     * @param $className
     * @return mixed
     */
    public function getCallClass($className){
        try{
            $class =  new $className;
            if($class instanceof $className){
                return $class;
            }else{
                throw new Exception('not instantiate the class: '.$className);
            }
        } catch (Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * @param $config
     * @return array
     */
    private function setItems($config){
        $data =  $this->dbPlugins->fetchData(array('context'=>'all','type'=>'list'));
        foreach($data as $item){
            switch($config['type']){
                case 'self':
                    if(file_exists(component_core_system::basePath().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$item['name'].DIRECTORY_SEPARATOR.'admin.php')) {
                        $class = 'plugins_' . $item['name'] . '_admin';
                        if (class_exists($class)) {
                            //Si la méthode run existe on ajoute le plugin dans le menu
                            if (method_exists($class, 'run')) {
                                $newsItems[] = $item;
                            }
                        }
                    }

                    break;
                case 'tabs':
                    //Ajoute l'onglet si le plugin est inscrit pour le core
                    if($item[$config['controller']] != '0'){
                        $newsItems[] = $item;
                        $this->template->assign('setTabsPlugins',$newsItems);
                    }

                    break;
                case 'core':
                    if(file_exists(component_core_system::basePath().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$item['name'].DIRECTORY_SEPARATOR.'core.php')) {
                        $class = 'plugins_' . $item['name'] . '_core';
                        if (method_exists($class, 'run')) {
                            if(isset($this->plugin)){
                                $executeClass = $this->getCallClass('plugins_' . $this->plugin . '_core');
                                $executeClass->run();
                            }
                        }
                    }
                    break;
            }
        }
        return $newsItems;
    }

    /**
     * @param $config
     * @return array
     */
    public function getItems($config){
        return $this->setItems($config);
    }

    /**
     * @param $id
     * @return array
     */
    public function readConfigXML($id){
        $pluginsDir = component_core_system::basePath().'plugins'.DIRECTORY_SEPARATOR.$id;
        $XMLFiles = $pluginsDir.DIRECTORY_SEPARATOR.'config.xml';
        if(file_exists($XMLFiles)) {
            try {
                if ($stream = fopen($XMLFiles, 'r')) {
                    $streamData = stream_get_contents($stream, -1, 0);
                    $streamData = urldecode($streamData);
                    $xml = simplexml_load_string($streamData, null, LIBXML_NOCDATA);
                    $newData = array();
                    $i = 0;
                    if(isset($xml->data->authors)) {
                        foreach ($xml->data->authors->author as $item) {
                            if (isset($item->website)) {
                                $newData['authors'][$i]['website'] = $item->website['href']->__toString();
                            }
                            if (isset($item->name)) {
                                $newData['authors'][$i]['name'] = $item->name->__toString();
                            }
                            $i++;
                        }
                    }
                    foreach ($xml->{'data'}->{'release'}->children() as $item => $value) {
                        $newData['release'][$item] = $value->__toString();
                    }
                    foreach ($xml->{'data'}->{'support'}->children() as $item => $value) {
                        $newData['support'][$item] = $value['href']->__toString();
                    }
                    fclose($stream);
                    /*print '<pre>';
                    print_r($newData);
                    print '</pre>';*/
                    return $newData;
                }

            }catch(Exception $e) {
                $logger = new debug_logger(MP_LOG_DIR);
                $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
            }
        }
    }
    /**
     * @param $routes
     * @param $template
     * @param $plugins
     */
    public function templateDir($routes, $template, $plugins){
        if(isset($this->controller_name)){
            $setTemplatePath = component_core_system::basePath().'/'.$routes.'/'.$this->controller_name.'/skin/'.$plugins.'/';
            if(file_exists($setTemplatePath)){
                $template->addTemplateDir($setTemplatePath);
            }
        }
    }

    /**
     * @param $routes
     * @param $template
     */
    public function addConfigDir($routes, $template){
        if(isset($this->controller_name)){
            $setConfigPath = component_core_system::basePath().'/'.$routes.'/'.$this->controller_name.'/i18n/';
            if(file_exists($setConfigPath)){
                $template->addConfigFile(
                    array(
                        component_core_system::basePath().'/'.$routes.'/'.$this->controller_name.'/i18n/'
                    ),
                    array(
                        'admin_local_',
                    )
                    ,false
                );
            }
        }
    }

    /**
     * @param null $template
     * @param null $plugin
     * @param null $cache_id
     * @param null $compile_id
     * @param null $parent
     */
    public function display($template = null, $plugin = null, $cache_id = null, $compile_id = null, $parent = null){
        if($plugin != null){
            $this->template->addTemplateDir($this->template->pluginsBasePath().$plugin.'/skin/admin/');
        }else{
            $this->template->addTemplateDir($this->template->pluginsBasePath().$this->plugin.'/skin/admin/');
        }

        if(!$this->template->isCached($template, $cache_id, $compile_id, $parent)){
            $this->template->display($template, $cache_id, $compile_id, $parent);
        }else{
            $this->template->display($template, $cache_id, $compile_id, $parent);
        }
    }
}
?>