<?php
include_once ('db.php');
/**
 * Class plugins_test_public
 * Fichier pour l'éxecution frontend d'un plugin
 */
class plugins_contact_public extends plugins_contact_db
{
    protected $template,$header,$data,$getlang,$mail;
    public $content;

    /**
     * frontend_controller_home constructor.
     */
    public function __construct()
    {
        $this->template = new frontend_model_template();
        $formClean = new form_inputEscape();
        $this->header = new component_httpUtils_header($this->template);
        $this->data = new frontend_model_data($this);
        $this->getlang = $this->template->currentLanguage();
        $this->mail = new mail_swift();

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
    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    private function setBodyMail($debug) {
        if($debug) {

        }else{
            
        }
    }
    /**
     *
     */
    public function run(){

        if(isset($this->content)){
            print 'test';
        }
    }
}