<?php

namespace Koschos\Classify\ExceptionType;

/**
 * Class ExceptionValueSortedSet
 */
class ExceptionValueSortedSet
{
    /**
     * @var ExceptionTypeValue[]
     */
    private $set = [];

    /**
     * @var array
     */
    private $sortedKeySet = [];

    /**
     * @var ClassComparator
     */
    private $comparator;

    /**
     * ExceptionValueSortedSet constructor.
     */
    public function __construct()
    {
        $this->comparator = new ClassComparator();
    }

    /**
     * @param string $exceptionClass
     *
     * @param bool $value
     * @return bool
     */
    public function put($exceptionClass, $value)
    {
        $exceptionType = new ExceptionType($exceptionClass);

        if (array_key_exists($exceptionType->getType(), $this->set)) {
            return false;
        }

        $this->set[$exceptionType->getType()] = new ExceptionTypeValue($exceptionType, $value);
        $this->sortedKeySet[] = $exceptionType->getType();

        usort($this->sortedKeySet, $this->comparator);

        return true;
    }

    /**
     * @param \Exception $exception
     *
     * @return bool|null
     */
    public function get(\Exception $exception)
    {
        foreach ($this->sortedKeySet as $key) {
            $typeValue = $this->set[$key];
            $value = $typeValue->getValue($exception);

            if ($value !== null) {
                return $value;
            }
        }

        return null;
    }
}