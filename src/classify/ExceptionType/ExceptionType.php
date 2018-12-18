<?php

namespace Koschos\Classify\ExceptionType;

/**
 * Class ExceptionType
 */
class ExceptionType
{
    /**
     * @var string
     */
    private $exceptionClass;

    /**
     * ExceptionType constructor.
     *
     * @param string $exceptionClass
     */
    public function __construct($exceptionClass)
    {
        $this->validateClass($exceptionClass);

        $this->exceptionClass = $exceptionClass;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->exceptionClass;
    }


    /**
     * @param \Exception $exception
     *
     * @return bool
     *
     */
    public function is(\Exception $exception)
    {
        return is_a($exception, $this->exceptionClass);
    }

    /**
     * @param string $exceptionClass
     */
    private function validateClass($exceptionClass)
    {
        if (!is_string($exceptionClass)) {
            throw new \InvalidArgumentException(sprintf('Class name must be string, but %s type given', gettype($exceptionClass)));
        }

        if (!class_exists($exceptionClass)) {
            throw new \InvalidArgumentException(sprintf('Class %s does not exist', $exceptionClass));
        }

        if (!is_a($exceptionClass, \Exception::class, true)) {
            throw new \InvalidArgumentException(sprintf('Class %s is not expected \Exception type', $exceptionClass));
        }
    }
}