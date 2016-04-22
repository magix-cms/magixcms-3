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
 * Time: 23:19
 *
 */
class db_adapter_sqlite extends db_adapter_connector {

    /**
     * Establish a PDO database connection.
     *
     * @param  array  $config
     * @return PDO
     */
    public function connect($config)
    {
        $options = $this->options($config);

        // SQLite provides supported for "in-memory" databases, which exist only for
        // lifetime of the request. Any given in-memory database may only have one
        // PDO connection open to it at a time. These are mainly for tests.

        if ($config['database'] == ':memory:')
        {
            return new PDO('sqlite::memory:', null, null, $options);
        }

        $path = path('storage').'database'.DS.$config['database'].'.sqlite';

        return new PDO('sqlite:'.$path, null, null, $options);
    }
}