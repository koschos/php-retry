<?php

namespace Koschos\Retry\Tests\Policy;

use Koschos\Retry\Builder\RetryTemplateBuilder;
use Koschos\Retry\RetryCallback;
use Koschos\Retry\RetryException;
use Koschos\Retry\Tests\AbstractRetryTestCase;
use Koschos\Retry\Context\Util\TimeUtil;

/**
 * Class TimeoutRetryPolicyTest
 */
class TimeoutRetryPolicyTest extends AbstractRetryTestCase
{
    /**
     * @test
     */
    public function failedAfterTimeout()
    {
        $retryCallback = $this->getMockBuilder(RetryCallback::class)->getMock();

        $retryCallback->expects($this->any())->method('doWithRetry')->willReturnCallback(function () {
            // Emulate latency to save memory
            usleep(20);
            throw new \Exception();
        });

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->withTimeout(100)
            ->build();

        $start = TimeUtil::milliseconds();
        $thrown = null;

        try {
            $retryTemplate->execute($retryCallback);
        } catch (RetryException $e) {
            $thrown = $e;
        }

        $this->assertInstanceOf(RetryException::class, $thrown);
        $this->assertGreaterThanOrEqual(100, TimeUtil::milliseconds() - $start);
    }

    /**
     * @test
     */
    public function successWithinTimeout()
    {
        $retryCallback = $this->getMockBuilder(RetryCallback::class)->getMock();

        $retryCallback->expects($this->at(0))->method('doWithRetry')->willReturnCallback(function () {
            // Emulate latency to save memory
            usleep(10);
            throw new \Exception();
        });

        $retryCallback->expects($this->at(1))->method('doWithRetry')->willReturnCallback(function () {
            // Emulate latency to save memory
            usleep(15);
            throw new \Exception();
        });

        $retryCallback->expects($this->at(2))->method('doWithRetry')->willReturn('html');

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->withTimeout(100)
            ->build();

        $this->assertEquals('html', $retryTemplate->execute($retryCallback));
    }
}
