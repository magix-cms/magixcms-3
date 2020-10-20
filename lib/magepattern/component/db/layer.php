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
class db_layer{
    /**
     * @access protected
     * DRIVER SGBD
     *
     * @var STRING
     */
    protected static $driver = MP_DBDRIVER;
    /**
     * The raw adapter instance.
     *
     * @var adapter
     */
    public $adapter;

    /**
     * The connection configuration array.
     *
     * @var array
     */
    public $config;
    /**
     * @var array
     */
    protected static $setOption = array(
        'mode'          =>  'assoc',
        'closeCursor'   =>  true,
        'debugParams'   =>  false
    );

    /**
     * @var bool
     */
    protected $inTransaction = false;
    /**
     *
     * @var boolean
     */
    protected $isPrepared = false;
    protected $logger;

    /**
     * db_layer constructor.
     * @param bool $config
     * @throws Exception
     */
    public function __construct($config = false){
    	$this->logger = new debug_logger(MP_LOG_DIR);
        try{
            if($config != false){
                if(is_array($config)){
                    if(array_key_exists('charset', $config)){
                        $this->config['charset'] = $config['charset'];
                    }else{
                        $this->config['charset'] = 'utf8';
                    }
                    if(array_key_exists('port', $config)){
                        $this->config['port'] = $config['port'];
                    }else{
                        $this->config['port'] = '3306';
                    }
                    //$this->config['options'] = array(PDO::ATTR_AUTOCOMMIT=>0);
                    /*if(array_key_exists('unix_socket', $config)){
                        $this->config['unix_socket'] = $config['unix_socket'];
                    }else{
                        $this->config['unix_socket'] = '3306';
                    }*/
                }
            }else{
                //$this->config['options'] = array(PDO::ATTR_AUTOCOMMIT=>0);
                $this->config['charset'] = 'utf8';
                $this->config['port'] = '3306';
                //$this->config['unix_socket'] = '3306';
            }
            //$this->connection()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch (PDOException $e){
            $this->logger->log('statement', 'db', 'An Exception PDO has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Retourne le driver courant
     * @return string
     */
    private function driver(){
        return self::$driver;
    }

    /**
     * Charge la class correspondant au driver sélectionné
     * @return PDO
     */
    public function connection(){
        switch(self::driver()){
            case 'mysql' || 'mariadb':
                $adapter = new db_adapter_mysql();
                break;
            case 'pgsql':
                $adapter = new db_adapter_postgres();
                break;
            case 'sqlite':
                $adapter = new db_adapter_sqlite();
                break;
            default:
                $adapter = null;
                break;
        }
        return $adapter->connect($this->config);
    }

    /**
     * @param $mode
     * @return int
     */
    private function setMode($mode){
        switch($mode){
            case 'assoc':
                $fetchmode = PDO::FETCH_ASSOC;
                break;
            case 'class':
                $fetchmode = PDO::FETCH_CLASS;
                break;
            case 'column':
                $fetchmode = PDO::FETCH_NUM;
                break;
            default:
                $fetchmode = PDO::FETCH_ASSOC;
                break;
        }
        return $fetchmode;
    }

    /**
     * @param bool $option
     * @return array
     */
    private function setConfig($option = false){
        if($option != false){
            if(is_array($option)){
                $optionDB = $option;
            }
        }else{
            $optionDB = self::$setOption;
        }
        if(array_key_exists('mode', $optionDB)){
            $setConfig['mode'] = $optionDB['mode'];
        }else{
            $setConfig['mode'] = self::$setOption['mode'];
        }
        if(array_key_exists('closeCursor', $optionDB)){
            $setConfig['closeCursor'] = $optionDB['closeCursor'];
        }else{
            $setConfig['closeCursor'] = self::$setOption['closeCursor'];
        }
        if(array_key_exists('debugParams', $optionDB)){
            $setConfig['debugParams'] = $optionDB['debugParams'];
        }else{
            $setConfig['debugParams'] = self::$setOption['debugParams'];
        }
        return $setConfig;
    }
    /**
     *  Executes an SQL statement, returning a result set as a PDOStatement object
     *
     * @param request $query
     * @return void
     */
    public function query($query)
    {
        try{
            return $this->connection()->query($query);
        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);//__DIR__.'/test'
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     *  Prepares a statement for execution and returns a statement object
     *
     * @param request containt $sql
     * @return PDOStatement
     * @throws Exception
     */
    public function prepare($sql){
    	//$log = preg_replace('~[\r\n]+~', '', $sql);
    	//$log = preg_replace('~([\t]+)~', ' ', $log);
    	//$log = preg_replace('~([ ]+)~', ' ', $log);
		//$this->logger->log('statement', 'requests', trim($log), debug_logger::LOG_VOID);
        try{
            if ($this->isPrepared) {
                throw new Exception('This statement has been prepared already');
            }

            return $this->connection()->prepare($sql);
            $this->isPrepared = true;

        }catch (PDOException $e){
			$this->logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * @return bool
     */
    public function isPrepared()
    {
        return $this->isPrepared;
    }
    /**
     *  Initiates a beginTransaction
     *
     * @internal param void $sql
     * @return void
     */
    public function beginTransaction(){
        if ( $this->inTransaction ) {
            return false;
        } else {
            $connection = $this->connection();
            $connection->beginTransaction();
            return $connection;
        }
        $this->inTransaction = true;
    }
    /**
     * instance exec
     *
     * @param void $sql
     */
    public function exec($sql){
        $this->connection()->exec($sql);
    }
    /**
     * instance commit
     *
     */
    public function commit(){
        $this->connection()->commit();
        $this->inTransaction = false;
    }
    /**
     * instance rollback
     *
     */
    public function rollBack(){
        if($this->connection()->inTransaction()){
            $this->connection()->rollBack();
            $this->inTransaction = false;
        }else{
            // throw new Exception('Must call beginTransaction() before you can rollback');
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('statement', 'db', 'Must call beginTransaction() before you can rollback', debug_logger::LOG_MONTH);
        }
    }
    /**
     * Retourne un tableau contenant toutes les lignes du jeu d'enregistrements
     * @param $sql
     * @param bool $execute
     * @param bool $setOption
     * @throws Exception
     * @return mixed
     * @example :
     * #### No params ###
     * $color = '';
        $db = new db_layer();
        $sql =  'SELECT id, color FROM fruit';
        foreach  ($db->fetchAll($sql) as $row) {
            $color.= $row['color'].'<br />';
        }
        print $color.'<br />';
         * ### With params ###
        $id=1;
        $db = new db_layer();
        $sql =  'SELECT id, color
        FROM fruit
        WHERE id = ?';
        foreach  ($db->fetchAll($sql,array($id)) as $row) {
            $color.= $row['color'];
        }
        print $color.'<br />';
     */
    public function fetchAll($sql,$execute=false,$setOption=false){
        try{
            /**
             * Charge la configuration
             */
			//$logger = new debug_logger(MP_LOG_DIR);//__DIR__.'/test'
			//$logger->log('statement', 'select', $sql, debug_logger::LOG_MONTH);
            $setConfig = $this->setConfig($setOption);
            $prepare = $this->prepare($sql);
            if(is_object($prepare)){
                $prepare->setFetchMode($this->setMode($setConfig['mode']));
                $execute ? $prepare->execute($execute) : $prepare->execute();
                $setConfig['debugParams'] ? $prepare->debugDumpParams():'';
                $result = $prepare->fetchAll();
                $setConfig['closeCursor'] ? $prepare->closeCursor():'';
                return $result;
            }else{
                throw new Exception('fetchAll Error with SQL prepare');
            }

        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);//__DIR__.'/test'
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Récupère la ligne suivante d'un jeu de résultats
     * @param $sql
     * @param bool $execute
     * @param bool $setOption
     * @return mixed
     * @throws Exception
     * @example:
     *
     * $select =  $db->fetch('SELECT id, color,name FROM fruit');
     * print $select['name'];
     */
    public function fetch($sql,$execute=false,$setOption=false){
        try{
            /**
             * Charge la configuration
             */
			//$logger = new debug_logger(MP_LOG_DIR);//__DIR__.'/test'
			//$logger->log('statement', 'select', $sql, debug_logger::LOG_MONTH);
            $setConfig = $this->setConfig($setOption);
            $prepare = $this->prepare($sql);
            if(is_object($prepare)){
                $prepare->setFetchMode($this->setMode($setConfig['mode']));
                $execute ? $prepare->execute($execute) : $prepare->execute();
                $setConfig['debugParams'] ? $prepare->debugDumpParams():'';
                $result = $prepare->fetch();
                $setConfig['closeCursor'] ? $prepare->closeCursor():'';
                return $result;
            }else{
                throw new Exception('fetch Error with SQL prepare');
            }
        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);//__DIR__.'/test'
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Récupère la prochaine ligne et la retourne en tant qu'objet
     * @param $sql
     * @param bool $execute
     * @param bool $setOption
     * @return mixed
     * @throws Exception
     */
    public function fetchObject($sql,$execute=false,$setOption=false){
        try{
            /**
             * Charge la configuration
             */
            $setConfig = $this->setConfig($setOption);
            $prepare = $this->prepare($sql);
            if(is_object($prepare)){
                $execute ? $prepare->execute($execute) : $prepare->execute();
                $setConfig['debugParams'] ? $prepare->debugDumpParams():'';
                $result = $prepare->fetchObject();
                $setConfig['closeCursor'] ? $prepare->closeCursor():'';
                return $result;
            }else{
                throw new Exception('fetchObject Error with SQL prepare');
            }
        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);//__DIR__.'/test'
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Insertion d'une ligne
     * @param $sql
     * @param bool $execute
     * @param bool $setOption
     * @throws Exception
     */
    public function insert($sql,$execute=false,$setOption=false){
        try{
            /**
             * Charge la configuration
             */
            $setConfig = $this->setConfig($setOption);
            $prepare = $this->prepare($sql);
            if(is_object($prepare)){
                $prepare->execute($execute);
                $setConfig['debugParams'] ? $prepare->debugDumpParams():'';
                $setConfig['closeCursor'] ? $prepare->closeCursor():'';
            }else{
                throw new Exception('insert Error with SQL prepare');
            }
        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);//__DIR__.'/test'
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Modification d'une ligne
     * @param $sql
     * @param bool $execute
     * @param bool $setOption
     * @throws Exception
     */
    public function update($sql,$execute=false,$setOption=false){
        try{
            /**
             * Charge la configuration
             */
            $setConfig = $this->setConfig($setOption);
            $prepare = $this->prepare($sql);
            if(is_object($prepare)){
                $prepare->execute($execute);
                $setConfig['debugParams'] ? $prepare->debugDumpParams():'';
                $setConfig['closeCursor'] ? $prepare->closeCursor():'';
            }else{
                throw new Exception('update Error with SQL prepare');
            }
        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Suppression d'une ligne
     * @param $sql
     * @param bool $execute
     * @param bool $setOption
     * @throws Exception
     */
    public function delete($sql,$execute=false,$setOption=false){
        try{
            /**
             * Charge la configuration
             */
            $setConfig = $this->setConfig($setOption);
            $prepare = $this->prepare($sql);
            if(is_object($prepare)){
                $prepare->execute($execute);
                $setConfig['debugParams'] ? $prepare->debugDumpParams():'';
                $setConfig['closeCursor'] ? $prepare->closeCursor():'';
            }else{
                throw new Exception('delete Error with SQL prepare');
            }
        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Effectuer une Transaction prépare
     *
     * @param $queries
     * @param array $config
     *
     * Example (prepare request with named parameters)
     * $queries = array(
     *   array('request'=>'DELETE FROM mytable WHERE id =:id','params'=>array(':id' => $id))
     * );
     *
     * OR (prepare request with question mark parameters)
     *
     * $queries = array(
     *   array('request'=>'DELETE FROM mytable WHERE id = ?','params'=>array($id))
     * );
     * component_routing_db::layer()->transaction($queries,array('type'=>'prepare'));
     *
     * Example (exec request)
     * $sql = array(
     *   'DELETE FROM mytable WHERE id ='.$id
     * );
     * component_routing_db::layer()->transaction($queries,array('type'=>'exec'));
     */
    public function transaction($queries,$config = array('type'=>'prepare')){
        try{
            $transaction = $this->beginTransaction();
            if($transaction->inTransaction()){
                if(is_array($queries)){
					$rslt = array();
                    foreach ($queries as $key => $value){
                        if($config['type'] === 'prepare'){
                            if(is_array($value)) {
                                if (isset($value['request'])) {
									$this->isPrepared = true;
									$setConfig = $this->setConfig(false);
									$prepare = $transaction->prepare($value['request']);
                                    if(is_object($prepare)) {
                                        if(isset($value['fetch']) && $value['fetch']) {
											$prepare->setFetchMode($this->setMode($setConfig['mode']));
											$value['params'] ? $prepare->execute($value['params']) : $prepare->execute();
											$setConfig['debugParams'] ? $prepare->debugDumpParams():'';
											$result = $prepare->fetchAll();
											$setConfig['closeCursor'] ? $prepare->closeCursor():'';
											$rslt[$key] = $result;
										}
                                    	else {
											$value['params'] ? $prepare->execute($value['params']) : $prepare->execute();
										}
                                    }else{
                                        throw new Exception('delete Error with SQL prepare transaction');
                                    }
                                }
                            }
                        }elseif($config['type'] === 'exec'){
                            if(!is_array($value)){
                                $this->isPrepared = false;
                                $transaction->exec($value);
                            }
                        }
                    }
                    $transaction->commit();
                    return $rslt;
                }else{
                    throw new Exception("queries params is not array");
                }
            }else{
                $logger = new debug_logger(MP_LOG_DIR);
                $logger->log('statement', 'db', 'inTransaction : false', debug_logger::LOG_MONTH);
            }
        }catch(Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
            $this->rollBack();
        }
    }

    /**
     * instance fetchColumn
     * @param $sql
     * @param $column
     * @param bool $setOption
     * @throws Exception
     * @return mixed
     */
    public function fetchColumn($sql,$column,$setOption=false){
        try{
            /**
             * Charge la configuration
             */
            $setConfig = $this->setConfig($setOption);
            $prepare = $this->prepare($sql);
            if(is_object($prepare)){
                $prepare->execute();
                $setConfig['debugParams'] ? $prepare->debugDumpParams():'';
                $result = $prepare->fetchColumn($column);
                $setConfig['closeCursor'] ? $prepare->closeCursor():'';
                return $result;
            }else{
                throw new Exception('fetchColumn Error with SQL prepare');
            }
        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Retourne le nombre de colonnes dans le jeu de résultats
     * @param $sql
     * @param bool $setOption
     * @throws Exception
     * @return mixed
     */
    public function columnCount($sql,$setOption=false){
        try{
            /**
             * Charge la configuration
             */
            $setConfig = $this->setConfig($setOption);
            $prepare = $this->prepare($sql);
            if(is_object($prepare)){
                $prepare->execute();
                $setConfig['debugParams'] ? $prepare->debugDumpParams():'';
                $result = $prepare->columnCount();
                //$setConfig['closeCursor'] ? $prepare->closeCursor():'';
                return $result;
            }else{
                throw new Exception('ColumnCount Error with SQL prepare');
            }
        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Retourne le nombre de lignes affectées par la dernière requête DELETE, INSERT ou UPDATE exécutée par l'objet
     * @param $sql
     * @param bool $setOption
     * @throws Exception
     * @return mixed
     */
    public function rowCount($sql,$setOption=false){
        try{
            /**
             * Charge la configuration
             */
            $setConfig = $this->setConfig($setOption);
            $prepare = $this->prepare($sql);
            if(is_object($prepare)){
                $prepare->execute();
                $setConfig['debugParams'] ? $prepare->debugDumpParams():'';
                $result = $prepare->rowCount();
                //$setConfig['closeCursor'] ? $prepare->closeCursor():'';
                return $result;
            }else{
                throw new Exception('rowCount Error with SQL prepare');
            }
        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Create simple table
     * @param $sql
     * @param bool $setOption
     * @throws Exception
     */
    public function createTable($sql,$setOption=false){
        try{
            /**
             * Charge la configuration
             */
            $setConfig = $this->setConfig($setOption);
            $prepare = $this->prepare($sql);
            if(is_object($prepare)){
                $prepare->execute();
                $setConfig['debugParams'] ? $prepare->debugDumpParams():'';
                $setConfig['closeCursor'] ? $prepare->closeCursor():'';
            }else{
                throw new Exception('createTable Error with SQL prepare');
            }
        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * SHOW TABLE WITH PDO
     * @param $table
     * @param bool $setOption
     * @return mixed
     * @throws Exception
     */
    public function showTable($table,$setOption=false){
        try{
            /**
             * Charge la configuration
             */
            $sql = 'SHOW TABLES FROM '.self::getInfo()->getDB().' LIKE  \''. $table. '\'';
            $setConfig = $this->setConfig($sql,$setOption=false);
            $prepare = $this->prepare($sql);
            if(is_object($prepare)){
                $prepare->execute();
                $result = $prepare->rowCount();
                $setConfig['debugParams'] ? $prepare->debugDumpParams():'';
                $setConfig['closeCursor'] ? $prepare->closeCursor():'';
                return $result;
            }else{
                throw new Exception('showTable Error with SQL prepare');
            }
        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * SHOW DATABASE WITH PDO
     * @param $database
     * @param bool $setOption
     * @return mixed
     * @throws Exception
     */
    public function showDatabase($database,$setOption=false){
        try{
            /**
             * Charge la configuration
             */
            $sql = 'SHOW DATABASES LIKE  \''. $database. '\'';
            $setConfig = $this->setConfig($sql,$setOption=false);
            $prepare = $this->prepare($sql);
            if(is_object($prepare)){
                $prepare->execute();
                $result = $prepare->rowCount();
                $setConfig['debugParams'] ? $prepare->debugDumpParams():'';
                $setConfig['closeCursor'] ? $prepare->closeCursor():'';
                return $result;
            }else{
                throw new Exception('showTable Error with SQL prepare');
            }
        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * function truncate table
     *
     * @param void $table
     * @param bool $setOption
     * @throws Exception
     */
    public function truncateTable($table,$setOption=false){
        try{
            /**
             * Charge la configuration
             */
            $sql = 'TRUNCATE TABLE '. $table;
            $setConfig = $this->setConfig($sql,$setOption=false);
            $prepare = $this->prepare($sql);
            if(is_object($prepare)){
                $prepare->execute();
                $setConfig['debugParams'] ? $prepare->debugDumpParams():'';
                $setConfig['closeCursor'] ? $prepare->closeCursor():'';
            }else{
                throw new Exception('showTable Error with SQL prepare');
            }
        }catch (PDOException $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('statement', 'db', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }
    /**
     * Instance getColumnMeta
     * @param integer $column
     */
    public function getColumnMeta($column){
        return $this->connection()->getColumnMeta($column);
    }
    /**
     * Return an array of available PDO drivers
     * @return array(void)
     */
    public function availableDrivers(){
        return $this->connection()->getAvailableDrivers();
    }
    /**
     * Returns the ID of the last inserted row or sequence value
     */
    public function lastInsertId(){
        return $this->connection()->lastInsertId();
    }
    /**
     * Quotes a string for use in a query.
     * @param string $string
     * @return string
     */
    public function quote($string){
        return $this->connection()->quote($string);
    }
    /**
     * Advances to the next rowset in a multi-rowset statement handle
     * @return void
     */
    public function nextRowset(){
        return $this->connection()->nextRowset();
    }
}
?>