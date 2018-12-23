<?php

namespace Koschos\Retry\Policy;

use Koschos\Retry\RetryContext;

/**
 * Class AlwaysRetryPolicy
 */
final class AlwaysRetryPolicy extends NeverRetryPolicy
{
    public function canRetry(RetryContext $context): bool
    {
        return true;
    }
}
