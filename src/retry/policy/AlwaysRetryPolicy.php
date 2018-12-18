<?php

namespace Koschos\retry\policy;

use Koschos\Retry\RetryContext;

/**
 * Class AlwaysRetryPolicy
 */
class AlwaysRetryPolicy extends NeverRetryPolicy
{
    /**
     * @inheritdoc
     */
    public function canRetry(RetryContext $context)
    {
        return true;
    }
}
