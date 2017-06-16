<?php
class backend_controller_home extends backend_db_home{

    public $edit, $action, $tabs;
    protected $message, $template, $header, $data, $collectionLanguage;

    public function __construct()
    {
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->collectionLanguage = new component_collections_language();

        // --- GET
        if (http_request::isGet('edit')) {
            $this->edit = $formClean->numeric($_GET['edit']);
        }
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }
        if (http_request::isGet('tabs')) {
            $this->tabs = $formClean->simpleClean($_GET['tabs']);
        }
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $context
     * @param string $type
     * @param string|int|null $id
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null) {
        return $this->data->getItems($type, $id, $context);
    }

    private function getLanguage(){
        $data = $this->collectionLanguage->fetchData(array('context'=>'all','type'=>'langs'));
        foreach ($data as $key) {
            $id_lang[] = $key['id_lang'];
            $iso_lang[] = $key['iso_lang'];
        }
        $newsData =  array_combine($id_lang, $iso_lang);
        $this->template->assign('langs',$newsData);
    }
    private function setItemData(){
        return parent::fetchData(array('context'=>'last','type'=>'root'));
    }
    private function setItemsData(){
        $data = parent::fetchData(array('context'=>'all','type'=>'pages'));
        $arr = array();
        foreach ($data as $page) {
            if (!array_key_exists($page['id_page'], $arr)) {
                $arr[$page['id_page']] = array();
                $arr[$page['id_page']]['id_page'] = $page['id_page'];
                $arr[$page['idpage']]['date_register'] = $page['date_register'];
            }
            $arr[$page['id_page']]['content'][$page['id_lang']] = array(
                'id_lang' => $page['id_lang'],
                'title_page' => $page['title_page'],
                'content_page' => $page['content_page'],
                'seo_title_page' => $page['seo_title_page'],
                'seo_desc_page' => $page['seo_desc_page'],
                'published' => $page['published']
            );
        }
        return $arr;
    }
    public function run(){
        $this->getLanguage();
        $last = $this->setItemData();
        $pages = $this->setItemsData();
        $this->template->assign('home',$last);
        $this->template->assign('page', $pages[$last['id_page']]);
        $this->template->display('home/edit.tpl');
    }

}
?>