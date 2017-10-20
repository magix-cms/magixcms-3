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
 * Created by SC BOX.
 * User: aureliengerits
 * Date: 29/07/12
 * Time: 00:53
 *
 */
class collections_ArrayTools{
    /**
     * @static
     * @var $default_country
     */
    private static $default_country= array(
        "AF"=>"Afghanistan",
        "AL"=>"Albania",
        "DZ"=>"Algeria",
        "AD"=>"Andorra",
        "AO"=>"Angola",
        "AG"=>"Antigua and Barbuda",
        "AR"=>"Argentina",
        "AM"=>"Armenia",
        "AW"=>"Aruba",
        "AU"=>"Australia",
        "AT"=>"Austria",
        "AZ"=>"Azerbaijan",
        "BS"=>"Bahamas",
        "BH"=>"Bahrain",
        "BD"=>"Bangladesh",
        "BB"=>"Barbados",
        "BY"=>"Belarus",
        "BE"=>"Belgium",
        "BZ"=>"Belize",
        "BJ"=>"Benin",
        "BM"=>"Bermuda",
        "BT"=>"Bhutan",
        "BO"=>"Bolivia",
        "BA"=>"Bosnia-Herzegovina",
        "BW"=>"Botswana",
        "BR"=>"Brazil",
        "VG"=>"British Virgin Islands",
        "BN"=>"Brunei",
        "BG"=>"Bulgaria",
        "BF"=>"Burkina Faso",
        "BI"=>"Burundi",
        "KH"=>"Cambodia",
        "CM"=>"Cameroon",
        "CA"=>"Canada",
        "CV"=>"Cape Verde",
        "KY"=>"Cayman Islands",
        "CF"=>"Central African Republic",
        "TD"=>"Chad",
        "CL"=>"Chile",
        "CN"=>"China",
        "CO"=>"Colombia",
        "KM"=>"Comoros",
        "CG"=>"Congo (Brazzaville)",
        "CD"=>"Congo (Democratic Rep.)",
        "CR"=>"Costa Rica",
        "CI"=>"Cote d'Ivoire",
        "HR"=>"Croatia",
        "CU"=>"Cuba",
        "CY"=>"Cyprus",
        "CZ"=>"Czech Republic",
        "DK"=>"Denmark",
        "DJ"=>"Djibouti",
        "DM"=>"Dominica",
        "DO"=>"Dominican Republic",
        "EC"=>"Ecuador",
        "EG"=>"Egypt",
        "SV"=>"El Salvador",
        "GQ"=>"Equatorial Guinea",
        "ER"=>"Eritrea",
        "EE"=>"Estonia",
        "ET"=>"Ethiopia",
        "FK"=>"Falkland Islands",
        "FO"=>"Faroe Islands",
        "FJ"=>"Fiji",
        "FI"=>"Finland",
        "FR"=>"France",
        "GF"=>"French Guiana",
        "PF"=>"French Polynesia",
        "GA"=>"Gabon",
        "GM"=>"Gambia",
        "GE"=>"Georgia",
        "DE"=>"Germany",
        "GH"=>"Ghana",
        "GI"=>"Gibraltar",
        "GR"=>"Greece",
        "GL"=>"Greenland",
        "GD"=>"Grenada",
        "GP"=>"Guadeloupe",
        "GT"=>"Guatemala",
        "GG"=>"Guernsey",
        "GN"=>"Guinea",
        "GW"=>"Guinea-Bissau",
        "GY"=>"Guyana",
        "HT"=>"Haiti",
        "HN"=>"Honduras",
        "HK"=>"Hong Kong",
        "HU"=>"Hungary",
        "IS"=>"Iceland",
        "IN"=>"India",
        "ID"=>"Indonesia",
        "IR"=>"Iran",
        "IQ"=>"Iraq",
        "IE"=>"Ireland",
        "IM"=>"Isle of Man",
        "IL"=>"Israel",
        "IT"=>"Italy",
        "JM"=>"Jamaica",
        "JP"=>"Japan",
        "JE"=>"Jersey",
        "JO"=>"Jordan",
        "KZ"=>"Kazakhstan",
        "KE"=>"Kenya",
        "KI"=>"Kiribati",
        "KV"=>"Kosovo",
        "KW"=>"Kuwait",
        "KG"=>"Kyrgyzstan",
        "LA"=>"Laos",
        "LV"=>"Latvia",
        "LB"=>"Lebanon",
        "LS"=>"Lesotho",
        "LR"=>"Liberia",
        "LY"=>"Libya",
        "LI"=>"Liechtenstein",
        "LT"=>"Lithuania",
        "LU"=>"Luxembourg",
        "MO"=>"Macau",
        "MK"=>"Macedonia",
        "MG"=>"Madagascar",
        "MW"=>"Malawi",
        "MY"=>"Malaysia",
        "MV"=>"Maldives",
        "ML"=>"Mali",
        "MT"=>"Malta",
        "MH"=>"Marshall Islands",
        "MQ"=>"Martinique",
        "MR"=>"Mauritania",
        "MU"=>"Mauritius",
        "YT"=>"Mayotte",
        "MX"=>"Mexico",
        "FM"=>"Micronesia",
        "MD"=>"Moldova",
        "MC"=>"Monaco",
        "MN"=>"Mongolia",
        "ME"=>"Montenegro",
        "MA"=>"Morocco",
        "MZ"=>"Mozambique",
        "MM"=>"Myanmar",
        "NA"=>"Namibia",
        "NR"=>"Nauru",
        "NP"=>"Nepal",
        "NL"=>"Netherlands",
        "NC"=>"New Caledonia",
        "NZ"=>"New Zealand",
        "NI"=>"Nicaragua",
        "NE"=>"Niger",
        "NG"=>"Nigeria",
        "KP"=>"North Korea",
        "NO"=>"Norway",
        "OM"=>"Oman",
        "PK"=>"Pakistan",
        "PW"=>"Palau",
        "PA"=>"Panama",
        "PG"=>"Papua New Guinea",
        "PY"=>"Paraguay",
        "PE"=>"Peru",
        "PH"=>"Philippines",
        "PL"=>"Poland",
        "PT"=>"Portugal",
        "PR"=>"Puerto Rico",
        "QA"=>"Qatar",
        "RE"=>"Reunion",
        "RO"=>"Romania",
        "RU"=>"Russia",
        "RW"=>"Rwanda",
        "BL"=>"Saint Barthelemy",
        "KN"=>"Saint Kitts and Nevis",
        "LC"=>"Saint Lucia",
        "MF"=>"Saint Martin",
        "PM"=>"Saint Pierre and Miquelon",
        "VC"=>"Saint Vincent and the Grenadines",
        "WS"=>"Samoa",
        "SM"=>"San Marino",
        "ST"=>"Sao Tome and Principe",
        "SA"=>"Saudi Arabia",
        "SN"=>"Senegal",
        "RS"=>"Serbia",
        "SC"=>"Seychelles",
        "SL"=>"Sierra Leone",
        "SG"=>"Singapore",
        "SK"=>"Slovakia",
        "SI"=>"Slovenia",
        "SB"=>"Solomon Islands",
        "SO"=>"Somalia",
        "ZA"=>"South Africa",
        "KR"=>"South Korea",
        "SS"=>"South Sudan",
        "ES"=>"Spain",
        "LK"=>"Sri Lanka",
        "SD"=>"Sudan",
        "SR"=>"Suriname",
        "SJ"=>"Svalbard",
        "SZ"=>"Swaziland",
        "SE"=>"Sweden",
        "CH"=>"Switzerland",
        "SY"=>"Syria",
        "TW"=>"Taiwan",
        "TJ"=>"Tajikistan",
        "TZ"=>"Tanzania",
        "TH"=>"Thailand",
        "TL"=>"Timor-Leste",
        "TG"=>"Togo",
        "TO"=>"Tonga",
        "TT"=>"Trinidad and Tobago",
        "TN"=>"Tunisia",
        "TR"=>"Turkey",
        "TM"=>"Turkmenistan",
        "TC"=>"Turks and Caicos",
        "TV"=>"Tuvalu",
        "UG"=>"Uganda",
        "UA"=>"Ukraine",
        "AE"=>"United Arab Emirates",
        "GB"=>"United Kingdom",
        "US"=>"United States",
        "UY"=>"Uruguay",
        "UZ"=>"Uzbekistan",
        "VU"=>"Vanuatu",
        "VA"=>"Vatican City",
        "VE"=>"Venezuela",
        "VN"=>"Vietnam",
        "WF"=>"Wallis et Futuna",
        "EH"=>"Western Sahara",
        "YE"=>"Yemen",
        "ZM"=>"Zambia",
        "ZW"=>"Zimbabwe"
    );
    /**
     * @var array $default_language
     */
    private static $default_language = array(
        "ar"=>"Arabic",
        "az"=>"Azerbaijani",
        "bg"=>"Bulgarian",
        "bs"=>"Bosnian",
        "ca"=>"Catalan",
        "fr-ca"=>"Canadian (French)",
        "en-ca"=>"Canadian (English)",
        "cs"=>"Czech",
        "da"=>"Danish",
        "de"=>"German",
        "el"=>"Greek",
        "en"=>"English",
        "es"=>"Spanish",
        "et"=>"Estonian",
        "fi"=>"Finnish",
        "fr"=>"French",
        "he"=>"Hebrew",
        "hr"=>"Croatian",
        "hu"=>"Hungarian",
        "hy"=>"Armenian",
        "is"=>"Icelandic",
        "it"=>"Italian",
        "ja"=>"Japanese",
        "ko"=>"Korean",
        "lt"=>"Lithuanian",
        "lv"=>"Latvian",
        "mk"=>"Macedonian",
        "mn"=>"Mongolian",
        "mt"=>"Maltese",
        "nl"=>"Dutch",
        "no"=>"Norwegian",
        "pl"=>"Polish",
        "pt"=>"Portuguese",
        "ro"=>"Romanian",
        "ru"=>"Russian",
        "sk"=>"Slovak",
        "sl"=>"Slovenian",
        "sq"=>"Albanian",
        "sr"=>"Serbian",
        "sv"=>"Swedish",
        "sz"=>"Montenegrin",
        "th"=>"Thai",
        "tr"=>"Turkish",
        "uk"=>"Ukrainian",
        "uz"=>"Uzbek",
        "vi"=>"Vietnamese",
        "zh"=>"Chinese"
    );
    /**
     * @static
     * @param $iterator
     * @param bool $recursive
     * @return array
     * @throws Exception
     */
    public static function iteratorToArray($iterator, $recursive = true){

        if (!is_array($iterator) && !$iterator instanceof Traversable) {
            throw new Exception(__METHOD__ . ' expects an array or Traversable object');
        }

        if (!$recursive) {
            if (is_array($iterator)) {
                return $iterator;
            }

            return iterator_to_array($iterator);
        }

        if (method_exists($iterator, 'toArray')) {
            return $iterator->toArray();
        }

        $array = array();
        foreach ($iterator as $key => $value) {
            if (is_scalar($value)) {
                $array[$key] = $value;
                continue;
            }

            if ($value instanceof Traversable) {
                $array[$key] = static::iteratorToArray($value, $recursive);
                continue;
            }

            if (is_array($value)) {
                $array[$key] = static::iteratorToArray($value, $recursive);
                continue;
            }

            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * Remplace les valeurs d'un tableau suivant la clé
     * @param array $arr
     * @param array $new_arr
     * @return array
     * @throws Exception
     */
    public function replaceArray($arr,$new_arr=NULL){
        if($new_arr!= NULL){
            if(is_array($new_arr)){
                $orig_arr = $arr;
                if(is_array($orig_arr) || is_array($new_arr)){
                    if (!function_exists('array_replace')){
                        foreach($new_arr as $key=>$value)
                            $orig_arr[$key]=$value;
                        return $orig_arr;
                    }else{
                        return array_replace($orig_arr, $new_arr);
                    }
                }else{
                    throw new Exception('new_arr is not array');
                }
            }else{
                return $arr;
            }
        }else{
            return $arr;
        }
    }

    /**
     * @access public
     * Retourne le tableau des pays par défaut
     * @return array
     */
    public function defaultCountry(){
        return self::$default_country;
    }

    /**
     * @access public
     * Retourne le tableau des langues par défaut
     * @return array
     */
    public function defaultLanguage(){
        return self::$default_language;
    }

    /**
     * @param null $input
     * @param null $columnKey
     * @param null $indexKey
     * @return array|null
     */
    public function array_column($input = null, $columnKey = null, $indexKey = null){
        if (!function_exists('array_column')) {
            /**
             * Returns the values from a single column of the input array, identified by
             * the $columnKey.
             *
             * Optionally, you may provide an $indexKey to index the values in the returned
             * array by the values from the $indexKey column in the input array.
             *
             * @param array $input A multi-dimensional array (record set) from which to pull
             *                     a column of values.
             * @param mixed $columnKey The column of values to return. This value may be the
             *                         integer key of the column you wish to retrieve, or it
             *                         may be the string key name for an associative array.
             * @param mixed $indexKey (Optional.) The column to use as the index/keys for
             *                        the returned array. This value may be the integer key
             *                        of the column, or it may be the string key name.
             * @return array
             */

                // Using func_get_args() in order to check for proper number of
                // parameters and trigger errors exactly as the built-in array_column()
                // does in PHP 5.5.
                $argc = func_num_args();
                $params = func_get_args();
                if ($argc < 2) {
                    trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
                    return null;
                }
                if (!is_array($params[0])) {
                    trigger_error(
                        'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
                        E_USER_WARNING
                    );
                    return null;
                }
                if (!is_int($params[1])
                    && !is_float($params[1])
                    && !is_string($params[1])
                    && $params[1] !== null
                    && !(is_object($params[1]) && method_exists($params[1], '__toString'))
                ) {
                    trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
                    return false;
                }
                if (isset($params[2])
                    && !is_int($params[2])
                    && !is_float($params[2])
                    && !is_string($params[2])
                    && !(is_object($params[2]) && method_exists($params[2], '__toString'))
                ) {
                    trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
                    return false;
                }
                $paramsInput = $params[0];
                $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
                $paramsIndexKey = null;
                if (isset($params[2])) {
                    if (is_float($params[2]) || is_int($params[2])) {
                        $paramsIndexKey = (int) $params[2];
                    } else {
                        $paramsIndexKey = (string) $params[2];
                    }
                }
                $resultArray = array();
                foreach ($paramsInput as $row) {
                    $key = $value = null;
                    $keySet = $valueSet = false;
                    if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                        $keySet = true;
                        $key = (string) $row[$paramsIndexKey];
                    }
                    if ($paramsColumnKey === null) {
                        $valueSet = true;
                        $value = $row;
                    } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                        $valueSet = true;
                        $value = $row[$paramsColumnKey];
                    }
                    if ($valueSet) {
                        if ($keySet) {
                            $resultArray[$key] = $value;
                        } else {
                            $resultArray[] = $value;
                        }
                    }
                }
                return $resultArray;
        }else{
            $resultArray = array_column ($input, $columnKey, $indexKey);
        }
        return $resultArray;
    }
}
?>