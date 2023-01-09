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
 * Time: 21:04
 * License: Dual licensed under the MIT or GPL Version
 */
/**
 * @author Gerits Aurelien <aurelien@sc-box.com>
 * @copyright  2012 SC BOX
 * @version  Release: $Revision$
 *  Date: 4/08/12
 *  Time: 17:18
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 */
class component_routing_db {
    /**
     * @var db_layer $layer
     */
    private static db_layer $layer;

	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @static
	 * @return db_layer
	 * @throws Exception
	 */
    public static function layer() {
        if(class_exists('db_layer')) {
            if(!isset(self::$layer)) {
				self::$layer = new db_layer();
            }
			return self::$layer;
        }
		else {
            throw new Exception('Class db_layer is not exist');
        }
    }

    /**
     * Chargement du fichier SQL pour la lecture du fichier
     * @param string $file
     * @return array|bool
     */
    private function importSQLFile(string $file) {
		if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);

        try {
            if(!file_exists($file)) {
				$this->logger->log('php', 'error', 'Error : Not File exist .sql', debug_logger::LOG_MONTH);
            }
			else {
				$sql = file_get_contents($file);
                if($sql !== '') {
					$sql_queries = preg_split("/;\\s*[\r\n]+/",$sql);

                    if(!empty($sql_queries)){
                        return $sql_queries;
                    }
					else {
						$this->logger->log('php', 'error', 'Error : SQL File contains no query', debug_logger::LOG_MONTH);
                    }
                }
				else {
					$this->logger->log('php', 'error', 'Error : SQL File is empty', debug_logger::LOG_MONTH);
                }
            }
            return false;
        }
		catch(Exception $e) {
            $this->logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
		return false;
    }

    /**
     * @param $files
     * @return bool
     * Création des tables avec la lecture du fichier SQL
     */
    public function setupSQL($files): bool{
        if($this->importSQLFile($files)) {
            foreach($this->importSQLFile($files) as $query) {
                $query = filter_escapeHtml::trim($query);
                self::layer()->createTable($query);
            }
            return true;
        }
		return false;
    }
}