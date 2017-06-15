<?php
class backend_controller_setting extends backend_db_setting{
    public $edit, $action, $tabs;
    protected $message, $template, $header, $data;
    public $setting, $type, $color;
    public function __construct()
    {
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();

        // --- GET
        if (http_request::isGet('edit')) {
            $this->edit = $formClean->numeric($_GET['edit']);
        }
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }
        if (http_request::isGet('tabs')) {
            $this->tabs = $formClean->simpleClean($_GET['tabs']);
        }
        // --- POST
        if (http_request::isPost('setting')) {
            $this->setting = $formClean->arrayClean($_POST['setting']);
        }else{
            $this->setting = array();
        }
        if (http_request::isPost('color')) {
            $this->color = $formClean->arrayClean($_POST['color']);
        }

        if (http_request::isPost('type')) {
            $this->type = $formClean->simpleClean($_POST['type']);
        }
    }
    /**
     * Assign data to the defined variable or return the data
     * @param string $context
     * @param string $type
     * @param string|int|null $id
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null) {
        return $this->data->getItems($type, $id, $context);
    }

    /**
     * Assign data to the defined value
     */
    private function setItemsData(){
        $newArray = array();
        $settings = $this->getItems('settings',null,'return');
        foreach($settings as $key){
            $newArray[$key['name']] = $key['value'];
        }
        $this->template->assign('settings',$newArray);
    }
    private function setItemsSkin(){
        $currentSkin = parent::fetchData(array('context'=>'unique','type'=>'skin'));
        $finder = new file_finder();
        $basePath = component_core_system::basePath().'skin';
        $skin = $finder->scanRecursiveDir($basePath);
        $newSkin = array();
        foreach($skin as $key => $value){
            if($value === $currentSkin['value']){
                $current = 'true';
            }else{
                $current = 'false';
            }
            if(file_exists($basePath.DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR.'screenshot_s.jpg')){
                $screenshot['small'] = DIRECTORY_SEPARATOR.'skin'.DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR.'screenshot_s.jpg';
                $screenshot['large'] = DIRECTORY_SEPARATOR.'skin'.DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR.'screenshot_l.jpg';
            }else{
                $screenshot['small'] = false;
                $screenshot['large'] = false;
            }
            $newSkin[$key]['name'] = $value;
            $newSkin[$key]['current'] = $current;
            $newSkin[$key]['screenshot']['small'] = $screenshot['small'];
            $newSkin[$key]['screenshot']['large'] = $screenshot['large'];
        }
        $this->template->assign('skin',$newSkin);
    }
    /**
     * Mise a jour des données
     * @param $data
     */
    private function upd($data)
    {
        switch ($data['type']) {
            case 'general':

                if(!isset($this->setting['concat'])){
                    $concat = '0';
                }else{
                    $concat = '1';
                }

                if(!isset($this->setting['ssl'])){
                    $ssl = '0';
                }else{
                    $ssl = '1';
                }

                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'content_css'   => $this->setting['content_css'],
                        'concat'        => $concat,
                        'ssl'           => $ssl,
                        'cache'         => $this->setting['cache'],
                        'mode'          => $this->setting['mode']
                    )
                );
                break;
            case 'css_inliner':
                if(isset($this->setting['css_inliner'])){
                    parent::update(
                        array(
                            'type'=>$data['type']
                        ),array(
                            'css_inliner'   => '1',
                            'header_bg'     => $this->color['header_bg'],
                            'header_c'      => $this->color['header_c'],
                            'footer_bg'     => $this->color['footer_bg'],
                            'footer_c'      => $this->color['footer_c']
                        )
                    );
                }else{
                    parent::update(
                        array(
                            'type'=>$data['type']
                        ),array(
                            'css_inliner'   => '0'
                        )
                    );
                }
                break;
            case 'google':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'analytics'   => $this->setting['analytics'],
                        'robots'      => $this->setting['robots']
                    )
                );
                break;
            case 'theme':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'theme'   => $this->setting['theme']
                    )
                );
                break;
        }
        $this->header->set_json_headers();
        $this->message->json_post_response(true,'update',$data['type']);
    }
    /**
     *
     */
    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'edit':
                    if (isset($this->setting)) {
                        if($this->type === 'general'){
                            $this->upd(array('type'=>'general'));
                        }elseif($this->type === 'css_inliner'){
                            $this->upd(array('type'=>'css_inliner'));
                        }elseif($this->type === 'theme'){
                            $this->upd(array('type'=>'theme'));
                        }elseif($this->type === 'google'){
                            $this->upd(array('type'=>'google'));
                        }
                    }
                    break;
            }
        }else{
            $this->setItemsData();
            $this->setItemsSkin();
            $this->template->display('setting/index.tpl');
        }
    }
}
?>