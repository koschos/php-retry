<?php

namespace Koschos\Retry\Context;

use Koschos\Retry\RetryContext;

/**
 * Class NeverRetryContext
 */
final class NeverRetryContext extends DefaultRetryContext implements RetryContext
{
    /**
     * @var bool
     */
    private $finished = false;

    /**
     * Set that context is stopped.
     */
    public function setFinished()
    {
        $this->finished = true;
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }
}
