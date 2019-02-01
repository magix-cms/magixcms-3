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
* @name array
*
*/
class component_format_array {
    /**
     * Sort an array by values
     * @param $field
     * @param $array
     * @param string $direction
     * @return bool
     */
	public function array_sortBy($field, &$array, $direction = 'asc'){
        if (version_compare(phpversion(), '7.0.0', '>')) {
            usort($array,function ($a, $b) use ($field,$direction) {
                $at = $a[$field];
                $bt = $b[$field];

                if ($at == $bt)
                {
                    return 0;
                }

                if($direction === 'desc') {
                    return ($at > $bt ? -1 : 1);
                }
                else {
                    return ($at < $bt ? -1 : 1);
                }
            });
            return true;
        }else{
            usort($array, create_function('$a, $b', '
                $a = $a["' . $field . '"];
                $b = $b["' . $field . '"];
        
                if ($a == $b)
                {
                    return 0;
                }
        
                return ($a ' . ($direction == 'desc' ? '>' : '<') .' $b) ? -1 : 1;
                '));
            return true;
        }
    }
}