<?php

    /**
     * @package utils.net.SMTP.Client.Client.Connection
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\Connection\TLSConnection.php
     */
    namespace utils\net\SMTP\Client\Connection;
    use utils\net\SMTP\Client\AbstractConnection;
    use utils\net\SMTP\Client\CommandInvoker;
    use utils\net\SMTP\Client\Command\EHLOCommand;
    use utils\net\SMTP\Client\Command\HELOCommand;
    use utils\net\SMTP\Client\Command\STARTTLSCommand;

    class TLSConnection extends AbstractConnection
    {

        /**
         * Opens a connection with SMTP server using TCP protocol 
         * But performs message exchanging over a TLS encryption.
         * 
         * @param string $host valid SMTP server hostname
         * @param integer $port the SMTP server port
         * @param integer $timeout timeout in seconds for wait a connection.
         */
        public function __construct($host, $port, $timeout = 30)
        {
            parent::__construct();
            if ($this->open("tcp", $host, $port, $timeout)) {
                $commandInvoker = new CommandInvoker();
                $commandInvoker->invoke(new STARTTLSCommand($this));
                $commandInvoker->invoke(new EHLOCommand($this));
                $commandInvoker->invoke(new HELOCommand($this));
            }
        }

    }