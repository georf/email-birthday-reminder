<?php

    /**
     * @package utils.net.SMTP.Message
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Message\HeaderEnconder.php
     */

    namespace utils\net\SMTP\Message;
    use utils\net\SMTP\Message\Encoder;
    use utils\net\SMTP\Message\Encoder\QuotedPrintable;
    use utils\net\SMTP\Message\Encoder\Base64;

    abstract class HeaderEncoder
    {

        /**
         * Encodes an header value using specified encoder or QuotedPrintable as default.
         * @param string $value the header value to be encoded
         * @param string $encoding the charset encoding
         * @param Encoder $encoder an valid encoder to encode
         * @return string
         */
        public static function encode($value, $encoding, Encoder $encoder = NULL)
        {
            if ($encoding === "ASCII")
                return $value;

            $encoder = is_null($encoder) ? new QuotedPrintable() : $encoder;
            return $encoder->encodeHeader($value, $encoding);
        }

    }