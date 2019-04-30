<?php
class backend_controller_seo extends backend_db_seo {
    public $edit, $action, $tabs;
    protected $message, $template, $header, $data, $dbPlugins,$modelLanguage,$collectionLanguage;
    public $id_seo,$content,$attribute_seo,$level_seo,$type_seo;

	/**
	 * backend_controller_seo constructor.
	 * @param stdClass $t
	 */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->dbPlugins = new backend_db_plugins();

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
        // --- ADD or EDIT
        if (http_request::isPost('id')) {
            $this->id_seo = $formClean->simpleClean($_POST['id']);
        }

        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = ($k == 'content_pages') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }
        if (http_request::isPost('attribute_seo')) {
            $this->attribute_seo = $formClean->simpleClean($_POST['attribute_seo']);
        }
        if (http_request::isPost('level_seo')) {
            $this->level_seo = $formClean->simpleClean($_POST['level_seo']);
        }
        if (http_request::isPost('type_seo')) {
            $this->type_seo = $formClean->simpleClean($_POST['type_seo']);
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
     * @return array
     */
    private function collectionModule(){
        $core =  array('catalog','news');
        $plugins = $this->dbPlugins->fetchData(array('context'=>'all','type'=>'seo'));
        if($plugins != null) {
            $newData = array();
            foreach ($plugins as $key) {
                $newData[] = /*'plugins:'.*/
                    $key['name'];
            }
            return array_merge($core,$newData);
        }else{
            return $core;
        }
    }
    /**
     * @param $data
     * @return array
     */
    private function setItemData($data)
    {
        $arr = array();

        foreach ($data as $page) {
            if (!array_key_exists($page['id_seo'], $arr)) {
                $arr[$page['id_seo']] = array();
                $arr[$page['id_seo']]['id_seo'] = $page['id_seo'];
                $arr[$page['id_seo']]['level_seo'] = $page['level_seo'];
                $arr[$page['id_seo']]['attribute_seo'] = $page['attribute_seo'];
                $arr[$page['id_seo']]['type_seo'] = $page['type_seo'];
            }
            $arr[$page['id_seo']]['content'][$page['id_lang']] = array(
                'id_lang'           => $page['id_lang'],
                'content_seo'     => $page['content_seo']
            );
        }
        return $arr;
    }
    /**
     * Mise a jour des données
     * @param $data
     */
    private function upd($data)
    {
        switch ($data['type']) {
            case 'content':
                parent::update(
                    array(
                        'type' => $data['type']
                    ), array(
                        'id_lang'       => $data['id_lang'],
                        'id_seo'        => $data['id_seo'],
                        'content_seo'   => $data['content_seo']
                    )
                );
                break;
            case 'data':
                parent::update(
                    array(
                        'type' => $data['type']
                    ), array(
                        'id_seo'            => $data['id_seo'],
                        'level_seo'         => $data['level_seo'],
                        'attribute_seo'     => $data['attribute_seo'],
                        'type_seo'          => $data['type_seo']
                    )
                );
                break;
        }
    }

    /**
     *
     */
    private function save(){
        if (isset($this->content) && isset($this->id_seo)) {
            $this->upd(array(
                'type'          => 'data',
                'id_seo'        => $this->id_seo,
                'attribute_seo' => $this->attribute_seo,
                'level_seo'     => $this->level_seo,
                'type_seo'      => $this->type_seo
            ));
            foreach ($this->content as $lang => $content) {
                $checkLangData = parent::fetchData(
                    array('context'=>'one','type'=>'content'),
                    array('id_seo'=>$this->id_seo,'id_lang'=>$lang)
                );
                // Check language page content
                if($checkLangData!= null){
                    $this->upd(array(
                        'type' => 'content',
                        'id_lang' => $lang,
                        'id_seo' => $this->id_seo,
                        'content_seo' => $content['content_seo']
                    ));
                }else{
                    parent::insert(
                        array(
                            'type' => 'newContent',
                        ),
                        array(
                            'id_lang' => $lang,
                            'id_seo' => $this->id_seo,
                            'content_seo' => $content['content_seo']
                        )
                    );
                }
            }
            $this->message->json_post_response(true,'update',$this->id_seo);
        }
        elseif (isset($this->content) && !isset($this->id_seo)) {
            parent::insert(
                array(
                    'type'=>'newSeo'
                ),
                array(
                    'attribute_seo' => $this->attribute_seo,
                    'level_seo'     => $this->level_seo,
                    'type_seo'      => $this->type_seo
                )
            );

            $setSeoData = parent::fetchData(
                array('context' => 'one', 'type' => 'root')
            );
            if($setSeoData['id_seo']){
                foreach ($this->content as $lang => $content) {
                    parent::insert(
                        array(
                            'type' => 'newContent',
                        ),
                        array(
                            'id_lang'     => $lang,
                            'id_seo'      => $setSeoData['id_seo'],
                            'content_seo' => $content['content_seo']
                        )
                    );
                }
                $this->message->json_post_response(true,'add_redirect');
            }

        }
    }
    /**
     * Insertion de données
     * @param $data
     */
    private function del($data){
        switch($data['type']){
            case 'delSeo':
                parent::delete(
                    array(
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
    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if (isset($this->content)) {
                        $this->save();
                    } else {
                        $this->modelLanguage->getLanguage();
                        $this->template->assign('collectionModule',$this->collectionModule());
                        $this->template->display('seo/add.tpl');
                    }

                    break;
                case 'edit':
                    if (isset($this->id_seo)) {
                        $this->save();
                    }else{
                        $this->template->assign('collectionModule',$this->collectionModule());
                        $this->modelLanguage->getLanguage();
                        $setEditData = parent::fetchData(
                            array('context'=>'all','type'=>'editSeo'),
                            array('edit'=>$this->edit)
                        );
                        $setEditData = $this->setItemData($setEditData);
                        $this->template->assign('seo',$setEditData[$this->edit]);
                        $this->template->display('seo/edit.tpl');
                    }
                    break;
                case 'delete':
                    if(isset($this->id_seo)) {
                        $this->del(
                            array(
                                'type'=>'delSeo',
                                'data'=>array(
                                    'id' => $this->id_seo
                                )
                            )
                        );
                    }
                    break;
            }
        }else{
            $this->modelLanguage->getLanguage();
            $defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
            $this->getItems('seo',array(':default_lang'=>$defaultLanguage['id_lang']),'all');

            $assign = array(
                'id_seo',
				'content_seo',
                'attribute_seo' => ['title' => 'module'],
                //'content_pages' => ['class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null],
                'level_seo',
                'type_seo'
            );
            //$this->module();
            $this->data->getScheme(array('mc_seo','mc_seo_content'),array('id_seo','content_seo','attribute_seo','level_seo','type_seo'),$assign);
            $this->template->display('seo/index.tpl');
        }
    }
}
?>