<?php
/*
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of MAGIX CMS.
# MAGIX CMS, The content management system optimized for users
# Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
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
/**
 * MAGIX CMS
 * @category   Model
 * @package    magixglobal
 * @copyright  MAGIX CMS Copyright (c) 2011-2019 Gerits Aurelien,
 * http://www.magix-cms.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    1.1
 * @author Salvatore Di Salvo
 * @name string
 *
 */
class component_format_math {
	/**
	 * Generate x random ids
	 * @param int $nb, amount of random ids to generate
	 * @param null|int $max, id max possible
	 * @param int $min, id min possible
	 * @param bool $duplicate, indicate whether duplicate ids are allowed or not
	 * @return array
	 */
	public function getRandomIds($nb, $max = null, $min = 1, $duplicate = false)
	{
		$ids = array();
		if($nb && $max > 0) {
			if($duplicate) {
				for($i=$min;$i<=$nb;$i++) {
					$ids[] = rand($min,$max);
				}
			}
			else {
				do {
					$rand_id = rand($min,$max);
					if(!in_array($rand_id,$ids)) $ids[] = $rand_id;
				} while (count($ids) < $nb);
			}
		}
		return $ids;
	}
}