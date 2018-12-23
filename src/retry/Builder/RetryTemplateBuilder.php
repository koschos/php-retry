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

    public static function getBuilder(): RetryTemplateBuilder
    {
        return new static();
    }

    public function withRetryPolicy(RetryPolicy $retryPolicy): RetryTemplateBuilder
    {
        $this->retryPolicy = $retryPolicy;

        return $this;
    }

    public function withBackOffPolicy(BackOffPolicy $backOffPolicy): RetryTemplateBuilder
    {
        $this->backOffPolicy = $backOffPolicy;

        return $this;
    }

    public function withMaxAttempts(int $maxAttempts): RetryTemplateBuilder
    {
        $this->retryPolicy = new SimpleRetryPolicy($maxAttempts);

        return $this;
    }

    public function withTimeout(int $timeout): RetryTemplateBuilder
    {
        $this->retryPolicy = new TimeoutRetryPolicy();
        $this->retryPolicy->setTimeout($timeout);

        return $this;
    }

    public function alwaysRetry(): RetryTemplateBuilder
    {
        $this->retryPolicy = new AlwaysRetryPolicy();

        return $this;
    }

    public function neverRetry(): RetryTemplateBuilder
    {
        $this->retryPolicy = new NeverRetryPolicy();

        return $this;
    }

    public function withBackOffPeriod(int $backOffPeriod): RetryTemplateBuilder
    {
        $this->backOffPolicy = new FixedBackOffPolicy();
        $this->backOffPolicy->setBackOffPeriod($backOffPeriod);

        return $this;
    }

    public function build(): RetryTemplate
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
