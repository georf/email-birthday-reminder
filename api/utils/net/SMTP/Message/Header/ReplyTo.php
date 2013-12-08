<?php

    /**
     * @package utils.net.SMTP.Message.Header
     * @filesource utils\net\SMTP\Message\Header\ReplyTo.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace utils\net\SMTP\Message\Header;
    use utils\net\SMTP\Message\AbstractAddressList;
    
    class ReplyTo extends AbstractAddressList
    {
        
        /**
         * @see AbstractAddressList::getName()
         * @return string
         */
        public function getName()
        {
            return "Reply-To";
        }
    
    }