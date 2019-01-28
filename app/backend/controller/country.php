<?php
class backend_controller_country extends backend_db_country
{
    public $edit, $action, $tabs, $search;
    protected $message, $template, $header, $data, $arrayTools;
    public $id_country,$iso_country,$name_country, $order;

	/**
	 * @param stdClass $t
	 * backend_controller_country constructor.
	 */
    public function __construct($t)
    {
        $this->template = $t;
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
			$this->search = array_filter($this->search, function ($value) { return $value !== ''; });
		}

        # ORDER PAGE
        if(http_request::isPost('country')){
            $this->order = $formClean->arrayClean($_POST['country']);
        }
    }

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @param boolean $pagination
	 * @return mixed
	 */
	private function getItems($type, $id = null, $context = null, $assign = true, $pagination = false) {
		return $this->data->getItems($type, $id, $context, $assign, $pagination);
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
     * Insertion de données
     * @param $data
     */
    private function add($data)
    {
        switch ($data['type']) {
            case 'newCountry':
                $fetchData = parent::fetchData(array('context'=>'one','type'=>'count'));
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
                        $this->message->json_post_response(true,'update',$this->id_country);
                    }else{
                        $this->getCollection();
                        //$this->getItemsCountry($this->edit);
						$this->getItems('country',$this->edit);
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
			$this->getItems('countries',null,'all',true,true);
			$assign = array(
				'id_country',
				'iso_country' => ['title' => 'iso_country'],
				'name_country'
			);
			$this->data->getScheme(array('mc_country'),array('id_country','iso_country','name_country'),$assign);
            $this->template->display('country/index.tpl');
        }
    }
}