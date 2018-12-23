<?php

namespace Koschos\retry;

/**
 * Class IllegalExhaustedStateException
 */
class IllegalExhaustedStateException extends RetryException
{
    /**
     * IllegalExhaustedStateException constructor.
     *
     * @param RetryContext $context
     */
    public function __construct(RetryContext $context)
    {
        parent::__construct(sprintf(
            'Illegal exhausted state: context %s, count retries %d',
            get_class($context),
            $context->getRetryCount()
        ));
    }
}