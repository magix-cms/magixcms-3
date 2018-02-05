<?php
class install_controller_config{

    public $MP_DBDRIVER,$MP_DBHOST,$MP_DBUSER,$MP_DBPASSWORD,$MP_DBNAME,$MP_LOG,$MP_FIREPHP;

    public function __construct()
    {
        $formClean = new form_inputEscape();

        if (http_request::isPost('MP_DBDRIVER')) {
            $this->MP_DBDRIVER = $formClean->simpleClean($_POST['MP_DBDRIVER']);
        }
        if (http_request::isPost('MP_DBHOST')) {
            $this->MP_DBHOST = $formClean->simpleClean($_POST['MP_DBHOST']);
        }
        if (http_request::isPost('MP_DBUSER')) {
            $this->MP_DBUSER = $formClean->simpleClean($_POST['MP_DBUSER']);
        }
        if (http_request::isPost('MP_DBPASSWORD')) {
            $this->MP_DBPASSWORD = $formClean->simpleClean($_POST['MP_DBPASSWORD']);
        }
        if (http_request::isPost('MP_DBNAME')) {
            $this->MP_DBNAME = $formClean->simpleClean($_POST['MP_DBNAME']);
        }
        if (http_request::isPost('MP_LOG')) {
            $this->MP_LOG = $formClean->simpleClean($_POST['MP_LOG']);
        }
    }

    /*private function testConnexion(){
        $database = component_routing_db::layer();
        if($database){
            print 'ok';
        }
    }*/

    /**
     * @return string
     */
    private function filesBasePath(){
        return component_core_system::basePath().'app'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR;
    }

    /**
     *
     */
    private function createConfigFiles(){
        $makefiles = new filesystem_makefile();
    }
    /**
     *
     */
    public function run(){
        //$this->testConnexion();
        install_model_smarty::getInstance()->display('config/index.tpl');
    }
}
?>