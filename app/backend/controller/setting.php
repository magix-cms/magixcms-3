<?php
class backend_controller_setting extends backend_db_setting{
    public $edit, $action, $tabs;
    protected $message, $template, $header, $data;
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

    /**
     *
     */
    public function run(){
        $this->setItemsData();
        $this->template->display('setting/index.tpl');
    }
}
?>