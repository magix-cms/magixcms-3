<?php

/**
 * Class plugins_test_public
 * Fichier pour l'éxecution frontend d'un plugin
 */
class plugins_contact_public
{
    protected $template,$header,$data,$getlang;
    public $content;

    /**
     * frontend_controller_home constructor.
     */
    public function __construct()
    {
        $this->template = new frontend_model_template();
        $formClean = new form_inputEscape();
        $this->header = new component_httpUtils_header($this->template);
        //$this->data = new frontend_model_data($this);
        $this->getlang = $this->template->currentLanguage();
        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }
    }
}