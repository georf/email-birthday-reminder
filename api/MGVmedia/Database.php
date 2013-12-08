<?php

namespace MGVmedia;

/**
 * Implements Database using MySQL
 *
 * @author Sebastian Gaul <sebastian@dev.mgvmedia.com>
 * @author Georg Limbach <georf@dev.mgvmedia.com>
 *
 */
class Database {


  /**
   * Link to database session
   *
   * @var int
   */
  private $dbConnection = false;

  /**
   * Handler for automatic logging
   */
  private $log = false;
  private $logIp;
  private $logTable;


  /**
   * Establishes database connection
   *
   * @param string $host
   * @param string $database
   * @param string $username
   * @param string $password
   */
  public function __construct($host, $database, $username, $password) {

    // Connect with persistent connection
    $this->dbConnection = @mysql_pconnect($host, $username, $password);

    if (!$this->dbConnection) {
      throw new \Exception('MySQL Connection Database Error: ' . mysql_error());
    }

    if (!mysql_select_db($database, $this->dbConnection)) {
      throw new \Exception('MySQL Connection Database Error: ' . mysql_error());
    }

    $this->query("SET NAMES 'utf8';");
  }

  public function enableLogging($ip, $table) {
    $this->log = true;
    $this->logIp = $ip;
    $this->logTable = $table;
  }


  /**
   * Execute query and return rows as array
   *
   * @param string $mysqlQuery
   * @return array
   */
  public function getRows($mysqlQuery) {

    $result = $this->query($mysqlQuery);

    if (!$result) {
      throw new \Exception('MySQL Connection Database Error: ' . mysql_error().'<br/>'.$mysqlQuery);
    }

    $rows = array();
    while ($row = mysql_fetch_assoc($result)) {
      $rows[] = $row;
    }
    return $rows;
  }


  /**
   * Excecute query and return the first row
   *
   * @param string $mysqlQuery
   * @return array
   */
  public function getFirstRow($mysqlQuery, $key = false) {

    $result = $this->query($mysqlQuery);

    if (!$result) {
      throw new \Exception('MySQL Connection Database Error: ' . mysql_error().'<br/>'.$mysqlQuery);
    }

    if (mysql_num_rows($result) === 0) {
      return false;
    }

    $assoc = mysql_fetch_assoc($result);

        if ($key && isset($assoc[$key])) {
            return $assoc[$key];
        } else {
            return $assoc;
        }
  }


  /**
   * Escape a string
   *
   * @param string $string
   * @return string
   */
  public function escape($string) {
    return mysql_real_escape_string($string, $this->dbConnection);
  }


  /**
   * Inserts a row given by an array
   *
   * Returns the inserted id, 0 or false
   *
   * @param string $table
   * @param array $values
   * @return int | boolean
   */
  public function insertRow($table, $values) {

    if (count($values) === 0) {
      throw new \Exception(_('No value given'));
    }

    $mysqlQuery = 'INSERT INTO `'.$table.'` SET ';
    $mysqlQuery .= $this->set($values);
    $mysqlQuery .= ";";

    $result = $this->query($mysqlQuery);

    if (!$result) {
      throw new \Exception('MySQL Connection Database Error: ' . mysql_error().'<br/>'.$mysqlQuery);
    }

    if (mysql_affected_rows($this->dbConnection) !== 1) {
      return false;
    }

    $id = mysql_insert_id($this->dbConnection);
    $this->insertLog("insert", $table, $id, $values);

    return $id;
  }

  public function deleteRow($table, $id, $colName = 'id') {

    $mysqlQuery = 'DELETE FROM `'.$table.'` ';
    $mysqlQuery .= " WHERE `".$colName."`='".$id."' LIMIT 1;";

    $result = $this->query($mysqlQuery);

    if (!$result) {
      throw new \Exception('MySQL Connection Database Error: ' . mysql_error().'<br/>'.$mysqlQuery);
    }

    $this->insertLog("delete", $table, $id, null);

    return  (mysql_affected_rows($this->dbConnection) === 1);

  }


  /**
   * Updates a row given by an array
   *
   * Returns success
   *
   * @param string $table
   * @param array $values
   * @return int | boolean
   */
  public function updateRow($table, $id, $values, $colName = 'id') {

    if (count($values) === 0) {
      throw new \Exception(_('No value given'));
    }

    $mysqlQuery = 'UPDATE `'.$table.'` SET ';
    $mysqlQuery .= $this->set($values);
    $mysqlQuery .= " WHERE `".$colName."`='".$id."' LIMIT 1;";

    $result = $this->query($mysqlQuery);

    if (!$result) {
      throw new \Exception('MySQL Connection Database Error: ' . mysql_error().'<br/>'.$mysqlQuery);
    }

    $this->insertLog("update", $table, $id, $values);

    return (mysql_affected_rows($this->dbConnection) === 1);
  }

  private function insertLog($type, $table, $id, $values) {
    if ($this->log) {
      $this->log = false;
      $this->insertRow($this->logTable, array(
        "ip" => $this->logIp,
        "type" => $type,
        "table" => $table,
        "table_id" => $id,
        "set" => json_encode($values)
      ));
      $this->log = true;
    }
  }

  private function set($values) {
    $mysqlQuery = array();
    foreach ($values as $col => $value) {
      if (is_int($value))
        $mysqlQuery[] = '`'.$col."` = ".$this->escape($value);
      elseif (is_null($value))
        $mysqlQuery[] = '`'.$col."` = NULL";
      else
        $mysqlQuery[] = '`'.$col."` = '".$this->escape($value)."'";
    }
    return implode(' , ', $mysqlQuery);
  }

  /**
   * Execute a query and returns the result
   *
   * @param string $query
   * @return int Result
   */
  public function query($query) {
    return mysql_query($query, $this->dbConnection);
  }
}
