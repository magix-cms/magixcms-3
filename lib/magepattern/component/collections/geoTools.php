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


class collections_geoTools{
    /**
     * @param float $lat // latitude of centre of bounding circle in degrees
     * @param float $lon // longitude of centre of bounding circle in degrees
     * @param int $radius // radius of bounding circle in kilometers
     * @return array
     */
    public function getBoxLimits(float $lat, float $lon, int $radius) {

        $earth_radius = 6371;  // earth's mean radius, km

        // first-cut bounding box (in degrees)
        $maxLat = $lat + rad2deg($radius/$earth_radius);
        $minLat = $lat - rad2deg($radius/$earth_radius);
        $maxLon = $lon + rad2deg(asin($radius/$earth_radius) / cos(deg2rad($lat)));
        $minLon = $lon - rad2deg(asin($radius/$earth_radius) / cos(deg2rad($lat)));
        return [
            'lat'           => deg2rad($lat),
            'lon'           => deg2rad($lon),
            'minLat'        => $minLat,
            'minLon'        => $minLon,
            'maxLat'        => $maxLat,
            'maxLon'        => $maxLon,
            'rad'           => $radius,
            'earth_radius'  => $earth_radius,
        ];
    }
}