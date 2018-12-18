<?php

namespace Koschos\Retry;

use Koschos\retry\backoff\BackOffInterruptedException;
use Koschos\Retry\Backoff\BackOffPolicy;
use Koschos\Retry\Backoff\NoBackOffPolicy;
use Koschos\Retry\Policy\SimpleRetryPolicy;

/**
 * Class RetryTemplate
 */
class RetryTemplate implements RetryOperations
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
     * RetryTemplate constructor.
     */
    public function __construct()
    {
        $this->retryPolicy = new SimpleRetryPolicy(3, [\Exception::class, true]);
        $this->backOffPolicy = new NoBackOffPolicy();
    }

    /**
     * @param RetryPolicy $retryPolicy
     */
    public function setRetryPolicy(RetryPolicy $retryPolicy)
    {
        $this->retryPolicy = $retryPolicy;
    }

    /**
     * @param BackOffPolicy $backOffPolicy
     */
    public function setBackOffPolicy(BackOffPolicy $backOffPolicy)
    {
        $this->backOffPolicy = $backOffPolicy;
    }

    /**
     * @inheritdoc
     */
    public function execute(RetryCallback $retryCallback)
    {
        return $this->doExecute($retryCallback, null);
    }

    /**
     * @inheritdoc
     */
    public function executeWithRecovery(RetryCallback $retryCallback, RecoveryCallback $recoveryCallback)
    {
        return $this->doExecute($retryCallback, $recoveryCallback);
    }

    /**
     * @param RetryCallback         $retryCallback
     * @param RecoveryCallback|null $recoveryCallback
     *
     * @return mixed
     */
    protected function doExecute(RetryCallback $retryCallback, RecoveryCallback $recoveryCallback = null)
    {
        $context = $this->retryPolicy->open();

        try {
            $this->backOffPolicy->start($context);

            while ($this->canRetry($context)) {
                try {
                    return $retryCallback->doWithRetry($context);
                } catch (\Exception $e) {
                    $this->registerException($context, $e);

                    if ($this->canRetry($context)) {
                        $this->backOff($context);
                    } else {
                        throw $e;
                    }
                }
            }

            return $this->handleRetryExhausted($context, $recoveryCallback);
        } catch (\Exception $e) {
            throw $this->wrapException($e);
        } finally {
            $this->close($context);
        }
    }

    /**
     * @param RetryContext $context
     *
     * @return bool
     */
    protected function canRetry(RetryContext $context)
    {
        return $this->retryPolicy->canRetry($context);
    }

    /**
     * @param RetryContext          $context
     * @param RecoveryCallback|null $recoveryCallback
     *
     * @return mixed|null
     *
     * @throws \Exception
     */
    protected function handleRetryExhausted(RetryContext $context, RecoveryCallback $recoveryCallback = null)
    {
        if ($recoveryCallback !== null) {
            return $recoveryCallback->recover($context);
        }

        throw $this->wrapException($context->getLastException());
    }

    /**
     * @param RetryContext $context
     */
    protected function close(RetryContext $context)
    {
        $this->retryPolicy->close($context);
    }

    /**
     * @param \Exception $exception
     *
     * @return RetryException
     */
    protected function wrapException(\Exception $exception)
    {
        return new RetryException('Exception in batch process', $exception);
    }

    /**
     * @param RetryContext $context
     * @param \Exception   $exception
     *
     * @throws TerminatedRetryException
     */
    protected function registerException(RetryContext $context, \Exception $exception)
    {
        try {
            $this->retryPolicy->registerException($context, $exception);
        } catch (\Exception $ex) {
            throw new TerminatedRetryException('Could not register Exception', $ex);
        }
    }

    /**
     * @param RetryContext $context
     *
     * @throws BackOffInterruptedException
     */
    protected function backOff(RetryContext $context)
    {
        try {
            $this->backOffPolicy->backOff($context);
        } catch (\Exception $e) {
            throw new BackOffInterruptedException('Abort retry because interrupted', $e);
        }
    }
}
