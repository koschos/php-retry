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
     * @param string          $message
     * @param \Exception|null $prev
     */
    public function __construct($message, \Exception $prev = null)
    {
        parent::__construct($message, 0, $prev);
    }
}
