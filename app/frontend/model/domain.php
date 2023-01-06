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
class frontend_model_domain extends frontend_db_domain {
	/**
	 * @var frontend_db_domain $instance
	 */
	static protected frontend_db_domain $instance;

	/**
	 * @var frontend_model_template $template
	 * @var frontend_model_data $data
	 */
	protected frontend_model_template $template;
	protected frontend_model_data $data;

	/**
	 * @var array $validDomains
	 */
	public array $validDomains;

	/**
	 * @param frontend_model_template|null $t
	 */
	public function __construct(frontend_model_template$t = null) {
		if (isset(self::$instance) && self::$instance !== null) {
			foreach (get_object_vars(self::$instance) as $prop=>$value) {
				$this->{$prop} = $value;
			}
		}
		else {
			$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
			$this->data = new frontend_model_data($this,$this->template);
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
	 * Return the valid domains
	 */
	public function getValidDomains(): array {
		if(!isset($this->validDomains)) {
			$validDomains = $this->getItems('domain',null,'all',false);
			$this->validDomains = empty($validDomains) ? [] : $validDomains;
		}
		else {
			$validDomains = $this->validDomains;
		}
		return $validDomains;
	}
}