<?php
class backend_controller_country extends backend_db_country
{
    public $edit, $action, $tabs;
    protected $message, $template, $header, $data, $arrayTools;
    public $id_country,$iso_country,$name_country, $order;

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
            $this->id_country = $formClean->simpleClean($_POST['id']);
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

        # ORDER PAGE
        if(http_request::isPost('country')){
            $this->order = $formClean->arrayClean($_POST['country']);
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

    /**
     * @param null $id_country
     */
    private function getItemsCountry($id_country = null){
        if($id_country) {
            $data = parent::fetchData(array('context'=>'unique','type'=>'country'),array('id' => $id_country));
            $this->template->assign('country',$data);
        }else{
            $this->getItems('countries');
        }
    }

    /**
     * Insertion de données
     * @param $data
     */
    private function add($data)
    {
        switch ($data['type']) {
            case 'newCountry':
                $fetchData = parent::fetchData(array('context'=>'unique','type'=>'count'));
                if($fetchData != null){
                    $nb = $fetchData['nb'];
                }else{
                    $nb = 0;
                }
                parent::insert(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'iso_country'      => $this->iso_country,
                        'name_country'	   => $this->name_country,
                        'order_country'	   => $nb
                    )
                );
                $this->header->set_json_headers();
                $this->message->json_post_response(true,'add_redirect');
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
            case 'country':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'id_country'       => $this->id_country,
                        'iso_country'      => $this->iso_country,
                        'name_country'	   => $this->name_country,
                    )
                );
                break;
            case 'order':
                $p = $this->order;
                for ($i = 0; $i < count($p); $i++) {
                    parent::update(
                        array(
                            'type'=>$data['type']
                        ),array(
                            'id_country'       => $p[$i],
                            'order_country'    => $i
                        )
                    );
                }
                break;
        }
    }

    /**
     * Insertion de données
     * @param $data
     */
    private function del($data){
        switch($data['type']){
            case 'delCountry':
                parent::delete(
                    array(
                        'context'   =>    'country',
                        'type'      =>    $data['type']
                    ),
                    $data['data']
                );
                $this->header->set_json_headers();
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
                    if(isset($this->iso_country) && isset($this->name_country)){
                        $this->add(
                            array(
                                'type'=>'newCountry'
                            )
                        );
                    }else{
                        $this->getCollection();
                        $this->template->display('country/add.tpl');
                    }
                    break;

                case 'edit':
                    if (isset($this->iso_country)) {
                        $this->upd(
                            array(
                                'type' => 'country'
                            )
                        );
                        $this->header->set_json_headers();
                        $this->message->json_post_response(true,'update',$this->id_country);
                    }else{
                        $this->getCollection();
                        $this->getItemsCountry($this->edit);
                        $this->template->display('country/edit.tpl');
                    }
                    break;
                case 'order':
                    if (isset($this->order)) {
                        $this->upd(
                            array(
                                'type' => 'order'
                            )
                        );
                        print 'test';
                    }
                    break;
                case 'delete':
                    if(isset($this->id_country)) {
                        $this->del(
                            array(
                                'type'=>'delCountry',
                                'data'=>array(
                                    'id' => $this->id_country
                                )
                            )
                        );
                    }
                    break;
            }
        }else{
            $this->getItemsCountry();
            $this->template->display('country/index.tpl');
        }
    }
}