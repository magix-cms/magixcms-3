<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of Mage Pattern.
# The toolkit PHP for developer
# Copyright (C) 2012 - 2013 Gerits Aurelien contact[at]aurelien-gerits[dot]be
#
# OFFICIAL TEAM MAGE PATTERN:
#
#   * Gerits Aurelien (Author - Developer) contact[at]aurelien-gerits[dot]be
#
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
# Redistributions of source code must retain the above copyright notice,
# this list of conditions and the following disclaimer.
#
# Redistributions in binary form must reproduce the above copyright notice,
# this list of conditions and the following disclaimer in the documentation
# and/or other materials provided with the distribution.
#
# DISCLAIMER

# Do not edit or add to this file if you wish to upgrade Mage Pattern to newer
# versions in the future. If you wish to customize Mage Pattern for your
# needs please refer to http://www.magepattern.com for more information.
#
# -- END LICENSE BLOCK -----------------------------------

/**
 * Created by Magix Dev.
 * User: aureliengerits
 * Date: 2/07/12
 * Time: 23:12
 *
 */
abstract class db_adapter_connector {
    /**
     * @access private
     * SGBD host
     *
     * @var string
     */
    protected static $host = MP_DBHOST;
    /**
     * @access protected
     * SGBD Name
     *
     * @var string
     */
    protected static $dbname = MP_DBNAME;
    /**
     * @access protected
     * SGBD User
     *
     * @var string
     */
    protected static $user = MP_DBUSER;
    /**
     * @access protected
     * SFBD Pass
     *
     * @var string
     */
    protected static $pass = MP_DBPASSWORD;

    /**
     * Establish a PDO database connection.
     *
     * @param  array  $config
     * @return PDO
     */
    abstract public function connect($config);

    /**
     * Get the PDO connection options for the configuration.
     *
     * Developer specified options will override the default connection options.
     *
     * @param  array  $config
     * @return array
     */
    protected function options($config)
    {
        $options = (isset($config['options'])) ? $config['options'] : array();

        return $this->options + $options;
    }

}