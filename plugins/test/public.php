<?php
class plugins_test_public{
    protected $template;
    /**
     * frontend_controller_home constructor.
     */
    public function __construct(){
        $this->template = new frontend_model_template();
    }
    public function run(){
        $this->template->display('test/index.tpl');
    }
}
?>