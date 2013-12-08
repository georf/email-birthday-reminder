<?php

    /**
     * @package utils.net.SMTP.Message.Header
     * @filesource \utils\net\SMTP\Message\Header\Subject.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace utils\net\SMTP\Message\Header;
    use utils\net\SMTP\Message\HeaderWrapper;
    use utils\net\SMTP\Message\AbstractHeader;
    use utils\net\SMTP\Message\Header\Type\Unstructured;

    class Subject extends AbstractHeader implements Unstructured
    {
        /**
         * Constructor, defines the mail message subject
         * @param string $subject the message subject
         * @return Subject
         */
        public function __construct($subject = null, $encoding = NULL)
        {
            parent::__construct("Subject", $subject);
            $this->setEncoding($encoding);
        }

        /**
         * Creates and returns the string representation of header
         * @link http://tools.ietf.org/html/rfc2822#section-2.2.3
         * @return string
         */
        public function __toString()
        {
            $value = HeaderWrapper::wrap($this);
            return sprintf("%s: %s", $this->getName(), $value);
        }

    }