<?php

    /**
     * @package utils.net.SMTP.Message
     * @filesource \utils\net\SMTP\Message\Header.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace utils\net\SMTP\Message;
    
    interface Header
    {
        
        /**
         * Retrieves the header name
         * @return string
         */
        public function getName();
        
        /**
         * Retrieves the header value
         * @return string
         */
        public function getValue();
        
        /**
         * Creates and return a string representation of header
         * @return string
         */
        public function __toString();
        
    }