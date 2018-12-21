<?php

namespace Koschos\Retry\Builder;

use Koschos\Retry\Backoff\BackOffPolicy;
use Koschos\Retry\Backoff\FixedBackOffPolicy;
use Koschos\Retry\Policy\AlwaysRetryPolicy;
use Koschos\Retry\Policy\NeverRetryPolicy;
use Koschos\Retry\Policy\SimpleRetryPolicy;
use Koschos\Retry\Policy\TimeoutRetryPolicy;
use Koschos\Retry\RetryPolicy;
use Koschos\Retry\RetryTemplate;

/**
 * Class RetryTemplateBuilder
 */
class RetryTemplateBuilder
{
    /**
     * @var RetryPolicy
     */
    private $retryPolicy;

    /**
     * @var BackOffPolicy
     */
    private $backOffPolicy;

    /**
     * @return RetryTemplateBuilder
     */
    public static function getBuilder()
    {
        return new static();
    }

    /**
     * @param RetryPolicy $retryPolicy
     *
     * @return RetryTemplateBuilder
     */
    public function withRetryPolicy($retryPolicy)
    {
        $this->retryPolicy = $retryPolicy;

        return $this;
    }

    /**
     * @param BackOffPolicy $backOffPolicy
     *
     * @return RetryTemplateBuilder
     */
    public function withBackOffPolicy($backOffPolicy)
    {
        $this->backOffPolicy = $backOffPolicy;

        return $this;
    }

    /**
     * @param $maxAttempts
     *
     * @return RetryTemplateBuilder
     */
    public function withMaxAttempts($maxAttempts)
    {
        $this->retryPolicy = new SimpleRetryPolicy($maxAttempts);

        return $this;
    }

    /**
     * @param $timeout
     *
     * @return RetryTemplateBuilder
     */
    public function withTimeout($timeout)
    {
        $this->retryPolicy = new TimeoutRetryPolicy();
        $this->retryPolicy->setTimeout($timeout);

        return $this;
    }

    /**
     * @return RetryTemplateBuilder
     */
    public function alwaysRetry()
    {
        $this->retryPolicy = new AlwaysRetryPolicy();

        return $this;
    }

    /**
     * @return RetryTemplateBuilder
     */
    public function neverRetry()
    {
        $this->retryPolicy = new NeverRetryPolicy();

        return $this;
    }

    /**
     * @param $backOffPeriod
     *
     * @return RetryTemplateBuilder
     */
    public function withBackOffPeriod($backOffPeriod)
    {
        $this->backOffPolicy = new FixedBackOffPolicy();
        $this->backOffPolicy->setBackOffPeriod($backOffPeriod);

        return $this;
    }

    /**
     * @return RetryTemplate
     */
    public function build()
    {
        $retryTemplate = new RetryTemplate();

        if ($this->retryPolicy !== null) {
            $retryTemplate->setRetryPolicy($this->retryPolicy);
        }

        if ($this->backOffPolicy !== null) {
            $retryTemplate->setBackOffPolicy($this->backOffPolicy);
        }

        return $retryTemplate;
    }
}
