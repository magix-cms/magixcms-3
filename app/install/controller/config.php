<?php
class install_controller_config{
    protected $makefiles,$routingDb;
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
        $this->makefiles = new filesystem_makefile();
        $this->routingDb = new component_routing_db();
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
     * @throws Exception
     */
    private function databaseProcess(){
        $this->routingDb->setupSQL(component_core_system::basePath().'install/sql/db.sql');
    }
    /**
     *
     */
    private function createConfigFiles(){
        if(isset($this->MP_DBHOST) && isset($this->MP_DBUSER) && isset($this->MP_DBPASSWORD) && isset($this->MP_DBNAME)){
            $configFiles = $this->filesBasePath().'config.php';
            if (!is_writable(dirname($configFiles))) {
                throw new Exception(sprintf('Cannot write %s file.',$configFiles));
            }
            if (!is_file($this->filesBasePath().'config.php.in')) {
                throw new Exception(sprintf('File %s does not exist.',$this->filesBasePath().'config.php.in'));
            }
            try {
                # Creates config.php file
                $readConfigIn = file_get_contents($this->filesBasePath() . 'config.php.in');
                $this->makefiles->writeConstValue('MP_DBDRIVER', $this->MP_DBDRIVER, $readConfigIn);
                $this->makefiles->writeConstValue('MP_DBHOST', $this->MP_DBHOST, $readConfigIn);
                $this->makefiles->writeConstValue('MP_DBUSER', $this->MP_DBUSER, $readConfigIn);
                $this->makefiles->writeConstValue('MP_DBPASSWORD', $this->MP_DBPASSWORD, $readConfigIn);
                $this->makefiles->writeConstValue('MP_DBNAME', $this->MP_DBNAME, $readConfigIn);
                switch ($this->M_LOG) {
                    case 'debug':
                        $this->makefiles->writeConstValue('MP_LOG', $this->MP_LOG, $readConfigIn);
                        break;
                    case 'log':
                        $this->makefiles->writeConstValue('MP_LOG', $this->MP_LOG, $readConfigIn);
                        break;
                    case 'false':
                        $this->makefiles->writeConstValue('MP_LOG', $this->MP_LOG, $readConfigIn, false);
                }
                $this->makefiles->writeConstValue('MP_LOG_DIR', component_core_system::basePath() . 'var' . DIRECTORY_SEPARATOR . 'log', $readConfigIn);
                $this->makefiles->writeConstValue('MP_FIREPHP', 'false', $readConfigIn, false);
                $fp = fopen($configFiles, 'wb');
                if ($fp === false) {
                    throw new Exception(sprintf('Cannot write %s file.', $configFiles));
                    exit();
                }
                fwrite($fp, $readConfigIn);
                fclose($fp);
                $this->databaseProcess();

                if (!headers_sent()) {
                    header('location: ' . http_url::getUrl() . '/install/employee.php');
                    exit;
                }
            }catch(Exception $e) {
                $logger = new debug_logger(component_core_system::basePath() . 'var' . DIRECTORY_SEPARATOR . 'log');
                $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
            }
        }
    }
    /**
     *
     */
    public function run(){
        //$this->testConnexion();
        $this->createConfigFiles();
        install_model_smarty::getInstance()->display('config/index.tpl');
    }
}
?>