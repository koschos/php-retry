<?php

namespace Koschos\Retry\Policy;

use Koschos\Retry\Context\NeverRetryContext;
use Koschos\Retry\RetryContext;
use Koschos\Retry\RetryPolicy;

/**
 * Class NeverRetryPolicy
 */
class NeverRetryPolicy implements RetryPolicy
{
    public function canRetry(RetryContext $context): bool
    {
        if ($context instanceof NeverRetryContext) {
            return !$context->isFinished();
        }

        return false;
    }

    public function open(): RetryContext
    {
        return new NeverRetryContext();
    }

    public function close(RetryContext $context): void
    {
    }

    public function registerException(RetryContext $context, \Exception $exception): void
    {
        if ($context instanceof NeverRetryContext) {
            $context->registerException($exception);
            $context->setFinished();
        }
    }
}
