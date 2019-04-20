<?php

namespace Application\Validator\Common;

use Zend\Crypt\Password\Bcrypt;
use Zend\Validator\AbstractValidator;

class PasswordVerify extends AbstractValidator {

    /**
     * Error codes
     * @const string
     */
    const NOT_SAME = 'notSame';

    /**
     * Error messages
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_SAME => "Your password does not match",
    ];
    protected $hash;

    public function __construct($options = null) {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (!array_key_exists('hash', $options)) {
            throw new Exception\InvalidArgumentException("Missing option 'hash'");
        }

        $this->setHash($options['hash']);

        parent::__construct($options);
    }

    public function getHash() {
        return $this->hash;
    }

    public function setHash(string $hash) {
        $this->hash = $hash;
        return $this;
    }

    public function isValid($value) {
        $bcrypt = new Bcrypt();
        if (!$bcrypt->verify($value, $this->hash)) {
            $this->error(self::NOT_SAME);
            return FALSE;
        }

        return TRUE;
    }

}
