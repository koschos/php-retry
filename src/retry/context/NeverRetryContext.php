<?php

namespace Koschos\retry\policy;

use Koschos\Retry\Context\DefaultRetryContext;
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

    /**
     * @return bool
     */
    public function isFinished()
    {
        return $this->finished;
    }
}
