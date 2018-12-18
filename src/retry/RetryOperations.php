<?php

namespace Koschos\Retry;

/**
 * Interface RetryOperations
 */
interface RetryOperations
{
    /**
     * @param RetryCallback $retryCallback
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function execute(RetryCallback $retryCallback);

    /**
     * @param RetryCallback $retryCallback
     * @param RecoveryCallback $recoveryCallback
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function executeWithRecovery(RetryCallback $retryCallback, RecoveryCallback $recoveryCallback);
}
