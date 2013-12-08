<?php

    /**
     * @package utils.net.SMTP.Client.Connection.State
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\Connection\State\Established.php
     */
    namespace utils\net\SMTP\Client\Connection\State;
    use utils\net\SMTP\Client\Connection\State\Connected;
    use utils\net\SMTP\Client\Authentication;
    use utils\net\SMTP\Client\Connection;
    use \RuntimeException;

    class Established extends Connected
    {

        /**
         * Authenticates the user with specified authentication method.
         * @param Authentication $authentication the authentication method to authenticate
         * @param Connection $context the connection with SMTP server
         * @throws RuntimeException if the user wasn't authenticated
         * @return boolean
         */
        public function authenticate(Authentication $authentication, Connection $context)
        {
            if (!$authentication->authenticate($context)) {
                $message = "Couldn't authenticate the user";
                throw new RuntimeException($message);
            }

            $this->changeState(new Connected(), $context);
            return true;
        }

    }