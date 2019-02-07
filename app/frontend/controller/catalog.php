<?php
class frontend_controller_catalog extends frontend_db_catalog {
    /**
     * @var
     */
    protected $template,$header,$data,$modelCatalog,$modelCore;
    public $getlang,$id,$id_parent;

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
        if (http_request::isGet('id')) {
            $this->id = $formClean->numeric($_GET['id']);
        }
        if (http_request::isGet('id_parent')) {
            $this->id_parent = $formClean->numeric($_GET['id_parent']);
        }
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
        $collection = $this->getItems('root',array(':iso'=>$this->getlang),'all',false);

        $newData = array();
        foreach ($collection as $item) {
            $newData[$item['name_info']] = $item['value_info'];
        }

        return $this->modelCatalog->setItemData($newData,null);
    }


    /**
     * set Data from database
     * @access private
     */
    private function getBuildCategoryList()
    {
		$conditions = ' WHERE lang.iso_lang = :iso AND c.published_cat = 1 AND p.id_parent IS NULL ORDER BY p.order_cat';
		$collection = parent::fetchData(
			array('context' => 'all', 'type' => 'category', 'conditions' => $conditions),
			array(':iso' => $this->getlang)
		);
        $newarr = array();
		foreach ($collection as $item) {
			$newarr[] = $this->modelCatalog->setItemData($item,null);
        }
        return $newarr;
    }


	/**
	 * set Data from database
	 * @access private
	 * @return array
	 */
    private function getBuildSubCategoryList()
    {
		$conditions = ' WHERE lang.iso_lang = :iso AND c.published_cat = 1 AND p.id_parent = :id_parent ORDER BY p.order_cat';
		$collection = parent::fetchData(
			array('context' => 'all', 'type' => 'category', 'conditions' => $conditions),
			array('iso' => $this->getlang,'id_parent' => $this->id)
		);
        $newarr = array();
		foreach ($collection as $item) {
			$newarr[] = $this->modelCatalog->setItemData($item,null);
        }
        return $newarr;
    }


	/**
	 * set Data from database
	 * @access private
	 * @return array
	 */
    private function getBuildProductList()
    {
		$conditions = ' WHERE lang.iso_lang = :iso 
						AND pc.published_p = 1 
						AND (img.default_img = 1 OR img.default_img IS NULL) 
						AND c2.id_cat = :id_cat 
						AND catalog.default_c = 1 
						ORDER BY c2.order_p ASC';
		$collection = parent::fetchData(
			array('context' => 'all', 'type' => 'product', 'conditions' => $conditions),
			array('iso' => $this->getlang,'id_cat' => $this->id)
		);
        $newarr = array();
		foreach ($collection as $item) {
			$newarr[] = $this->modelCatalog->setItemData($item,null);
        }
        return $newarr;
    }


    /**
     * set Data from database
     * @access private
     */
    private function getBuildCategoryItems()
    {
        $collection = $this->getItems('cat',array(':id'=>$this->id,':iso'=>$this->getlang),'one',false);
        return $this->modelCatalog->setItemData($collection,null);
    }

    /**
     * set Data from database
     * @access private
     */
    private function getBuildProductItems()
    {
        $collection = $this->getItems('product',array(':id'=>$this->id,':iso'=>$this->getlang),'one',false);
        $imgCollection = $this->getItems('images',array(':id'=>$this->id,':iso'=>$this->getlang),'all',false);
        $associatedCollection = $this->getItems('similar',array(':id'=>$this->id,':iso'=>$this->getlang),'all',false);
        if($imgCollection != null){
            $collection['img'] = $imgCollection;
        }

        if($associatedCollection != null){
            $collection['associated'] = $associatedCollection;
        }

        return $this->modelCatalog->setItemData($collection,null);
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
                $cats = $this->getBuildCategoryList();
                $this->template->assign('categories',$cats,true);
                break;
            case 'cat':
                $data = $this->getBuildCategoryItems();
				$cats = $this->getBuildSubCategoryList();
				$products = $this->getBuildProductList();
				$this->template->assign('cat',$data,true);
				$this->template->assign('categories',$cats,true);
				$this->template->assign('products',$products,true);
                break;
            case 'product':
                $data = $this->getBuildProductItems();
                $this->template->assign('product',$data,true);
                break;
        }

		if($data['id_parent'] !== null) {
			$this->id = $data['id_parent'];
			$parent = $this->getBuildCategoryItems();
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