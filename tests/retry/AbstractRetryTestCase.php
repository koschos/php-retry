<?php

namespace Koschos\Retry\Tests;

use Koschos\Retry\Backoff\BackOffPolicy;
use Koschos\Retry\RetryCallback;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractRetryTestCase
 */
abstract class AbstractRetryTestCase extends TestCase
{
    /**
     * @param int $countFails
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|RetryCallback
     */
    protected function createRetryCallback($countFails)
    {
        $retryCallback = $this->getMockBuilder(RetryCallback::class)->getMock();

        for ($i = 0; $i < $countFails; $i++) {
            $retryCallback->expects($this->at($i))->method('doWithRetry')->willThrowException(new \Exception($i));
        }

        $retryCallback->expects($this->at($i))->method('doWithRetry')->willReturn($i);

        return $retryCallback;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|RetryCallback
     */
    protected function createAlwaysFailingRetryCallback()
    {
        $retryCallback = $this->getMockBuilder(RetryCallback::class)->getMock();
        $retryCallback->expects($this->any())->method('doWithRetry')->willThrowException(new \Exception());

        return $retryCallback;
    }

    /**
     * @param int $countBackOffs
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|BackOffPolicy
     */
    protected function createBackOffPolicy($countBackOffs)
    {
        $backOffPolicy = $this->getMockBuilder(BackOffPolicy::class)->getMock();
        $backOffPolicy->expects($this->once())->method('start');
        $backOffPolicy->expects($this->exactly($countBackOffs))->method('backOff');

        return $backOffPolicy;
    }
}