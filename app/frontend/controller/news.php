<?php
class frontend_controller_news extends frontend_db_news
{
    /**
     * @var
     */
    protected $template, $header, $data, $modelNews, $modelCore;
    public $getlang, $id, $id_parent;
    /**
     * frontend_controller_pages constructor.
     */
    public function __construct(){
        $formClean = new form_inputEscape();
        $this->template = new frontend_model_template();
        $this->header = new component_httpUtils_header($this->template);
        $this->data = new frontend_model_data($this);
        $this->getlang = $this->template->currentLanguage();
        $this->modelNews = new frontend_model_catalog($this->template);
        if (http_request::isGet('id')) {
            $this->id = $formClean->numeric($_GET['id']);
        }
        /*if (http_request::isGet('id_parent')) {
            $this->id_parent = $formClean->numeric($_GET['id_parent']);
        }*/
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
}