<?php

namespace Koschos\Retry;

/**
 * Interface RetryPolicy
 */
interface RetryPolicy
{
    /**
     * @param RetryContext $context
     *
     * @return bool
     */
    public function canRetry(RetryContext $context);

    /**
     * @return RetryContext
     */
    public function open();

    /**
     * @param RetryContext $context
     *
     * @return void
     */
    public function close(RetryContext $context);

    /**
     * @param RetryContext $context
     * @param \Exception   $exception
     *
     * @return void
     */
    public function registerException(RetryContext $context, \Exception $exception);
}
