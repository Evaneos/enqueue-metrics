<?php

namespace Evaneos\Enqueue\Metric;

use Beberlei\Metrics\Collector\DogStatsD;

class DogStatsDMetricService implements MetricService
{
    /**
     * @var DogStatsD
     */
    private $collector;

    public function __construct(DogStatsD $collector)
    {
        $this->collector = $collector;
    }

    /**
     * {@inheritdoc}
     */
    public function timing($stat, $time, array $tags = [])
    {
        $this->collector->timing($stat, $time, $tags);
        $this->collector->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function increment($stat, array $tags = [])
    {
        $this->collector->increment($stat, $tags);
        $this->collector->flush();
    }
}
