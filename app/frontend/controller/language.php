<?php
class frontend_controller_language {
    protected $template, $arrayTools;

    public function __construct()
    {
        $this->template = new frontend_model_template();
        $this->arrayTools = new collections_ArrayTools();
    }

    /**
     * @return array
     */
    public function setCollection(){
        return $this->arrayTools->defaultLanguage();
    }

    /**
     *
     */
    public function getCollection(){
        $data = $this->setCollection();
        $this->template->assign('getLanguageCollection',$data);
    }
}
?>