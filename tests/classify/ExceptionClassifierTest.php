<?php

namespace Koschos\Classify\Tests;

use Koschos\Classify\ExceptionClassifier;
use PHPUnit\Framework\TestCase;

/**
 * Class ExceptionClassifierTest
 */
class ExceptionClassifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCatchAllExceptionsByDefault()
    {
        $classifier = new ExceptionClassifier();
        $this->assertTrue($classifier->classify(new \BadMethodCallException()));
    }

    /**
     * @test
     */
    public function shouldReturnDefaultValueIfNotSpecified()
    {
        $classifier = new ExceptionClassifier([\InvalidArgumentException::class => false]);
        $this->assertTrue($classifier->classify(new \Exception()));

        $classifier = new ExceptionClassifier([\LogicException::class => true], false);
        $this->assertFalse($classifier->classify(new \Exception()));
    }

    /**
     * @test
     */
    public function shouldUseSortedSetForMap()
    {
        $classifier = new ExceptionClassifier([
            \Exception::class => true,
            \InvalidArgumentException::class => true,
            \LogicException::class => false,
        ]);

        $this->assertTrue($classifier->classify(new \InvalidArgumentException()), 'own value');
        $this->assertFalse($classifier->classify(new \DomainException()), 'inherited from \LogicException');
        $this->assertTrue($classifier->classify(new \ErrorException()), 'inherited from \Exception');
    }

    /**
     * @dataProvider wrongClassProvider
     *
     * @test
     *
     * @param array $map
     */
    public function shouldFailIfNotExceptionInTheMap(array $map)
    {
        $this->expectException(\InvalidArgumentException::class);

        new ExceptionClassifier($map);
    }

    /**
     * @return array
     */
    public function wrongClassProvider()
    {
        return [
            'not exception' => [
                [
                    \LogicException::class => false,
                    \SplObjectStorage::class => true,
                ],
            ],
            'not string' => [
                [
                    \LogicException::class => false,
                    1 => true,
                ],
            ],
            'class not exists' => [
                [
                    \LogicException::class => false,
                    'WrongClass' => true,
                ],
            ],
        ];
    }
}
