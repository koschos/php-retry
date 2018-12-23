<?php

namespace Koschos\Retry\Tests\Policy;

use Koschos\Retry\Builder\RetryTemplateBuilder;
use Koschos\Retry\RetryCallback;
use Koschos\Retry\RetryException;
use Koschos\Retry\Tests\AbstractRetryTestCase;

/**
 * Class SimpleRetryPolicyTest
 */
class SimpleRetryPolicyTest extends AbstractRetryTestCase
{
    /**
     * @test
     */
    public function maxAttemptsNotExceeded()
    {
        $retryCallback = $this->createRetryCallback(1);

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->withMaxAttempts(2)
            ->build();

        $this->assertEquals(1, $retryTemplate->execute($retryCallback));
    }

    /**
     * @test
     */
    public function maxAttemptsExceeded()
    {
        $retryCallback = $this->getMockBuilder(RetryCallback::class)->getMock();
        $retryCallback->expects($this->any())->method('doWithRetry')->willThrowException(new \Exception());

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->withMaxAttempts(2)
            ->build();

        $this->expectException(RetryException::class);

        $retryTemplate->execute($retryCallback);
    }
}
