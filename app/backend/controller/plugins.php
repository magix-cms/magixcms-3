<?php
class backend_controller_plugins extends backend_db_plugins{
    protected $modelPlugins,$template,$message,$header,$data;

    public function __construct()
    {
        $this->modelPlugins = new backend_model_plugins();
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
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
     *
     */
    public function run(){
        $data = $this->getItems('list',null,'return');
        //print_r($data);
        $this->template->assign('getListPlugins',$data);
        $this->template->display('plugins/index.tpl');
    }
}
?>