<?php

namespace Koschos\Classify\ExceptionType;

/**
 * Class ExceptionTypeValue
 */
class ExceptionTypeValue
{
    /**
     * @var ExceptionType
     */
    private $exceptionType;

    /**
     * @var bool
     */
    private $value;

    /**
     * ExceptionTypeValue constructor.
     *
     * @param ExceptionType $exceptionType
     * @param bool          $value
     */
    public function __construct(ExceptionType $exceptionType, $value)
    {
        $this->exceptionType = $exceptionType;
        $this->value = (bool) $value;
    }

    /**
     * @param \Exception $exception
     *
     * @return bool|null
     */
    public function getValue(\Exception $exception)
    {
        if ($this->exceptionType->is($exception)) {
            return $this->value;
        }

        return null;
    }
}