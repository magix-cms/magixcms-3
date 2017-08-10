<?php
class backend_model_data extends backend_db_scheme{
	protected $template, $db, $caller;
	public $search;

	/**
	 * backend_model_data constructor.
	 * @param object $caller - object class of the class that called the model
	 */
	public function __construct($caller)
	{
		$this->caller = $caller;
		$this->db = (new ReflectionClass(get_parent_class($caller)))->newInstance();
		$this->template = new backend_model_template();
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
				return $data;
		}
	}

	/**
	 * @param array $sch
	 * @param array $cols
	 * @param null|array $ass
	 * @return array
	 */
	public function parseScheme($sch, $cols, $ass)
	{
		$this->template->configLoad();
		$arr = array();
		$scheme = array();
		foreach ($sch as $col) {
			$arr[$col['column']] = $col['type'];
		}
		$sch = $arr;

		foreach ($cols as $col) {
			$type = $sch[$col];
			$pre = strstr($col, '_', true);

			$column = array(
				'type' => 'text',
				'class' => '',
				'title' => $pre,
				'input' => array(
					'type' => 'text'
				)/*,
				'info' => $col*/
			);

			if (strpos($type, 'int') !== false) {
				$sl = strpos($type,'(') + 1;
				$el = strpos($type,')');
				$limit = substr($type, $sl, ($el - $sl));

				if($limit > 1) {
					$column['type'] =  'text';
					$column['class'] =  'fixed-td-md text-center';

					if(preg_match('/^id/i', $type)) {
						$scheme[$col]['title'] = 'id';
					}
				}
				else {
					$column['type'] = 'bin';
					$column['enum'] = 'bin_';
					$column['class'] = 'fixed-td-md text-center';
					$column['input'] = array(
						'type' => 'select',
						'var' => true,
						'values' => array(
							array('v' => 0),
							array('v' => 1)
						)
					);
				}
			}
			else if(preg_match('/^decimal/i', $type)
				|| preg_match('/^float/i', $type)
				|| preg_match('/^double/i', $type)) {
			}
			else if(preg_match('/^enum/i', $type)) {
				$sl = strpos($type,'(') + 2;
				$el = strpos($type,')') - 1;
				$values = substr($type, $sl, ($el - $sl));
				$values = explode("','",$values);
				$enum = array();
				foreach ($values as $k => $val) {
					$name = $pre.'_'.$k;
					$enum[] = array('v' => $val, 'name' => $this->template->getConfigVars($name));
				}

				$column['type'] = 'enum';
				$column['enum'] = $pre.'_';
				$column['class'] = 'fixed-td-lg';
				$column['input'] = array(
					'type' => 'select',
					'values' => $enum
				);
			}
			else if(preg_match('/^varchar/i', $type)) {
				$sl = strpos($type,'(') + 1;
				$el = strpos($type,')');
				$limit = substr($type, $sl, ($el - $sl));

				if($limit <= 100) {
					$column['class'] =  'th-25';
				}
				else {
					$column['class'] =  'th-35';
				}
			}
			else if(preg_match('/^text/i', $type)) {
				$column['type'] =  'content';
				$column['input'] = null;
			}
			else if(preg_match('/^datetime/i', $type)
				|| preg_match('/^timestamp/i', $type)) {
				$column['class'] =  'fixed-td-lg';
				$column['type'] =  'date';

				if(preg_match('/^date/i', $pre)) {
					$column['input'] =  array('type' => 'text', 'class' => 'date-input');
				}
				else if(preg_match('/^time/i', $pre)){
					$column['input'] =  array('type' => 'text', 'class' => 'time-input');
				}
			}

			$scheme[$col] = $column;
		}

		if(is_array($ass)) {
			$newScheme =  array();

			foreach ($ass as $name => $info) {
				$pre = strstr($name, '_', true);

				if(is_array($info)) {
					if(isset($info['col'])) {
						$key = $info['col'];
					} else {
						$key = $name;
					}

					$newScheme[$name] = $scheme[$key];

					if(isset($info['title'])) {
						if($info['title'] == 'pre') {
							$newScheme[$name]['title'] = $pre;
						} elseif($info['title'] == 'name') {
							$newScheme[$name]['title'] = $name;
						} else {
							$newScheme[$name]['title'] = $info['title'];
						}
					}

					foreach($info as $k => $d) {
						if($k !== 'title' && $k !== 'col') {
							$newScheme[$name][$k] = $d;
						}
					}
				}
				else {
					$newScheme[$info] = $scheme[$info];
				}
			}

			$scheme = $newScheme;
		}

		return $scheme;
	}

	/**
	 * Get Columns types
	 * @param array $tables
	 * @param array $columns
	 * @param null|array $assign
	 */
	public function getScheme($tables, $columns, $assign = null, $tpl_var = 'scheme')
	{
		$tables = "'".implode("','", $tables)."'";
		$cols = "'".implode("','", $columns)."'";
		$params = array(':dbname' => MP_DBNAME, 'table' => $tables, 'columns' => $cols);
		$scheme = parent::fetchData(array('context'=>'all','type'=>'scheme'),$params);
		$this->template->assign($tpl_var,$this->parseScheme($scheme, $columns, $assign));
	}
}
?>