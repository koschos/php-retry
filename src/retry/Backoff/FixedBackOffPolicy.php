<?php

namespace Koschos\Retry\Backoff;

use Koschos\Retry\RetryContext;

/**
 * Class FixedBackOffPolicy
 */
final class FixedBackOffPolicy implements BackOffPolicy
{
    /**
     * Period in milliseconds between retries.
     *
     * @var int
     */
    private $backOffPeriod = 1000;

    public function start(RetryContext $context): void
    {
    }

    public function backOff(RetryContext $context): void
    {
        usleep($this->backOffPeriod * 1000);
    }

    /**
     * @return int
     */
    public function getBackOffPeriod(): int
    {
        return $this->backOffPeriod;
    }

    /**
     * @param int $backOffPeriod
     */
    public function setBackOffPeriod(int $backOffPeriod)
    {
        $this->backOffPeriod = $backOffPeriod > 0 ? $backOffPeriod : 1;
    }
}
