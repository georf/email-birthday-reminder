<?php

namespace MGVmedia;

class Birthday {
  public $id = 0;
  protected $name = "";
  protected $date = "";
  protected $hint = "";

  protected function __construct() {}

  public static function create($date, $name, $hint) {
    $new = new self();
    $new->date = $date;
    $new->name = $name;
    $new->hint = $hint;
    return $new;
  }

  public static function get($id) {
    $new = new self();
    $db = self::DB();
    $row = $db->getFirstRow("
      SELECT * 
      FROM `birthdays`
      WHERE `id` = '".$db->escape($id)."'
      LIMIT 1
    ");
    $new->id = $row['id'];
    $new->date = $row['date'];
    $new->name = $row['name'];
    $new->hint = $row['hint'];
    return $new;
  }

  public function save() {
    if (empty($this->name)) throw new \Exception("Name not set");
    if (empty($this->date)) throw new \Exception("Date not valid");
    
    if ($this->id === 0) {
      $this->id = self::DB()->insertRow('birthdays', array(
        'date' => $this->date,
        'name' => $this->name,
        'hint' => $this->hint,
      ));
      return $this->id !== false;
    } else {
      return self::DB()->updateRow('birthdays', $this->id, array(
        'date' => $this->date,
        'name' => $this->name,
        'hint' => $this->hint,
      ));
    }
  }

  public function destroy() {
    if ($this->id === 0) {
      return false;
    }
    return self::DB()->deleteRow('birthdays', $this->id);
  }

  public function time() {
    return strtotime($this->date);
  }

  public function month() {
    return date('m', $this->time());
  }

  public function day() {
    return date('d', $this->time());
  }

  public function today() {
    return $this->month() == date('m') && $this->day() == date('d');
  }

  public function diff() {
    $diff = date('z', $this->time()) - date('z');
    return $diff%(date('L')? 366 : 365);
  }

  public function next7() {
    return ($this->diff() < 8);
  }

  public function next14() {
    return ($this->diff() < 15); 
  }

  public function age() {
    $today = new \DateTime();
    $birthdate = new \DateTime($this->date);
    $interval = $today->diff($birthdate);
    return $interval->format('%y');
  }

  public function __toString() {
    return sprintf("%30s%5d Jahre\n%s", $this->name, $this->age(), $this->hint);
  }

  public static function todayHaveBirthdays() {
    $allBirthdays = self::all();

    foreach ($allBirthdays as $data) {
      $birthday = self::get($data['id']);
      if ($birthday->today()) return true;
    }
    return false;
  }

  public static function report() {
    $allBirthdays = self::all();
    
    $today = array();
    $next7 = array();
    $next14 = array();

    foreach ($allBirthdays as $data) {
      $birthday = self::get($data['id']);
      if ($birthday->today()) {
        $today[] = $birthday;
      } elseif ($birthday->next7()) {
        $next7[] = $birthday;
      } elseif ($birthday->next14()) {
        $next14[] = $birthday;
      }
    }
    $text = 
    "HEUTE\n".
    "==========================================\n".
    implode("\n------------------------------------------\n", $today)."\n".
    "Nächste Woche\n".
    "==========================================\n".
    implode("\n------------------------------------------\n", $next7)."\n".
    "Nächste 2 Wochen\n".
    "==========================================\n".
    implode("\n------------------------------------------\n", $next14)."\n";

    return $text;
  }

  public static function all() {
    return self::DB()->getRows("
      SELECT *
      FROM `birthdays`
      ORDER BY MONTH(`date`), DAY(`date`)
    ");
  }

  protected static function DB() {
    $db = new Database(Config::$db_hostname, Config::$db_database, Config::$db_username, Config::$db_password);
    if (isset($_SERVER['REMOTE_ADDR'])) {
      $db->enableLogging($_SERVER['REMOTE_ADDR'], "birthday-logs");
    }
    return $db;
  }
}
