<?php

namespace Brick\Json;

use Brick\Error\ErrorCatcher;

/**
 * Common functionality for JsonEncoder and JsonDecoder.
 */
abstract class Common
{
    /**
     * @var \Brick\Error\ErrorCatcher
     */
    protected $errorHandler;

    /**
     * The maximum encoding depth.
     *
     * @var integer
     */
    protected $maxDepth = 512;

    /**
     * The encoding options bitmask.
     *
     * @var integer
     */
    protected $options = 0;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->errorHandler = new ErrorCatcher(function(\ErrorException $e) {
            throw JsonException::wrap($e);
        });
    }

    /**
     * Sets the max depth. Defaults to `512`.
     *
     * @param integer $depth
     *
     * @return static
     */
    public function setMaxDepth($depth)
    {
        $this->maxDepth = (int) $depth;

        return $this;
    }

    /**
     * Executes the given function and throws an exception if an error has occurred.
     *
     * @param \Closure $function The function to execute.
     *
     * @return mixed The value returned by the function.
     *
     * @throws JsonException If an error occurs.
     */
    protected function execute(\Closure $function)
    {
        $result = $this->errorHandler->swallow(E_ALL, $function);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException(json_last_error_msg(), json_last_error());
        }

        return $result;
    }

    /**
     * Sets or resets a bitmask option.
     *
     * @param integer $option
     * @param boolean $boolean
     *
     * @return static
     */
    protected function setOption($option, $boolean)
    {
        if ($boolean) {
            $this->options |= $option;
        } else {
            $this->options &= ~ $option;
        }

        return $this;
    }
}
