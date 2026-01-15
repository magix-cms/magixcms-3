<?php
class backend_model_data extends backend_db_scheme{
	protected $template, $db, $caller;
	public $search, $page, $offset;

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
		if (http_request::isGet('page')) {
			$this->page = intval($formClean->simpleClean($_GET['page']));
		}
		$this->offset = (http_request::isGet('offset')) ? intval($formClean->simpleClean($_GET['offset'])) : 25;
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

            if(isset($item['id_parent'])){
                $k = $item['id_parent'] == null ? 'root' : $item['id_parent'];
            }else{
                $k = 'root';
            }
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
	private function setItems(&$context, $type, $id = null, $page = null, $offset = null) {
        $params = [];
        if($id) {
            if(is_array($id)) {
                $params = $id;
            }
            else {
                $params = ['id' => $id];
            }
            $context = $context ?: 'one';
        }
        else {

            $context = $context ?: 'all';
        }
		return $this->db->fetchData(array('context'=>$context,'type'=>$type,'search'=>$this->search,'page'=>$page,'offset'=>$offset),$params);
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string|null $context
	 * @param string|boolean $assign
	 * @param string|boolean $pagination
	 * @return mixed
	 */
	public function getItems($type, $id = null, $context = null, $assign = true, $pagination = false) {
		$data = $this->setItems($context, $type, $id, ($pagination || $this->page) ? $this->page : null, ($pagination || $this->page) ? $this->offset : null);
		if($assign) {
			$varName = gettype($assign) == 'string' ? $assign : $type;
			$this->template->assign($varName,$data);
		}
		if(isset($this->page) || $pagination) {
			$data = $this->setItems($context, $type, $id);
			$this->template->assign('nbp',!empty($data) ? ceil(count($data) / $this->offset) : 1);
			$this->template->assign('offset',$this->offset * (isset($this->page) && $this->page > 1 ? $this->page - 1 : 1));
		}
		return $data;
	}

	/**
	 * @param array $sch
	 * @param array $cols
	 * @param null|array $ass
	 * @return array
	 */
	/*public function parseScheme($sch, $cols, $ass)
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
				),
				//'info' => $col
			);

			if (strpos($type, 'int') !== false) {
				$sl = strpos($type,'(') + 1;
				$el = strpos($type,')');
				$limit = substr($type, $sl, ($el - $sl));

				if($limit > 1) {
					$column['type'] =  'text';
					$column['class'] =  'fixed-td-md text-center';

					if(preg_match('/^id/i', $type)) {
						//$scheme[$col]['title'] = 'id';
						$column['title'] = 'id';
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
                || preg_match('/^timestamp/i', $type)
                || preg_match('/^date/i', $type)) {
				$column['class'] =  'fixed-td-lg';
				$column['type'] =  'date';

				if(preg_match('/^date/i', $pre)) {
					$column['input'] =  array('type' => 'text', 'class' => 'date-input', 'placeholder' => '__/__/____');
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
					$key = $info['col'] ?? $name;
					if(key_exists($key, $scheme)) $newScheme[$name] = $scheme[$key];

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
	}*/
    public function parseScheme(array $rawScheme, array $targetCols, ?array $associations = null): array
    {
        $this->template->configLoad();

        // 1. Création rapide du map [col_name => type]
        // Supposons que $rawScheme contient des tableaux avec clés 'column' et 'type'
        $typeMap = array_column($rawScheme, 'type', 'column');

        $scheme = [];

        // 2. Construction du schéma de base
        foreach ($targetCols as $colName) {
            if (!isset($typeMap[$colName])) {
                continue; // Sécurité si la colonne n'existe pas
            }

            $fullType = $typeMap[$colName];
            $prefix   = strstr($colName, '_', true) ?: $colName; // Fallback si pas de underscore

            // On délègue la complexité à une fonction dédiée
            $scheme[$colName] = $this->buildColumnConfig($colName, $fullType, $prefix);
        }

        // 3. Application des surcharges (Associations)
        if (is_array($associations) && !empty($associations)) {
            return $this->applyAssociations($scheme, $associations);
        }

        return $scheme;
    }

    /**
     * Analyse le type SQL et retourne la configuration de base
     */
    /**
     * Analyse le type SQL et retourne la configuration de base
     */
    private function buildColumnConfig(string $colName, string $fullType, string $prefix): array
    {
        // Configuration par défaut
        $config = [
            'type'  => 'text',
            'class' => '',
            'title' => $prefix,
            'input' => ['type' => 'text']
        ];

        // Regex robuste : capture "tinyint", "varchar", etc. et l'argument entre parenthèses
        preg_match('/^([a-z]+)(?:\((.+)\))?/i', $fullType, $matches);
        $baseType = strtolower($matches[1] ?? 'text');
        $arg      = $matches[2] ?? null;

        switch ($baseType) {
            // --- TOUS LES TYPES ENTIERS ---
            // --- TOUS LES TYPES ENTIERS ---
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'int':
            case 'integer':
            case 'bigint':
                // 1. Détection automatique du type "bin" (Oui/Non)
                // On considère comme binaire si :
                // - C'est un tinyint(1)
                // - OU le nom de la colonne suggère un booléen (ex: active, visible, is_admin)
                $isBoolName = preg_match('/^(is_|has_|active|visible|display)/i', $colName);

                if (($baseType === 'tinyint' && $arg == 1) || $isBoolName) {
                    $config['type']  = 'bin';
                    $config['enum']  = 'bin_';
                    $config['class'] = 'fixed-td-md text-center';
                    $config['input'] = [
                        'type'   => 'select',
                        'var'    => true,
                        'values' => [['v' => 0], ['v' => 1]]
                    ];
                } else {
                    // 2. Cas standard (ID ou nombre)
                    $config['class'] = 'fixed-td-md text-center';
                    if (stripos($colName, 'id') === 0) {
                        $config['title'] = 'id';
                    }
                }
                break;

            case 'enum':
                // ... (reste du code inchangé)
                if ($arg) {
                    $values = explode("','", str_replace("'", "", $arg));
                    $enumOpts = [];
                    foreach ($values as $k => $val) {
                        $name = $prefix . '_' . $k;
                        $enumOpts[] = ['v' => $val, 'name' => $this->template->getConfigVars($name)];
                    }
                    $config['type']  = 'enum';
                    $config['enum']  = $prefix . '_';
                    $config['class'] = 'fixed-td-lg';
                    $config['input'] = ['type' => 'select', 'values' => $enumOpts];
                }
                break;

            case 'varchar':
                $limit = (int)$arg;
                $config['class'] = ($limit <= 100) ? 'th-25' : 'th-35';
                break;

            case 'text':
            case 'mediumtext':
            case 'longtext':
                $config['type']  = 'content';
                $config['input'] = null;
                break;

            case 'datetime':
            case 'timestamp':
            case 'date':
                $config['class'] = 'fixed-td-lg';
                $config['type']  = 'date';

                if (stripos($prefix, 'date') === 0) {
                    $config['input'] = ['type' => 'text', 'class' => 'date-input', 'placeholder' => '__/__/____'];
                } elseif (stripos($prefix, 'time') === 0) {
                    $config['input'] = ['type' => 'text', 'class' => 'time-input'];
                }
                break;

            // Ajout explicite pour les décimaux si besoin
            case 'decimal':
            case 'float':
            case 'double':
                $config['class'] = 'fixed-td-md text-center';
                break;
        }

        return $config;
    }

    /**
     * Gère la réorganisation et la surcharge via le tableau $ass
     */
    private function applyAssociations(array $currentScheme, array $associations): array
    {
        $newScheme = [];

        foreach ($associations as $name => $info) {
            if (!is_array($info)) {
                if (isset($currentScheme[$info])) $newScheme[$info] = $currentScheme[$info];
                continue;
            }

            $colKey = $info['col'] ?? $name;

            if (isset($currentScheme[$colKey])) {
                $item = $currentScheme[$colKey];
                $prefix = strstr($name, '_', true) ?: $name;

                // Gestion des titres (inchangé)
                if (isset($info['title'])) {
                    if ($info['title'] === 'pre') $item['title'] = $prefix;
                    elseif ($info['title'] === 'name') $item['title'] = $name;
                    else $item['title'] = $info['title'];
                    unset($info['title']);
                }
                unset($info['col']);

                // Fusionner
                $mergedItem = array_merge($item, $info);

                // --- CORRECTION ---
                // 1. Si l'input est explicitement null, on arrête là pour cette colonne.
                // array_key_exists est important car isset() renvoie false si la valeur est null.
                if (array_key_exists('input', $info) && $info['input'] === null) {
                    $newScheme[$name] = $mergedItem;
                    continue;
                }

                // 2. Sinon, on applique la correction automatique pour 'bin'
                // Uniquement si l'utilisateur n'a pas défini son propre input
                if (isset($mergedItem['type']) && $mergedItem['type'] === 'bin' && !isset($info['input'])) {
                    $mergedItem['enum']  = 'bin_';
                    $mergedItem['class'] = 'fixed-td-md text-center';
                    $mergedItem['input'] = [
                        'type'   => 'select',
                        'var'    => true,
                        'values' => [['v' => 0], ['v' => 1]]
                    ];
                }

                $newScheme[$name] = $mergedItem;
            }
        }

        return $newScheme;
    }

	/**
	 * Get Columns types
	 * @param array $tables
	 * @param array $columns
	 * @param null|array $assign
	 * @param string $tpl_var
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