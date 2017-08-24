<?php
class backend_model_sitemap{
    /**
     * @var xml_sitemap
     */
    protected $xml,$setting,$collectionLanguage,$DBPages,$DBNews,$DBCatalog,$DBPlugins,$template;

    /**
     * backend_model_sitemap constructor.
     * @param $template
     */
    public function __construct($template)
    {
        $this->xml = new xml_sitemap();
        $this->setting = new backend_controller_setting();
        $this->DBPages = new backend_db_pages();
        $this->DBNews = new backend_db_news();
        $this->DBCatalog = new backend_db_catalog();
        $this->DBPlugins = new backend_db_plugins();
        $this->collectionLanguage = new component_collections_language();
        $this->template = $template;
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
                $host = 'http://www.';
            }else{
                $host = 'https://www.';
            }

            $domain = $data['domain'];

            return $host.$domain.$data['url'];
        }
    }

    private function getCallClass($className){
        try{
            $class =  new $className;
            if($class instanceof $className){
                return $class;
            }else{
                throw new Exception('not instantiate the class: '.$className);
            }
        } catch (Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Call a callback method for create sitemap with plugins
     * @param $config
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
                                $this->getCallClass($class),'setSitemap'
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
     */
    public function setItems($config){
        $dateFormat = new date_dateformat();
        $this->template->configLoad();
        usleep(200000);
        $this->progress = new component_core_feedback($this->template);
        $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('control_of_data'),'progress' => 10));
        // LOAD active languages
        $lang = $this->collectionLanguage->fetchData(array('context'=>'all','type'=>'langs'));
        $newData = array();
        // Basepath
        $basePath = component_core_system::basePath();

        usleep(200000);
        $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_sitemap'),'progress' => 30, 'rendering' => true));

        if($lang != null) {
            $i = 0;
            foreach ($lang as $item) {
                $i++;
                usleep(200000);
                $progress =  40 + (30/(count($lang)))*($i + 1);
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

                // Load Data pages
                $dataPages = $this->DBPages->fetchData(array('context' => 'all', 'type' => 'sitemap'), array('id_lang' => $item['id_lang']));
                foreach ($dataPages as $key => $value) {
                    $url = '/' . $value['iso_lang'] . '/pages/' . $value['id_pages'] . '-' . $value['url_pages'] . '/';
                    //$newData[$item['iso_lang']][$key] = $this->url(array('domain' => $config['domain'], 'url' => $url));
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
                    $datePublish = $dateFormat->dateToDefaultFormat($value['date_publish']);
                    $url = '/' . $value['iso_lang'] . '/news/' . $datePublish . '/' . $value['id_news'] . '-' . $value['url_news'] . '/';

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

                // Load Data catalog
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
                    $url = '/' . $value['iso_lang'] . '/catalog/' . $value['id_cat'] . '-' . $value['url_cat'] . '/';
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
                    $url = '/' . $value['iso_lang'] . '/catalog/' . $value['id_cat'] . '-' . $value['url_cat'] . '/' . $value['id_product'] . '-' . $value['url_p'] . '/';
                    //$newData[$item['iso_lang']][$key] = $this->url(array('domain' => $config['domain'], 'url' => $url));
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
            usleep(200000);
            $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_sitemap_success'), 'progress' => 100, 'status' => 'success'));

        }else{
            usleep(200000);
            $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_error'),'progress' => 100,'status' => 'error','error_code' => 'error_data'));

        }
    }
}
?>