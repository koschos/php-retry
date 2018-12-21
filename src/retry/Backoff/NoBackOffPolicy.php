<?php

namespace Koschos\Retry\Backoff;

use Koschos\Retry\RetryContext;

/**
 * Class NoBackOffPolicy
 */
final class NoBackOffPolicy implements BackOffPolicy
{
    /**
     * @inheritdoc
     */
    public function start(RetryContext $context)
    {
    }

    /**
     * @inheritdoc
     */
    public function backOff(RetryContext $context)
    {
    }
}
