<?php

function __autoload($class_name) {
  include __DIR__.'/../api/'.str_replace("\\", "/", $class_name) . '.php';
}


use MGVmedia\Birthday;
use MGVmedia\Config;
use MGVmedia\ContentType;
use utils\net\SMTP\Client; // SMTP client
use utils\net\SMTP\Client\Authentication\Login; // authentication mechanism
use utils\net\SMTP\Client\Connection\SSLConnection; // the connection
use utils\net\SMTP\Message; // the message

if (Birthday::todayHaveBirthdays()) {
  $client = new Client(new SSLConnection(Config::$smtp_hostname, Config::$smtp_port));
  $client->authenticate(new Login(Config::$smtp_username, Config::$smtp_password));

  $message = new Message();
  $message->setEncoding("UTF-8");
  $message->getHeaderSet()->insert(new ContentType());
  $message->from(Config::$smtp_username) // sender
          ->to(Config::$send_to) // receiver
          ->subject(Config::$send_subject) // message subject
          ->body(Birthday::report()); // message content
  $client->send($message);
}