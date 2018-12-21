<?php

namespace Evaneos\Enqueue\Metric;

interface Clock
{
    /**
     * @return float
     */
    public function timestampInMs();
}
