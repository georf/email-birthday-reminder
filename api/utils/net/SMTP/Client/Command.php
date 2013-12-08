<?php

    /**
     * @package utils.net.SMTP.Client
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\Command.php
     */
    namespace utils\net\SMTP\Client;

    interface Command
    {

        /**
         * Executes an given command
         * @return void
         */
        public function execute();
    }