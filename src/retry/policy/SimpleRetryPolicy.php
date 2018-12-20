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
    public function __construct($maxAttempts, array $exceptionClassValueMap = [])
    {
        $this->maxAttempts = $maxAttempts;
        $this->retryableClassifier = new ExceptionClassifier($exceptionClassValueMap);
    }

    /**
     * @inheritdoc
     */
    public function canRetry(RetryContext $context)
    {
        $t = $context->getLastException();

        return ($t === null || $this->retryForException($t)) && $context->getRetryCount() < $this->maxAttempts;
    }

    /**
     * @inheritdoc
     */
    public function open()
    {
        return new DefaultRetryContext();
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
        if ($context instanceof DefaultRetryContext) {
            $context->registerException($exception);
        }
    }

    /**
     * @param \Exception $exception
     *
     * @return bool|null
     */
    private function retryForException(\Exception $exception)
    {
        return $this->retryableClassifier->classify($exception);
    }
}
