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
 * Date: 18/06/12
 * Time: 23:01
 *
 */
class debug_logger {
    /**
     * @var string
     */
    private $pathlog; # Dossier où sont enregistrés les fichiers logs (ex: /Applications/MAMP/www/monsite/logs)
    /**
     * @var bool
     */
    private $ready; # Le logger est prêt quand le dossier de dépôt des logs existe

    # Archivage (pour l'archivage des logs)
    const LOG_VOID  = 'VOID';  # Aucun archivage
    const LOG_MONTH = 'MONTH'; # Archivage mensuel
    const LOG_YEAR  = 'YEAR';  # Archivage annuel

    /**
     * Constructeur
     * Vérifie que le dossier dépôt éxiste
     *
     * @param string $path Chemin vers le dossier de dépôt
     **/
    public function __construct($path){
        /**
         * Start log
         */
        $this->ready = false;

        # Si le dépôt n'éxiste pas
        if( !is_dir($path) ){
            trigger_error("<code>$path</code> not exist", E_USER_WARNING);
            return false;
        }

        $this->pathlog = realpath($path);
        $this->ready = true;

        return true;
    }

    /**
     * Retourne le chemin vers un fichier de log déterminé à partir des paramètres $type, $name et $archive.
     * (ex: /Applications/MAMP/www/monsite/logs/erreurs/201202/201202_erreur_connexion.log)
     * Elle crée le chemin s'il n'existe pas.
     *
     * @param string $type Dossier dans lequel sera enregistré le fichier de log
     * @param string $name Nom du fichier de log
     * @param string $archive Archivage : LOG_VOID, LOG_MONTH ou LOG_YEAR
     * @return string Chemin vers le fichier de log
     **/
    private function path($type, $name, $archive = self::LOG_YEAR){
        # On vérifie que le logger est prêt (et donc que le dossier de dépôt existe)
        if( !$this->ready ){
            trigger_error("Logger is not ready", E_USER_WARNING);
            return false;
        }

        # Contrôle des arguments
        if( !isset($type) || empty($name) ){
            trigger_error("Paramètres incorrects", E_USER_WARNING);
            return false;
        }
        $makefile = new filesystem_makefile();
        # Création dossier du type (ex: /Applications/MAMP/www/monsite/logs/erreurs/)
        if( empty($type) ){
            $type_path = $this->pathlog.'/';
        } else {
            $type_path = $this->pathlog.'/'.$type.'/';
            if( !is_dir($type_path) ){
                $makefile->mkdir(array($type_path));
            }
        }
        $date = new date_dateformat();
        # Création du dossier archive (ex: /Applications/MAMP/www/monsite/logs/erreurs/201202/)
        if( $archive == self::LOG_VOID ){
            $logfile = $type_path.$name.'.log';
        }
        elseif( $archive == self::LOG_MONTH ){
            $current_year     = $date->dateDefine('Y');
            $current_month    = $date->dateDefine('m');
            $type_path_month  = $type_path.$current_year;
            if( !is_dir($type_path_month) ){
                $makefile->mkdir(array($type_path_month));
            }
            $logfile = $type_path_month.'/'.$current_year.$current_month.'_'.$name.'.log';
        }
        elseif( $archive == self::LOG_YEAR ){
            $current_year    = $date->dateDefine('Y');
            $type_path_year  = $type_path.$current_year;
            if( !is_dir($type_path_year) ){
                $makefile->mkdir(array($type_path_year));
            }
            $logfile = $type_path_year.'/'.$current_year.'_'.$name.'.log';
        }
        else{
            trigger_error("LOG Error '$archive'", E_USER_WARNING);
            return false;
        }

        return $logfile;
    }

    /**
     * Écrit (append) $row dans $logfile
     *
     * @param string $logfile Chemin vers le fichier de log
     * @param string $row Chaîne de caractères à ajouter au fichier
     *
     * @return bool
     */
    private function write($logfile, $row){
        if( !$this->ready ){return false;}

        if( empty($logfile) ){
            trigger_error("<code>$logfile</code> is empty", E_USER_WARNING);
            return false;
        }

        $fichier = fopen($logfile,'a+');
        fputs($fichier, $row);
        fclose($fichier);
    }

    /**
     * Enregistre $row dans le fichier log déterminé à partir des paramètres $type, $name et $archive
     *
     * @param string $type Dossier dans lequel sera enregistré le fichier de log
     * @param string $name Nom du fichier de log
     * @param string $row Texte à ajouter au fichier de log
     * @param string $archive Archive : LOG_VOID, LOG_MONTH ou LOG_YEAR
     *
     * @return bool
     */
    public function log($type, $name, $row, $archive = self::LOG_YEAR){
        /**
         * Instance dateformat
         */
        $date = new date_dateformat();
        # Contrôle des arguments
        if( !isset($type) || empty($name) || empty($row) ){
            trigger_error("Params is not defined", E_USER_WARNING);
            return false;
        }

        $logfile = $this->path($type, $name, $archive);

        if( $logfile === false ){
            trigger_error("Unable to save the log", E_USER_WARNING);
            return false;
        }

        # Ajout de la date et de l'heure au début de la ligne
        $row = $date->dateDefine('d/m/Y H:i:s').' '.$row;

        # Ajout du retour chariot de fin de ligne si il n'y en a pas
        if( !preg_match('#\n$#',$row) ){
            $row .= "\n";
        }

        $this->write($logfile, $row);

        # Firephp
        $firephp = new debug_firephp();
        if($firephp instanceof debug_firephp){
            $firephp->error($row);
        }
    }
}
?>