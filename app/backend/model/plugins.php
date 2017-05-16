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
        return $this->dbPlugins->fetchData(array('context'=>'all','type'=>'list'));
    }

    /**
     * @return mixed|null
     */
    public function getItems(){
        return $this->setItems();
    }

    /**
     * @param $id
     */
    public function register($id){
        $data = $this->dbPlugins->fetchData(array('context'=>'unique','type'=>'register'),array(':id'=>$id));
        if($data['id_plugins'] != null){
            return;
        }else{
            print_r(array('type'=>'register'),array('name'=>$id));
            //$this->dbPlugins->insert(array('type'=>'register'),array('name'=>$id));
            return;
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