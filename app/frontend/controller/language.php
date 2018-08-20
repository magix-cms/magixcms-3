<?php
class frontend_controller_language {
    protected $template, $arrayTools;

	/**
	 * @param stdClass $t
	 * frontend_controller_language constructor.
	 */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new frontend_model_template();
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