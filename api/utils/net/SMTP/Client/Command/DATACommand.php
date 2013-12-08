<?php

    /**
     * @package utils.net.SMTP.Client.Command
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource \utils\net\SMTP\Client\Command\DATACommand.php
     */
    namespace utils\net\SMTP\Client\Command;
    use utils\net\SMTP\Client\AbstractCommand;
    use utils\net\SMTP\Client\Connection;
    use \RuntimeException;

    class DATACommand extends AbstractCommand
    {
        /**
         * Raw message
         * @var string
         */
        private $data;

        public function __construct(Connection $connection, $data)
        {
            parent::__construct($connection);
            $this->data = $data;
        }

        
        public function execute()
        {
            if($this->connection->write(sprintf("DATA"))) {
                $response = $this->connection->read();
                if(($code = $response->getCode()) === 354) {
                    foreach(explode("\r\n", $this->data) AS $line) {
                        $this->connection->write(strpos($line, ".") === 0 ? sprintf(".%s", $line) : $line);
                    }
                } else {
                    $message = "Server doesn't accepted the DATA command";
                    throw new RuntimeException($message, $code);
                }
                
                $this->connection->write(".");
                if(($code = $this->connection->read()->getCode()) !== 250) {
                    $message = "Cannot send the message";
                    throw new RuntimeException($message, $code);
                }
            }
        }

    }