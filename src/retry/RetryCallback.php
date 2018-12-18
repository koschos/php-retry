<?php

namespace Koschos\Retry;

/**
 * Interface RetryCallback
 */
interface RetryCallback
{
    /**
     * @param RetryContext $retryContext
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function doWithRetry(RetryContext $retryContext);
}
