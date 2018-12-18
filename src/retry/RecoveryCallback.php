<?php

namespace Koschos\Retry;

/**
 * Interface RecoveryCallback
 */
interface RecoveryCallback
{
    /**
     * @param RetryContext $retryContext
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function recover(RetryContext $retryContext);
}