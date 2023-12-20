<?php
class backend_controller_webservice extends backend_db_webservice {
    /**
     * @var backend_model_template $template
     * @var component_core_message $message
     * @var backend_model_data $data
     */
    protected backend_model_template $template;
    protected component_core_message $message;
    protected backend_model_data $data;

    /**
     * @var int $edit
     * @var int $status_ws
     */
    public int
        $edit,
        $status_ws;

    /**
     * @var string
     */
    public string
        $key_ws,
        $action,
        $tabs;

	/**
	 * backend_controller_webservice constructor.
	 * @param ?backend_model_template $t
	 */
    public function __construct(?backend_model_template $t = null) {
        $this->template = $t instanceof backend_model_template ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
        $this->data = new backend_model_data($this);

        // --- GET
        if(http_request::isGet('edit')) $this->edit = form_inputEscape::numeric($_GET['edit']);
        if(http_request::isRequest('action')) $this->action = form_inputEscape::simpleClean($_REQUEST['action']);
        if(http_request::isGet('tabs')) $this->tabs = form_inputEscape::simpleClean($_GET['tabs']);
        if(http_request::isPost('key_ws'))$this->key_ws = form_inputEscape::simpleClean($_POST['key_ws']);
        $this->status_ws = http_request::isPost('status_ws') ? 1 : 0;
    }

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param array|int|null $id
	 * @param ?string $context
	 * @param bool|string $assign
	 * @return mixed
	 */
	private function getItems(string $type, $id = null, ?string $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}

    /**
     *
     */
    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'edit':
                    $data = $this->getItems('ws',null,'one',false);
                    if(!empty($data['id_ws'])) {
                        parent::update(['type' => 'ws'],['id_ws' => $data['id_ws'], 'key_ws' => $this->key_ws, 'status_ws' => $this->status_ws]);
                        $this->message->json_post_response(true, 'update', $data['id_ws']);
                    }
                    else {
                        parent::insert(['type' => 'newWs'],['key_ws' => $this->key_ws, 'status_ws' => $this->status_ws]);
                        $this->message->json_post_response(true, 'add');
                    }
                    break;
            }
        }
        else {
            $this->getItems('ws',null,'one');
            $this->template->display('webservice/index.tpl');
        }
    }
}