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

class autoloader{
    /**
     * CONSTANTE PREFIX SEPARATOR
     */
    CONST PREFIX_SEPARATOR = '_';
    /**
     * @var array $prefixes
     */
    private $prefixes = array();
    /**
     * @var array $prefixFallbacks
     */
    private $prefixFallbacks = array();

    /**
     * Gets the configured class prefixes.
     *
     * @return array A hash with class prefixes as keys and directories as values
     */
    public function getPrefixes()
    {
        return $this->prefixes;
    }

    /**
     * Gets the directory(ies) to use as a fallback for class prefixes.
     *
     * @return array An array of directories
     */
    public function getPrefixFallbacks()
    {
        return $this->prefixFallbacks;
    }

    /**
     * Registers directories to use as a fallback for class prefixes.
     *
     * @param array $dirs An array of directories
     *
     * @throws Exception
     * @api
     */
    public function registerPrefixFallbacks(array $dirs)
    {
        if (!is_array($dirs)) {
            throw new Exception('registerPrefixFallbacks : dirs is not array');
        }
        $this->prefixFallbacks = $dirs;
    }

    /**
     * Registers a directory to use as a fallback for class prefixes.
     *
     * @param string $dir A directory
     */
    public function registerPrefixFallback($dir)
    {
        $this->prefixFallbacks[] = $dir;
    }

    /**
     * Registers an array of classes using the PEAR naming convention.
     *
     * @param array $classes An array of classes (prefixes as keys and locations as values)
     *
     * @throws Exception
     * @api
     */
    public function registerPrefixes(array $classes)
    {
        if (!is_array($classes)) {
            throw new Exception('Prefix pairs must be either an array or Traversable');
        }
        foreach ($classes as $prefix => $locations) {
            $this->prefixes[$prefix] = (array) $locations;
        }
    }

    /**
     * Registers a set of classes using the PEAR naming convention.
     *
     * @param string       $prefix  The classes prefix
     * @param array|string $paths   The location(s) of the classes
     *
     * @api
     */
    public function registerPrefix($prefix, $paths)
    {
        $prefix = rtrim($prefix, self::PREFIX_SEPARATOR). self::PREFIX_SEPARATOR;
        $this->prefixes[$prefix] = (array) $paths;
    }

    /**
     * Registers this instance as an autoloader.
     *
     * @param Boolean $prepend Whether to prepend the autoloader or not
     *
     * @api
     */
    public function register($prepend = false)
    {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $class The name of the class
     */
    private function loadClass($class){
    	$file = $this->findFile($class);
        if ($file) {
            require $file;
        }
    }

    /**
     * Finds the path to the file where the class is defined.
     *
     * @param string $class The name of the class
     *
     * @return string|null The path, if found
     */
    public function findFile($class){
        if ('\\' == $class[0]) {
            $class = substr($class, 1);
        }
        // PEAR-like class name
        $normalizedClass = str_replace(self::PREFIX_SEPARATOR, DIRECTORY_SEPARATOR, $class).'.php';
        foreach ($this->prefixes as $prefix => $dirs) {
             if (0 !== strpos($class, $prefix)) {
             	continue;
             }
             foreach ($dirs as $dir) {
                 $file = $dir.DIRECTORY_SEPARATOR.$normalizedClass;
                 if (is_file($file)) {
                      return $file;
                 }
             }
         }
         foreach ($this->prefixFallbacks as $dir) {
            $file = $dir.DIRECTORY_SEPARATOR.$normalizedClass;
            if (is_file($file)) {
            	return $file;
            }
        }
	}
}
?>