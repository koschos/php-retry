<?php

namespace Koschos\Retry\Backoff;

use Koschos\Retry\RetryContext;

/**
 * Interface BackOffPolicy
 */
interface BackOffPolicy
{
    /**
     * @param RetryContext $context
     *
     * @return void
     */
    public function start(RetryContext $context);

    /**
     * @param RetryContext $context
     *
     * @return void
     */
    public function backOff(RetryContext $context);
}
