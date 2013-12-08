<?php

    /**
     * @package utils.net.SMTP.Client.Command
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\Command\InputCommand.php
     */
    namespace utils\net\SMTP\Client\Command;
    use utils\net\SMTP\Client\AbstractCommand;
    use utils\net\SMTP\Client\Connection;

    class InputCommand extends AbstractCommand
    {

        /**
         * The command to be performed
         * @var string
         */
        private $command;

        /**
         * - Constructor
         * @param Connection $connection the connection where command will be performed
         * @param string $command the command to be performed
         * @return InputCommand
         */
        public function __construct(Connection $connection, $command)
        {
            parent::__construct($connection);
            $this->command = $command;
        }

        /**
         * Performs a command on the server
         * @return void
         */
        public function execute()
        {
            $this->connection->write($this->command);
        }

    }