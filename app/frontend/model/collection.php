<?php
class frontend_model_collection{

    protected $upload,$imagesComponent,$routingUrl;

    public function __construct($template)
    {
        $this->upload = new component_files_upload();
        $this->imagesComponent = new component_files_images($this->template);
        $this->routingUrl = new component_routing_url();
    }

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
}
?>