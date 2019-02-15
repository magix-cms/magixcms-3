<?php
class frontend_controller_webservice extends frontend_db_webservice{
    /**
     * @var
     */
    protected $template,$UtilsHeader, $header, $data, $modelNews, $modelCore, $dateFormat, $xml, $message;
    protected $DBPages, $DBNews, $DBCatalog, $DBHome,$DBCategory,$DBProduct;
    protected $modelPages,$upload,$imagesComponent, $routingUrl, $buildCollection,$ws,$collectionLanguage,$collectionDomain;
    public $collection, $retrieve, $id, $filter ,$sort, $url, $img, $img_multiple, $imgData;

    /**
     * frontend_controller_webservice constructor.
     * @param null $t
     * @throws Exception
     */
    public function __construct($t = null){
		$this->template = $t ? $t : new frontend_model_template();
		$formClean = new form_inputEscape();
        $this->message = new component_core_message($this->template);
        $this->UtilsHeader = new component_httpUtils_header($this->template);
        $this->modelPages = new frontend_model_pages($this->template);
        $this->xml = new component_xml_output();
        $this->header = new http_header();
        $this->data = new frontend_model_data($this);
        //$this->getlang = $this->template->currentLanguage();
        $this->imagesComponent = new component_files_images($this->template);
        $this->upload = new component_files_upload();
        $this->buildCollection = new frontend_model_collection($this->template);
        $this->DBHome = new frontend_db_home();
        $this->DBPages = new frontend_db_pages();
        $this->DBNews = new frontend_db_news();
        $this->DBCatalog = new frontend_db_news();
        $this->dateFormat = new date_dateformat();
        $this->DBCatalog = new frontend_db_catalog();
        $this->DBCategory = new frontend_db_category();
        $this->DBProduct = new frontend_db_product();
        $this->collectionDomain = new frontend_model_domain($this->template);
        $this->url = http_url::getUrl();
        $this->collectionLanguage = new component_collections_language();
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

        // --- Image Upload
        if(isset($_FILES['img']["name"])){
            $this->img = http_url::clean($_FILES['img']["name"]);
        }
        // --- MultiImage Upload
        if (isset($_FILES['img_multiple']["name"])) $this->img_multiple = ($_FILES['img_multiple']["name"]);

        if (http_request::isPost('data')) {
            $this->imgData = array();
            parse_str($_POST['data'],$this->imgData);
        }
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @return mixed
     * @throws Exception
     */
    private function getItems($type, $id = null, $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function setWsAuthKey(){
        $data = $this->getItems('auth',null,'one',false);
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
        $data = array('domain','languages','home','pages','news','catalog');
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
     * Build language Data
     */
    private function getBuildLanguageData(){
        // Collection
        $collection = $this->collectionLanguage->fetchData(
            array('context' => 'all', 'type' => 'langs')
        );

        $this->xml->newStartElement('languages');

        foreach($collection as $key) {
            $this->xml->newStartElement('language');

            $this->xml->setElement(
                array(
                    'start' => 'id_lang',
                    'text' => $key['id_lang']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'iso_lang',
                    'text' => $key['iso_lang']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'name_lang',
                    'text' => $key['name_lang']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'default_lang',
                    'text' => $key['default_lang']
                )
            );
            $this->xml->newEndElement();
        }
        $this->xml->newEndElement();
        $this->xml->output();
    }
    /**
     * Build language Data
     */
    private function getBuildDomainData(){
        // Collection
        $collection = $this->collectionDomain->getValidDomains();

        $this->xml->newStartElement('domains');

        foreach($collection as $key) {
            $this->xml->newStartElement('domain');

            $this->xml->setElement(
                array(
                    'start' => 'id_domain',
                    'text' => $key['id_domain']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'url_domain',
                    'text' => $key['url_domain']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'default_domain',
                    'text' => $key['default_domain']
                )
            );
            $this->xml->newEndElement();
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
                    'start' => 'content',
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
            $this->xml->setElement(
                array(
                    'start' => 'menu',
                    'text' => $value['menu_pages']
                )
            );
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
       /*print '<pre>';
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
                if(is_array($item['tags'])) {
                    foreach ($item['tags'] as $tags => $tag) {
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
                // Start SEO
                $this->xml->newStartElement('seo');
                $this->xml->setElement(
                    array(
                        'start' => 'title',
                        'text' => $item['seo_title_news']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'description',
                        'text' => $item['seo_desc_news']
                    )
                );
                //End SEO
                $this->xml->newEndElement();
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
                if(is_array($item['tags'])) {
                    // Start tags
                    $this->xml->newStartElement('tags');
                    foreach ($item['tags'] as $tags => $tag) {
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
                }
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
     * Build Home Data (EDIT)
     */
    private function getBuildCatalogData(){
        // Collection
        $collectionData = $this->DBCatalog->fetchData(
            array('context' => 'all', 'type' => 'rootWs')
        );

        $collection = array();
        if($collectionData != null) {
            foreach ($collectionData as $item) {
                $collection[$item['id_lang']][$item['name_info']] = $item['value_info'];
                $collection[$item['id_lang']]['iso_lang'] = $item['iso_lang'];
                $collection[$item['id_lang']]['id_lang'] = $item['id_lang'];
                $collection[$item['id_lang']]['default_lang'] = $item['default_lang'];
            }
        }

        $this->xml->newStartElement('pages');
        if($collectionData != null) {
            foreach ($collection as $key) {
                $this->xml->newStartElement('page');

                $this->xml->setElement(
                    array(
                        'start' => 'id_lang',
                        'text' => $key['id_lang'],
                        'attr' => array(
                            array(
                                'name' => 'default',
                                'content' => $key['default_lang']
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
                        'text' => $key['name']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'content',
                        'cData' => $key['content']
                    )
                );
                // Start SEO
                $this->xml->newStartElement('seo');
                $this->xml->setElement(
                    array(
                        'start' => 'title',
                        'text' => $key['seo_title']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'description',
                        'text' => $key['seo_desc']
                    )
                );
                //End SEO
                $this->xml->newEndElement();
                $this->xml->newEndElement();
            }
        }
        $this->xml->newEndElement();
        $this->xml->output();
    }

    /**
     * Build News items
     */
    private function getBuildCategoryItems()
    {
        $collection = $this->DBCategory->fetchData(
            array('context' => 'all', 'type' => 'pages', 'conditions' => null)
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
                // Start SEO
                $this->xml->newStartElement('seo');
                $this->xml->setElement(
                    array(
                        'start' => 'title',
                        'text' => $item['seo_title_cat']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'description',
                        'text' => $item['seo_desc_cat']
                    )
                );
                //End SEO
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
    public function getBuildProductItems(){
        $collection = $this->DBProduct->fetchData(
            array('context' => 'all', 'type' => 'pages','conditions'=>null)
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
        $collection = $this->DBProduct->fetchData(
            array('context' => 'all', 'type' => 'pages','conditions'=>'WHERE p.id_product = :id'),
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
                            $this->xml->setElement(
                                array(
                                    'start' => 'caption',
                                    'text' => $imgData['caption_img']
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
                        'start' => 'longname',
                        'text' => $item['longname_p']
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
                // Start SEO
                $this->xml->newStartElement('seo');
                $this->xml->setElement(
                    array(
                        'start' => 'title',
                        'text' => $item['seo_title_p']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'description',
                        'text' => $item['seo_desc_p']
                    )
                );
                //End SEO
                $this->xml->newEndElement();
                // End language loop
                $this->xml->newEndElement();
            }
            // End languages
            $this->xml->newEndElement();
            if($value['associated']) {
                // Start Associated
                $this->xml->newStartElement('associated');
                // Loop associated
                foreach ($value['associated'] as $assoKey => $item) {
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
                    if (isset($item['images'])) {
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
                        if ($item['images']['content'] != null) {
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
            }
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
     * Ajout et Mise a jour des données
     * @param $operation
     * @param $arrData
     * @throws Exception
     */
    private function getBuildSave($operation,$arrData)
    {
        switch($operation['type']){
            case 'home':
                $fetchRootData = $this->DBHome->fetchData(array('context'=>'one','type'=>'root'));
                if($fetchRootData != null){
                    $id_page = $fetchRootData['id_page'];
                }else{
                    $this->DBHome->insert(array('type'=>'newHome'));
                    $newData = $this->DBHome->fetchData(array('context'=>'one','type'=>'root'));
                    $id_page = $newData['id_page'];
                }

                if($id_page) {
                    //print_r($arrData['language']);
                    foreach ($arrData['language'] as $lang => $content) {
                        //$content['published'] = (!isset($content['published']) ? 0 : 1);

                        $data = array(
                            'title_page'        => !is_array($content['name']) ? $content['name'] : '',
                            'content_page'      => !is_array($content['content']) ? $content['content'] : '',
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
                    $this->header->set_json_headers();
                    $this->message->json_post_response(true, null, array('id'=>$id_page));
                }
                break;
            case 'pages':
                if (isset($this->id)) {
                    // Regarder pour voir si l'édition et ajout fonctionne correctement, sinon ajouté paramètre id (get)
                    $fetchRootData = $this->DBPages->fetchData(array('context'=>'one','type'=>'wsEdit'),array('id'=>$this->id));
                    if($fetchRootData != null){
                        $id_page = $fetchRootData['id_pages'];
                        $this->DBPages->update(array('type'=>'page'),array(':id_parent' => empty($arrData['parent']) ? NULL : $arrData['parent'],':menu_pages' => empty($arrData['menu']) ? NULL : $arrData['menu'],':id_pages'=>$id_page));

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
                    if(!array_key_exists('0',$arrData['language'])) {

                        $content = $arrData['language'];

                        $data = array(
                            'name_pages'        => !is_array($content['name']) ? $content['name'] : '',
                            'url_pages'         => !is_array($content['url']) ? http_url::clean($content['name'],
                                array(
                                    'dot' => false,
                                    'ampersand' => 'strict',
                                    'cspec' => '', 'rspec' => ''
                                )
                            ) : '',
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

                    }else{
                        foreach ($arrData['language'] as $lang => $content) {
                            //print_r($content);
                            //$content['published'] = (!isset($content['published']) ? 0 : 1);

                            $data = array(
                                'name_pages'        => !is_array($content['name']) ? $content['name'] : '',
                                'url_pages'         => !is_array($content['url']) ? http_url::clean($content['name'],
                                    array(
                                        'dot' => false,
                                        'ampersand' => 'strict',
                                        'cspec' => '', 'rspec' => ''
                                    )
                                ) : '',
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
                    }

                    $this->header->set_json_headers();
                    $this->message->json_post_response(true, null, array('id'=>$id_page));
                }
                break;
            case 'news':
                if (isset($this->id)) {
                    // Regarder pour voir si l'édition et ajout fonctionne correctement, sinon ajouté paramètre id (get)
                    $fetchRootData = $this->DBNews->fetchData(array('context'=>'one','type'=>'wsEdit'),array('id'=>$this->id));
                    if($fetchRootData != null){
                        $id_news = $fetchRootData['id_news'];
                    }else{
                        $this->DBNews->insert(array('type'=>'page'));
                        $newData = $this->DBNews->fetchData(array('context'=>'one','type'=>'root'));
                        $id_news = $newData['id_news'];
                    }
                }else{
                    $this->DBNews->insert(array('type'=>'page'));
                    $newData = $this->DBNews->fetchData(array('context'=>'one','type'=>'root'));
                    $id_news = $newData['id_news'];
                }
                if($id_news) {
                    //print_r($arrData);
                    foreach ($arrData['language'] as $lang => $content) {
                        $content['published'] = (!isset($content['published']) ? 0 : 1);
                        if (is_array($content['url'])) {
                            $content['url'] = http_url::clean($content['name'],
                                array(
                                    'dot' => false,
                                    'ampersand' => 'strict',
                                    'cspec' => '', 'rspec' => ''
                                )
                            );
                        }
                        $dateFormat = new date_dateformat();
                        $datePublish = !empty($content['date']) ? $dateFormat->SQLDateTime($content['date']) : $dateFormat->SQLDateTime($dateFormat->dateToDefaultFormat());
                        $data = array(
                            'id_lang'           => $content['id_lang'],
                            'id_news'           => $id_news,
                            'name_news'         => !is_array($content['name']) ? $content['name'] : '',
                            'url_news'          => !is_array($content['url']) ? $content['url'] : '',
                            'content_news'      => !is_array($content['content']) ? $content['content'] : '',
                            'resume_news'       => !is_array($content['resume']) ? trim($content['resume']) : '',
                            'seo_title_news'    => !is_array($content['seo']['title']) ? $content['seo']['title'] : '',
                            'seo_desc_news'     => !is_array($content['seo']['description']) ? $content['seo']['description'] : '',
                            'date_publish'      => $datePublish,
                            'published_news'    => $content['published']
                        );
                        if ($this->DBNews->fetchData(array('context' => 'one', 'type' => 'content'), array('id_news' => $id_news, 'id_lang' => $content['id_lang'])) != null) {

                            $this->DBNews->update(array('type' => 'content'), $data);

                        } else {

                            $this->DBNews->insert(array('type' => 'content'), $data);
                        }
                        // Add Tags
                        if(!empty($content['tag']) && isset($content['tag'])) {
                            //$tagNews = explode(',', $content['tag_news']);
                            //if ($tagNews != null) {
                            if(is_array($content['tag'])){
                                foreach ($content['tag'] as $key => $value) {
                                    $setTags = $this->DBNews->fetchData(
                                        array('context' => 'one', 'type' => 'tag_ws'),
                                        array(':id_news' => $id_news, ':id_lang' => $content['id_lang'], ':name_tag' => $value)
                                    );
                                    if ($setTags['id_tag'] != null) {
                                        if ($setTags['rel_tag'] == null) {
                                            $this->DBNews->insert(
                                                array(
                                                    'type' => 'newTagRel'
                                                ),
                                                array(
                                                    'id_news'=> $id_news,
                                                    'id_tag' => $setTags['id_tag']
                                                )
                                            );
                                        }
                                    } else {
                                        $this->DBNews->insert(
                                            array(
                                                'type' => 'newTagComb'
                                            ),
                                            array(
                                                'id_news' => $id_news,
                                                'id_lang' => $content['id_lang'],
                                                'name_tag'=> $value
                                            )
                                        );
                                    }
                                }
                            }else{
                                $setTags = $this->DBNews->fetchData(
                                    array('context' => 'one', 'type' => 'tag_ws'),
                                    array(':id_news' => $id_news, ':id_lang' => $content['id_lang'], ':name_tag' => $content['tag'])
                                );
                                if ($setTags['id_tag'] != null) {
                                    if ($setTags['rel_tag'] == null) {
                                        $this->DBNews->insert(
                                            array(
                                                'type' => 'newTagRel'
                                            ),
                                            array(
                                                'id_news'=> $id_news,
                                                'id_tag' => $setTags['id_tag']
                                            )
                                        );
                                    }
                                } else {
                                    $this->DBNews->insert(
                                        array(
                                            'type' => 'newTagComb'
                                        ),
                                        array(
                                            'id_news' => $id_news,
                                            'id_lang' => $content['id_lang'],
                                            'name_tag'=> $content['tag']
                                        )
                                    );
                                }
                            }
                        }
                    }
                    $this->header->set_json_headers();
                    $this->message->json_post_response(true, null, array('id'=>$id_news));
                }
                break;
            case 'catalog':
                if(!array_key_exists('0',$arrData['language'])) {

                    $content = $arrData['language'];

                    if ($this->DBCatalog->fetchData(array('context' => 'one', 'type' => 'root'), array('id_lang' => $content['id_lang'])) != null) {
                        $this->DBCatalog->update(array('type' => 'content'), array(
                                'name'          => !is_array($content['name']) ? $content['name'] : '',
                                'content'       => !is_array($content['content']) ? $content['content'] : '',
                                'seo_title'     => !is_array($content['seo']['title']) ? $content['seo']['title'] : '',
                                'seo_desc'      => !is_array($content['seo']['description']) ? $content['seo']['description'] : '',
                                'id_lang'       => $content['id_lang']
                            )
                        );
                    } else {
                        $this->DBCatalog->insert(array('type' => 'newContent'), array(
                                'name'          => !is_array($content['name']) ? $content['name'] : '',
                                'content'       => !is_array($content['content']) ? $content['content'] : '',
                                'seo_title'     => !is_array($content['seo']['title']) ? $content['seo']['title'] : '',
                                'seo_desc'      => !is_array($content['seo']['description']) ? $content['seo']['description'] : '',
                                'id_lang'       => $content['id_lang']
                            )
                        );
                    }

                }else{
                    foreach ($arrData['language'] as $lang => $content) {
                        if ($this->DBCatalog->fetchData(array('context' => 'one', 'type' => 'root'), array('id_lang' => $content['id_lang'])) != null) {
                            $this->DBCatalog->update(array('type' => 'content'), array(
                                    'name'          => !is_array($content['name']) ? $content['name'] : '',
                                    'content'       => !is_array($content['content']) ? $content['content'] : '',
                                    'seo_title'     => !is_array($content['seo']['title']) ? $content['seo']['title'] : '',
                                    'seo_desc'      => !is_array($content['seo']['description']) ? $content['seo']['description'] : '',
                                    'id_lang'       => $content['id_lang']
                                )
                            );
                        } else {
                            $this->DBCatalog->insert(array('type' => 'newContent'), array(
                                    'name'      => !is_array($content['name']) ? $content['name'] : '',
                                    'content'   => !is_array($content['content']) ? $content['content'] : '',
                                    'seo_title' => !is_array($content['seo']['title']) ? $content['seo']['title'] : '',
                                    'seo_desc'  => !is_array($content['seo']['description']) ? $content['seo']['description'] : '',
                                    'id_lang'   => $content['id_lang']
                                )
                            );
                        }
                    }
                }

                break;
            case 'category':
                if (isset($this->id)) {
                    // Regarder pour voir si l'édition et ajout fonctionne correctement, sinon ajouté paramètre id (get)
                    $fetchRootData = $this->DBCategory->fetchData(array('context'=>'one','type'=>'wsEdit'),array('id'=>$this->id));
                    if($fetchRootData != null){
                        $id_cat = $fetchRootData['id_cat'];
                        $this->DBCategory->update(array('type'=>'page'),array(':id_parent' => empty($arrData['parent']) ? NULL : $arrData['parent'],':menu_cat' => empty($arrData['menu']) ? NULL : $arrData['menu'],':id_cat'=>$id_cat));
                    }else{
                        $this->DBCategory->insert(array('type'=>'page'),array(':id_parent' => empty($arrData['parent']) ? NULL : $arrData['parent']));
                        $newData = $this->DBCategory->fetchData(array('context'=>'one','type'=>'root'));
                        $id_cat = $newData['id_cat'];
                    }
                }else{
                    $this->DBCategory->insert(array('type'=>'page'),array(':id_parent' => empty($arrData['parent']) ? NULL : $arrData['parent']));
                    $newData = $this->DBCategory->fetchData(array('context'=>'one','type'=>'root'));
                    $id_cat = $newData['id_cat'];
                }

                if($id_cat) {

                    /*print '<pre>';
                    print_r($arrData);
                    print '</pre>';*/

                    if(!array_key_exists('0',$arrData['language'])) {

                        $content = $arrData['language'];
                        $data = array(
                            'name_cat' => !is_array($content['name']) ? $content['name'] : '',
                            'url_cat' => !is_array($content['url']) ? http_url::clean($content['name'],
                                array(
                                    'dot' => false,
                                    'ampersand' => 'strict',
                                    'cspec' => '', 'rspec' => ''
                                )
                            ) : '',
                            'resume_cat'    => !is_array($content['resume']) ? trim($content['resume']) : '',
                            'content_cat'   => !is_array($content['content']) ? $content['content'] : '',
                            'seo_title_cat' => !is_array($content['seo']['title']) ? $content['seo']['title'] : '',
                            'seo_desc_cat'  => !is_array($content['seo']['description']) ? $content['seo']['description'] : '',
                            'published_cat' => $content['published'],
                            'id_cat'        => $id_cat,
                            'id_lang'       => $content['id_lang']
                        );
                        //print_r($data);
                        if ($this->DBCategory->fetchData(array('context' => 'one', 'type' => 'content'), array('id_cat' => $id_cat, 'id_lang' => $content['id_lang'])) != null) {

                            $this->DBCategory->update(array('type' => 'content'), $data);

                        } else {

                            $this->DBCategory->insert(array('type' => 'content'), $data);
                        }
                    }else{
                        foreach ($arrData['language'] as $lang => $content) {
                            //print_r($content);

                            $data = array(
                                'name_cat' => !is_array($content['name']) ? $content['name'] : '',
                                'url_cat' => !is_array($content['url']) ? http_url::clean($content['name'],
                                    array(
                                        'dot' => false,
                                        'ampersand' => 'strict',
                                        'cspec' => '', 'rspec' => ''
                                    )
                                ) : '',
                                'resume_cat' => !is_array($content['resume']) ? trim($content['resume']) : '',
                                'content_cat' => !is_array($content['content']) ? $content['content'] : '',
                                'seo_title_cat' => !is_array($content['seo']['title']) ? $content['seo']['title'] : '',
                                'seo_desc_cat'  => !is_array($content['seo']['description']) ? $content['seo']['description'] : '',
                                'published_cat' => $content['published'],
                                'id_cat' => $id_cat,
                                'id_lang' => $content['id_lang']
                            );
                            //print_r($data);
                            if ($this->DBCategory->fetchData(array('context' => 'one', 'type' => 'content'), array('id_cat' => $id_cat, 'id_lang' => $content['id_lang'])) != null) {

                                $this->DBCategory->update(array('type' => 'content'), $data);

                            } else {

                                $this->DBCategory->insert(array('type' => 'content'), $data);
                            }
                        }
                    }
                    $this->header->set_json_headers();
                    $this->message->json_post_response(true, null, array('id'=>$id_cat));
                }
                break;
            case 'product':
                // ######### ---- Add product in category
                if(isset($arrData['category'])){
                    if (isset($this->id)) {
                        $content = $arrData['category'];

                        if(!array_key_exists('0',$arrData['language'])) {
                            $control = $this->DBProduct->fetchData(array('context' => 'one', 'type' => 'category'), array('id' => $this->id, 'id_cat' => $content['id']));
                            if(!$control['id_catalog']){
                                $this->DBProduct->insert(array('type' => 'catRel'), array('id' => $this->id, 'id_cat' => $content['id'], 'default_c' => $content['default']));
                            }
                        }else{
                            foreach ($arrData['category'] as $category => $content) {
                                $control = $this->DBProduct->fetchData(array('context' => 'one', 'type' => 'category'), array('id' => $this->id, 'id_cat' => $content['id']));
                                if(!$control['id_catalog']){
                                    $this->DBProduct->insert(array('type' => 'catRel'), array('id' => $this->id, 'id_cat' => $content['id'], 'default_c' => $content['default']));
                                }
                            }
                        }

                        $this->header->set_json_headers();
                        $this->message->json_post_response(true, null);
                    }
                }else {
                    // ######### ---- Add Or Update product
                    if (isset($this->id)) {
                        // Regarder pour voir si l'édition et ajout fonctionne correctement, sinon ajouté paramètre id (get)
                        $fetchRootData = $this->DBProduct->fetchData(array('context'=>'one','type'=>'page'),array('id'=>$this->id));
                        if($fetchRootData != null){
                            $id_product = $fetchRootData['id_product'];
                            $this->DBProduct->update(array('type'=>'page'),array(':price_p' => empty($arrData['price']) ? NULL : $arrData['price'],':reference_p' => empty($arrData['reference']) ? NULL : $arrData['reference'],':id_product'=>$id_product));
                        }else{
                            $this->DBProduct->insert(array('type'=>'newPages'),array(':price_p' => empty($arrData['price']) ? NULL : $arrData['price'],':reference_p' => empty($arrData['reference']) ? NULL : $arrData['reference']));//reference_p
                            $newData = $this->DBProduct->fetchData(array('context'=>'one','type'=>'root'));
                            $id_product = $newData['id_product'];
                        }
                    }else{
                        $this->DBProduct->insert(array('type'=>'newPages'),array(':price_p' => empty($arrData['price']) ? NULL : $arrData['price'],':reference_p' => empty($arrData['reference']) ? NULL : $arrData['reference']));
                        $newData = $this->DBProduct->fetchData(array('context'=>'one','type'=>'root'));
                        $id_product = $newData['id_product'];
                    }

                    if($id_product) {
                        if(!array_key_exists('0',$arrData['language'])) {
                            $content = $arrData['language'];
                            $data = array(
                                'name_p'        => !is_array($content['name']) ? $content['name'] : '',
                                'url_p'         => !is_array($content['url']) ? http_url::clean($content['name'],
                                    array(
                                        'dot' => false,
                                        'ampersand' => 'strict',
                                        'cspec' => '', 'rspec' => ''
                                    )
                                ) : '',
                                'longname_p'    => !is_array($content['longname']) ? $content['longname'] : '',
                                'resume_p'      => !is_array($content['resume']) ? trim($content['resume']) : '',
                                'content_p'     => !is_array($content['content']) ? $content['content'] : '',
                                'seo_title_p'   => !is_array($content['seo']['title']) ? $content['seo']['title'] : '',
                                'seo_desc_p'    => !is_array($content['seo']['description']) ? $content['seo']['description'] : '',
                                'published_p'   => $content['published'],
                                'id_product'    => $id_product,
                                'id_lang'       => $content['id_lang']
                            );
                            if ($this->DBProduct->fetchData(array('context' => 'one', 'type' => 'content'), array('id_product' => $id_product, 'id_lang' => $content['id_lang'])) != null) {

                                $this->DBProduct->update(array('type' => 'content'), $data);

                            } else {

                                $this->DBProduct->insert(array('type' => 'newContent'), $data);
                            }

                        }else{
                            foreach ($arrData['language'] as $lang => $content) {

                                $data = array(
                                    'name_p'        => !is_array($content['name']) ? $content['name'] : '',
                                    'url_p'         => !is_array($content['url']) ? http_url::clean($content['name'],
                                        array(
                                            'dot' => false,
                                            'ampersand' => 'strict',
                                            'cspec' => '', 'rspec' => ''
                                        )
                                    ) : '',
                                    'longname_p'    => !is_array($content['longname']) ? $content['longname'] : '',
                                    'resume_p'      => !is_array($content['resume']) ? trim($content['resume']) : '',
                                    'content_p'     => !is_array($content['content']) ? $content['content'] : '',
                                    'seo_title_p' => !is_array($content['seo']['title']) ? $content['seo']['title'] : '',
                                    'seo_desc_p'  => !is_array($content['seo']['description']) ? $content['seo']['description'] : '',
                                    'published_p'   => $content['published'],
                                    'id_product'    => $id_product,
                                    'id_lang'       => $content['id_lang']
                                );
                                if ($this->DBProduct->fetchData(array('context' => 'one', 'type' => 'content'), array('id_product' => $id_product, 'id_lang' => $content['id_lang'])) != null) {

                                    $this->DBProduct->update(array('type' => 'content'), $data);

                                } else {

                                    $this->DBProduct->insert(array('type' => 'newContent'), $data);
                                }
                            }
                        }

                        $this->header->set_json_headers();
                        $this->message->json_post_response(true, null, array('id'=>$id_product));
                    }
                }
                break;
        }
    }

    /**
     * @param $operation
     * @param $arrData
     * @throws Exception
     */
    private function getBuildRemove($operation,$arrData){
        $makeFiles = new filesystem_makefile();
        switch($operation['type']){
            case 'pages':
                $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'pages','attribute_img'=>'page'));
                $imgPrefix = $this->imagesComponent->prefix();
                if($arrData['id'] != null) {
                    if (is_array($arrData['id'])) {
                        foreach ($arrData['id'] as $key => $value) {
                            $fetchImg[$key] = $this->DBPages->fetchData(array('context' => 'one', 'type' => 'image'), array('id_pages' => $value));

                            if ($fetchImg[$key] != null) {
                                $imgPath[$key] = component_core_system::basePath() . 'upload/pages/' . $value;
                                // Supprime le dossier des images et les fichiers
                                if (file_exists($imgPath[$key])) {
                                    $makeFiles->remove(array(
                                        $imgPath[$key]
                                    ));
                                }
                            }
                        }

                        $this->DBPages->delete(
                            array(
                                'type' => 'delPages'
                            ),
                            array('id' => implode(",", $arrData['id'])
                            )
                        );

                    } else {
                        $fetchImg = $this->DBPages->fetchData(array('context' => 'one', 'type' => 'image'), array('id_pages' => $arrData['id']));

                        if ($fetchImg != null) {
                            $imgPath = component_core_system::basePath() . 'upload/pages/' . $arrData['id'];
                            // Supprime l'image original
                            /*$original = $imgPath . '/' . $fetchImg['img_pages'];
                            if (file_exists($original)) {
                                $makeFiles->remove(array(
                                    $original
                                ));
                            }
                            $img = '';
                            // Supprime les images recadrer
                            foreach ($fetchConfig as $key => $value) {
                                $img[$key]= $imgPath . '/' . $imgPrefix[$value['type_img']] . $fetchImg['img_pages'];
                                if (file_exists($img[$key])) {
                                    $makeFiles->remove(array(
                                        $img[$key]
                                    ));
                                }
                            }*/
                            // Supprime le dossier des images et les fichiers
                            if (file_exists($imgPath)) {
                                $makeFiles->remove(array(
                                    $imgPath
                                ));
                            }
                        }

                        $this->DBPages->delete(
                            array(
                                'type' => 'delPages'
                            ),
                            array('id' => $arrData['id'])
                        );

                    }
                }
                break;
            case 'news':
                if (isset($this->id)) {
                    foreach ($arrData['language'] as $lang => $content) {
                        if ($content['tag'] != null) {
                            if (is_array($content['tag'])) {
                                foreach ($content['tag'] as $key => $value) {
                                    $setTags = $this->DBNews->fetchData(
                                        array('context' => 'one', 'type' => 'tag_ws'),
                                        array(':id_news' => $this->id, ':id_lang' => $content['id_lang'], ':name_tag' => $value)
                                    );

                                    if ($setTags['id_tag'] != null && $setTags['rel_tag'] != null) {
                                        $this->DBNews->delete(array('type' => 'tagRel'), array('id_rel' => $setTags['rel_tag']));
                                    }
                                }
                            }else{
                                $setTags = $this->DBNews->fetchData(
                                    array('context' => 'one', 'type' => 'tag_ws'),
                                    array(':id_news' => $this->id, ':id_lang' => $content['id_lang'], ':name_tag' => $content['tag'])
                                );

                                if ($setTags['id_tag'] != null && $setTags['rel_tag'] != null) {
                                    $this->DBNews->delete(array('type' => 'tagRel'), array('id_rel' => $setTags['rel_tag']));
                                }
                            }
                        }
                    }
                }else {
                    $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img' => 'news', 'attribute_img' => 'news'));
                    $imgPrefix = $this->imagesComponent->prefix();
                    if ($arrData['id'] != null) {
                        if (is_array($arrData['id'])) {
                            foreach ($arrData['id'] as $key => $value) {
                                $fetchImg[$key] = $this->DBNews->fetchData(array('context' => 'one', 'type' => 'image'), array('id_news' => $value));

                                if ($fetchImg[$key] != null) {
                                    $imgPath[$key] = component_core_system::basePath() . 'upload/news/' . $value;
                                    // Supprime le dossier des images et les fichiers
                                    if (file_exists($imgPath[$key])) {
                                        $makeFiles->remove(array(
                                            $imgPath[$key]
                                        ));
                                    }
                                }
                            }

                            $this->DBNews->delete(
                                array(
                                    'type' => 'delPages'
                                ),
                                array('id' => implode(",", $arrData['id'])
                                )
                            );

                        } else {
                            $fetchImg = $this->DBNews->fetchData(array('context' => 'one', 'type' => 'image'), array('id_news' => $arrData['id']));

                            if ($fetchImg != null) {
                                $imgPath = component_core_system::basePath() . 'upload/news/' . $arrData['id'];

                                // Supprime le dossier des images et les fichiers
                                if (file_exists($imgPath)) {
                                    $makeFiles->remove(array(
                                        $imgPath
                                    ));
                                }
                            }

                            $this->DBNews->delete(
                                array(
                                    'type' => 'delPages'
                                ),
                                array('id' => $arrData['id'])
                            );

                        }
                    }
                }
                break;
            case 'category':
                $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'catalog','attribute_img'=>'category'));
                $imgPrefix = $this->imagesComponent->prefix();
                if($arrData['id'] != null) {
                    if (is_array($arrData['id'])) {
                        foreach ($arrData['id'] as $key => $value) {
                            $fetchImg[$key] = $this->DBCategory->fetchData(array('context' => 'one', 'type' => 'image'), array('id_cat' => $value));

                            if ($fetchImg[$key] != null) {
                                $imgPath[$key] = component_core_system::basePath() . 'upload/catalog/c/' . $value;
                                // Supprime le dossier des images et les fichiers
                                if (file_exists($imgPath[$key])) {
                                    $makeFiles->remove(array(
                                        $imgPath[$key]
                                    ));
                                }
                            }
                        }

                        $this->DBCategory->delete(
                            array(
                                'type' => 'delPages'
                            ),
                            array('id' => implode(",", $arrData['id'])
                            )
                        );

                    } else {
                        $fetchImg = $this->DBCategory->fetchData(array('context' => 'one', 'type' => 'image'), array('id_cat' => $arrData['id']));

                        if ($fetchImg != null) {
                            $imgPath = component_core_system::basePath() . 'upload/catalog/c/' . $arrData['id'];
                            // Supprime le dossier des images et les fichiers
                            if (file_exists($imgPath)) {
                                $makeFiles->remove(array(
                                    $imgPath
                                ));
                            }
                        }

                        $this->DBCategory->delete(
                            array(
                                'type' => 'delPages'
                            ),
                            array('id' => $arrData['id'])
                        );

                    }
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
                case 'languages':

                    $this->xml->getXmlHeader();
                    $this->getBuildLanguageData();

                    break;
                case 'domain':

                    $this->xml->getXmlHeader();
                    $this->getBuildDomainData();

                    break;
                case 'home':
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
                            //$this->header->set_json_headers();
                            //print '{"statut":'.json_encode(true).',"notify":'.json_encode("add").'}';


                        }elseif($getContentType === 'json'){

                            //{"parent":2,"languages":{"language":[{"id_lang":6},{"id_lang":2}]}}
                            //{"parent":2,"language":[{"id_lang":6},{"id_lang":2}]}
                            $arrData = json_decode(json_encode($this->parse()), true);

                            $this->getBuildSave($operations,$arrData);
                        }
                    }

                    elseif($this->ws->setMethod() === 'GET'){
                        //print $getContentType;
                        $this->xml->getXmlHeader();
                        $this->getBuildHomeData();

                    }
                    break;
                case 'pages':

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

                        }elseif($getContentType === 'files'){

                            if (isset($this->id)) {

                                $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
                                $page = $this->DBPages->fetchData(array('context' => 'one', 'type' => 'pageLang'), array('id' => $this->id, 'iso' => $defaultLanguage['iso_lang']));

                                $settings = array(
                                    'name' => $page['url_pages'],
                                    'edit' => $page['img_pages'],
                                    'prefix' => array('s_', 'm_', 'l_'),
                                    'module_img' => 'pages',
                                    'attribute_img' => 'page',
                                    'original_remove' => false
                                );
                                $dirs = array(
                                    'upload_root_dir' => 'upload/pages', //string
                                    'upload_dir' => $this->id //string ou array
                                );

                                $resultUpload = $this->upload->setImageUpload('img', $settings, $dirs, false);

                                $this->DBPages->update(
                                    array(
                                        'type' => 'img'
                                    ),
                                    array(
                                        'id_pages' => $this->id,
                                        'img_pages' => $resultUpload['file']
                                    )
                                );
                            }
                        }
                    }
                    elseif($this->ws->setMethod() === 'DELETE'){
                        //print_r($this->parse());

                        if($getContentType === 'xml') {

                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildRemove($operations,$arrData);

                        }elseif($getContentType === 'json'){
                            //{"id":[53,54]}
                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildRemove($operations,$arrData);

                        }
                        //$this->header->set_json_headers();
                        //$this->message->json_post_response(true,'delete',$del);

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
                case 'news':
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
                        elseif($getContentType === 'files'){

                            if (isset($this->id)) {

                                $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
                                $page = $this->DBNews->fetchData(array('context' => 'one', 'type' => 'pageLang'), array('id' => $this->id, 'iso' => $defaultLanguage['iso_lang']));

                                $settings = array(
                                    'name' => $page['url_news'],
                                    'edit' => $page['img_news'],
                                    'prefix' => array('s_', 'm_', 'l_'),
                                    'module_img' => 'news',
                                    'attribute_img' => 'news',
                                    'original_remove' => false
                                );
                                $dirs = array(
                                    'upload_root_dir' => 'upload/news', //string
                                    'upload_dir' => $this->id //string ou array
                                );

                                $resultUpload = $this->upload->setImageUpload('img', $settings, $dirs, false);

                                $this->DBNews->update(
                                    array(
                                        'type' => 'img'
                                    ),
                                    array(
                                        'id_news' => $this->id,
                                        'img_news' => $resultUpload['file']
                                    )
                                );
                            }
                        }

                    }elseif($this->ws->setMethod() === 'DELETE'){
                        //print_r($this->parse());

                        if($getContentType === 'xml') {

                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildRemove($operations,$arrData);

                        }elseif($getContentType === 'json'){
                            //{"id":[53,54]}
                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildRemove($operations,$arrData);
                        }
                        //$this->header->set_json_headers();
                        //$this->message->json_post_response(true,'delete',$del);

                    }elseif($this->ws->setMethod() === 'GET'){
                        if (isset($this->id)) {

                            $this->xml->getXmlHeader();
                            $this->getBuildNewsData();

                        } else {
                            $this->xml->getXmlHeader();
                            $this->getBuildNewsItems();

                        }
                    }
                    break;
                case 'catalog':
                    if($this->ws->setMethod() === 'POST') {
                        if ($getContentType === 'xml') {

                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildSave($operations, $arrData);


                        } elseif ($getContentType === 'json') {

                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildSave($operations, $arrData);
                        }
                    }
                    elseif($this->ws->setMethod() === 'GET') {
                        $this->xml->getXmlHeader();
                        $this->getBuildCatalogData();
                    }
                    break;
                case 'category':
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
                        }elseif($getContentType === 'files'){

                            if (isset($this->id)) {

                                $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
                                $page = $this->DBCategory->fetchData(array('context' => 'one', 'type' => 'pageLang'), array('id' => $this->id, 'iso' => $defaultLanguage['iso_lang']));

                                $settings = array(
                                    'name'              => $page['url_cat'],
                                    'edit'              => $page['img_cat'],
                                    'prefix'            => array('s_', 'm_', 'l_'),
                                    'module_img'        => 'catalog',
                                    'attribute_img'     => 'category',
                                    'original_remove'   => false
                                );
                                $dirs = array(
                                    'upload_root_dir'   => 'upload/catalog/c', //string
                                    'upload_dir'        => $this->id //string ou array
                                );

                                $resultUpload = $this->upload->setImageUpload('img', $settings, $dirs, false);

                                $this->DBCategory->update(
                                    array(
                                        'type' => 'img'
                                    ),
                                    array(
                                        'id_cat' => $this->id,
                                        'img_cat' => $resultUpload['file']
                                    )
                                );
                            }
                        }

                    }elseif($this->ws->setMethod() === 'DELETE'){
                        //print_r($this->parse());

                        if($getContentType === 'xml') {

                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildRemove($operations,$arrData);

                        }elseif($getContentType === 'json'){
                            //{"id":[53,54]}
                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildRemove($operations,$arrData);
                        }
                        //$this->header->set_json_headers();
                        //$this->message->json_post_response(true,'delete',$del);

                    }elseif($this->ws->setMethod() === 'GET'){
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
                    }
                    break;
                case 'product':
                    if($this->ws->setMethod() === 'PUT'){
                        if($getContentType === 'xml') {

                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildSave($operations,$arrData);
                        }
                    }elseif($this->ws->setMethod() === 'POST'){
                        if($getContentType === 'xml'){

                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildSave($operations,$arrData);


                        }elseif($getContentType === 'json'){

                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildSave($operations,$arrData);

                        }elseif($getContentType === 'files'){

                            if (isset($this->id)) {

                                $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
                                $product = $this->DBProduct->fetchData(array('context' => 'one', 'type' => 'content'), array('id_product' => $this->id, 'id_lang' => $defaultLanguage['id_lang']));
                                $newimg = $this->DBProduct->fetchData(array('context' => 'one', 'type' => 'lastImgId'));

                                $resultUpload = $this->upload->setMultipleImageUpload(
                                    'img_multiple',
                                    array(
                                        'name' => $product['url_p'],
                                        'prefix_name' => $newimg['id_img'],
                                        'prefix_increment' => true,
                                        'prefix' => array('s_', 'm_', 'l_'),
                                        'module_img' => 'catalog',
                                        'attribute_img' => 'product',
                                        'original_remove' => false
                                    ),
                                    array(
                                        'upload_root_dir' => 'upload/catalog/p', //string
                                        'upload_dir' => $this->id //string ou array
                                    ),
                                    false
                                );
                                if ($resultUpload != null) {
                                    $preparePercent = 80 / count($resultUpload);
                                    $percent = 10;

                                    foreach ($resultUpload as $key => $value) {
                                        if ($value['statut'] == '1') {
                                            $percent = $percent + $preparePercent;

                                            $this->DBProduct->insert(
                                                array(
                                                    'type' => 'newImg'
                                                ),
                                                array(
                                                    'id_product' => $this->id,
                                                    'name_img' => $value['file']
                                                )
                                            );
                                        }
                                    }
                                }
                            }
                        }

                    }elseif($this->ws->setMethod() === 'GET'){
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
                    case 'languages':

                        $this->getBuildParse(array('type' => 'languages'));

                        break;
                    case 'domain':

                        $this->getBuildParse(array('type' => 'domain'));

                        break;
                    case 'home':

                        $this->getBuildParse(array('type' => 'home'));

                        break;
                    case 'pages':

                        $this->getBuildParse(array('type' => 'pages'));

                        break;
                    case 'news':

                        $this->getBuildParse(array('type' => 'news'));

                        break;
                    case 'catalog':

                        if (isset($this->retrieve)) {

                            if ($this->retrieve == 'category') {

                                $this->getBuildParse(array('type' => 'category'));

                            }elseif ($this->retrieve == 'product') {

                                $this->getBuildParse(array('type' => 'product'));

                            } else {
                                return;
                            }
                        }else{
                            $this->getBuildParse(array('type' => 'catalog'));
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