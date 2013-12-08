<?php

    /**
     * @package utils.net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client.php
     */
    namespace utils\net\SMTP;
    use utils\net\SMTP\Client\Connection;
    use utils\net\SMTP\Client\Authentication;
    use utils\net\SMTP\Message;
    use utils\net\SMTP\Client\CommandInvoker;
    use utils\net\SMTP\Client\Command\RCPTCommand;
    use utils\net\SMTP\Client\Command\MAILCommand;
    use utils\net\SMTP\Client\Command\DATACommand;
    use utils\net\SMTP\Message\Composer;
    use utils\net\SMTP\Message\Address;
    use \RuntimeException;

    class Client
    {

        /**
         * The connection used to talk with the server.
         * @var Connection 
         */
        private $connection = NULL;

        /**
         * - Constructor
         * @param Connection $connection the connection used to talk with server
         * @return Client
         */
        public function __construct(Connection $connection)
        {
            $this->connection = $connection;
        }

        /**
         * Authenticates a user with provided authentication mechanism.
         * @param Authentication $authentication the authentication mechanism
         * @return boolean
         */
        public function authenticate(Authentication $authentication)
        {
            return $this->connection->authenticate($authentication);
        }

        /**
         * Closes an opened connection with the SMTP server.
         * @return void
         */
        public function close()
        {
            $this->connection->close();
        }

        /**
         * Retrieves the latest exchanged message with the server.
         * @see AbstractConnection::getLatestMessage()
         * @return Message
         */
        public function getLatestMessage()
        {
            return $this->connection->getLatestMessage();
        }

        /**
         * Retrieves all exchanged messages with the server.
         * @see AbstractConnection::getExchangedMessages()
         * @return array[Message]
         */
        public function getExchangedMessages()
        {
            return $this->connection->getExchangedMessages();
        }
        
        /**
         * Sends a mail message
         * @param Message $message the message to send
         * @throws RuntimeException if the sender is not specified
         * @return boolean
         */
        public function send(Message $message)
        {
            $connection = $this->connection;
            $commandInvoker = new CommandInvoker();
            
            if(($from = $message->getFrom()) instanceof Address) {
                $commandInvoker->invoke(new MAILCommand($connection, $from->getEmail()));
                
                foreach($this->getRecipients($message) AS $recipient) {
                    $commandInvoker->invoke(new RCPTCommand($connection, $recipient));
                }

                $composer = new Composer();
                $commandInvoker->invoke(new DATACommand($connection, $composer->compose($message)));
                return true;
            } else {
                $message = "Couldn't send the message without specifying its sender";
                throw new RuntimeException($message);
            }
        }
        
        /**
         * Prepares recipients addresses to send the message
         * @param Message $message the message with their recipients
         * @return array[string]
         */
        private function getRecipients(Message $message)
        {
            $addresses = array_merge(
                $message->getTo(), // Recipients
                $message->getCc(), // Recipients that receive a copy
                $message->getBcc() // Recipients that receive an blind copy
            );
            
            $recipients = array();
            foreach($addresses AS $address) {
                $recipients[] = $address->getEmail();
            }
            
            return array_unique($recipients);
        }
                
    }
