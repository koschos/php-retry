<?php

namespace Koschos\Retry\Tests;

use Koschos\Retry\Backoff\BackOffInterruptedException;
use Koschos\Retry\Backoff\BackOffPolicy;
use Koschos\Retry\Builder\RetryTemplateBuilder;
use Koschos\Retry\Context\DefaultRetryContext;
use Koschos\retry\IllegalExhaustedStateException;
use Koschos\Retry\RecoveryCallback;
use Koschos\Retry\RetryCallback;
use Koschos\Retry\RetryContext;
use Koschos\Retry\RetryException;
use Koschos\Retry\RetryPolicy;
use Koschos\Retry\RetryTemplate;
use Koschos\Retry\TerminatedRetryException;

/**
 * Class RetryTemplateTest
 */
class RetryTemplateTest extends AbstractRetryTestCase
{
    /**
     * @test
     */
    public function shouldSupportRuntimeCallback()
    {
        $template = RetryTemplateBuilder::getBuilder()
            ->neverRetry()
            ->build();

        $this->expectException(RetryException::class);

        $template->execute(new class implements RetryCallback {
            public function doWithRetry(RetryContext $context) {
                throw new \Exception('Never give up!');
            }
        });
    }

    /**
     * @test
     */
    public function happyPass()
    {
        $retryCallback = $this->createRetryCallback(1);

        $context = $this->getMockBuilder(RetryContext::class)->getMock();

        $retryPolicy = $this->getMockBuilder(RetryPolicy::class)->getMock();
        $retryPolicy->expects($this->once())->method('open')->willReturn($context);
        $retryPolicy->expects($this->any())->method('canRetry')->with($context)->willReturn(true);
        $retryPolicy->expects($this->once())->method('registerException')->with($context, new \Exception(0));
        $retryPolicy->expects($this->once())->method('close')->with($context);

        $backOffPolicy = $this->getMockBuilder(BackOffPolicy::class)->getMock();
        $backOffPolicy->expects($this->once())->method('start')->with($context);
        $backOffPolicy->expects($this->once())->method('backOff')->with($context);

        $retryTemplate = new RetryTemplate();
        $retryTemplate->setRetryPolicy($retryPolicy);
        $retryTemplate->setBackOffPolicy($backOffPolicy);

        $this->assertEquals(1, $retryTemplate->execute($retryCallback));
    }

    /**
     * @test
     */
    public function successOnFirstTry()
    {
        $retryCallback = $this->createRetryCallback(0);
        $backOffPolicy = $this->createBackOffPolicy(0);

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->alwaysRetry()
            ->withBackOffPolicy($backOffPolicy)
            ->build();

        $this->assertEquals(0, $retryTemplate->execute($retryCallback));
    }

    /**
     * @test
     */
    public function successAfterFailedRetries()
    {
        $retryCallback = $this->createRetryCallback(2);
        $backOffPolicy = $this->createBackOffPolicy(2);

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->alwaysRetry()
            ->withBackOffPolicy($backOffPolicy)
            ->build();

        $this->assertEquals(2, $retryTemplate->execute($retryCallback));
    }

    /**
     * @test
     */
    public function exhausted()
    {
        $retryCallback = $this->createAlwaysFailingRetryCallback();

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->neverRetry()
            ->build();

        $this->expectException(RetryException::class);

        $retryTemplate->execute($retryCallback);
    }

    /**
     * @test
     */
    public function recoverWhenExhausted()
    {
        $retryCallback = $this->createAlwaysFailingRetryCallback();

        $recoveryCallback = $this->getMockBuilder(RecoveryCallback::class)->getMock();
        $recoveryCallback->expects($this->once())->method('recover')->willReturn('recovered');

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->neverRetry()
            ->build();

        $this->assertEquals('recovered', $retryTemplate->executeWithRecovery($retryCallback, $recoveryCallback));
    }

    /**
     * @test
     */
    public function backOffFails()
    {
        $retryCallback = $this->createAlwaysFailingRetryCallback();

        $backOffPolicy = $this->getMockBuilder(BackOffPolicy::class)->getMock();
        $backOffPolicy->expects($this->once())->method('start');
        $backOffPolicy->expects($this->once())->method('backOff')->willThrowException(new \LogicException());

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->alwaysRetry()
            ->withBackOffPolicy($backOffPolicy)
            ->build();

        $this->expectException(BackOffInterruptedException::class);

        $retryTemplate->execute($retryCallback);
    }

    /**
     * @test
     */
    public function illegalExhaustedState()
    {
        $retryCallback = $this->createAlwaysFailingRetryCallback();

        $retryPolicy = $this->getMockBuilder(RetryPolicy::class)->getMock();
        $retryPolicy->expects($this->once())->method('open')->willReturn(new DefaultRetryContext());
        $retryPolicy->expects($this->once())->method('canRetry')->willReturn(false);

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->withRetryPolicy($retryPolicy)
            ->build();

        $this->expectException(IllegalExhaustedStateException::class);

        $retryTemplate->execute($retryCallback);
    }

    /**
     * @test
     */
    public function canNotRegisterFailedTry()
    {
        $retryCallback = $this->createAlwaysFailingRetryCallback();

        $context = new DefaultRetryContext();

        $retryPolicy = $this->getMockBuilder(RetryPolicy::class)->getMock();
        $retryPolicy->expects($this->once())->method('open')->willReturn($context);
        $retryPolicy->expects($this->once())->method('canRetry')->with($context)->willReturn(true);

        $retryPolicy->expects($this->once())->method('registerException')
            ->with($context, new \Exception())->willThrowException(new \LogicException());

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->withRetryPolicy($retryPolicy)
            ->build();

        $this->expectException(TerminatedRetryException::class);

        $retryTemplate->execute($retryCallback);
    }
}
