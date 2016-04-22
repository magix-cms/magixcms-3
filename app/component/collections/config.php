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
 * Author: Gerits Aurelien <aurelien[at]magix-cms[point]com>
 * Copyright: MAGIX CMS
 * Date: 13/12/13
 * Time: 21:09
 * License: Dual licensed under the MIT or GPL Version
 */
class component_collections_config{
    private static function buildClauseWhere ($filter,$ouput='both')
    {
        if (!empty($filter)) {
            $clause['where'] = ' WHERE ';
            $first = true;
            foreach ($filter as $col => $val) {
                $clause['where'] .= ($first) ? '' : ' AND ';
                $first = false;
                $alias = $col;
                $process = true;
                switch ($col) {
                    case 'attr_name':
                        $col = 'config.attr_name';
                        break;
                    case 'idconfig':
                        $col = 'config.idconfig';
                        break;
                }
                if ($val == 'NOT NULL') {
                    $clause['where'] .= $col.' IS NOT NULL';
                } elseif ($val == 'NULL') {
                    $clause['where'] .= $col.' IS NULL';
                } elseif ($process) {
                    $clause['where'] .= $col.' = :'.$alias;
                    $values[':'.$alias] = $val;
                }
            }

            return array('clause' => $clause['where'],'values' => $values);
        }
        return false;
    }
    public function fetch($where=array()){
        $clause['where'] = '';
        $values = array();
        $buildedWhere = self::buildClauseWhere($where);
        if ($buildedWhere) {
            $clause['where'] = $buildedWhere['clause'];
            $values = $buildedWhere['values'];
        }
        $query = 'SELECT attr_name,status FROM mc_config
    	WHERE attr_name = :attr_name';
        $query  .=  $clause['where'];
        return component_routing_db::layer()->fetch($query,$values);
    }
}