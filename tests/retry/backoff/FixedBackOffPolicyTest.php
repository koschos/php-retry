<?php

namespace Koschos\Retry\Tests\Policy;

use Koschos\Retry\Builder\RetryTemplateBuilder;
use Koschos\Retry\Context\Util\TimeUtil;
use Koschos\Retry\Tests\AbstractRetryTestCase;

/**
 * Class FixedBackOffPolicyTest
 */
class FixedBackOffPolicyTest extends AbstractRetryTestCase
{
    /**
     * @test
     */
    public function waitBetweenRetries()
    {
        $retryCallback = $this->createRetryCallback(2);

        $retryTemplate = RetryTemplateBuilder::getBuilder()
            ->alwaysRetry()
            ->withBackOffPeriod(50)
            ->build();

        $start = TimeUtil::milliseconds();

        $result = $retryTemplate->execute($retryCallback);

        $this->assertEquals(2, $result);
        $this->assertGreaterThanOrEqual(100, TimeUtil::milliseconds() - $start);
    }
}
