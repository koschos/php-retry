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
    /**
     * @inheritdoc
     */
    public function canRetry(RetryContext $context)
    {
        if ($context instanceof NeverRetryContext) {
            return $context->isFinished();
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function open()
    {
        return new NeverRetryContext();
    }

    /**
     * @inheritdoc
     */
    public function close(RetryContext $context)
    {
    }

    /**
     * @inheritdoc
     */
    public function registerException(RetryContext $context, \Exception $exception)
    {
        if ($context instanceof NeverRetryContext) {
            $context->registerException($exception);
            $context->setFinished();
        }
    }

}
