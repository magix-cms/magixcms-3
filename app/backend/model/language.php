<?php
class backend_model_language{

    protected $template,$collectionLanguage;

    public function __construct($template)
    {
        $this->collectionLanguage = new component_collections_language();
        $this->template = $template;
    }

    /**
     * @return array
     */
    public function setLanguage(){
        $data = $this->collectionLanguage->fetchData(array('context'=>'all','type'=>'langs'));
        foreach ($data as $key) {
            $id_lang[]  = $key['id_lang'];
            $iso_lang[] = $key['iso_lang'];
        }
        return array_combine($id_lang, $iso_lang);
    }

    /**
     *
     */
    public function getLanguage(){
        $newsData = $this->setLanguage();
        $this->template->assign('langs',$newsData);
    }
}
?>