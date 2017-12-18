<?php
class install_controller_home{

    /**
     *
     */
    public function run(){
        install_model_smarty::getInstance()->display('home/index.tpl');
    }
}
?>