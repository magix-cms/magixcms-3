<?php
class component_routing_dispatcher{
    protected $header,$template,$pluginsCollection;
    /**
     * @var Dispatcher
     */
    public $router,$controller,$controller_name,$plugins,$action;

    public function __construct($router,$template,$plugins = null){
        $formClean = new form_inputEscape();
        $this->router = $router;
        $this->controller = $router.'_controller_';
        //$this->plugins = $plugins;
        if(http_request::isGet('controller')){
            $this->controller_name = $formClean->simpleClean($_GET['controller']);
        }
        if(http_request::isGet('action')){
            $this->action = $formClean->simpleClean($_GET['action']);
        }
        if(http_request::isGet('http_error')){
            $this->http_error = form_inputFilter::isAlphaNumeric($_GET['http_error']);
        }
        $this->plugins = $plugins;
        $this->header = new component_httpUtils_header($template);
        $this->template = $template;
        $this->pluginsCollection = new component_collections_plugins();
    }

    /**
     * @return mixed
     */
    private function pluginsRegister(){
        $pluginsCheck =  $this->pluginsCollection->fetch(array('context'=>'check','name'=>$this->controller_name));
        return $pluginsCheck['name'];
    }
    /**
     * @return mixed
     */
    private function getController(){
        switch($this->router){
            case 'frontend':
                if($this->controller_name){
                    $controller_class = $this->controller.$this->controller_name;
                }else{
                    $controller_class = $this->controller.'home';
                }
                break;
            case 'backend':
                $controller_class = $this->controller.$this->controller_name;
                break;
            case 'plugins':
                if(isset($this->plugins) && $this->plugins != null){
                    $pluginLoadFiles = array('public','admin');
                    if(in_array($this->plugins,$pluginLoadFiles)){
                        $pluginsDir = component_core_system::basePath().'plugins'.DIRECTORY_SEPARATOR.$this->controller_name;
                        if($this->plugins !== 'admin'){
                            if($this->pluginsRegister() != null && file_exists($pluginsDir)){
                                $controller_class = $this->router.'_'.$this->controller_name.'_'.$this->plugins;
                            }else{
                                $this->template->assign(
                                    'getTitleHeader',
                                    $this->header->getTitleHeader(
                                        404
                                    ),
                                    true
                                );
                                $this->template->assign(
                                    'getTxtHeader',
                                    $this->header->getTxtHeader(
                                        404
                                    ),
                                    true
                                );
                                $this->template->display('error/index.tpl');
                            }
                        }else{
                            if($this->action === 'setup'){
                                if(class_exists('backend_controller_plugins')) {
                                    $pluginsController = new backend_controller_plugins();
                                    $pluginsController->register($this->controller_name);
                                }
                            }elseif($this->action === 'upgrade'){
                                if(class_exists('backend_controller_plugins')) {
                                    $pluginsController = new backend_controller_plugins();
                                    $pluginsController->upgrade($this->controller_name);
                                }
                            }else{
                                if($this->pluginsRegister() != null && file_exists($pluginsDir)){
                                    $controller_class = $this->router.'_'.$this->controller_name.'_'.$this->plugins;
                                }else{
                                    $this->template->assign(
                                        'getTitleHeader',
                                        $this->header->getTitleHeader(
                                            404
                                        ),
                                        true
                                    );
                                    $this->template->assign(
                                        'getTxtHeader',
                                        $this->header->getTxtHeader(
                                            404
                                        ),
                                        true
                                    );
                                    $this->template->display('error/index.tpl');
                                }
                            }
                        }
                    }else{
                        $logger = new debug_logger(MP_LOG_DIR);
                        $logger->log('php', 'error', 'An error has occured : '.$this->router. ' ' . $this->controller_name , debug_logger::LOG_MONTH);
                        //trigger_error('An error has occured : '.$this->router. ' ' . $this->controller_name, E_USER_WARNING);
                    }
                }
                break;
        }

        $this->template->assign('cClass',$controller_class);

        try{
            if(class_exists($controller_class)) {
                $class =  new $controller_class;
                if ($class instanceof $controller_class) {
                    return $class;
                } else {
                    //throw new Exception('not instantiate the class: ' . $controller_class);
                    $logger = new debug_logger(MP_LOG_DIR);
                    $logger->log('php', 'error', 'Not instantiate the class: : '.$controller_class , debug_logger::LOG_MONTH);
                }
            }
        }catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Execute dispatch
     */
    public function dispatch(){
        $dispatcher = $this->getController();
        if($dispatcher){
            if(method_exists($dispatcher,'run')){
                $this->header->mobileDetect();
                $dispatcher->run();
            }
        }
    }
}
?>