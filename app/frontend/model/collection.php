<?php
class frontend_model_collection{

    protected $upload,$imagesComponent,$routingUrl,$DBNews;

    public function __construct($template)
    {
        $this->upload = new component_files_upload();
        $this->imagesComponent = new component_files_images($this->template);
        $this->routingUrl = new component_routing_url();
        $this->DBNews = new frontend_db_news();
    }

    /**
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
     * @param $data
     * @param null $tagData
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
            $publicUrl = !empty($page['url_news']) ? '/'.$page['iso_lang'].'/news/'.$datePublish.'/'.$page['id_news'].'-'.$page['url_news'].'/' : '';

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
            $tagData = $this->DBNews->fetchData(
                array('context'=>'all','type'=>'tags','conditions'=>' WHERE tag.id_lang = :id_lang'),
                array('id_lang'=>$page['id_lang'])
            );

            if($tagData != null){
                $newArrayTags = array();
                foreach($tagData as $item){
                    $newArrayTags[]=$item['name_tag'];
                }
                $tags = implode(',',$newArrayTags);
            }else{
                $tags = '';
            }

            $arr[$page['id_news']]['content'][$page['id_lang']] = array(
                'id_lang'           => $page['id_lang'],
                'iso_lang'          => $page['iso_lang'],
                'name_news'         => $page['name_news'],
                'url_news'          => $page['url_news'],
                'resume_news'       => $page['resume_news'],
                'content_news'      => $page['content_news'],
                'date_publish'      => $datePublish,
                'published_news'    => $page['published_news'],
                'public_url'        => $publicUrl,
                //'tags_news'         => $page['tags_news'],
                'tags'              => $tags
            );
        }

        return $arr;
    }
}
?>