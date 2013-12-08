<?php

    /**
     * @package utils.net.SMTP.Message
     * @filesource utils\net\SMTP\Message\AddressList.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace utils\net\SMTP\Message;
    use \Countable;
    use \IteratorAggregate;
    use utils\net\SMTP\Message\Address;
    use utils\net\SMTP\Message\Header;

    interface AddressList extends \Countable, IteratorAggregate
    {
        
        /**
         * Retrieves all stacked address on the list
         * @return array[Address]
         */
        public function getAddresses();
        
        /**
         * Adds an address in list
         * @param Address $address the address to be added
         */
        public function addAddress(Address $address);
        
    }