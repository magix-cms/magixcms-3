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
	 * @param $data
	 * @param $type
	 * @param string $branch
	 * @return array|mixed
	 */
	public function setPagesTree($data, $type, $branch = 'root')
	{
		$childs = array();
		$id = 'id_'.$type;

		foreach ($data as &$item) {
			if(!isset($item[$id])) $id = 'id';
			$childs[$item[$id]] = &$item;
			$childs[$item[$id]]['subdata'] = array();
		}
		unset($item);

		foreach($data as &$item) {
			$k = $item['id_parent'] == null ? 'root' : $item['id_parent'];
			if(!isset($item[$id])) $id = 'id';

			if($k === 'root')
				$childs[$k][] = &$item;
			else
				$childs[$k]['subdata'][] = &$item;
		}
		unset($item);

		foreach($data as &$item) {
			if (isset($childs[$item[$id]])) {
				$item['subdata'] = $childs[$item[$id]]['subdata'];
			}
		}

		if($branch === 'root')
			return $childs[$branch];
		else
			return array($childs[$branch]);
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
	 * @throws Exception
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