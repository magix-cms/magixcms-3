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
class frontend_model_about extends frontend_db_about {
	/**
	 * @var frontend_model_template $template
	 * @var frontend_model_data $data
	 * @var component_routing_url $routingUrl
	 * @var frontend_model_plugins $modelPlugins
	 * @var frontend_controller_language $language
	 */
	protected frontend_model_template $template;
	protected frontend_model_data $data;
	protected component_routing_url $routingUrl;
	protected frontend_model_plugins $modelPlugins;
	protected frontend_controller_language $language;
	protected array $languages;
	protected bool $touch;
	protected string $mOS;

	/**
	 * @var array $type type of website allowed
	 */
	public array $type = [
		'org' => [
			'schema' => 'Organization',
			'label' => 'Organisation'
		],
		'locb' => [
			'schema' => 'LocalBusiness',
			'label' => 'Entreprise locale'
		],
		'corp' => [
			'schema' => 'Corporation',
			'label' => 'Société'
		],
		'store' => [
			'schema' => 'Store',
			'label' => 'Magasin'
		],
		'food' => [
			'schema' => 'FoodEstablishment',
			'label' => 'Restaurant'
		],
		'place' => [
			'schema' => 'Place',
			'label' => 'Lieu'
		],
		'person' => [
			'schema' => 'Person',
			'label' => 'Personne physique'
		]
	];

	/**
	 * @var array, Company informations
	 */
	public array $company = [
		'name' => NULL,
		'desc' => NULL,
		'slogan' => NULL,
		'type' => NULL,
		'eshop' => '0',
		'tva' => NULL,
		'contact' => [
			'mail' => NULL,
			'click_to_mail' => '0',
			'crypt_mail' => '1',
			'phone' => NULL,
			'mobile' => NULL,
			'click_to_call' => '1',
			'fax' => NULL,
			'adress' => [
				'adress' => NULL,
				'street' => NULL,
				'postcode' => NULL,
				'city' => NULL
			],
			'languages' => 'Français'
		],
		'socials' => [
			'facebook' => NULL,
			'twitter' => NULL,
			'youtube' => NULL,
			'linkedin' => NULL,
			'viadeo' => NULL,
			'pinterest' => NULL,
			'instagram' => NULL,
			'github' => NULL,
			'soundcloud' => NULL,
			'tumblr' => NULL,
			'tiktok' => NULL
		],
		'openinghours' => '0',
		'specifications' => [
			'Mo' => [
				'open_day' => '0',
				'open_time' => NULL,
				'close_time' => NULL,
				'noon_time' => '0',
				'noon_start' => NULL,
				'noon_end' => NULL,
				'close_txt' => NULL
			],
			'Tu' => [
				'open_day' => '0',
				'open_time' => NULL,
				'close_time' => NULL,
				'noon_time' => '0',
				'noon_start' => NULL,
				'noon_end' => NULL,
				'close_txt' => NULL
			],
			'We' => [
				'open_day' => '0',
				'open_time' => NULL,
				'close_time' => NULL,
				'noon_time' => '0',
				'noon_start' => NULL,
				'noon_end' => NULL,
				'close_txt' => NULL
			],
			'Th' => [
				'open_day' => '0',
				'open_time' => NULL,
				'close_time' => NULL,
				'noon_time' => '0',
				'noon_start' => NULL,
				'noon_end' => NULL,
				'close_txt' => NULL
			],
			'Fr' => [
				'open_day' => '0',
				'open_time' => NULL,
				'close_time' => NULL,
				'noon_time' => '0',
				'noon_start' => NULL,
				'noon_end' => NULL,
				'close_txt' => NULL
			],
			'Sa' => [
				'open_day' => '0',
				'open_time' => NULL,
				'close_time' => NULL,
				'noon_time' => '0',
				'noon_start' => NULL,
				'noon_end' => NULL,
				'close_txt' => NULL
			],
			'Su' => [
				'open_day' => '0',
				'open_time' => NULL,
				'close_time' => NULL,
				'noon_time' => '0',
				'noon_start' => NULL,
				'noon_end' => NULL,
				'close_txt' => NULL
			]
		]
	];

	/**
	 * @var bool $amp
	 */
	public bool $amp = false;

	/**
	 * @param frontend_model_template|null $t
	 */
	public function __construct(frontend_model_template $t = null) {
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
		$this->data = new frontend_model_data($this,$this->template);
		$this->routingUrl = new component_routing_url();
		$this->modelPlugins = new frontend_model_plugins($this->template);
		$this->language = new frontend_controller_language($this->template);
		$this->languages = $this->language->setCollection();
		$this->amp = http_request::isGet('amp');

		$detect = new Mobile_Detect;
		$this->touch = false;
		$this->mOS = '';

		if($detect->isMobile() || $detect->isTablet()){
			$this->touch = true;

			if($detect->isiOS()){
				$this->mOS = 'IOS';
			}
			elseif($detect->isAndroidOS()){
				$this->mOS = 'Android';
			}
		}
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
	 * Formate les valeurs principales d'un élément suivant la ligne passées en paramètre
	 * @param array $row
	 * @param array $current
	 * @param array $newRow
	 * @return array
	 */
	public function setItemData(array $row, array $current, array $newRow = []): array {
		$string_format = new component_format_string();
		$data = [];
		if (!empty($row)) {
			if (isset($row['name'])) {
				$data['name'] = $row['name'];
				$data['content'] = $row['content'];
				$data['seo']['title'] = $row['seo_title'] ?: ($row['name'] ?: $this->template->getConfigVars('about'));
				$data['seo']['description'] = $row['seo_desc'] ?: ($row['content'] ? substr(strip_tags($row['content']),300) : $row['seo_title']);
			}
			elseif (isset($row['name_pages'])) {
				$data['id'] = $row['id_pages'];
				$data['id_parent'] = !is_null($row['id_parent']) ? $row['id_parent'] : NULL;
				$data['name'] = $row['name_pages'];
				$data['iso'] = $row['iso_lang'];
				$data['url'] = $this->routingUrl->getBuildUrl([
					'type' => 'about',
					'iso' => $row['iso_lang'],
					'id' => $row['id_pages'],
					'url' => $row['url_pages']
				]);

				$data['active'] = false;

				if ($row['id_pages'] == $current['controller']['id']) {
					$data['active'] = true;
				}
				$data['resume'] = $row['resume_pages'] ? $row['resume_pages'] : ($row['content_pages'] ? $string_format->truncate(strip_tags($row['content_pages'])) : '');
				$data['content'] = $row['content_pages'];
				$data['menu'] = $row['menu_pages'];
				$data['date']['update'] = $row['last_update'] ?? null;
				$data['date']['register'] = $row['date_register'] ?? null;
				$data['seo']['title'] = !empty($row['seo_title_pages']) ? $row['seo_title_pages'] : $data['name'];
				$data['seo']['description'] = !empty($row['seo_desc_pages']) ? $row['seo_desc_pages'] : ($data['resume'] ?? $data['seo']['title']);
				// Plugin
				if(!empty($newRow)){
					foreach($newRow as $key => $value){
						$data[$key] = $row[$value];
					}
				}
			}
		}
		return $data;
	}

	/**
	 * Formate les valeurs principales d'un élément suivant la ligne passées en paramètre
	 * @param $row
	 * @param $current
	 * @return array|null
	 * @throws Exception
	 */
	public function setItemShortData($row)
	{
		$data = null;

		if ($row != null) {
			if (isset($row['name'])) {
				$data['name'] = $row['name'];
				$data['seo']['title'] = $row['seo_title'] ? $row['seo_title'] : ($row['name'] ? $row['name'] : $this->template->getConfigVars('about'));
			}
			elseif (isset($row['name_pages'])) {
				$data['id'] = $row['id_pages'];
				$data['id_parent'] = !is_null($row['id_parent']) ? $row['id_parent'] : NULL;
				$data['name'] = $row['name_pages'];
				$data['url']  =
					$this->routingUrl->getBuildUrl(array(
						'type' => 'about',
						'iso' => $row['iso_lang'],
						'id' => $row['id_pages'],
						'url' => $row['url_pages']
					));
				$data['seo']['title'] = $row['seo_title_pages'];
			}
			return $data;
		}
	}

	/**
	 * @param $pages
	 * @param string $branch
	 * @return mixed
	 */
	public function setPagesTree($pages, $branch = 'root')
	{
		$childs = [];
		$id = 'id_pages';

		foreach($pages as &$item) {
			$k = $item['id_parent'] == null ? 'root' : $item['id_parent'];
			if(!isset($item['id_pages'])) $id = 'id';

			if($k === 'root')
				$childs[$k][] = &$item;
			else
				$childs[$k]['subdata'][] = &$item;

			$childs[$item[$id]] = &$item;
		}
		unset($item);

		foreach($pages as &$item) {
			if (isset($childs[$item[$id]]) && isset($childs[$item[$id]]['subdata'])) {
				$item['subdata'] = $childs[$item[$id]]['subdata'];
			}
		}

		if(empty($childs)) return $childs;
		if($branch === 'root')
			return $childs[$branch];
		else
			return array($childs[$branch]);
	}

	/**
	 * @param $arr
	 * @return bool
	 */
	private function is_array_empty($arr)
	{
		$Result = true;

		if (is_array($arr) && count($arr) > 0)
		{
			foreach ($arr as $Value)
			{
				$Result = $Result && $this->is_array_empty($Value);
			}
		}
		else
		{
			$Result = empty($arr);
		}

		return $Result;
	}

	/**
	 * @return string
	 */
	private function getActiveLang() {
		//$langs = $this->getItems('languages',null,'all',false);
		$list = [];
		foreach ($this->template->langs as $lang) {
			$list[] = 'languages' === 'iso' ? ucfirst($this->languages[$lang['iso_lang']]) : ucfirst($lang['name_lang']);
		}
		return implode(', ',$list);
	}

	/**
	 * @return array
	 */
	public function getCompanyData()
	{
		$infoData = parent::fetchData(['context'=>'all','type'=>'info']);
		$about = [];
		foreach ($infoData as $item) {
			$about[$item['name_info']] = $item['value_info'];
		}
		$schedule = [];

		foreach ($this->company as $info => $value) {
			switch ($info) {
				case 'type':
					$this->company['type'] = $this->type[$about['type']]['schema'];
					break;
				case 'contact':
					foreach ($value as $contact_info => $val) {
						if($contact_info == 'adress') {
							$this->company['contact'][$contact_info]['adress'] = $about['adress'];
							$this->company['contact'][$contact_info]['street'] = $about['street'];
							$this->company['contact'][$contact_info]['postcode'] = $about['postcode'];
							$this->company['contact'][$contact_info]['city'] = $about['city'];
						} elseif ($contact_info == 'languages') {
							$this->company['contact'][$contact_info] = $this->getActiveLang();
						} else {
							$this->company['contact'][$contact_info] = $about[$contact_info];
						}
					}
					break;
				case 'socials':
					/*if($this->is_array_empty($value)) {
						$this->company['socials'] = array();
					}
					else{*/
					foreach ($value as $social_name => $link) {
						//$this->company['socials'][$social_name] = $about[$social_name];
						$social = [];

						if($about[$social_name] !== null) {
							switch ($social_name) {
								case 'facebook':
									$link = (($this->touch && !$this->amp) ? 'fb://facewebmodal/f?href=' : '') . 'https://www.facebook.com/'.$about[$social_name].'/';
									//$link = 'https://www.facebook.com/'.$about[$social_name].'/';
									break;
								case 'twitter':
									//$link = (($this->touch) ? 'twitter://user?screen_name=' : 'https://twitter.com/') . $about[$social_name];
									$link = 'https://twitter.com/'. $about[$social_name];
									break;
								case 'youtube':
									//$link = (($this->touch) ? 'gplus://' : 'https://') . 'plus.google.com/'.$about[$social_name].'/posts';
									//$link = ($this->touch) ? 'gplus://plus.google.com/app/basic/'.$about[$social_name].'/posts' : 'https://plus.google.com/'.$about[$social_name].'/posts';
									//$link = 'https://plus.google.com/'.$about[$social_name].'/posts';
									$link = 'https://www.youtube.com/channel/'.$about[$social_name];
									break;
								case 'linkedin':
									//$link = (($this->touch) ? 'linkedin://profile?id=' : 'https://www.linkedin.com/in/') . $about[$social_name];
									$link = 'https://www.linkedin.com/'.(strpos($about[$social_name],'company/') === false ? 'in/' : '').$about[$social_name];
									break;
								case 'viadeo':
									//$link = (($this->touch) ? 'viadeo://profile?id=' : 'http://www.viadeo.com/fr/profile/') . $about[$social_name];
									$link = 'http://www.viadeo.com/fr/profile/'.$about[$social_name];
									break;
								case 'pinterest':
									//$link = (($this->touch) ? 'pinterest://user/' : 'https://www.pinterest.fr/') . $about[$social_name];
									$link = 'https://www.pinterest.fr/'.$about[$social_name];
									break;
								case 'instagram':
									//$link = ($this->touch) ? 'instagram://user?username='.$about[$social_name] : 'https://www.instagram.com/'.$about[$social_name].'/';
									$link = 'https://www.instagram.com/'.$about[$social_name].'/';
									break;
								case 'github':
									$link = 'https://github.com/'.$about[$social_name];
									break;
								case 'soundcloud':
									//$link = (($this->touch) ? 'soundcloud://users/' : 'https://soundcloud.com/') . $about[$social_name];
									$link = 'https://soundcloud.com/'.$about[$social_name];
									break;
								case 'tumblr':
									//$link = (($this->touch) ? 'soundcloud://users/' : 'https://soundcloud.com/') . $about[$social_name];
									$link = 'https://'.$about[$social_name].'.tumblr.com/';
									break;
								case 'tiktok':
									//$link = (($this->touch) ? 'soundcloud://users/' : 'https://soundcloud.com/') . $about[$social_name];
									$link = 'https://www.tiktok.com/@'.$about[$social_name];
									break;
							}
                            $social = [
                                'id' => $about[$social_name],
                                'url' => $link
                            ];
						}

						$this->company['socials'][$social_name] = $social;
					}
					$this->company['socials'] = $this->is_array_empty($this->company['socials']) ? array() : $this->company['socials'];
					/*}*/
					break;
				case 'specifications':
					foreach ($value as $day => $op_info) {
						foreach ($op_info as $t => $v) {
							$this->company['specifications'][$day][$t] = isset($schedule[$day][$t]) ? $schedule[$day][$t] : $this->company['specifications'][$day][$t];
						}
					}
					break;
				case 'openinghours':
					$this->company[$info] = $about['openinghours'];

					$op = parent::fetchData(array('context'=>'all','type'=>'op'));
					$op_content = parent::fetchData(array('context'=>'all','type'=>'op_content'));

					foreach ($op as $d) {
						$abbr = $d['day_abbr'];
						$schedule[$abbr] = $d;

						foreach ($op_content as $opc) {
							$schedule[$abbr]['close_txt'][$opc['iso_lang']] = $opc['text_' . strtolower($abbr)];
						}
					}
					break;
				default:
					$this->company[$info] = isset($about[$info]) ? $about[$info] : $this->company[$info];
			}
		}

		return $this->company;
	}

	/**
	 * @return array
	 */
	public function getContentData(){
		$data = parent::fetchData(['context'=>'all','type'=>'content']);
		$newArr = [];
		foreach ($data as $item) {
			$newArr[$item['id_lang']][$item['name_info']] = $item['value_info'];
		}
		return $newArr;
	}

	/**
	 * @param array $row
	 * @return array
	 */
	public function setHrefLangData(array $row): array {
		$arr = [];
		if(!empty($row)) {
			foreach ($row as $item) {
				$arr[$item['id_lang']] = $this->routingUrl->getBuildUrl([
					'type' =>  'about',
					'iso'  =>  $item['iso_lang'],
					'id'   =>  $item['id_pages'],
					'url'  =>  $item['url_pages']
				]);
			}
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
	 * @param $custom
	 * @param $current
	 * return array
	 */
	private function parseConf($custom,$current)
	{
		$conf = array(
			'id' => null,
			'id_parent' => ($current['controller']['id_parent'] ? $current['controller']['id_parent'] : null),
			'type' => 'data',
			'lang' =>  $current['lang']['iso'],
			'context' => array(
				1 => 'parent'
			),
			'sort' => array(
				'type' => 'order',
				'order' => 'ASC'
			),
			'exclude' => null,
			'limit' => null,
			'deepness' => 0
		);

		// Define context
		if (isset($custom['context'])) {
			if (is_array($custom['context'])) {
				foreach ($custom['context'] as $k => $v) {
					$conf['context'][1] = $k;
					$conf['context'][2] = $v;
				}
			}
			else {
				$allowed = array(
					'all',
					'parent',
					'child',
					'one'
				);

				if (in_array($custom['context'],$allowed)) $conf['context'][1] = $custom['context'];
			}
		}

		// Define select
		if (isset($custom['select'])) {
			if ($custom['select'] === 'current') {
				$conf['type'] = 'collection';
			}
			elseif ($custom['select'] === 'all') {
				$conf['id'] = null;
				$conf['type'] = null;
			}
			else {
				$conf['id'] = $custom['select'];
				$conf['type'] = 'collection';
			}
		}

		// Define exclude
		if (isset($custom['exclude'])) {
			if (is_array($custom['exclude'])) {
				$conf['exclude'] = $custom['exclude'];
				$conf['type'] = 'collection';
			}
		}

		// Define limit
		if (isset($custom['limit'])) $conf['limit'] = $custom['limit'];

		// Define sort
		if (isset($custom['sort'])) {
			if (is_array($custom['sort'])) {
				if(array_key_exists('type', $custom['sort'])) $conf['sort']['type'] =  $custom['sort']['type'];
				if(array_key_exists('order', $custom['sort'])) $conf['sort']['order'] =  $custom['sort']['order'];
			}
		}

		// deepness for element
		if(isset($custom['deepness'])) {
			$deepness_allowed = array('all','none');
			if (in_array($custom['deepness'],$deepness_allowed)) {
				if($custom['deepness'] == 'all'){
					$conf['deepness'] = null;
				}
				elseif($custom['deepness'] == 'none') {
					$conf['deepness'] = 0;
				}
			}
			else {
				$conf['deepness'] = 0;
			}
		}

		// Override with plugin
		if (isset($custom['plugins'])) $conf['plugins'] = $custom['plugins'];

		return $conf;
	}

	/**
	 * Retourne les données sql sur base des paramètres passés en paramète
	 * @param $custom
	 * @param array $current
	 * @param bool $override
	 * @return array|null
	 */
	public function getData($custom,$current,$override = false)
	{
		if (!(is_array($custom))) return null;

		if (!(array_key_exists('controller', $current))) return null;

		$conf = $this->parseConf($custom,$current);
		$current = $current['controller'];
		$current['name'] = !empty($current['name']) ? $current['name'] : 'pages';

		// *** Load SQL data
		$conditions = '';
		$data = null;

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
				$conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 ';

				/*if (isset($custom['select'])) {
					$conditions .= ' AND (p.id_pages IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ') OR p.id_parent IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . '))';
				}*/

				if (isset($custom['exclude'])) {
					$conditions .= ' AND p.id_pages NOT IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ') AND p.id_parent NOT IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ')';
				}

				if ($custom['type'] == 'menu') {
					$conditions .= ' AND p.menu_pages = 1';
				}

				// Set order
				switch ($conf['sort']['type']) {
					case 'order':
						$conditions .= ' ORDER BY p.id_parent, p.order_pages '.$conf['sort']['order'];
						break;
				}

				if ($conf['limit'] !== null) $conditions .= ' LIMIT ' . $conf['limit'];

				if ($conditions != '') {
					$data = parent::fetchData(
						array('context' => 'all', 'type' => 'pages', 'conditions' => $conditions),
						array('iso' => $conf['lang'])
					);

					if(is_array($data) && !empty($data)) {
						$branch = ($conf['id'] !== null) ? $conf['id'] : 'root';
						$data = $this->data->setPagesTree($data,'pages',$branch);
					}
				}
			}
		}
		elseif ($conf['context'][1] == 'one') {
			if ($override) {
				$getCallClass = $this->modelPlugins->getCallClass($override);
				if(method_exists($getCallClass,'override')){
					$conf['data'] = 'one';
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
				$conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 ';

				if (isset($custom['select'])) {
					$conditions .= ' AND p.id_pages IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ')';
				}

				if (isset($custom['exclude'])) {
					$conditions .= ' AND p.id_pages NOT IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ') AND p.id_parent NOT IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ')';
				}

				if ($custom['type'] == 'menu') {
					$conditions .= ' AND p.menu_pages = 1';
				}

				// Set order
				switch ($conf['sort']['type']) {
					case 'order':
						$conditions .= ' ORDER BY p.id_parent, p.order_pages '.$conf['sort']['order'];
						break;
				}

				if ($conf['limit'] !== null) $conditions .= ' LIMIT ' . $conf['limit'];

				if ($conditions != '') {
					$data = parent::fetchData(
						array('context' => 'all', 'type' => 'pages', 'conditions' => $conditions),
						array('iso' => $conf['lang'])
					);
				}
			}
		}
		elseif ($conf['context'][1] == 'parent') {
			if ($override) {
				$getCallClass = $this->modelPlugins->getCallClass($override);
				if(method_exists($getCallClass,'override')){
					$conf['data'] = 'parent';
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
				$conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 AND p.id_parent IS NULL ';

				if (isset($custom['select'])) {
					$conditions .= ' AND p.id_pages IN (' . implode(',',$conf['id']) . ') ';
				}

				if (isset($custom['exclude'])) {
					$conditions .= ' AND p.id_pages NOT IN (' . implode(',',$conf['id']) . ') ';
				}

				if ($conf['type'] == 'menu') {
					$conditions .= ' AND p.menu_pages = 1';
				}

				// Set order
				switch ($conf['sort']['type']) {
					case 'order':
						$conditions .= ' ORDER BY p.id_pages, p.order_pages '.$conf['sort']['order'];
						break;
				}

				if ($conf['limit'] !== null) $conditions .= ' LIMIT ' . $conf['limit'];

				if ($conditions != '') {
					$data = parent::fetchData(
						array('context' => 'all', 'type' => 'pages', 'conditions' => $conditions),
						array('iso' => $conf['lang'])
					);

					if(is_array($data) && !empty($data)) {
						$branch = ($conf['id'] !== null) ? $conf['id'] : 'root';
						$data = $this->data->setPagesTree($data,'pages',$branch);
					}
				}
			}
			if($data != null AND ($conf['context'][2] == 'child'))
			{
				if ($override) {
					$getCallClass = $this->modelPlugins->getCallClass($override);
					if(method_exists($getCallClass,'override')){
						foreach ($data as $k1 => $v1) {
							$conf['data'] = 'child';
							$conf['controller'] = $current;
							$conf['id_pages'] = $v1['id_pages'];
							$data_2 = call_user_func_array(
								array(
									$getCallClass,
									'override'
								),
								array(
									$conf,
									$custom
								)
							);
							if ($data_2 != null) {
								$data[$k1]['subdata'] = $data_2;
							}
						}
						$data_2 = null;
					}
				} else {

					foreach ($data as $k1 => $v1) {

						$conditions = '';
						$conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1
                    AND p.id_parent = :id';

						/*if (isset($custom['select'])) {

							$conditions .= ' AND p.id_pages IN (' . $conf['id'] . ') ';
						}*/
						if (isset($custom['exclude'])) {

							$conditions .= ' AND p.id_pages NOT IN (' . $conf['id'] . ') ';
						}

						if ($conf['type'] == 'menu') {
							$conditions .= ' AND p.menu_pages = 1';
						}

						$conditions .= ' GROUP BY p.id_pages ORDER BY p.order_pages ASC';

						if ($conf['limit'] != null) {
							$conditions .= ' LIMIT ' . $conf['limit'];
						}


						if ($conditions != '') {
							$data_2 = parent::fetchData(
								array('context' => 'all', 'type' => 'child', 'conditions' => $conditions),
								array(
									':iso' => $conf['lang'],
									':id' => $v1['id_pages']
								)
							);
						}

						if ($data_2 != null) {
							$data[$k1]['subdata'] = $data_2;
						}
					}
					$data_2 = null;
				}
			}
		}
		elseif ($conf['context'][1] == 'child') {
			if ($override) {
				$getCallClass = $this->modelPlugins->getCallClass($override);
				if(method_exists($getCallClass,'override')){
					$conf['data'] = 'child';
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
				$params = array('iso' => $conf['lang']);
				$conditions = '';
				$conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1';

				if($conf['id'] !== null) {
					$conditions .= ' AND p.id_parent = :id';
					$params['id'] = $conf['id'];
				}
				else {
					$conditions .= ' AND p.id_parent IS NULL';
				}

				if ($conf['type'] == 'menu') {
					$conditions .= ' AND p.menu_pages = 1';
				}

				$conditions .= ' GROUP BY p.id_pages';
				if ($conf['sort'] != null) {
					$conditions .= ' ORDER BY p.order_pages';
				}
				if ($conf['limit'] != null) {
					$conditions .= ' LIMIT ' . $conf['limit'];
				}

				$data = parent::fetchData(
					array(
						'context' => 'all',
						'type' => 'child',
						'conditions' => $conditions
					),
					$params
				);
			}
		}

		return $data;
	}

	/**
	 * Retourne les données sql sur base des paramètres donnés
	 * @param $custom
	 * @param array $current
	 * @return array|null
	 */
	public function getShortData(array $custom,array $current)
	{
		if (!(is_array($custom))) return null;

		if (!(array_key_exists('controller', $current))) return null;

		$conf = $this->parseConf($custom,$current);
		$current = $current['controller'];
		$current['name'] = !empty($current['name']) ? $current['name'] : 'pages';

		// *** Load SQL data
		$conditions = '';
		$data = null;

		if ($conf['context'][1] == 'all') {
			$conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 ';

			if (isset($custom['exclude'])) {
				$conditions .= ' AND p.id_pages NOT IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ') AND p.id_parent NOT IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ')';
			}

			if (isset($custom['type']) && $custom['type'] === 'menu') {
				$conditions .= ' AND p.menu_pages = 1';
			}

			// Set order
			switch ($conf['sort']['type']) {
				case 'order':
					$conditions .= ' ORDER BY p.id_parent, p.order_pages '.$conf['sort']['order'];
					break;
			}

			if ($conf['limit'] !== null) $conditions .= ' LIMIT ' . $conf['limit'];

			if ($conditions != '') {
				$data = parent::fetchData(
					array('context' => 'all', 'type' => 'pages_short', 'conditions' => $conditions),
					array('iso' => $conf['lang'])
				);

				if(is_array($data) && !empty($data)) {
					$branch = ($conf['id'] !== null) ? $conf['id'] : 'root';
					$data = $this->data->setPagesTree($data,'pages',$branch);
				}
			}
		}
		elseif ($conf['context'][1] == 'one') {
			$conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 ';

			if (isset($custom['select'])) {
				$conditions .= ' AND p.id_pages IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ')';
			}

			if (isset($custom['exclude'])) {
				$conditions .= ' AND p.id_pages NOT IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ') AND p.id_parent NOT IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ')';
			}

			if ($custom['type'] == 'menu') {
				$conditions .= ' AND p.menu_pages = 1';
			}

			// Set order
			switch ($conf['sort']['type']) {
				case 'order':
					$conditions .= ' ORDER BY p.id_parent, p.order_pages '.$conf['sort']['order'];
					break;
			}

			if ($conf['limit'] !== null) $conditions .= ' LIMIT ' . $conf['limit'];

			if ($conditions != '') {
				$data = parent::fetchData(
					array('context' => 'all', 'type' => 'pages_short', 'conditions' => $conditions),
					array('iso' => $conf['lang'])
				);
			}
		}

		return $data;
	}

	/**
	 * @param $id
	 * @return array
	 * @throws Exception
	 */
	public function getParents($id)
	{
		return $this->data->getParents($id);
	}
}