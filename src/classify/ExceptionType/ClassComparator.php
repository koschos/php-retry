<?php

namespace Koschos\Classify\ExceptionType;

/**
 * Class ClassComparator
 */
class ClassComparator
{
    public function compare($class1, $class2)
    {
        if ($class1 === $class2) {
            return 0;
        }

        if (is_a($class1, $class2, true)) {
            return -1;
        }

        if (is_a($class2, $class1, true)) {
            return 1;
        }

        return 0;
    }

    public function __invoke($class1, $class2)
    {
        return $this->compare($class1, $class2);
    }
}
