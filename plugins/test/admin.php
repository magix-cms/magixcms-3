<?php
class plugins_test_admin{
    /**
     * @var backend_model_template
     */
    protected $template, $plugins;
    /**
     * frontend_controller_home constructor.
     */
    public function __construct(){
        $this->template = new backend_model_template();
        $this->plugins = new backend_controller_plugins();
        $formClean = new form_inputEscape();
        $this->message = new component_core_message($this->template);
        if(http_request::isGet('controller')){
            $this->controller_name = $formClean->simpleClean($_GET['controller']);
        }
    }

    public function run(){
        $this->template->display('index.tpl');
    }
}
?>