<?php
class frontend_controller_news extends frontend_db_news
{
    /**
     * @var
     */
    protected $template, $header, $data, $modelNews, $modelCore, $dateFormat;
    public $getlang, $id, $id_parent,$date,$year,$month;
    /**
     * frontend_controller_pages constructor.
     */
    public function __construct(){
        $formClean = new form_inputEscape();
        $this->template = new frontend_model_template();
        $this->header = new component_httpUtils_header($this->template);
        $this->data = new frontend_model_data($this);
        $this->getlang = $this->template->currentLanguage();
        $this->modelNews = new frontend_model_news($this->template);
        $this->dateFormat = new date_dateformat();
        if (http_request::isGet('id')) {
            $this->id = $formClean->numeric($_GET['id']);
        }
        if (http_request::isGet('date')) {
            $this->date = $formClean->simpleClean($_GET['date']);
        }
        if (http_request::isGet('year')) {
            $this->year = $formClean->simpleClean($_GET['year']);
        }
        if (http_request::isGet('month')) {
            $this->month = $formClean->simpleClean($_GET['month']);
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

    /**
     * set Data from database
     * @access private
     */
    private function getBuildItems()
    {
        $collection = $this->getItems('page',array(':id'=>$this->id,':iso'=>$this->getlang),'one',false);
        $tagsCollection = $this->getItems('tagsRel',array(':id'=>$this->id,':iso'=>$this->getlang),'all',false);
        if($tagsCollection != null){
            $collection['tags'] = $tagsCollection;
        }
        return $this->modelNews->setItemData($collection,null);
    }
    
    /**
     * @return array
     */
    private function getBuildLangItems(){
        $collection = $this->getItems('langs',array(':id'=>$this->id),'all',false);
        return $this->modelNews->setHrefLangData($collection);
    }

    private function getBuildDate(){

    }

    /**
     * Assign page's data to smarty
     * @param $type
     */
    private function getData($type)
    {
        switch($type){
            case 'date':
                break;
            case 'id':
                $data = $this->getBuildItems();

                $hreflang = $this->getBuildLangItems();
                $this->template->assign('news',$data,true);
                $this->template->assign('hreflang',$hreflang,true);
                break;
        }
    }

    /**
     * @access public
     * run app
     */
    public function run(){
        if(isset($this->id) && isset($this->date)){
            $this->getData('id');
            $this->template->display('news/news.tpl');
        }elseif(isset($this->year) OR isset($this->month) OR isset($this->date)){
            $this->getData('date');
            $this->template->display('news/date.tpl');
        }else{
            $this->template->display('news/index.tpl');
        }
    }
}