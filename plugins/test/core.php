<?php

/**
 * Class plugins_test_core
 * Fichier pour les plugins core
 */
class plugins_test_core{

    protected $template,$modelPlugins,$message;
    public $controller,$plugins,$plugin;

    public function __construct(){
        $this->modelPlugins = new backend_model_plugins();
        $this->template = new backend_model_template();
        $this->plugins = new backend_controller_plugins();
        $formClean = new form_inputEscape();
        $this->message = new component_core_message($this->template);
        if(http_request::isGet('controller')) {
            $this->controller = $formClean->simpleClean($_GET['controller']);
        }
        if(http_request::isGet('plugin')){
            $this->plugin = $formClean->simpleClean($_GET['plugin']);
        }
    }

    /**
     * Execution du plugin dans un ou plusieurs modules core
     */
    public function run(){
        if(isset($this->controller)){
            switch($this->controller){
                case 'home':
                    $this->modelPlugins->display('form/edit.tpl');
                    break;

            }
        }
    }
}
?>