<?php

namespace Brick\Json;

/**
 * Exception thrown when an error occurs during encoding/decoding in JSON format.
 */
class JsonException extends \RuntimeException
{
    /**
     * @param \Exception $e
     *
     * @return JsonException
     */
    public static function wrap(\Exception $e)
    {
        return new static($e->getMessage(), $e->getCode(), $e);
    }
}
