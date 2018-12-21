<?php

namespace Evaneos\Enqueue\Metric;

use Beberlei\Metrics\Collector\StatsD;

class StatsDMetricService implements MetricService
{
    /**
     * @var StatsD
     */
    private $collector;

    public function __construct(StatsD $collector)
    {
        $this->collector = $collector;
    }

    /**
     * {@inheritdoc}
     */
    public function timing($stat, $time, array $tags = [])
    {
        $this->collector->timing($stat, $time);
        $this->collector->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function increment($stat, array $tags = [])
    {
        $this->collector->increment($stat);
        $this->collector->flush();
    }
}
