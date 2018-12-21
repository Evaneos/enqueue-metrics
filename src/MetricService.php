<?php

namespace Evaneos\Enqueue\Metric;

interface MetricService
{
    /**
     * @param string $stat
     * @param float $time Microseconds needed
     * @param array $tags
     */
    public function timing($stat, $time, array $tags = []);

    /**
     * @param string $stat
     * @param array  $tags
     */
    public function increment($stat, array $tags = []);
}
