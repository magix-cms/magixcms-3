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
* @copyright  MAGIX CMS Copyright (c) 2011-2013 Gerits Aurelien,
* http://www.magix-cms.com
* @license    Dual licensed under the MIT or GPL Version 3 licenses.
* @version    1.1
* @author Lesire Samuel www.sire-sam.be
* @name constructor
*
*/
class component_format_date {
	public function date_to_db_format($d,$separator = '/')
	{
		list($day, $month, $year) = explode($separator, $d);
		$date = "$day";
		if(!empty($month)) {
			$date = "$month-$day";
		}
		if(!empty($month)) {
			$date = "$year-$month-$day";
		}
		return $date;
	}

	/**
	 * @param string $date date
	 * @param DateTimeZone $timezone
	 * @return array
	 */
	public function setTzDateTimeArray(string $date,DateTimeZone $timezone): array {
		try {
			$datetime = new DateTime($date);
		} catch (Exception $e) {
			return [];
		}

		/*$time = [
			'h' => $datetime->format('G'),
			'm' => $datetime->format('i'),
			'text' => $datetime->format('G').':'.$datetime->format('i')
		];*/
		$tzDatetime = $datetime->setTimezone($timezone);
		date_default_timezone_set($timezone->getName());
		$timestamp = $tzDatetime->getTimestamp();

		$date = [
			'timestamp' => $timestamp,
			'time' => strftime('%R',$timestamp),
			'date' => $datetime->format('Y-m-d'),
			'year' => $datetime->format('Y'),
			'month' => [
				'num' => $datetime->format('m'),
				'name' => strftime('%B',$timestamp),
				'abv' => strftime('%b',$timestamp)
			],
			'week' => $datetime->format('W'),
			'day' => [
				'num' => $datetime->format('j'),
				'name' => strftime('%A',$timestamp),
				'abv' => strftime('%a',$timestamp)
			],
			'suffix' => $datetime->format('S'),
		];

		date_default_timezone_set('UTC');

		return $date;
	}
}