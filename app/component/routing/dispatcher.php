<?php
class component_routing_dispatcher{
    /**
     * @var Dispatcher
     */
    public static $instance = null;
    public $router,$controller,$controller_name,$plugins;

    public function __construct($router){
        $formClean = new form_inputEscape();
        $this->router = $router;
        $this->controller = $router.'_controller_';
        //$this->plugins = $plugins;
        if(http_request::isGet('controller')){
            $this->controller_name = $formClean->simpleClean($_GET['controller']);
        }
        if(http_request::isGet('plugins')){
            $this->plugins = $formClean->simpleClean($_GET['plugins']);
        }
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
                        $controller_class = $this->router.'_'.$this->controller_name.'_'.$this->plugins;
                    }else{
                        $logger = new debug_logger(MP_LOG_DIR);
                        $logger->log('php', 'error', 'An error has occured : '.$this->router. ' ' . $this->controller_name , debug_logger::LOG_MONTH);
                        //trigger_error('An error has occured : '.$this->router. ' ' . $this->controller_name, E_USER_WARNING);
                    }
                }
                break;
        }

        try{
            if(class_exists($controller_class)) {
                $class =  new $controller_class;
                if ($class instanceof $controller_class) {
                    return $class;
                } else {
                    throw new Exception('not instantiate the class: ' . $controller_class);
                }
            }
        }catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     *
     */
    public function dispatch(){
        $dispatcher = $this->getController();
        if($dispatcher){
            if(method_exists($dispatcher,'run')){
                $dispatcher->run();
            }
        }
    }
}
?>