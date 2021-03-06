<?php

namespace Brick\Cron\Logger;

use Brick\Cron\Logger;

/**
 * Logger implementation that echoes to the terminal.
 */
class EchoLogger implements Logger
{
    /**
     * {@inheritdoc}
     */
    public function log($message)
    {
        echo $message;
    }
}
