<?php

    /**
     * @package utils.net.SMTP.Message
     * @filesource \utils\net\SMTP\Message\Composer.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace utils\net\SMTP\Message;
    use utils\net\SMTP\Message;

    class Composer
    {

        /**
         * Composes headers from the headers set
         * @param HeaderSet $headerSet the headers to compose
         * @return string
         */
        private function composeHeaders(HeaderSet $headerSet)
        {
            $headers = array();
            foreach ($headerSet AS $header) {
                $headerString = (string) $header;
                if (!empty($headerString)) {
                    $headers[] = trim($header);
                }
            }

            return implode("\r\n", $headers);
        }

        /**
         * Composes an given message
         * @param Message $message the message to be composed
         * @return string
         */
        public function compose(Message $message)
        {
            $headers = $this->composeHeaders($message->getHeaderSet());
            return sprintf("%s\r\n\r\n%s", $headers, $message->getBody());
        }

    }