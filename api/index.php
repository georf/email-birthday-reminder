<?php
function __autoload($class_name) {
  include str_replace("\\", "/", $class_name) . '.php';
}

use RestService\Server;
use MGVmedia\Birthday;

Server::create('/')
  ->addGetRoute('birthdays', function () {
    return Birthday::all();
  })
  ->addPostRoute('birthday', function($date, $name, $hint) {
    $birthday = Birthday::create($date, $name, $hint);
    if (!$birthday->save()) throw new Exception("bad parameter");

    return $birthday->id;
  })
  ->addGetRoute('report', function() {
    return Birthday::report();
  })
  ->addPostRoute('birthday/(\d+)', function($id, $date, $name, $hint) {
    $birthday = Birthday::get($id);
    $birthday->setDate($date);
    $birthday->setName($name);
    $birthday->setHint($hint);
    if (!$birthday->save()) throw new Exception("bad parameter");

    return $birthday->id;
  })
  ->addPostRoute('birthday/(\d+)/destroy', function($id) {
    $birthday = Birthday::get($id);
    if (!$birthday->destroy()) throw new Exception("bad parameter");

    return $birthday->id;
  })
->run();
