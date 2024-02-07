<?php
class frontend_controller_webservice extends frontend_db_webservice {
    /**
     * @var frontend_model_template $template
     * @var frontend_model_data $data
     * @var component_core_message $message
     * @var http_header $header
     * @var component_xml_output $xml
     * @var date_dateformat $dateFormat
     * @var component_routing_url $routingUrl
     * @var component_files_upload $upload
     * @var component_files_images $imagesComponent
     * @var component_collections_language $collectionLanguage
     * @var frontend_model_domain $collectionDomain
     * @var frontend_db_home $DBHome
     * @var frontend_db_pages $DBPages
     * @var frontend_db_news $DBNews
     * @var frontend_db_catalog $DBCatalog
     * @var frontend_db_category $DBCategory
     * @var frontend_db_product $DBProduct
     * @var frontend_model_webservice $ws
     * @var frontend_model_collection $buildCollection
     * @var frontend_model_plugins $buildPlugins
     */
    protected frontend_model_template $template;
    protected frontend_model_data $data;
    protected component_core_message $message;
    protected http_header $header;
    protected component_xml_output $xml;
    protected date_dateformat $dateFormat;
    protected component_routing_url $routingUrl;
    protected component_files_upload $upload;
    protected component_files_images $imagesComponent;
    protected component_collections_language $collectionLanguage;
    protected frontend_model_domain $collectionDomain;
    protected frontend_db_home $DBHome;
    protected frontend_db_pages $DBPages;
    protected frontend_db_news $DBNews;
    protected frontend_db_catalog $DBCatalog;
    protected frontend_db_category $DBCategory;
    protected frontend_db_product $DBProduct;
    protected frontend_model_webservice $ws;
    protected frontend_model_collection $buildCollection;
    protected frontend_model_plugins $buildPlugins;

    /**
     * @var int $id
     */
    public int $id,
        $tag;

    /**
     * @var string $url
     * @var string $collection
     * @var string $retrieve
     * @var string $sort
     * @var string $img
     * @var string $img_multiple
     */
    public string
        $url,
        $collection,
        $retrieve,
        $sort,
        $img,
        $img_multiple,
        $lang;

    /**
     * @var array $imgData
     * @var array $filter
     */
    public array
        $imgData,
        $filter,
        $tags,
        $tagsData;

    /**
     * frontend_controller_webservice constructor.
     * @param ?frontend_model_template $t
     */
    public function __construct(?frontend_model_template $t = null) {
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this);
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->xml = new component_xml_output();
        $this->dateFormat = new date_dateformat();
        $this->routingUrl = new component_routing_url();
        $this->upload = new component_files_upload();
        $this->imagesComponent = new component_files_images($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->collectionDomain = new frontend_model_domain($this->template);
        $this->ws = new frontend_model_webservice();
        $this->buildCollection = new frontend_model_collection($this->template);
        $this->buildPlugins = new frontend_model_plugins();
        // Databases
        $this->DBHome = new frontend_db_home();
        $this->DBPages = new frontend_db_pages();
        $this->DBNews = new frontend_db_news();
        $this->DBCatalog = new frontend_db_catalog();
        $this->DBCategory = new frontend_db_category();
        $this->DBProduct = new frontend_db_product();
        $this->lang = $this->template->lang;

        $this->url = http_url::getUrl();
        if (http_request::isGet('id')) $this->id = form_inputEscape::numeric($_GET['id']);
        if (http_request::isGet('collection')) $this->collection = form_inputEscape::simpleClean($_GET['collection']);
        if (http_request::isGet('retrieve')) $this->retrieve = form_inputEscape::simpleClean($_GET['retrieve']);
        if(http_request::isGet('sort')) $this->sort = form_inputEscape::simpleClean($_GET['sort']);
        if(http_request::isGet('filter')) $this->filter = form_inputEscape::arrayClean($_GET['filter']);
        if (http_request::isGet('tag')) $this->tag = form_inputEscape::numeric($_GET['tag']);
        if (http_request::isGet('tags')) $this->tags = form_inputEscape::arrayClean($_GET['tags']);

        // --- Image Upload
        if(isset($_FILES['img']["name"])) $this->img = http_url::clean($_FILES['img']["name"]);
        // --- MultiImage Upload
        if (isset($_FILES['img_multiple']["name"])) $this->img_multiple = ($_FILES['img_multiple']["name"]);
        if (http_request::isPost('data')) {
            $this->imgData = [];
            parse_str($_POST['data'],$this->imgData);
        }
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param array|int|null $id
     * @param ?string $context
     * @param bool|string $assign
     * @return mixed
     */
    private function getItems(string $type, $id = null, ?string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    /**
     * @return string
     */
    public function setWsAuthKey(): string {
        $data = $this->getItems('auth',null,'one',false);
        if(!empty($data) && !empty($data['key_ws']) && $data['status_ws']) return $data['key_ws'];
        return '';
    }

    // ############## GET
    /**
     * Global Root
     */
    private function getBuildRootData() {
        $module = ['domain','languages','home'];
        $this->xml->newStartElement('modules');
        foreach($module as $key) {
            $this->xml->setElement([
                'start' => 'module',
                'attrNS' => [[
                    'prefix' => 'xlink',
                    'name' => 'href',
                    'uri' => $this->url . '/webservice/'.$key.'/'
                ]]
            ]);
        }
        $moduleIso = ['pages','news','catalog'];
        foreach($moduleIso as $key) {
            $this->xml->setElement([
                'start' => 'module',
                'attrNS' => [[
                    'prefix' => 'xlink',
                    'name' => 'href',
                    'uri' => $this->url .'/'.$this->lang.'/webservice/'.$key.'/'
                ]]
            ]);
        }
        $plugins = $this->buildPlugins->setWebserviceItems();
        if(!empty($plugins)) {
            foreach ($plugins as $key) {
                $this->xml->setElement([
                    'start' => 'module',
                    'attrNS' => [[
                        'prefix' => 'xlink',
                        'name' => 'href',
                        'uri' => $this->url . '/webservice/plugin/' . $key . '/'
                    ]]
                ]);
            }
        }
        $this->xml->newEndElement();
        $this->xml->output();
    }

    /**
     * Build language Data
     */
    private function getBuildLanguageData() {
        $collection = $this->collectionLanguage->fetchData(['context' => 'all', 'type' => 'langs']);
        $this->xml->newStartElement('languages');
        foreach($collection as $key) {
            $this->xml->newStartElement('language');
            $this->xml->setElement([
                'start' => 'id_lang',
                'text' => $key['id_lang']
            ]);
            $this->xml->setElement([
                'start' => 'iso_lang',
                'text' => $key['iso_lang']
            ]);
            $this->xml->setElement([
                'start' => 'name_lang',
                'text' => $key['name_lang']
            ]);
            $this->xml->setElement([
                'start' => 'default_lang',
                'text' => $key['default_lang']
            ]);
            $this->xml->newEndElement();
        }
        $this->xml->newEndElement();
        $this->xml->output();
    }

    /**
     * Build language Data
     */
    private function getBuildDomainData() {
        $collection = $this->collectionDomain->getValidDomains();
        $this->xml->newStartElement('domains');
        foreach($collection as $key) {
            $this->xml->newStartElement('domain');
            $this->xml->setElement([
                'start' => 'id_domain',
                'text' => $key['id_domain']
            ]);
            $this->xml->setElement([
                'start' => 'url_domain',
                'text' => $key['url_domain']
            ]);
            $this->xml->setElement([
                'start' => 'default_domain',
                'text' => $key['default_domain']
            ]);
            $this->xml->newEndElement();
        }
        $this->xml->newEndElement();
        $this->xml->output();
    }

    /**
     * Build Home Data (EDIT)
     */
    private function getBuildHomeData() {
        // Collection
        $collection = $this->DBHome->fetchData(['context' => 'all', 'type' => 'pages']);
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
    private function getBuildPagesItems() {
        $pages = new frontend_controller_pages();
        //$collection = $this->DBPages->fetchData(['context' => 'all', 'type' => 'pages','conditions'=>null]);
        $arr = $pages->getPagesList();//$this->buildCollection->getBuildPages($collection);
        //print_r($arr);
        $this->xml->newStartElement('pages');

        foreach($arr as $value) {
            $this->xml->newStartElement('page');
            $this->xml->setElement(
                array(
                    'start' => 'id',
                    'text' => $value['id']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'id_parent',
                    'text' => $value['id_parent']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'name',
                    'text' => $value['name']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'iso',
                    'text' => $value['iso']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'url',
                    'text' => $value['url']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'resume',
                    'text' => $value['resume']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'content',
                    'cData' => $value['content']
                )
            );

            $this->xml->newStartElement('seo');
            $this->xml->setElement(
                array(
                    'start' => 'title',
                    'text' => $value['seo']['title']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'description',
                    'cData' => $value['seo']['description']
                )
            );
            $this->xml->newEndElement();
            /*if(isset($value['imgSrc'])) {
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
            }*/
            if(isset($value['img']) && isset($value['img']['name'])) {
                $this->xml->newStartElement('images');
                $this->xml->newStartElement('image');
                $this->xml->setElement(
                    array(
                        'start' => 'name',
                        'text' => $value['img']['name']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'small',
                        'attrNS' => array(
                            array(
                                'prefix' => 'xlink',
                                'name' => 'href',
                                'uri' => $value['img']['small']['src']
                            )
                        )
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'medium',
                        'attrNS' => array(
                            array(
                                'prefix' => 'xlink',
                                'name' => 'href',
                                'uri' => $value['img']['medium']['src']
                            )
                        )
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'large',
                        'attrNS' => array(
                            array(
                                'prefix' => 'xlink',
                                'name' => 'href',
                                'uri' => $value['img']['large']['src']
                            )
                        )
                    )
                );
                // End loop image
                $this->xml->newEndElement();
                // End images
                $this->xml->newEndElement();
                /*foreach ($value['images'] as $k) {
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
                $this->xml->newEndElement();*/
            }
            // Start languages loop
            /*$this->xml->newStartElement('languages');
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
            $this->xml->newEndElement();*/
            // End languages
            $this->xml->newEndElement();
        }
        $this->xml->newEndElement();
        $this->xml->output();
    }

    /**
     * Build Pages Data (EDIT)
     */
    private function getBuildPagesData() {
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
            /*if(isset($value['imgSrc'])) {
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
            }*/
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
    private function getBuildNewsItems() {
        /*$collection = $this->DBNews->fetchData(
            array('context' => 'all', 'type' => 'pages', 'conditions' => null)
        );*/
        $news = new frontend_controller_news();
        $news->getBuildTagList();
        $arr = $news->getNewsList();//$this->buildCollection->getBuildNews($collection);
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
                    'text' => $value['id']
                )
            );
            /*$this->xml->setElement(
                array(
                    'start' => 'id_parent',
                    'text' => $value['id_parent']
                )
            );*/
            $this->xml->setElement(
                array(
                    'start' => 'name',
                    'text' => $value['name']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'iso',
                    'text' => $value['iso']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'url',
                    'text' => $value['url']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'resume',
                    'text' => $value['resume']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'content',
                    'cData' => $value['content']
                )
            );

            $this->xml->newStartElement('seo');
            $this->xml->setElement(
                array(
                    'start' => 'title',
                    'text' => $value['seo']['title']
                )
            );
            $this->xml->setElement(
                array(
                    'start' => 'description',
                    'cData' => $value['seo']['description']
                )
            );
            $this->xml->newEndElement();
            if(isset($value['img']) && isset($value['img']['name'])) {
                $this->xml->newStartElement('images');
                $this->xml->newStartElement('image');
                $this->xml->setElement(
                    array(
                        'start' => 'name',
                        'text' => $value['img']['name']
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'small',
                        'attrNS' => array(
                            array(
                                'prefix' => 'xlink',
                                'name' => 'href',
                                'uri' => $value['img']['small']['src']
                            )
                        )
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'medium',
                        'attrNS' => array(
                            array(
                                'prefix' => 'xlink',
                                'name' => 'href',
                                'uri' => $value['img']['medium']['src']
                            )
                        )
                    )
                );
                $this->xml->setElement(
                    array(
                        'start' => 'large',
                        'attrNS' => array(
                            array(
                                'prefix' => 'xlink',
                                'name' => 'href',
                                'uri' => $value['img']['large']['src']
                            )
                        )
                    )
                );
                // End loop image
                $this->xml->newEndElement();
                // End images
                $this->xml->newEndElement();
                /*foreach ($value['images'] as $k) {
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
                $this->xml->newEndElement();*/
            }
            // Start tags
            $this->xml->newStartElement('tags');
            if(is_array($value['tags'])) {
                foreach ($value['tags'] as $tags => $tag) {
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
            /*if(isset($value['imgSrc'])) {
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
            }*/
            // Start languages loop
            /*$this->xml->newStartElement('languages');
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
            $this->xml->newEndElement();*/
            // End languages
            $this->xml->newEndElement();
        }
        $this->xml->newEndElement();
        $this->xml->output();

    }

    /**
     * Build Pages Data (EDIT)
     */
    private function getBuildNewsData() {
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
    private function getBuildCatalogData() {
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
    private function getBuildCategoryItems() {
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
    private function getBuildCategoryData() {
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

    /**
     * @return void
     */
    public function getBuildProductItems() {
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
    public function getBuildProductData() {
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
    public function parse(bool $debug = false) {
        return $this->ws->setParseData($debug);
    }

    /**
     * Ajout et Mise a jour des données
     * @param array $operation
     * @param array $arrData
     * @throws Exception
     */
    private function getBuildSave(array $operation, array $arrData) {
        switch($operation['type']) {
            case 'home':
                $fetchRootData = $this->DBHome->fetchData(['context' => 'one', 'type' => 'root']);
                if($fetchRootData != null) {
                    $id_page = $fetchRootData['id_page'];
                }
                else {
                    $this->DBHome->insert(['type'=>'newHome']);
                    $newData = $this->DBHome->fetchData(['context'=>'one','type'=>'root']);
                    $id_page = $newData['id_page'];
                }

                if($id_page) {
                    if(!array_key_exists('0',$arrData['language'])) {
                        $content = $arrData['language'];

                        $data = [
                            'title_page' => !is_array($content['name']) ? $content['name'] : NULL,
                            'content_page' => !is_array($content['content']) ? $content['content'] : NULL,
                            'seo_title_page' => !is_array($content['seo']['title']) ? $content['seo']['title'] : NULL,
                            'seo_desc_page' => !is_array($content['seo']['description']) ? $content['seo']['description'] : NULL,
                            'published' => $content['published'],
                            'id_page' => $id_page,
                            'id_lang' => $content['id_lang']
                        ];

                        if ($this->DBHome->fetchData(['context' => 'one', 'type' => 'content'], ['id_page' => $id_page, 'id_lang' => $content['id_lang']]) != null) {
                            $this->DBHome->update(['type' => 'content'], $data);
                        }
                        else {
                            $this->DBHome->insert(['type' => 'newContent'], $data);
                        }
                    }
                    else {
                        foreach ($arrData['language'] as $lang => $content) {
                            //$content['published'] = (!isset($content['published']) ? 0 : 1);
                            $data = [
                                'title_page' => !is_array($content['name']) ? $content['name'] : NULL,
                                'content_page' => !is_array($content['content']) ? $content['content'] : NULL,
                                'seo_title_page' => !is_array($content['seo']['title']) ? $content['seo']['title'] : NULL,
                                'seo_desc_page' => !is_array($content['seo']['description']) ? $content['seo']['description'] : NULL,
                                'published' => $content['published'],
                                'id_page' => $id_page,
                                'id_lang' => $content['id_lang']
                            ];

                            if ($this->DBHome->fetchData(['context' => 'one', 'type' => 'content'], ['id_page' => $id_page, 'id_lang' => $content['id_lang']]) != null) {
                                $this->DBHome->update(['type' => 'content'], $data);
                            }
                            else {
                                $this->DBHome->insert(['type' => 'newContent'], $data);
                            }
                        }
                    }
                    //print_r($arrData['language']);
                    $this->header->set_json_headers();
                    $this->message->json_post_response(true, null, ['id' => $id_page]);
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
                }
                else {
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
                            'resume_pages'      => !is_array($content['resume']) ? $content['resume'] : NULL,
                            'content_pages'     => !is_array($content['content']) ? $content['content'] : NULL,
                            'seo_title_pages'   => !is_array($content['seo']['title']) ? $content['seo']['title'] : NULL,
                            'seo_desc_pages'    => !is_array($content['seo']['description']) ? $content['seo']['description'] : NULL,
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
                                'resume_pages'      => !is_array($content['resume']) ? $content['resume'] : NULL,
                                'content_pages'     => !is_array($content['content']) ? $content['content'] : NULL,
                                'seo_title_pages'   => !is_array($content['seo']['title']) ? $content['seo']['title'] : NULL,
                                'seo_desc_pages'    => !is_array($content['seo']['description']) ? $content['seo']['description'] : NULL,
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
                            'content_news'      => !is_array($content['content']) ? $content['content'] : NULL,
                            'resume_news'       => !is_array($content['resume']) ? trim($content['resume']) : NULL,
                            'seo_title_news'    => !is_array($content['seo']['title']) ? $content['seo']['title'] : NULL,
                            'seo_desc_news'     => !is_array($content['seo']['description']) ? $content['seo']['description'] : NULL,
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
                                'content'       => !is_array($content['content']) ? $content['content'] : NULL,
                                'seo_title'     => !is_array($content['seo']['title']) ? $content['seo']['title'] : NULL,
                                'seo_desc'      => !is_array($content['seo']['description']) ? $content['seo']['description'] : NULL,
                                'id_lang'       => $content['id_lang']
                            )
                        );
                    } else {
                        $this->DBCatalog->insert(array('type' => 'newContent'), array(
                                'name'          => !is_array($content['name']) ? $content['name'] : NULL,
                                'content'       => !is_array($content['content']) ? $content['content'] : NULL,
                                'seo_title'     => !is_array($content['seo']['title']) ? $content['seo']['title'] : NULL,
                                'seo_desc'      => !is_array($content['seo']['description']) ? $content['seo']['description'] : NULL,
                                'id_lang'       => $content['id_lang']
                            )
                        );
                    }

                }else{
                    foreach ($arrData['language'] as $lang => $content) {
                        if ($this->DBCatalog->fetchData(array('context' => 'one', 'type' => 'root'), array('id_lang' => $content['id_lang'])) != null) {
                            $this->DBCatalog->update(array('type' => 'content'), array(
                                    'name'          => !is_array($content['name']) ? $content['name'] : NULL,
                                    'content'       => !is_array($content['content']) ? $content['content'] : NULL,
                                    'seo_title'     => !is_array($content['seo']['title']) ? $content['seo']['title'] : NULL,
                                    'seo_desc'      => !is_array($content['seo']['description']) ? $content['seo']['description'] : NULL,
                                    'id_lang'       => $content['id_lang']
                                )
                            );
                        } else {
                            $this->DBCatalog->insert(array('type' => 'newContent'), array(
                                    'name'      => !is_array($content['name']) ? $content['name'] : NULL,
                                    'content'   => !is_array($content['content']) ? $content['content'] : NULL,
                                    'seo_title' => !is_array($content['seo']['title']) ? $content['seo']['title'] : NULL,
                                    'seo_desc'  => !is_array($content['seo']['description']) ? $content['seo']['description'] : NULL,
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
                            'resume_cat'    => !is_array($content['resume']) ? trim($content['resume']) : NUll,
                            'content_cat'   => !is_array($content['content']) ? $content['content'] : NUll,
                            'seo_title_cat' => !is_array($content['seo']['title']) ? $content['seo']['title'] : NUll,
                            'seo_desc_cat'  => !is_array($content['seo']['description']) ? $content['seo']['description'] : NUll,
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
                                'resume_cat' => !is_array($content['resume']) ? trim($content['resume']) : NUll,
                                'content_cat' => !is_array($content['content']) ? $content['content'] : NUll,
                                'seo_title_cat' => !is_array($content['seo']['title']) ? $content['seo']['title'] : NUll,
                                'seo_desc_cat'  => !is_array($content['seo']['description']) ? $content['seo']['description'] : NUll,
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
                            $this->DBProduct->update(array('type'=>'product'),array(':price_p' => empty($arrData['price']) ? NULL : $arrData['price'],':reference_p' => empty($arrData['reference']) ? NULL : $arrData['reference'],':id_product'=>$id_product));
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
                                'longname_p'    => !is_array($content['longname']) ? $content['longname'] : NUll,
                                'resume_p'      => !is_array($content['resume']) ? trim($content['resume']) : NUll,
                                'content_p'     => !is_array($content['content']) ? $content['content'] : NUll,
                                'seo_title_p'   => !is_array($content['seo']['title']) ? $content['seo']['title'] : NUll,
                                'seo_desc_p'    => !is_array($content['seo']['description']) ? $content['seo']['description'] : NUll,
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
                                    'longname_p'    => !is_array($content['longname']) ? $content['longname'] : NUll,
                                    'resume_p'      => !is_array($content['resume']) ? trim($content['resume']) : NUll,
                                    'content_p'     => !is_array($content['content']) ? $content['content'] : NUll,
                                    'seo_title_p' => !is_array($content['seo']['title']) ? $content['seo']['title'] : NUll,
                                    'seo_desc_p'  => !is_array($content['seo']['description']) ? $content['seo']['description'] : NUll,
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
     * @param array $operation
     * @param array $arrData
     */
    private function getBuildRemove(array $operation, array $arrData) {
        $makeFiles = new filesystem_makefile();
        switch($operation['type']){
            case 'pages':
                //$fetchConfig = $this->imagesComponent->getConfigItems('pages','page');
                //$imgPrefix = $this->imagesComponent->prefix();
                if($arrData['id'] != null) {
                    if (is_array($arrData['id'])) {
                        foreach ($arrData['id'] as $key => $value) {
                            $fetchImg[$key] = $this->DBPages->fetchData(['context' => 'one', 'type' => 'image'], ['id_pages' => $value]);

                            if ($fetchImg[$key] != null) {
                                $imgPath[$key] = component_core_system::basePath() . 'upload/pages/' . $value;
                                // Supprime le dossier des images et les fichiers
                                if (file_exists($imgPath[$key])) $makeFiles->remove([$imgPath[$key]]);
                            }
                        }
                        $this->DBPages->delete(
                            ['type' => 'delPages'],
                            ['id' => implode(",", $arrData['id'])]
                        );
                    }
                    else {
                        $fetchImg = $this->DBPages->fetchData(['context' => 'one', 'type' => 'image'], ['id_pages' => $arrData['id']]);

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
                            if (file_exists($imgPath)) $makeFiles->remove([$imgPath]);
                        }
                        $this->DBPages->delete(
                            ['type' => 'delPages'],
                            ['id' => $arrData['id']]
                        );
                    }
                }
                break;
            case 'news':
                if (isset($this->id)) {
                    foreach ($arrData['language'] as $content) {
                        if ($content['tag'] != null) {
                            if (is_array($content['tag'])) {
                                foreach ($content['tag'] as $value) {
                                    $setTags = $this->DBNews->fetchData(
                                        ['context' => 'one', 'type' => 'tag_ws'],
                                        [':id_news' => $this->id, ':id_lang' => $content['id_lang'], ':name_tag' => $value]
                                    );
                                    if ($setTags['id_tag'] != null && $setTags['rel_tag'] != null) {
                                        $this->DBNews->delete(['type' => 'tagRel'], ['id_rel' => $setTags['rel_tag']]);
                                    }
                                }
                            }
                            else{
                                $setTags = $this->DBNews->fetchData(
                                    ['context' => 'one', 'type' => 'tag_ws'],
                                    [':id_news' => $this->id, ':id_lang' => $content['id_lang'], ':name_tag' => $content['tag']]
                                );
                                if ($setTags['id_tag'] != null && $setTags['rel_tag'] != null) {
                                    $this->DBNews->delete(['type' => 'tagRel'], ['id_rel' => $setTags['rel_tag']]);
                                }
                            }
                        }
                    }
                }
                else {
                    //$fetchConfig = $this->imagesComponent->getConfigItems('news', 'news');
                    //$imgPrefix = $this->imagesComponent->prefix();
                    if ($arrData['id'] != null) {
                        if (is_array($arrData['id'])) {
                            foreach ($arrData['id'] as $key => $value) {
                                $fetchImg[$key] = $this->DBNews->fetchData(['context' => 'one', 'type' => 'image'], ['id_news' => $value]);

                                if ($fetchImg[$key] != null) {
                                    $imgPath[$key] = component_core_system::basePath() . 'upload/news/' . $value;
                                    // Supprime le dossier des images et les fichiers
                                    if (file_exists($imgPath[$key])) $makeFiles->remove([$imgPath[$key]]);
                                }
                            }
                            $this->DBNews->delete(
                                ['type' => 'delPages'],
                                ['id' => implode(",", $arrData['id'])]
                            );
                        }
                        else {
                            $fetchImg = $this->DBNews->fetchData(['context' => 'one', 'type' => 'image'], ['id_news' => $arrData['id']]);
                            if ($fetchImg != null) {
                                $imgPath = component_core_system::basePath() . 'upload/news/' . $arrData['id'];

                                // Supprime le dossier des images et les fichiers
                                if (file_exists($imgPath)) {
                                    $makeFiles->remove([$imgPath]);
                                }
                            }
                            $this->DBNews->delete(
                                ['type' => 'delPages'],
                                ['id' => $arrData['id']]
                            );
                        }
                    }
                }
                break;
            case 'category':
                //$fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'catalog','attribute_img'=>'category'));
                //$imgPrefix = $this->imagesComponent->prefix();
                if($arrData['id'] != null) {
                    if (is_array($arrData['id'])) {
                        foreach ($arrData['id'] as $key => $value) {
                            $fetchImg[$key] = $this->DBCategory->fetchData(['context' => 'one', 'type' => 'image'], ['id_cat' => $value]);

                            if ($fetchImg[$key] != null) {
                                $imgPath[$key] = component_core_system::basePath() . 'upload/catalog/category/' . $value;
                                // Supprime le dossier des images et les fichiers
                                if (file_exists($imgPath[$key])) $makeFiles->remove([$imgPath[$key]]);
                            }
                        }
                        $this->DBCategory->delete(
                            ['type' => 'delPages'],
                            ['id' => implode(",", $arrData['id'])]
                        );
                    }
                    else {
                        $fetchImg = $this->DBCategory->fetchData(['context' => 'one', 'type' => 'image'], ['id_cat' => $arrData['id']]);

                        if ($fetchImg != null) {
                            $imgPath = component_core_system::basePath() . 'upload/catalog/category/' . $arrData['id'];
                            // Supprime le dossier des images et les fichiers
                            if (file_exists($imgPath)) $makeFiles->remove([$imgPath]);
                        }
                        $this->DBCategory->delete(
                            ['type' => 'delPages'],
                            ['id' => $arrData['id']]
                        );

                    }
                }
                break;
            case 'product':
                if ($arrData['id'] != null) {
                    $setImgDirectory = NULL;
                    if (is_array($arrData['id'])) {
                        // ----- Remove Image All for replace from Webservice
                        $this->DBProduct->delete(['type' => 'delImagesAll'], ['id' => implode(",", $arrData['id'])]);
                        $makeFiles = new filesystem_makefile();
                        $finder = new file_finder();
                        foreach ($arrData['id'] as $value) {
                            $setImgDirectory = $this->routingUrl->dirUpload('upload/catalog/product/'.$value, true);
                        }
                        $this->DBProduct->delete(
                            ['type' => 'delPages'],
                            ['id' => implode(",", $arrData['id'])]
                        );
                    }
                    else{
                        // ----- Remove Image All for replace from Webservice
                        $this->DBProduct->delete(['type' => 'delImagesAll'], ['id' => $arrData['id']]);
                        $makeFiles = new filesystem_makefile();
                        $finder = new file_finder();
                        $setImgDirectory = $this->routingUrl->dirUpload('upload/catalog/product/'.$arrData['id'], true);
                        $this->DBProduct->delete(
                            ['type' => 'delPages'],
                            ['id' => $arrData['id']]
                        );
                    }
                    if(file_exists($setImgDirectory) && $setImgDirectory != NULL){
                        $setFiles = $finder->scanDir($setImgDirectory);
                        if($setFiles != null){
                            foreach($setFiles as $file){
                                $makeFiles->remove($setImgDirectory.$file);
                            }
                        }
                    }
                }
                break;
        }
    }

    /**
     * Set parse data from XML OR JSON
     * @param array $operations
     */
    private function getBuildParse(array $operations) {
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
                    switch ($this->ws->setMethod()) {
                        case 'GET':
                            //print $getContentType;
                            $this->xml->getXmlHeader();
                            $this->getBuildHomeData();
                            break;
                        case 'PUT':
                            if($getContentType === 'xml') {
                                $arrData = json_decode(json_encode($this->parse()), true);
                                $this->getBuildSave($operations,$arrData);
                            }
                            break;
                        case 'POST':
                            if($getContentType === 'xml'){
                                $arrData = json_decode(json_encode($this->parse()), true);
                                $this->getBuildSave($operations,$arrData);
                            }
                            elseif($getContentType === 'json'){
                                $arrData = json_decode(json_encode($this->parse()), true);
                                $this->getBuildSave($operations,$arrData);
                            }
                            break;
                    }
                    break;
                case 'pages':
                    switch ($this->ws->setMethod()) {
                        case 'GET':
                            $this->xml->getXmlHeader();
                            if (isset($this->id)) {
                                $this->getBuildPagesData();
                            }
                            else {
                                $this->getBuildPagesItems();
                            }
                            break;
                        case 'PUT':
                            if($getContentType === 'xml') {
                                $arrData = json_decode(json_encode($this->parse()), true);
                                $this->getBuildSave($operations,$arrData);
                            }
                            break;
                        case 'POST':
                            switch ($getContentType) {
                                case 'xml':
                                case 'json':
                                    $arrData = json_decode(json_encode($this->parse()), true);
                                    $this->getBuildSave($operations,$arrData);
                                    break;
                                case 'files':
                                    if (isset($this->id)) {
                                        $defaultLanguage = $this->collectionLanguage->fetchData(['context' => 'one', 'type' => 'default']);
                                        $page = $this->DBPages->fetchData(['context' => 'one', 'type' => 'pageLang'], ['id' => $this->id, 'iso' => $defaultLanguage['iso_lang']]);
                                        $newimg = $this->DBPages->fetchData(['context' => 'one', 'type' => 'lastImgId'],['id_pages' => $this->id]);

                                        // ----- Remove Image All for replace from Webservice
                                        $this->DBPages->delete(['type' => 'delImagesAll'], ['id' => $this->id]);
                                        $makeFiles = new filesystem_makefile();
                                        $finder = new file_finder();

                                        /*$setImgDirectory = $this->upload->dirImgUpload(
                                            array_merge(
                                                array('upload_root_dir'=>'upload/pages/'.$this->id),
                                                array('imgBasePath'=>true)
                                            )
                                        );*/

                                        $setImgDirectory = $this->routingUrl->dirUpload('upload/pages/'.$this->id,true);

                                        if(file_exists($setImgDirectory)){
                                            $setFiles = $finder->scanDir($setImgDirectory);
                                            if($setFiles != null) {
                                                foreach($setFiles as $file) {
                                                    $makeFiles->remove($setImgDirectory.$file);
                                                }
                                            }
                                        }
                                        // -----------
                                        $newData = [];
                                        foreach($_FILES as $key => $value){
                                            $newData['name'][$key] = $value['name'];
                                            $imageInfo = getimagesize( $value['tmp_name'] );
                                            $newData['type'][$key] = $imageInfo['mime'];
                                            $newData['tmp_name'][$key] = $value['tmp_name'];
                                            $newData['error'][$key] = $value['error'];
                                            $newData['size'][$key] = $value['size'];
                                        }
                                        $_FILES = ['img_multiple' => $newData];

                                        // If $newimg = NULL return 0
                                        $newimg['index'] = $newimg['index'] ?? 0;
                                        //$img_multiple = $newData;
                                        $this->img_multiple = ($_FILES['img_multiple']["name"]);
                                        $this->upload = new component_files_upload();
                                        /*$resultUpload = $this->upload->setMultipleImageUpload(
                                            'img_multiple',
                                            array(
                                                'name' => $page['url_pages'],
                                                'prefix_name' => $newimg['id_img'],
                                                'prefix_increment' => true,
                                                'prefix' => array('s_', 'm_', 'l_'),
                                                'module_img' => 'pages',
                                                'attribute_img' => 'page',
                                                'original_remove' => false
                                            ),
                                            array(
                                                'upload_root_dir' => 'upload/pages', //string
                                                'upload_dir' => $this->id //string ou array
                                            ),
                                            false
                                        );*/

                                        $resultUpload = $this->upload->multipleImageUpload('pages','pages','upload/pages',["$this->id"],[
                                            'name' => $page['url_pages'],
                                            'suffix' => (int)$newimg['index'],
                                            'suffix_increment' => true
                                        ],false);

                                        if ($resultUpload != null) {
                                            foreach ($resultUpload as $value) {
                                                if ($value['status'] == '1') {
                                                    $this->DBPages->insert(
                                                        ['type' => 'newImg'], [
                                                            'id_pages' => $this->id,
                                                            'name_img' => $value['file']
                                                        ]
                                                    );
                                                }
                                            }
                                        }
                                    }
                                    break;
                            }
                            break;
                        case 'DELETE':
                            //print_r($this->parse());
                            if($getContentType === 'xml') {
                                $arrData = json_decode(json_encode($this->parse()), true);
                                $this->getBuildRemove($operations,$arrData);
                            }
                            elseif($getContentType === 'json') {
                                //{"id":[53,54]}
                                $arrData = json_decode(json_encode($this->parse()), true);
                                $this->getBuildRemove($operations,$arrData);
                            }
                            //$this->header->set_json_headers();
                            //$this->message->json_post_response(true,'delete',$del);
                            break;
                    }
                    break;
                case 'news':
                    switch ($this->ws->setMethod()) {
                        case 'GET':
                            $this->xml->getXmlHeader();
                            if (isset($this->id)) {
                                $this->getBuildNewsData();
                            }
                            else {
                                $this->getBuildNewsItems();
                            }
                            break;
                        case 'PUT':
                            if($getContentType === 'xml') {
                                $arrData = json_decode(json_encode($this->parse()), true);
                                $this->getBuildSave($operations,$arrData);
                            }
                            break;
                        case 'POST':
                            switch ($getContentType) {
                                case 'xml':
                                case 'json':
                                    $arrData = json_decode(json_encode($this->parse()), true);
                                    $this->getBuildSave($operations,$arrData);
                                    break;
                                case 'files':
                                    if (isset($this->id)) {
                                        $defaultLanguage = $this->collectionLanguage->fetchData(['context' => 'one', 'type' => 'default']);
                                        $page = $this->DBNews->fetchData(['context' => 'one', 'type' => 'pageLang'], ['id' => $this->id, 'iso' => $defaultLanguage['iso_lang']]);
                                        $resultUpload = $this->upload->imageUpload('news','news','upload/news',["$this->id"],['name' => $page['url_news'] !== '' ? $page['url_news'] : $page['url_news']],false);
                                        $this->DBNews->update(
                                            ['type' => 'img'],[
                                                'id_news' => $this->id,
                                                'img_news' => $resultUpload['file']
                                            ]
                                        );
                                    }
                                    break;
                            }
                            break;
                        case 'DELETE':
                            switch ($getContentType) {
                                case 'xml':
                                case 'json':
                                    $arrData = json_decode(json_encode($this->parse()), true);
                                    $this->getBuildRemove($operations,$arrData);
                                    break;
                            }
                            break;
                    }
                    break;
                case 'catalog':
                    if($this->ws->setMethod() === 'GET') {
                        $this->xml->getXmlHeader();
                        $this->getBuildCatalogData();
                    }
                    elseif($this->ws->setMethod() === 'POST') {
                        if ($getContentType === 'xml' || $getContentType === 'json') {
                            $arrData = json_decode(json_encode($this->parse()), true);
                            $this->getBuildSave($operations, $arrData);
                        }
                    }
                    break;
                case 'category':
                    switch ($this->ws->setMethod()) {
                        case 'GET':
                            $this->xml->getXmlHeader();
                            if (isset($this->id)) {
                                $this->getBuildCategoryData();
                            }
                            else {
                                $this->getBuildCategoryItems();
                            }
                            break;
                        case 'PUT':
                            if($getContentType === 'xml') {
                                $arrData = json_decode(json_encode($this->parse()), true);
                                $this->getBuildSave($operations,$arrData);
                            }
                            break;
                        case 'POST':
                            switch ($getContentType) {
                                case 'xml':
                                case 'json':
                                    $arrData = json_decode(json_encode($this->parse()), true);
                                    $this->getBuildSave($operations,$arrData);
                                    break;
                                case 'files':
                                    if (isset($this->id)) {
                                        $defaultLanguage = $this->collectionLanguage->fetchData(['context' => 'one', 'type' => 'default']);
                                        $page = $this->DBCategory->fetchData(['context' => 'one', 'type' => 'pageLang'], ['id' => $this->id, 'iso' => $defaultLanguage['iso_lang']]);
                                        $resultUpload = $this->upload->imageUpload('catalog','category','upload/catalog/category',["$this->id"],['name' => $page['url_cat'] !== '' ? $page['url_cat'] : $page['url_cat']],false);
                                        $this->DBCategory->update(
                                            ['type' => 'img'],[
                                                'id_cat' => $this->id,
                                                'img_cat' => $resultUpload['file']
                                            ]
                                        );
                                    }
                                    break;
                            }
                            break;
                        case 'DELETE':
                            if($getContentType === 'xml' || $getContentType === 'json') {
                                $arrData = json_decode(json_encode($this->parse()), true);
                                $this->getBuildRemove($operations,$arrData);
                            }
                            break;
                    }
                    break;
                case 'product':
                    switch ($this->ws->setMethod()) {
                        case 'GET':
                            $this->xml->getXmlHeader();
                            if (isset($this->id)) {
                                $this->getBuildProductData();
                            }
                            else {
                                $this->xml->getXmlHeader();
                                $this->getBuildProductItems();
                            }
                            break;
                        case 'PUT':
                            if($getContentType === 'xml') {
                                $arrData = json_decode(json_encode($this->parse()), true);
                                $this->getBuildSave($operations,$arrData);
                            }
                            break;
                        case 'POST':
                            switch ($getContentType) {
                                case 'xml':
                                case 'json':
                                    $arrData = json_decode(json_encode($this->parse()), true);
                                    $this->getBuildSave($operations,$arrData);
                                    break;
                                case 'files':
                                    if (isset($this->id)) {
                                        $defaultLanguage = $this->collectionLanguage->fetchData(['context' => 'one', 'type' => 'default']);
                                        $product = $this->DBProduct->fetchData(['context' => 'one', 'type' => 'content'],['id_product' => $this->id, 'id_lang' => $defaultLanguage['id_lang']]);
                                        $newimg = $this->DBProduct->fetchData(['context' => 'one', 'type' => 'lastImgId'],['id_product' => $this->id]);

                                        // ----- Remove Image All for replace from Webservice
                                        $this->DBProduct->delete(['type' => 'delImagesAll'], ['id' => $this->id]);
                                        $makeFiles = new filesystem_makefile();
                                        $finder = new file_finder();

                                        $setImgDirectory = $this->routingUrl->dirUpload('upload/catalog/p/'.$this->id,true);

                                        if(file_exists($setImgDirectory)){
                                            $setFiles = $finder->scanDir($setImgDirectory);
                                            if($setFiles != null){
                                                foreach($setFiles as $file){
                                                    $makeFiles->remove($setImgDirectory.$file);
                                                }
                                            }
                                        }
                                        // -----------
                                        $newData = [];
                                        foreach($_FILES as $key => $value){
                                            $newData['name'][$key] = $value['name'];
                                            $imageInfo = getimagesize( $value['tmp_name'] );
                                            $newData['type'][$key] = $imageInfo['mime'];
                                            $newData['tmp_name'][$key] = $value['tmp_name'];
                                            $newData['error'][$key] = $value['error'];
                                            $newData['size'][$key] = $value['size'];
                                        }
                                        $_FILES = ['img_multiple' => $newData];

                                        // If $newimg = NULL return 0
                                        $newimg['index'] = $newimg['index'] ?? 0;
                                        //$img_multiple = $newData;
                                        $this->img_multiple = ($_FILES['img_multiple']["name"]);
                                        $this->upload = new component_files_upload();
                                        $resultUpload = $this->upload->multipleImageUpload('catalog','product','upload/catalog/product',["$this->id"],[
                                            'name' => $product['url_p'],
                                            'suffix' => (int)$newimg['index'],
                                            'suffix_increment' => true
                                        ],false);

                                        if ($resultUpload != null) {
                                            foreach ($resultUpload as $value) {
                                                if ($value['status'] == '1') {
                                                    $this->DBProduct->insert(
                                                        ['type' => 'newImg'],[
                                                            'id_product' => $this->id,
                                                            'name_img' => $value['file']
                                                        ]
                                                    );
                                                }
                                            }
                                        }
                                    }
                                    break;
                            }
                            break;
                        case 'DELETE':
                            if($getContentType === 'xml' || $getContentType === 'json') {
                                $arrData = json_decode(json_encode($this->parse()), true);
                                $this->getBuildRemove($operations,$arrData);
                            }
                            break;
                    }
                    break;
            }
        }
        catch (Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     *
     */
    public function run() {
        if ($this->ws->authorization($this->setWsAuthKey())) {
            if (isset($this->collection)) {
                switch ($this->collection) {
                    case 'languages':
                        $this->getBuildParse(['type' => 'languages']);
                        break;
                    case 'domain':
                        $this->getBuildParse(['type' => 'domain']);
                        break;
                    case 'home':
                        $this->getBuildParse(['type' => 'home']);
                        break;
                    case 'pages':
                        $this->getBuildParse(['type' => 'pages']);
                        break;
                    case 'news':
                        $this->getBuildParse(['type' => 'news']);
                        break;
                    case 'catalog':
                        if (isset($this->retrieve)) {
                            if ($this->retrieve == 'category') {
                                $this->getBuildParse(['type' => 'category']);
                            }
                            elseif ($this->retrieve == 'product') {
                                $this->getBuildParse(['type' => 'product']);
                            }
                            return;
                        }
                        else {
                            $this->getBuildParse(['type' => 'catalog']);
                        }
                        break;
                    case 'plugin':
                        if (isset($this->retrieve)) {
                            $this->buildPlugins->getWebserviceItems($this->retrieve);
                        }
                        break;
                }
            }
            else {
                $this->xml->getXmlHeader();
                $this->getBuildRootData();
            }
        }
    }
}