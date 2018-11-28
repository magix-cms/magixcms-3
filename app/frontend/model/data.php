<?php
class frontend_model_data{
	protected $template, $db, $caller;

	/**
	 * backend_model_data constructor.
	 * @param object $caller - object class of the class that called the model
	 * @param object $t - object class of the template class from the caller
	 */
	public function __construct($caller, $t = null)
	{
		$this->caller = $caller;
		$this->template = $t ? $t : new frontend_model_template();

		try {
			$this->db = (new ReflectionClass(get_parent_class($caller)))->newInstance();
		}
		catch(Exception $e) {
			$logger = new debug_logger(MP_LOG_DIR);
			$logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
		}
	}

	/**
	 * Parse data for frontend use
	 * @param $data
	 * @param $model
	 * @param $current
	 * @param bool $newRow
	 * @return mixed|null
	 */
	public function parseData($data,$model,$current,$newRow = false)
	{
		if($data && $model && $current){
			// ** Loop management var
			$deep = 1;
			$deep_minus = $deep  - 1;
			$deep_plus = $deep  + 1;
			$pass_trough = 0;
			$data_empty = false;

			// ** Loop format & output var
			$row = array();
			$items = array();
			$i[$deep] = 0;

			do{
				// *** loop management START
				if ($pass_trough == 0){
					// Si je n'ai plus de données à traiter je vide ma variable
					$row[$deep] = null;
				}else{
					// Sinon j'active le traitement des données
					$pass_trough = 0;
				}

				// Si je suis au premier niveaux et que je n'ai pas de donnée à traiter
				if ($deep == 1 AND $row[$deep] == null) {
					// récupération des données dans $data
					$row[$deep] = array_shift($data);
				}

				// Si ma donnée possède des sous-donnée sous-forme de tableau
				if (isset($row[$deep]['subdata']) ){
					if (is_array($row[$deep]['subdata']) AND $row[$deep]['subdata'] != null){
						// On monte d'une profondeur
						$deep++;
						$deep_minus++;
						$deep_plus++;
						// on récupére la  première valeur des sous-données en l'éffacant du tableau d'origine
						$row[$deep] = array_shift($row[$deep_minus]['subdata']);
						// Désactive le traitement des données
						$pass_trough = 1;
					}
				}elseif($deep != 1){
					if ( $row[$deep] == null) {
						if ($row[$deep_minus]['subdata'] == null){
							// Si je n'ai pas de sous-données & pas de données à traiter & pas de frères à récupérer dans mon parent
							// ====> désactive le tableaux de sous-données du parent et retourne au niveau de mon parent
							unset ($row[$deep_minus]['subdata']);
							unset ($i[$deep]);
							$deep--;
							$deep_minus = $deep  - 1;
							$deep_plus = $deep  + 1;
						}else{
							// Je récupère un frère dans mon parent
							$row[$deep] = array_shift($row[$deep_minus]['subdata']);
						}
						// Désactive le traitement des données
						$pass_trough = 1;
					}
				}
				// *** loop management END

				// *** list format START
				if ($row[$deep] != null AND $pass_trough != 1){
					$i[$deep]++;

					// Construit doonées de l'item en array avec clée nominative unifiée ('name' => 'monname,'descr' => '<p>ma descr</p>,...)

					$itemData = method_exists($model, 'setItemData') ? $model->setItemData($row[$deep],$current,$newRow) : null;

					// Récupération des sous-données (enfants)
					if(isset($items[$deep_plus]) != null) {
						$itemData['subdata'] = $items[$deep_plus];
						$items[$deep_plus] = null;
					}else{
						$subitems = null;
					}

					$items[$deep][] = $itemData;
				}
				// *** list format END

				// Si $data est vide ET que je n'ai plus de données en traitement => arrête la boucle
				if (empty($data) AND $row[1] == null){
					$data_empty = true;
				}

			}while($data_empty == false);

			return $items[$deep];
		}
		return null;
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

		if($branch === 'root') {
			return $childs[$branch];
		}
		else {
			if(is_array($branch)) {
				$d = array();
				foreach ($branch as $k) {
					$d[] = $childs[$k];
				}
				return $d;
			}
			else {
				return array($childs[$branch]);
			}
		}
	}

	/**
	 * @param $id
	 * @throws Exception
	 */
	public function getParents($id)
	{
		$data = $this->db->fetchData(array('context' => 'all', 'type' => 'parents'));
		$p = array((int)$id);
		$parent = $id;

		do {
			$s = $parent;
			foreach ($data as $k => $row) {
				if(in_array($parent,explode(',',$row['children']))) {
					$parent = $row['parent'];
					$p[] = $row['parent'];
					unset($data[$k]);
				}
			}
			if($s === $parent) $parent = null;

		} while ($parent !== null);

		return $p;
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
			$this->template->assign($varName, $data);
		}
		return $data;
	}
}