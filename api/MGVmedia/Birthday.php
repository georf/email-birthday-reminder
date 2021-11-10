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
      FROM birthdays
      WHERE id = ".$db->escape($id)."
      LIMIT 1
    ");
    $new->id = $row['id'];
    $new->date = $row['date'];
    $new->name = $row['name'];
    $new->hint = $row['hint'];
    return $new;
  }

  public function setDate($date) {
    $this->date = $date;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function setHint($hint) {
    $this->hint = $hint;
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

  public function special() {
    $days = (new \DateTime($this->date))->diff(new \DateTime(date('Y-m-d')))->days;

    if ($days%1000 == 0) {
      return $days.' Tage';
    }

    if (in_array($days, array(1024, 1111, 11111, 12345))) {
      return $days.' Tage';
    }

    if ($this->month() == date('m')) {
      $year = date('Y', $this->time());
      $diff = ((date('Y') - $year) * 12) + (date('m') - $this->month());

      if (in_array($diff, array(100, 111, 222, 333, 444, 512, 555, 666, 777, 888, 999, 1000, 1111))) {
        return $diff.' Monate';
      }
    }

    return false;
  }

  public function date($format) {
    return date($format, $this->time());
  }

  public function diff() {
    $thisYear = mktime(0, 0, 0, $this->date('m'), $this->date('d'), date('Y'));
    $diff = date('z', $thisYear) - date('z');
    $days = date('L')? 366 : 365;
    return ($diff + $days) % $days;
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
    $age = $interval->format('%y');
    if ($today->format('m-d') != $birthdate->format('m-d')) {
      $age++;
    }
    return $age;
  }

  public function __toString() {
    return sprintf("%30s%5d Jahre%11s\n%s", $this->name, $this->age(), date('d.m.Y', $this->time()), $this->hint);
  }

  public function specialToString() {
    return sprintf("%30s\n%30s%5d Jahre%11s\n%s", $this->special(), $this->name, $this->age(), date('d.m.Y', $this->time()), $this->hint);
  }

  public static function todayHaveBirthdays() {
    $allBirthdays = self::all();

    foreach ($allBirthdays as $data) {
      $birthday = self::get($data['id']);
      if ($birthday->today()) return true;
    }
    return false;
  }

  public static function todayHaveSpecialBirthdays() {
    $allBirthdays = self::all();

    foreach ($allBirthdays as $data) {
      $birthday = self::get($data['id']);
      if ($birthday->special()) return true;
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
    $text = "";

    if (count($today)) {
      $text .= 
      "HEUTE\n".
      "=====================================================\n".
      implode("\n-----------------------------------------------------\n", $today)."\n";
    }
    if (count($next7)) {
      $text .=
      "Nächste Woche\n".
      "=====================================================\n".
      implode("\n-----------------------------------------------------\n", $next7)."\n";
    }
    if (count($next14)) {
      $text .=
      "Nächste 2 Wochen\n".
      "=====================================================\n".
      implode("\n-----------------------------------------------------\n", $next14)."\n";
    }

    return $text;
  }

  public static function specialReport() {
    $allBirthdays = self::all();

    $today = array();

    foreach ($allBirthdays as $data) {
      $birthday = self::get($data['id']);
      if ($birthday->special()) {
        $today[] = $birthday->specialToString();
      }
    }
    $text = "";

    if (count($today)) {
      $text .= 
      "HEUTE\n".
      "=====================================================\n".
      implode("\n-----------------------------------------------------\n", $today)."\n";
    }

    return $text;
  }

  public static function all() {
    return self::DB()->getRows("
      SELECT *
      FROM birthdays
      ORDER BY TO_CHAR(date, 'MM'), TO_CHAR(date, 'DD')
    ");
  }

  protected static function DB() {
    $db = new Database(Config::$db_hostname, Config::$db_database, Config::$db_username, Config::$db_password);
    if (isset($_SERVER['REMOTE_ADDR'])) {
      $db->enableLogging($_SERVER['REMOTE_ADDR'], "birthday_logs");
    }
    return $db;
  }
}
