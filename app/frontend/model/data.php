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
			$context = $context ? $context : 'one';
		} else {
			$params = null;
			$context = $context ? $context : 'all';
		}
		return $this->db->fetchData(array('context'=>$context,'type'=>$type,'search'=>$this->search),$params);
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return mixed
	 */
	public function getItems($type, $id = null, $context = null, $assign = true) {
		$data = $this->setItems($context, $type, $id);
		if($assign) {
			$varName = gettype($assign) == 'string' ? $assign : $type;
			$this->template->assign($varName,$data);
		}
		return $data;
	}
}
?>