<?php

namespace Brick\Validation;

/**
 * A validation result, containing zero or more failures.
 */
class ValidationResult
{
    /**
     * The validation failures. No failures means successful validation.
     *
     * @var ValidationFailure[]
     */
    private $failures = [];

    /**
     * @param string $messageKey The message key.
     *
     * @return static This instance for chaining.
     */
    public function addFailure($messageKey)
    {
        $this->failures[] = new ValidationFailure($messageKey);

        return $this;
    }

    /**
     * Returns whether this result has failures.
     *
     * @return boolean
     */
    public function hasFailures()
    {
        return count($this->failures) != 0;
    }

    /**
     * Returns the validation failures.
     *
     * @return ValidationFailure[]
     */
    public function getFailures()
    {
        return $this->failures;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return count($this->failures) == 0;
    }
}
