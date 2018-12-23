<?php

namespace Koschos\Retry\Policy;

use Koschos\Classify\ExceptionClassifier;
use Koschos\Retry\Context\DefaultRetryContext;
use Koschos\Retry\RetryContext;
use Koschos\Retry\RetryPolicy;

/**
 * Class SimpleRetryPolicy
 */
final class SimpleRetryPolicy implements RetryPolicy
{
    /**
     * @var int
     */
    private $maxAttempts = 3;

    /**
     * @var ExceptionClassifier
     */
    private $retryableClassifier;

    /**
     * SimpleRetryPolicy constructor.
     *
     * @param int   $maxAttempts
     * @param array $exceptionClassValueMap
     */
    public function __construct(int $maxAttempts, array $exceptionClassValueMap = [])
    {
        $this->maxAttempts = $maxAttempts;
        $this->retryableClassifier = new ExceptionClassifier($exceptionClassValueMap);
    }

    public function canRetry(RetryContext $context): bool
    {
        $e = $context->getLastException();

        return ($e === null || $this->retryForException($e)) && $context->getRetryCount() < $this->maxAttempts;
    }

    public function open(): RetryContext
    {
        return new DefaultRetryContext();
    }

    public function close(RetryContext $context): void
    {
    }

    public function registerException(RetryContext $context, \Exception $exception): void
    {
        if ($context instanceof DefaultRetryContext) {
            $context->registerException($exception);
        }
    }

    /**
     * @param \Exception $exception
     *
     * @return bool
     */
    private function retryForException(\Exception $exception)
    {
        return $this->retryableClassifier->classify($exception);
    }
}
