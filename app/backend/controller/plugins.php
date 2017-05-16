<?php
class backend_controller_plugins extends backend_db_plugins{
    protected $modelPlugins,$template,$message,$header,$data,$finder;

    public function __construct()
    {
        $this->modelPlugins = new backend_model_plugins();
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $this->finder = new file_finder();
    }
    /**
     * Assign data to the defined variable or return the data
     * @param string $context
     * @param string $type
     * @param string|int|null $id
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null) {
        return $this->data->getItems($type, $id, $context);
    }

    /**
     * @return array
     */
    private function setRegistrationItems(){
        $newsItems = array();
        $pluginsDir = $this->finder->scanRecursiveDir(component_core_system::basePath().'/plugins');
        $pluginsRegister = $this->getItems('list',null,'return');
        foreach($pluginsRegister as $item){
            $registerItems[]=$item['name'];
        }
        $newRegisterItems = array_flip($registerItems);
        foreach($pluginsDir as $item){
            if(!isset($newRegisterItems[$item])){
                if(file_exists(component_core_system::basePath().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$item.DIRECTORY_SEPARATOR.'admin.php')){
                    //Nom de la classe pour le test de la méthode
                    $class = 'plugins_'.$item.'_admin';
                    if(class_exists($class)){
                        //Si la méthode run existe on ajoute le plugin dans le menu
                        if(method_exists($class,'run')){
                            $newsItems[]=$item;
                        }
                    }
                }
            }
        }
        return $newsItems;
    }

    /**
     *
     */
    public function run(){
        $data = $this->getItems('list',null,'return');
        //print_r($data);
        $this->template->assign('getListPlugins',$data);
        $this->template->assign('getListPluginsRegistration',$this->setRegistrationItems());
        $this->template->display('plugins/index.tpl');
    }
}
?>