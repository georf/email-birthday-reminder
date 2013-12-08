<?php

    /**
     * @package utils.net.SMTP.Message
     * @filesource \utils\net\SMTP\Message\Encodable.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace utils\net\SMTP\Message;
    
    interface Encodable
    {
        /**
         * Retrieves the encoding
         * @return string
         */
        public function getEncoding();
        
        /**
         * Sets the encoding
         * @param string $encoding the encoding to be used
         */
        public function setEncoding($encoding);
    }