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
     * @var bool
     */
    private $defaultValue;

    /**
     * ExceptionClassifier constructor.
     *
     * @param array $exceptionMap
     * @param bool  $defaultValue
     */
    public function __construct(array $exceptionMap = [], $defaultValue = true)
    {
        if ($exceptionMap === []) {
            $exceptionMap = [\Exception::class => true];
        }

        $this->defaultValue = $defaultValue;
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
        $value = $this->valueSet->get($exception);

        if ($value === null) {
            $value = $this->defaultValue;
        }

        return $value;
    }
}