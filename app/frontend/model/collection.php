<?php
class frontend_model_collection{

    protected $template,$upload,$imagesComponent,$routingUrl,$DBNews,$DBCatalog,$DBproduct;

	/**
	 * frontend_model_collection constructor.
	 * @param stdClass $t
	 */
    public function __construct($t = null)
    {
    	$this->template = $t ? $t : new frontend_model_template();
        $this->upload = new component_files_upload();
        $this->imagesComponent = new component_files_images($t);
        $this->routingUrl = new component_routing_url();
        $this->DBNews = new frontend_db_news();
        $this->DBCatalog = new frontend_db_catalog();
        $this->DBproduct = new frontend_db_product();
    }

    /**
     * Build Pages
     * @param $data
     * @return array
     */
    public function getBuildPages($data){
        $imgPath = $this->upload->imgBasePath('upload/pages');
        $arr = array();
        $conf = array();
        $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'pages','attribute_img'=>'page'));
        $imgPrefix = $this->imagesComponent->prefix();

        foreach ($data as $page) {

            $publicUrl = !empty($page['url_pages']) ? $this->routingUrl->getBuildUrl(array(
                    'type'      =>  'pages',
                    'iso'       =>  $page['iso_lang'],
                    'id'        =>  $page['id_pages'],
                    'url'       =>  $page['url_pages']
                )
            ) : '';

            if (!array_key_exists($page['id_pages'], $arr)) {
                $arr[$page['id_pages']] = array();
                $arr[$page['id_pages']]['id_pages'] = $page['id_pages'];
                $arr[$page['id_pages']]['id_parent'] = $page['id_parent'];
                if($page['img_pages'] != null) {
                    foreach ($fetchConfig as $key => $value) {
                        $arr[$page['id_pages']]['imgSrc'][$value['type_img']] = '/upload/pages/'.$page['id_pages'].'/'.$imgPrefix[$value['type_img']] . $page['img_pages'];
                    }
                }
                $arr[$page['id_pages']]['menu_pages'] = $page['menu_pages'];
                $arr[$page['id_pages']]['date_register'] = $page['date_register'];
            }
            $arr[$page['id_pages']]['content'][$page['id_lang']] = array(
                'id_lang'           => $page['id_lang'],
                'iso_lang'          => $page['iso_lang'],
                'default_lang'      => $page['default_lang'],
                'name_pages'        => $page['name_pages'],
                'url_pages'         => $page['url_pages'],
                'resume_pages'      => $page['resume_pages'],
                'content_pages'     => $page['content_pages'],
                'seo_title_pages'   => $page['seo_title_pages'],
                'seo_desc_pages'    => $page['seo_desc_pages'],
                'published_pages'   => $page['published_pages'],
                'public_url'        => $publicUrl
            );
        }
        return $arr;
    }

    /**
     * Build News
     * @param $data
     * @return array
     */
    public function getBuildNews($data){
        $imgPath = $this->upload->imgBasePath('upload/news');
        $arr = array();
        $conf = array();

        $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'news','attribute_img'=>'news'));
        $imgPrefix = $this->imagesComponent->prefix();

        foreach ($data as $page) {
            $dateFormat = new date_dateformat();
            $datePublish = !empty($page['date_publish']) ? $dateFormat->dateToDefaultFormat($page['date_publish']) : $dateFormat->dateToDefaultFormat();
            //$publicUrl = !empty($page['url_news']) ? '/'.$page['iso_lang'].'/news/'.$datePublish.'/'.$page['id_news'].'-'.$page['url_news'].'/' : '';
            $publicUrl = !empty($page['url_news']) ? $this->routingUrl->getBuildUrl(array(
                    'type'      =>  'news',
                    'iso'       =>  $page['iso_lang'],
                    'date'      =>  $datePublish,
                    'id'        =>  $page['id_news'],
                    'url'       =>  $page['url_news']
                )
            ) : '';
            if (!array_key_exists($page['id_news'], $arr)) {
                $arr[$page['id_news']] = array();
                $arr[$page['id_news']]['id_news'] = $page['id_news'];
                if($page['img_news'] != null) {
                    foreach ($fetchConfig as $key => $value) {
                        $arr[$page['id_news']]['imgSrc'][$value['type_img']] = '/upload/news/'.$page['id_news'].'/'.$imgPrefix[$value['type_img']] . $page['img_news'];
                    }
                }
                //$arr[$page['id_news']]['menu_news'] = $page['menu_news'];
                $arr[$page['id_news']]['date_register'] = $page['date_register'];
            }
            /*$tagData = $this->DBNews->fetchData(
                array('context'=>'all','type'=>'tags','conditions'=>' WHERE tag.id_lang = :id_lang'),
                array('id_lang'=>$page['id_lang'])
            );*/
            $tagData = $this->DBNews->fetchData(
                array('context' => 'all', 'type' => 'tagsRel'),
                array(
                    ':iso' => $page['iso_lang'],
                    ':id'  => $page['id_news']
                )
            );

            if($tagData != null){
                $newArrayTags = array();
                foreach($tagData as $key =>$item){
                    $newArrayTags[$key]['id']=$item['id_tag'];
                    $newArrayTags[$key]['name']=$item['name_tag'];
                }
                //$tags = implode(',',$newArrayTags);
                $tags = $newArrayTags;
            }else{
                $tags = null;
            }

            $arr[$page['id_news']]['content'][$page['id_lang']] = array(
                'id_lang'           => $page['id_lang'],
                'iso_lang'          => $page['iso_lang'],
                'default_lang'      => $page['default_lang'],
                'name_news'         => $page['name_news'],
                'url_news'          => $page['url_news'],
                'resume_news'       => $page['resume_news'],
                'content_news'      => $page['content_news'],
                'date_publish'      => $datePublish,
                'published_news'    => $page['published_news'],
                'public_url'        => $publicUrl,
                'seo_title_news'    => $page['seo_title_news'],
                'seo_desc_news'     => $page['seo_desc_news'],
                //'tags_news'         => $page['tags_news'],
                'tags'              => $tags
            );
        }

        return $arr;
    }
    /**
     * @param $data
     * @return array
     */
    public function getBuildCategory($data){

        $imgPath = $this->upload->imgBasePath('upload/catalog/c');
        $arr = array();
        $conf = array();
        $extwebp = 'webp';
        $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'catalog','attribute_img'=>'category'));
        $imgPrefix = $this->imagesComponent->prefix();

        foreach ($data as $page) {

            $publicUrl = !empty($page['url_cat']) ? $this->routingUrl->getBuildUrl(array(
                    'type'      =>  'category',
                    'iso'       =>  $page['iso_lang'],
                    'id'        =>  $page['id_cat'],
                    'url'       =>  $page['url_cat']
                )
            ) : '';

            if (!array_key_exists($page['id_cat'], $arr)) {
                $arr[$page['id_cat']] = array();
                $arr[$page['id_cat']]['id_cat'] = $page['id_cat'];
                $arr[$page['id_cat']]['id_parent'] = $page['id_parent'];
                $arr[$page['id_cat']]['menu_cat'] = $page['menu_cat'];
                // # return filename without extension
                $pathinfo = pathinfo($page['img_cat']);
                $filename = $pathinfo['filename'];
                if($page['img_cat'] != null) {
                    foreach ($fetchConfig as $key => $value) {
                        $arr[$page['id_cat']]['imgSrc'][$value['type_img']] = '/upload/catalog/c/'.$page['id_cat'].'/'.$imgPrefix[$value['type_img']] . $page['img_cat'];
                        //$data['img'][$value['type_img']]['src_webp'] = '/upload/catalog/c/'.$row['id_cat'].'/'.$imgPrefix[$value['type_img']] . $filename. '.' .$extwebp;
                    }
                }
                $arr[$page['id_cat']]['date_register'] = $page['date_register'];
            }
            $arr[$page['id_cat']]['content'][$page['id_lang']] = array(
                'id_lang'           => $page['id_lang'],
                'iso_lang'          => $page['iso_lang'],
                'default_lang'      => $page['default_lang'],
                'name_cat'          => $page['name_cat'],
                'url_cat'           => $page['url_cat'],
                'content_cat'       => $page['content_cat'],
                'seo_title_cat'     => $page['seo_title_cat'],
                'seo_desc_cat'      => $page['seo_desc_cat'],
                'resume_cat'        => $page['resume_cat'],
                'published_cat'     => $page['published_cat'],
                'public_url'        => $publicUrl
            );
        }
        return $arr;
    }

    /**
     * @param $data
     * @return array
     */
    public function getBuildProductItems($data){
        $arr = array();
        $conf = array();
        $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'catalog','attribute_img'=>'product'));
        $imgPrefix = $this->imagesComponent->prefix();
        foreach ($data as $page) {

            //$publicUrl = !empty($page['url_p']) ? '/' . $page['iso_lang'] . '/' . $page['id_product'] . '-' . $page['url_p'] . '/' : '';
            if (!array_key_exists($page['id_product'], $arr)) {
                $arr[$page['id_product']] = array();
                $arr[$page['id_product']]['id_product'] = $page['id_product'];
                $arr[$page['id_product']]['price_p'] = $page['price_p'];
                $arr[$page['id_product']]['reference_p'] = $page['reference_p'];
                $arr[$page['id_product']]['width_p'] = $page['width_p'];
                $arr[$page['id_product']]['height_p'] = $page['height_p'];
                $arr[$page['id_product']]['depth_p'] = $page['depth_p'];
                $arr[$page['id_product']]['weight_p'] = $page['weight_p'];
                $arr[$page['id_product']]['date_register'] = $page['date_register'];

                // Images collection
                $imgCollection = $this->DBproduct->fetchData(
                    array('context' => 'all', 'type' => 'images','conditions'=>'WHERE img.id_product = :id AND img.default_img = 1'),
                    array('id'=>$page['id_product']/*,'iso'=>$page['iso_lang']*/)
                );

                if($imgCollection != null) {

                    foreach ($imgCollection as $img) {

                        if (!array_key_exists($page['id_img'], $arr)) {
                            $arr[$page['id_product']]['images'][$img['id_img']] = array();
                            $arr[$page['id_product']]['images'][$img['id_img']]['id_img'] = $img['id_img'];
                            $arr[$page['id_product']]['images'][$img['id_img']]['id_product'] = $img['id_product'];
                            $arr[$page['id_product']]['images'][$img['id_img']]['name_img'] = $img['name_img'];
                            $arr[$page['id_product']]['images'][$img['id_img']]['default_img'] = $img['default_img'];
                            $arr[$page['id_product']]['images'][$img['id_img']]['imgSrc']['original'] = '/upload/catalog/p/' . $page['id_product'] . '/' . $img['name_img'];
                            foreach ($fetchConfig as $key => $value) {
                                $arr[$page['id_product']]['images'][$img['id_img']]['imgSrc'][$value['type_img']] = '/upload/catalog/p/' . $page['id_product'] . '/' . $imgPrefix[$value['type_img']] . $img['name_img'];
                            }
                        }

                        $imgContent = $this->DBproduct->fetchData(
                            array('context' => 'all', 'type' => 'images_content','conditions'=>'WHERE c.id_img = :id'),
                            array('id'=>$img['id_img'])
                        );

                        if($imgContent != null) {
                            foreach ($imgContent as $content) {

                                $arr[$page['id_product']]['images'][$img['id_img']]['content'][$content['id_lang']] = array(
                                    'id_lang' => $content['id_lang'],
                                    'iso_lang' => $content['iso_lang'],
                                    'alt_img' => $content['alt_img'],
                                    'title_img' => $content['title_img']
                                );
                            }
                        }
                    }
                }
            }
            $arr[$page['id_product']]['content'][$page['id_lang']] = array(
                'id_lang' => $page['id_lang'],
                'iso_lang' => $page['iso_lang'],
                'default_lang' => $page['default_lang'],
                'name_p' => $page['name_p'],
                'url_p' => $page['url_p'],
                'resume_p' => $page['resume_p'],
                'content_p' => $page['content_p'],
                'published_p' => $page['published_p']/*,
				'public_url' => $publicUrl*/
            );
        }
        return $arr;
    }

    /**
     * @param $data
     * @return array
     * @throws Exception
     */
    public function getBuildProduct($data){
        $arr = array();
        $conf = array();
        $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'catalog','attribute_img'=>'product'));
        $imgPrefix = $this->imagesComponent->prefix();
        foreach ($data as $page) {

            //$publicUrl = !empty($page['url_p']) ? '/' . $page['iso_lang'] . '/' . $page['id_product'] . '-' . $page['url_p'] . '/' : '';
            if (!array_key_exists($page['id_product'], $arr)) {
                $arr[$page['id_product']] = array();
                $arr[$page['id_product']]['id_product'] = $page['id_product'];
                $arr[$page['id_product']]['price_p'] = $page['price_p'];
                $arr[$page['id_product']]['reference_p'] = $page['reference_p'];
                $arr[$page['id_product']]['width_p'] = $page['width_p'];
                $arr[$page['id_product']]['height_p'] = $page['height_p'];
                $arr[$page['id_product']]['depth_p'] = $page['depth_p'];
                $arr[$page['id_product']]['weight_p'] = $page['weight_p'];
                $arr[$page['id_product']]['date_register'] = $page['date_register'];

                // Images collection
                $imgCollection = $this->DBproduct->fetchData(
                    array('context' => 'all', 'type' => 'images','conditions'=>'WHERE img.id_product = :id'),
                    array('id'=>$page['id_product']/*,'iso'=>$page['iso_lang']*/)
                );

                if($imgCollection != null) {

                    foreach ($imgCollection as $img) {

                        if (!array_key_exists($page['id_img'], $arr)) {
                            $arr[$page['id_product']]['images'][$img['id_img']] = array();
                            $arr[$page['id_product']]['images'][$img['id_img']]['id_img'] = $img['id_img'];
                            $arr[$page['id_product']]['images'][$img['id_img']]['id_product'] = $img['id_product'];
                            $arr[$page['id_product']]['images'][$img['id_img']]['name_img'] = $img['name_img'];
                            $arr[$page['id_product']]['images'][$img['id_img']]['default_img'] = $img['default_img'];
                            $arr[$page['id_product']]['images'][$img['id_img']]['imgSrc']['original'] = '/upload/catalog/p/' . $page['id_product'] . '/' . $img['name_img'];
                            foreach ($fetchConfig as $key => $value) {
                                $arr[$page['id_product']]['images'][$img['id_img']]['imgSrc'][$value['type_img']] = '/upload/catalog/p/' . $page['id_product'] . '/' . $imgPrefix[$value['type_img']] . $img['name_img'];
                            }
                        }

                        $imgContent = $this->DBproduct->fetchData(
                            array('context' => 'all', 'type' => 'images_content','conditions'=>'WHERE c.id_img = :id'),
                            array('id'=>$img['id_img'])
                        );

                        if($imgContent != null) {
                            foreach ($imgContent as $content) {

                                $arr[$page['id_product']]['images'][$img['id_img']]['content'][$content['id_lang']] = array(
                                    'id_lang' => $content['id_lang'],
                                    'iso_lang' => $content['iso_lang'],
                                    'alt_img' => $content['alt_img'],
                                    'title_img' => $content['title_img'],
                                    'caption_img' => $content['caption_img']
                                );
                            }
                        }
                    }
                }
                // Associated / Similar product
                $associatedCollection = $this->DBproduct->fetchData(
                    array('context' => 'all', 'type' => 'similar','conditions'=>'WHERE rel.id_product = :id'),
                    array('id'=>$page['id_product'])
                );

                // Loop associated / similar product
                foreach ($associatedCollection as $associated) {
                    // associated Collection
                    $imgCollection = $this->DBproduct->fetchData(
                        array('context' => 'all', 'type' => 'images','conditions'=>'WHERE img.id_product = :id AND img.default_img = 1'),
                        array('id'=>$associated['id_product']/*,'iso'=>$page['iso_lang']*/)
                    );
                    // images collection
                    $imgRel = array();
                    if($imgCollection != null) {

                        foreach ($imgCollection as $img) {
                            // images collection each
                            if (!array_key_exists($page['id_img'], $arr)) {
                                $imgRel['images'] = array();
                                $imgRel['images']['id_img']             = $img['id_img'];
                                $imgRel['images']['id_product']         = $img['id_product'];
                                $imgRel['images']['name_img']           = $img['name_img'];
                                $imgRel['images']['default_img']        = $img['default_img'];
                                $imgRel['images']['imgSrc']['original'] = '/upload/catalog/p/' . $page['id_product'] . '/' . $img['name_img'];
                                foreach ($fetchConfig as $key => $value) {
                                    $imgRel['images']['imgSrc'][$value['type_img']] = '/upload/catalog/p/' . $page['id_product'] . '/' . $imgPrefix[$value['type_img']] . $img['name_img'];
                                }
                            }
                            // Image content by languages
                            $imgContent = $this->DBproduct->fetchData(
                                array('context' => 'all', 'type' => 'images_content','conditions'=>'WHERE c.id_img = :id'),
                                array('id'=>$img['id_img'])
                            );

                            // Loop image associated / similar product by language data
                            if($imgContent != null) {
                                foreach ($imgContent as $content) {

                                    $imgRel['images']['content'][$content['id_lang']] = array(
                                        'id_lang'   => $content['id_lang'],
                                        'iso_lang'  => $content['iso_lang'],
                                        'alt_img'   => $content['alt_img'],
                                        'title_img' => $content['title_img']
                                    );
                                }
                            }
                        }
                    }
                    $arr[$page['id_product']]['associated'][$associated['id_product_2']]['id_product'] = $associated['id_product'];
                    $arr[$page['id_product']]['associated'][$associated['id_product_2']]['price_p'] = $associated['price_p'];
                    $arr[$page['id_product']]['associated'][$associated['id_product_2']]['reference_p'] = $associated['reference_p'];
                    $arr[$page['id_product']]['associated'][$associated['id_product_2']]['width_p'] = $associated['width_p'];
                    $arr[$page['id_product']]['associated'][$associated['id_product_2']]['height_p'] = $associated['height_p'];
                    $arr[$page['id_product']]['associated'][$associated['id_product_2']]['depth_p'] = $associated['depth_p'];
                    $arr[$page['id_product']]['associated'][$associated['id_product_2']]['weight_p'] = $associated['weight_p'];
                    $arr[$page['id_product']]['associated'][$associated['id_product_2']]['date_register'] = $associated['date_register'];

                    $arr[$page['id_product']]['associated'][$associated['id_product_2']]['images'] = $imgRel['images'];
                    $arr[$page['id_product']]['associated'][$associated['id_product_2']]['content'][$associated['id_lang']] = array(
                        'id_rel'        => $associated['id_rel'],
                        'id_lang'       => $associated['id_lang'],
                        'iso_lang'      => $associated['iso_lang'],
                        'name_p'        => $associated['name_p'],
                        'url_p'         => $associated['url_p'],
                        'resume_p'      => $associated['resume_p'],
                        'content_p'     => $associated['content_p'],
                        'published_p'   => $associated['published_p']
                    );
                }
            }
            // Content by languages for product ID
            $arr[$page['id_product']]['content'][$page['id_lang']] = array(
                'id_lang'       => $page['id_lang'],
                'iso_lang'      => $page['iso_lang'],
                'default_lang'  => $page['default_lang'],
                'name_p'        => $page['name_p'],
                'url_p'         => $page['url_p'],
                'longname_p'    => $page['longname_p'],
                'resume_p'      => $page['resume_p'],
                'content_p'     => $page['content_p'],
                'seo_title_p'   => $page['seo_title_p'],
                'seo_desc_p'    => $page['seo_desc_p'],
                'published_p'   => $page['published_p']/*,
				'public_url' => $publicUrl*/
            );
        }
        return $arr;
    }
}