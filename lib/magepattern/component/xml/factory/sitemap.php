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
 * Date: 22/07/12
 * Time: 15:05
 *
 */
abstract class xml_factory_sitemap{
    /**
     * url valid sitemap standard
     * @var NS string
     */
    protected $NS = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    /**
     * URI schema Google sitemap image
     * @var string
     */
    protected $googleimages_xmlns = 'http://www.google.com/schemas/sitemap-image/1.1';
    /**
     * level compressor for GZ
     * @var (int)GZCompressionLevel
     */
    protected $GZCompressionLevel = null;
    /**
     * tabs for changeFreq
     * @var array() changeFreqControl
     * @access protected
     */
    protected $changeFreqControl = array(
        'always','hourly','daily','weekly','monthly','yearly','never'
    );

    /**
     * Validation des éléments pour la création d'un sitemap
     * @param null $loc
     * @param null $lastmod
     * @param null $changefreq
     * @param null $priority
     * @return bool
     * @throws Exception
     */
    protected function validElement($loc=null,$lastmod=null,$changefreq=null,$priority=null){
        if(form_inputFilter::isURL($loc) == false) {
            throw new Exception('Loc is invalid format');
        }
        if($lastmod && !date_dateformat::isW3CValid($lastmod)) {
            throw new Exception('Invalid format for lastmod');
        }
        if($changefreq && !in_array($changefreq, $this->changeFreqControl)) {
            throw new Exception('Invalid format for changefreq');
        }
        if($priority && (!form_inputFilter::isNumeric($priority) || $priority < 0 || $priority > 1)) {
            throw new Exception('Invalid format for priority 0.0 > 1.0');
        }
        elseif($priority) {
            $priority = sprintf('%0.1f',$priority);
        }
        return true;
    }
    /**
     * Level for compressor GZIP 0 - 10
     *
     * @param int $level level compressor
     */
    protected function setGZCompressionLevel($level) {
        $this->GZCompressionLevel = (int) $level;
    }

    /**
     * protected abstract function for create file XML and create GZ
     * @param $file
     * @param $data
     * @throws Exception
     * @return void
     */
    protected function makeGZFile($file, $data) {
        if((int) $this->GZCompressionLevel !== 0) {
            if(!extension_loaded('zlib')) {
                throw new Exception('Unable to find zlib extension');
            }
            if(!$fp = fopen($data, "r")) {
                throw new Exception('Unable to open sitemap file : '.$file);
            }
            $filesize = filesize($data);

            if($filesize === false){
                throw new Exception("filesize error");
            }
            $datafile = fread($fp, $filesize);
            fclose($fp);
            $mode = 'w' . (int) $this->GZCompressionLevel;
            if(!$zp = gzopen($file, $mode)) {
                throw new Exception('Unable to create/update GZIP sitemap file : '.$file);
            }
            gzwrite($zp, $datafile);
            gzclose($zp);
        }
        return true;
    }
    /**
     *
     * @param $data
     * @param $level
     * @return void
     */
    protected function compress($data, $level=0) {

        if(!(int)$level) {
            return $data;
        }
        return gzcompress($data, (int)$level);
    }
    /**
     *
     * Init curl get
     * @param string $url
     * @param string $file
     */
    private function curlSetPing($url,$file){
        try{
            // PING DU SITEMAP A GOOGLE
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://www.google.com/webmasters/tools/ping?sitemap=http%3A%2F%2F'.$url.'%2F'.$file);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_REFERER, http_url::getUrl());
            curl_setopt ($ch, CURLOPT_NOBODY, 1);
            $body = curl_exec($ch);
            curl_close($ch);
        }catch(Exception $e){
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }
    /**
     *
     * @param $url
     * @param $file
     * @return request ping Google webmaster Tools for sitemap update
     */
    protected function googlePing($url,$file){
        /**
         * Find out whether an extension "pecl_http" is loaded + and Class exists
         */
        if (extension_loaded('pecl_http')) {
            if (class_exists('HttpResponse')){
                HttpResponse::getRequestBody('http://www.google.com/webmasters/tools/ping?sitemap=http%3A%2F%2F'.$url.'%2F'.$file);
            }
        }elseif (extension_loaded('curl')) {
            $this->curlSetPing($url,$file);
        }else{
            file_get_contents('http://www.google.com/webmasters/tools/ping?sitemap=http%3A%2F%2F'.$url.'%2F'.$file);
        }
    }
}
?>