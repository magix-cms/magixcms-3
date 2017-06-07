<?php
class backend_model_plugins{
    protected $template, $controller_name, $dbPlugins;

    /**
     * backend_model_plugins constructor.
     */
    public function __construct()
    {
        $formClean = new form_inputEscape();
        if(http_request::isGet('controller')){
            $this->controller_name = $formClean->simpleClean($_GET['controller']);
        }
        $this->dbPlugins = new backend_db_plugins();
        //$this->data = new backend_model_data($this);
    }

    /**
     * @return mixed|null
     */
    private function setItems(){
        $data =  $this->dbPlugins->fetchData(array('context'=>'all','type'=>'list'));
        foreach($data as $item){
            if(file_exists(component_core_system::basePath().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$item['name'].DIRECTORY_SEPARATOR.'admin.php')) {
                $class = 'plugins_' . $item['name'] . '_admin';
                if (class_exists($class)) {
                    //Si la méthode run existe on ajoute le plugin dans le menu
                    if (method_exists($class, 'run')) {
                        $newsItems[] = $item;
                    }
                }
            }
        }
        return $newsItems;
    }

    /**
     * @return mixed|null
     */
    public function getItems(){
        return $this->setItems();
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
}
?>