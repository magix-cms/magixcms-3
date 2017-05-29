<?php
class backend_controller_country extends backend_db_country
{
    public $edit, $action, $tabs;
    protected $message, $template, $header, $data, $arrayTools;
    public $id_country,$iso_country,$name_country;

    public function __construct()
    {
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->arrayTools = new collections_ArrayTools();

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

        if (http_request::isPost('id')) {
            $this->id_country = $formClean->numeric($_POST['id']);
        }
        if (http_request::isPost('iso_country')) {
            $this->iso_country = $formClean->simpleClean($_POST['iso_country']);
        }
        if (http_request::isPost('name_country')) {
            $this->name_country = $formClean->simpleClean($_POST['name_country']);
        }

        // --- Search
        if (http_request::isGet('search')) {
            $this->search = $formClean->arrayClean($_GET['search']);
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
     * @return array
     */
    private function setCollection(){
        return $this->arrayTools->defaultCountry();
    }

    /**
     *
     */
    public function getCollection(){
        $data = $this->setCollection();
        $this->template->assign('getCountryCollection',$data);
    }
}