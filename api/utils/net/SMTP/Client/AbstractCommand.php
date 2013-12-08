<?php

    /**
     * @package utils.net.SMTP.Client
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\AbstractCommand.php
     */
    namespace utils\net\SMTP\Client;
    use utils\net\SMTP\Client\Command;
    use utils\net\SMTP\Client\Connection;

    abstract class AbstractCommand implements Command
    {

        /**
         * The connection with server
         * @var Connection
         */
        protected $connection;

        /**
         * Sets the SMTP server connection to perform commands on it
         * @param Connection $connection the SMTP server $connection
         */
        public function __construct(Connection $connection)
        {
            $this->connection = $connection;
        }

    }