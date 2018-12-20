<?php

namespace Koschos\Retry;

/**
 * Class RetryException
 */
class RetryException extends \RuntimeException
{
    /**
     * RetryException constructor.
     *
     * @param string     $message
     * @param \Exception $prev
     */
    public function __construct($message, \Exception $prev)
    {
        parent::__construct($message, 0, $prev);
    }
}
