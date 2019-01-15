<?php

namespace MGVmedia;

class Database {
  private $pdo = false;
  private $log = false;
  private $logIp;
  private $logTable;

  public function __construct($host, $database, $username, $password) {
    try {
      $conStr = sprintf("pgsql:host=%s;dbname=%s;user=%s;password=%s", $host, $database, $username, $password);
      $this->pdo = new \PDO($conStr);
    } catch (\PDOException $e) {
      throw new \Exception($e->getMessage());
    }
  }

  public function enableLogging($ip, $table) {
    $this->log = true;
    $this->logIp = $ip;
    $this->logTable = $table;
  }

  public function getRows($query) {

    $result = $this->query($query);

    $rows = array();
    foreach ($result as $row) {
      $rows[] = $row;
    }
    return $rows;
  }

  public function getFirstRow($query, $key = false) {
    $rows = $this->getRows($query);
    $assoc = $rows[0];
    if ($key && isset($assoc[$key])) {
        return $assoc[$key];
    } else {
        return $assoc;
    }
  }

  public function escape($string) {
    return $this->pdo->quote($string);
  }

  public function insertRow($table, $values) {

    if (count($values) === 0) {
      throw new \Exception(_('No value given'));
    }

    $query = 'INSERT INTO '.$table.' ('.$this->keys($values).') VALUES ('.$this->values($values).');';
    $result = $this->query($query);
    
    $id = $this->pdo->lastInsertId();
    $this->insertLog("insert", $table, $id, $values);

    return $id;
  }

  public function deleteRow($table, $id, $colName = 'id') {

    $query = 'DELETE FROM '.$table.' ';
    $query .= " WHERE ".$colName."=".$id.";";
    $result = $this->query($query);

    $this->insertLog("delete", $table, $id, null);

    return true;
  }

  public function updateRow($table, $id, $values, $colName = 'id') {

    if (count($values) === 0) {
      throw new \Exception(_('No value given'));
    }

    $query = 'UPDATE '.$table.' SET ';
    $query .= $this->set($values);
    $query .= " WHERE ".$colName."='".$id."';";
    $result = $this->query($query);

    $this->insertLog("update", $table, $id, $values);

    return true;
  }

  private function insertLog($type, $table, $id, $values) {
    if ($this->log) {
      $this->log = false;
      $this->insertRow($this->logTable, array(
        "ip" => $this->logIp,
        "type" => $type,
        "table" => $table,
        "table_id" => $id,
        "set" => json_encode($values),
        "logged" => date('Y-m-d H:i')
      ));
      $this->log = true;
    }
  }

  private function keys($values) {
    $query = array();
    foreach ($values as $col => $value) {
      $query[] = '"'.$col.'"';
    }
    return implode(' , ', $query);
  }

  private function values($values) {
    $query = array();
    foreach ($values as $col => $value) {
      if (is_null($value))
        $query[] = "NULL";
      else
        $query[] = $this->escape($value);
    }
    return implode(' , ', $query);
  }

  private function set($values) {
    $query = array();
    foreach ($values as $col => $value) {
      if (is_null($value))
        $query[] = ''.$col." = NULL";
      else
        $query[] = ''.$col." = ".$this->escape($value);
    }
    return implode(' , ', $query);
  }

  public function query($query) {
    $result = $this->pdo->query($query);
    if (!$result) $this->throw_error($query);
    return $result;
  }

  private function throw_error($query) {
    throw new \Exception('Database Error: '.$query."\n".print_r($this->pdo->errorInfo()), true);
  }
}
