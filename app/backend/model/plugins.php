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