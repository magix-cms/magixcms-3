<?php
class backend_controller_webservice extends backend_db_webservice{

    public $edit, $action, $tabs;
    protected $message, $template, $header, $data;
    public $key_ws,$status_ws;

	/**
	 * backend_controller_webservice constructor.
	 * @param stdClass $t
	 */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new backend_model_template;
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
        if(http_request::isPost('key_ws')){
            $this->key_ws = $formClean->simpleClean($_POST['key_ws']);
        }

        if (http_request::isPost('status_ws')) {
            $this->status_ws = 1;
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
     * Mise a jour des données
     */
    private function save()
    {
        $data = $this->getItems('ws',null,'one',false);
        if(!isset($this->status_ws)){
            $status_ws = '0';
        }else{
            $status_ws = $this->status_ws;
        }
        if($data['id_ws'] != null){
            parent::update(array('type'=>'ws'),array('id_ws'=>$data['id_ws'],'key_ws'=>$this->key_ws,'status_ws'=>$status_ws));
            $this->message->json_post_response(true, 'update', $data['id_ws']);
        }else{
            parent::insert(array('type'=>'newWs'),array('key_ws'=>$this->key_ws,'status_ws'=>$status_ws));
            $this->message->json_post_response(true, 'add');
        }

    }

    /**
     *
     */
    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'edit':
                    $this->save();
                    break;
            }
        }else{
            $this->getItems('ws',null,'one');
            $this->template->display('webservice/index.tpl');
        }
    }

}
?>