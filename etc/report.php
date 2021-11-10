<?php

function __autoload($class_name) {
  include __DIR__.'/../api/'.str_replace("\\", "/", $class_name) . '.php';
}


use MGVmedia\Birthday;
use MGVmedia\Config;

if (Birthday::todayHaveBirthdays()) {

  $headers =  "From: ".Config::$send_from."\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\n";
  mail(Config::$send_to, Config::$send_subject, Birthday::report(), $headers);
}

if (Birthday::todayHaveSpecialBirthdays()) {
  $headers =  "From: ".Config::$send_from."\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\n";
  mail(Config::$special_send_to, Config::$special_send_subject, Birthday::specialReport(), $headers);
}
