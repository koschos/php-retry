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
     * @param array $throwableMap
     */
    public function __construct(array $throwableMap)
    {
        if ($throwableMap === []) {
            $throwableMap = [\Throwable::class => true];
        }

        $this->valueSet = new ExceptionValueSortedSet();

        foreach ($throwableMap as $throwableClass => $value) {
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