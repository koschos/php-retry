<?php

namespace Koschos\Classify;

use Koschos\Classify\ExceptionType\ExceptionValueSortedSet;

/**
 * Class ExceptionClassifier
 */
class ExceptionClassifier
{
    /**
     * @var ExceptionValueSortedSet
     */
    private $valueSet;

    /**
     * ExceptionClassifier constructor.
     *
     * @param array $exceptionMap
     */
    public function __construct(array $exceptionMap)
    {
        if ($exceptionMap === []) {
            $exceptionMap = [\Exception::class => true];
        }

        $this->valueSet = new ExceptionValueSortedSet();

        foreach ($exceptionMap as $throwableClass => $value) {
            $this->valueSet->put($throwableClass, $value);
        }
    }

    /**
     * @param \Exception $exception
     *
     * @return bool|null
     */
    public function classify(\Exception $exception)
    {
        return $this->valueSet->get($exception);
    }
}