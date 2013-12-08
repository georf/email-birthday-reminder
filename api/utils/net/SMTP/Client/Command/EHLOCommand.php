<?php

    /**
     * @package utils.net.SMTP.Client.Command
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\Command\EHLOCommand.php
     */

    namespace utils\net\SMTP\Client\Command;
    use utils\net\SMTP\Client\Command\HELLOCommand;
    use \RuntimeException;

    class EHLOCommand extends HELLOCommand
    {

        /**
         * Executes the EHLO command in the SMTP server.
         * @throws RuntimeException if the command wasn't executed successfully
         */
        public function execute()
        {
            if (!$this->performEhloHelo("EHLO")) {
                $message = "Couldn't perform EHLO command in SMTP server.";
                throw new RuntimeException($message);
            }
        }

    }