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
 * Date: 26/07/12
 * Time: 01:13
 *
 */
class file_ftp{
    protected
    /**
     * @var $ftp_server
     */
    $ftp_server,
    /**
     * @var $ftp_port
     */
    $ftp_port,
    /**
     * @var $ftp_user
     */
    $ftp_user,
    /**
     * @var $ftp_pass
     */
    $ftp_pass;
    /**
     * @param $ftp_server
     * @param $ftp_port
     * @param $ftp_user
     * @param $ftp_pass
     */
    function __construct($ftp_server,$ftp_port,$ftp_user, $ftp_pass){
        $this->ftp_server = $ftp_server;
        $this->ftp_port   = $ftp_port;
        $this->ftp_user   = $ftp_user;
        $this->ftp_pass   = $ftp_pass;
    }
    /**
     * Connexion FTP
     * @access private
     * @return void
     */
    private function ftpConnect(){
        // set up basic connection
        $connect = ftp_connect($this->ftp_server,$this->ftp_port);
        // login with username and password
        $login_result = ftp_login($connect, $this->ftp_user, $this->ftp_pass);
        return $connect;
    }

    /**
     * @access public
     * Affiche les données du fichier en cours de téléchargement (comparaison local et distante)
     * @param $server_file
     * @return string
     */
    public function remoteFileCmp($server_file){
        // Récupération de la taille du fichier $file
        $res = ftp_size($this->ftpConnect(), $server_file);
        if ($res != -1) {
            //echo 'La taille du fichier '.self::SERVERFILE.' est de '.$res.' octets sur le serveur<br />';
            $getFileSize = $res;
        } else {
            //echo "Impossible de récupérer la taille du fichier";
            $getFileSize ='';
        }
        /*On ferme la connexion FTP*/
        ftp_close($this->ftpConnect());
        $comp = $getFileSize;
        return $comp;
        //print 'Taille du fichier local : '.filesize(self::LOCALFILE).' octets';
    }

    /**
     * Lance le téléchargement du fichier par FTP sur le serveur
     * @param $directory_file
     * @param $server_file
     * @return bool
     */
    public function getRemoteFile($directory_file, $server_file){
        $timeout = ftp_get_option($this->ftpConnect(), FTP_TIMEOUT_SEC);
        // try to download server_file and save to local_file
        if (ftp_get($this->ftpConnect(), $directory_file, $server_file, FTP_BINARY)) {
            return true;
        } else {
            return false;
        }
        ftp_close($this->ftpConnect());
    }
}
?>