<?php
class frontend_controller_webservice{
    /**
     * @var
     */
    protected $template, $header, $data, $modelNews, $modelCore, $dateFormat, $xml;
    protected $DBPages, $DBNews, $DBCatalog, $DBHome;
    protected $modelPages,$upload,$imagesComponent, $routingUrl, $buildCollection;
    public $collection, $retrieve, $id, $filter ,$sort, $url;
    /**
     * frontend_controller_pages constructor.
     */
    public function __construct(){
        $formClean = new form_inputEscape();
        $this->template = new frontend_model_template();
        $this->header = new component_httpUtils_header($this->template);
        $this->modelPages = new frontend_model_pages($this->template);
        $this->xml = new component_xml_output();
        //$this->data = new frontend_model_data($this);
        //$this->getlang = $this->template->currentLanguage();
        $this->buildCollection = new frontend_model_collection($this->template);
        $this->DBHome = new frontend_db_home();
        $this->DBPages = new frontend_db_pages();
        $this->DBNews = new frontend_db_news();
        $this->DBCatalog = new frontend_db_news();
        $this->dateFormat = new date_dateformat();
        $this->DBCatalog = new frontend_db_catalog();
        $this->url = http_url::getUrl();

        if (http_request::isGet('id')) {
            $this->id = $formClean->numeric($_GET['id']);
        }
        if (http_request::isGet('collection')) {
            $this->collection = $formClean->simpleClean($_GET['collection']);
        }
        if (http_request::isGet('retrieve')) {
            $this->retrieve = $formClean->simpleClean($_GET['retrieve']);
        }
        if(http_request::isGet('filter')){
            $this->filter = $formClean->arrayClean($_GET['filter']);
        }
        if(http_request::isGet('sort')){
            $this->sort = $formClean->simpleClean($_GET['sort']);
        }
    }

    // ############## GET
    /**
     * Global Root
     */
    private function getBuildRootData(){
        $data = array('home','pages','news','catalog');
        $this->xml->newStartElement('modules');
        foreach($data as $key) {
            $this->xml->setElement(
                array(
                    'start' => 'module',
                    'attrNS' => array(
                        array(
                            'prefix' => 'xlink',
                            'name' => 'href',
                            'uri' => $this->url . '/webservice/'.$key.'/'
                        )
                    )
                )
            );
        }
        $this->xml->newEndElement();
        $this->xml->output();
    }

    /**
     * Build Home Data (EDIT)
     */
    private function getBuildHomeData(){
        // Collection
        $collection = $this->DBHome->fetchData(
            array('context' => 'all', 'type' => 'pages')
        );
        //print_r($collection);
        $this->xml->newStartElement('pages');

        foreach($collection as $key) {
            $this->xml->newStartElement('page');
            /*$this->xml->setElement(
                array(
                    'start' => 'id',
                    'text' => $key['id_page']
                )
            );*/
            $this->xml->setElement(
                array(
                    'start' => 'id_lang',
                    'text' => $key['id_lang'],
                    'attr'=>array(
                        array(
                            'name'      =>  'default',
                            'content'   =>  $key['default_lang']
                        )
                    )
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'iso',
                    'text' => $key['iso_lang']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'name',
                    'text' => $key['title_page']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'description',
                    'cData' => $key['content_page']
                )
            );
            $this->xml->newEndElement();
        }
        $this->xml->newEndElement();
        $this->xml->output();
    }

    /**
     * Build Pages items (LIST)
     */
    private function getBuildPagesItems(){
        $collection = $this->DBPages->fetchData(
            array('context' => 'all', 'type' => 'pages','conditions'=>null)
        );
        $arr = $this->buildCollection->getBuildPages($collection);
        //print_r($arr);
        $this->xml->newStartElement('pages');

        foreach($arr as $key => $value) {
            $this->xml->newStartElement('page');
            $this->xml->setElement(
                array(
                    'start' => 'id',
                    'text' => $value['id_pages']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'id_parent',
                    'text' => $value['id_parent']
                )
            );
            if(isset($value['imgSrc'])) {
                $this->xml->newStartElement('image');
                foreach ($value['imgSrc'] as $k => $item) {
                    $this->xml->setElement(
                        array(
                            'start' => $k,
                            'attrNS' => array(
                                array(
                                    'prefix' => 'xlink',
                                    'name' => 'href',
                                    'uri' => $this->url . $item
                                )
                            )
                        )
                    );
                }
                $this->xml->newEndElement();
            }
            // Start languages loop
            $this->xml->newStartElement('languages');
            foreach($value['content'] as $item) {
                // Start Language
                $this->xml->newStartElement('language');
                $this->xml->setElement(
                    array(
                        'start' => 'id_lang',
                        'text' => $item['id_lang'],
                        'attr' => array(
                            array(
                                'name' => 'default',
                                'content' => $item['default_lang']
                            )
                        )
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'iso',
                        'text' => $item['iso_lang']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'name',
                        'text' => $item['name_pages']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'url',
                        'text' => $item['url_pages']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'public_url',
                        'text' => $item['public_url']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'resume',
                        'text' => $item['resume_pages']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'content',
                        'cData' => $item['content_pages']
                    )
                );
                // End language loop
                $this->xml->newEndElement();
            }
            $this->xml->newEndElement();
            // End languages
            $this->xml->newEndElement();
        }
        $this->xml->newEndElement();
        $this->xml->output();
    }

    /**
     * Build Pages Data (EDIT)
     */
    private function getBuildPagesData(){
        $collection = $this->DBPages->fetchData(
            array('context' => 'all', 'type' => 'ws'),
            array(':id'=>$this->id)
        );
        // Format data
        $newArr = $this->buildCollection->getBuildPages($collection);
        $this->xml->newStartElement('page');

        foreach($newArr as $key => $value) {

            $this->xml->setElement(
                array(
                    'start' => 'id',
                    'text' => $value['id_pages']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'id_parent',
                    'text' => $value['id_parent']
                )
            );
            if(isset($value['imgSrc'])) {
                $this->xml->newStartElement('image');
                foreach ($value['imgSrc'] as $k => $item) {
                    $this->xml->setElement(
                        array(
                            'start' => $k,
                            'attrNS' => array(
                                array(
                                    'prefix' => 'xlink',
                                    'name' => 'href',
                                    'uri' => $this->url . $item
                                )
                            )
                        )
                    );
                }
                $this->xml->newEndElement();
            }
            // Start languages loop
            $this->xml->newStartElement('languages');
            foreach($value['content'] as $item) {
                $this->xml->newStartElement('language');
                $this->xml->setElement(
                    array(
                        'start' => 'id_lang',
                        'text' => $item['id_lang'],
                        'attr' => array(
                            array(
                                'name' => 'default',
                                'content' => $item['default_lang']
                            )
                        )
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'iso',
                        'text' => $item['iso_lang']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'name',
                        'text' => $item['name_pages']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'url',
                        'text' => $item['url_pages']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'public_url',
                        'text' => $item['public_url']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'resume',
                        'text' => $item['resume_pages']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'content',
                        'cData' => $item['content_pages']
                    )
                );
                // Start SEO
                $this->xml->newStartElement('seo');
                $this->xml->setElement(
                    array(
                        'start' => 'title',
                        'text' => $item['seo_title_pages']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'description',
                        'text' => $item['seo_desc_pages']
                    )
                );
                //End SEO
                $this->xml->newEndElement();
                $this->xml->setElement(
                    array(
                        'start' => 'published_pages',
                        'text' => $item['published_pages']
                    )
                );
                //End Language
                $this->xml->newEndElement();
            }
            //End Languages
            $this->xml->newEndElement();
        }
        $this->xml->newEndElement();
        $this->xml->output();

    }
    /**
     * Build News items
     */
    private function getBuildNewsItems()
    {
        $collection = $this->DBNews->fetchData(
            array('context' => 'all', 'type' => 'pages', 'conditions' => null)
        );

        $arr = $this->buildCollection->getBuildNews($collection);
        //
       /* print '<pre>';
        print_r($arr);
        print '</pre>';*/

        $this->xml->newStartElement('pages');

        foreach($arr as $key => $value) {
            $this->xml->newStartElement('page');
            $this->xml->setElement(
                array(
                    'start' => 'id',
                    'text' => $value['id_news']
                )
            );
            /*$this->xml->setElement(
                array(
                    'start' => 'id_parent',
                    'text' => $value['id_parent']
                )
            );*/
            if(isset($value['imgSrc'])) {
                $this->xml->newStartElement('image');
                foreach ($value['imgSrc'] as $k => $item) {
                    $this->xml->setElement(
                        array(
                            'start' => $k,
                            'attrNS' => array(
                                array(
                                    'prefix' => 'xlink',
                                    'name' => 'href',
                                    'uri' => $this->url . $item
                                )
                            )
                        )
                    );
                }
                $this->xml->newEndElement();
            }
            // Start languages loop
            $this->xml->newStartElement('languages');
            foreach($value['content'] as $item) {
                // Start Language
                $this->xml->newStartElement('language');
                $this->xml->setElement(
                    array(
                        'start' => 'id_lang',
                        'text' => $item['id_lang'],
                        'attr' => array(
                            array(
                                'name' => 'default',
                                'content' => $item['default_lang']
                            )
                        )
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'iso',
                        'text' => $item['iso_lang']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'name',
                        'text' => $item['name_news']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'url',
                        'text' => $item['url_news']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'public_url',
                        'text' => $item['public_url']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'resume',
                        'text' => $item['resume_news']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'content',
                        'cData' => $item['content_news']
                    )
                );
                // Start tags
                $this->xml->newStartElement('tags');
                foreach($item['tags'] as $tags => $tag) {
                    $this->xml->setElement(
                        array(
                            'start' => 'tag',
                            'text' => $tag['name'],
                            'attr' => array(
                                array(
                                    'name' => 'id',
                                    'content' => $tag['id']
                                )
                            )
                        )
                    );
                }
                // END tags
                $this->xml->newEndElement();
                // End language loop
                $this->xml->newEndElement();
            }
            $this->xml->newEndElement();
            // End languages
            $this->xml->newEndElement();
        }
        $this->xml->newEndElement();
        $this->xml->output();

    }
    /**
     * Build Pages Data (EDIT)
     */
    private function getBuildNewsData(){
        $collection = $this->DBNews->fetchData(
            array('context' => 'all', 'type' => 'ws'),
            array(':id'=>$this->id)
        );
        // Format data
        $newArr = $this->buildCollection->getBuildNews($collection);

        //print_r($newArr);
        $this->xml->newStartElement('page');

        foreach($newArr as $key => $value) {

            $this->xml->setElement(
                array(
                    'start' => 'id',
                    'text' => $value['id_news']
                )
            );

            if(isset($value['imgSrc'])) {
                $this->xml->newStartElement('image');
                foreach ($value['imgSrc'] as $k => $item) {
                    $this->xml->setElement(
                        array(
                            'start' => $k,
                            'attrNS' => array(
                                array(
                                    'prefix' => 'xlink',
                                    'name' => 'href',
                                    'uri' => $this->url . $item
                                )
                            )
                        )
                    );
                }
                $this->xml->newEndElement();
            }
            // Start languages loop
            $this->xml->newStartElement('languages');
            foreach($value['content'] as $item) {
                $this->xml->newStartElement('language');
                $this->xml->setElement(
                    array(
                        'start' => 'id_lang',
                        'text' => $item['id_lang'],
                        'attr' => array(
                            array(
                                'name' => 'default',
                                'content' => $item['default_lang']
                            )
                        )
                    )
                );

                $this->xml->setElement(
                    array(
                        'start' => 'iso',
                        'text' => $item['iso_lang']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'name',
                        'text' => $item['name_news']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'url',
                        'text' => $item['url_news']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'public_url',
                        'text' => $item['public_url']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'resume',
                        'text' => $item['resume_news']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'content',
                        'cData' => $item['content_news']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'date_publish',
                        'text' => $item['date_publish']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'published_pages',
                        'text' => $item['published_news']
                    )
                );
                // Start tags
                $this->xml->newStartElement('tags');
                foreach($item['tags'] as $tags => $tag) {
                    $this->xml->setElement(
                        array(
                            'start' => 'tag',
                            'text' => $tag['name'],
                            'attr' => array(
                                array(
                                    'name' => 'id',
                                    'content' => $tag['id']
                                )
                            )
                        )
                    );
                }
                // END tags
                $this->xml->newEndElement();
                //End Language
                $this->xml->newEndElement();
            }
            //End Loop
            $this->xml->newEndElement();
        }
        $this->xml->newEndElement();
        $this->xml->output();

    }
    /**
     * Build News items
     */
    private function getBuildCategoryItems()
    {
        $collection = $this->DBCatalog->fetchData(
            array('context' => 'all', 'type' => 'category', 'conditions' => null)
        );

        $arr = $this->buildCollection->getBuildCategory($collection);
        //
        /*print '<pre>';
         print_r($arr);
         print '</pre>';*/

        $this->xml->newStartElement('pages');

        foreach($arr as $key => $value) {
            $this->xml->newStartElement('page');
            $this->xml->setElement(
                array(
                    'start' => 'id',
                    'text' => $value['id_cat']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'id_parent',
                    'text' => $value['id_parent']
                )
            );
            if(isset($value['imgSrc'])) {
                $this->xml->newStartElement('image');
                foreach ($value['imgSrc'] as $k => $item) {
                    $this->xml->setElement(
                        array(
                            'start' => $k,
                            'attrNS' => array(
                                array(
                                    'prefix' => 'xlink',
                                    'name' => 'href',
                                    'uri' => $this->url . $item
                                )
                            )
                        )
                    );
                }
                $this->xml->newEndElement();
            }
            // Start languages loop
            $this->xml->newStartElement('languages');
            foreach($value['content'] as $item) {
                // Start Language
                $this->xml->newStartElement('language');
                $this->xml->setElement(
                    array(
                        'start' => 'id_lang',
                        'text' => $item['id_lang'],
                        'attr' => array(
                            array(
                                'name' => 'default',
                                'content' => $item['default_lang']
                            )
                        )
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'iso',
                        'text' => $item['iso_lang']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'name',
                        'text' => $item['name_cat']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'url',
                        'text' => $item['url_cat']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'public_url',
                        'text' => $item['public_url']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'resume',
                        'text' => $item['resume_cat']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'content',
                        'cData' => $item['content_cat']
                    )
                );
                // End language loop
                $this->xml->newEndElement();
            }
            $this->xml->newEndElement();
            // End languages
            $this->xml->newEndElement();
        }
        $this->xml->newEndElement();
        $this->xml->output();

    }
    /**
     * Build News items
     */
    private function getBuildCategoryData()
    {
        $collection = $this->DBCatalog->fetchData(
            array('context' => 'all', 'type' => 'category', 'conditions' => 'WHERE p.id_cat = :id'),
            array('id'=>$this->id)
        );

        $arr = $this->buildCollection->getBuildCategory($collection);
        //
        /*print '<pre>';
         print_r($arr);
         print '</pre>';*/

        $this->xml->newStartElement('pages');

        foreach($arr as $key => $value) {
            $this->xml->newStartElement('page');
            $this->xml->setElement(
                array(
                    'start' => 'id',
                    'text' => $value['id_cat']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'id_parent',
                    'text' => $value['id_parent']
                )
            );
            if(isset($value['imgSrc'])) {
                $this->xml->newStartElement('image');
                foreach ($value['imgSrc'] as $k => $item) {
                    $this->xml->setElement(
                        array(
                            'start' => $k,
                            'attrNS' => array(
                                array(
                                    'prefix' => 'xlink',
                                    'name' => 'href',
                                    'uri' => $this->url . $item
                                )
                            )
                        )
                    );
                }
                $this->xml->newEndElement();
            }
            // Start languages loop
            $this->xml->newStartElement('languages');
            foreach($value['content'] as $item) {
                // Start Language
                $this->xml->newStartElement('language');
                $this->xml->setElement(
                    array(
                        'start' => 'id_lang',
                        'text' => $item['id_lang'],
                        'attr' => array(
                            array(
                                'name' => 'default',
                                'content' => $item['default_lang']
                            )
                        )
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'iso',
                        'text' => $item['iso_lang']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'name',
                        'text' => $item['name_cat']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'url',
                        'text' => $item['url_cat']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'public_url',
                        'text' => $item['public_url']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'resume',
                        'text' => $item['resume_cat']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'content',
                        'cData' => $item['content_cat']
                    )
                );
                // End language loop
                $this->xml->newEndElement();
            }
            $this->xml->newEndElement();
            // End languages
            $this->xml->newEndElement();
        }
        $this->xml->newEndElement();
        $this->xml->output();

    }
    public function getBuildProductItems(){
        $collection = $this->DBCatalog->fetchData(
            array('context' => 'all', 'type' => 'product_ws','conditions'=>null)
        );

        $arr = $this->buildCollection->getBuildProductItems($collection);
        //WHERE p.id_product = :id
        print '<pre>';
         print_r($arr);
         print '</pre>';
    }

    /**
     *
     */
    public function getBuildProductData(){
        $collection = $this->DBCatalog->fetchData(
            array('context' => 'all', 'type' => 'product_ws','conditions'=>'WHERE p.id_product = :id'),
            array('id'=>$this->id)
        );

        $arr = $this->buildCollection->getBuildProduct($collection);
        //WHERE p.id_product = :id
        print '<pre>';
        print_r($arr);
        print '</pre>';
    }
    /**
     *
     */
    public function run(){
        if(isset($this->collection)){
            switch($this->collection){
                case 'home':
                    $this->xml->getXmlHeader();
                    $this->getBuildHomeData();

                    break;
                case 'pages':
                    if(isset($this->id)){
                        $this->xml->getXmlHeader();
                        $this->getBuildPagesData();

                    }else{
                        $this->xml->getXmlHeader();
                        $this->getBuildPagesItems();
                    }
                    break;
                case 'news':
                    if(isset($this->id)){

                        $this->xml->getXmlHeader();
                        $this->getBuildNewsData();

                    }else{
                        $this->xml->getXmlHeader();
                        $this->getBuildNewsItems();

                    }
                    break;
                case 'catalog':
                    if(isset($this->retrieve)){

                        if($this->retrieve == 'category'){
                            if(isset($this->id)){

                                /*print 'test collection : ' . $this->collection.'<br />';
                                print 'retrieve : ' . $this->retrieve.'<br />';
                                print 'id : ' . $this->id;
                                print_r($this->filter);*/
                                $this->xml->getXmlHeader();
                                $this->getBuildCategoryData();

                            }else{

                                /*print 'test collection : ' . $this->collection.'<br />';
                                print 'retrieve : ' . $this->retrieve;*/
                                $this->xml->getXmlHeader();
                                $this->getBuildCategoryItems();

                            }
                        }elseif($this->retrieve == 'product'){
                            if(isset($this->id)){

                                /*print 'test collection : ' . $this->collection.'<br />';
                                print 'retrieve : ' . $this->retrieve.'<br />';
                                print 'id : ' . $this->id;
                                print_r($this->filter);*/
                                $this->getBuildProductData();

                            }else{

                                /*print 'test collection : ' . $this->collection.'<br />';
                                print 'retrieve : ' . $this->retrieve;*/
                                $this->getBuildProductItems();

                            }
                        }else{
                            return;
                        }
                    }
            }
        }else{
            $this->xml->getXmlHeader();
            $this->getBuildRootData();
        }
    }
}
?>