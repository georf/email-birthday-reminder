<?php

    /**
     * @package utils.net.SMTP.Client.Command
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\Command\RCPTCommand.php
     */
    namespace utils\net\SMTP\Client\Command;
    use utils\net\SMTP\Client\AbstractCommand;
    use utils\net\SMTP\Client\Connection;
    use \RuntimeException;

    class RCPTCommand extends AbstractCommand
    {
        /**
         * Recipient email address
         * @var string
         */
        private $recipient;

        public function __construct(Connection $connection, $recipient)
        {
            parent::__construct($connection);
            $this->recipient = $recipient;
        }

        
        public function execute()
        {
            if($this->connection->write(sprintf("RCPT TO:<%s>", $this->recipient))) {
                $response = $this->connection->read();
                if(($code = $response->getCode()) !== 250) {
                    $message = "The recipient %s wasn't accepted";
                    throw new RuntimeException(sprintf($message, $this->recipient), $response->getCode());
                }
            }
        }

    }