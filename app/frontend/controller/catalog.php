<?php
class frontend_controller_catalog extends frontend_db_catalog {
    /**
     * @var
     */
    protected $template,$header,$data,$modelCatalog,$modelCore,$modelModule;
    public $getlang,$id,$id_parent, $filter;

    /**
	 * @param stdClass $t
     * frontend_controller_pages constructor.
     */
    public function __construct($t = null){
        $formClean = new form_inputEscape();
        $this->template = $t ? $t : new frontend_model_template();
        $this->header = new component_httpUtils_header($this->template);
        $this->data = new frontend_model_data($this);
        $this->getlang = $this->template->currentLanguage();
        $this->modelCatalog = new frontend_model_catalog($this->template);
        $this->modelModule = new frontend_model_module($this->template);
        if (http_request::isGet('id')) {
            $this->id = $formClean->numeric($_GET['id']);
        }
        if (http_request::isGet('id_parent')) {
            $this->id_parent = $formClean->numeric($_GET['id_parent']);
        }
        if (http_request::isGet('filter'))  $this->filter = $formClean->arrayClean($_GET['filter']);

    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

	/**
	 * @return array|null
	 */
    private function getBuildRootItems()
    {
        $collection = $this->getItems('root',array('iso'=>$this->getlang),'all',false);

        $newData = array();
        foreach ($collection as $item) {
            $newData[$item['name_info']] = $item['value_info'];
        }

        return $this->modelCatalog->setItemData($newData,null);
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
                array(':iso' => $this->getlang)
            );
            $newarr = array();
            foreach ($collection as $item) {
                $newarr[] = $this->modelCatalog->setItemData($item, null);
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
                array('iso' => $this->getlang, 'id_parent' => $this->id)
            );
            $newarr = array();
            foreach ($collection as $item) {
                $newarr[] = $this->modelCatalog->setItemData($item, null);
            }
            return $newarr;
        }else{
            return $override;
        }
    }

    /**
     * @param $id_parent
     * @param array $filter
     * @return array
     * @throws Exception
     */
    private function getCategoryList($id_parent = NULL, array $filter = []) : array{
        if(isset($this->filter)){
            $filter = $this->filter;
        }
        $newtableArray = [];
        $override = $this->modelModule->extendDataArray('category',__FUNCTION__);

        if($override) {
            foreach ($override as $key => $value) {
                $newtableArray = array_merge_recursive($newtableArray, $value);
            }

        }
        //print_r($newtableArray);
        if(is_null($id_parent)){
            $defaultParams = array('iso' => $this->getlang);

            $defaultParams['where'] = [
                ['type' => 'WHERE',
                    'condition' => 'lang.iso_lang = :iso'
                ],
                ['type' => 'AND',
                    'condition' => 'catc.published_cat = 1'
                ],
                ['type' => 'AND',
                    'condition' => 'cat.id_parent IS NULL'
                ]
            ];
        }else{
            $defaultParams = array('iso' => $this->getlang,'id_parent' => $id_parent);

            $defaultParams['where'] = [
                    ['type' => 'WHERE',
                        'condition' => 'lang.iso_lang = :iso'
                    ],
                    ['type' => 'AND',
                        'condition' => 'catc.published_cat = 1'
                    ],
                    ['type' => 'AND',
                        'condition' => 'cat.id_parent = :id_parent'
                    ]
                ];
        }
        if(!$newtableArray) {
            $collection = $this->getItems('category',$defaultParams,'all',false);
        }else{
            //print_r(array_merge($newtableArray['extendQueryParams'], $newtableArray['filterQueryParams']));
            $extendQueryParams = [];
            $extendQueryParams[] = $newtableArray['extendQueryParams'];
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
                        if(isset($extendParams['order']) && !empty($extendParams['order'])) $params['order'] = $extendParams['order'];
                        if(isset($extendParams['filter']) && !empty($extendParams['filter'])) $params['where'][] = is_array($extendParams['where']) ? array_merge($extendParams['where'],$extendParams['filter']) : $extendParams['filter'];
                    }
                }
            }
            /*print '<pre>';
            print_r($params);
            print '</pre>';*/
            $collection = $this->getItems('product', array_merge($defaultParams,$params), 'all', false);
        }

        $extendNumberProduct = $this->modelModule->extendDataArray('category','extendNbProduct', $filter);

        if($extendNumberProduct){
            //unset($params);
            $newNumbertableArray = [];
            foreach ($extendNumberProduct as $key => $value) {
                $newNumbertableArray = array_merge_recursive($newNumbertableArray, $value);
            }
            $extendQueryParams = [];
            $extendQueryParams[] = $newNumbertableArray['extendQueryParams'];
            $params = [];
            if(!empty($extendQueryParams)) {
                foreach ($extendQueryParams as $extendParams) {
                    if(isset($extendParams['join']) && !empty($extendParams['join'])) $params['join'][] = $extendParams['join'];
                    if(!empty($filter)){
                       if(isset($extendParams['filter']) && !empty($extendParams['filter'])) $params['where'][] = $extendParams['filter'];
                    }
                }
            }
            /*print '<pre>';
            print_r($params);
            print '</pre>';*/
        }

        foreach ($collection as $key => $value){
            $childCat = $this->getItems('childCat', ['id_parent'=>$value['id_cat'],'id'=>$value['id_cat']], 'one', false);
            if(!is_null($childCat)){
                $nbProduct = $this->getItems('nbProduct', isset($params) ? array_merge(['id_cat'=>$childCat['child'],'iso' => $this->getlang],$params) : ['id_cat'=>$childCat['child'],'iso' => $this->getlang], 'one', false);
                $collection[$key]['nb_product'] = $nbProduct['nb_product'];
            }
        }

        /*print '<pre>';
        print_r($collection);
        print '</pre>';*/
        $newSetArray = [];
        if(!$newtableArray){
            foreach ($collection as &$item) {
                $newSetArray[] = $this->modelCatalog->setItemData($item, null);
            }
        }else{
            if(isset($newtableArray['collection'])){
                $extendFormArray = [];

                if(is_array($newtableArray['collection'])){
                    foreach ($newtableArray['collection'] as $key => $value){
                        $extendFormArray[] = $value;
                    }
                }else{
                    $extendFormArray[] = $newtableArray['collection'];
                }
                /*print '<pre>';
                print_r($newtableArray['collection']);
                print '</pre>';*/
                $extendFormData = $this->modelModule->extendDataArray('category','extendListCategory', $collection);

                foreach ($collection as $key => $value){
                    foreach ($extendFormData as $key1 => $value1) {
                        $collection[$key][$extendFormArray[$key1]]/*[$key]*/ = $value1[$key];
                    }

                }

                $newRow = $newtableArray['newRow'];
                foreach ($collection as &$item) {
                    $newSetArray[] = $this->modelCatalog->setItemData($item, null, $newRow);
                }
            }
        }
        return $newSetArray;
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
                array('iso' => $this->getlang, 'id_cat' => $this->id)
            );

            $collection = $this->getItems('product',array('iso' => $this->getlang, 'id_cat' => $this->id),'all',false);

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
            $collection = $this->getItems('cat', array(':id' => $this->id, ':iso' => $this->getlang), 'one', false);
            return $this->modelCatalog->setItemData($collection, null);
        }else{
            return $override;
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getCategoryData() : array{
        $newtableArray = [];
        $override = $this->modelModule->extendDataArray('category',__FUNCTION__);
        if($override) {
            foreach ($override as $key => $value) {
                $newtableArray = array_merge_recursive($newtableArray, $value);
            }
        }
        if(!$newtableArray){
            $collection = $this->getItems('category', array('id' => $this->id, 'iso' => $this->getlang), 'one', false);
        }else{
            $extendQueryParams = [];
            $extendQueryParams[] = $newtableArray['extendQueryParams'];
            //print_r($extendQueryParams);
            $params = [];
            if(!empty($extendQueryParams)) {
                foreach ($extendQueryParams as $extendParams) {
                    if(isset($extendParams['select']) && !empty($extendParams['select'])) $params['select'][] = $extendParams['select'];
                    if(isset($extendParams['join']) && !empty($extendParams['join'])) $params['join'][] = $extendParams['join'];
                    if(isset($extendParams['where']) && !empty($extendParams['where'])) $params['where'][] = $extendParams['where'];
                }
            }
            $collection = $this->getItems('category', array_merge(array('id' => $this->id, 'iso' => $this->getlang),$params), 'one', false);
        }

        if(!$newtableArray){

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
                $newRow = $extendRow['newRow'];

                return $this->modelCatalog->setItemData($collection, null, $newRow);

            }else{

                return $this->modelCatalog->setItemData($collection, null);
            }

        }else{
            if(isset($newtableArray['collection'])){
                $extendFormArray = [];
                foreach ($newtableArray['collection'] as $key => $value){
                    $extendFormArray[] = $value;
                }
                $extendFormData = $this->modelModule->extendDataArray('category','extendCategory', $collection);
                foreach ($extendFormData as $key => $value) {
                    $collection[$extendFormArray[$key]] = $value;
                }
            }
            $extendProductData = $this->modelModule->extendDataArray('category','extendCategoryData', $collection);
            //print_r($extendProductData);
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

                //print_r($extendRow);
                $newRow = array_merge($newtableArray['newRow'], $extendRow['newRow']);
            }else{
                $newRow = $newtableArray['newRow'];
            }

            return $this->modelCatalog->setItemData($collection, null, $newRow);
        }
    }
    /**
     * @param $id_cat
     * @param array $filter
     * @return array
     * @throws Exception
     */
    public function getProductList($id_cat = NULL, array $filter = []) : array{
        if(isset($this->filter)){
            $filter = $this->filter;
        }

        $newtableArray = [];

        $override = $this->modelModule->extendDataArray('product',__FUNCTION__, $filter);

        if($override) {

            foreach ($override as $key => $value) {
                $newtableArray = array_merge_recursive($newtableArray, $value);
            }

        }

        if($id_cat != NULL) {
            $defaultParams = array('id_cat' => $id_cat, 'iso' => $this->getlang);
        }else{
            $defaultParams = array('iso' => $this->getlang);
        }
        if(!$newtableArray) {
            $collection = $this->getItems('product',$defaultParams ,'all', false);
        }else{
            //print_r(array_merge($newtableArray['extendQueryParams'], $newtableArray['filterQueryParams']));
            $extendQueryParams = [];
            $extendQueryParams[] = $newtableArray['extendQueryParams'];
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
            $collection = $this->getItems('product', array_merge($defaultParams,$params), 'all', false);
        }

        $newSetArray = [];
        if(!$newtableArray){
            foreach ($collection as &$item) {
                $newSetArray[] = $this->modelCatalog->setItemData($item, null);
            }
        }else{
            if(isset($newtableArray['collection'])){
                $extendFormArray = [];

                if(is_array($newtableArray['collection'])){
                    foreach ($newtableArray['collection'] as $key => $value){
                        $extendFormArray[] = $value;
                    }
                }else{
                    $extendFormArray[] = $newtableArray['collection'];
                }
                /*print '<pre>';
                print_r($newtableArray['collection']);
                print '</pre>';*/
                $extendFormData = $this->modelModule->extendDataArray('product','extendListProduct', $collection);

                foreach ($collection as $key => $value){
                    foreach ($extendFormData as $key1 => $value1) {
                        $collection[$key][$extendFormArray[$key1]]/*[$key]*/ = $value1[$key];
                    }

                }

                $newRow = $newtableArray['newRow'];
                foreach ($collection as &$item) {
                    $newSetArray[] = $this->modelCatalog->setItemData($item, null, $newRow);
                }
            }
        }
        return $newSetArray;
    }

    /**
     * @param int $id
     * @param array $filter
     * @return array
     * @throws Exception
     */
    private function getProductSimilar(int $id, array $filter = []) : array{
        if(isset($this->filter)){
            $filter = $this->filter;
        }

        $newtableArray = [];
        $override = $this->modelModule->extendDataArray('product',"getProductList", $filter);

        if($override) {

            foreach ($override as $key => $value) {
                $newtableArray = array_merge_recursive($newtableArray, $value);
            }
        }

        $defaultParams = array('id' => $id, 'iso' => $this->getlang);

        if(!$newtableArray) {
            $collection = $this->getItems('similar',$defaultParams ,'all', false);
        }else{
            //print_r(array_merge($newtableArray['extendQueryParams'], $newtableArray['filterQueryParams']));
            $extendQueryParams = [];
            $extendQueryParams[] = $newtableArray['extendQueryParams'];
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
        if(!$newtableArray){
            foreach ($collection as &$item) {
                $newSetArray[] = $this->modelCatalog->setItemData($item, null);
            }
        }else{
            if(isset($newtableArray['collection'])){
                $extendFormArray = [];

                if(is_array($newtableArray['collection'])){
                    foreach ($newtableArray['collection'] as $key => $value){
                        $extendFormArray[] = $value;
                    }
                }else{
                    $extendFormArray[] = $newtableArray['collection'];
                }
                /*print '<pre>';
                print_r($newtableArray['collection']);
                print '</pre>';*/
                $extendFormData = $this->modelModule->extendDataArray('product','extendListProduct', $collection);

                foreach ($collection as $key => $value){
                    foreach ($extendFormData as $key1 => $value1) {
                        $collection[$key][$extendFormArray[$key1]]/*[$key]*/ = $value1[$key];
                    }

                }

                $newRow = $newtableArray['newRow'];
                foreach ($collection as &$item) {
                    $newSetArray[] = $this->modelCatalog->setItemData($item, null, $newRow);
                }
            }
        }
        return $newSetArray;
    }
    /**
     * @return array
     * @throws Exception
     */
    private function getProductData() : array{
        // $override = $this->modelModule->getOverride('product',__FUNCTION__);
        $newtableArray = [];
        $override = $this->modelModule->extendDataArray('product',__FUNCTION__);
        if($override) {
            foreach ($override as $key => $value) {
                $newtableArray = array_merge_recursive($newtableArray, $value);
            }
        }
        //print_r($newtableArray);
        if(!$newtableArray){
            $collection = $this->getItems('product', array('id' => $this->id, 'iso' => $this->getlang), 'one', false);
        }else{
            $extendQueryParams = [];
            $extendQueryParams[] = $newtableArray['extendQueryParams'];
            //print_r($extendQueryParams);
            $params = [];
            if(!empty($extendQueryParams)) {
                foreach ($extendQueryParams as $extendParams) {
                    if(isset($extendParams['select']) && !empty($extendParams['select'])) $params['select'][] = $extendParams['select'];
                    if(isset($extendParams['join']) && !empty($extendParams['join'])) $params['join'][] = $extendParams['join'];
                    if(isset($extendParams['where']) && !empty($extendParams['where'])) $params['where'][] = $extendParams['where'];
                }
            }
            $collection = $this->getItems('product', array_merge(array('id' => $this->id, 'iso' => $this->getlang),$params), 'one', false);
        }

        $imgCollection = $this->getItems('images', array('id' => $this->id, 'iso' => $this->getlang), 'all', false);
        //$associatedCollection = $this->getItems('similar', array('id' => $this->id, 'iso' => $this->getlang), 'all', false);
        if ($imgCollection != null) {
            $collection['img'] = $imgCollection;
        }

        /*if ($associatedCollection != null) {
            $collection['associated'] = $associatedCollection;
        }*/
        if(!$newtableArray){

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
                $newRow = $extendRow['newRow'];

                return $this->modelCatalog->setItemData($collection, null, $newRow);

            }else{

                return $this->modelCatalog->setItemData($collection, null);
            }

        }else{
            if(isset($newtableArray['collection'])){
                $extendFormArray = [];
                foreach ($newtableArray['collection'] as $key => $value){
                    $extendFormArray[] = $value;
                }
                $extendFormData = $this->modelModule->extendDataArray('product','extendProduct', $collection);
                foreach ($extendFormData as $key => $value) {
                    $collection[$extendFormArray[$key]] = $value;
                }
            }

            $extendProductData = $this->modelModule->extendDataArray('product','extendProductData', $collection);
            //print_r($extendProductData);
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

                //print_r($extendRow);
                $newRow = array_merge($newtableArray['newRow'], $extendRow['newRow']);
            }else{
                $newRow = $newtableArray['newRow'];
            }

            return $this->modelCatalog->setItemData($collection, null, $newRow);
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
            $collection = $this->getItems('product', array(':id' => $this->id, ':iso' => $this->getlang), 'one', false);
            $imgCollection = $this->getItems('images', array(':id' => $this->id, ':iso' => $this->getlang), 'all', false);
            $associatedCollection = $this->getItems('similar', array(':id' => $this->id, ':iso' => $this->getlang), 'all', false);
            if ($imgCollection != null) {
                $collection['img'] = $imgCollection;
            }

            if ($associatedCollection != null) {
                $collection['associated'] = $associatedCollection;
            }

            return $this->modelCatalog->setItemData($collection, null);
        }else{

            return $override;
        }
    }

    /**
     * Return data Lang
     * @param $type
     * @return array
     */

    private function getBuildLangItems($type){

        switch($type){
            case 'cat':
                $collection = $this->getItems('catLang',array(':id'=>$this->id),'all',false);
                return $this->modelCatalog->setHrefLangCategoryData($collection);
                break;
            case 'product':
                $collection = $this->getItems('productLang',array(':id'=>$this->id),'all',false);
                return $this->modelCatalog->setHrefLangProductData($collection);
                break;
        }
    }

    /**
     * Assign page's data to smarty
     * @access private
     * @param $type
     */
    private function getData($type)
    {
		$data = $this->getBuildRootItems();
		$this->template->assign('root',$data,true);
		if($type != 'root') {
			$hreflang = $this->getBuildLangItems($type);
			$this->template->assign('hreflang',$hreflang,true);
		}

        switch($type){
            case 'root':
                $cats = $this->getCategoryList();//$this->getBuildCategoryList();
                $this->template->assign('categories',$cats,true);
				//$products = $this->getBuildProductList();
                if(isset($this->filter)){
                    $products = $this->getProductList(NULL,$this->filter);
                }else{
                    $products = $this->getProductList(NULL,$this->filter = []);
                }

				$this->template->assign('products',$products,true);
                break;
            case 'cat':
                $data = $this->getCategoryData();
				$cats = $this->getCategoryList($this->id);
				$products = $this->getProductList($this->id);
				$this->template->assign('cat',$data,true);
				$this->template->assign('categories',$cats,true);
				$this->template->assign('products',$products,true);
                break;
            case 'product':
                $data = $this->getProductData();//$this->getBuildProductItems();
                $this->template->assign('product',$data,true);
                if(isset($this->filter)){
                    $associated = $this->getProductSimilar($this->id,$this->filter);
                }else{
                    $associated = $this->getProductSimilar($this->id,$this->filter = []);
                }
                $this->template->assign('associated',$associated,true);
                break;
        }

		if(isset($data['id_parent'])) {
			$this->id = $data['id_parent'];
			$parent = $this->getCategoryData();
			$this->template->assign('parent',$parent,true);
		}
    }

    /**
     * @access public
     * run app
     */
    public function run(){
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