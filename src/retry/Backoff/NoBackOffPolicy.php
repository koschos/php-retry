<?php

namespace Koschos\Retry\Backoff;

use Koschos\Retry\RetryContext;

/**
 * Class NoBackOffPolicy
 */
final class NoBackOffPolicy implements BackOffPolicy
{
    public function start(RetryContext $context): void
    {
    }

    public function backOff(RetryContext $context): void
    {
    }
}
