<?php

namespace Koschos\Classify\Tests\ExceptionType;

use Koschos\Classify\ExceptionType\ClassComparator;

/**
 * Class ClassComparatorTest
 */
class ClassComparatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     *
     * @param $class1
     * @param $class2
     * @param $result
     */
    public function testCompare($class1, $class2, $result)
    {
        $this->assertEquals($result, (new ClassComparator())->compare($class1, $class2));
    }

    /**
     * @return array
     */
    public function provider()
    {
        return [
            [\Exception::class, \InvalidArgumentException::class, 1],
            [\InvalidArgumentException::class, \Exception::class, -1],
            [\DomainException::class, \Exception::class, -1],
            [\BadMethodCallException::class, \Exception::class, -1],
            [\Exception::class, \BadFunctionCallException::class, 1],

            [\Exception::class, \Exception::class, 0],
            [\SplObjectStorage::class, \InvalidArgumentException::class, 0],
            [\ErrorException::class, \InvalidArgumentException::class, 0],
        ];
    }
}
