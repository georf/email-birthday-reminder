<?php

    /**
     * @package utils.net.SMTP.Client
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\CommandInvoker.php
     */
    namespace utils\net\SMTP\Client;
    use utils\net\SMTP\Client\Command;

    class CommandInvoker
    {

        /**
         * Invokes an specified command.
         * @param Command $command the command to be invoked
         * @return void
         */
        public function invoke(Command $command)
        {
            $command->execute();
        }

    }