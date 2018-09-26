<?php
class frontend_controller_news extends frontend_db_news
{
    /**
     * @var
     */
    protected $template, $header, $data, $modelNews, $modelCore, $dateFormat, $routingUrl;
    public $getlang, $id, $id_parent, $date, $year, $month, $tag, $offset, $page = 0;

    /**
	 * @param stdClass $t
     * frontend_controller_pages constructor.
     */
    public function __construct($t = null){
		$this->template = $t ? $t : new frontend_model_template();
		$formClean = new form_inputEscape();
        $this->header = new component_httpUtils_header($this->template);
        $this->data = new frontend_model_data($this);
        $this->getlang = $this->template->currentLanguage();
        $this->modelNews = new frontend_model_news($this->template);
        $this->dateFormat = new date_dateformat();
		$this->routingUrl = new component_routing_url();
		$this->offset = 5;

        if (http_request::isGet('id')) {
            $this->id = $formClean->numeric($_GET['id']);
        }
        if (http_request::isGet('date')) {
            $this->date = $formClean->simpleClean($_GET['date']);
        }
        if (http_request::isGet('year')) {
            $this->year = $formClean->simpleClean($_GET['year']);
        }
        if (http_request::isGet('month')) {
            $this->month = $formClean->simpleClean($_GET['month']);
        }
        if (http_request::isGet('tag')) {
            $this->tag = $formClean->simpleClean($_GET['tag']);
        }
        if (http_request::isGet('page')) {
            $this->page = $formClean->simpleClean($_GET['page']) - 1;
        }
        /*if (http_request::isGet('id_parent')) {
            $this->id_parent = $formClean->numeric($_GET['id_parent']);
        }*/
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
	 * @param bool $count
	 * @return array|float|null
	 */
    private function getBuildList($count = false)
    {
		$conditions = '';

		if(isset($this->tag)) $conditions .= ' JOIN mc_news_tag_rel AS ntr ON(c.id_news = ntr.id_news)';

		$conditions .= ' WHERE lang.iso_lang = :iso';
		$params = array('iso' => $this->getlang);


		if(isset($this->date)) {
			$conditions .= ' AND c.date_publish = :date';
			$params['date'] = $this->dateFormat->SQLDate($this->date);
		}
		elseif(isset($this->year)) {
			$conditions .= ' AND YEAR(c.date_publish) = :yr';
			$params['yr'] = $this->year;

			if(isset($this->month)) {
				$conditions .= ' AND MONTH(c.date_publish) = :mth';
				$params['mth'] = $this->month;
			}
		}
		else {
			$conditions .= ' AND c.date_publish <= :date';
			$params['date'] = $this->dateFormat->SQLDate();
		}

		if(isset($this->tag)) {
			$conditions .= ' AND ntr.id_tag = :tag';
			$params['tag'] = $this->tag;
		}

		$conditions .= ' AND c.published_news = 1 ORDER BY c.date_publish DESC, p.id_news DESC'.(!$count ? ' LIMIT '.($this->page * $this->offset).', '.$this->offset : '');

		$collection = parent::fetchData(
			array('context' => ($count ? 'one':'all'), 'type' => ($count ? 'count_news':'pages'), 'conditions' => $conditions),
			$params
		);

		if($collection) {
			if(!$count) {
				$newarr = array();
				foreach ($collection as $k => &$item) {
					$tags = parent::fetchData(
						array('context' => 'all', 'type' => 'tagsRel'),
						array(
							':iso' => $item['iso_lang'],
							':id'  => $item['id_news']
						)
					);
					if($tags != null) {
						$item['tags'] = $tags;
					}
					$newarr[] = $this->modelNews->setItemData($item,null);
				}
				return $newarr;
			}
			else {
				return ceil(($collection['nbp']/ $this->offset));
			}
		}
		return null;
    }

    /**
     * set Data from database
     * @access private
     */
    private function getBuildTagList()
    {
		$conditions = ' WHERE lang.iso_lang = :iso ';
		$data = parent::fetchData(
			array('context' => 'all', 'type' => 'tags', 'conditions' => $conditions),
			array(':iso' => $this->getlang)
		);

		$newarr = array();
		foreach ($data as $k => &$item) {
			$newarr[] = $this->modelNews->setItemData($item,null);
		}
		return $newarr;
    }

    /**
     * set Data from database
     * @access private
     */
    private function getBuildItems()
    {
        $collection = $this->getItems('page',array('id'=>$this->id,'iso'=>$this->getlang),'one',false);
        $tagsCollection = $this->getItems('tagsRel',array('id'=>$this->id,'iso'=>$this->getlang),'all',false);
        if($tagsCollection != null){
            $collection['tags'] = $tagsCollection;
        }

		$collection['prev'] = null;
		$prev = $this->getItems('prev_page',array('id'=>$this->id,'iso'=>$this->getlang,'date_publish'=>$collection['date_publish']),'one',false);
        if($prev) {
        	$collection['prev']['title'] = $prev['name_news'];
			$collection['prev']['url'] = $this->routingUrl->getBuildUrl(array(
				'type' => 'news',
				'iso'  => $prev['iso_lang'],
				'date' => $prev['date_publish'],
				'id'   => $prev['id_news'],
				'url'  => $prev['url_news']
			));
		}

		$collection['next'] = null;
		$next = $this->getItems('next_page',array('id'=>$this->id,'iso'=>$this->getlang,'date_publish'=>$collection['date_publish']),'one',false);
        if($next) {
        	$collection['next']['title'] = $next['name_news'];
			$collection['next']['url'] = $this->routingUrl->getBuildUrl(array(
				'type' => 'news',
				'iso'  => $next['iso_lang'],
				'date' => $next['date_publish'],
				'id'   => $next['id_news'],
				'url'  => $next['url_news']
			));
		}

        return $this->modelNews->setItemData($collection,null);
    }
    
    /**
     * @return array
     */
    private function getBuildLangItems(){
        $collection = $this->getItems('langs',array(':id'=>$this->id),'all',false);
        return $this->modelNews->setHrefLangData($collection);
    }

    /**
     * Assign page's data to smarty
     * @param $type
     */
    private function getData($type)
    {
        switch($type){
            case 'tag':
            	$this->getItems('tag',$this->tag,'one');
                break;
            case 'id':
                $data = $this->getBuildItems();
                $hreflang = $this->getBuildLangItems();
                $this->template->assign('news',$data,true);
                $this->template->assign('hreflang',$hreflang,true);
                break;
        }
    }
	/**
	 * @return array
	 */
    private function getBuildArchive(){
		$data = parent::fetchData(
			array('context' => 'all', 'type' => 'archives'),
			array('iso' => $this->getlang)
		);
		$arch = array();
		foreach ($data as $arr) {
			$months = explode(',',$arr['mths']);
			$months = array_reverse($months);
			foreach ($months as $k => $month) {
				$nbr =parent::fetchData(
					array('context' => 'one', 'type' => 'nb_archives'),
					array('iso' => $this->getlang, 'yr' => $arr['yr'], 'mth' => $month)
				);
				$month = array(
					'month' => $month,
					'url' => $this->routingUrl->getBuildUrl(array(
							'type'  =>  'date',
							'iso'   =>  $this->getlang,
							'year'  =>  $arr['yr'],
							'month' =>  $month,
						)
					),
					'nbr' => $nbr['nbr']
				);
				$months[$k] = $month;
			}
			$arch[] = array(
				'year' => $arr['yr'],
				'url' => $this->routingUrl->getBuildUrl(array(
						'type'  =>  'date',
						'iso'   =>  $this->getlang,
						'year'  =>  $arr['yr']
					)
				),
				'months' => $months
			);
		}
		return $arch;
    }

    /**
     * @access public
     * run app
     */
    public function run(){
		$this->template->assign('tags',$this->getBuildTagList());
		$this->template->assign('archives',$this->getBuildArchive());

        if(isset($this->id) && isset($this->date)) {
            $this->getData('id');
            $this->template->display('news/news.tpl');
        }
        else {
			$this->template->assign('news',$this->getBuildList());
			$this->template->assign('nbp',$this->getBuildList(true));

			if(isset($this->year) OR isset($this->month) OR isset($this->date)) {
				if(isset($this->month)) {
					$monthName = strftime("%B", mktime(0, 0, 0, $this->month, 1, 2000));
					$this->template->assign('monthName',$monthName);
				}
				$this->template->display('news/date.tpl');
			}
			elseif(isset($this->tag)) {
				$this->getData('tag');
				$this->template->display('news/tag.tpl');
			}
			else {
				$this->template->display('news/index.tpl');
			}
		}
    }
}