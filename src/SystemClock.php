<?php

namespace Evaneos\Enqueue\Metric;

class SystemClock implements Clock
{
    /**
     * {@inheritdoc}
     */
    public function timestampInMs()
    {
        return microtime(true);
    }
}
