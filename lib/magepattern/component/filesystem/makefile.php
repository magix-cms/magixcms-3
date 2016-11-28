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
 * Date: 20/07/12
 * Time: 21:09
 *
 */
class filesystem_makefile{
    /**
     * Creates a directory recursively.
     *
     * @param string|array|\Traversable $dirs The directory path
     * @param integer $mode The directory mode
     *
     * @throws Exception
     * @copyright symfony 2
     * 
     */
    public function mkdir($dirs, $mode = 0755){
        foreach ($this->toIterator($dirs) as $dir) {
            if (is_dir($dir)) {
                continue;
            }

            if (true !== @mkdir($dir, $mode, true)) {
                throw new Exception(sprintf('Failed to create %s', $dir));
            }
        }
    }
    /**
     * Checks the existence of files or directories.
     *
     * @param string|array|\Traversable $files A filename, an array of files, or a \Traversable instance to check
     *
     * @return Boolean true if the file exists, false otherwise
     * @copyright symfony 2
     * 
     */
    public function exists($files)
    {
        foreach ($this->toIterator($files) as $file) {
            if (!file_exists($file)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Removes files or directories.
     *
     * @param string|array|\Traversable $files A filename, an array of files, or a \Traversable instance to remove
     *
     * @throws Exception
     * @copyright symfony 2
     * 
     */
    public function remove($files){
        $files = iterator_to_array($this->toIterator($files));
        $files = array_reverse($files);
        foreach ($files as $file) {
            if (!file_exists($file) && !is_link($file)) {
                continue;
            }

            if (is_dir($file) && !is_link($file)) {
                $this->remove(new \FilesystemIterator($file));

                if (true !== @rmdir($file)) {
                    throw new Exception(sprintf('Failed to remove directory %s', $file));
                }
            } else {
                // https://bugs.php.net/bug.php?id=52176
                if (defined('PHP_WINDOWS_VERSION_MAJOR') && is_dir($file)) {
                    if (true !== @rmdir($file)) {
                        throw new Exception(sprintf('Failed to remove file %s', $file));
                    }
                } else {
                    if (true !== @unlink($file)) {
                        throw new Exception(sprintf('Failed to remove file %s', $file));
                    }
                }
            }
        }
    }

    /**
     * This function rename files and dir
     *
     * @access public
     * @param $files array
     * @return bool
     * @throws Exception
     */
    public function rename($files){
        try{
            if(is_array($files)){
               // print_r($files);
                // we check that target does not exist
                if (is_readable($files['target'])) {
                    throw new Exception(sprintf('Cannot rename because the target "%s" already exist.', $files['origin']));
                }
                //print $files['origin'];
                if(!file_exists($files['origin'])){
                    return false;
                }
                if (true !== @rename($files['origin'], $files['target'])) {
                    throw new Exception(sprintf('Failed to rename %s', $files['origin']));
                }
            }else{
                throw new Exception(sprintf('%s is not array', $files));
            }

        }catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Copies a file.
     *
     * This method only copies the file if the origin file is newer than the target file.
     *
     * By default, if the target already exists, it is not overridden.
     *
     * @param string $originFile The original filename
     * @param string $targetFile The target filename
     * @param array|bool $override Whether to override an existing file or not
     *
     * @throws Exception
     * @copyright symfony 2
     * @example :
     * $makefile = new filesystem_makefile();
     * $makefile->copy(
         $root."/truc/montest.txt",
         $root."/machin/montest.txt"
        );
     */
    public function copy($originFile, $targetFile, $override = false)
    {
        $this->mkdir(dirname($targetFile));

        if (!$override && is_file($targetFile)) {
            $doCopy = filemtime($originFile) > filemtime($targetFile);
        } else {
            $doCopy = true;
        }

        if ($doCopy) {
            if (true !== @copy($originFile, $targetFile)) {
                throw new Exception(sprintf('Failed to copy %s to %s', $originFile, $targetFile));
            }
        }
    }

    /**
     * Change mode for an array of files or directories.
     *
     * @param string|array|\Traversable $files A filename, an array of files, or a \Traversable instance to change mode
     * @param integer $mode The new mode (octal)
     * @param integer $umask The mode mask (octal)
     * @param Boolean $recursive Whether change the mod recursively or not
     *
     * @throws Exception When the change fail
     */
    public function chmod($files, $mode, $umask = 0000, $recursive = false)
    {
        foreach ($this->toIterator($files) as $file) {
            if ($recursive && is_dir($file) && !is_link($file)) {
                $this->chmod(new \FilesystemIterator($file), $mode, $umask, true);
            }
            if (true !== @chmod($file, $mode & ~$umask)) {
                throw new Exception(sprintf('Failed to chmod file %s', $file));
            }
        }
    }

    /**
     * Change the owner of an array of files or directories
     *
     * @param string|array|\Traversable $files A filename, an array of files, or a \Traversable instance to change owner
     * @param string $user The new owner user name
     * @param Boolean $recursive Whether change the owner recursively or not
     *
     * @throws Exception When the change fail
     */
    public function chown($files, $user, $recursive = false)
    {
        foreach ($this->toIterator($files) as $file) {
            if ($recursive && is_dir($file) && !is_link($file)) {
                $this->chown(new \FilesystemIterator($file), $user, true);
            }
            if (is_link($file) && function_exists('lchown')) {
                if (true !== @lchown($file, $user)) {
                    throw new Exception(sprintf('Failed to chown file %s', $file));
                }
            } else {
                if (true !== @chown($file, $user)) {
                    throw new Exception(sprintf('Failed to chown file %s', $file));
                }
            }
        }
    }

    /**
     * Change the group of an array of files or directories
     *
     * @param string|array|\Traversable $files A filename, an array of files, or a \Traversable instance to change group
     * @param string $group The group name
     * @param Boolean $recursive Whether change the group recursively or not
     *
     * @throws Exception When the change fail
     */
    public function chgrp($files, $group, $recursive = false)
    {
        foreach ($this->toIterator($files) as $file) {
            if ($recursive && is_dir($file) && !is_link($file)) {
                $this->chgrp(new \FilesystemIterator($file), $group, true);
            }
            if (is_link($file) && function_exists('lchgrp')) {
                if (true !== @lchgrp($file, $group)) {
                    throw new Exception(sprintf('Failed to chgrp file %s', $file));
                }
            } else {
                if (true !== @chgrp($file, $group)) {
                    throw new Exception(sprintf('Failed to chgrp file %s', $file));
                }
            }
        }
    }
    /**
     * Returns whether the file path is an absolute path.
     *
     * @param string $file A file path
     *
     * @return Boolean
     * @copyright symfony 2
     * 
     */
    public function isAbsolutePath($file){
        if (strspn($file, '/\\', 0, 1)
            || (strlen($file) > 3 && ctype_alpha($file[0])
                && substr($file, 1, 1) === ':'
                && (strspn($file, '/\\', 2, 1))
            )
            || null !== parse_url($file, PHP_URL_SCHEME)
        ) {
            return true;
        }

        return false;
    }

    /**
     * erase Recursive file in multi dir
     * @param string $directory
     * @param bool $debug
     * @return null|string
     */
    public function removeRecursiveFile($directory,$debug=false){
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory), RecursiveIteratorIterator::SELF_FIRST);
        $dir = null;
        foreach($objects as $name => $object){
            if($object->isDir($name)) continue;
            if($debug == true){
                $dir[] .=  $name;
                //magixcjquery_debug_magixfire::magixFireInfo($dir);
            }else{
                $dir[] .=  @unlink($name);
            }
        }
        return $dir;
    }

    /**
     * writing values in constants
     * @param string $name
     * @param void $val
     * @param path construct $str
     * Creates config.php file
     * @example :
        $full_conf = file_get_contents($config_in);
        writeConstValue('M_DBDRIVER',$M_DBDRIVER,$full_conf);
        writeConstValue('M_DBHOST',$M_DBHOST,$full_conf);
        writeConstValue('M_DBUSER',$M_DBUSER,$full_conf);
        writeConstValue('M_DBPASSWORD',$M_DBPASSWORD,$full_conf);
        writeConstValue('M_DBNAME',$M_DBNAME,$full_conf);
        writeConstValue('M_LOG',$M_LOG,$full_conf);
        writeConstValue('M_TMP_DIR',$M_TMP_DIR,$full_conf);
        writeConstValue('M_FIREPHP',$M_FIREPHP,$full_conf);
     * @param bool $quote
     */
    public function writeConstValue($name,$val,&$str,$quote=true){
        if($quote){
            $quote = '$1,\''.$val.'\');';
        }else{
            $quote = '$1,'.$val.');';
        }
        $val = str_replace("'","\'",$val);
        $str = preg_replace('/(\''.$name.'\')(.*?)$/ms',$quote,$str);
    }

    /**
     * @param mixed $files
     *
     * @return \Traversable
     * @copyright symfony 2
     * 
     */
    private function toIterator($files)
    {
        if (!$files instanceof \Traversable) {
            $files = new \ArrayObject(is_array($files) ? $files : array($files));
        }

        return $files;
    }
}
?>