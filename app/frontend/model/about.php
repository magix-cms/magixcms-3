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

# DISCLAIMER

# Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
# versions in the future. If you wish to customize MAGIX CMS for your
# needs please refer to http://www.magix-cms.com for more information.
*/
class frontend_model_about extends frontend_db_about {

	protected $data, $routingUrl, $modelPlugins, $language, $languages;

	/**
	 * @var array, type of website allowed
	 */
	public $type = array(
		'org' 		=> array(
			'schema' => 'Organization',
			'label' => 'Organisation'
		),
		'corp' 		=> array(
			'schema' => 'LocalBusiness',
			'label' => 'Entreprise locale'
		),
		'store' 	=> array(
			'schema' => 'Store',
			'label' => 'Magasin'
		),
		'food' 		=> array(
			'schema' => 'FoodEstablishment',
			'label' => 'Restaurant'
		),
		'place' 	=> array(
			'schema' => 'Place',
			'label' => 'Lieu'
		),
		'person' 	=> array(
			'schema' => 'Person',
			'label' => 'Personne physique'
		)
	);

	/**
	 * @var array, Company informations
	 */
	public $company = array(
		'name' 		=> NULL,
		'desc'	    => NULL,
		'slogan'	=> NULL,
		'type' 		=> NULL,
		'eshop' 	=> '0',
		'tva' 		=> NULL,
		'contact' 	=> array(
			'mail' 			=> NULL,
			'click_to_mail' => '0',
			'crypt_mail' 	=> '1',
			'phone' 		=> NULL,
			'mobile' 		=> NULL,
			'click_to_call' => '1',
			'fax' 			=> NULL,
			'adress' 		=> array(
				'adress' 		=> NULL,
				'street' 		=> NULL,
				'postcode' 		=> NULL,
				'city' 			=> NULL
			),
			'languages' => 'Français'
		),
		'socials' => array(
			'facebook' 	=> NULL,
			'twitter' 	=> NULL,
			'google' 	=> NULL,
			'linkedin' 	=> NULL,
			'viadeo' 	=> NULL
		),
		'openinghours' => '0',
		'specifications' => array(
			'Mo' => array(
				'open_day' 		=> '0',
				'open_time' 	=> NULL,
				'close_time' 	=> NULL,
				'noon_time' 	=> '0',
				'noon_start' 	=> NULL,
				'noon_end' 		=> NULL
			),
			'Tu' => array(
				'open_day' 		=> '0',
				'open_time' 	=> NULL,
				'close_time'	=> NULL,
				'noon_time' 	=> '0',
				'noon_start'	=> NULL,
				'noon_end'		=> NULL
			),
			'We' => array(
				'open_day' 		=> '0',
				'open_time' 	=> NULL,
				'close_time' 	=> NULL,
				'noon_time' 	=> '0',
				'noon_start' 	=> NULL,
				'noon_end' 		=> NULL
			),
			'Th' => array(
				'open_day' 		=> '0',
				'open_time' 	=> NULL,
				'close_time' 	=> NULL,
				'noon_time' 	=> '0',
				'noon_start' 	=> NULL,
				'noon_end' 		=> NULL
			),
			'Fr' => array(
				'open_day' 		=> '0',
				'open_time' 	=> NULL,
				'close_time' 	=> NULL,
				'noon_time' 	=> '0',
				'noon_start' 	=> NULL,
				'noon_end'		=> NULL
			),
			'Sa' => array(
				'open_day' 		=> '0',
				'open_time' 	=> NULL,
				'close_time' 	=> NULL,
				'noon_time' 	=> '0',
				'noon_start' 	=> NULL,
				'noon_end' 		=> NULL
			),
			'Su' => array(
				'open_day' 		=> '0',
				'open_time' 	=> NULL,
				'close_time' 	=> NULL,
				'noon_time' 	=> '0',
				'noon_start' 	=> NULL,
				'noon_end' 		=> NULL
			)
		)
	);

	public function __construct($template)
	{
		$this->routingUrl = new component_routing_url();
		$this->modelPlugins = new frontend_model_plugins();
		$this->data = new frontend_model_data($this);
		$this->language = new frontend_controller_language();
		$this->languages = $this->language->setCollection();
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
	 * @return string
	 */
	private function getActiveLang()
	{
		$langs = $this->getItems('languages',null,'all',false);

		$list = array();
		foreach ($langs as $lang) {
			$list[] = 'languages' === 'iso' ? ucfirst($this->languages[$lang['iso_lang']]) : ucfirst($lang['name_lang']);
		}

		$langs = implode(', ',$list);

		return $langs;
	}

	/**
	 * @return array
	 */
	public function getCompanyData()
	{
		$infoData = parent::fetchData(array('context'=>'all','type'=>'info'));
		$about = array();
		foreach ($infoData as $item) {
			$about[$item['name_info']] = $item['value_info'];
		}
		$schedule = array();

		foreach ($this->company as $info => $value) {
			switch ($info) {
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
					foreach ($value as $social_name => $link) {
						$this->company['socials'][$social_name] = $about[$social_name];
					}
					break;
				case 'specifications':
					foreach ($value as $day => $op_info) {
						foreach ($op_info as $t => $v) {
							$this->company['specifications'][$day][$t] = $schedule[$day][$t];
						}
					}
					break;
				case 'openinghours':
					$this->company[$info] = $about['openinghours'];

					$op = parent::fetchData(array('context'=>'all','type'=>'op'));
					foreach ($op as $d) {
						$schedule[$d['day_abbr']] = $d;
						array_shift($schedule[$d['day_abbr']]);

						$schedule[$d['day_abbr']]['open_time'] = explode(':',$d['open_time']);
						$schedule[$d['day_abbr']]['close_time'] = explode(':',$d['close_time']);
						$schedule[$d['day_abbr']]['noon_start'] = explode(':',$d['noon_start']);
						$schedule[$d['day_abbr']]['noon_end'] = explode(':',$d['noon_end']);
					}
					break;
				default:
					$this->company[$info] = $about[$info];
			}
		}

		return $this->company;
	}

	/**
	 * @return array
	 */
	public function getContentData(){
		$data = parent::fetchData(array('context'=>'all','type'=>'content'));
		$newArr = array();
		foreach ($data as $item) {
			$newArr[$item['id_lang']][$item['name_info']] = $item['value_info'];
		}
		return $newArr;
	}

	/**
	 * @param $row
	 * @return array
	 */
	public function setHrefLangData($row)
	{
		$arr = array();

		foreach ($row as $item) {
			$arr[$item['id_lang']] = $this->routingUrl->getBuildUrl(array(
                'type'      =>  'about',
                'iso'       =>  $row['iso_lang'],
                'id'        =>  $row['id_pages'],
                'url'       =>  $row['url_pages']
            ));
		}
		return $arr;
	}
}
?>