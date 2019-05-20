<?php
/*
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of MAGIX CMS.
# MAGIX CMS, The content management system optimized for users
# Copyright (C) 2008 - 2017 magix-cms.com <support@magix-cms.com>
#
# OFFICIAL TEAM :
#
#   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com>
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
class component_core_system{
    /**
     * Retourne le dossier base(ROOT) de Magix CMS
     */
    public static function basePath(){
        try{
            return filter_path::basePath(array('lib','magepattern'));
        }catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }
    /**
     * @access public
     * getUrlConcat Retourne la concaténation de la minification de fichiers
     * @param $options
     * @return string
     * @throws Exception
     * @author Gérits Aurelien and JB Demonte (http://jb.demonte.fr/)
     */
    public function getUrlConcat($options){
        if(!is_array($options)) throw new Exception("Error options is not array");

		if(!array_key_exists('caches', $options)) throw new Exception("Error caches dir is not defined");
		$min_cachePath = $options['caches'];

		if(!array_key_exists('url', $options) || empty($options["url"])) throw new Exception("No url was passed");
		$url = $options["url"];

		if(!array_key_exists('type', $options)) throw new Exception("Must precise the type of the resource");
		$ext = $options["type"];

		$filesgroups = (array_key_exists('filesgroups', $options)) ? $options['filesgroups'] : 'min/groupsConfig.php';
		$minDir = (array_key_exists('minDir', $options)) ? $options['minDir'] : '/min/';
		$callback = (array_key_exists('callback', $options)) ? $options['callback'] : '';
		$name = "";
		$filesCollection = false;

		//Parse a URL and return its components
		$parseurl = parse_url($url);

		//return position
		$position = strpos($parseurl['query'], '=');

		if($position) {
			//return first query
			$query = substr($parseurl['query'],0,$position);
			//return url after query
			$filesPath = substr(strrchr($parseurl['query'], '='), 1);

			if($query !== 'g' && $query !== 'f') throw new Exception("Minifier type should be group (g) or files (f)");
			if(empty($filesPath)) throw new Exception("No filepath");

			if($query === 'g'){
				// Group
				$filesCollection = array();
				if(file_exists($filesgroups)){
					$groups = (require $filesgroups);
					foreach(explode(",", $filesPath) as $group){
						$filesCollection = array_merge($filesCollection, isset($groups[$group]) ? $groups[$group] : array());
					}
				}
				else{
					throw new Exception("filesgroups is not exist");
				}
			}
			elseif($query === 'f'){
				// Files
				$filesCollection = explode(",", $filesPath);
			}
		}

		if(!$filesCollection) $filesCollection = array($url);

		foreach($filesCollection as &$file){
			$file = ltrim($file, "/");
			$name .= $file . "|" . filemtime(self::basePath().$file) . "|" . filesize(self::basePath().$file) . "/";
		};

		$sha1name = sha1($name) . "." . $ext;

		if(file_exists($min_cachePath) AND is_writable($min_cachePath)){
			$filepath = realpath(".") . "/" . $min_cachePath . "/" . $sha1name;

			if (!file_exists($filepath)){
				try {
					$stream = stream_context_create(
						array(
							"ssl"=>array(
								"verify_peer"=>false,
								"verify_peer_name"=>false,
							)
						)
					);
					$content = file_get_contents(http_url::getUrl().$minDir.'?f=' . implode(",", $filesCollection),false,$stream);

					if ($content === false) {
						$logger = new debug_logger(MP_LOG_DIR);
						$logger->log('minify', 'concat', "Concat : Test\r\n
						file(s)=".http_url::getUrl().$minDir.'?f=' . implode(",", $filesCollection)."\r\n
						error = no content\r\n
						content = $content\r\n", debug_logger::LOG_MONTH);
					}
					else {
						file_put_contents($filepath, $content);
					}
				} catch (Exception $e) {
					$logger = new debug_logger(MP_LOG_DIR);
					$logger->log('minify', 'concat', "Concat : Test\n
						file(s)=".http_url::getUrl().$minDir.'?f=' . implode(",", $filesCollection)."\n
						error=$e\n", debug_logger::LOG_MONTH);
				}
			}
			return $callback."/" . $min_cachePath . "/" . $sha1name;
		}
		else{
			throw new Exception("Error ".$min_cachePath." is not writable");
		}
    }
    /**
     * Parse le fichier de configuration
     * @param $file
     * @return mixed
     * @throws Exception
     */
    public function parseIni($file){
        $result = array();
        $section = '';
        if ($lines = file($file)) {
            foreach ($lines as $line){
                if (preg_match('/^###/', $line)){
                	if($line == '###') $section = '';
                    else $section = trim(substr($line,3));
                }
                elseif (!preg_match('/[0-9a-z]/i', $line) or preg_match('/^#/', $line)){
                    continue;
                }
                //if (preg_match('/(.*)=(.*)/', $line, $match)){
                if (preg_match('/^([^=]+)=(.*)$/', $line, $match)){
					if($section !== '') $result[$section][trim($match[1])] = trim($match[2]);
                    else $result[trim($match[1])] = trim($match[2]);
                }
            }
        } else {
            throw new Exception("No valid file specified");
        }
        return $result;
    }
}