<?php

namespace Koschos\Classify\Tests\ExceptionType;

use Koschos\Classify\ExceptionType\ExceptionValueSortedSet;

/**
 * Class ExceptionValueSortedSetTest
 */
class ExceptionValueSortedSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldFindMoreSpecificExceptionsFirst()
    {
        $set = new ExceptionValueSortedSet();
        $set->put(\LogicException::class, false);
        $set->put(\BadFunctionCallException::class, true);
        $set->put(\Exception::class, true);
        $set->put(\DomainException::class, false);

        $this->assertTrue($set->get(new \BadMethodCallException()), 'inherited from \BadFunctionCallException');
        $this->assertFalse($set->get(new \InvalidArgumentException()), 'inherited from \LogicException');
        $this->assertFalse($set->get(new \DomainException()), 'own value');

        $this->assertTrue($set->get(new \ErrorException()), 'inherited from \Exception');
        $this->assertTrue($set->get(new \OverflowException()), 'inherited from \Exception through \RuntimeException');
    }
}
