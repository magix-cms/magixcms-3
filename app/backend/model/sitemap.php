<?php
class backend_model_sitemap{
    /**
     * @var xml_sitemap
     */
    protected $xml,$setting,$collectionLanguage,$DBPages,$DBNews,$DBCatalog,$DBPlugins,$template,$modelPlugins,$routingUrl,$configCollection;

    /**
     * backend_model_sitemap constructor.
     * @param stdClass $t
     */
    public function __construct($t)
    {
		$this->template = $t;
		$this->xml = new xml_sitemap();
		$this->setting = new backend_controller_setting($t);
		$this->DBPages = new backend_db_pages();
		$this->DBNews = new backend_db_news();
		$this->DBCatalog = new backend_db_catalog();
		$this->DBPlugins = new backend_db_plugins();
		$this->collectionLanguage = new component_collections_language();
		$this->modelPlugins = new backend_model_plugins();
        $this->routingUrl = new component_routing_url();
        $this->configCollection = new component_collections_config();
    }

    /**
     * Assign data to the defined value
     */
    public function setConfigData(){
        $newArray = array();
        $config = $this->configCollection->fetchData(array('context'=>'all','type'=>'config'));
        foreach($config as $key){
            $newArray[$key['attr_name']] = $key['status'];
        }
        return $newArray;
    }
    /**
     * @param $data
     * @return string
     */
    public function url($data)
    {
        $setting = $this->setting->setItemsData();
        if(is_array($data)){
            if($setting['ssl']==='0'){
                $host = 'http://';
            }else{
                $host = 'https://';
            }

            $domain = $data['domain'];

            return $host.$domain.$data['url'];
        }
    }


    /**
     * Call a callback method for create sitemap with plugins
     * Example :
     *
     * public function setSitemap($config){
     * $dateFormat = new date_dateformat();
     * $url = '/' . $config['iso_lang']. '/'.$config['name'].'/';
     * $this->xml->writeNode(
     * array(
     * 'type'      =>  'child',
     * 'loc'       =>  $this->sitemap->url(array('domain' => $config['domain'], 'url' => $url)),
     * 'image'     =>  false,
     * 'lastmod'   =>  $dateFormat->dateDefine(),
     * 'changefreq'=>  'always',
     * 'priority'  =>  '0.7'
     * )
     * );
     * }
     * @param $config
     * @throws Exception
     */
    private function setPluginsItems($config){
        $data =  $this->DBPlugins->fetchData(array('context'=>'all','type'=>'list'));
        foreach($data as $item){
            if(file_exists(component_core_system::basePath().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$item['name'].DIRECTORY_SEPARATOR.'admin.php')) {
                $class = 'plugins_' . $item['name'] . '_admin';
                if (class_exists($class)) {
                    //Si la méthode sitemap existe
                    if (method_exists($class, 'setSitemap')) {

                        //Call a callback with an array
                        call_user_func_array(
                            array(
                                $this->modelPlugins->getCallClass($class),'setSitemap'
                            ),
                            array(
                                array_merge($config,array('name'=>$item['name']))
                            )
                        );
                    }
                }
            }
        }
    }

    /**
     * @param $config
     * @throws Exception
     */
    public function setItems($config){
        $dateFormat = new date_dateformat();
        $this->template->configLoad();
        usleep(200000);
        $this->progress = new component_core_feedback($this->template);
        $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('control_of_data'),'progress' => 10));
        // LOAD active languages
        $langDomain = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'currentDomain'),array('url'=>$config['domain']));
        $setLang = $this->collectionLanguage->fetchData(array('context'=>'all','type'=>'domain'),array('id'=>$langDomain['id_domain']));
        if($setLang != null){
            $lang = $setLang;
        }else{
            $lang = $this->collectionLanguage->fetchData(array('context'=>'all','type'=>'langs'));
        }
        $newData = array();
        $newBaseXml = array();
        $newImgXml = array();
        // Basepath
        $basePath = component_core_system::basePath();

        usleep(200000);
        $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_sitemap'),'progress' => 30, 'rendering' => true));

        $xmlIndexFiles = $basePath . 'sitemap-' . $config['domain'] . '.xml';

        $this->xml->createNewFile($xmlIndexFiles);
        $this->xml->openUri($xmlIndexFiles);
        /*indente les lignes (optionnel)*/
        $this->xml->setIndent(true);
        /*Ecrit la DTD ainsi que l'entête complète suivi de l'encodage souhaité*/
        $this->xml->headerSitemap(array('encode' => 'UTF-8', 'type' => 'parent'));

        // Chargement de la configuration des mudles Core
        $setConfig = $this->setConfigData();

        if($lang != null) {
            $i = 0;
            // ---- Sitemap Index
            foreach ($lang as $item) {
                $i++;
                $this->xml->writeNode(
                    array(
                        'type' => 'parent',
                        'loc' => $this->url(array('domain' => $config['domain'], 'url' => '/'.$item['iso_lang'] . '-sitemap-' . $config['domain'] . '.xml')),
                        'image' => false,
                        'lastmod' => $dateFormat->dateDefine(),
                        'changefreq' => 'always',
                        'priority' => '0.7'
                    )
                );
                $this->xml->writeNode(
                    array(
                        'type' => 'parent',
                        'loc' => $this->url(array('domain' => $config['domain'], 'url' => '/'.$item['iso_lang'] . '-sitemap-image-' . $config['domain'] . '.xml')),
                        'image' => false,
                        'lastmod' => $dateFormat->dateDefine(),
                        'changefreq' => 'always',
                        'priority' => '0.7'
                    )
                );
            }
            $this->xml->endElement();
            /* ------ Retourne les URLS des sitemaps dans le résultat ######*/
            $parentXML = $this->url(array($config['domain'], 'domain' => $config['domain'], 'url' => '/'. 'sitemap-' . $config['domain'] . '.xml'));

            if($lang != null) {
                foreach ($lang as $key => $value) {
                    $newBaseXml[$key] = $this->url(array('domain' => $config['domain'], 'url' => '/'.$value['iso_lang'] . '-sitemap-' . $config['domain'] . '.xml'));
                    $newImgXml[$key] = $this->url(array('domain' => $config['domain'], 'url' => '/'.$value['iso_lang'] . '-sitemap-image-' . $config['domain'] . '.xml'));
                }
                $newData = array_merge($newBaseXml,$newImgXml);
                array_unshift($newData,$parentXML);
            }

            // ---- ####### --

            $i = 0;
            // ---- Sitemap Image
            foreach ($lang as $item) {
                $i++;
                usleep(200000);
                $progress =  40 + (30/(count($lang)))*($i + 1);
                $xmlFiles = $basePath . $item['iso_lang'] . '-sitemap-image-' . $config['domain'] . '.xml';
                $this->xml->createNewFile($xmlFiles);
                $this->xml->openUri($xmlFiles);
                /*indente les lignes (optionnel)*/
                $this->xml->setIndent(true);
                /*Ecrit la DTD ainsi que l'entête complète suivi de l'encodage souhaité*/
                $this->xml->headerSitemap(array('encode' => 'UTF-8', 'type' => 'image'));
                # WriteNode Pages
                if($setConfig['pages'] != '0') {
                    // Load Data pages
                    $dataPages = $this->DBPages->fetchData(array('context' => 'all', 'type' => 'sitemap'), array('id_lang' => $item['id_lang']));

                    foreach ($dataPages as $key => $value) {
                        $url = '/' . $value['iso_lang'] . '/pages/' . $value['id_pages'] . '-' . $value['url_pages'] . '/';
                        if ($value['img_pages'] != NULL) {
                            $this->xml->writeNode(
                                array(
                                    'type' => 'image',
                                    'loc' => $this->url(array('domain' => $config['domain'], 'url' => $url)),
                                    'image' => array('url' => $this->url(array('domain' => $config['domain'], 'url' => '/upload/pages/' . $value['id_pages'] . '/')), 'imageloc' => $value['img_pages'])
                                )
                            );
                        }
                    }
                }
                #WriteNode News
                if($setConfig['news'] != '0') {
                    // Load Data news
                    $dataNews = $this->DBNews->fetchData(array('context' => 'all', 'type' => 'sitemap'), array('id_lang' => $item['id_lang']));

                    foreach ($dataNews as $key => $value) {
                        $datePublish = $dateFormat->dateToDefaultFormat($value['date_publish']);
                        $url = '/' . $value['iso_lang'] . '/news/' . $datePublish . '/' . $value['id_news'] . '-' . $value['url_news'] . '/';
                        if ($value['img_news'] != NULL) {
                            $this->xml->writeNode(
                                array(
                                    'type' => 'image',
                                    'loc' => $this->url(array('domain' => $config['domain'], 'url' => $url)),
                                    'image' => array('url' => $this->url(array('domain' => $config['domain'], 'url' => '/upload/news/' . $value['id_news'] . '/')), 'imageloc' => $value['img_news'])
                                )
                            );
                        }
                    }
                }
                // WriteNode category catalog
                if($setConfig['catalog'] != '0') {
                    $dataCategory = $this->DBCatalog->fetchData(array('context' => 'all', 'type' => 'category'), array('id_lang' => $item['id_lang']));
                    foreach ($dataCategory as $key => $value) {
                        $url = '/' . $value['iso_lang'] . '/catalog/' . $value['id_cat'] . '-' . $value['url_cat'] . '/';
                        //$newData[$item['iso_lang']][$key] = $this->url(array('domain' => $config['domain'], 'url' => $url));
                        if ($value['img_cat'] != NULL) {
                            $this->xml->writeNode(
                                array(
                                    'type' => 'image',
                                    'loc' => $this->url(array('domain' => $config['domain'], 'url' => $url)),
                                    'image' => array('url' => $this->url(array('domain' => $config['domain'], 'url' => '/upload/catalog/c/' . $value['id_cat'] . '/')), 'imageloc' => $value['img_cat'])
                                )
                            );
                        }
                    }

                    // WriteNode product catalog
                    $newImgArr = array();
                    $dataProduct = $this->DBCatalog->fetchData(array('context' => 'all', 'type' => 'product'), array('id_lang' => $item['id_lang']));
                    foreach ($dataProduct as $key => $value) {
                        $url = '/' . $value['iso_lang'] . '/catalog/' . $value['id_cat'] . '-' . $value['url_cat'] . '/' . $value['id_product'] . '-' . $value['url_p'] . '/';

                        $dataProductImg = $this->DBCatalog->fetchData(array('context' => 'all', 'type' => 'images'), array('id' => $value['id_product']));
                        if ($dataProductImg != null) {
                            // Multi images
                            foreach ($dataProductImg as $img) {
                                $newImgArr[] = $img['name_img'];
                            }
                            //print_r($newImgArr);

                            $this->xml->writeNode(
                                array(
                                    'type' => 'image',
                                    'loc' => $this->url(array('domain' => $config['domain'], 'url' => $url)),
                                    'image' => array(
                                        'url' => $this->url(array('domain' => $config['domain'], 'url' => '/upload/catalog/p/' . $value['id_product'] . '/')),
                                        'loop' => $newImgArr
                                    )
                                )
                            );
                        }
                    }
                }
                $this->xml->endElement();
            }

            $i = 0;
            // ---- Sitemap URL
            foreach ($lang as $item) {
                $i++;
                //usleep(200000);
                $progress =  60 + (30/(count($lang)))*($i + 1);
                $this->progress->sendFeedback(array('progress' => $progress, 'rendering' => true));

                $xmlFiles = $basePath . $item['iso_lang'] . '-sitemap-' . $config['domain'] . '.xml';
                $this->xml->createNewFile($xmlFiles);
                $this->xml->openUri($xmlFiles);
                /*indente les lignes (optionnel)*/
                $this->xml->setIndent(true);
                /*Ecrit la DTD ainsi que l'entête complète suivi de l'encodage souhaité*/
                $this->xml->headerSitemap(array('encode' => 'UTF-8', 'type' => 'child'));

                // WriteNode Root
                $url = '/' . $item['iso_lang'] . '/';
                $this->xml->writeNode(
                    array(
                        'type' => 'child',
                        'loc' => $this->url(array('domain' => $config['domain'], 'url' => $url)),
                        'image' => false,
                        'lastmod' => $dateFormat->dateDefine(),
                        'changefreq' => 'always',
                        'priority' => '0.7'
                    )
                );
                // WriteNode pages
                if($setConfig['pages'] != '0') {
                    // Load Data pages
                    $dataPages = $this->DBPages->fetchData(array('context' => 'all', 'type' => 'sitemap'), array('id_lang' => $item['id_lang']));
                    foreach ($dataPages as $key => $value) {

                        $url = $this->routingUrl->getBuildUrl(array(
                            'type' => 'pages',
                            'iso' => $value['iso_lang'],
                            'id' => $value['id_pages'],
                            'url' => $value['url_pages']
                        ));

                        $this->xml->writeNode(
                            array(
                                'type' => 'child',
                                'loc' => $this->url(array('domain' => $config['domain'], 'url' => $url)),
                                'image' => false,
                                'lastmod' => $value['last_update'],
                                'changefreq' => 'always',
                                'priority' => '0.7'
                            )
                        );
                    }
                }
                // WriteNode News
                if($setConfig['news'] != '0') {
                    // WriteNode Root News
                    $url = '/' . $item['iso_lang'] . '/news/';
                    $this->xml->writeNode(
                        array(
                            'type' => 'child',
                            'loc' => $this->url(array('domain' => $config['domain'], 'url' => $url)),
                            'image' => false,
                            'lastmod' => $dateFormat->dateDefine(),
                            'changefreq' => 'always',
                            'priority' => '0.7'
                        )
                    );

                    // Load Data news
                    $dataNews = $this->DBNews->fetchData(array('context' => 'all', 'type' => 'sitemap'), array('id_lang' => $item['id_lang']));
                    foreach ($dataNews as $key => $value) {

                        $url = $this->routingUrl->getBuildUrl(array(
                                'type' => 'news',
                                'iso' => $value['iso_lang'],
                                'date' => $value['date_publish'],
                                'id' => $value['id_news'],
                                'url' => $value['url_news']
                            )
                        );
                        $this->xml->writeNode(
                            array(
                                'type' => 'child',
                                'loc' => $this->url(array('domain' => $config['domain'], 'url' => $url)),
                                'image' => false,
                                'lastmod' => $value['last_update'],
                                'changefreq' => 'always',
                                'priority' => '0.7'
                            )
                        );
                    }
                    $dataTagsNews = $this->DBNews->fetchData(array('context' => 'all', 'type' => 'tags'), array('id_lang' => $item['id_lang']));

                    foreach ($dataTagsNews as $key => $value) {
                        $url = $this->routingUrl->getBuildUrl(array(
                                'type' => 'tag',
                                'iso' => $item['iso_lang'],
                                'id' => $value['id_tag'],
                                'url' => $value['name_tag']
                            )
                        );
                        $this->xml->writeNode(
                            array(
                                'type' => 'child',
                                'loc' => $this->url(array('domain' => $config['domain'], 'url' => $url)),
                                'image' => false,
                                'lastmod' => $value['last_update'],
                                'changefreq' => 'always',
                                'priority' => '0.7'
                            )
                        );
                    }
                }
                // Load Data catalog
                if($setConfig['catalog'] != '0') {
                    // WriteNode Root catalog
                    $url = '/' . $item['iso_lang'] . '/catalog/';
                    $this->xml->writeNode(
                        array(
                            'type' => 'child',
                            'loc' => $this->url(array('domain' => $config['domain'], 'url' => $url)),
                            'image' => false,
                            'lastmod' => $dateFormat->dateDefine(),
                            'changefreq' => 'always',
                            'priority' => '0.7'
                        )
                    );
                    // WriteNode category catalog
                    $dataCategory = $this->DBCatalog->fetchData(array('context' => 'all', 'type' => 'category'), array('id_lang' => $item['id_lang']));
                    foreach ($dataCategory as $key => $value) {

                        $url = $this->routingUrl->getBuildUrl(array(
                                'type' => 'category',
                                'iso' => $value['iso_lang'],
                                'id' => $value['id_cat'],
                                'url' => $value['url_cat']
                            )
                        );

                        //$newData[$item['iso_lang']][$key] = $this->url(array('domain' => $config['domain'], 'url' => $url));
                        $this->xml->writeNode(
                            array(
                                'type' => 'child',
                                'loc' => $this->url(array('domain' => $config['domain'], 'url' => $url)),
                                'image' => false,
                                'lastmod' => $value['last_update'],
                                'changefreq' => 'always',
                                'priority' => '0.8'
                            )
                        );
                    }
                    // WriteNode product catalog
                    $dataProduct = $this->DBCatalog->fetchData(array('context' => 'all', 'type' => 'product'), array('id_lang' => $item['id_lang']));
                    foreach ($dataProduct as $key => $value) {

                        $url = $this->routingUrl->getBuildUrl(array(
                                'type' => 'product',
                                'iso' => $value['iso_lang'],
                                'id' => $value['id_product'],
                                'url' => $value['url_p'],
                                'id_parent' => $value['id_cat'],
                                'url_parent' => $value['url_cat']
                            )
                        );


                        $this->xml->writeNode(
                            array(
                                'type' => 'child',
                                'loc' => $this->url(array('domain' => $config['domain'], 'url' => $url)),
                                'image' => false,
                                'lastmod' => $value['last_update'],
                                'changefreq' => 'always',
                                'priority' => '0.9'
                            )
                        );
                    }
                }
                $this->setPluginsItems(
                    array(
                        'domain'        => $config['domain'],
                        'id_lang'       => $item['id_lang'],
                        'iso_lang'      => $item['iso_lang'],
                        'default_lang'  => $item['default_lang']
                    )
                );

                $this->xml->endElement();
            }
            $this->template->assign('xmlItems', $newData);
            $display = $this->template->fetch('domain/loop/sitemap.tpl');

            usleep(200000);
            $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_sitemap_success'), 'progress' => 100, 'status' => 'success','result'=>$display));

        }else{
            usleep(200000);
            $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_error'),'progress' => 100,'status' => 'error','error_code' => 'error_data'));

        }
    }
}
?>