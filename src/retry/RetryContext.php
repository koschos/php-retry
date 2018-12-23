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
    public function getRetryCount(): int;

    /**
     * @return \Exception|null
     */
    public function getLastException();
}
