<?php
class backend_model_data {
	protected $template, $db, $providers, $caller;
	public $search;

	/**
	 * backend_model_data constructor.
	 * @param object $caller - object class of the class that called the model
	 */
	public function __construct($caller)
	{
		$this->db = (new ReflectionClass(get_parent_class($caller)))->newInstance();
		$this->template = new backend_model_template();
		$this->providers = new backend_model_provider();
		$formClean = new form_inputEscape();

		// --- Search
		if (http_request::isGet('search')) {
			$this->search = $formClean->arrayClean($_GET['search']);
		}
	}

	/**
	 * Retrieve data
	 * @param string $context
	 * @param string $type
	 * @param string|int|null $id
	 * @return mixed
	 */
	private function setItems(&$context, $type, $id = null) {
		if($id) {
			if(is_array($id)) {
				$params = $id;
			}
			else {
				$params = array(':id' => $id);
			}
			$context = $context ? $context : 'unique';
		} else {
			$params = null;
			$context = $context ? $context : 'all';
		}
		return $this->db->fetchData(array('context'=>$context,'type'=>$type,'search'=>$this->search),$params);
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $context
	 * @param string $type
	 * @param string|int|null $id
	 * @return mixed
	 */
	public function getItems($type, $id = null, $context = null) {
		$data = $this->setItems($context, $type, $id);
		switch ($context) {
			case 'return':
			case 'last':
				return $data;
				break;
			default:
				$varName = $type;
				$this->template->assign($varName,$data);
		}
	}

	/**
	 * Get all providers of the same $type
	 * @param $type
	 * @return mixed
	 */
	public function getProviders($type) {
		$data = $this->providers->getItems($type);
		switch ($type) {
			case 'church':
				$varName = 'churches';
				break;

			default:
				$varName = $type.'s';
		}
		$this->template->assign($varName,$data);
	}
}
?>