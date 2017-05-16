<?php
class plugins_test2_admin{
    /**
     * @var backend_model_template
     */
    protected $template, $plugins;
    /**
     * frontend_controller_home constructor.
     */
    public function __construct(){
        $this->template = new backend_model_template();
        $this->plugins = new backend_model_plugins();
    }
    public function run(){
        $this->template->display('index.tpl');
    }
}
?>