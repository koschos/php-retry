<?php

namespace Koschos\Retry\Policy;

use Koschos\Retry\RetryContext;

/**
 * Class AlwaysRetryPolicy
 */
final class AlwaysRetryPolicy extends NeverRetryPolicy
{
    /**
     * @inheritdoc
     */
    public function canRetry(RetryContext $context)
    {
        return true;
    }
}
