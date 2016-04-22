<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of SC BOX.
# SC BOX, The content management system optimized for users
# Copyright (C) 2012 sc-box.com <support@sc-box.com>
#
# OFFICIAL TEAM :
#
#   * Gerits Aurelien (Author - Developer) <aurelien@sc-box.com>
#   * Lesire Samuel (Design) <samuel@sc-box.com>
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

# Do not edit or add to this file if you wish to upgrade SC Box to newer
# versions in the future. If you wish to customize SC Box for your
# needs please refer to http://www.sc-box.com for more information.

/**
 * @author Gerits Aurelien <aurelien@sc-box.com>
 * @copyright  2012 SC BOX
 * @version  Release: $Revision$
 *  Date: 11/08/12
 *  Time: 00:51
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 */
class http_requestMatcher{

    /**
     * Validates an IP address.
     *
     * @param string $requestIp
     * @param string $ip
     *
     * @return boolean True valid, false if not.
     */
    public function checkIp($requestIp, $ip)
    {
        // IPv6 address
        if (false !== strpos($requestIp, ':')) {
            return $this->checkIp6($requestIp, $ip);
        } else {
            return $this->checkIp4($requestIp, $ip);
        }
    }

    /**
     * Validates an IPv4 address.
     *
     * @param string $requestIp
     * @param string $ip
     *
     * @return boolean True valid, false if not.
     */
    protected function checkIp4($requestIp, $ip)
    {
        if (false !== strpos($ip, '/')) {
            list($address, $netmask) = explode('/', $ip, 2);

            if ($netmask < 1 || $netmask > 32) {
                return false;
            }
        } else {
            $address = $ip;
            $netmask = 32;
        }

        return 0 === substr_compare(sprintf('%032b', ip2long($requestIp)), sprintf('%032b', ip2long($address)), 0, $netmask);
    }

    /**
     * Validates an IPv6 address.
     *
     * @author David Soria Parra <dsp at php dot net>
     * @see https://github.com/dsp/v6tools
     *
     * @param string $requestIp
     * @param string $ip
     *
     * @throws Exception
     * @return boolean True valid, false if not.
     */
    protected function checkIp6($requestIp, $ip)
    {
        if (!defined('AF_INET6')) {
            throw new Exception('Unable to check Ipv6. Check that PHP was not compiled with option "disable-ipv6".');
        }

        if (false !== strpos($ip, '/')) {
            list($address, $netmask) = explode('/', $ip, 2);

            if ($netmask < 1 || $netmask > 128) {
                return false;
            }
        } else {
            $address = $ip;
            $netmask = 128;
        }

        $bytesAddr = unpack("n*", inet_pton($address));
        $bytesTest = unpack("n*", inet_pton($requestIp));

        for ($i = 1, $ceil = ceil($netmask / 16); $i <= $ceil; $i++) {
            $left = $netmask - 16 * ($i-1);
            $left = ($left <= 16) ? $left : 16;
            $mask = ~(0xffff >> $left) & 0xffff;
            if (($bytesAddr[$i] & $mask) != ($bytesTest[$i] & $mask)) {
                return false;
            }
        }

        return true;
    }
}
?>