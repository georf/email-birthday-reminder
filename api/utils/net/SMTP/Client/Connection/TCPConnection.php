<?php

    /**
     * @package utils.net.SMTP.Client.Client.Connection
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\net\SMTP\Client\Connection\TCPConnection.php
     */
    namespace utils\net\SMTP\Client\Connection;
    use utils\net\SMTP\Client\AbstractConnection;

    class TCPConnection extends AbstractConnection
    {

        /**
         * Opens a connection with SMTP server using TCP protocol
         * @param string $host valid SMTP server hostname
         * @param integer $port the SMTP server port
         * @param integer $timeout timeout in seconds for wait a connection.
         */
        public function __construct($hostname, $port, $timeout = 30)
        {
            parent::__construct();
            $this->open("tcp", $hostname, $port, $timeout);
        }

    }