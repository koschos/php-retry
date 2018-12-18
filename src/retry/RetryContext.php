<?php

namespace Koschos\Retry;

/**
 * Interface RetryContext
 */
interface RetryContext
{
    /**
     * @return int
     */
    public function getRetryCount();

    /**
     * @return \Exception
     */
    public function getLastException();
}
