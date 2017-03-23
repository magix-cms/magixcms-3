<?php
class component_routing_dispatcher{
    /**
     * @var Dispatcher
     */
    public static $instance = null;
    public $controller,$controller_name,$plugin;

    public function __construct($router){
        $formClean = new form_inputEscape();
        $this->controller = $router.'_controller_';
        if(http_request::isGet('controller')){
            $this->controller_name = $formClean->simpleClean($_GET['controller']);
        }
    }

    /**
     * @return mixed
     */
    private function getController(){
        if($this->controller_name){
            $controller_class = $this->controller.$this->controller_name;
        }else{
            $controller_class = $this->controller.'home';
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
            $dispatcher->run();
        }
    }
}
?>