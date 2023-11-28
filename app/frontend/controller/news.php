<?php
class frontend_controller_news extends frontend_db_news {
	/**
	 * @var frontend_model_template $template
	 * @var component_httpUtils_header $header
	 * @var frontend_model_data $data
	 * @var frontend_model_news $modelNews
	 * @var date_dateformat $dateFormat
	 * @var component_routing_url $routingUrl
	 * @var frontend_model_module $modelCore
	 * @var $modelModule
	 */
	protected frontend_model_template $template;
	protected component_httpUtils_header $header;
	protected frontend_model_data $data;
	protected frontend_model_news $modelNews;
	protected date_dateformat $dateFormat;
	protected component_routing_url $routingUrl;
	protected frontend_model_module $modelCore;
	protected $modelModule;

	/**
	 * @var int $id
	 * @var int $id_parent
	 * @var int $offset
	 * @var int $page
	 */
	public int
		$id,
		$tag,
		$id_parent,
		$offset,
		$page;

	/**
	 * @var string $lang
	 * @var string $date
	 * @var string $year
	 * @var string $month
	 * @var string $tag
	 */
    public string
		$lang,
		$date, 
		$year, 
		$month;

	/**
	 * @var array $tags
	 * @var array $filter
	 */
	public array
		$tags,
		$tagsData,
		$filter;

    /**
	 * frontend_controller_pages constructor.
	 * @param frontend_model_template|null $t
     */
    public function __construct(frontend_model_template $t = null) {
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->header = new component_httpUtils_header($this->template);
        $this->data = new frontend_model_data($this);
        $this->lang = $this->template->lang;
        $this->modelNews = new frontend_model_news($this->template);
        $this->modelModule = new frontend_model_module($this->template);
        $this->dateFormat = new date_dateformat();
		$this->routingUrl = new component_routing_url();
		$this->offset = $this->template->settings['news_per_page'];

        if (http_request::isGet('id')) $this->id = form_inputEscape::numeric($_GET['id']);
        if (http_request::isGet('date')) $this->date = form_inputEscape::simpleClean($_GET['date']);
        if (http_request::isGet('year')) $this->year = form_inputEscape::simpleClean($_GET['year']);
        if (http_request::isGet('month')) $this->month = form_inputEscape::simpleClean($_GET['month']);
        if (http_request::isGet('tag')) $this->tag = form_inputEscape::numeric($_GET['tag']);
        if (http_request::isGet('tags')) $this->tags = form_inputEscape::arrayClean($_GET['tags']);
		if (http_request::isGet('filter')) $this->filter = form_inputEscape::arrayClean($_GET['filter']);
		$this->page = http_request::isGet('page') ? form_inputEscape::numeric($_GET['page']) - 1 : 0;
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param array|int|null $id
     * @param string|null $context
     * @param bool|string $assign
     * @return array|bool
     */
    private function getItems(string $type, $id = null, string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

	/**
	 * @deprecated
	 * @param bool $count
	 * @return array|float|null
	 */
    private function getBuildNewsList($count = false)
    {
        $override = $this->modelModule->getOverride('news',__FUNCTION__,array($count));
        if(!$override) {
            $conditions = '';

            if (isset($this->tag)) $conditions .= ' JOIN mc_news_tag_rel AS ntr ON(c.id_news = ntr.id_news)';

            $conditions .= ' WHERE lang.iso_lang = :iso';
            $params = array('iso' => $this->lang);


            if (isset($this->date)) {
                $conditions .= ' AND c.date_publish = :date';
                $params['date'] = $this->dateFormat->SQLDate($this->date);
            }
			elseif (isset($this->year)) {
                $conditions .= ' AND YEAR(c.date_publish) = :yr';
                $params['yr'] = $this->year;

                if (isset($this->month)) {
                    $conditions .= ' AND MONTH(c.date_publish) = :mth';
                    $params['mth'] = $this->month;
                }
            }
			else {
                $conditions .= ' AND c.date_publish <= :date';
                $params['date'] = $this->dateFormat->SQLDate();
            }

            if (isset($this->tag)) {
                $conditions .= ' AND ntr.id_tag = :tag';
                $params['tag'] = $this->tag;
            }

            $conditions .= ' AND c.published_news = 1 ORDER BY c.date_publish DESC, p.id_news DESC' . (!$count ? ' LIMIT ' . ($this->page * $this->offset) . ', ' . $this->offset : '');

            $collection = parent::fetchData(
                array('context' => ($count ? 'one' : 'all'), 'type' => ($count ? 'count_news' : 'pages'), 'conditions' => $conditions),
                $params
            );

            if ($collection) {
                if (!$count) {
                    $newarr = array();
                    foreach ($collection as $k => &$item) {
                        $tags = parent::fetchData(
                            array('context' => 'all', 'type' => 'tagsRel'),
                            array(
                                ':iso' => $item['iso_lang'],
                                ':id' => $item['id_news']
                            )
                        );
                        if ($tags != null) {
                            $item['tags'] = $tags;
                        }
                        $newarr[] = $this->modelNews->setItemData($item, []);
                    }
                    return $newarr;
                } else {
                    return ceil(($collection['nbp'] / $this->offset));
                }
            }
            return null;
        }else{
            return $override;
        }
    }

    /**
     * @return array
     */
    public function getBuildTagList() : array {
		$conditions = ' WHERE lang.iso_lang = :iso ';
		//$tagsData = parent::fetchData(['context' => 'all', 'type' => 'tags', 'conditions' => $conditions],['iso' => $this->lang]);
        $tagsData = $this->getItems('tagsLang',['iso' => $this->lang],'all',false);

		$tags = [];
        if(!empty($tagsData)) {
            $this->tagsData = $tagsData;
            foreach ($tagsData as $tagData) {
                $tags[] = $this->modelNews->setItemData($tagData,[]);
            }
        }
		return $tags;
    }

	/**
	 * @param bool $count
	 * @param array $filter
	 * @return array
	 */
	public function getNewsList(bool $count = false, $limit = false, array $filter = []) : array {
		if(isset($this->filter)) $filter = $this->filter;

		$newtableArray = [];

		$override = $this->modelModule->extendDataArray('news',__FUNCTION__, $filter);

		if(!empty($override)) {
			foreach ($override as $value) {
				$newtableArray = array_merge_recursive($newtableArray, $value);
			}
		}

		$params = ['iso' => $this->lang];
		$joins = [];
		$conditions = [];

		if(isset($this->tag) || isset($this->tags)) {
			if(isset($this->tags)) $this->tags = collections_ArrayTools::ArrayCleaner($this->tags);

			if(!empty($this->tag) || !empty($this->tags)) {
				$joins[] = [
					'type' => 'LEFT JOIN',
					'table' => 'mc_news_tag_rel',
					'as' => 'mntr',
					'on' => [
						'table' => 'mn',
						'key' => 'id_news'
					]
				];

				if (!empty($this->tags)) {
					$conditions[] = [
						'type' => 'AND',
						'condition' => 'mntr.id_tag IN('.implode(',',$this->tags).')'
					];
					if(!$count) {
						$params['group'] = [['mn.id_news']];
						$params['having'] = [['COUNT(mn.id_news) = ' . count($this->tags)]];
					}
				}
				elseif (!empty($this->tag)) {
					$conditions[] = [
						'type' => 'AND',
						'condition' => 'mntr.id_tag = :tag'
					];
					$params['tag'] = $this->tag;
				}
			}
		}
		if(isset($this->date)) {
			$conditions[] = [
				'type' => 'AND',
				'condition' => 'mnc.date_publish = :date'
			];
			$params['date'] = $this->dateFormat->SQLDate($this->date);
		}
		elseif(isset($this->year)) {
			$conditions[] = [
				'type' => 'AND',
				'condition' => 'YEAR(mnc.date_publish) = :yr'
			];
			$params['yr'] = $this->year;

			if (isset($this->month)) {
				$conditions[] = [
					'type' => 'AND',
					'condition' => 'MONTH(mnc.date_publish) = :mth'
				];
				$params['mth'] = $this->month;
			}
		}
		else {
			$conditions[] = [
				'type' => 'AND',
				'condition' => 'mnc.date_publish <= :date'
			];
			$params['date'] = $this->dateFormat->SQLDate();
		}

		if(!$count) $limit = !$limit ? [($this->page * $this->offset) . ', ' . $this->offset] : [$limit] ;

		if(!empty($joins)) $params['join'] = [$joins];
		if(!empty($conditions)) $params['where'] = [$conditions];
		if(!empty($limit)) $params['limit'] = $limit;

		if(!empty($newtableArray)) {
			$extendQueryParams = [];
			$extendQueryParams[] = $newtableArray['extendQueryParams'];

			if(!empty($extendQueryParams)) {
				foreach ($extendQueryParams as $extendParams) {
					if(isset($extendParams['select']) && !empty($extendParams['select'])) $params['select'][] = $extendParams['select'];
					if(isset($extendParams['join']) && !empty($extendParams['join'])) $params['join'][] = $extendParams['join'];
					if(isset($extendParams['where']) && !empty($extendParams['where'])) $params['where'][] = $extendParams['where'];
					if(isset($extendParams['order']) && !empty($extendParams['order'])) $params['order'][] = $extendParams['order'];
					if(isset($extendParams['group']) && !empty($extendParams['group'])) $params['group'][] = $extendParams['group'];
					if(isset($extendParams['having']) && !empty($extendParams['having'])) $params['having'][] = $extendParams['having'];
					if(isset($extendParams['limit']) && !empty($extendParams['limit'])) $params['limit'][] = $extendParams['limit'];

					if(!empty($filter)){
						if(isset($extendParams['filter']) && !empty($extendParams['filter'])) $params['where'][] = is_array($extendParams['where']) ? array_merge($extendParams['where'],$extendParams['filter']) : $extendParams['filter'];
					}
				}
			}
		}

		if(!$count) {
			$collection = $this->getItems('news',$params, 'all', false);

			$newSetArray = [];
			if(!empty($collection)) {
				/*foreach ($collection as &$item) {
					$tags = $this->getItems('tagsRel',['iso' => $item['iso_lang'], 'id' => $item['id_news']],'all',false);
					if(!empty($tags)) $item['tags'] = $tags;
				}*/
                if(!empty($this->tagsData)) {
                    $tags = [];
                    foreach ($this->tagsData as $tag) {
                        $tags[$tag['id_tag']] = $tag;
                    }
                    foreach($collection as $key => &$value){
                        $tags_ids = explode(',',$value['tags_ids']);
                        $itemTags = array_intersect_key($tags,array_flip($tags_ids));
                        //$itemTags = array_intersect_ukey($tags,array_flip($tags_ids),function($a,$b){ return $a == $b; });
                        if(!empty($itemTags)) {
                            $value['tags'] = $itemTags;
                        }
                    }
                }

				if(empty($newtableArray)) {
					foreach ($collection as &$item) {
						$newSetArray[] = $this->modelNews->setItemData($item, []);
					}
				}
				else {
					if(isset($newtableArray['collection'])){
						$extendFormArray = [];

						if(is_array($newtableArray['collection'])) {
							foreach ($newtableArray['collection'] as $value){
								$extendFormArray[] = $value;
							}
						}
						else {
							$extendFormArray[] = $newtableArray['collection'];
						}
						$extendFormData = $this->modelModule->extendDataArray('news','extendListNews', $collection);

						foreach ($collection as $key => $value){
							foreach ($extendFormData as $key1 => $value1) {
								$collection[$key][$extendFormArray[$key1]] = $value1[$key];
							}
						}

						$newRow = $newtableArray['newRow'];
						foreach ($collection as &$item) {
							$newSetArray[] = $this->modelNews->setItemData($item, [], $newRow);
						}
					}
				}
			}
			return $newSetArray;
		}
		else {
			$collection = $this->getItems('count_news',$params, 'one', false);
			return [
				'total' => empty($collection) ? 0 : $collection['total'],
				'nbp' => empty($collection) ? 1 : ceil(($collection['total'] / $this->offset))
			];
		}
	}

    /**
     * set Data from database
     * @access private
     */
    private function getBuildNewsItems()
    {
        $override = $this->modelModule->getOverride('news',__FUNCTION__);
        if(!$override) {
            $collection = $this->getItems('page', array('id' => $this->id, 'iso' => $this->lang), 'one', false);
            /*$tagsCollection = $this->getItems('tagsRel', array('id' => $this->id, 'iso' => $this->lang), 'all', false);
            if ($tagsCollection != null) {
                $collection['tags'] = $tagsCollection;
            }*/
            if(!empty($this->tagsData)) {
                $tags = [];
                foreach ($this->tagsData as $tag) {
                    $tags[$tag['id_tag']] = $tag;
                }
                $tags_ids = explode(',',$collection['tags_ids']);
                $itemTags = array_intersect_key($tags,array_flip($tags_ids));
                if(!empty($itemTags)) {
                    $collection['tags'] = $itemTags;
                }
            }

            $collection['prev'] = null;
            $prev = $this->getItems('prev_page', array('id' => $this->id, 'iso' => $this->lang, 'date_publish' => $collection['date_publish']), 'one', false);
            if ($prev) {
                $collection['prev']['title'] = $prev['name_news'];
                $collection['prev']['url'] = $this->routingUrl->getBuildUrl(array(
                    'type' => 'news',
                    'iso' => $prev['iso_lang'],
                    'date' => $prev['date_publish'],
                    'id' => $prev['id_news'],
                    'url' => $prev['url_news']
                ));
            }

            $collection['next'] = null;
            $next = $this->getItems('next_page', array('id' => $this->id, 'iso' => $this->lang, 'date_publish' => $collection['date_publish']), 'one', false);
            if ($next) {
                $collection['next']['title'] = $next['name_news'];
                $collection['next']['url'] = $this->routingUrl->getBuildUrl(array(
                    'type' => 'news',
                    'iso' => $next['iso_lang'],
                    'date' => $next['date_publish'],
                    'id' => $next['id_news'],
                    'url' => $next['url_news']
                ));
            }

            return $this->modelNews->setItemData($collection, []);
        }
		else{
            return $override;
        }
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
    private function getData($type) {
        switch($type){
            case 'tag':
            	$data = $this->getItems('tag',$this->tag,'one',false);
                $tag = [];
                $tag['id'] = $data['id'];
                $tag['name'] = $data['name'];
                $tag['seo'] = $this->modelNews->tagSeo($data['name']);
                $this->template->assign('tag',$tag);
                $this->template->breadcrumb->addItem($this->template->getConfigVars('theme').': '.$data['name']);
                break;
            case 'id':
                $data = $this->getBuildNewsItems();
                $this->template->breadcrumb->addItem($data['name']);
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
        $monthsData = parent::fetchData(['context' => 'all', 'type' => 'archives'],['iso' => $this->lang]);
		$archives = [];
		/*foreach ($data as $arr) {
			$months = explode(',',$arr['mths']);
			$months = array_reverse($months);
			foreach ($months as $k => $month) {
				$nbr = parent::fetchData(['context' => 'one', 'type' => 'nb_archives'], ['iso' => $this->lang, 'yr' => $arr['yr'], 'mth' => $month]);
				$month = [
                    'month' => $month,
                    'url' => $this->routingUrl->getBuildUrl(['type'  =>  'date',
                        'iso'   =>  $this->lang,
                        'year'  =>  $arr['yr'],
                        'month' =>  $month,]),
                    'nbr' => $nbr['nbr']
                ];
				$months[$k] = $month;
			}
			$arch[] = [
                'year' => $arr['yr'],
                'url' => $this->routingUrl->getBuildUrl(['type'  =>  'date',
                    'iso'   =>  $this->lang,
                    'year'  =>  $arr['yr']
                ]),
                'months' => $months
            ];
		}*/
        if(!empty($monthsData)) {
            $year = '';
            foreach ($monthsData as $monthData) {
                if($year !== $monthData['year']) $year = $monthData['year'];
                if(!isset($archives[$year])) {
                    $archives[$year] = [
                        'year' => $year,
                        'url' => $this->routingUrl->getBuildUrl([
							'type' => 'date',
                            'iso' => $this->lang,
                            'year' => $year
                        ])
                    ];
                }
                $month = [
                    'month' => $monthData['month'],
                    'url' => $this->routingUrl->getBuildUrl([
						'type' => 'date',
                        'iso' => $this->lang,
                        'year' => $year,
                        'month' => $monthData['month'],
                    ]),
                    'nbr' => $monthData['number']
                ];
                $archives[$year]['months'][] = $month;
            }
        }
		return $archives;
    }

    public function run() {
		$this->template->assign('tags',$this->getBuildTagList());
        if(isset($this->id) && isset($this->date)) {
            $this->template->breadcrumb->addItem(
                $this->template->getConfigVars('news'),
                '/'.$this->template->lang.($this->template->is_amp() ? '/amp' : '').'/news/',
                $this->template->getConfigVars('news')
            );
            $this->getData('id');
            $this->template->display('news/news.tpl');
        }
        else {
			$this->template->assign('archives',$this->getBuildArchive());
			$this->template->assign('news',$this->getNewsList());
			$this->template->assign('nbp',$this->getNewsList(true));
			$this->template->assign('rootSeo',$this->modelNews->rootSeo());

			if(isset($this->year) OR isset($this->month) OR isset($this->date)) {
                $this->template->breadcrumb->addItem(
                    $this->template->getConfigVars('news'),
                    '/'.$this->template->lang.($this->template->is_amp() ? '/amp' : '').'/news/',
                    $this->template->getConfigVars('news')
                );
                if(isset($this->date)) {
                    $date = new DateTime($this->date);
                    $this->template->breadcrumb->addItem(ucfirst($this->template->getConfigVars('date')).': '.strftime('%e %B %Y',$date->getTimestamp()));
                }
                if(isset($this->month)) {
                    $monthName = date("%B", mktime(0, 0, 0, $this->month, 1, 2000));
                    //$monthName = strftime("%B", mktime(0, 0, 0, $this->month, 1, 2000));
                    $this->template->breadcrumb->addItem(ucfirst($this->template->getConfigVars('month')).': '.$monthName.' '.$this->year);
                }
                elseif(isset($this->year)) $this->template->breadcrumb->addItem(ucfirst($this->template->getConfigVars('year')).': '.$this->year);

				$this->template->display('news/date.tpl');
			}
			elseif(isset($this->tag)) {
                $this->template->breadcrumb->addItem(
                    $this->template->getConfigVars('news'),
                    '/'.$this->template->lang.($this->template->is_amp() ? '/amp' : '').'/news/',
                    $this->template->getConfigVars('news')
                );
                $this->getData('tag');
				$this->template->display('news/tag.tpl');
			}
			else {
                $this->template->breadcrumb->addItem($this->template->getConfigVars('news'));
				$this->template->display('news/index.tpl');
			}
		}
    }
}