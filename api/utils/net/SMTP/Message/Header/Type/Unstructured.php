<?php

    /**
     * @package utils.net.SMTP.Message.Header.Type
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\net\SMTP\Message\Header\Type\Unstructured.php
     * @link http://tools.ietf.org/html/rfc2822#section-2.2.1
     */
    namespace utils\net\SMTP\Message\Header\Type;
    use utils\net\SMTP\Message\Header;

    interface Unstructured extends Header
    {
        /**
         * Retrieves the header encoding
         * @return string
         */
        public function getEncoding();
    }