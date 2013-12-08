<?php

    /**
     * @package utils.net.SMTP.Client.Authentication
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\Authentication\Login.php
     */

    namespace utils\net\SMTP\Client\Authentication;

use utils\net\SMTP\Client\Connection;
use utils\net\SMTP\Client\Authentication;
use utils\net\SMTP\Client\Authentication\AbstractAuthentication;
use \RuntimeException;
use utils\net\SMTP\Client\CommandInvoker;
use utils\net\SMTP\Client\Command\InputCommand;
use utils\net\SMTP\Client\Command\AUTHCommand;

    class Login extends AbstractAuthentication implements Authentication
    {

        /**
         * Perform an AUTH LOGIN in SMTP server to authenticate the user.
         * @param Connection $connection the connection with SMTP server
         * @link http://www.ietf.org/rfc/rfc2554.txt
         * @return boolean
         */
        public function authenticate(Connection $connection)
        {
            $username = $this->getUsername();
            $password = $this->getPassword();

            $invoker = new CommandInvoker();
            $invoker->invoke(new AUTHCommand($connection, "LOGIN"));
            $invoker->invoke(new InputCommand($connection, base64_encode($username)));

            if ($connection->read()->getCode() === Authentication::ACCEPTED) {
                $invoker->invoke(new InputCommand($connection, base64_encode($password)));
                return $connection->read()->getCode() === Authentication::AUTHENTICATION_PERFORMED;
            }
        }

    }