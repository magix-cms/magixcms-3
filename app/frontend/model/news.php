<?php
/*
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of MAGIX CMS.
# MAGIX CMS, The content management system optimized for users
# Copyright (C) 2008 - 2013 sc-box.com <support@magix-cms.com>
#
# OFFICIAL TEAM :
#
#   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
#
# Redistributions of files must retain the above copyright notice.
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# -- END LICENSE BLOCK -----------------------------------
#
# DISCLAIMER
#
# Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
# versions in the future. If you wish to customize MAGIX CMS for your
# needs please refer to http://www.magix-cms.com for more information.
*/
class frontend_model_news extends frontend_db_news {
	/**
	 * @var frontend_model_template $template
	 * @var frontend_model_data $data
	 * @var frontend_model_plugins $modelPlugins
	 * @var frontend_model_seo $seo
	 * @var frontend_model_logo $logo
	 * @var component_routing_url $routingUrl
	 * @var component_files_images $imagesComponent
	 * @var date_dateformat $dateFormat
	 */
	protected frontend_model_template $template;
	protected frontend_model_data $data;
	protected frontend_model_plugins $modelPlugins;
	protected frontend_model_seo $seo;
	protected frontend_model_logo $logo;
	protected component_routing_url $routingUrl;
	protected component_files_images $imagesComponent;
	protected date_dateformat $dateFormat;

	/**
	 * @var array $imgPrefix
	 * @var array $fetchConfig
	 */
	protected array
		$imgPrefix,
		$fetchConfig;

    /**
	 * @param null|frontend_model_template $t
	 */
    public function __construct(frontend_model_template $t = null) {
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
		$this->routingUrl = new component_routing_url();
		$this->modelPlugins = new frontend_model_plugins($this->template);
        $this->dateFormat = new date_dateformat();
		$this->data = new frontend_model_data($this,$this->template);
        $this->seo = new frontend_model_seo('news', '', '',$this->template);
        $this->logo = new frontend_model_logo($this->template);
    }

	/**
	 * @return void
	 */
	private function initImageComponent() {
		if(!isset($this->imagesComponent)) $this->imagesComponent = new component_files_images($this->template);
	}

    /**
     * @return array
     */
	public function rootSeo() {
		$rootSeo = array();
		$this->seo->level = 'root';
		$seoTitle = $this->seo->replace_var_rewrite('','','title');
		$rootSeo['title'] = $seoTitle ? $seoTitle : $this->template->getConfigVars('news');
		$seoDesc = $this->seo->replace_var_rewrite('','','description');
		$rootSeo['description'] = $seoDesc ? $seoDesc : $this->template->getConfigVars('last_news');
		return $rootSeo;
    }

    /**
     * @param array $row
     * @param array $current
     * @param array $newRow
     * @return array
     */
    public function setItemData(array $row, array $current, array $newRow = []) {
        $data = [];

        if (isset($row['id_news'])) {
            $string_format = new component_format_string();
            $date_format = new component_format_date();
			$this->initImageComponent();
            $data['id'] = $row['id_news'];
            $data['name'] = $row['name_news'];
            $data['url'] = $this->routingUrl->getBuildUrl([
				'type' => 'news',
				'iso' => $row['iso_lang'],
				'date' => $row['date_publish'],
				'id' => $row['id_news'],
				'url' => $row['url_news']
			]);

            if (isset($row['img_news'])) {
				$data['img'] = $this->imagesComponent->setModuleImage('news','news',$row['img_news'],$row['id_news']);
            }
			$data['img']['default'] = $this->imagesComponent->setModuleImage('news','news');
			$data['img']['alt'] = $row['alt_img'];
			$data['img']['title'] = $row['title_img'];
			$data['img']['caption'] = $row['caption_img'];
            $data['active'] = (!empty($current) && $row['id_news'] == $current['controller']['id']) ? true : false;

            /*$dr = new DateTime($row['date_register']);
            $drt = $dr->getTimestamp();
            $data['date'] = array(
                'register' => array(
                    'timestamp' => $drt,
                    'date' => $dr->format('Y-m-d'),
                    'year' => $dr->format('Y'),
                    'month' => array(
                        'num' => $dr->format('m'),
                        'name' => strftime('%B',$drt),
                        'abv' => strftime('%b',$drt)
                    ),
                    'week' => $dr->format('W'),
                    'day' => array(
                        'num' => $dr->format('j'),
                        'name' => strftime('%A'),
                        'abv' => strftime('%a')
                    ),
                    'suffix' => $dr->format('S'),
                )
            );*/
            $data['date']['register'] = $date_format->setTzDateTimeArray($row['date_register']);

            if(isset($row['last_update'])) {
                /*$du = new DateTime($row['last_update']);
                $dut = $du->getTimestamp();
                $data['date']['update'] = array(
                    'timestamp' => $dut,
                    'date' => $du->format('Y-m-d'),
                    'year' => $du->format('Y'),
                    'month' => array(
                        'num' => $du->format('m'),
                        'name' => strftime('%B',$dut),
                        'abv' => strftime('%b',$dut)
                    ),
                    'week' => $du->format('W'),
                    'day' => array(
                        'num' => $du->format('j'),
                        'name' => strftime('%A'),
                        'abv' => strftime('%a')
                    ),
                    'suffix' => $du->format('S'),
                );*/
                $data['date']['update'] = $date_format->setTzDateTimeArray($row['last_update']);
            }
            if(isset($row['date_publish'])) {
                /*$dp = new DateTime($row['date_publish']);
                $dpt = $dp->getTimestamp();
                $data['date']['publish'] = array(
                    'timestamp' => $dpt,
                    'date' => $dp->format('Y-m-d'),
                    'year' => $dp->format('Y'),
                    'month' => array(
                        'num' => $dp->format('m'),
                        'name' => strftime('%B',$dpt),
                        'abv' => strftime('%b',$dpt)
                    ),
                    'week' => $dp->format('W'),
                    'day' => array(
                        'num' => $dp->format('j'),
                        'name' => strftime('%A'),
                        'abv' => strftime('%a')
                    ),
                    'suffix' => $dp->format('S'),
                );*/
                $data['date']['publish'] = $date_format->setTzDateTimeArray($row['date_publish']);
            }

            $data['content'] = $row['content_news'];
			$data['lead'] = $row['resume_news'];
			$data['resume'] = $row['resume_news'] ?: ($row['content_news'] ? $string_format->clearHTMLTemplate($row['content_news']) : '');

            $data['tags'] = null;
            if(isset($row['tags'])) {
                if (is_array($row['tags'])) {
                    foreach ($row['tags'] as $key => $value) {
                        $data['tags'][$key]['id'] = $value['id_tag'];
                        $data['tags'][$key]['name'] = $value['name_tag'];
                        $data['tags'][$key]['url'] = $this->routingUrl->getBuildUrl([
                            'type' => 'tag',
                            'iso' => $value['iso_lang'],
                            'id' => $value['id_tag'],
                            'url' => http_url::clean(html_entity_decode($value['name_tag']),['dot' => false, 'ampersand' => 'and', 'cspec' => '', 'rspec' => ''])
                        ]);
                    }
                }
            }
            // Plugin
            if(!empty($newRow)){
                if(is_array($newRow)){
                    foreach($newRow as $key => $value){
                        $data[$key] = $row[$value];
                    }
                }
            }

			// @ToDo faire en sorte que les listing utilise une fonction short data pour limiter le nombre de donnée à récup + utiliser COALESCE plutôt que récup plusieurs colonnes
			$this->seo->level = 'record';
			if (!isset($row['seo_title_news']) || empty($row['seo_title_news'])) {
				$seoTitle = $this->seo->replace_var_rewrite('',$data['name'],'title');
				$data['seo']['title'] = $seoTitle ? $seoTitle : $data['name'];
			}
			else {
				$data['seo']['title'] = $row['seo_title_news'];
			}
			if (!isset($row['seo_desc_news']) || empty($row['seo_desc_news'])) {
				$seoDesc = $this->seo->replace_var_rewrite('',$data['name'],'description');
				$data['seo']['description'] = $seoDesc ? $seoDesc : ($data['resume'] ? $data['resume'] : $data['seo']['title']);
			}
			else {
				$data['seo']['description'] = $row['seo_desc_news'];
			}

            if(isset($row['prev'])) $data['prev'] = $row['prev'];
            if(isset($row['next'])) $data['next'] = $row['next'];
        }
        else if(isset($row['id_tag'])) {
            $data['id'] = $row['id_tag'];
            $data['name'] = $row['name_tag'];
            $data['url'] = $this->routingUrl->getBuildUrl([
                'type' => 'tag',
                'iso' => $row['iso_lang'],
                'id' => $row['id_tag'],
                'url' => http_url::clean(html_entity_decode($row['name_tag']),['dot' => false, 'ampersand' => 'and', 'cspec' => '', 'rspec' => ''])
            ]);
        }

        return $data;
    }

    /**
     * Formate les valeurs principales d'un élément suivant la ligne passées en paramètre
     * @param $row
     * @return null|array
     * @throws Exception
     */
    public function setItemShortData($row) {
        $data = null;

        if (isset($row['id_news'])) {
            $data['id'] = $row['id_news'];
            $data['name'] = $row['name_news'];
            $data['url'] = $this->routingUrl->getBuildUrl(array(
				'type' => 'news',
				'iso'  => $row['iso_lang'],
				'date' => $row['date_publish'],
				'id'   => $row['id_news'],
				'url'  => $row['url_news']
			));
        }
        else if(isset($row['id_tag'])) {
            $data['id'] = $row['id_tag'];
            $data['name'] = $row['name_tag'];
            $data['url'] = $this->routingUrl->getBuildUrl(array(
                    'type' => 'tag',
                    'iso' => $row['iso_lang'],
                    'id' => $row['id_tag'],
                    'url' => http_url::clean(html_entity_decode($row['name_tag']),
						array(
							'dot' => false,
							'ampersand' => 'and',
							'cspec' => '', 'rspec' => ''
						)
					)
                )
            );
        }

        return $data;
    }

    /**
     * @param $row
     * @return array
     * @throws Exception
     */
    public function setHrefLangData($row)
    {
        $arr = array();

        foreach ($row as $item) {
            $arr[$item['id_lang']] = $this->routingUrl->getBuildUrl(array(
                'type'      =>  'news',
                'iso'       =>  $item['iso_lang'],
                'date'      =>  $item['date_publish'],
                'id'        =>  $item['id_news'],
                'url'       =>  $item['url_news']
            ));
        }

        return $arr;
    }

	/**
	 * @param $d
	 * @param $c
	 * @param $nr
	 * @param $s
	 * @return mixed|null
	 */
	public function parseData($d,$c,$nr = false,$s = false)
	{
		return $this->data->parseData($d,$this,$c,$nr,$s);
	}

    /**
     * Retourne les données sql sur base des paramètres passés en paramète
     * @param array $custom
     * @param array $current
     * @param bool $override
     * @return array|null
     * @throws Exception
     */
    public function getData($custom,$current,$override = false)
    {
        if (!(is_array($custom))) return null;

        if (!(array_key_exists('controller', $current))) return null;

        // set default values for query
        $conf = array(
            'id' => null,
            'type' => 'data',
            'limit' => null,
            //'offset'    =>  $ModelPager->setPaginationOffset(10,$current['news']['pagination']['id']),
            'lang' => $current['lang']['iso'],
            'filter' => null,
            'context' => array(1 => 'all')
        );
        !empty($current['controller']['name']) || $current['controller']['name'] !='' ? $current['controller']['name'] : $current['controller']['name'] = 'news';
        $current = $current['controller'];

        // custom values: select or exclude
        if (isset($custom['select'])) {
            if ($custom['select'] == 'current') {
                $conf['id'] = $current['id'];
            } elseif (is_array($custom['select'])) {
                if (array_key_exists($conf['lang'], $custom['select'])) {
                    $conf['id'] = $custom['select'][$conf['lang']];
                }
            }
        } elseif (isset($custom['exclude'])) {
            if (is_array($custom['exclude'])) {
                if (array_key_exists($conf['lang'], $custom['exclude'])) {
                    $conf['id'] = $custom['exclude'][$conf['lang']];
                    //$conf['type'] = 'exclude';
                }
            }
        }
        // custom values: display
        if (isset($custom['context'])) {
            if (is_array($custom['context'])) {
                foreach ($custom['context'] as $k => $v) {
                    $conf['context'][1] = $k;
                    $conf['context'][2] = $v;
                }
            } else {
                $allowed = array(
                    '',
                    'all',
                    'tag',
                    'tags'
                );

                if (in_array($custom['context'], $allowed)) {
                    $conf['context'][1] = $custom['context'];
                }
            }
        }

        if (isset($custom['limit'])) {
            $conf['limit'] = $custom['limit'];
        }
        // Filter
        if (isset($custom['filter'])) {
            //$conf['filter'] = $custom['filter'];
            foreach ($custom['filter'] as $k => $v) {
                $conf['filter'][$k] = $v;
            }
        }

        // Override with plugin
        if (isset($custom['plugins'])) {
            $conf['plugins'] = $custom['plugins'];
        }

        // *** Load SQL data
        $conditions = '';
        if ($conf['context'][1] == 'all') {
            if ($override) {
                $getCallClass = $this->modelPlugins->getCallClass($override);
                if(method_exists($getCallClass,'override')){
                    $conf['data'] = 'all';
                    $conf['controller'] = $current;
                    $data = call_user_func_array(
                        array(
                            $getCallClass,
                            'override'
                        ),
                        array(
                            $conf,
                            $custom
                        )
                    );
                }
            }
            else {

                $conditions .= ' WHERE lang.iso_lang = :iso AND c.date_publish <=:date AND c.published_news = 1 ';
                //

                if (isset($custom['exclude'])) {
                    $conditions .= ' AND p.id_news NOT IN (' . $conf['id'] . ') ';
                }

                // ORDER
                //$conditions .= ' ORDER BY p.order_pages ASC';


                if ($conf['filter'] != null) {
                    if(isset($conf['filter']['year'])) {
                        $conditions .= ' AND YEAR(c.date_publish) = ' . $conf['filter']['year'];
                    }
                    if(isset($conf['filter']['month']) && $conf['filter']['month'] != null) {
                        $conditions .= ' AND MONTH(c.date_publish) = ' . $conf['filter']['month'];
                    }
                }

                $conditions .= ' ORDER BY c.date_publish DESC';

                if ($conf['limit'] != null) {
                    $conditions .= ' LIMIT ' . $conf['limit'];
                }

                if ($conditions != '') {
                    $data = parent::fetchData(
                        array('context' => 'all', 'type' => 'pages', 'conditions' => $conditions),
                        array(
                            ':iso' => $conf['lang'],
                            ':date' => $this->dateFormat->SQLDate()
                        )
                    );
					$tagsData = parent::fetchData(['context' => 'all','type' => 'tagsLang'],['iso' => $conf['lang']]);
					if(!empty($tagsData)) {
						$tags = [];
						foreach ($tagsData as $tag) {
							$tags[$tag['id_tag']] = $tag;
						}
						foreach($data as $key => $value){
							$tags_ids = explode(',',$value['tags_ids']);
							$itemTags = array_intersect_key($tags,array_flip($tags_ids));
							//$itemTags = array_intersect_ukey($tags,array_flip($tags_ids),function($a,$b){ return $a == $b; });
							if(!empty($itemTags)) {
								$data[$key]['tags'] = $itemTags;
							}
						}
					}

                    /*if($data != null) {
                        $branch = isset($custom['select']) ? $conf['id'] : 'root';
                        $data = $this->setPagesTree($data,$branch);
                    }*/
                }
            }
        }
        elseif ($conf['context'][1] == 'tag') {
            if ($override) {
                $getCallClass = $this->modelPlugins->getCallClass($override);
                if(method_exists($getCallClass,'override')){
                    $conf['data'] = 'tag';
                    $conf['controller'] = $current;
                    $data = call_user_func_array(
                        array(
                            $getCallClass,
                            'override'
                        ),
                        array(
                            $conf,
                            $custom
                        )
                    );
                }
            }
            else{
                $conditions .= ' JOIN mc_news_tag_rel AS tagrel ON ( tagrel.id_news = p.id_news ) ';
                $conditions .= ' WHERE lang.iso_lang = :iso AND c.date_publish <=:date AND c.published_news = 1 AND tagrel.id_tag = :id ';

                if (isset($custom['select'])) {
                    $conf['id'] = $custom['select'];
                }
                if ($conditions != '') {


                    $data = parent::fetchData(
                        array('context' => 'all', 'type' => 'pages', 'conditions' => $conditions),
                        array(
                            ':iso' => $conf['lang'],
                            ':date' => $this->dateFormat->SQLDate(),
                            ':id' => $conf['id'],
                        )
                    );
                    foreach($data as $key => $value){
                        $collectionTags = parent::fetchData(
                            array('context' => 'all', 'type' => 'tagsRel'),
                            array(
                                ':iso' => $value['iso_lang'],
                                ':id'  => $value['id_news']
                            )
                        );
                        if($collectionTags != null) {
                            $data[$key]['tags'] = $collectionTags;
                        }
                    }
                }
            }
        }
        elseif ($conf['context'][1] == 'tags') {

            if ($override) {
                $getCallClass = $this->modelPlugins->getCallClass($override);
                if (method_exists($getCallClass, 'override')) {
                    $conf['data'] = 'tags';
                    $conf['controller'] = $current;
                    $data = call_user_func_array(
                        array(
                            $getCallClass,
                            'override'
                        ),
                        array(
                            $conf,
                            $custom
                        )
                    );
                }
            } else {
                $conditions .= ' WHERE lang.iso_lang = :iso ';
                if ($conditions != '') {
                    $data = parent::fetchData(
                        array('context' => 'all', 'type' => 'tags', 'conditions' => $conditions),
                        array(
                            ':iso' => $conf['lang']
                        )
                    );
                }
            }
        }

        return $data;
    }

    /**
     * Retourne les données sql sur base des paramètres passés en paramète
     * @param array $custom
     * @param array $current
     * @param bool $override
     * @return array|null
     * @throws Exception
     */
    public function getShortData($custom,$current,$override = false)
    {
        if (!(is_array($custom))) return null;

        if (!(array_key_exists('controller', $current))) return null;

        // set default values for query
        $conf = array(
            'id' => null,
            'type' => 'data',
            'limit' => null,
            //'offset'    =>  $ModelPager->setPaginationOffset(10,$current['news']['pagination']['id']),
            'lang' => $current['lang']['iso'],
            'filter' => null,
            'context' => array(1 => 'all')
        );
        !empty($current['controller']['name']) || $current['controller']['name'] !='' ? $current['controller']['name'] : $current['controller']['name'] = 'news';
        $current = $current['controller'];

        // custom values: select or exclude
        if (isset($custom['select'])) {
            if ($custom['select'] == 'current') {
                $conf['id'] = $current['id'];
            } elseif (is_array($custom['select'])) {
                if (array_key_exists($conf['lang'], $custom['select'])) {
                    $conf['id'] = $custom['select'][$conf['lang']];
                }
            }
        }
        elseif (isset($custom['exclude'])) {
            if (is_array($custom['exclude'])) {
                if (array_key_exists($conf['lang'], $custom['exclude'])) {
                    $conf['id'] = $custom['exclude'][$conf['lang']];
                    //$conf['type'] = 'exclude';
                }
            }
        }
        // custom values: display
        if (isset($custom['context'])) {
            if (is_array($custom['context'])) {
                foreach ($custom['context'] as $k => $v) {
                    $conf['context'][1] = $k;
                    $conf['context'][2] = $v;
                }
            } else {
                $allowed = array(
                    '',
                    'all',
                    'tag',
                    'tags'
                );

                if (in_array($custom['context'], $allowed)) {
                    $conf['context'][1] = $custom['context'];
                }
            }
        }

        if (isset($custom['limit'])) {
            $conf['limit'] = $custom['limit'];
        }
        // Filter
        if (isset($custom['filter'])) {
            //$conf['filter'] = $custom['filter'];
            foreach ($custom['filter'] as $k => $v) {
                $conf['filter'][$k] = $v;
            }
        }

        // Override with plugin
        if (isset($custom['plugins'])) {
            $conf['plugins'] = $custom['plugins'];
        }

        // *** Load SQL data
        $conditions = '';
        if ($conf['context'][1] == 'all') {
            if ($override) {
                $getCallClass = $this->modelPlugins->getCallClass($override);
                if(method_exists($getCallClass,'override')){
                    $conf['data'] = 'all';
                    $conf['controller'] = $current;
                    $data = call_user_func_array(
                        [$getCallClass, 'override'],
                        [$conf, $custom]
                    );
                }
            }
            else {
                $conditions .= ' WHERE lang.iso_lang = :iso AND c.date_publish <=:date AND c.published_news = 1 ';

                if (isset($custom['exclude'])) $conditions .= ' AND p.id_news NOT IN (' . $conf['id'] . ') ';

                if ($conf['filter'] != null) {
                    if(isset($conf['filter']['year'])) $conditions .= ' AND YEAR(c.date_publish) = ' . $conf['filter']['year'];
                    if(isset($conf['filter']['month']) && $conf['filter']['month'] != null) $conditions .= ' AND MONTH(c.date_publish) = ' . $conf['filter']['month'];
                }

                $conditions .= ' ORDER BY c.date_publish DESC';

                if ($conf['limit'] != null) $conditions .= ' LIMIT ' . $conf['limit'];

                if ($conditions != '') {
                    $data = parent::fetchData(
                        ['context' => 'all', 'type' => 'pages_short', 'conditions' => $conditions],
                        ['iso' => $conf['lang'], 'date' => $this->dateFormat->SQLDate()]
                    );
                    /*foreach($data as $key => $value){
                        $collectionTags = parent::fetchData(
                            ['context' => 'all', 'type' => 'tagsRel'],
                            ['iso' => $value['iso_lang'], 'id'  => $value['id_news']]
                        );
                        if($collectionTags != null) {
                            $data[$key]['tags'] = $collectionTags;
                        }
                    }*/
                    /*if($data != null) {
                        $branch = isset($custom['select']) ? $conf['id'] : 'root';
                        $data = $this->setPagesTree($data,$branch);
                    }*/
                }
            }
        }
        elseif ($conf['context'][1] == 'tag') {
            if ($override) {
                $getCallClass = $this->modelPlugins->getCallClass($override);
                if(method_exists($getCallClass,'override')){
                    $conf['data'] = 'tag';
                    $conf['controller'] = $current;
                    $data = call_user_func_array(
                        array(
                            $getCallClass,
                            'override'
                        ),
                        array(
                            $conf,
                            $custom
                        )
                    );
                }
            }
            else{
                $conditions .= ' JOIN mc_news_tag_rel AS tagrel ON ( tagrel.id_news = p.id_news ) ';
                $conditions .= ' WHERE lang.iso_lang = :iso AND c.date_publish <=:date AND c.published_news = 1 AND tagrel.id_tag = :id ';

                if (isset($custom['select'])) {
                    $conf['id'] = $custom['select'];
                }
                if ($conditions != '') {
                    $data = parent::fetchData(
                        array('context' => 'all', 'type' => 'pages_short', 'conditions' => $conditions),
                        array(
                            ':iso' => $conf['lang'],
                            ':date' => $this->dateFormat->SQLDate(),
                            ':id' => $conf['id'],
                        )
                    );
                    foreach($data as $key => $value){
                        $collectionTags = parent::fetchData(
                            array('context' => 'all', 'type' => 'tagsRel'),
                            array(
                                ':iso' => $value['iso_lang'],
                                ':id'  => $value['id_news']
                            )
                        );
                        if($collectionTags != null) {
                            $data[$key]['tags'] = $collectionTags;
                        }
                    }
                }
            }
        }
        elseif ($conf['context'][1] == 'tags') {
            if ($override) {
                $getCallClass = $this->modelPlugins->getCallClass($override);
                if (method_exists($getCallClass, 'override')) {
                    $conf['data'] = 'tags';
                    $conf['controller'] = $current;
                    $data = call_user_func_array(
                        array(
                            $getCallClass,
                            'override'
                        ),
                        array(
                            $conf,
                            $custom
                        )
                    );
                }
            } else {
                $conditions .= ' WHERE lang.iso_lang = :iso ';
                if ($conditions != '') {
                    $data = parent::fetchData(
                        array('context' => 'all', 'type' => 'tags', 'conditions' => $conditions),
                        array(
                            ':iso' => $conf['lang']
                        )
                    );
                }
            }
        }

        return $data;
    }
}