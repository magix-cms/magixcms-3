<?php
class frontend_model_data{
	protected $template, $db, $caller;

	/**
	 * backend_model_data constructor.
	 * @param object $caller - object class of the class that called the model
	 */
	public function __construct($caller)
	{
		$this->caller = $caller;
		$this->db = (new ReflectionClass(get_parent_class($caller)))->newInstance();
		$this->template = new frontend_model_template();

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
		return $this->db->fetchData(array('context'=>$context,'type'=>$type),$params);
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
				return $data;
		}
	}
}
?>