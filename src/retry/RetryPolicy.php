<?php

namespace Koschos\Retry;

/**
 * Interface RetryPolicy
 */
interface RetryPolicy
{
    public function canRetry(RetryContext $context): bool;

    public function open(): RetryContext;

    public function close(RetryContext $context): void;

    public function registerException(RetryContext $context, \Exception $exception): void;
}
