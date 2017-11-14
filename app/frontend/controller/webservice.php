<?php
class frontend_controller_webservice extends frontend_db_webservice{
    /**
     * @var
     */
    protected $template,$UtilsHeader, $header, $data, $modelNews, $modelCore, $dateFormat, $xml, $message;
    protected $DBPages, $DBNews, $DBCatalog, $DBHome;
    protected $modelPages,$upload,$imagesComponent, $routingUrl, $buildCollection,$ws;
    public $collection, $retrieve, $id, $filter ,$sort, $url;
    /**
     * frontend_controller_pages constructor.
     */
    public function __construct(){
        $formClean = new form_inputEscape();
        $this->template = new frontend_model_template();
        $this->message = new component_core_message($this->template);
        $this->UtilsHeader = new component_httpUtils_header($this->template);
        $this->modelPages = new frontend_model_pages($this->template);
        $this->xml = new component_xml_output();
        $this->header = new http_header();
        $this->data = new frontend_model_data($this);
        //$this->getlang = $this->template->currentLanguage();
        $this->buildCollection = new frontend_model_collection($this->template);
        $this->DBHome = new frontend_db_home();
        $this->DBPages = new frontend_db_pages();
        $this->DBNews = new frontend_db_news();
        $this->DBCatalog = new frontend_db_news();
        $this->dateFormat = new date_dateformat();
        $this->DBCatalog = new frontend_db_catalog();
        $this->url = http_url::getUrl();
        $this->ws = new frontend_model_webservice();

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
     * @return string
     */
    public function setWsAuthKey(){
        $data = $this->getItems('authentification',null,'one',false);
        if($data != null){
            if($data['status_ws'] != '0'){
                return $data['key_ws'];
            }else{
                return false;
            }
        }else{
            return false;
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
            // Start SEO
            $this->xml->newStartElement('seo');
            $this->xml->setElement(
                array(
                    'start' => 'title',
                    'text' => $key['seo_title_page']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'description',
                    'text' => $key['seo_desc_page']
                )
            );
            //End SEO
            $this->xml->newEndElement();
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
                        'start' => 'published',
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
        /*print '<pre>';
         print_r($arr);
         print '</pre>';*/
        $this->xml->newStartElement('pages');

        foreach($arr as $key => $value) {
            $this->xml->newStartElement('page');
            $this->xml->setElement(
                array(
                    'start' => 'id',
                    'text' => $value['id_product']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'price',
                    'text' => $value['price_p']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'reference',
                    'text' => $value['reference_p']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'width',
                    'text' => $value['width_p']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'height',
                    'text' => $value['height_p']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'depth',
                    'text' => $value['depth_p']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'weight',
                    'text' => $value['weight_p']
                )
            );

            if(isset($value['images'])) {
                $this->xml->newStartElement('images');
                foreach ($value['images'] as $k) {
                    $this->xml->newStartElement('image');
                    $this->xml->setElement(
                        array(
                            'start' => 'name',
                            'text' => $k['name_img']
                        )
                    );
                    //start src
                    $this->xml->newStartElement('src');
                    foreach ($k['imgSrc'] as $images => $imgSrc) {
                        $this->xml->setElement(
                            array(
                                'start' => $images,
                                'attrNS' => array(
                                    array(
                                        'prefix' => 'xlink',
                                        'name' => 'href',
                                        'uri' => $this->url . $imgSrc
                                    )
                                )
                            )
                        );
                    }
                    //End src
                    $this->xml->newEndElement();
                    if($k['content']!= null) {
                        // Start languages loop
                        $this->xml->newStartElement('languages');
                        foreach ($k['content'] as $imgData) {
                            // Start Language
                            $this->xml->newStartElement('language');
                            $this->xml->setElement(
                                array(
                                    'start' => 'id_lang',
                                    'text' => $imgData['id_lang']
                                )
                            );
                            $this->xml->setElement(
                                array(
                                    'start' => 'iso',
                                    'text' => $imgData['iso_lang']
                                )
                            );
                            $this->xml->setElement(
                                array(
                                    'start' => 'alt',
                                    'text' => $imgData['alt_img']
                                )
                            );
                            $this->xml->setElement(
                                array(
                                    'start' => 'title',
                                    'text' => $imgData['title_img']
                                )
                            );
                            //End language img
                            $this->xml->newEndElement();
                        }
                        //End languages img
                        $this->xml->newEndElement();
                    }
                    // End loop image
                    $this->xml->newEndElement();
                }
                // End images
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
                        'text' => $item['name_p']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'url',
                        'text' => $item['url_p']
                    )
                );

                $this->xml->setElement(
                    array(
                        'start' => 'resume',
                        'text' => $item['resume_p']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'content',
                        'cData' => $item['content_p']
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
     *
     */
    public function getBuildProductData(){
        $collection = $this->DBCatalog->fetchData(
            array('context' => 'all', 'type' => 'product_ws','conditions'=>'WHERE p.id_product = :id'),
            array('id'=>$this->id)
        );

        $arr = $this->buildCollection->getBuildProduct($collection);
        //WHERE p.id_product = :id
        /*print '<pre>';
        print_r($arr);
        print '</pre>';*/
        $this->xml->newStartElement('pages');

        foreach($arr as $key => $value) {
            $this->xml->newStartElement('page');
            $this->xml->setElement(
                array(
                    'start' => 'id',
                    'text' => $value['id_product']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'price',
                    'text' => $value['price_p']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'reference',
                    'text' => $value['reference_p']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'width',
                    'text' => $value['width_p']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'height',
                    'text' => $value['height_p']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'depth',
                    'text' => $value['depth_p']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'weight',
                    'text' => $value['weight_p']
                )
            );

            if(isset($value['images'])) {
                $this->xml->newStartElement('images');
                foreach ($value['images'] as $k) {
                    $this->xml->newStartElement('image');
                    $this->xml->setElement(
                        array(
                            'start' => 'name',
                            'text' => $k['name_img']
                        )
                    );
                    $this->xml->setElement(
                        array(
                            'start' => 'default',
                            'text' => $k['default_img']
                        )
                    );
                    //start src
                    $this->xml->newStartElement('src');
                    foreach ($k['imgSrc'] as $images => $imgSrc) {
                        $this->xml->setElement(
                            array(
                                'start' => $images,
                                'attrNS' => array(
                                    array(
                                        'prefix' => 'xlink',
                                        'name' => 'href',
                                        'uri' => $this->url . $imgSrc
                                    )
                                )
                            )
                        );
                    }
                    //End src
                    $this->xml->newEndElement();
                    if($k['content']!= null) {
                        // Start languages loop
                        $this->xml->newStartElement('languages');
                        foreach ($k['content'] as $imgData) {
                            // Start Language
                            $this->xml->newStartElement('language');
                            $this->xml->setElement(
                                array(
                                    'start' => 'id_lang',
                                    'text' => $imgData['id_lang']
                                )
                            );
                            $this->xml->setElement(
                                array(
                                    'start' => 'iso',
                                    'text' => $imgData['iso_lang']
                                )
                            );
                            $this->xml->setElement(
                                array(
                                    'start' => 'alt',
                                    'text' => $imgData['alt_img']
                                )
                            );
                            $this->xml->setElement(
                                array(
                                    'start' => 'title',
                                    'text' => $imgData['title_img']
                                )
                            );
                            //End language img
                            $this->xml->newEndElement();
                        }
                        //End languages img
                        $this->xml->newEndElement();
                    }
                    // End loop image
                    $this->xml->newEndElement();
                }
                // End images
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
                        'text' => $item['name_p']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'url',
                        'text' => $item['url_p']
                    )
                );

                $this->xml->setElement(
                    array(
                        'start' => 'resume',
                        'text' => $item['resume_p']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'content',
                        'cData' => $item['content_p']
                    )
                );
                // End language loop
                $this->xml->newEndElement();
            }
            // End languages
            $this->xml->newEndElement();

            // Start Associated
            $this->xml->newStartElement('associated');
            // Loop associated
            foreach($value['associated'] as $assoKey => $item) {
                $this->xml->newStartElement('product');
                $this->xml->setElement(
                    array(
                        'start' => 'id',
                        'text' => $item['id_product']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'price',
                        'text' => $item['price_p']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'reference',
                        'text' => $item['reference_p']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'width',
                        'text' => $item['width_p']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'height',
                        'text' => $item['height_p']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'depth',
                        'text' => $item['depth_p']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'weight',
                        'text' => $item['weight_p']
                    )
                );
                // Loop associated image product
                if(isset($item['images'])) {
                    $this->xml->newStartElement('images');

                    //foreach ($item['images'] as $k) {
                        $this->xml->newStartElement('image');
                        $this->xml->setElement(
                            array(
                                'start' => 'name',
                                'text' => $item['images']['name_img']
                            )
                        );
                        //start src
                        $this->xml->newStartElement('src');
                        foreach ($item['images']['imgSrc'] as $images => $imgSrc) {
                            $this->xml->setElement(
                                array(
                                    'start' => $images,
                                    'attrNS' => array(
                                        array(
                                            'prefix' => 'xlink',
                                            'name' => 'href',
                                            'uri' => $this->url . $imgSrc
                                        )
                                    )
                                )
                            );
                        }
                        //End src
                        $this->xml->newEndElement();
                        if($item['images']['content']!= null) {
                            // Start languages loop
                            $this->xml->newStartElement('languages');
                            foreach ($item['images']['content'] as $imgData) {
                                // Start Language
                                $this->xml->newStartElement('language');
                                $this->xml->setElement(
                                    array(
                                        'start' => 'id_lang',
                                        'text' => $imgData['id_lang']
                                    )
                                );
                                $this->xml->setElement(
                                    array(
                                        'start' => 'iso',
                                        'text' => $imgData['iso_lang']
                                    )
                                );
                                $this->xml->setElement(
                                    array(
                                        'start' => 'alt',
                                        'text' => $imgData['alt_img']
                                    )
                                );
                                $this->xml->setElement(
                                    array(
                                        'start' => 'title',
                                        'text' => $imgData['title_img']
                                    )
                                );
                                //End language img
                                $this->xml->newEndElement();
                            }
                            //End languages img
                            $this->xml->newEndElement();
                        }
                        // End loop image
                        $this->xml->newEndElement();
                    //}
                    // End images
                    $this->xml->newEndElement();
                }
            }
            // End Associated
            $this->xml->newEndElement();
            // End page
            $this->xml->newEndElement();
        }
        $this->xml->newEndElement();
        $this->xml->output();
    }
    //########## REQUEST POST, PUT, DELETE

    /**
     * @param bool $debug
     * @return mixed|SimpleXMLElement
     */
    public function parse($debug = false){
        return $this->ws->setParseData($debug);
    }

    /**
     * Mise a jour des données
     * @param $operation
     * @param $arrData
     */
    private function getBuildSave($operation,$arrData)
    {
        switch($operation['type']){
            case 'home':
                $fetchRootData = $this->DBHome->fetchData(array('context'=>'one','type'=>'root'));
                if($fetchRootData != null){
                    $id_page = $fetchRootData['id_page'];
                }else{
                    parent::insert(array('type'=>'newHome'));
                    $newData = $this->DBHome->fetchData(array('context'=>'one','type'=>'root'));
                    $id_page = $newData['id_page'];
                }

                if($id_page) {
                    //print_r($arrData['language']);
                    foreach ($arrData['language'] as $lang => $content) {
                        $content['published'] = (!isset($content['published']) ? 0 : 1);
                        $data = array(
                            'title_page'        => $content['name'],
                            'content_page'      => $content['content'],
                            'seo_title_page'    => !is_array($content['seo']['title']) ? $content['seo']['title'] : '',
                            'seo_desc_page'     => !is_array($content['seo']['description']) ? $content['seo']['description'] : '',
                            'published'         => $content['published'],
                            'id_page'           => $id_page,
                            'id_lang'           => $content['id_lang']
                        );

                        if ($this->DBHome->fetchData(array('context' => 'one', 'type' => 'content'), array('id_page' => $id_page, 'id_lang' => $content['id_lang'])) != null) {
                            $this->DBHome->update(array('type' => 'content'), $data);
                        } else {
                            $this->DBHome->insert(array('type' => 'newContent'), $data);
                        }
                    }
                    //$this->header->set_json_headers();
                    //$this->message->json_post_response(true, 'update', $id_page);
                }
                break;
            case 'pages':
                if (isset($this->id)) {
                    // Regarder pour voir si l'édition et ajout fonctionne correctement, sinon ajouté paramètre id (get)
                    $fetchRootData = $this->DBPages->fetchData(array('context'=>'one','type'=>'root'));
                    if($fetchRootData != null){
                        $id_page = $fetchRootData['id_pages'];
                    }else{
                        $this->DBPages->insert(array('type'=>'page'),array(':id_parent' => empty($arrData['parent']) ? NULL : $arrData['parent']));
                        $newData = $this->DBPages->fetchData(array('context'=>'one','type'=>'root'));
                        $id_page = $newData['id_pages'];
                    }
                }else{
                    $this->DBPages->insert(array('type'=>'page'),array(':id_parent' => empty($arrData['parent']) ? NULL : $arrData['parent']));
                    $newData = $this->DBPages->fetchData(array('context'=>'one','type'=>'root'));
                    $id_page = $newData['id_pages'];
                }


                if($id_page) {
                    //print_r($arrData);
                    foreach ($arrData['language'] as $lang => $content) {
                        //print_r($content);
                        $content['published'] = (!isset($content['published']) ? 0 : 1);
                        if (empty($content['url'])) {
                            $content['url'] = http_url::clean($content['name'],
                                array(
                                    'dot' => false,
                                    'ampersand' => 'strict',
                                    'cspec' => '', 'rspec' => ''
                                )
                            );
                        }
                        $data = array(
                            'name_pages'        => !is_array($content['name']) ? $content['name'] : '',
                            'url_pages'         => !is_array($content['url']) ? $content['url'] : '',
                            'resume_pages'      => !is_array($content['resume']) ? $content['resume'] : '',
                            'content_pages'     => !is_array($content['content']) ? $content['content'] : '',
                            'seo_title_pages'   => !is_array($content['seo']['title']) ? $content['seo']['title'] : '',
                            'seo_desc_pages'    => !is_array($content['seo']['description']) ? $content['seo']['description'] : '',
                            'published_pages'   => $content['published'],
                            'id_pages'          => $id_page,
                            'id_lang'           => $content['id_lang']
                        );

                        if ($this->DBPages->fetchData(array('context' => 'one', 'type' => 'content'), array('id_pages' => $id_page, 'id_lang' => $content['id_lang'])) != null) {

                            $this->DBPages->update(array('type' => 'content'), $data);

                        } else {

                            $this->DBPages->insert(array('type' => 'content'), $data);
                        }
                    }
                    //$this->header->set_json_headers();
                    //$this->message->json_post_response(true, 'update', $id_page);
                }
                break;
        }
    }
    /**
     * Set parse data from XML OR JSON
     * @param $operations
     * @return array
     * @throws Exception
     */
    private function getBuildParse($operations){
        try {
            $getContentType = $this->ws->getContentType();
            //$this->ws->setHeaderType();
            switch ($operations['type']) {
                case 'home':
                    /*if ($operations['scrud'] === 'create') {

                    }*/
                    if($this->ws->setMethod() === 'PUT'){
                        if($getContentType === 'xml') {
                            $arrData = json_decode(json_encode($this->parse()), true);
                            //
                            //$arrData = $this->xml2array($this->parse());
                            /*print $arrData['parent'] . '<br />';
                            foreach ($arrData['language'] as $key => $value) {
                                print $value['id_lang'] . '<br />';
                                print $value['name'];
                            }*/
                            $this->getBuildSave($operations,$arrData);
                        }
                    }
                    elseif($this->ws->setMethod() === 'POST'){
                        if($getContentType === 'xml'){
                            //print 'je suis xml';
                            //$this->parse();
                            //print $this->parse()->parent;
                            /*oreach($this->parse()->languages->language as $key){
                                $dta[]= $key->id_lang.'<br />';
                            }*/
                            /*foreach($this->parse()->languages->language as $key => $arr) {
                                foreach($arr as $k => $v) {
                                    $array[$key][$k] = $v;
                                }
                            }

                            print_r($array);*/
                            $arrData = json_decode(json_encode($this->parse()), true);
                            //
                            //$arrData = $this->xml2array($this->parse());
                            //print_r($arrData);
                            /*print $arrData['parent'].'<br />';
                            foreach($arrData['language'] as $key => $value){
                                print $value['id_lang'].'<br />';
                                print $value['name'];
                            }*/
                            $this->getBuildSave($operations,$arrData);
                            //$this->header->set_json_headers();
                            //print json_encode($arrData);
                            //print '{"statut":'.json_encode(true).',"notify":'.json_encode("add").'}';


                        }elseif($getContentType === 'json'){
                            /*print json_encode(
                                array('parent'=>2,
                                    'language'=>
                                        array(
                                            0   =>  array('id_lang'=>6),
                                            1  =>  array('id_lang'=>2)
                                        )
                                    )
                            );*/
                            //print_r($this->xml2array($this->parse()));
                            //print 'POST';
                            //{"parent":2,"languages":{"language":[{"id_lang":6},{"id_lang":2}]}}
                            //{"parent":2,"language":[{"id_lang":6},{"id_lang":2}]}
                            $arrData = json_decode(json_encode($this->parse()), true);
                            /*print_r($arrData);
                            foreach($arrData['language'] as $key => $value){
                                print $value['id_lang'];
                            }*/
                            $this->getBuildSave($operations,$arrData);
                        }
                    }
                    elseif($this->ws->setMethod() === 'DELETE'){
                        print $getContentType;
                        print 'DELETE';
                        /*print json_encode(
                            array('parent'=>2,
                                'languages'=>
                                    array(
                                        'language'=>
                                            array(
                                                0   =>  array('id_lang'=>6),
                                                1  =>  array('id_lang'=>2)
                                            )
                                    )
                            )
                        );*/

                    }
                    elseif($this->ws->setMethod() === 'GET'){
                        //print $getContentType;
                        $this->xml->getXmlHeader();
                        $this->getBuildHomeData();

                    }
                    break;
                case 'pages':
                    /*if ($operations['scrud'] === 'create') {

                    }*/
                    if($this->ws->setMethod() === 'PUT'){
                        if($getContentType === 'xml') {

                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildSave($operations,$arrData);
                        }
                    }
                    elseif($this->ws->setMethod() === 'POST'){
                        if($getContentType === 'xml'){

                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildSave($operations,$arrData);


                        }elseif($getContentType === 'json'){

                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildSave($operations,$arrData);
                        }
                    }
                    elseif($this->ws->setMethod() === 'DELETE'){
                        //print_r($this->parse());

                        if($getContentType === 'xml') {
                            $arrData = json_decode(json_encode($this->parse()), true);
                            //print_r($arrData);
                            //print_r(implode(",",$arrData['id']));
                            if($arrData['id'] != null){
                                $this->DBPages->delete(
                                    array(
                                        'type'      =>    'delPages'
                                    ),
                                    array('id'=>implode(",",$arrData['id'])
                                    )
                                );
                            }

                            /*print json_encode(
                                array('id'=>
                                    array(
                                        0   =>  53,
                                        1  =>  54
                                    )
                                )
                            );*/
                        }elseif($getContentType === 'json'){
                            //{"id":[53,54]}
                            $arrData = json_decode(json_encode($this->parse()), true);
                            if($arrData['id'] != null){
                                $del = implode(",",$arrData['id']);
                                $this->DBPages->delete(
                                    array(
                                        'type'      =>    'delPages'
                                    ),
                                    array('id'=>$del)
                                );
                            }
                        }
                        $this->header->set_json_headers();
                        $this->message->json_post_response(true,'delete',$del);

                    }
                    elseif($this->ws->setMethod() === 'GET'){
                        if (isset($this->id)) {
                            $this->xml->getXmlHeader();
                            $this->getBuildPagesData();

                        } else {
                            $this->xml->getXmlHeader();
                            $this->getBuildPagesItems();
                        }

                    }
                    break;

            }
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
        }
    }
    /**
     *
     */
    public function run(){
        if ($this->ws->authorization($this->setWsAuthKey())) {
            if (isset($this->collection)) {
                switch ($this->collection) {
                    case 'home':
                        $this->getBuildParse(array('type' => 'home'));
                        break;
                    case 'pages':
                        /*if (isset($this->id)) {
                            $this->xml->getXmlHeader();
                            $this->getBuildPagesData();

                        } else {
                            $this->xml->getXmlHeader();
                            $this->getBuildPagesItems();
                        }*/
                        $this->getBuildParse(array('type' => 'pages'));
                        break;
                    case 'news':
                        if (isset($this->id)) {

                            $this->xml->getXmlHeader();
                            $this->getBuildNewsData();

                        } else {
                            $this->xml->getXmlHeader();
                            $this->getBuildNewsItems();

                        }
                        break;
                    case 'catalog':
                        if (isset($this->retrieve)) {

                            if ($this->retrieve == 'category') {
                                if (isset($this->id)) {

                                    /*print 'test collection : ' . $this->collection.'<br />';
                                    print 'retrieve : ' . $this->retrieve.'<br />';
                                    print 'id : ' . $this->id;
                                    print_r($this->filter);*/
                                    $this->xml->getXmlHeader();
                                    $this->getBuildCategoryData();

                                } else {

                                    /*print 'test collection : ' . $this->collection.'<br />';
                                    print 'retrieve : ' . $this->retrieve;*/
                                    $this->xml->getXmlHeader();
                                    $this->getBuildCategoryItems();

                                }
                            } elseif ($this->retrieve == 'product') {
                                if (isset($this->id)) {

                                    /*print 'test collection : ' . $this->collection.'<br />';
                                    print 'retrieve : ' . $this->retrieve.'<br />';
                                    print 'id : ' . $this->id;
                                    print_r($this->filter);*/
                                    $this->xml->getXmlHeader();
                                    $this->getBuildProductData();

                                } else {

                                    /*print 'test collection : ' . $this->collection.'<br />';
                                    print 'retrieve : ' . $this->retrieve;*/
                                    $this->xml->getXmlHeader();
                                    $this->getBuildProductItems();

                                }
                            } else {
                                return;
                            }
                        }
                }
            } else {
                $this->xml->getXmlHeader();
                $this->getBuildRootData();
            }
        }
    }
}
?>