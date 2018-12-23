<?php

namespace Koschos\Retry\Backoff;

use Koschos\Retry\RetryContext;

/**
 * Interface BackOffPolicy
 */
interface BackOffPolicy
{
    public function start(RetryContext $context): void;

    public function backOff(RetryContext $context): void;
}
