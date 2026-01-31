<?php
class frontend_controller_catalog extends frontend_db_catalog {
    /**
     * @var frontend_model_template $template
     * @var frontend_model_data $data
     * @var frontend_model_catalog $modelCatalog
     * @var frontend_model_module $modelModule
     */
	protected frontend_model_template $template;
	protected frontend_model_data $data;
	protected frontend_model_catalog $modelCatalog;
	protected frontend_model_module $modelModule;
	protected $modelCore;

	/**
	 * @var int $id
	 * @var int $id_parent
	 */
    public int
		$id,
		$id_parent,
		$offset,
		$page;

	/**
	 * @var string $lang
	 */
    public string $lang;

	/**
	 * @var array $filter
	 */
    public array $filter;

	/**
	 * @param frontend_model_template|null $t
	 */
    public function __construct(frontend_model_template $t = null) {
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this);
        $this->lang = $this->template->lang;
        $this->modelCatalog = new frontend_model_catalog($this->template);
        $this->modelModule = new frontend_model_module($this->template);
		$this->offset = $this->template->settings['product_per_page'];

        if(http_request::isGet('id')) $this->id = form_inputEscape::numeric($_GET['id']);
        if(http_request::isGet('id_parent')) $this->id_parent = form_inputEscape::numeric($_GET['id_parent']);
        if(http_request::isGet('filter')) $this->filter = form_inputEscape::arrayClean($_GET['filter']);
		$this->page = http_request::isGet('page') ? form_inputEscape::numeric($_GET['page']) - 1 : 0;
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param array|int|null $id
     * @param string|null $context
     * @param bool|string $assign
     * @return mixed
     */
    private function getItems(string $type, $id = null, string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

	// --- Deprecated
	/**
	 * @deprecated
	 * @return array
	 */
	private function getBuildRootItems(): array {
		$collection = $this->getItems('root',['iso' => $this->lang],'all',false);

		$newData = [];
		if(!empty($collection)) {
			foreach ($collection as $item) {
				$newData[$item['name_info']] = $item['value_info'];
			}
		}

		return $this->modelCatalog->setItemData($newData,[]);
	}

	/**
	 * @deprecated
	 * set Data from database
	 * @access private
	 */
	private function getBuildCategoryList()
	{
		$override = $this->modelModule->getOverride('category',__FUNCTION__);
		if(!$override) {
			$conditions = ' WHERE lang.iso_lang = :iso AND c.published_cat = 1 AND p.id_parent IS NULL ORDER BY p.order_cat';
			$collection = parent::fetchData(
				array('context' => 'all', 'type' => 'category', 'conditions' => $conditions),
				array(':iso' => $this->lang)
			);
			$newarr = array();
			foreach ($collection as $item) {
				$newarr[] = $this->modelCatalog->setItemData($item, []);
			}
			return $newarr;
		}else{
			return $override;
		}
	}

	/**
	 * @deprecated
	 * set Data from database
	 * @access private
	 * @return array
	 */
	private function getBuildSubCategoryList()
	{
		$override = $this->modelModule->getOverride('category',__FUNCTION__);
		if(!$override) {
			$conditions = ' WHERE lang.iso_lang = :iso AND c.published_cat = 1 AND p.id_parent = :id_parent ORDER BY p.order_cat';
			$collection = parent::fetchData(
				array('context' => 'all', 'type' => 'category', 'conditions' => $conditions),
				array('iso' => $this->lang, 'id_parent' => $this->id)
			);
			$newarr = array();
			foreach ($collection as $item) {
				$newarr[] = $this->modelCatalog->setItemData($item, []);
			}
			return $newarr;
		}else{
			return $override;
		}
	}

	/**
     * @deprecated
	 * set Data from database
	 * @access private
	 * @return array
	 */
    private function getBuildProductList()
    {
        $override = $this->modelModule->getOverride('product',__FUNCTION__);
        if(!$override) {
            $conditions = ' WHERE lang.iso_lang = :iso
						AND pc.published_p = 1 
						AND (img.default_img = 1 OR img.default_img IS NULL) 
						AND catalog.default_c = 1
						AND catalog.id_product IN (SELECT id_product FROM mc_catalog WHERE id_cat = :id_cat) 
						ORDER BY catalog.order_p ASC';
            $collection = parent::fetchData(
                array('context' => 'all', 'type' => 'product', 'conditions' => $conditions),
                array('iso' => $this->lang, 'id_cat' => $this->id)
            );

            $collection = $this->getItems('product',array('iso' => $this->lang, 'id_cat' => $this->id),'all',false);

        }else{
            return $override;
        }
    }

	/**
	 * @deprecated
	 * @return array|mixed|null
	 * @throws Exception
	 */
	private function getBuildProductItems()
	{

		$override = $this->modelModule->getOverride('product',__FUNCTION__);

		if(!$override) {
			$collection = $this->getItems('product', array(':id' => $this->id, ':iso' => $this->lang), 'one', false);
			$imgCollection = $this->getItems('images', array(':id' => $this->id, ':iso' => $this->lang), 'all', false);
			$associatedCollection = $this->getItems('similar', array(':id' => $this->id, ':iso' => $this->lang), 'all', false);
			if ($imgCollection != null) {
				$collection['img'] = $imgCollection;
			}

			if ($associatedCollection != null) {
				$collection['associated'] = $associatedCollection;
			}

			return $this->modelCatalog->setItemData($collection, []);
		}else{

			return $override;
		}
	}

    /**
     * @deprecated
     * set Data from database
     * @access private
     */
    private function getBuildCategoryItems()
    {
        $override = $this->modelModule->getOverride('category',__FUNCTION__);
        if(!$override) {
            $collection = $this->getItems('cat', array(':id' => $this->id, ':iso' => $this->lang), 'one', false);
            return $this->modelCatalog->setItemData($collection, []);
        }else{
            return $override;
        }
    }
	// ---------------

	// --- Root
	/**
	 * @return array
	 */
	private function getRoot(): array {
		$collection = $this->getItems('root',['iso' => $this->lang],'all',false);
		$newData = [];
		if(!empty($collection)) {
			foreach ($collection as $item) {
				$newData[$item['name_info']] = $item['value_info'];
			}
		}
		return $this->modelCatalog->setItemData($newData,[]);
	}
	// ---------------

	// --- Categories
	/**
	 * @param $id_parent
	 * @param array $filter
	 * @return array
	 * @throws Exception
	 */
	public function getCategoryList($id_parent = NULL, string $listids = NULL, $order = NULL, array $filter = []) : array {
		if(isset($this->filter)){
			$filter = $this->filter;
		}
		$newTableArray = [];
		$override = $this->modelModule->extendDataArray('category',__FUNCTION__);

		if($override) {
			foreach ($override as $key => $value) {
				$newTableArray = array_merge_recursive($newTableArray, $value);
			}

		}

        $params = [
            'iso' => $this->lang/*,
            'where' => [
                ['type' => 'WHERE',
                    'condition' => 'lang.iso_lang = :iso'
                ],
                ['type' => 'AND',
                    'condition' => 'catc.published_cat = 1'
                ]
            ]*/
        ];
        if(!empty($listids)) $params['listids'] = $listids;
        if ($order !== NULL) {
            // On l'adapte au format attendu par votre fonction db (tableau de tableaux)
            $params['order'] = is_array($order) ? $order : [[$order]];
        }
		/*if(is_null($id_parent)) {
            $params['where'][] = [
                'type' => 'AND',
                'condition' => 'cat.id_parent IS NULL'
            ];
		}*/
        /*if(!is_null($id_parent)) {
            $params['id_parent'] = $id_parent;
            $params['where'][] = [
                'type' => 'AND',
                'condition' => 'cat.id_parent = :id_parent'
            ];
		}*/
		if($newTableArray) {
			//print_r(array_merge($newTableArray['extendQueryParams'], $newTableArray['filterQueryParams']));
			$extendQueryParams = [];
			$extendQueryParams[] = $newTableArray['extendQueryParams'];
			//print_r($extendQueryParams);
			//$params = [];
			if(!empty($extendQueryParams)) {
				foreach ($extendQueryParams as $extendParams) {
					if(isset($extendParams['select']) && !empty($extendParams['select'])) $params['select'][] = $extendParams['select'];
					if(isset($extendParams['join']) && !empty($extendParams['join'])) $params['join'][] = $extendParams['join'];
					if(isset($extendParams['where']) && !empty($extendParams['where'])) $params['where'][] = $extendParams['where'];
					if(isset($extendParams['order']) && !empty($extendParams['order'])) $params['order'][] = $extendParams['order'];

					if(!empty($filter)){
						if(isset($extendParams['limit']) && !empty($extendParams['limit'])) $params['limit'][] = $extendParams['limit'];
						if(isset($extendParams['order']) && !empty($extendParams['order'])) $params['order'] = $extendParams['order'];
						if(isset($extendParams['filter']) && !empty($extendParams['filter'])) $params['where'][] = is_array($extendParams['where']) ? array_merge($extendParams['where'],$extendParams['filter']) : $extendParams['filter'];
					}
				}
			}
			/*print '<pre>';
			print_r($params);
			print '</pre>';*/
			//$collection = $this->getItems('category', array_merge($defaultParams,$params), 'all', false);
		}

		$extendNumberProduct = $this->modelModule->extendDataArray('category','extendNbProduct', $filter);

		if($extendNumberProduct) {
			$newNumbertableArray = [];
			foreach ($extendNumberProduct as $value) {
				$newNumbertableArray = array_merge_recursive($newNumbertableArray, $value);
			}
			$extendQueryParams = [];
			$extendQueryParams[] = $newNumbertableArray['extendQueryParams'];
			$nbParams = [];
			if(!empty($extendQueryParams)) {
				foreach ($extendQueryParams as $extendParams) {
					if(isset($extendParams['join']) && !empty($extendParams['join'])) $nbParams['join'][] = $extendParams['join'];
					if(isset($extendParams['where']) && !empty($extendParams['where'])) $nbParams['where'][] = $extendParams['where'];

					if(!empty($filter)){
						if(isset($extendParams['filter']) && !empty($extendParams['filter'])) $nbParams['where'][] = $extendParams['filter'];
					}
				}
			}
            $params['nbParams'] = $nbParams;
		}
        /*print '<pre>';
        print_r($params);
        print '</pre>';*/
        $collection = $this->getItems('category',$params,'all',false);
        unset($params);
		/*foreach ($collection as $key => $value){
			//$childCat = $this->getItems('childCat', ['id_parent'=>$value['id_cat'],'id'=>$value['id_cat']], 'one', false);
			if(!is_null($value['childs'])){
				$nbProduct = $this->getItems('nbProduct', isset($params) ? array_merge(['id_cat'=>$value['childs'],'iso' => $this->lang],$params) : ['id_cat'=>$value['childs'],'iso' => $this->lang], 'one', false);
				$collection[$key]['nb_product'] = $nbProduct['nb_product'];
			}
		}*/

		/*print '<pre>';
		print_r($collection);
		print '</pre>';*/
        $newRow = [];
        $newTree = [];
		if($newTableArray) {
            /*print '<pre>';
            print_r($newTableArray);
            print '</pre>';*/
			if(isset($newTableArray['collection'])){
				$extendFormArray = [];
				if(is_array($newTableArray['collection'])){
					foreach ($newTableArray['collection'] as $value){
						$extendFormArray[] = $value;
					}
				}
                else{
					$extendFormArray[] = $newTableArray['collection'];
				}
				$extendFormData = $this->modelModule->extendDataArray('category','extendListCategory', $collection);
				foreach ($collection as $key => $value){
					foreach ($extendFormData as $key1 => $value1) {
						$collection[$key][$extendFormArray[$key1]] = $value1[$key];
					}
				}
				$newRow = $newTableArray['newRow'];
                $newTree = $newTableArray['type'] ?? [];
			}
		}
        $setTree = !empty($newTree) ? $newTree : 'root';
        $isFlatMode = !empty($listids);

        $newSetArray = [];
        if(!empty($collection)) {
			$collection = array_map(function(&$row){
				$row['products'] = $this->getProductList($row['id_cat'],false, NULL,NULL,[]);
				return $row;
			},$collection);
			//$newRow[] = ['products' => 'products'];
			//$newRow[] = ['products'];
			$newRow['products'] = 'products';
            $newSetArray = $this->data->setPagesTree($collection,'cat', $id_parent ?? $setTree ,'all',$this->modelCatalog,false, $newRow, $isFlatMode);
            /*print '<pre>';
            print_r($newSetArray);
            print '</pre>';*/
            /*foreach ($collection as $item) {
                $newSetArray[] = $this->modelCatalog->setItemData($item, [], $newRow);
            }*/
            if($id_parent !== null) $newSetArray = empty($newSetArray[0]['subdata']) ? [] : $newSetArray[0]['subdata'];
        }

		return $newSetArray;
	}

    /**
     * @return array
     * @throws Exception
     */
    public function getCategoryData() : array {
        $newTableArray = [];
        $override = $this->modelModule->extendDataArray('category',__FUNCTION__);
        if($override) {
            foreach ($override as $value) {
                $newTableArray = array_merge_recursive($newTableArray, $value);
            }
        }
        if(!$newTableArray) {
            $collection = $this->getItems('category', ['id' => $this->id, 'iso' => $this->lang], 'one', false);
        }
		else {
            $extendQueryParams = [];
            $extendQueryParams[] = $newTableArray['extendQueryParams'];
            $params = [];
            if(!empty($extendQueryParams)) {
                foreach ($extendQueryParams as $extendParams) {
                    if(isset($extendParams['select']) && !empty($extendParams['select'])) $params['select'][] = $extendParams['select'];
                    if(isset($extendParams['join']) && !empty($extendParams['join'])) $params['join'][] = $extendParams['join'];
                    if(isset($extendParams['where']) && !empty($extendParams['where'])) $params['where'][] = $extendParams['where'];
                }
            }
            $collection = $this->getItems('category', array_merge(['id' => $this->id, 'iso' => $this->lang],$params), 'one', false);
        }

        if(!$newTableArray) {
            $extendProductData = $this->modelModule->extendDataArray('category','extendCategoryData', $collection);
			$newRow = [];

            if($extendProductData) {
                $extendRow = [];
                foreach ($extendProductData as $value) {
                    foreach ($value['newRow'] as $key => $item) {
                        $extendRow['newRow'][$key] = $item;
                        $extendRow['collection'][$key] = $value['collection'];
                        $extendRow['data'][$key] = $value['data'];
                        $collection[$value['collection']] = $value['data'];
                    }
                }
                $newRow = $extendRow['newRow'];
            }

			return $this->modelCatalog->setItemData($collection, [], $newRow);
        }
		else {
            if(isset($newTableArray['collection'])){
                $extendFormArray = [];
                foreach ($newTableArray['collection'] as $value){
                    $extendFormArray[] = $value;
                }
                $extendFormData = $this->modelModule->extendDataArray('category','extendCategory', $collection);
                foreach ($extendFormData as $key => $value) {
                    $collection[$extendFormArray[$key]] = $value;
                }
            }
            $extendProductData = $this->modelModule->extendDataArray('category','extendCategoryData', $collection);

            if($extendProductData) {
                $extendRow = [];
                foreach ($extendProductData as $value) {
                    foreach ($value['newRow'] as $key => $item) {
                        $extendRow['newRow'][$key] = $item;
                        $extendRow['collection'][$key] = $value['collection'];
                        $extendRow['data'][$key] = $value['data'];
                        $collection[$value['collection']] = $value['data'];
                    }
                }
                $newRow = array_merge($newTableArray['newRow'], $extendRow['newRow']);
            }
			else{
                $newRow = $newTableArray['newRow'];
            }

            return $this->modelCatalog->setItemData($collection, [], $newRow);
        }
    }
	// ---------------

	// --- Products
    /**
     * @param int|NULL $id_cat
     * @param bool $count
     * @param array $filter
     * @param string|NULL $listids
     * @return array
     * @throws Exception
     */
    public function getProductList(int $id_cat = NULL, bool $count = false, string $listids = NULL, $order = NULL, array $filter = []) : array {
        if(isset($this->filter)) $filter = $this->filter;

        $newTableArray = [];

        $override = $this->modelModule->extendDataArray('product',__FUNCTION__, $filter);

        if(!empty($override)) {
            foreach ($override as $value) {
                $newTableArray = array_merge_recursive($newTableArray, $value);
            }
        }

        $params = ['iso' => $this->lang];

        if(!empty($id_cat)) $params['id_cat'] = $id_cat;
        if(!empty($listids)) $params['listids'] = $listids;
        if ($order !== NULL) {
            // On l'adapte au format attendu par votre fonction db (tableau de tableaux)
            $params['order'] = is_array($order) ? $order : [[$order]];
        }

        //if(!$count) $limit = [($this->page * $this->offset) . ', ' . $this->offset];
        //if(!empty($limit)) $params['limit'] = $limit;
        //print_r($newTableArray);
        if(!empty($newTableArray)) {
            $extendQueryParams = [];
            $extendQueryParams[] = $newTableArray['extendQueryParams'];

            if(!empty($extendQueryParams)) {
                foreach ($extendQueryParams as $extendParams) {
                    if(isset($extendParams['select']) && !empty($extendParams['select'])) $params['select'][] = $extendParams['select'];
                    if(isset($extendParams['join']) && !empty($extendParams['join'])) $params['join'][] = $extendParams['join'];
                    if(isset($extendParams['where']) && !empty($extendParams['where'])) $params['where'][] = $extendParams['where'];
                    if(isset($extendParams['order']) && !empty($extendParams['order'])) $params['order'][] = $extendParams['order'];
                    if(isset($extendParams['group']) && !empty($extendParams['group'])) $params['group'][] = $extendParams['group'];
                    if(isset($extendParams['having']) && !empty($extendParams['having'])) $params['having'][] = $extendParams['having'];
                    if(isset($extendParams['limit']) && !empty($extendParams['limit'])) $params['limit'][] = $extendParams['limit'];

                    //if(!empty($filter)){
                    if(isset($extendParams['filter']) && !empty($extendParams['filter'])) $params['where'][] = is_array($extendParams['where']) ? array_merge($extendParams['where'],$extendParams['filter']) : $extendParams['filter'];
                    //}
                }
            }
        }

        if(!$count) {
            //print !$count ? '! count':'count';
            //print_r($params);
            $collection = $this->getItems('product', $params, 'all', false);
            //print_r($collection);
            $newSetArray = [];
            if(!empty($collection)) {
                if(empty($newTableArray)){
                    foreach ($collection as &$item) {
                        $newSetArray[] = $this->modelCatalog->setItemData($item, []);
                    }
                }
                else {
                    if(isset($newTableArray['collection'])){
                        $extendFormArray = [];

                        if(is_array($newTableArray['collection'])) {
                            foreach ($newTableArray['collection'] as $value){
                                $extendFormArray[] = $value;
                            }
                        }
                        else {
                            $extendFormArray[] = $newTableArray['collection'];
                        }
                        $extendFormData = $this->modelModule->extendDataArray('product','extendListProduct', $collection);
                        $extendFormData = array_filter($extendFormData);
                        foreach ($collection as $key => $value){
                            foreach ($extendFormData as $key1 => $value1) {
                                $collection[$key][$extendFormArray[$key1]] = $value1[$key];
                            }
                        }

                        $newRow = $newTableArray['newRow'];
                        foreach ($collection as &$item) {
                            $newSetArray[] = $this->modelCatalog->setItemData($item, [], $newRow);
                        }
                    }
                }
            }

            return $newSetArray;
        }
        else {
            $collection = $this->getItems('count_product',$params, 'one', false);
            //print_r($collection);
            return [
                'total' => empty($collection) ? 0 : $collection['total'],
                'nbp' => empty($collection) ? 1 : ceil(($collection['total'] / $this->offset))
            ];
        }
    }

    /**
     * @param int $id
     * @param array $filter
     * @return array
     * @throws Exception
     */
    private function getProductSimilar(int $id, array $filter = []) : array {
        if(isset($this->filter)){
            $filter = $this->filter;
        }

        $newTableArray = [];
        $override = $this->modelModule->extendDataArray('product',"getProductList", $filter);

        if($override) {

            foreach ($override as $key => $value) {
                $newTableArray = array_merge_recursive($newTableArray, $value);
            }
        }

        $defaultParams = array('id' => $id, 'iso' => $this->lang);

        if(!$newTableArray) {
            $collection = $this->getItems('similar',$defaultParams ,'all', false);
        }
		else{
            //print_r(array_merge($newTableArray['extendQueryParams'], $newTableArray['filterQueryParams']));
            $extendQueryParams = [];
            $extendQueryParams[] = $newTableArray['extendQueryParams'];
            //print_r($extendQueryParams);
            $params = [];
            if(!empty($extendQueryParams)) {
                foreach ($extendQueryParams as $extendParams) {

                    if(isset($extendParams['select']) && !empty($extendParams['select'])) $params['select'][] = $extendParams['select'];
                    if(isset($extendParams['join']) && !empty($extendParams['join'])) $params['join'][] = $extendParams['join'];
                    if(isset($extendParams['where']) && !empty($extendParams['where'])) $params['where'][] = $extendParams['where'];
                    if(isset($extendParams['order']) && !empty($extendParams['order'])) $params['order'][] = $extendParams['order'];

                    if(!empty($filter)){
                        if(isset($extendParams['limit']) && !empty($extendParams['limit'])) $params['limit'][] = $extendParams['limit'];
                        if(isset($extendParams['order']) && !empty($extendParams['order'])) $params['order'][] = $extendParams['order'];
                        if(isset($extendParams['filter']) && !empty($extendParams['filter'])) $params['where'][] = is_array($extendParams['where']) ? array_merge($extendParams['where'],$extendParams['filter']) : $extendParams['filter'];
                    }
                }
            }
            /*print '<pre>';
            print_r($params);
            print '</pre>';*/
            $collection = $this->getItems('similar', array_merge($defaultParams,$params), 'all', false);

        }
        $newSetArray = [];
        if(!$newTableArray){
            foreach ($collection as &$item) {
                $newSetArray[] = $this->modelCatalog->setItemData($item, []);
            }
        }
		else{
            if(isset($newTableArray['collection'])){
                $extendFormArray = [];

                if(is_array($newTableArray['collection'])){
                    foreach ($newTableArray['collection'] as $key => $value){
                        $extendFormArray[] = $value;
                    }
                }else{
                    $extendFormArray[] = $newTableArray['collection'];
                }
                /*print '<pre>';
                print_r($newTableArray['collection']);
                print '</pre>';*/
                $extendFormData = $this->modelModule->extendDataArray('product','extendListProduct', $collection);

                foreach ($collection as $key => $value){
                    foreach ($extendFormData as $key1 => $value1) {
                        $collection[$key][$extendFormArray[$key1]]/*[$key]*/ = $value1[$key];
                    }

                }

                $newRow = $newTableArray['newRow'];
                foreach ($collection as &$item) {
                    $newSetArray[] = $this->modelCatalog->setItemData($item, [], $newRow);
                }
            }
        }
        return $newSetArray;
    }

    /**
     * @param int|null $id
     * @return array
     * @throws Exception
     */
    public function getProductData(int $id = null) : array {
        if($id !== null) $this->id = $id;
        $newTableArray = [];
        $override = $this->modelModule->extendDataArray('product',__FUNCTION__);
        if($override) {
            foreach ($override as $value) {
                $newTableArray = array_merge_recursive($newTableArray, $value);
            }
        }

        if(!$newTableArray){
            $collection = $this->getItems('product', array('id' => $this->id, 'iso' => $this->lang), 'one', false);
        }
        else{
            $extendQueryParams = [];
            $extendQueryParams[] = $newTableArray['extendQueryParams'];

            $params = [];
            if(!empty($extendQueryParams)) {
                foreach ($extendQueryParams as $extendParams) {
                    if(isset($extendParams['select']) && !empty($extendParams['select'])) $params['select'][] = $extendParams['select'];
                    if(isset($extendParams['join']) && !empty($extendParams['join'])) $params['join'][] = $extendParams['join'];
                    if(isset($extendParams['where']) && !empty($extendParams['where'])) $params['where'][] = $extendParams['where'];
                }
            }
            $collection = $this->getItems('product', array_merge(array('id' => $this->id, 'iso' => $this->lang),$params), 'one', false);
        }
        $imgCollection = $this->getItems('images', array('id' => $this->id, 'iso' => $this->lang), 'all', false);

        if ($imgCollection != null) $collection['img'] = $imgCollection;

        if(!$newTableArray){
            $extendProductData = $this->modelModule->extendDataArray('product','extendProductData', $collection);
			$newRow = [];
            if($extendProductData) {
                $extendRow = [];
                foreach ($extendProductData as $value) {
                    foreach ($value['newRow'] as $key => $item) {
                        $extendRow['newRow'][$key] = $item;
                        $extendRow['collection'][$key] = $value['collection'];
                        $extendRow['data'][$key] = $value['data'];
                        $collection[$value['collection']] = $value['data'];
                    }
                }
                $newRow = $extendRow['newRow'];
            }
			return $this->modelCatalog->setItemData($collection, [], $newRow);
        }
        else{
            if(isset($newTableArray['collection'])){
                $extendFormArray = [];
                if(is_array($newTableArray['collection'])) {
                    foreach ($newTableArray['collection'] as $key => $value) {
                        $extendFormArray[] = $value;
                    }
                }else{
                    $extendFormArray[] = $newTableArray['collection'];
                }
                $extendFormData = $this->modelModule->extendDataArray('product','extendProduct', $collection);
                foreach ($extendFormData as $key => $value) {
                    $collection[$extendFormArray[$key]] = $value;
                }
            }

            $extendProductData = $this->modelModule->extendDataArray('product','extendProductData', $collection);
            if($extendProductData) {
                $extendRow = [];
                foreach ($extendProductData as $value) {
                    foreach ($value['newRow'] as $key => $item) {
                        $extendRow['newRow'][$key] = $item;
                        $extendRow['collection'][$key] = $value['collection'];
                        $extendRow['data'][$key] = $value['data'];
                        $collection[$value['collection']] = $value['data'];
                    }
                }
                $newRow = array_merge($newTableArray['newRow'], $extendRow['newRow']);
            }
            else{
                $newRow = $newTableArray['newRow'];
            }

            return $this->modelCatalog->setItemData($collection, [], $newRow);
        }
    }
	// ---------------

	// --- Deprecated
    /**
     * Return data Lang
     * @param string $type
     * @return array
     */
    private function setHrefLangItems(string $type): array {
        switch($type){
            case 'cat':
                $collection = $this->getItems('catLang',[':id' => $this->id],'all',false);
                return $this->modelCatalog->setHrefLangCategoryData($collection);
            case 'product':
                $collection = $this->getItems('productLang',[':id' => $this->id],'all',false);
                return $this->modelCatalog->setHrefLangProductData($collection);
			default:
				return [];
        }
    }

    /**
     * Assign page's data to smarty
     * @access private
     * @param string $type
	 * @return void
     */
    private function getData(string $type) {
		$data = $this->getRoot();
		$this->template->assign('root',$data,true);

		if($type != 'root') {
			$hreflang = $this->setHrefLangItems($type);
			$this->template->assign('hreflang',$hreflang,true);
            $this->template->breadcrumb->addItem(
                $data['name'] ?? $this->template->getConfigVars('catalog'),
                '/'.$this->template->lang.($this->template->is_amp() ? '/amp' : '').'/catalog/',
                $data['name'] ?? $this->template->getConfigVars('catalog')
            );
		}
        else {
            $this->template->breadcrumb->addItem($data['name'] ?? $this->template->getConfigVars('catalog'));
        }

        switch($type){
            case 'root':
                $cats = $this->getCategoryList();
                $this->template->assign('categories',$cats,true);
                //$products = $this->getProductList(NULL,false,$this->filter ?? []);
				//$this->template->assign('nbp',$this->getProductList(NULL,true,$this->filter ?? []));
				//$this->template->assign('products',$products,true);
                break;
            case 'cat':
                $data = $this->getCategoryData();
				$cats = $this->getCategoryList($this->id);
				$products = $this->getProductList($this->id,false, NULL,NULL,$this->filter ?? []);
				//$this->template->assign('nbp',$this->getProductList($this->id,true,$this->filter ?? []));
				$this->template->assign('cat',$data,true);
				$this->template->assign('categories',$cats,true);
				$this->template->assign('products',$products,true);
                break;
            case 'product':
                $data = $this->getProductData();
                $this->template->assign('product',$data,true);
                $associated = $this->getProductSimilar($this->id,$this->filter ?? []);
                $this->template->assign('associated',$associated,true);
                break;
        }

		if(isset($data['id_parent'])) {
			$this->id = $data['id_parent'];
			$parent = $this->getCategoryData();
            $parent['subdata'] = $this->getCategoryList($data['id_parent']);
			$this->template->assign('parent',$parent,true);
            if(!empty($parent)) $this->template->breadcrumb->addItem(
                $parent['name'],
                $parent['url'],
				!empty($parent['link']['title']) ? $parent['link']['title'] : $this->template->getConfigVars('category').': '.$parent['name']
            );
		}
        if($type != 'root') $this->template->breadcrumb->addItem($data['name']);
    }

    /**
     * @access public
     * run app
     */
    public function run() {
        if(isset($this->id) && !isset($this->id_parent)) {
            $this->getData('cat');
            $this->template->display('catalog/category/index.tpl');
        }
        elseif(isset($this->id) && isset($this->id_parent)) {
            $this->getData('product');
            $this->template->display('catalog/product/index.tpl');
        }
        else {
            $this->getData('root');
            $this->template->display('catalog/index.tpl');
        }
    }
}