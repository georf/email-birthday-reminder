<?php

namespace MGVmedia;
use utils\net\SMTP\Message\AbstractHeader;

class ContentType extends AbstractHeader
{
    public function __construct($contentType = "text/plain", $charset = "utf-8")
    {
       parent::__construct("Content-Type", $contentType . "; charset=" . $charset);
    } 
}