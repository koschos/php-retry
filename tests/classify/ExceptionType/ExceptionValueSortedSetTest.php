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

        $this->assertTrue($set->put(\LogicException::class, false));
        $this->assertTrue($set->put(\BadFunctionCallException::class, true));
        $this->assertTrue($set->put(\Exception::class, true));
        $this->assertTrue($set->put(\DomainException::class, false));
        $this->assertFalse($set->put(\DomainException::class, true), 'can\'t replace value');

        $this->assertTrue($set->get(new \BadMethodCallException()), 'inherited from \BadFunctionCallException');
        $this->assertFalse($set->get(new \InvalidArgumentException()), 'inherited from \LogicException');
        $this->assertFalse($set->get(new \DomainException()), 'own value');

        $this->assertTrue($set->get(new \ErrorException()), 'inherited from \Exception');
        $this->assertTrue($set->get(new \OverflowException()), 'inherited from \Exception through \RuntimeException');
    }
}
