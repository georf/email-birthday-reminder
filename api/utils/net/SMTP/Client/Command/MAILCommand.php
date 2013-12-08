<?php

    /**
     * @package utils.net.SMTP.Client.Command
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\net\SMTP\Client\Command\MAILCommand.php
     */
    namespace utils\net\SMTP\Client\Command;
    use utils\net\SMTP\Client\AbstractCommand;
    use utils\net\SMTP\Client\Connection;
    use \RuntimeException;

    class MAILCommand extends AbstractCommand
    {
        /**
         * Sender email address
         * @var string
         */
        private $from;

        public function __construct(Connection $connection, $from)
        {
            parent::__construct($connection);
            $this->from = $from;
        }

        
        public function execute()
        {
            if($this->connection->write(sprintf("MAIL FROM:<%s>", $this->from))) {
                $response = $this->connection->read();
                if(($code = $response->getCode()) !== 250) {
                    $message = "Cannot perform MAIL FROM successfully, %s isn't accepted";
                    throw new RuntimeException(sprintf($message, $this->from), $code);
                }
            }
        }

    }