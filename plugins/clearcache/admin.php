<?php
class plugins_clearcache_admin{
    /**
     * @var backend_model_template
     */
    protected $template, $header, $plugins, $message;
    public $action,$clear;
    /**
     * frontend_controller_home constructor.
     */
    public function __construct(){
        $this->template = new backend_model_template();
        $this->plugins = new backend_controller_plugins();
        $this->header = new http_header();
        $formClean = new form_inputEscape();
        $this->message = new component_core_message($this->template);
        if(http_request::isGet('controller')){
            $this->controller_name = $formClean->simpleClean($_GET['controller']);
        }
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }

        if(http_request::isPost('clear')){
            $this->clear = $formClean->simpleClean($_POST['clear']);
        }
    }

    /**
     * @param $data
     * @return string
     */
    private function setCacheDirectory($data){
        $basePath = component_core_system::basePath();
        switch($data['app']){
            case 'public':
                $setDir = $basePath.'var'.DIRECTORY_SEPARATOR.$data['dir'].DIRECTORY_SEPARATOR;
                break;
            case 'admin':
                $setDir = $basePath.PATHADMIN.DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.$data['dir'].DIRECTORY_SEPARATOR;
                break;
        }
        return $setDir;
    }

    /**
     * @param $data
     */
    private function setRemoveFiles($data){
        $makeFiles = new filesystem_makefile();
        $finder = new file_finder();
        $setCacheDir = $this->setCacheDirectory($data);
        if(file_exists($setCacheDir)){
            $setFiles = $finder->scanDir($setCacheDir,array('.htaccess','.gitignore'));
            $clean = '';
            if($setFiles != null){
                foreach($setFiles as $file){
                    $clean .= $makeFiles->remove($setCacheDir.$file);
                }
            }
        }
    }

    /**
     *
     */
    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'delete':
                    if(isset($this->clear)){
                        $this->setRemoveFiles(array('app'=>$this->clear,'dir'=>'templates_c'));
                        $this->setRemoveFiles(array('app'=>$this->clear,'dir'=>'minify'));
                        $this->header->set_json_headers();
                        $this->message->json_post_response(true, 'delete_multi');
                    }
                    break;
            }
        }else{
            $this->template->display('index.tpl');
        }
    }
}
?>