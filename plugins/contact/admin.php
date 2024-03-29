<?php
include_once ('db.php');
/**
 * Class plugins_contact_admin
 * Fichier pour l'administration d'un plugin
 */
class plugins_contact_admin extends plugins_contact_db {
    public $edit, $action, $tabs;
    protected $controller,$data,$template, $message, $plugins, $xml, $sitemap,$modelLanguage,$collectionLanguage,$header,$module,$mods;
    public $content,$id_contact,$mail_contact,$address_required,$address_enabled,/*$mail_sender,*/$id_config;
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
        if(http_request::isGet('controller')) $this->controller = $formClean->simpleClean($_GET['controller']);
        if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
        if (http_request::isRequest('action')) $this->action = $formClean->simpleClean($_REQUEST['action']);
        if (http_request::isGet('tabs')) $this->tabs = $formClean->simpleClean($_GET['tabs']);

		if (http_request::isPost('content')) {
			$array = $_POST['content'];
			foreach($array as $key => $arr) {
				foreach($arr as $k => $v) {
					$array[$key][$k] = ($k == 'content_page') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
				}
			}
			$this->content = $array;
		}

        // --- ADD or EDIT
        if (http_request::isPost('id')) $this->id_contact = $formClean->simpleClean($_POST['id']);
        if (http_request::isPost('mail_contact')) $this->mail_contact = $formClean->simpleClean($_POST['mail_contact']);
        if (http_request::isPost('id_config')) $this->id_config = $formClean->simpleClean($_POST['id_config']);
        if (http_request::isPost('address_enabled')) $this->address_enabled = $formClean->simpleClean($_POST['address_enabled']);
        if (http_request::isPost('address_required')) $this->address_required = $formClean->simpleClean($_POST['address_required']);
        /*if (http_request::isPost('mail_sender')) $this->mail_sender = $formClean->simpleClean($_POST['mail_sender']);*/
        if (http_request::isGet('plugin')) $this->plugin = $formClean->simpleClean($_GET['plugin']);
    }

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName()
	{
		return $this->template->getConfigVars('contact_plugin');
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
	private function setItemPageData($data)
	{
		$arr = array();
		if(!empty($data)) {
			foreach ($data as $page) {
				if (!array_key_exists('id_page', $arr)) {
					$arr['id_page'] = $page['id_page'];
				}
				$arr['content'][$page['id_lang']] = array(
					'id_lang'          => $page['id_lang'],
					'name_page'        => $page['name_page'],
					'content_page'     => $page['content_page'],
					'published_page'   => $page['published_page']
				);
			}
		}
		return $arr;
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
	 * @return array
	 */
	public function getContact(){
		return $this->getItems('contacts',array('lang' => $this->getlang),'all',false);
	}

	/**
	 * @return mixed
	 */
	public function getSender()
	{
		return $this->getItems('sender',null,'one',false);
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
            case 'content_page':
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
            case 'content_page':
            case 'config':
                parent::update(
                   ['type' => $data['type']],
                    $data['data']
                );
                break;
        }
    }
    /**
     * @param $id_contact
     * @return array
     */
    private function saveContent($id_contact) {
        $extendData = [];

        foreach ($this->content as $lang => $content) {
            $content['id_lang'] = $lang;
            $content['id_contact'] = $id_contact;
            $contentContact = $this->getItems('content',['id_contact' => $id_contact, 'id_lang' => $lang],'one',false);

            if(!empty($contentContact)) {
                $this->upd([
					'context' => 'contact',
					'type' => 'contact',
					'data' => [
						'id_contact' => $id_contact,
						'mail_contact' => $this->mail_contact
					]
				]);
                $this->upd([
					'context' => 'contact',
					'type' => 'content',
					'data' => $content
				]);
            }
            else {
                $this->add([
					'context' => 'contact',
					'type' => 'content',
					'data' => $content
				]);
            }
        }
    }

	/**
	 * @param $data
	 */
    private function save($data){
        $data['address_enabled'] = (!isset($data['address_enabled']) ? 0 : 1);
        $data['address_required'] = (!isset($data['address_required']) ? 0 : 1);
        //$data['mail_sender'] = (!empty($data['mail_sender']) ? $data['mail_sender'] : NULL);
        $this->upd(
            array(
                'context' => 'contact',
                'type' => 'config',
                'data' => array(
                    'id_config' => $this->id_config,
                    'address_enabled'  => $data['address_enabled'],
                    'address_required' => $data['address_required']/*,
                    'mail_sender'      => $data['mail_sender']*/
                )
            )
        );

    }

    /**
     * Insertion de données
     * @param $data
     */
    private function del($data){
        switch($data['type']){
            case 'delMail':
                parent::delete(
                    array(
                        'context'   =>    'mail',
                        'type'      =>    $data['type']
                    ),
                    $data['data']
                );
                $this->message->json_post_response(true,'delete',$data['data']);
                break;
        }
    }

    /**
     *
     */
    private function loadModules() {
        $this->module = $this->module instanceof backend_controller_module ? $this->module : new backend_controller_module();
        if(empty($this->mods)) $this->mods = $this->module->load_module('contact');
    }

    /**
     * @return void
     */
    private function getModuleTabs() {
        $newsItems = [];
        foreach ($this->mods as $name => $mod) {
            // Execute un plugin core
            $class = 'plugins_' . $name . '_core';
            if(file_exists(component_core_system::basePath().'plugins'.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR.'core.php') && class_exists($class) && method_exists($class, 'run')) {

                $item['name'] = $name;
                if (method_exists($mod, 'getExtensionName')) {
                    $this->template->addConfigFile(
                        array(component_core_system::basePath() . 'plugins' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR),
                        array($name . '_admin_')
                    );
                    //$this->template->configLoad();
                    $item['title'] = $mod->getExtensionName();
                } else {
                    $item['title'] = $name;
                }
                $newsItems[] = $item;
            }
        }
        $this->template->assign('setTabsPlugins', $newsItems);
    }

    /**
     *
     */
    public function run(){
        $this->loadModules();
        if(isset($this->plugin)) {
            $this->modelLanguage->getLanguage();
            $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
            $this->getItems('contact', array(':default_lang' => $defaultLanguage['id_lang']), 'all');
            $this->getModuleTabs();
            // Initialise l'API menu des plugins core
            $this->modelLanguage->getLanguage();
            // Execute un plugin core
            $class = 'plugins_' . $this->plugin . '_core';
            if(file_exists(component_core_system::basePath().'plugins'.DIRECTORY_SEPARATOR.$this->plugin.DIRECTORY_SEPARATOR.'core.php') && class_exists($class) && method_exists($class, 'run')) {
                $executeClass =  new $class;
                if($executeClass instanceof $class){
                    $executeClass->run();
                }
            }
        }
        else {
            if (isset($this->action)) {
                switch ($this->action) {
                    case 'add':
                        if (isset($this->content)) {
                            $this->add([
								'context' => 'contact',
								'type' => 'contact',
								'data' => ['mail_contact' => $this->mail_contact]
							]);

                            $contact = $this->getItems('root', null, 'one', false);

                            if ($contact['id_contact']) {
                                $this->saveContent($contact['id_contact']);
                                $this->message->json_post_response(true, 'add_redirect');
                            }
                        }
						else {
                            $this->modelLanguage->getLanguage();
                            $this->template->display('add.tpl');
                        }
                        break;
                    case 'edit':
                        if (isset($this->tabs) && $this->tabs === 'content' && isset($this->content) && !empty($this->content)) {
                            $root = parent::fetchData(array('context' => 'one', 'type' => 'root_page'));
                            if (!$root) {
                                parent::insert(array('type' => 'root_page'));
                                $root = parent::fetchData(array('context' => 'one', 'type' => 'root_page'));
                            }
                            $id = $root['id_page'];

                            foreach ($this->content as $lang => $content) {
                                if (empty($content['id'])) $content['id'] = $id;
                                $rootLang = $this->getItems('content_page', array('id' => $id, 'id_lang' => $lang), 'one', false);

                                $content['id_lang'] = $lang;
                                $content['published_page'] = (!isset($content['published_page']) ? 0 : 1);

                                $config = array(
                                    'type' => 'content_page',
                                    'data' => $content
                                );

                                ($rootLang) ? $this->upd($config) : $this->add($config);
                            }
                            $this->message->json_post_response(true, 'update');
                        }
						elseif (isset($this->id_contact)) {
                            $this->saveContent($this->id_contact);
                            $this->message->json_post_response(true, 'update', array('result' => $this->id_contact));
                        }
						elseif (isset($this->id_config)) {
                            $this->save(array('address_enabled' => $this->address_enabled, 'address_required' => $this->address_required, 'mail_sender' => $this->mail_sender));
                            $this->message->json_post_response(true, 'update', array('result' => $this->id_config));

                        }
						else {
                            $this->modelLanguage->getLanguage();

                            $setEditData = parent::fetchData(array('context' => 'all', 'type' => 'data'), array('edit' => $this->edit));
                            $setEditData = $this->setItemContentData($setEditData);
                            $this->template->assign('contact', $setEditData[$this->edit]);
                            $this->template->display('edit.tpl');
                        }
                        break;
                    case 'delete':
                        if (isset($this->id_contact)) {
                            $this->del(
                                array(
                                    'type' => 'delMail',
                                    'data' => array(
                                        'id' => $this->id_contact
                                    )
                                )
                            );
                        }
                        break;
                }
            }
            else {
                $this->modelLanguage->getLanguage();
                $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
                // Page content
                $last = $this->getItems('root_page', null, 'one', false);
				$pages = [];
				if(!empty($last)) {
					$collection = $this->getItems('pages', $last['id_page'], 'all', false);
					$pages = $this->setItemPageData($collection);
				}
				$this->template->assign('pages', $pages);
                // Mails
                $contacts = $this->getItems('contact', array(':default_lang' => $defaultLanguage['id_lang']), 'all',false);
				$this->template->assign('contact',empty($contacts) ? [] : $contacts);
                $assign = array(
                    'id_contact',
                    'mail_contact' => ['title' => 'name']
                );
                $this->data->getScheme(array('mc_contact', 'mc_contact_content'), array('id_contact', 'mail_contact'), $assign);
                // Configuration
                $this->getItems('config', null, 'one', 'config');

                $this->getModuleTabs();

                $this->template->display('index.tpl');
            }
        }
    }

    /**
     * @param $config
     * @throws Exception
     */
    public function setSitemap($config){
        $dateFormat = new date_dateformat();
        $url = '/' . $config['iso_lang']. '/'.$config['name'].'/';
        $this->xml->writeNode(
            array(
                'type'      =>  'child',
                'loc'       =>  $this->sitemap->url(array('domain' => $config['domain'], 'url' => $url)),
                'image'     =>  false,
                'lastmod'   =>  $dateFormat->dateDefine(),
                'changefreq'=>  'always',
                'priority'  =>  '0.7'
            )
        );
    }
	/**
	 * @return array|bool
	 */
	public function menu_mode()
	{
		return class_exists('plugins_gmap_public') ? array('simple','dropdown') : false;
	}
}