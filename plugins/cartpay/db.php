<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2018 magix-cms.com support[at]magix-cms[point]com
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com>
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
class plugins_cartpay_db
{
	/**
	 * @param $config
	 * @param bool $params
	 * @return mixed|null
	 * @throws Exception
	 */
	public function fetchData($config, $params = false)
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'carts':
					$cond = '';

					if(isset($config['search'])) {
						if(is_array($config['search'])) {
							$nbc = 0;
							foreach ($config['search'] as $key => $q) {
								if($q !== '') {
									$cond .= !$nbc ? ' WHERE ' : 'AND ';
									switch ($key) {
										case 'id_account':
										case 'active_ac':
											$cond .= 'a.'.$key.' = '.$q.' ';
											break;
										case 'iso_lang':
											$cond .= 'l.id_lang = '.$q.' ';
											break;
										case 'email_ac':
										case 'firstname_ac':
										case 'lastname_ac':
											$cond .= "a.".$key." LIKE '%".$q."%' ";
											break;
										case 'date_register':
											$dateFormat = new component_format_date();
											$q = $dateFormat->date_to_db_format($q);
											$cond .= "a.".$key." LIKE '%".$q."%' ";
											break;
									}
									$nbc++;
								}
							}
						}
					}

					$sql = 'SELECT
 								a.id_account,
 								l.iso_lang,
 								a.email_ac,
 								a.firstname_ac,
 								a.lastname_ac,
 								a.active_ac,
 								a.date_create
							FROM `mc_account` as a
							JOIN `mc_cartpay` as cart USING (id_account)
							JOIN `mc_cartpay_order` as o USING (id_cart)
							JOIN `mc_lang` as l USING(`id_lang`)' . $cond;
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'config':
					$sql = 'SELECT * FROM `mc_cartpay_config` ORDER BY id_config DESC LIMIt 0,1';
					break;
				case 'auth':
					$sql = 'SELECT *
							FROM `mc_account` as a
							WHERE `email_ac` = :email_ac
							AND `passcrypt_ac` = :passwd_ac';
					break;
				case 'account':
					$sql = 'SELECT *
							FROM `mc_account` as a
							JOIN `mc_account_address` as aa USING(`id_account`)
							JOIN `mc_account_social` as asos USING(`id_account`)
							JOIN `mc_lang` as l USING(`id_lang`)
							WHERE `id_account` = :id';
					break;
				case 'accountEmail':
					$sql = 'SELECT *
							FROM `mc_account` as a
							JOIN `mc_account_address` as aa USING(`id_account`)
							JOIN `mc_account_social` as asos USING(`id_account`)
							JOIN `mc_lang` as l USING(`id_lang`)
							WHERE `email_ac` = :email_ac';
					break;
				case 'pwdcrypt':
					$sql = 'SELECT passcrypt_ac
							FROM `mc_account` as a
							WHERE `id_account` = :id';
					break;
				case 'pwdcryptEmail':
					$sql = 'SELECT passcrypt_ac
							FROM `mc_account` as a
							WHERE `email_ac` = :email_ac';
					break;
				case 'searchmail':
					$sql = 'SELECT email_ac
							FROM `mc_account` as a
							WHERE `email_ac` = :email_ac';
					break;
				case 'session':
					$sql = 'SELECT *
							FROM mc_account_session
							WHERE id_session = :id_session';
					break;
				case 'accountSession':
					$sql = 'SELECT *
							FROM mc_account_session
							WHERE `keyuniqid_ac` = :keyuniqid_ac';
					break;
				case 'idFromIso':
					$sql = 'SELECT `id_lang` FROM `mc_lang` WHERE `iso_lang` = :iso';
					break;
				case 'accountFromKey':
					$sql = 'SELECT *
							FROM `mc_account` as a
							JOIN `mc_account_address` as aa USING(`id_account`)
							JOIN `mc_account_social` as asos USING(`id_account`)
							JOIN `mc_lang` as l USING(`id_lang`)
							WHERE `keyuniqid_ac` = :keyuniqid';
					break;
				case 'accountHashKey':
					$sql = 'SELECT *
							FROM `mc_account` as a
							JOIN `mc_account_address` as aa USING(`id_account`)
							JOIN `mc_account_social` as asos USING(`id_account`)
							JOIN `mc_lang` as l USING(`id_lang`)
							WHERE `keyuniqid_ac` = :keyuniqid
							AND `change_pwd` = :token';
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function insert($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'account':
				$queries = array(
					array('request' => 'INSERT INTO `mc_account` (`id_lang`, `email_ac`, `passcrypt_ac`, `keyuniqid_ac`, `firstname_ac`, `lastname_ac`, `active_ac`, `date_create`) VALUES (:id_lang, :email_ac, :passcrypt_ac, :keyuniqid_ac, :firstname_ac, :lastname_ac, :active_ac, NOW())', 'params' => $params),
					array('request' => 'SET @account_id = LAST_INSERT_ID()', 'params' => array()),
					array('request' => 'INSERT INTO `mc_account_address` (`id_account`) VALUE (@account_id)', 'params' => array()),
					array('request' => 'INSERT INTO `mc_account_social` (`id_account`) VALUE (@account_id)', 'params' => array())
				);

				try {
					component_routing_db::layer()->transaction($queries);
					return true;
				}
				catch (Exception $e) {
					return 'Exception reçue : '.$e->getMessage();
				}
				break;
			case 'session':
				$sql = 'INSERT INTO `mc_account_session` (`id_session`,`id_account`,`keyuniqid_ac`,`ip_session`,`browser_session`)
						VALUES (:id_session,:id_account,:keyuniqid_ac,:ip_session,:browser_session)';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->insert($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'accountActive':
				$sql = 'UPDATE `mc_account` SET `active_ac` = :active_ac WHERE `id_account` IN ('.$params['id'].')';

				try {
					component_routing_db::layer()->update($sql,array('active_ac' => $params['active_ac']));
					return true;
				}
				catch (Exception $e) {
					return 'Exception reçue : '.$e->getMessage();
				}
				break;
			case 'account':
				$sql = 'UPDATE `mc_account`
						SET 
							`lastname_ac` = :lastname_ac,
							`firstname_ac` = :firstname_ac,
							`phone_ac` = :phone_ac,
							`company_ac` = :company_ac,
							`vat_ac` = :vat_ac,
						WHERE `id_account` = :id';
				break;
			case 'socials':
				$sql = 'UPDATE `mc_account_social`
						SET 
							`website` = :website,
							`facebook` = :facebook,
							`instagram` = :instagram,
							`pinterest` = :pinterest,
							`twitter` = :twitter,
							`google` = :google,
							`linkedin` = :linkedin,
							`viadeo` = :viadeo,
							`github` = :github,
							`soundcloud` = :soundcloud
						WHERE `id_account` = :id';
				break;
			case 'accountConfig':
				$sql = 'UPDATE `mc_account`
						SET 
							`id_lang` = :id_lang,
							`active_ac` = :active_ac,
							`email_ac` = :email_ac
						WHERE `id_account` = :id';
				break;
			case 'pwd':
				$sql = 'UPDATE `mc_account`
						SET 
							`passcrypt_ac` = :passcrypt_ac
						WHERE `id_account` = :id';
				break;
			case 'pwdTicket':
				$sql = 'UPDATE `mc_account`
						SET `change_pwd` = :token
						WHERE `id_account` = :id';
				break;
			case 'newPwd':
				$sql = 'UPDATE `mc_account`
						SET `change_pwd` = NULL,
							`passcrypt_ac` = :newpwd
						WHERE `id_account` = :id';
				break;
			case 'address':
				$sql = 'UPDATE `mc_account_address`
						SET 
							`street_address` = :street_address,
							`postcode_address` = :postcode_address,
							`city_address` = :city_address,
							`country_address` = :country_address
						WHERE `id_account` = :id';
				break;
			case 'activate':
				$sql = 'UPDATE `mc_account` SET `active_ac` = 1 WHERE `id_account` = :id';
				break;
			case 'config':
				$sql = 'UPDATE `mc_account_config`
						SET 
							`links` = :links,
							`cartpay` = :cartpay,
							`google_recaptcha` = :google_recaptcha,
							`recaptchaApiKey` = :recaptchaApiKey,
							`recaptchaSecret` = :recaptchaSecret
						WHERE id_config = :id';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function delete($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';
		$sql = '';

		switch ($config['type']) {
			case 'account':
				$sql = 'DELETE FROM `mc_account` 
						WHERE `id_account` IN ('.$params['id'].')';
				$params = array();
				break;
			case 'session':
				$sql = 'DELETE FROM `mc_account_session`
						WHERE `id_session` = :id_session';
				break;
			case 'lastSessions':
				$sql = 'DELETE FROM `mc_account_session`
						WHERE TO_DAYS(DATE_FORMAT(NOW(), "%Y%m%d")) - TO_DAYS(DATE_FORMAT(last_modified_session, "%Y%m%d")) > :limit';
				break;
			case 'currentSession':
				$sql = 'DELETE FROM `mc_account_session`
						WHERE `id_account` = :id_account';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->delete($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}
}