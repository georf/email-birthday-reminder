<?php

    /**
     * @package utils.net.SMTP.Message
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\net\SMTP\Message\Encoder.php
     */
    namespace utils\net\SMTP\Message;
    use utils\net\SMTP\Message\Header;
    
    interface Encoder
    {
        /**
         * Encodes the provided data
         * @param string $data the data to be encoded
         */
        public function encodeString($data);
        
        /**
         * Encodes the provided header
         * @param string $header the header to be encoded
         * @param string $charset the string character set
         */
        public function encodeHeader($header, $charset);
    }

    