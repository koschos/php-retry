<?php

namespace retry\Policy;

use Koschos\Retry\Backoff\BackOffPolicy;
use Koschos\Retry\Builder\RetryTemplateBuilder;
use Koschos\Retry\RecoveryCallback;
use Koschos\Retry\RetryCallback;
use Koschos\Retry\RetryException;
use Koschos\Retry\Tests\AbstractRetryTestCase;

/**
 * Class NeverRetryPolicyTest
 */
class NeverRetryPolicyTest extends AbstractRetryTestCase
{
    /**
     * @test
     */
    public function shouldRetryOnlyOnce()
    {
        $retryCallback = $this->getMockBuilder(RetryCallback::class)->getMock();
        $retryCallback->expects($this->once())->method('doWithRetry')->willThrowException(new \Exception());

        $backOffPolicy = $this->createBackOffPolicy(0);

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->neverRetry()
            ->withBackOffPolicy($backOffPolicy)
            ->build();

        $this->expectException(RetryException::class);

        $retryTemplate->execute($retryCallback);
    }

    /**
     * @test
     */
    public function shouldRetryOnlyOnceAndCallRecovery()
    {
        $retryCallback = $this->getMockBuilder(RetryCallback::class)->getMock();
        $retryCallback->expects($this->once())->method('doWithRetry')->willThrowException(new \Exception());

        $recoveryCallback = $this->getMockBuilder(RecoveryCallback::class)->getMock();
        $recoveryCallback->expects($this->once())->method('recover')->willReturn('recovered');

        $backOffPolicy = $this->createBackOffPolicy(0);

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->neverRetry()
            ->withBackOffPolicy($backOffPolicy)
            ->build();

        $this->assertEquals('recovered', $retryTemplate->executeWithRecovery($retryCallback, $recoveryCallback));
    }
}
