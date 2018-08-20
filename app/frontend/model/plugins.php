<?php
class frontend_model_plugins{
    protected $template, $controller_name, $dbPlugins,$plugin ,$collectionLanguage;

	/**
	 * frontend_model_plugins constructor.
	 * @param stdClass $t
	 */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new frontend_model_template();
        $formClean = new form_inputEscape();
        if(http_request::isGet('controller')){
            $this->controller_name = $formClean->simpleClean($_GET['controller']);
        }
        if(http_request::isGet('plugin')){
            $this->plugin = $formClean->simpleClean($_GET['plugin']);
        }
        //$this->dbPlugins = new backend_db_plugins();
        //$this->data = new backend_model_data($this);
        $this->collectionLanguage = new component_collections_language();
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
                        'public_local_',
                    )
                    ,false
                );
            }
        }
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
}
?>