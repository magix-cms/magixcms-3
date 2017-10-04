<?php
include_once ('db.php');
/**
 * Class plugins_contact_admin
 * Fichier pour l'administration d'un plugin
 */
class plugins_contact_admin extends plugins_contact_db{
    public $edit, $action, $tabs;
    protected $controller,$data,$template, $message, $plugins, $xml, $sitemap,$modelLanguage,$collectionLanguage,$header;
    public $content,$id_contact,$mail_contact,$address_required,$address_enabled,$id_config;
    /**
     * frontend_controller_home constructor.
     */
    public function __construct(){
        $this->template = new backend_model_template();
        $this->plugins = new backend_controller_plugins();
        $formClean = new form_inputEscape();
        $this->message = new component_core_message($this->template);
        $this->xml = new xml_sitemap();
        $this->sitemap = new backend_model_sitemap($this->template);
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->data = new backend_model_data($this);
        $this->header = new http_header();
        // --- GET
        if(http_request::isGet('controller')) {
            $this->controller = $formClean->simpleClean($_GET['controller']);
        }
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

        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }

        // --- ADD or EDIT
        if (http_request::isPost('id')) {
            $this->id_contact = $formClean->simpleClean($_POST['id']);
        }
        if (http_request::isPost('mail_contact')) {
            $this->mail_contact = $formClean->simpleClean($_POST['mail_contact']);
        }

        if (http_request::isPost('id_config')) {
            $this->id_config = $formClean->simpleClean($_POST['id_config']);
        }
        if (http_request::isPost('address_enabled')) {
            $this->address_enabled = $formClean->simpleClean($_POST['address_enabled']);
        }
        if (http_request::isPost('address_required')) {
            $this->address_required = $formClean->simpleClean($_POST['address_required']);
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
     * @param $data
     * @return array
     */
    private function setItemContentData($data){

        $arr = array();
        foreach ($data as $page) {

            if (!array_key_exists($page['id_contact'], $arr)) {
                $arr[$page['id_contact']] = array();
                $arr[$page['id_contact']]['id_contact'] = $page['id_contact'];
                $arr[$page['id_contact']]['mail_contact'] = $page['mail_contact'];
            }
            $arr[$page['id_contact']]['content'][$page['id_lang']] = array(
                'id_lang'          => $page['id_lang'],
                'published_contact'   => $page['published_contact']
            );
        }
        return $arr;
    }

    /**
     * Update data
     * @param $data
     */
    private function add($data)
    {
        switch ($data['type']) {
            case 'contact':
            case 'content':
                parent::insert(
                    array(
                        'context' => $data['context'],
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                break;
        }
    }

    /**
     * Mise a jour des données
     * @param $data
     */
    private function upd($data)
    {
        switch ($data['type']) {
            case 'contact':
            case 'content':
                parent::update(
                    array(
                        'context' => $data['context'],
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                break;
            case 'config':
                parent::update(
                    array(
                        'context' => $data['context'],
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                break;
        }
    }
    /**
     * @param $id_contact
     * @return array
     */
    private function saveContent($id_contact)
    {
        $extendData = array();

        foreach ($this->content as $lang => $content) {
            $content['id_lang'] = $lang;
            $content['id_contact'] = $id_contact;

            $contentContact = $this->getItems('content',array('id_contact'=>$id_contact, 'id_lang'=>$lang),'one',false);

            //print_r($contentContact);

            if($contentContact != null) {
                $this->upd(
                    array(
                        'context' => 'contact',
                        'type' => 'contact',
                        'data' => array(
                            'id_pages' => $id_contact,
                            'mail_contact' => $this->mail_contact
                        )
                    )
                );
                $this->upd(
                    array(
                        'context' => 'contact',
                        'type' => 'content',
                        'data' => $content
                    )
                );
            }
            else {
                $this->add(
                    array(
                        'context' => 'contact',
                        'type' => 'content',
                        'data' => $content
                    )
                );
            }
        }

        //if(!empty($extendData)) return $extendData;
    }

    private function save($data){
        $data['address_enabled'] = (!isset($data['address_enabled']) ? 0 : 1);
        $data['address_required'] = (!isset($data['address_required']) ? 0 : 1);
        $this->upd(
            array(
                'context' => 'contact',
                'type' => 'config',
                'data' => array(
                    'id_config' => $this->id_config,
                    'address_enabled'  => $data['address_enabled'],
                    'address_required' => $data['address_required']
                )
            )
        );

    }
    /**
     *
     */
    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if(isset($this->content)) {
                        $this->add(
                            array(
                                'context' => 'contact',
                                'type' => 'contact',
                                'data' => array(
                                    'mail_contact' => $this->mail_contact
                                )
                            )
                        );

                        $contact = $this->getItems('root',null,'one',false);

                        if ($contact['id_contact']) {
                            $this->saveContent($contact['id_contact']);
                            $this->header->set_json_headers();
                            $this->message->json_post_response(true,'add_redirect');
                        }
                    }else {
                        $this->modelLanguage->getLanguage();
                        $this->template->display('add.tpl');
                    }
                    break;
                case 'edit':
                    if (isset($this->id_contact)) {
                        $this->saveContent($this->id_contact);
                        $this->header->set_json_headers();
                        $this->message->json_post_response(true, 'update', array('result'=>$this->id_contact));
                    }elseif(isset($this->id_config)) {

                        $this->save(array('address_enabled'=>$this->address_enabled,'address_required'=>$this->address_required));
                        $this->header->set_json_headers();
                        $this->message->json_post_response(true, 'update', array('result'=>$this->id_config));

                    }else{
                        $this->modelLanguage->getLanguage();

                        $setEditData = parent::fetchData(array('context'=>'all','type'=>'data'), array('edit'=>$this->edit));
                        $setEditData = $this->setItemContentData($setEditData);
                        $this->template->assign('contact',$setEditData[$this->edit]);
                        $this->template->display('edit.tpl');
                    }
                    break;
            }
        }else{
            $this->modelLanguage->getLanguage();
            $defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
            $this->getItems('contact',array(':default_lang'=>$defaultLanguage['id_lang']),'all');
            $assign = array(
                'id_contact',
                'mail_contact' => ['title' => 'name']
            );
            $this->data->getScheme(array('mc_contact','mc_contact_content'),array('id_contact','mail_contact'),$assign);
            $this->getItems('config',null,'one','config');
            $this->template->display('index.tpl');
        }
    }
}